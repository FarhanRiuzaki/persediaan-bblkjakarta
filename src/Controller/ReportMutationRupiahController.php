<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\I18n\Time;

/**
 * ReportMutationRupiahController
 */
class ReportMutationRupiahController extends AppController
{
    public function index()
    {
        $this->loadModel('Products');
        $this->loadModel('Stocks');
        $awalStocks = $this->Stocks->find();
        $awalStocks2 = $this->Stocks->find();
        $inStocks = $this->Stocks->find();
        $outStocks = $this->Stocks->find();
        $inStocks2 = $this->Stocks->find();
        $outStocks2 = $this->Stocks->find();
        $akhirStocks = $this->Stocks->find();
        $akhirStocks2 = $this->Stocks->find();
        $priceStocks = $this->Stocks->find();

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

        $whereCategories = '';
        $this->loadModel('Categories');
        $categories = $this->Categories->find('list')->order(['name' => 'ASC']);
        $startDate = date('Y-m-d', strtotime(($this->request->query('start')) ? $this->request->query('start') : '2017-12-01' ));
        $endDate = date('Y-m-t', strtotime(($this->request->query('end')) ? $this->request->query('end') : date('Y-m-t') ));
        $endDateSaldo = date("Y-m-t", strtotime("-1 months", strtotime($startDate)));

        $keywordCategory = $this->request->query('keywordCategory');
        if($keywordCategory != '' && $keywordCategory != 'undefined') {
            $whereCategories = ['SubCategories.category_id' => $keywordCategory];
        }

        $results = $this->Products->find('all', [
            'conditions' => [
                $whereCategories
            ],
            'contain' => [
                'SubCategories',
            ]
        ])->select([
            'product_id' => 'Products.id',
            'product_code' => 'Products.code',
            'product_name' => 'Products.name',
            'product_price' => $priceStocks->select([
                'price'
                ])->where([
                    'type = "IN"',
                    'product_id = Products.id',
                    '(Stocks.date BETWEEN "2017-12-01" AND "'. $endDate .'")'
                ])->order(['date' => 'DESC'])->limit(1),
            'saldo_akhir' => '((IFNULL((' . $akhirStocks->select([
                'SUM' => $akhirStocks->func()->sum('qty')
                ])->where([
                    'type = "IN"',
                    'product_id = Products.id',
                    '(Stocks.date BETWEEN "2017-12-01" AND "'. $endDateSaldo .'")'
                ]) . '), 0)) - (IFNULL((' . $akhirStocks2->select([
                    'SUM' => $akhirStocks2->func()->sum('qty')
                ])->where([
                    'type = "OUT"',
                    'product_id = Products.id',
                    '(Stocks.date BETWEEN "2017-12-01" AND "'. $endDateSaldo .'")'
                ]) . '), 0))) + (IFNULL((' . $inStocks2->select([
                    'SUM' => $inStocks2->func()->sum('qty')
                ])->where([
                    '(Stocks.date BETWEEN "'. $startDate .'" AND "'. $endDate .'")',
                    'type = "IN"',
                    'product_id = Products.id',
                ]) . '), 0)) - (IFNULL((' . $outStocks2->select([
                    'SUM' => $outStocks2->func()->sum('qty')
                ])->where([
                    '(Stocks.date BETWEEN "'. $startDate .'" AND "'. $endDate .'")',
                    'type = "OUT"',
                    'product_id = Products.id',
                ]) . '), 0))'
        ]);

        $request_print = $this->request->query('print');
        $titleModule = 'Monitoring Rupiah';
        $this->set(compact('titleModule', 'results', 'periode', 'start', 'end', 'categories', 'startDate', 'endDate', 'keywordCategory'));
        if (empty($request_print)) {
            $titlesubModule = 'Filter ' . $titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => $titlesubModule
            ];
            $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule'));
        } else {
            if ($request_print == 'pdf') {
                Configure::write('CakePdf', [
                    'engine' => [
                        'className' => 'CakePdf.WkHtmlToPdf', 'binary' => '/usr/local/bin/wkhtmltopdf',
                        'options' => [
                            'print-media-type' => false,
                            'outline' => true,
                            'dpi' => 96
                        ],
                    ],
                    'margin' => [
                        'bottom' => 30,
                        'left' => 30,
                        'right' => 30,
                        'top' => 30
                    ],
                    'pageSize' => 'A3',
                    'download' => false
                ]);
                $this->RequestHandler->renderAs($this, 'pdf');
            }
        }
    }
}
