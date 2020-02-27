<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Core\Configure;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Datasource\ConnectionManager;

/**
 * ReportReceivesController
 */
class ReportExpendituresDistributionsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        if (php_sapi_name() !== 'cli') {
            $this->Auth->allow(['view', 'printMonth', 'printYear']);
        }
        $this->loadModel('Institutes');
    }

    public function index()
    {
        $request_print = $this->request->query('print');

        $cek = $this->userData->user_group_id;
        $whereCategory = '';
        if($cek == 4){ /*gudang ATK*/
            $whereCategory = 'category_id NOT IN(2)';
        }elseif($cek == 6){  /*gudang Reagen*/
            $whereCategory = 'category_id = 2';
        }
        
        $titleModule = 'Laporan Distribusi Barang';
        if (empty($request_print)) {
            $categoriesTable = $this->loadModel('Categories');
            $categories = $categoriesTable->find('list')->order(['name' => 'ASC']);
            $institutes = $this->Institutes->find('list')->order('Institutes.name ASC');

            $titlesubModule = 'Filter ' . $titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => $titlesubModule
            ];
            $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'categories','institutes'));
        } else {
            $start          = $this->request->query('start');
            $end            = $this->request->query('end');
            $institute_id   = $this->request->query('institute_id');
            $arrayMonth = $this->Utilities->monthArray();

            $startYear = date('Y', strtotime($start));
            $startMonth = date('m', strtotime($start));
            $startDay = date('d', strtotime($start));
            $startSql = $startDay.' '.$arrayMonth[$startMonth*1].' '.$startYear;

            $endYear = date('Y', strtotime($end));
            $endMonth = date('m', strtotime($end));
            $endDay = date('d', strtotime($end));
            $endSql = $endDay.' '.$arrayMonth[$endMonth*1].' '.$endYear;

            $institutes = $this->Institutes->get($institute_id);
            // dd($institutes);

            $periode = $startSql . ' s.d ' . $endSql;
            $titleModule = 'Laporan Distribusi Barang ' . $institutes->name;

            $where = '';
            if($institute_id == null) {
                $where = ['ExpendituresDistributions.institute_id' => $this->userData->institute_id];
            }

            $this->loadModel('ExpendituresDistributionsDetails');
            $results = $this->ExpendituresDistributionsDetails->find('all', [
                'conditions' => [
                    'ExpendituresDistributions.institute_id' => $institute_id,
                ],
                'contain' => [
                    'Products.SubCategories',
                    'ExpendituresDistributions',
                    'ExpendituresDistributions.Institutes',
                    'ExpendituresDistributions.InternalOrders'
                ]
            ])->select([
                'code1' => 'ExpendituresDistributions.code',
                'code2' => 'InternalOrders.code',
                'code'  => 'Products.code',
                'unit'  => 'Products.unit',
                'name'  => 'Products.name',
                'date'  => 'ExpendituresDistributions.date',
                'qty'   => 'ExpendituresDistributionsDetails.qty',
                'price' => 'ExpendituresDistributionsDetails.price',
            ])->where([
                'DATE(ExpendituresDistributions.date) BETWEEN "' . $start . '" AND "' . $end . '"',
                $whereCategory
            ]);

            $this->set(compact('titleModule', 'results', 'periode', 'start', 'end'));
            if ($request_print == 'pdf') {
                Configure::write('CakePdf', [
                    'engine' => [
                        'className' => 'CakePdf.WkHtmlToPdf',
                        'binary' => '/usr/local/bin/wkhtmltopdf',
                        // 'className' => 'CakePdf.WkHtmlToPdf', 'binary' => 'C:\wkhtmltopdf\bin\wkhtmltopdf.exe',
                        'options' => [
                            'print-media-type' => false,
                            'outline' => true,
                            'dpi' => 96
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
            } elseif ($request_print == 'xlsx') {
                $this->autoRender = false;
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->getColumnDimension('A')->setAutoSize(true);
                $sheet->getColumnDimension('B')->setAutoSize(true);
                $sheet->getColumnDimension('C')->setAutoSize(true);
                $sheet->getColumnDimension('D')->setAutoSize(true);
                $sheet->getColumnDimension('E')->setAutoSize(true);
                $sheet->getColumnDimension('F')->setAutoSize(true);
                $sheet->getColumnDimension('G')->setAutoSize(true);
                $sheet->getColumnDimension('H')->setAutoSize(true);
                $sheet->getStyle('A1:H2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A1', 'LAPORAN DISTRIBUSI BARANG ' .  strtoupper($institutes->name));
                $sheet->setCellValue('A2', 'PERIODE : ' . $periode);
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');
                //set style header
                $sheet->getStyle('A1:H2')->getFont()->setBold(true);
                $sheet->getStyle('A1:H2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('DDDDDD');

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '333333'],
                        ],
                    ],
                ];

                $start = 3;

                $sheet->setCellValue('A' . $start, 'No.');
                $sheet->setCellValue('B' . $start, 'Kode Distribusi Barang');
                $sheet->setCellValue('C' . $start, 'Kode Permintaan User');
                $sheet->setCellValue('D' . $start, 'Kode Barang');
                $sheet->setCellValue('E' . $start, 'Nama barang');
                $sheet->setCellValue('F' . $start, 'Jumlah Barang');
                $sheet->setCellValue('G' . $start, 'Harga Barang');
                $sheet->setCellValue('H' . $start, 'Total');
                $col = $start + 1;
                $no = 1;

                foreach ($results as $grant) {
                    $sheet->setCellValue('A' . $col, $no);
                    $sheet->setCellValue('B' . $col, $grant->code1);
                    $sheet->setCellValue('C' . $col, $grant->code2);
                    $sheet->setCellValue('D' . $col, $grant->code);
                    $sheet->setCellValue('E' . $col, $grant->name);
                    $sheet->setCellValue('F' . $col, number_format($grant->qty) . ' ' . $grant->unit);
                    $sheet->setCellValue('G' . $col, number_format($grant->price));
                    $sheet->setCellValue('H' . $col, number_format($grant->price * $grant->qty));

                    $no++;
                    $col++;
                }

                $end = $col - 1;
                $sheet->getStyle('A1:H' . $end)->applyFromArray($styleArray);

                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Laporan Distribusi Barang' . $institutes->name . rand(2, 32012) . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                $this->viewBuilder()->layout('report');
                $this->render('pdf/index');
            }
        }
    }

    public function getcolumnrange($min, $max)
    {
        $pointer = strtoupper($min);
        $output = [];
        while ($this->positionalcomparison($pointer, strtoupper($max)) <= 0) {
            array_push($output, $pointer);
            $pointer++;
        }
        return $output;
    }

    public function positionalcomparison($a, $b)
    {
        $a1 = $this->stringtointvalue($a);
        $b1 = $this->stringtointvalue($b);
        if ($a1 > $b1) {
            return 1;
        } elseif ($a1 < $b1) {
            return -1;
        } else {
            return 0;
        }
    }

    public function stringtointvalue($str)
    {
        $amount = 0;
        $strarra = array_reverse(str_split($str));

        for ($i = 0;$i < strlen($str);$i++) {
            $amount += (ord($strarra[$i]) - 64) * pow(26, $i);
        }
        return $amount;
    }

    public function printMonth()
    {
        $data = $this->request->query();
        $date = $data['date'];
        $conn = ConnectionManager::get('default');
        $month = trim(substr($date, 5, 7), 0);
        $year = substr($date, 0, 4);
        $arrayMonth = $this->Utilities->monthArray();
        $category_id = $this->request->query('category_id');
        $titleModule = 'Laporan Distribusi Barang Bulan ' . $arrayMonth[$month];

        $categoriesTable = $this->loadModel('Categories');

        $cek = $this->userData->user_group_id;
        $category = '';
        if($cek == 4){ /*gudang ATK*/
            $category = 'ATK';
        }elseif($cek == 6){  /*gudang Reagen*/
            $category = 'REAGEN';
        }else{
            $category = $categoriesTable->get($category_id);
            $category = $category->name;
        }

        // QUERY SHEET 1
        $this->loadModel('ExpendituresDistributionsDetails');
        $this->loadModel('Institutes');

        $where = '';
        // dd($this->Auth->User()->institute_id);
        if ($this->Auth->User()->institute_id) {
            $where = ['ExpendituresDistributions.institute_id' => $this->userData->institute_id];
        }

        $sheets1 = $this->ExpendituresDistributionsDetails->find('all', [
            'contain' => [
                'ExpendituresDistributions',
                'ExpendituresDistributions.Institutes',
                'Products',
                'Products.SubCategories'
            ],
            'conditions' => [
                'SubCategories.category_id IN('.$category_id.')',
                'MONTH(ExpendituresDistributions.date)' => $month,
                'YEAR(ExpendituresDistributions.date)' => $year,
                $where
            ],
            'order' => 'Institutes.name ASC'
        ])->select([
            'date' => 'ExpendituresDistributions.date',
            'institute_name' => 'Institutes.name',
            'product_name' => 'Products.name',
            'qty' => 'ExpendituresDistributionsDetails.qty',
            'unit' => 'Products.unit',
            'price' => 'ExpendituresDistributionsDetails.price',
            'subtotal' => '(ExpendituresDistributionsDetails.price * ExpendituresDistributionsDetails.qty)',
        ]);
        // QUERY SHEET 2

        $this->loadModel('ExpendituresDistributionsDetails');
        $expendTable1_sheet2 = $this->ExpendituresDistributionsDetails->find();

        $where = '';
        if ($this->userData->institute_id) {
            $where = ['Institutes.id' => $this->userData->institute_id];
        }

        $sheet2_results = $this->Institutes->find('all', [
            'order' => 'Institutes.name ASC',
            'conditions' => [
                $where
            ]
        ])->select([
            'id' => 'Institutes.id',
            'name' => 'Institutes.name',
            'qty_sum' => '(IFNULL((' .
                            $expendTable1_sheet2->select([
                                'SUM' => $expendTable1_sheet2->func()->sum('qty')
                            ])
                            ->contain(['ExpendituresDistributions', 'Products', 'Products.SubCategories'])
                            ->where([
                                'institute_id = Institutes.id',
                                'MONTH(date) = "' . $month . '"',
                                'YEAR(date) = "' . $year . '"',
                                'SubCategories.category_id IN ("' . $category_id . '")',
                            ])
                        . '), 0))',
            'price_sum' => '(IFNULL((' .
                            $expendTable1_sheet2->select([
                                'SUM' => $expendTable1_sheet2->func()->sum('price')
                            ])
                            ->contain(['ExpendituresDistributions', 'Products', 'Products.SubCategories'])
                            ->where([
                                'institute_id = Institutes.id',
                                'MONTH(date) = "' . $month . '"',
                                'YEAR(date) = "' . $year . '"',
                                'SubCategories.category_id IN ("' . $category_id . '")',
                            ])
                        . '), 0))'
        ]);
        
        foreach ($sheet2_results as $key => $value) {
            $sheets2[$value->id] = $this->ExpendituresDistributionsDetails->find('all', [
                'contain' => [
                    'Products',
                    'Products.SubCategories',
                    'ExpendituresDistributions'
                ],
                'conditions' => [
                    'institute_id' => $value->id,
                    'SubCategories.category_id IN('.$category_id.')',
                    'MONTH(ExpendituresDistributions.date)' => $month,
                    'YEAR(ExpendituresDistributions.date)' => $year,
                ],
                'order' => 'Products.name ASC'
            ])->select([
                'product_name' => 'Products.name',
                'product_qty' => 'SUM(ExpendituresDistributionsDetails.qty)',
                'product_price' => 'SUM(ExpendituresDistributionsDetails.price)',
            ])->group('Products.name');
        }

        // QUERY SHEET3
        // $ProductsModel = $this->loadModel('Products');
        // $InstitutesModel = $this->loadModel('Institutes');
        // $ExpendituresTable = $this->loadModel('ExpendituresDistributionsDetails');

        // $where = '';
        // if ($this->userData->institute_id) {
        //     $where = ['Institutes.id' => $this->userData->institute_id];
        // }

        // $products = $ProductsModel->find('all', [
        //     'order' => 'Products.name',
        //     'contain' => 'SubCategories',
        //     'conditions' => [
        //         'SubCategories.category_id' => $category_id,
        //     ]
        // ])->select([
        //     'id' => 'Products.id',
        //     'name' => 'Products.name',
        // ]);

        // $institutes = $InstitutesModel->find('all', [
        //     'order' => 'Institutes.name',
        //     'conditions' => [
        //         $where
        //     ]
        // ])->select([
        //     'id' => 'Institutes.id',
        //     'name' => 'Institutes.name'
        // ]);
        // foreach ($products as $key => $vp) {
        //     foreach ($institutes as $key => $vi) {
        //         $sum = '';
        //         $sum = $ExpendituresTable->find('all', [
        //             'contain' => [
        //                 'ExpendituresDistributions', 'Products', 'Products.SubCategories'
        //             ],
        //             'conditions' => [
        //                 'SubCategories.category_id' => $category_id,
        //                 'institute_id' => $vi->id,
        //                 'product_id' => $vp->id,
        //                 'MONTH(ExpendituresDistributions.date)' => $month,
        //                 'YEAR(ExpendituresDistributions.date)' => $year,
        //             ]
        //         ])->select([
        //             'sum' => 'IFNULL(SUM(qty), 0)'
        //         ])->first();

        //         $arrayProducts[$vp->id][$vi->id] = $sum->sum;
        //     }
        // }

        // dump($products->toArray());
        // die;
        $this->set(compact('titleModule', 'date', 'sheets1', 'sheet2_results', 'sheets2', 'products', 'institutes', 'arrayProducts'));

        $this->autoRender = false;
        $spreadsheet = new Spreadsheet();

        // SHEET 1
        $sheet = $spreadsheet->getActiveSheet()->setTitle(strtoupper($arrayMonth[$month]));
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getStyle('A1:H2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('A1', 'LAPORAN DISTRIBUSI BARANG');
        $sheet->setCellValue('A2', 'BULAN : ' . strtoupper($arrayMonth[$month]));
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        //set style header
        $sheet->getStyle('A1:H2')->getFont()->setBold(true);
        $sheet->getStyle('A1:H2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('DDDDDD');

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '333333'],
                ],
            ],
        ];

        $start = 3;

        $sheet->setCellValue('A' . $start, 'No.');
        $sheet->setCellValue('B' . $start, 'Tanggal');
        $sheet->setCellValue('C' . $start, 'Instalasi');
        $sheet->setCellValue('D' . $start, 'Nama Barang');
        $sheet->setCellValue('E' . $start, 'Jumlah Barang');
        $sheet->setCellValue('F' . $start, 'Satuan');
        $sheet->setCellValue('G' . $start, 'Harga Barang');
        $sheet->setCellValue('H' . $start, 'Total Harga');
        $col = $start + 1;
        $no = 1;

        // set height column
        $sheet->getRowDimension('3')->setRowHeight(30);
        $sheet->getStyle('A3:H3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A3:H3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($sheets1 as $sheet1) {
            $sheet->setCellValue('A' . $col, $no);
            $sheet->setCellValue('B' . $col, $this->Utilities->indonesiaDateFormat($sheet1->date->format('Y-m-d')));
            $sheet->setCellValue('C' . $col, $sheet1->institute_name);
            $sheet->setCellValue('D' . $col, $sheet1->product_name);
            $sheet->setCellValue('E' . $col, number_format($sheet1->qty));
            $sheet->setCellValue('F' . $col, $sheet1->unit);
            $sheet->setCellValue('G' . $col, number_format($sheet1->price));
            $sheet->setCellValue('H' . $col, number_format($sheet1->subtotal));

            $no++;
            $col++;
        }
        $sheet->getStyle('B4:D' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('E4:H' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        $end = $col - 1;
        $sheet->getStyle('A1:H' . $end)->applyFromArray($styleArray);

        // SHEET 2
        // $spreadsheet->createSheet();
        // $spreadsheet->setActiveSheetIndex(1);
        // $sheet = $spreadsheet->getActiveSheet()->setTitle('BIAYA ' . strtoupper($arrayMonth[$month]));
        // $sheet->getColumnDimension('A')->setAutoSize(true);
        // $sheet->getColumnDimension('B')->setAutoSize(true);
        // $sheet->getColumnDimension('C')->setAutoSize(true);
        // $sheet->getColumnDimension('D')->setAutoSize(true);
        // $sheet->getStyle('A1:D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('A1:D2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        // $sheet->setCellValue('A1', 'BIAYA PEMAKAIAN ' . strtoupper($category->name) . ' BULAN ' . strtoupper($arrayMonth[$month]));
        // $sheet->mergeCells('A1:D2');
        // //set style header
        // $sheet->getStyle('A1:D2')->getFont()->setBold(true);
        // $sheet->getStyle('A1:D2')->getFill()
        // ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        // ->getStartColor()->setARGB('DDDDDD');

        // $styleArray = [
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['argb' => '333333'],
        //         ],
        //     ],
        // ];

        // $start = 3;

        // // set height column
        // $sheet->getRowDimension('3')->setRowHeight(30);
        // $sheet->getStyle('A3:H3')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        // $sheet->getStyle('A3:H3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // $sheet->setCellValue('A' . $start, 'Instalasi');
        // $sheet->setCellValue('B' . $start, 'Nama Barang');
        // $sheet->setCellValue('C' . $start, 'Jumlah Barang');
        // $sheet->setCellValue('D' . $start, 'Total Harga');
        // $col = $start + 1;
        // $total_qty = 0;
        // $total_price = 0;

        // foreach ($sheet2_results as $sheet2_result) {
        //     $sheet->setCellValue('A' . $col, $sheet2_result->name);
        //     $sheet->setCellValue('B' . $col, '');
        //     $sheet->setCellValue('C' . $col, number_format($sheet2_result->qty_sum));
        //     $sheet->setCellValue('D' . $col, number_format($sheet2_result->price_sum));
        //     $col++;

        //     foreach ($sheets2[$sheet2_result->id] as $sheet2) {
        //         $total_qty += $sheet2->product_qty;
        //         $total_price += $sheet2->product_price;

        //         $sheet->setCellValue('B' . $col, $sheet2->product_name);
        //         $sheet->setCellValue('C' . $col, number_format($sheet2->product_qty));
        //         $sheet->setCellValue('D' . $col, number_format($sheet2->product_price));

        //         $col++;
        //     }
        // }

        // $sheet->getStyle('A3:B' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        // $sheet->getStyle('C3:D' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // $sheet->setCellValue('A' . $col, 'Total Result');
        // $sheet->setCellValue('B' . $col, '');
        // $sheet->setCellValue('C' . $col, number_format($total_qty));
        // $sheet->setCellValue('D' . $col, number_format($total_price));
        // $col++;

        // $end = $col - 1;
        // $sheet->getStyle('A' . $end . ':D' . $end)->getFont()->setBold(true);
        // $sheet->getStyle('A1:D' . $end)->applyFromArray($styleArray);

        // SHEET 3
        // $alphas = $this->getcolumnrange('A', 'ZZ');

        // $spreadsheet->createSheet();
        // $spreadsheet->setActiveSheetIndex(2);
        // $sheet = $spreadsheet->getActiveSheet()->setTitle('PEMAKAIAN ' . strtoupper($arrayMonth[$month]));

        // $column = -1;
        // for ($y = 0; $y < $institutes->count() + 2; $y++) {
        //     $sheet->getColumnDimension($alphas[$y])->setAutoSize(true);
        //     $column++;
        // }

        // $styleArray = [
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['argb' => '333333'],
        //         ],
        //     ],
        // ];

        // $start = 3;
        // $i = 1;

        // // set height column
        // $sheet->getRowDimension('3')->setRowHeight(30);
        // $sheet->getStyle('A3:' . $alphas[$column] . 3)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        // $sheet->getStyle('A3:' . $alphas[$column] . 3)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // $sheet->setCellValue('A' . $start, 'Nama Barang');
        // foreach ($institutes as $institute) {
        //     $sheet->setCellValue($alphas[$i] . $start, $institute->name);

        //     $i++;
        // }
        // $sheet->setCellValue($alphas[$i] . $start, 'Total Result');

        // $col = $start + 1;

        // $grant = 0;
        // foreach ($products as $product) {
        //     $sheet->setCellValue('A' . $col, $product->name);
        //     $i = 1;
        //     $total_result = 0;

        //     foreach ($institutes as $institute) {
        //         $sheet->setCellValue($alphas[$i] . $col, number_format($arrayProducts[$product->id][$institute->id]));
        //         $total_result += $arrayProducts[$product->id][$institute->id];

        //         if (empty($grant_total[$institute->id])) {
        //             $grant_total[$institute->id] = 0;
        //         }

        //         $grant_total[$institute->id] += $arrayProducts[$product->id][$institute->id];
        //         $i++;
        //     }
        //     $grant += $total_result;

        //     $sheet->setCellValue($alphas[$i] . $col, number_format($total_result));
        //     $col++;
        //     $i++;
        // }

        // $sheet->setCellValue('A' . $col, 'Total Result');
        // $i = 1;
        // foreach ($grant_total as $total) {
        //     $sheet->setCellValue($alphas[$i] . $col, number_format($total));

        //     $i++;
        // }
        // $sheet->setCellValue($alphas[$i] . $col, number_format($grant));
        // $sheet->getStyle('A' . $col . ':' . $alphas[$column] . $col)->getFont()->setBold(true);
        // $sheet->getStyle($alphas[$column] . 4 . ':' . $alphas[$column] . $col)->getFont()->setBold(true);
        // $col++;

        // $sheet->getStyle('B4:' . $alphas[$column] . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // $end = $col - 1;
        // $sheet->getStyle('A1:' . $alphas[$column] . $end)->applyFromArray($styleArray);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Laporan Distribusi Barang Bulan ' . $arrayMonth[$month] . rand(2, 32012) . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function getTotal($year, $month, $category_id)
    {
        $expendTable_year1 = $this->ExpendituresDistributionsDetails->find();
        $expendTable_year2 = $this->ExpendituresDistributionsDetails->find();

        $data = '(IFNULL((' .
                    $expendTable_year1->select([
                        'SUM' => $expendTable_year1->func()->sum('qty')
                    ])->contain(['ExpendituresDistributions', 'Products', 'Products.SubCategories'])
                    ->where([
                        'institute_id = Institutes.id',
                        'MONTH(date) = "' . $month . '"',
                        'YEAR(date) = "' . $year . '"',
                        'SubCategories.category_id IN ("' . $category_id . '")'
                    ])->order(['date' => 'ASC'])->limit(1)
                . '), 0) * IFNULL((' .
                    $expendTable_year2->select([
                        'price'
                    ])->contain(['ExpendituresDistributions', 'Products', 'Products.SubCategories'])
                    ->where([
                        'institute_id = Institutes.id',
                        'MONTH(date) = "' . $month . '"',
                        'YEAR(date) = "' . $year . '"',
                        'SubCategories.category_id IN ("' . $category_id . '")'
                    ])->order(['date' => 'ASC'])->limit(1)
                . '), 0))';

        return $data;
    }

    public function printYear()
    {
        $year = $this->request->query('year');
        $titleModule = 'Laporan Distribusi Barang Tahun ' . ' ' . $year;

        $category_id = $this->request->query('category_id');
        $categoriesTable = $this->loadModel('Categories');

        $cek = $this->userData->user_group_id;
        $category = '';
        if($cek == 4){ /*gudang ATK*/
            $category = 'ATK';
        }elseif($cek == 6){  /*gudang Reagen*/
            $category = 'REAGEN';
        }else{
            $category = $categoriesTable->get($category_id);
            $category = $category->name;
        }
        // QUERY SHEET 1
        $this->loadModel('Products');
        $this->loadModel('Stocks');

        $stocksTable_1 = $this->Stocks->find();
        $stocksTable_1Out = $this->Stocks->find();
        $stocksTable_2 = $this->Stocks->find();
        $stocksTable_3 = $this->Stocks->find();
        $stocksTable_4 = $this->Stocks->find();
        $stocksTable_5 = $this->Stocks->find();
        $stocksTable_6 = $this->Stocks->find();
        $stocksTable_7 = $this->Stocks->find();
        $stocksTable_8 = $this->Stocks->find();
        $stocksTable_9 = $this->Stocks->find();
        $stocksTable_10 = $this->Stocks->find();
        $stocksTable_11 = $this->Stocks->find();
        $stocksTable_12 = $this->Stocks->find();
        $stocksTable_13 = $this->Stocks->find();
        $stocksTable_14 = $this->Stocks->find();
        $stocksTable_15 = $this->Stocks->find();
        $stocksTable_16 = $this->Stocks->find();
        $stocksTable_17 = $this->Stocks->find();

        // $conn = ConnectionManager::get('default');
        // $a = $conn->execute("
        //     SELECT * FROM products
        // ")->fetchAll('assoc');
        // dump($a);
        // die();

        $products = $this->Products->find('all', [
            'contain' => [
                'SubCategories'
            ],
            'conditions' => [
                'SubCategories.category_id IN ('.$category_id.')'
            ],
            'order' => 'Products.name ASC'
        ])->select([
            'id' => 'Products.id',
            'name' => 'Products.name',
            'saldo_awal' => '(IFNULL((' .
                                    $stocksTable_1->select(
                                        [
                                            'SUM' => $stocksTable_1->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "IN"',
                                        'OR' => [
                                            ['ref_table = "expenditures_distributions_details"'],
                                            ['ref_table = "init_stocks_details"']
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) < "' . $year . '"'
                                    ])
                                . '), 0)) - (IFNULL((' .
                                    $stocksTable_1Out->select(
                                        [
                                            'SUM' => $stocksTable_1Out->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'ref_table = "expenditures_distributions_details"',
                                        'product_id = Products.id',
                                        'YEAR(date) < "' . $year . '"'
                                    ])
                                . '), 0))',
            'penerimaan' => '(IFNULL((' .
                                    $stocksTable_2->select(
                                        [
                                            'SUM' => $stocksTable_2->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "IN"',
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"'
                                    ])
                                . '), 0))',
            'januari' => '(IFNULL((' .
                                    $stocksTable_3->select(
                                        [
                                            'SUM' => $stocksTable_3->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 1'
                                    ])
                                . '), 0))',
            'februari' => '(IFNULL((' .
                                    $stocksTable_4->select(
                                        [
                                            'SUM' => $stocksTable_4->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 2'
                                    ])
                                . '), 0))',
            'maret' => '(IFNULL((' .
                                    $stocksTable_5->select(
                                        [
                                            'SUM' => $stocksTable_5->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 3'
                                    ])
                                . '), 0))',
            'april' => '(IFNULL((' .
                                    $stocksTable_6->select(
                                        [
                                            'SUM' => $stocksTable_6->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 4'
                                    ])
                                . '), 0))',
            'mei' => '(IFNULL((' .
                                    $stocksTable_7->select(
                                        [
                                            'SUM' => $stocksTable_7->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 5'
                                    ])
                                . '), 0))',
            'juni' => '(IFNULL((' .
                                    $stocksTable_8->select(
                                        [
                                            'SUM' => $stocksTable_8->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 6'
                                    ])
                                . '), 0))',
            'juli' => '(IFNULL((' .
                                    $stocksTable_9->select(
                                        [
                                            'SUM' => $stocksTable_9->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 7'
                                    ])
                                . '), 0))',
            'agustus' => '(IFNULL((' .
                                    $stocksTable_10->select(
                                        [
                                            'SUM' => $stocksTable_10->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 8'
                                    ])
                                . '), 0))',
            'september' => '(IFNULL((' .
                                    $stocksTable_11->select(
                                        [
                                            'SUM' => $stocksTable_11->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 9'
                                    ])
                                . '), 0))',
            'oktober' => '(IFNULL((' .
                                    $stocksTable_12->select(
                                        [
                                            'SUM' => $stocksTable_12->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 10'
                                    ])
                                . '), 0))',
            'november' => '(IFNULL((' .
                                    $stocksTable_13->select(
                                        [
                                            'SUM' => $stocksTable_13->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 11'
                                    ])
                                . '), 0))',
            'desember' => '(IFNULL((' .
                                    $stocksTable_14->select(
                                        [
                                            'SUM' => $stocksTable_14->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                        'MONTH(date) = 12'
                                    ])
                                . '), 0))',
            'total' => '(IFNULL((' .
                                    $stocksTable_15->select(
                                        [
                                            'SUM' => $stocksTable_15->func()->sum('qty')
                                        ]
                                    )->where([
                                        'type = "OUT"',
                                        'or' => [
                                            'ref_table = "expenditures_distributions_details"',
                                            'ref_table = "expenditures_grands_details"',
                                            'ref_table = "expenditures_reclarifications_details"',
                                            'ref_table = "expenditures_transfers_details"',
                                        ],
                                        'product_id = Products.id',
                                        'YEAR(date) = "' . $year . '"',
                                    ])
                                . '), 0))',
            'saldo_akhir' => '((IFNULL((' .
                                $stocksTable_16->select(
                                    [
                                        'SUM' => $stocksTable_16->func()->sum('qty')
                                    ]
                                )->where([
                                    'type = "IN"',
                                    'OR' => [
                                        ['ref_table = "expenditures_distributions_details"'],
                                        ['ref_table = "init_stocks_details"']
                                    ],
                                    'product_id = Products.id',
                                    'YEAR(date) < "' . $year . '"'
                                ])
                            . '), 0)) + (IFNULL((' .
                                $stocksTable_1Out->select(
                                    [
                                        'SUM' => $stocksTable_1Out->func()->sum('qty')
                                    ]
                                )->where([
                                    'type = "OUT"',
                                    'ref_table = "expenditures_distributions_details"',
                                    'product_id = Products.id',
                                    'YEAR(date) < "' . $year . '"'
                                ])
                            . '), 0)) + (IFNULL((' .
                            $stocksTable_2->select(
                                [
                                    'SUM' => $stocksTable_2->func()->sum('qty')
                                ]
                            )->where([
                                'type = "IN"',
                                'product_id = Products.id',
                                'YEAR(date) = "' . $year . '"'
                            ])
                        . '), 0))) - (IFNULL((' .
                        $stocksTable_15->select(
                            [
                                'SUM' => $stocksTable_15->func()->sum('qty')
                            ]
                        )->where([
                            'type = "OUT"',
                            'or' => [
                                'ref_table = "expenditures_distributions_details"',
                                'ref_table = "expenditures_grands_details"',
                                'ref_table = "expenditures_reclarifications_details"',
                                'ref_table = "expenditures_transfers_details"',
                            ],
                            'product_id = Products.id',
                            'YEAR(date) = "' . $year . '"',
                        ])
                    . '), 0))'
        ]);
        // dump($products->toArray());
        // die();

        // QUERY SHEET 2
        $this->loadModel('ExpendituresDistributionsDetails');

        $expendTable_year = $this->ExpendituresDistributionsDetails->find();
        $expendTable_year_ = $this->ExpendituresDistributionsDetails->find();

        $this->loadModel('Institutes');
        $institutes = $this->Institutes->find('all', [
            'order' => 'Institutes.name ASC'
        ])->select([
            'name' => 'Institutes.name',
            'januari' => $this->getTotal($year, 1, $category_id),
            'februari' => $this->getTotal($year, 2, $category_id),
            'maret' => $this->getTotal($year, 3, $category_id),
            'april' => $this->getTotal($year, 4, $category_id),
            'mei' => $this->getTotal($year, 5, $category_id),
            'juni' => $this->getTotal($year, 6, $category_id),
            'juli' => $this->getTotal($year, 7, $category_id),
            'agustus' => $this->getTotal($year, 8, $category_id),
            'september' => $this->getTotal($year, 9, $category_id),
            'oktober' => $this->getTotal($year, 10, $category_id),
            'november' => $this->getTotal($year, 11, $category_id),
            'desember' => $this->getTotal($year, 12, $category_id),
        ]);

        // SHEET 1
        $alphas = $this->getcolumnrange('A', 'ZZ');

        $this->autoRender = false;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet = $spreadsheet->getActiveSheet()->setTitle('TAHUN ' . $year);

        for ($i = 0;$i < 18;$i++) {
            $sheet->getColumnDimension($alphas[$i])->setAutoSize(true);
        }

        $sheet->getStyle('A1:R4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:R4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A1', 'LAPORAN POSISI BARANG ' . strtoupper($category) . ' ' . $year);
        $sheet->mergeCells('A1:R2');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '333333'],
                ],
            ],
        ];

        $start = 3;
        $col = $start + 1;
        $no = 1;
        $sheet->setCellValue('A' . $start, 'No.');
        $sheet->setCellValue('B' . $start, 'Nama Barang');
        $sheet->setCellValue('C' . $start, 'Saldo Awal');
        $sheet->setCellValue('D' . $start, 'penerimaan');
        $sheet->setCellValue('E' . $start, 'Pengeluaran');
        $sheet->setCellValue('R' . $start, 'Saldo Akhir');

        $sheet->mergeCells('A3:A4');
        $sheet->mergeCells('B3:B4');
        $sheet->mergeCells('C3:C4');
        $sheet->mergeCells('D3:D4');
        $sheet->mergeCells('E3:Q3');
        $sheet->mergeCells('R3:R4');

        $sheet->setCellValue('E' . $col, 'JAN');
        $sheet->setCellValue('F' . $col, 'FEB');
        $sheet->setCellValue('G' . $col, 'MARET');
        $sheet->setCellValue('H' . $col, 'APRIL');
        $sheet->setCellValue('I' . $col, 'MEI');
        $sheet->setCellValue('J' . $col, 'JUNI');
        $sheet->setCellValue('K' . $col, 'JULI');
        $sheet->setCellValue('L' . $col, 'AGTS');
        $sheet->setCellValue('M' . $col, 'SEPT');
        $sheet->setCellValue('N' . $col, 'OKT');
        $sheet->setCellValue('O' . $col, 'NOV');
        $sheet->setCellValue('P' . $col, 'DES');
        $sheet->setCellValue('Q' . $col, 'Total');
        $col++;

        $saldo_awal = 0;
        $penerimaan = 0;
        $januari = 0;
        $februari = 0;
        $maret = 0;
        $april = 0;
        $mei = 0;
        $juni = 0;
        $juli = 0;
        $agustus = 0;
        $september = 0;
        $oktober = 0;
        $november = 0;
        $desember = 0;
        $total = 0;
        $saldo_akhir = 0;

        foreach ($products as $product) {
            $sheet->setCellValue('A' . $col, $no);
            $sheet->setCellValue('B' . $col, $product->name);
            $sheet->setCellValue('C' . $col, number_format($product->saldo_awal));
            $sheet->setCellValue('D' . $col, number_format($product->penerimaan));
            $sheet->setCellValue('E' . $col, number_format($product->januari));
            $sheet->setCellValue('F' . $col, number_format($product->februari));
            $sheet->setCellValue('G' . $col, number_format($product->maret));
            $sheet->setCellValue('H' . $col, number_format($product->april));
            $sheet->setCellValue('I' . $col, number_format($product->mei));
            $sheet->setCellValue('J' . $col, number_format($product->juni));
            $sheet->setCellValue('K' . $col, number_format($product->juli));
            $sheet->setCellValue('L' . $col, number_format($product->agustus));
            $sheet->setCellValue('M' . $col, number_format($product->september));
            $sheet->setCellValue('N' . $col, number_format($product->oktober));
            $sheet->setCellValue('O' . $col, number_format($product->november));
            $sheet->setCellValue('P' . $col, number_format($product->desember));
            $sheet->setCellValue('Q' . $col, number_format($product->total));
            $sheet->setCellValue('R' . $col, number_format($product->saldo_akhir));

            $saldo_awal += $product->saldo_awal;
            $penerimaan += $product->penerimaan;
            $januari += $product->januari;
            $februari += $product->februari;
            $maret += $product->maret;
            $april += $product->april;
            $mei += $product->mei;
            $juni += $product->juni;
            $juli += $product->juli;
            $agustus += $product->agustus;
            $september += $product->september;
            $oktober += $product->oktober;
            $november += $product->november;
            $desember += $product->desember;
            $total += $product->total;
            $saldo_akhir += $product->saldo_akhir;

            $no++;
            $col++;
        }

        // set last record
        $sheet->setCellValue('A' . $col, '');
        $sheet->setCellValue('B' . $col, '');
        $sheet->setCellValue('C' . $col, number_format($saldo_awal));
        $sheet->setCellValue('D' . $col, number_format($penerimaan));
        $sheet->setCellValue('E' . $col, number_format($januari));
        $sheet->setCellValue('F' . $col, number_format($februari));
        $sheet->setCellValue('G' . $col, number_format($maret));
        $sheet->setCellValue('H' . $col, number_format($april));
        $sheet->setCellValue('I' . $col, number_format($mei));
        $sheet->setCellValue('J' . $col, number_format($juni));
        $sheet->setCellValue('K' . $col, number_format($juli));
        $sheet->setCellValue('L' . $col, number_format($agustus));
        $sheet->setCellValue('M' . $col, number_format($september));
        $sheet->setCellValue('N' . $col, number_format($oktober));
        $sheet->setCellValue('O' . $col, number_format($november));
        $sheet->setCellValue('P' . $col, number_format($desember));
        $sheet->setCellValue('Q' . $col, number_format($total));
        $sheet->setCellValue('R' . $col, number_format($saldo_akhir));

        $sheet->getStyle('A' . $col . ':R' . $col)->getFont()->setSize(12);
        $col++;

        $end = $col - 1;
        $sheet->getStyle('A1:A' . $end)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:A' . $end)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('C5:R' . $end)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('C5:R' . $end)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:R' . $end)->applyFromArray($styleArray);

        // SHEET 2
        // $spreadsheet->createSheet();
        // $spreadsheet->setActiveSheetIndex(1);
        // $sheet = $spreadsheet->getActiveSheet()->setTitle('TOTAL BIAYA ' . $year);

        // for ($i = 0;$i < 15;$i++) {
        //     $sheet->getColumnDimension($alphas[$i])->setAutoSize(true);
        // }

        // $sheet->getStyle('A1:O4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('A1:O4')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        // $sheet->setCellValue('A1', 'TOTAL BIAYA ' . strtoupper($category->name) . ' TAHUN ' . $year);
        // $sheet->mergeCells('A1:O2');
        // $styleArray = [
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['argb' => '333333'],
        //         ],
        //     ],
        // ];

        // $start = 3;
        // $col = $start + 1;
        // $no = 1;
        // $sheet->setCellValue('A' . $start, 'No.');
        // $sheet->setCellValue('B' . $start, 'Instalasi');
        // $sheet->setCellValue('C' . $start, 'JAN');
        // $sheet->setCellValue('D' . $start, 'FEB');
        // $sheet->setCellValue('E' . $start, 'MARET');
        // $sheet->setCellValue('F' . $start, 'APRIL');
        // $sheet->setCellValue('G' . $start, 'MEI');
        // $sheet->setCellValue('H' . $start, 'JUNI');
        // $sheet->setCellValue('I' . $start, 'JULI');
        // $sheet->setCellValue('J' . $start, 'AGTS');
        // $sheet->setCellValue('K' . $start, 'SEPT');
        // $sheet->setCellValue('L' . $start, 'OKT');
        // $sheet->setCellValue('M' . $start, 'NOV');
        // $sheet->setCellValue('N' . $start, 'DES');
        // $sheet->setCellValue('O' . $start, 'Total');

        // foreach ($institutes as $institute) {
        //     $total = $institute->januari   +
        //              $institute->februari  +
        //              $institute->maret     + 
        //              $institute->april     + 
        //              $institute->mei       + 
        //              $institute->juni      + 
        //              $institute->juli      + 
        //              $institute->agustus   + 
        //              $institute->september + 
        //              $institute->oktober   + 
        //              $institute->november  + 
        //              $institute->desember;

        //     $sheet->setCellValue('A' . $col, $no);
        //     $sheet->setCellValue('B' . $col, $institute->name);
        //     $sheet->setCellValue('C' . $col, number_format($institute->januari));
        //     $sheet->setCellValue('D' . $col, number_format($institute->februari));
        //     $sheet->setCellValue('E' . $col, number_format($institute->maret));
        //     $sheet->setCellValue('F' . $col, number_format($institute->april));
        //     $sheet->setCellValue('G' . $col, number_format($institute->mei));
        //     $sheet->setCellValue('H' . $col, number_format($institute->juni));
        //     $sheet->setCellValue('I' . $col, number_format($institute->juli));
        //     $sheet->setCellValue('J' . $col, number_format($institute->agustus));
        //     $sheet->setCellValue('K' . $col, number_format($institute->september));
        //     $sheet->setCellValue('L' . $col, number_format($institute->oktober));
        //     $sheet->setCellValue('M' . $col, number_format($institute->november));
        //     $sheet->setCellValue('N' . $col, number_format($institute->desember));
        //     $sheet->setCellValue('O' . $col, number_format($total));

        //     $no++;
        //     $col++;
        // }

        // $end = $col - 1;
        // $sheet->getStyle('A1:A' . $end)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('A1:A' . $end)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        // $sheet->getStyle('B4:B' . $end)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        // $sheet->getStyle('C4:O' . $end)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        // $sheet->getStyle('C4:O' . $end)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        // $sheet->getStyle('A1:O' . $end)->applyFromArray($styleArray);

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Laporan Distribusi Barang Tahun '.$year.' ' . rand(2, 32012) . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
