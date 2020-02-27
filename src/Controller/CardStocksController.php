<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * Provinces Controller
 *
 * @property \App\Model\Table\ProvincesTable $Provinces
 *
 * @method \App\Model\Entity\province[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CardStocksController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['print']);
    }

    function beforeFilter(\Cake\Event\Event $event){
        parent::beforeFilter($event);
    
        $this->Security->config('validatePost',false);
    
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $titleModule = 'Kartu Stok';
        $titlesubModule = 'Pencarian Detail Produk';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule'));
    }

    public function print($product_id, $startDate, $endDate)
    {
        $this->loadModel('Stocks');
        $this->loadModel('Products');

        $product = $this->Products->get($product_id);
        $startDate = date('Y-m-d', strtotime($startDate));
        $startYear = date('Y', strtotime($startDate)) -1;
        $endDate = date('Y-m-t', strtotime($endDate));
        $endDateSaldo = date("Y-m-t", strtotime("-1 months", strtotime($startDate)));
        // dd($endDateSaldo);
        $saldoAwals = $this->Stocks->find('all', [
            'conditions' => [
                'product_id' => $product_id,
                'date BETWEEN "2017-12-01" AND "'. $endDateSaldo .'"',
            ],
            'order' => 'date asc',
            'limit' => 1
        ])->select([
            'date' => 'DATE("'.$startYear.'-12-31")',
            'price' => 'Stocks.price',
            'price' => 'Stocks.price',
            'saldo_awal' => 'IFNULL(
                                    (
                                        SELECT
                                        (
                                            SUM(qty)
                                        ) AS `SUM`
                                        FROM
                                        stocks inStocks
                                        WHERE
                                        (
                                            inStocks.product_id = Stocks.product_id
                                            AND inStocks.type = "IN"
                                            AND date BETWEEN "2017-12-01" AND "'. $endDateSaldo .'"
                                        )
                                    ),
                                    0
                                    ) -
                                    IFNULL(
                                    (
                                        SELECT
                                        (
                                            SUM(qty)
                                        ) AS `SUM`
                                        FROM
                                        stocks outStocks
                                        WHERE
                                        (
                                            outStocks.product_id = Stocks.product_id
                                            AND outStocks.type = "OUT"
                                            AND date BETWEEN "2017-12-01" AND "'. $endDateSaldo .'"
                                        )
                                    ),
                                0)'
        ]);
        // dd($saldoAwals);
        $saldoAwal = [];
        foreach ($saldoAwals as $key => $val) {
            $saldoAwal['date'] = $val->date;
            $saldoAwal['price'] = $val->price;
            $saldoAwal['saldo_awal'] = $val->saldo_awal;
        }
        // dd($saldoAwal);
        $stocks = $this->Stocks->find('all', [
            'conditions' => [
                'product_id' => $product_id,
                'date BETWEEN "'. $startDate .'" AND "'. $endDate .'"',
                'ref_table !=' => 'init_stocks_details'
            ],
            'order' => [
                'date' => 'ASC'
            ]
        ])->select([
            'date' => 'Stocks.date',
            'type' => 'Stocks.type',
            'qty' => 'Stocks.qty',
            'price' => 'Stocks.price',
            'uraian' => 'IF(
                            Stocks.ref_table = \'receipt_grants_details\',
                            (
                                SELECT
                                    CONCAT("Penerimaan Barang Hibah : ", receipt_grants.source)
                                FROM
                                receipt_grants_details
                                INNER JOIN receipt_grants ON receipt_grants.id = receipt_grants_details.receipt_grant_id
                                where
                                receipt_grants_details.id = Stocks.ref_id
                            ),
                            (
                                IF(
                                    Stocks.ref_table = \'expenditures_distributions_details\',
                                    (
                                        SELECT
                                            CONCAT("Distribusi Barang : ", institutes.name )
                                        FROM
                                            expenditures_distributions_details
                                            INNER JOIN expenditures_distributions ON expenditures_distributions.id = expenditures_distributions_details.expenditures_distribution_id
                                            INNER JOIN institutes ON expenditures_distributions.institute_id = institutes.id
                                        where
                                            expenditures_distributions_details.id = Stocks.ref_id
                                    ),
                                        (
                                            IF(
                                                Stocks.ref_table = \'expenditures_grants_details\',
                                                (
                                                    SELECT
                                                        CONCAT("Pengeluaran Barang Hibah : ", expenditures_grants.given_to)
                                                    FROM
                                                        expenditures_grants_details
                                                        INNER JOIN expenditures_grants ON expenditures_grants.id = expenditures_grants_details.expenditures_grant_id
                                                    where
                                                        expenditures_grants_details.id = Stocks.ref_id
                                                ),
                                                (
                                                    IF(
                                                        Stocks.ref_table = \'expenditures_others_details\',
                                                        (
                                                            SELECT
                                                                CONCAT("Pengeluaran Barang Lainnya : ", expenditures_others.given_to)
                                                            FROM
                                                                expenditures_others_details
                                                                INNER JOIN expenditures_others ON expenditures_others.id = expenditures_others_details.expenditures_other_id
                                                            where
                                                                expenditures_others_details.id = Stocks.ref_id
                                                        ),
                                                        (
                                                            IF(
                                                                Stocks.ref_table = \'expenditures_reclarifications_details\',
                                                                (
                                                                    \'Barang Reklarifikasi\'
                                                                ),
                                                                (
                                                                    IF(
                                                                        Stocks.ref_table = \'expenditures_transfers_details\',
                                                                        (
                                                                            SELECT
                                                                                CONCAT("Pengeluaran Barang Transfer: ", institutes.name)
                                                                            FROM
                                                                                expenditures_transfers_details
                                                                                INNER JOIN expenditures_transfers ON expenditures_transfers.id = expenditures_transfers_details.expenditures_transfer_id
                                                                                INNER JOIN institutes ON expenditures_transfers.institution_id = institutes.id
                                                                            where
                                                                                expenditures_transfers_details.id = Stocks.ref_id
                                                                        ),
                                                                        (
                                                                            IF(
                                                                                Stocks.ref_table = \'receipt_others_details\',
                                                                                (
                                                                                    SELECT
                                                                                        CONCAT("Penerimaan Barang Lainnya: ", receipt_others.source)
                                                                                    FROM
                                                                                        receipt_others_details
                                                                                        INNER JOIN receipt_others ON receipt_others.id = receipt_others_details.receipt_other_id
                                                                                    where
                                                                                        receipt_others_details.id = Stocks.ref_id
                                                                                ),
                                                                                (
                                                                                    IF(
                                                                                        Stocks.ref_table = \'receipt_purchases_details\',
                                                                                        (
                                                                                            ifnull((select concat("Barang Pembelian : ", suppliers.name) from receipt_purchases_details inner join receipt_purchases on receipt_purchases.id = receipt_purchases_details.receipt_purchase_id inner join purchase_orders on purchase_orders.id = receipt_purchases.purchase_order_id inner join suppliers on suppliers.id = purchase_orders.supplier_id where receipt_purchases_details.id = Stocks.ref_id), concat("Barang Pembelian : ", "-"))
                                                                                        ),
                                                                                        (
                                                                                            IF(
                                                                                                Stocks.ref_table = \'receipt_reclarifications_details\',
                                                                                                (
                                                                                                    \'Barang Reklarifikasi Masuk\'
                                                                                                ),
                                                                                                (
                                                                                                    IF(
                                                                                                        Stocks.ref_table = \'receipt_transfers_details\',
                                                                                                        (
                                                                                                            SELECT
                                                                                                                CONCAT("Penerimaan Barang Transfer: ", institutes.name)
                                                                                                            FROM
                                                                                                                receipt_transfers_details
                                                                                                                INNER JOIN receipt_transfers ON receipt_transfers.id = receipt_transfers_details.receipt_transfer_id
                                                                                                                INNER JOIN institutes ON receipt_transfers.institution_id = institutes.id
                                                                                                            where
                                                                                                                receipt_transfers_details.id = Stocks.ref_id
                                                                                                        ),
                                                                                                        (
                                                                                                            IF(
                                                                                                                Stocks.ref_table = \'stock_opnames_details\',
                                                                                                                (
                                                                                                                    \'Stok Opname\'
                                                                                                                ),
                                                                                                                (
                                                                                                                    IF(
                                                                                                                        Stocks.ref_table = \'init_stocks_details\',
                                                                                                                        (
                                                                                                                            \'Stok Awal\'
                                                                                                                        ),
                                                                                                                        (
                                                                                                                            IF(
                                                                                                                                Stocks.ref_table = \'item_receipts_details\',
                                                                                                                                (
                                                                                                                                    \'Barang Masuk\'
                                                                                                                                ),
                                                                                                                                (
                                                                                                                                    IF(
                                                                                                                                        Stocks.ref_table = \'item_handovers_details\',
                                                                                                                                        (
                                                                                                                                            \'Barang Keluar\'
                                                                                                                                        ),
                                                                                                                                        \'-\'
                                                                                                                                    )
                                                                                                                                )
                                                                                                                            )
                                                                                                                        )
                                                                                                                    )
                                                                                                                )
                                                                                                            )
                                                                                                        )
                                                                                                    )
                                                                                                )
                                                                                            )
                                                                                        )
                                                                                    )
                                                                                )
                                                                            )
                                                                        )
                                                                    )
                                                                )
                                                            )
                                                        )
                                                    )
                                                )
                                            )
                                        )
                                    )
                                )
                                )'
        ]);
        // dd($stocks);
        Configure::write('CakePdf', [
            'engine' => 'CakePdf.DomPdf',
            'margin' => [
                'bottom' => 30,
                'left'   => 30,
                'right'  => 30,
                'top'    => 30
            ],
            'pageSize' => 'A4',
            'download' => false
        ]);
        $titleModule = 'Kartu Stok';
        $this->set(compact('titleModule', 'stocks', 'saldoAwal', 'product'));
        $this->viewBuilder()->setLayout(false);
        $this->RequestHandler->renderAs($this, 'pdf');
        $this->render('view');
        
    }
}
