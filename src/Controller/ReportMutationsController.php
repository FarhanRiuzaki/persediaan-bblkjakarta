<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\I18n\Time;
use Cake\Core\Configure;

/**
 * ReportMutationsController
 */
class ReportMutationsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['print']);

    }
    public function index()
    {
        $print = $this->request->query('print');

        $titleModule = 'Monitoring Stok Barang Gudang';
        $titlesubModule = 'Filter ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titlesubModule
        ];
        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule'));

        $this->loadModel('Products');
        $this->loadModel('Categories');
        $this->loadModel('Stocks');
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

        $keywordName = '';

        $keywordCategory = '';
        $categories = $this->Categories->find('list')->order(['name' => 'ASC']);

        $cek = $this->userData->user_group_id;
        $where = '';
        if($cek == 4){ /*gudang ATK*/
            $where = 'category_id NOT IN(2)';
            $keywordCategory = '!= 2';
        }elseif($cek == 6){  /*gudang Reagen*/
            $where = 'category_id = 2';
            $keywordCategory = '= 2';
        }

        if ($this->request->query('keywordName') && $this->request->query('keywordCategory')) {
            $keywordName = $this->request->query('keywordName');
            $keywordCategory = $this->request->query('keywordCategory');
            if($cek == 4){ /*gudang ATK*/
                $keywordCategory = '!= 2';
            }elseif($cek == 6){  /*gudang Reagen*/
                $keywordCategory = '= 2';
            }

            $results = $this->Products->find('all', [
                'conditions' => [
                    [
                        'category_id '.$keywordCategory.'',
                    ],
                    $where,
                    'or' => [
                        'Products.code LIKE' => '%' . $keywordName . '%',
                        'Products.no_catalog LIKE' => '%' . $keywordName . '%',
                        'Products.name LIKE' => '%' . $keywordName . '%',
                    ],
                ],
                'order' => [
                    'Products.name' => 'ASC'
                ],
                'contain' => [
                    'SubCategories'
                ]
            ])->select([
                'product_id' => 'Products.id',
                'product_code' => 'Products.code',
                'product_no_catalog' => 'Products.no_catalog',
                'unit' => 'Products.unit',
                'min_unit' => 'Products.min_unit',
                'product_name' => 'Products.name',
                'saldo_awal' => '(IFNULL((' . $awalStocks->select(
                    [
                        'SUM' => $awalStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0)) - (IFNULL((' . $awalStocks2->select(
                    [
                        'SUM' => $awalStocks2->func()->sum('qty')
                    ]
                        )->where([
                            'type = "OUT"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'in' => '(IFNULL((' . $inStocks->select(
                    [
                        'SUM' => $inStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'out' => '(IFNULL((' . $outStocks->select(
                    [
                        'SUM' => $outStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "OUT"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'saldo_akhir' => '(IFNULL((' . $akhirStocks->select(
                    [
                        'SUM' => $akhirStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0)) - (IFNULL((' . $akhirStocks2->select(
                            [
                                'SUM' => $akhirStocks2->func()->sum('qty')
                            ]
                                )->where([
                                    'type = "OUT"',
                                    'product_id = Products.id',
                                ]) . '), 0))'
            ]);
        } elseif ($this->request->query('keywordName')) {
            $keywordName = $this->request->query('keywordName');
            $results = $this->Products->find('all', [
                'conditions' => [
                    $where,
                    'or' => [
                        'Products.code LIKE' => '%' . $keywordName . '%',
                        'Products.no_catalog LIKE' => '%' . $keywordName . '%',
                        'Products.name LIKE' => '%' . $keywordName . '%',
                    ],
                ],
                'order' => [
                    'Products.name' => 'ASC'
                ],
                'contain' => [
                    'SubCategories'
                ]
            ])->select([
                'product_id' => 'Products.id',
                'unit' => 'Products.unit',
                'min_unit' => 'Products.min_unit',
                'product_code' => 'Products.code',
                'product_no_catalog' => 'Products.no_catalog',
                'product_name' => 'Products.name',
                'saldo_awal' => '(IFNULL((' . $awalStocks->select(
                    [
                        'SUM' => $awalStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0)) - (IFNULL((' . $awalStocks2->select(
                    [
                        'SUM' => $awalStocks2->func()->sum('qty')
                    ]
                        )->where([
                            'type = "OUT"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'in' => '(IFNULL((' . $inStocks->select(
                    [
                        'SUM' => $inStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'out' => '(IFNULL((' . $outStocks->select(
                    [
                        'SUM' => $outStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "OUT"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'saldo_akhir' => '(IFNULL((' . $akhirStocks->select(
                    [
                        'SUM' => $akhirStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0)) - (IFNULL((' . $akhirStocks2->select(
                            [
                                'SUM' => $akhirStocks2->func()->sum('qty')
                            ]
                                )->where([
                                    'type = "OUT"',
                                    'product_id = Products.id',
                                ]) . '), 0))'
            ]);
        } elseif ($this->request->query('keywordCategory')) {
            $keywordCategory = $this->request->query('keywordCategory');
            if($cek == 4){ /*gudang ATK*/
                $keywordCategory = '!= 2';
            }elseif($cek == 6){  /*gudang Reagen*/
                $keywordCategory = '= 2';
            }
            $results = $this->Products->find('all', [
                'conditions' => [
                    $where,
                    'or' => [
                        'category_id '.$keywordCategory.' ',
                    ],
                ],
                'order' => [
                    'Products.name' => 'ASC'
                ],
                'contain' => [
                    'SubCategories'
                ]
            ])->select([
                'product_id' => 'Products.id',
                'unit' => 'Products.unit',
                'min_unit' => 'Products.min_unit',
                'product_code' => 'Products.code',
                'product_no_catalog' => 'Products.no_catalog',
                'product_name' => 'Products.name',
                'saldo_awal' => '(IFNULL((' . $awalStocks->select(
                    [
                        'SUM' => $awalStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0)) - (IFNULL((' . $awalStocks2->select(
                    [
                        'SUM' => $awalStocks2->func()->sum('qty')
                    ]
                        )->where([
                            'type = "OUT"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'in' => '(IFNULL((' . $inStocks->select(
                    [
                        'SUM' => $inStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'out' => '(IFNULL((' . $outStocks->select(
                    [
                        'SUM' => $outStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "OUT"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'saldo_akhir' => '(IFNULL((' . $akhirStocks->select(
                    [
                        'SUM' => $akhirStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0)) - (IFNULL((' . $akhirStocks2->select(
                            [
                                'SUM' => $akhirStocks2->func()->sum('qty')
                            ]
                                )->where([
                                    'type = "OUT"',
                                    'product_id = Products.id',
                                ]) . '), 0))'
            ]);
        } else {
            $results = $this->Products->find('all', [
                'conditions' => [
                    $where
                ],
                'contain' => [
                    'SubCategories'
                ]
            ])->select([
                'product_id' => 'Products.id',
                'unit' => 'Products.unit',
                'min_unit' => 'Products.min_unit',
                'product_code' => 'Products.code',
                'product_no_catalog' => 'Products.no_catalog',
                'product_name' => 'Products.name',
                'saldo_awal' => '(IFNULL((' . $awalStocks->select(
                    [
                        'SUM' => $awalStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0)) - (IFNULL((' . $awalStocks2->select(
                    [
                        'SUM' => $awalStocks2->func()->sum('qty')
                    ]
                        )->where([
                            'type = "OUT"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'in' => '(IFNULL((' . $inStocks->select(
                    [
                        'SUM' => $inStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'out' => '(IFNULL((' . $outStocks->select(
                    [
                        'SUM' => $outStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "OUT"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'saldo_akhir' => '(IFNULL((' . $akhirStocks->select(
                    [
                        'SUM' => $akhirStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'product_id = Products.id',
                        ]) . '), 0)) - (IFNULL((' . $akhirStocks2->select(
                            [
                                'SUM' => $akhirStocks2->func()->sum('qty')
                            ]
                                )->where([
                                    'type = "OUT"',
                                    'product_id = Products.id',
                                ]) . '), 0))'
            ]);
        }

        $this->set(compact('titleModule', 'results', 'periode', 'start', 'end', 'categories', 'keywordName', 'keywordCategory'));
        if($print){
            $this->print();
        }
    
    }

    public function print()
    {
        // dd($this->request);
        $this->set(compact('titleModule'));
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
        $this->render('print');

    }
}
