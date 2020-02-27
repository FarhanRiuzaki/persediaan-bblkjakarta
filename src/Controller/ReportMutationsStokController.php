<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Core\Configure;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Cake\I18n\Time;

/**
 * ReportReceivesController
 */
class ReportMutationsStokController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        if (php_sapi_name() !== 'cli') {
            $this->Auth->allow(['printMonth', 'printYear']);
        }
    }
    public function index()
    {
        $request_print = $this->request->query('print');
        $titleModule = 'Laporan Mutasi Stok';
        if (empty($request_print)) {
            $titlesubModule = 'Filter ' . $titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => $titlesubModule
            ];
            $categoriesTable = $this->loadModel('Categories');
            $categories = $categoriesTable->find('list')->order(['name' => 'ASC']);
            $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'categories'));
        } else {
            $start = $this->request->query('start');
            $end = $this->request->query('end');
            $arrayMonth = $this->Utilities->monthArray();

            $startYear = date('Y', strtotime($start));
            $startMonth = date('m', strtotime($start));
            $startDay = date('d', strtotime($start));
            $startSql = $startDay.' '.$arrayMonth[$startMonth*1].' '.$startYear;

            $endYear = date('Y', strtotime($end));
            $endMonth = date('m', strtotime($end));
            $endDay = date('d', strtotime($end));
            $endSql = $endDay.' '.$arrayMonth[$endMonth*1].' '.$endYear;

            $cek = $this->userData->user_group_id;
            $where = '';
            if($cek == 4){ /*gudang ATK*/
                $where = 'category_id NOT IN(2)';
            }elseif($cek == 6){  /*gudang Reagen*/
                $where = 'category_id = 2';
            }
            
            $periode = $startSql . ' s.d ' . $endSql;
            $titleModule = 'Laporan Mutasi Stok ' . $periode;

            $this->loadModel('Products');
            $this->loadModel('Stocks');
            $awalStocks = $this->Stocks->find();
            $awalStocksOut = $this->Stocks->find();
            $inStocks = $this->Stocks->find();
            $outStocks = $this->Stocks->find();
            $akhirStocks = $this->Stocks->find();
            $now = Time::now();
            $now->month;
            $results = $this->Products->find('all', [
                'contain' => [
                    'SubCategories',
                ],
                'conditions' => [
                    $where
                ],
            ])->select([
                'product_id' => 'Products.id',
                'product_code' => 'Products.code',
                'product_name' => 'Products.name',
                'saldo_awal' => '(IFNULL((' . $awalStocks->select(
                    [
                        'SUM' => $awalStocks->func()->sum('qty')
                    ]
                        )->where([
                            'DATE(Stocks.date) < "' . $start . '"',
                            'product_id = Products.id',
                            'type = "IN"',
                        ]) . '), 0)) - (IFNULL((' . $awalStocksOut->select(
                            [
                                'SUM' => $awalStocksOut->func()->sum('qty')
                            ]
                        )->where([
                            'DATE(Stocks.date) < "' . $start . '"',
                            'product_id = Products.id',
                            'type = "OUT"',
                        ]) . '), 0))',
                'in' => '(IFNULL((' . $inStocks->select(
                    [
                        'SUM' => $inStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'DATE(Stocks.date) BETWEEN "' . $start . '" AND "' . $end . '"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'out' => '(IFNULL((' . $outStocks->select(
                    [
                        'SUM' => $outStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "OUT"',
                            'DATE(Stocks.date) BETWEEN "' . $start . '" AND "' . $end . '"',
                            'product_id = Products.id',
                        ]) . '), 0))',
            ]);

            $this->set(compact('titleModule', 'results', 'periode', 'start', 'end'));
            if ($request_print == 'pdf') {
                Configure::write('CakePdf', [
                    'engine' => [
                        'className' => 'CakePdf.WkHtmlToPdf', 'binary' => '/usr/local/bin/wkhtmltopdf',
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
                $sheet->getStyle('A1:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A1', 'LAPORAN MUTASI STOK');
                $sheet->setCellValue('A2', 'PERIODE : ' . $periode);
                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');
                //set style header
                $sheet->getStyle('A1:G2')->getFont()->setBold(true);
                $sheet->getStyle('A1:G2')->getFill()
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
                $sheet->setCellValue('B' . $start, 'ID');
                $sheet->setCellValue('C' . $start, 'Nama Barang');
                $sheet->setCellValue('D' . $start, 'Stok Awal');
                $sheet->setCellValue('E' . $start, 'IN');
                $sheet->setCellValue('F' . $start, 'OUT');
                $sheet->setCellValue('G' . $start, 'Stok Akhir');
                $col = $start + 1;
                $no = 1;

                $total_saldo_awal = 0;
                $total_in = 0;
                $total_out = 0;
                $total_saldo_akhir = 0;

                foreach ($results as $grant) {
                    $total_saldo_awal += $grant->saldo_awal;
                    $total_in += $grant->in;
                    $total_out += $grant->out;
                    $total_saldo_akhir += ($grant->saldo_awal + $grant->in - $grant->out);

                    $sheet->setCellValue('A' . $col, $no);
                    $sheet->setCellValue('B' . $col, $grant->product_id);
                    $sheet->setCellValue('C' . $col, $grant->product_name);
                    $sheet->setCellValue('D' . $col, $grant->saldo_awal);
                    $sheet->setCellValue('E' . $col, $grant->in);
                    $sheet->setCellValue('F' . $col, $grant->out);
                    $sheet->setCellValue('G' . $col, $grant->saldo_awal + $grant->in - $grant->out);

                    $no++;
                    $col++;
                }

                $sheet->setCellValue('A' . $col, 'Total');
                $sheet->setCellValue('D' . $col, $total_saldo_awal);
                $sheet->setCellValue('E' . $col, $total_in);
                $sheet->setCellValue('F' . $col, $total_out);
                $sheet->setCellValue('G' . $col, $total_saldo_akhir);

                $sheet->mergeCells('A' . $col . ':C' . $col);
                $sheet->getStyle('A' . $col . ':C' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $no++;
                $col++;

                $end = $col - 1;
                $sheet->getStyle('A1:G' . $end)->applyFromArray($styleArray);

                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Laporan Mutasi Stok' . rand(2, 32012) . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                $this->viewBuilder()->layout('report');
                $this->render('pdf/index');
            }
        }
    }
    
    public function printMonth(){
        $request_print = $this->request->query('print');
        $category_id = $this->request->query('category_id');
        $category = $this->loadModel('Categories');
        $cek = $this->userData->user_group_id;
        $category_data = '';
        if($cek == 4){ /*gudang ATK*/
            $category_data = 'ATK';
        }elseif($cek == 6){  /*gudang Reagen*/
            $category_data = 'REAGEN';
        }else{
            $category_data = $category->get($category_id);
            $category_data = $category_data->name;
        }
        // dump($category_data);
        // die();
        $titleModule = 'Laporan Mutasi Stok';
        if (empty($request_print)) {
            $titlesubModule = 'Filter ' . $titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => $titlesubModule
            ];
            $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule'));
        } else {
            $month = $this->request->query('month');
            $arrayMonth = $this->Utilities->monthArray();

            $year  = date('Y', strtotime($month));
            $month1 = date('m', strtotime($month));

            $periode = 'Pada bulan '. $arrayMonth[$month1 * 1]. ' '. $year;

            $titleModule = 'Laporan Mutasi Stok ' . $periode;
            $this->loadModel('Products');
            $this->loadModel('Stocks');
            $awalStocks = $this->Stocks->find();
            $awalStocksOut = $this->Stocks->find();
            $inStocks = $this->Stocks->find();
            $outStocks = $this->Stocks->find();
            $akhirStocks = $this->Stocks->find();
            $now = Time::now();
            $now->month;
            $results = $this->Products->find('all', [
                'contain' => [
                    'SubCategories',
                ]
            ])->select([
                'product_id' => 'Products.id',
                'product_code' => 'Products.code',
                'product_name' => 'Products.name',
                'saldo_awal' => '(IFNULL((' . $awalStocks->select(
                        [
                            'SUM' => $awalStocks->func()->sum('qty')
                        ])->where([
                            'DATE(Stocks.date) < "' .  $month. '-01"',
                            'product_id = Products.id',
                            'type = "IN"',
                        ]) . '), 0)) - (IFNULL((' . $awalStocksOut->select(
                            [
                                'SUM' => $awalStocksOut->func()->sum('qty')
                            ]
                        )->where([
                            'DATE(Stocks.date) < "' .  $month. '-01"',
                            'product_id = Products.id',
                            'type = "OUT"',
                        ]) . '), 0))',
                'in' => '(IFNULL((' . $inStocks->select(
                    [
                        'SUM' => $inStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "IN"',
                            'MONTH(Stocks.date) = "'.$month1.'"',
                            'product_id = Products.id',
                        ]) . '), 0))',
                'out' => '(IFNULL((' . $outStocks->select(
                    [
                        'SUM' => $outStocks->func()->sum('qty')
                    ]
                        )->where([
                            'type = "OUT"',
                            'MONTH(Stocks.date) = "'.$month1.'"',
                            'product_id = Products.id',
                        ]) . '), 0))',
            ])->where([
                'SubCategories.category_id IN('.$category_id.')' 
            ]);
            // dd($results->all());
            if ($request_print == 'xlsx') {
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
                $sheet->getStyle('A1:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A1', 'LAPORAN MUTASI STOK '. strtoupper($category_data));
                $sheet->setCellValue('A2', 'PERIODE : ' . $periode);
                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');
                //set style header
                $sheet->getStyle('A1:G2')->getFont()->setBold(true);
                $sheet->getStyle('A1:G2')->getFill()
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
                $sheet->setCellValue('B' . $start, 'ID');
                $sheet->setCellValue('C' . $start, 'Nama Barang');
                $sheet->setCellValue('D' . $start, 'Stok Awal');
                $sheet->setCellValue('E' . $start, 'IN');
                $sheet->setCellValue('F' . $start, 'OUT');
                $sheet->setCellValue('G' . $start, 'Stok Akhir');
                $col = $start + 1;
                $no = 1;

                $total_saldo_awal = 0;
                $total_in = 0;
                $total_out = 0;
                $total_saldo_akhir = 0;

                foreach ($results as $grant) {
                    $total_saldo_awal += $grant->saldo_awal;
                    $total_in += $grant->in;
                    $total_out += $grant->out;
                    $total_saldo_akhir += ($grant->saldo_awal + $grant->in - $grant->out);

                    $sheet->setCellValue('A' . $col, $no);
                    $sheet->setCellValue('B' . $col, $grant->product_id);
                    $sheet->setCellValue('C' . $col, $grant->product_name);
                    $sheet->setCellValue('D' . $col, $grant->saldo_awal);
                    $sheet->setCellValue('E' . $col, $grant->in);
                    $sheet->setCellValue('F' . $col, $grant->out);
                    $sheet->setCellValue('G' . $col, $grant->saldo_awal + $grant->in - $grant->out);

                    $no++;
                    $col++;
                }

                $sheet->setCellValue('A' . $col, 'Total');
                $sheet->setCellValue('D' . $col, $total_saldo_awal);
                $sheet->setCellValue('E' . $col, $total_in);
                $sheet->setCellValue('F' . $col, $total_out);
                $sheet->setCellValue('G' . $col, $total_saldo_akhir);

                $sheet->mergeCells('A' . $col . ':C' . $col);
                $sheet->getStyle('A' . $col . ':C' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $no++;
                $col++;

                $end = $col - 1;
                $sheet->getStyle('A1:G' . $end)->applyFromArray($styleArray);

                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Laporan Mutasi Stok pada '. $periode . '_' . rand(2, 32012) . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                $this->viewBuilder()->layout('report');
                $this->render('pdf/index');
            }
        }
    }
    private function getInOut($year, $month, $type = 'IN'){
        $sql = "(IFNULL((select sum(qty) from stocks where YEAR(date) = '".$year."' and MONTH(date) = '".$month."' and product_id = Products.id and type = '".$type."'),0))";
        // dd($sql); 
        return $sql;
    }

    public function printYear(){
        $request_print = $this->request->query('print');
        $category_id   = $this->request->query('category_id');
        $category      = $this->loadModel('Categories');
        $cek           = $this->userData->user_group_id;

        $category_data = '';
        if($cek == 4){ /*gudang ATK*/
            $category_data = 'ATK';
        }elseif($cek == 6){  /*gudang Reagen*/
            $category_data = 'REAGEN';
        }else{
            $category_data = $category->get($category_id);
            $category_data = $category_data->name;
        }

        $titleModule   = 'Laporan Mutasi Stok';
        if (empty($request_print)) {
            $titlesubModule = 'Filter ' . $titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => $titlesubModule
            ];
            $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule'));
        } else {
            $year = $this->request->query('year');
            // dd($category_id);
            $periode = 'Pada tahun '. $year;

            $titleModule = 'Laporan Mutasi Stok ' . $periode;
            $this->loadModel('Products');
            $this->loadModel('Stocks');
            $awalStocks    = $this->Stocks->find();
            $awalStocks2    = $this->Stocks->find();
            $awalStocksOut = $this->Stocks->find();
            $inStocks      = $this->Stocks->find();
            $outStocks     = $this->Stocks->find();
            $akhirStocks   = $this->Stocks->find();

            $results = $this->Products->find('all', [
                'contain' => [
                    'SubCategories',
                ]
            ])->select([
                'product_id' => 'Products.id',
                'product_code' => 'Products.code',
                'product_name' => 'Products.name',
                'saldo_awal' => '(IFNULL((' . $awalStocks->select(
                        [
                            'SUM' => $awalStocks->func()->sum('qty')
                        ])->where([
                            'YEAR(Stocks.date) < '. $year .'',
                            'product_id = Products.id',
                            'type = "IN"',
                        ]) . '), 0)) - (IFNULL((' . $awalStocksOut->select(
                            [
                                'SUM' => $awalStocksOut->func()->sum('qty')
                            ]
                        )->where([
                            'YEAR(Stocks.date) < '. $year .'',
                            'product_id = Products.id',
                            'type = "OUT"',
                        ]) . '), 0))',
                'jan' => $this->getInOut($year,'01'). '-' . $this->getInOut($year,'01', 'OUT'),
                'feb' => $this->getInOut($year,'02'). '-' . $this->getInOut($year,'02', 'OUT'),
                'mar' => $this->getInOut($year,'03'). '-' . $this->getInOut($year,'03', 'OUT'),
                'apr' => $this->getInOut($year,'04'). '-' . $this->getInOut($year,'04', 'OUT'),
                'mei' => $this->getInOut($year,'05'). '-' . $this->getInOut($year,'05', 'OUT'),
                'jun' => $this->getInOut($year,'06'). '-' . $this->getInOut($year,'06', 'OUT'),
                'jul' => $this->getInOut($year,'07'). '-' . $this->getInOut($year,'07', 'OUT'),
                'agt' => $this->getInOut($year,'08'). '-' . $this->getInOut($year,'08', 'OUT'),
                'sep' => $this->getInOut($year,'09'). '-' . $this->getInOut($year,'09', 'OUT'),
                'okt' => $this->getInOut($year,'10'). '-' . $this->getInOut($year,'10', 'OUT'),
                'nov' => $this->getInOut($year,'11'). '-' . $this->getInOut($year,'11', 'OUT'),
                'des' => $this->getInOut($year,'12'). '-' . $this->getInOut($year,'12', 'OUT'),
            ])->where([
                'SubCategories.category_id IN('. $category_id .')'
            ]);
            // dd($results->all());
            if ($request_print == 'xlsx') {
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
                $sheet->getColumnDimension('I')->setAutoSize(true);
                $sheet->getColumnDimension('J')->setAutoSize(true);
                $sheet->getColumnDimension('K')->setAutoSize(true);
                $sheet->getColumnDimension('L')->setAutoSize(true);
                $sheet->getColumnDimension('M')->setAutoSize(true);
                $sheet->getColumnDimension('N')->setAutoSize(true);
                $sheet->getColumnDimension('O')->setAutoSize(true);
                $sheet->getColumnDimension('P')->setAutoSize(true);
                $sheet->getColumnDimension('Q')->setAutoSize(true);

                $sheet->getStyle('A1:Q2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A1', 'LAPORAN MUTASI STOK '. strtoupper($category_data));
                $sheet->setCellValue('A2', 'PERIODE : ' . $periode);
                $sheet->mergeCells('A1:Q1');
                $sheet->mergeCells('A2:Q2');
                //set style header
                $sheet->getStyle('A1:Q2')->getFont()->setBold(true);
                $sheet->getStyle('A1:Q2')->getFill()
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
                $sheet->setCellValue('B' . $start, 'ID');
                $sheet->setCellValue('C' . $start, 'Nama Barang');
                $sheet->setCellValue('D' . $start, 'Stok Awal');
                $sheet->setCellValue('E' . $start, 'Bulan');
                $sheet->setCellValue('Q' . $start, 'Stok Akhir');

                $sheet->getStyle('E'.$start)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('E' . ($start + 1), 'Jan');
                $sheet->setCellValue('F' . ($start + 1), 'Feb');
                $sheet->setCellValue('G' . ($start + 1), 'Mar');
                $sheet->setCellValue('H' . ($start + 1), 'Apr');
                $sheet->setCellValue('I' . ($start + 1), 'Mei');
                $sheet->setCellValue('J' . ($start + 1), 'Jun');
                $sheet->setCellValue('K' . ($start + 1), 'Jul');
                $sheet->setCellValue('L' . ($start + 1), 'Agt');
                $sheet->setCellValue('M' . ($start + 1), 'Sep');
                $sheet->setCellValue('N' . ($start + 1), 'Okt');
                $sheet->setCellValue('O' . ($start + 1), 'Nov');
                $sheet->setCellValue('P' . ($start + 1), 'Des');

                $sheet->mergeCells('A'.$start.':A'.($start + 1));
                $sheet->mergeCells('B'.$start.':B'.($start + 1));
                $sheet->mergeCells('C'.$start.':C'.($start + 1));
                $sheet->mergeCells('D'.$start.':D'.($start + 1));
                $sheet->mergeCells('Q'.$start.':Q'.($start + 1));
                
                $sheet->mergeCells('E'.$start.':P'.$start);
                $col = $start + 2;
                $no = 1;

                $total_saldo_awal = 0;
                $total_in = 0;
                $total_out = 0;
                $total_saldo_akhir = 0;

                foreach ($results as $grant) {
                    $total_saldo_awal += $grant->saldo_awal;
                    $total_in += $grant->in;
                    $total_out += $grant->out;

                    $sheet->setCellValue('A' . $col, $no);
                    $sheet->setCellValue('B' . $col, $grant->product_id);
                    $sheet->setCellValue('C' . $col, $grant->product_name);
                    $sheet->setCellValue('D' . $col, $grant->saldo_awal);
                    $sheet->setCellValue('E' . $col, $grant->jan);
                    $sheet->setCellValue('F' . $col, $grant->feb);
                    $sheet->setCellValue('G' . $col, $grant->mar);
                    $sheet->setCellValue('H' . $col, $grant->apr);
                    $sheet->setCellValue('I' . $col, $grant->mei);
                    $sheet->setCellValue('J' . $col, $grant->jun);
                    $sheet->setCellValue('K' . $col, $grant->jul);
                    $sheet->setCellValue('L' . $col, $grant->agt);
                    $sheet->setCellValue('M' . $col, $grant->sep);
                    $sheet->setCellValue('N' . $col, $grant->okt);
                    $sheet->setCellValue('O' . $col, $grant->nov);
                    $sheet->setCellValue('P' . $col, $grant->des);

                    $total = $grant->saldo_awal + $grant->jan + $grant->feb + $grant->mar + $grant->apr + $grant->mei + $grant->jun + $grant->jul + $grant->agt + $grant->sep + $grant->okt + $grant->nov + $grant->des;
                    $total_saldo_akhir += $total;

                    $sheet->setCellValue('Q' . $col,  $total);

                    $no++;
                    $col++;
                }

                $sheet->setCellValue('A' . $col, 'Total');
                $sheet->setCellValue('D' . $col, $total_saldo_awal);
                $sheet->setCellValue('E' . $col, $total_in);
                $sheet->setCellValue('F' . $col, $total_out);
                $sheet->setCellValue('G' . $col, $total_saldo_akhir);

                $sheet->mergeCells('A' . $col . ':C' . $col);
                $sheet->getStyle('A' . $col . ':C' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $col++;

                $end = $col - 1;
                $sheet->getStyle('A1:Q' . $end)->applyFromArray($styleArray);

                $writer = new Xls($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Laporan Mutasi Stok pada tahun '. $periode . '_' . rand(2, 32012) . '.xls"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                $this->viewBuilder()->layout('report');
                $this->render('pdf/index');
            }
        }
    }
}
