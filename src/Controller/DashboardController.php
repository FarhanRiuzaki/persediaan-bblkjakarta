<?php

namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class DashboardController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        if (php_sapi_name() !== 'cli') {
            // $this->Auth->allow(['index']);
        }
        $this->Auth->allow(['print']);

    }

    public function RequestIOPR($print = null)
    {
        $this->loadModel('Products');
        $this->loadModel('InternalOrders');
        $this->loadModel('PurchaseSubmisions');
        $this->loadModel('PurchaseRequestsDetails');
        $this->loadModel('ExportProductsDetails');
        $this->loadModel('ExportProducts');
        $this->loadModel('PurchaseRequests');
        // dd($print);
        // dd($this->userData->user_group_id);
        $whereStok  = '';
        $whereType  = ''; //type permintaan pembelian
        if ($this->userData->user_group_id == 3) {
            $whereIO = [
                'InternalOrders.status != ' => 1,
                'institute_id' => $this->userData->institute_id
            ];

            $wherePR = [
                'institute_id' => $this->userData->institute_id
            ];
            $whereApprovePR = [
                'institute_id' => $this->userData->institute_id
            ];
        } else if ($this->userData->user_group_id == 7) {
            $whereIO = [
                'InternalOrders.status' => 3,
                'institute_id' => $this->userData->institute_id
            ];

            $wherePR = [
                'institute_id' => $this->userData->institute_id
            ];
            $whereApprovePR = [
                'institute_id' => $this->userData->institute_id,
                'PurchaseRequests.status NOT IN ' => [0,1],
            ];
        } else {
            
            $whereIO = [
                'InternalOrders.status NOT IN ' => [2, 3, 4],
            ];
            if($this->userData->user_group_id == 4){ /* ATK */
                $whereIO = [
                    'InternalOrders.status NOT IN ' => [2,3,4],
                    'InternalOrders.type' => '1'
                ];
                $whereStok = 'SubCategories.category_id IN (1,3,4)';
                $whereType  = 'PurchaseRequests.type = 1';
            }elseif($this->userData->user_group_id == 6){ /* Reagen */
                $whereIO = [
                    'InternalOrders.status NOT IN ' => [2,3,4],
                    'InternalOrders.type' => '2'
                ];
                $whereStok = 'SubCategories.category_id NOT IN (1,3,4)';
                $whereType  = 'PurchaseRequests.type = 2';

            }

            $wherePR = [
                'PurchaseRequests.status NOT IN ' => [1,2,3],
            ];
            $whereApprovePR = [
                'PurchaseRequests.status NOT IN ' => [0,1],
            ];
        }
        $this->loadModel('Categories');
        $this->loadModel('Stocks');
        $categories = $this->Categories->find();
        $awalStocks = $this->Stocks->find();
        $awalStocks2 = $this->Stocks->find();
        $inStocks = $this->Stocks->find();
        $outStocks = $this->Stocks->find();
        $inStocks2 = $this->Stocks->find();
        $outStocks2 = $this->Stocks->find();
        $akhirStocks = $this->Stocks->find();
        $akhirStocks2 = $this->Stocks->find();

        $now = Time::now();
        $now->month;

        $fmonth = $now->month;
        $year = $now->year;
        if($fmonth == 1) {
            $year -= 1;
            $fmonth = 13;
        }
        $fmonth -= 1;
        $fdate = $year . '-' . $fmonth . '-31';
        $date = date('Y-m-d', strtotime($fdate));

        $stok = $this->Products->find('all', [
            'contain' => [
                'SubCategories.Categories'
            ]
        ])
        ->where([
            $whereStok
        ])
        ->select([
            'category'  => 'Categories.name',
            'product_id' => 'Products.id',
            'unit' => 'Products.unit',
            'product_code' => 'Products.code',
            'min_unit' => 'Products.min_unit',
            'product_name' => 'Products.name',
            'saldo_akhir' => '(IFNULL((' . $inStocks->select(
                [
                    'SUM' => $inStocks->func()->sum('qty')
                ]
                    )->where([
                        'type = "IN"',
                        'product_id = Products.id',
                    ]) . '), 0))-(IFNULL((' . $outStocks->select(
                        [
                            'SUM' => $outStocks->func()->sum('qty')
                        ]
                            )->where([
                                'type = "OUT"',
                                'product_id = Products.id',
                            ]) . '), 0))'])->order(['product_name ASC']);
        

        $stokKurang = [];
        foreach($stok as $key => $val){
            $stok = (int) $val['saldo_akhir'];
            if($stok <= $val['min_unit']){

                $export_detail = $this->ExportProductsDetails->find('all')->where(['status = 0', 'product_id'=>$val['product_id']])->first();
                
                if(empty($export_detail)){
                    $stokKurang[$key]['id']         = $val['product_id'];
                    $stokKurang[$key]['kode']       = $val['product_code'];
                    $stokKurang[$key]['nama']       = $val['product_name'];
                    $stokKurang[$key]['category']   = $val['category'];
                    $stokKurang[$key]['qty']        = $val['saldo_akhir'];
                    $stokKurang[$key]['saldo_akhir'] = $val['saldo_akhir'] . ' ' . $val['unit'];
                }
            
            }
        }
        // die();
        // dd($stokKurang);
        $internalOrders = $this->InternalOrders->find('all', [
            'conditions' => [
                $whereIO
            ],
            'contain' => [
                'Institutes'
            ]
        ]);
        $pendingApproval = $this->InternalOrders->find('all', [
            'conditions' => [
                'InternalOrders.status NOT IN ' => [2, 0, 4],
            ],
            'contain' => [
                'Institutes'
            ]
        ])->order('InternalOrders.id DESC');

        if ($this->userData->user_group_id == 3) {
            $internalOrders->order('InternalOrders.id DESC');
        }else{
            $internalOrders->order('InternalOrders.id ASC');

        }       

        $cek = $this->userData->user_group_id;
        $where = '';
        if($cek == 4){ /*gudang ATK*/
            $where = 'type = 1';
        }elseif($cek == 6){  /*gudang Reagen*/
            $where = 'type = 2';
        }

        $conn = ConnectionManager::get('default');
        $stmt = $conn->execute("SELECT
        *
        From(
        SELECT
        Pro.name,
        Pro.unit,
        SUM(CASE 
                WHEN type = 'IN' THEN
                    stocks.qty
                else 
                    0
            END)
            - 
            SUM(CASE 
                WHEN type = 'OUT' THEN
                    stocks.qty
                else
                    0
            END) as stok,
            SUM(CASE 
                WHEN type = 'IN' THEN
                    stocks.qty
                else 
                    0
            END)
            - 
            (SUM(CASE 
                WHEN type = 'OUT' THEN
                    stocks.qty
                else
                    0
            END) + (SELECT
            SUM(qty) as permintaan
        FROM
            internal_orders_details 
            INNER JOIN internal_orders on internal_orders.id = internal_orders_details.internal_order_id
            WHERE internal_orders.status = 0 AND internal_orders_details.product_id = Pro.id
            GROUP BY product_id) )as stok_permintaan,
            SUM(CASE 
                WHEN type = 'IN' THEN
                    stocks.qty
                else 
                    0
            END) as stok_in,
            SUM(CASE 
                WHEN type = 'OUT' THEN
                    stocks.qty
                else 
                    0
            END)
            as stok_out,
            (SELECT
            SUM(qty) as permintaan
        FROM
            internal_orders_details 
            INNER JOIN internal_orders on internal_orders.id = internal_orders_details.internal_order_id
            WHERE internal_orders.status = 0 AND internal_orders_details.product_id = Pro.id
            GROUP BY product_id) as permintaan
        FROM
            products Pro
            LEFT JOIN stocks ON stocks.product_id = Pro.id
            GROUP BY Pro.id
            ) as t1
            WHERE permintaan is not NULL
            having stok_permintaan < 0");
        
        // Read all rows.
        $permintaan = $stmt->fetchAll('assoc');
        
        //approval permintaan pembelian 
        $approvalPR = $this->PurchaseRequests->find('all', [
            'conditions' => [
                $whereApprovePR
            ],
            'contain' => [
                'Institutes'
            ]
        ])->order('PurchaseRequests.id DESC');
        // dd($approvalPR);
        //end approval

        $purchaseRequests = $this->PurchaseRequests->find('all', [
            'conditions' => [
                $wherePR
            ],
            'contain' => [
                'Institutes'
            ]
        ])->order('PurchaseRequests.id DESC');

        $purchaseRequestsDetails = $this->PurchaseRequestsDetails->find('all', [
            'conditions' => [
                'PurchaseRequestsDetails.status' => 0,
                $whereType
            ],
            'contain' => [
                'Products',
                'PurchaseRequests.Institutes'
            ]
        ]);
        if($cek == 5){  /*ULP*/
            $status = 'PurchaseSubmisions.status NOT IN(0,2)';
        }else{
            $status = 'PurchaseSubmisions.status = 0';
        }

        $purchaseSubmisions = $this->PurchaseSubmisions->find('all', [
            'contain' => [
                'CreatedUsers'
            ],
            'conditions' => [
                $status
            ]
        ])->order('PurchaseSubmisions.id ASC');


        // insert into export product
        if($print == 'print'){

            // Prior to 3.6.0
            $exportProduct  = $this->ExportProducts->newEntity();
            $data           = $this->request->getData();

            if(!empty($stokKurang)){
                foreach ($stokKurang as $key => $val) {
                    // dd($val['id']);
                    $data['export_products_details'][$key]['product_id']    = $val['id'];
                    $data['export_products_details'][$key]['qty']           = $val['qty'];
                    $data['export_products_details'][$key]['status']        = '0';
                }
            }

            $exportProduct  = $this->ExportProducts->patchEntity($exportProduct, $data);
            // dd($exportProduct);
            $this->ExportProducts->save($exportProduct);

        }

        $this->set(compact('internalOrders','approvalPR','purchaseRequestsDetails','purchaseSubmisions', 'purchaseRequests', 'permintaan', 'stokKurang','categories','pendingApproval'));
    }

    public function index()
    {
        $titleModule  = 'Dashboard';
        if ($this->userData->user_group_id == 3) { //Ruangan
            $this->RequestIOPR();
            $this->set(compact('titleModule'));
            $this->render('institute');
        } elseif ($this->userData->user_group_id == 5) { // ULP
            $this->RequestIOPR();
            $this->set(compact('titleModule'));
            $this->render('ulp');
        }elseif ($this->userData->user_group_id == 7) { // Atasan 
            $this->RequestIOPR();
            $this->set(compact('titleModule'));
            $this->render('kr');
        }elseif ($this->userData->user_group_id == 8) { //PPK
            $this->RequestIOPR();
            $this->set(compact('titleModule'));
            $this->render('ppk');
        }else {
            $this->RequestIOPR();

            // HIGHCHART
            if ($this->request->is('ajax')) {
                $this->loadModel('Products');
                $this->loadModel('Stocks');

                $now = Time::now();
                $select = [];
                $month = $this->Utilities->monthArray();
                $year = date('Y');
                $mod = 0;
                $thisMonth = $now->month;
                $selisih = 12 - $thisMonth;

                for ($a = 1; $a <= $thisMonth; $a++) {
                    $select[$month[$a] . '__in'] = $this->getStock($a, $year, 'IN');
                    $select[$month[$a] . '__out'] = $this->getStock($a, $year, 'OUT');
                    $select[$month[$a] . '__saldo'] = $this->getStockAkhir($year, $a);

                    if ($a == 1) {
                        $select[$month[$a] . '__saldo_awal'] = $this->getSaldoAwalJanuari($year);
                    } else {
                        $select[$month[$a] . '__saldo_awal'] = $this->getSaldoAwal($year, $a);
                    }
                }

                if ($selisih != 0) {
                    for ($a = $a; $a <= 12; $a++) {
                        $select[$month[$a] . '__in'] = 0;
                        $select[$month[$a] . '__out'] = 0;
                        $select[$month[$a] . '__saldo'] = 0;
                        $select[$month[$a] . '__saldo_awal'] = 0;
                    }
                }

                $connection = ConnectionManager::get('default');
                $query = $connection->newQuery()
                        ->select($select)
                        ->execute()
                        ->fetchAll('assoc');

                $datas = [];
                foreach ($query as $skey => $fetch) {
                    foreach ($fetch as $key => $r) {
                        $explode = explode('__', $key);
                        $datas[$explode[0]][$explode[1]] = $r;
                    }
                }

                $this->set(compact('datas'));
                $this->set('_serialize', ['datas']);
            }

            // 10 DAFTAR TRANSAKSI TERAKHIR BARANG MASUK
            $this->loadModel('ReceiptGrants');
            $this->loadModel('ReceiptOthers');
            $this->loadModel('ReceiptPurchases');
            $this->loadModel('ReceiptReclarifications');
            $this->loadModel('ReceiptTransfers');
            $this->loadModel('ItemReceipts');

            $ReceiptGrants = $this->ReceiptGrants->find()->select(['code', 'date' => 'date', 'type' => '("Penerimaan Barang Hibah")']);
            $ReceiptOthers = $this->ReceiptOthers->find()->select(['code', 'date' => 'date', 'type' => '("Penerimaan Barang Lainnya")']);
            $ReceiptPurchases = $this->ReceiptPurchases->find()->select(['code', 'date' => 'date', 'type' => '("Penerimaan Barang Pembelian")']);
            $ReceiptReclarifications = $this->ReceiptReclarifications->find()->select(['code', 'date' => 'date', 'type' => '("Penerimaan Barang Reklarifikasi Masuk")']);
            $ReceiptTransfers = $this->ReceiptTransfers->find()->select(['code', 'date' => 'date', 'type' => '("Penerimaan Barang Transfer Antar Lembaga")']);
            $ItemReceipts = $this->ItemReceipts->find()->select(['code', 'date' => 'date', 'category']);
            
            $in_unions = $ReceiptGrants->union($ReceiptOthers)
                        ->union($ReceiptPurchases)
                        ->union($ReceiptReclarifications)
                        ->union($ReceiptTransfers)
                        ->union($ItemReceipts)
                        ->epilog('ORDER BY date DESC LIMIT 10');

            // 10 DAFTAR TRANSAKSI TERAKHIR BARANG KELUAR
            $this->loadModel('ExpendituresGrants');
            $this->loadModel('ExpendituresOthers');
            $this->loadModel('ExpendituresDistributions');
            $this->loadModel('ExpendituresReclarifications');
            $this->loadModel('ExpendituresTransfers');
            $this->loadModel('ItemHandovers');

            $ExpendituresGrants = $this->ExpendituresGrants->find()->select(['code', 'date' => 'date', 'type' => '("Pengeluaran Barang Hibah")']);
            $ExpendituresOthers = $this->ExpendituresOthers->find()->select(['code', 'date' => 'date', 'type' => '("Pengeluaran Barang Lainnya")']);
            $ExpendituresDistributions = $this->ExpendituresDistributions->find()->select(['code', 'date' => 'date', 'type' => '("Distribusi Barang")']);
            $ExpendituresReclarifications = $this->ExpendituresReclarifications->find()->select(['code', 'date' => 'date', 'type' => '("Pengeluaran Reklarifikasi")']);
            $ExpendituresTransfers = $this->ExpendituresTransfers->find()->select(['code', 'date' => 'date', 'type' => '("Pengeluaran Barang Transfer Antar Lembaga")']);
            $ItemHandovers = $this->ItemHandovers->find()->select(['code', 'date' => 'date', 'category']);

            $out_unions = $ExpendituresGrants->union($ExpendituresOthers)
                        ->union($ExpendituresDistributions)
                        ->union($ExpendituresReclarifications)
                        ->union($ExpendituresTransfers)
                        ->union($ItemHandovers)
                        ->epilog('ORDER BY date DESC LIMIT 10');

            $this->set(compact('titleModule', 'in_unions', 'out_unions'));
        }
    }

    public function getSaldoAwalJanuari($year)
    {
        $InStocks = $this->Stocks->find();
        $OutStocks = $this->Stocks->find();

        $data = '(IFNULL((' . $InStocks->select([
            'SUM' => $InStocks->func()->sum('qty')
        ])->where([
            'type = "IN"',
            'YEAR(Stocks.date) < ' . $year . ''
        ]) . '), 0)) - (IFNULL((' . $OutStocks->select([
            'SUM' => $OutStocks->func()->sum('qty')
        ])->where([
            'type = "OUT"',
            'YEAR(Stocks.date) < ' . $year . ''
        ]) . '), 0))';

        return $data;
    }

    public function getSaldoAwal($year, $month)
    {
        $InStocks = $this->Stocks->find();
        $OutStocks = $this->Stocks->find();

        $data = '(IFNULL((' . $InStocks->select([
            'SUM' => $InStocks->func()->sum('qty')
        ])->where([
            'type = "IN"',
            'OR' => [
                'YEAR(Stocks.date) < ' . $year,
                'AND' => [
                    'YEAR(Stocks.date) = ' . $year,
                    'MONTH(Stocks.date) < ' . $month
                ]
            ]
        ]) . '), 0)) - (IFNULL((' . $OutStocks->select([
            'SUM' => $OutStocks->func()->sum('qty')
        ])->where([
            'type = "OUT"',
            'OR' => [
                'YEAR(Stocks.date) < ' . $year,
                'AND' => [
                    'YEAR(Stocks.date) = ' . $year,
                    'MONTH(Stocks.date) < ' . $month
                ]
            ]
        ]) . '), 0))';

        return $data;
    }

    public function getStock($month, $year, $type = 'IN')
    {
        $InStocks = $this->Stocks->find();
        $data = '(IFNULL((' . $InStocks->select([
            'SUM' => $InStocks->func()->sum('qty')
        ])->where([
            'type = "' . $type . '"',
            'MONTH(Stocks.date) = ' . $month . '',
            'YEAR(Stocks.date) = ' . $year . ''
        ]) . '), 0))';

        return $data;
    }

    public function getStockAkhir($year, $month)
    {
        $akhirStocks = $this->Stocks->find();
        $akhirStocks2 = $this->Stocks->find();
        $inStocks2 = $this->Stocks->find();
        $outStocks2 = $this->Stocks->find();

        $data = '((IFNULL((' . $akhirStocks->select([
            'SUM' => $akhirStocks->func()->sum('qty')
        ])->where([
            'type = "IN"',
            'product_id = product_id',
            'OR' => [
                'YEAR(Stocks.date) < ' . $year,
                'AND' => [
                    'YEAR(Stocks.date) <= ' . $year,
                    'MONTH(Stocks.date) < ' . $month
                ]
            ],
        ]) . '), 0)) - (IFNULL((' . $akhirStocks2->select([
            'SUM' => $akhirStocks2->func()->sum('qty')
        ])->where([
            'type = "OUT"',
            'product_id = product_id',
            'OR' => [
                'YEAR(Stocks.date) < ' . $year,
                'AND' => [
                    'YEAR(Stocks.date) <= ' . $year,
                    'MONTH(Stocks.date) < ' . $month
                ]
            ],
        ]) . '), 0))) + (IFNULL((' . $inStocks2->select([
            'SUM' => $inStocks2->func()->sum('qty')
        ])->where([
            'YEAR(Stocks.date) = "' . $year . '"',
            'MONTH(Stocks.date) = "' . $month . '"',
            'type = "IN"',
            'product_id = product_id',
        ]) . '), 0)) - (IFNULL((' . $outStocks2->select([
            'SUM' => $outStocks2->func()->sum('qty')
        ])->where([
            'YEAR(Stocks.date) = "' . $year . '"',
            'MONTH(Stocks.date) = "' . $month . '"',
            'type = "OUT"',
            'product_id = product_id',
        ]) . '), 0))';

        return $data;
    }

    public function print($id = null)
    {
        $this->set(compact('titleModule'));
        $this->RequestIOPR('print');
        // dd($stokKurang);
        Configure::write('CakePdf', [
            'engine' => [
                'className' => 'CakePdf.WkHtmlToPdf',
                'binary' => '/usr/local/bin/wkhtmltopdf',
                // 'className' => 'CakePdf.WkHtmlToPdf', 'binary' => 'C:\wkhtmltopdf\bin\wkhtmltopdf.exe',
                'options' => [
                    'print-media-type' => false,
                    'outline' => true,
                    'dpi' => 96,
                ],
            ],
            'margin' => [
                'bottom' => 15,
                'left' => 15,
                'right' => 15,
                'top' => 15
            ],

            'pageSize' => 'A4',
            'download' => false
        ]);
        $this->RequestHandler->renderAs($this, 'pdf');
    }
}
