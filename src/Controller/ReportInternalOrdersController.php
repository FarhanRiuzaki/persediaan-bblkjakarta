<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Core\Configure;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * ReportReceivesController
 */
class ReportInternalOrdersController extends AppController
{
    public function index()
    {
        $this->loadModel('Institutes');
        $this->loadModel('Categories');
        $request_print = $this->request->query('print');
        $titleModule = 'Laporan Permintaan User';
        
        $categories = $this->Categories->find('list')->order(['name' => 'ASC']);

        if (empty($request_print)) {

            $institutes = $this->Institutes->find('all')->order('Institutes.name ASC');
            // dd($institutes->all());
            $titlesubModule = 'Filter ' . $titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => $titlesubModule
            ];
            $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule','institutes','categories'));
        } else {
            $start = $this->request->query('start');
            $end = $this->request->query('end');
            $type_lap = $this->request->query('type_lap');
            $category = $this->request->query('category');

            $institute_id = $this->request->query('institute_id');
            $arrayMonth = $this->Utilities->monthArray();

            $startYear = date('Y', strtotime($start));
            $startMonth = date('m', strtotime($start));
            $startDay = date('d', strtotime($start));
            $startSql = $startDay.' '.$arrayMonth[$startMonth*1].' '.$startYear;

            $endYear = date('Y', strtotime($end));
            $endMonth = date('m', strtotime($end));
            $endDay = date('d', strtotime($end));
            $endSql = $endDay.' '.$arrayMonth[$endMonth*1].' '.$endYear;

            $periode = $startSql . ' s.d ' . $endSql;
            $titleModule = 'Laporan Permintaan User ' . $periode;

            $this->loadModel('InternalOrdersDetails');

            $where = '';
            if($institute_id != 'all'){
                $where = ['InternalOrders.institute_id' => $institute_id];
            }

            //Kategori
            $cek = $this->userData->user_group_id;
            $whereCategory = '';
            if($cek == 4){ /*gudang ATK*/
                $whereCategory = 'SubCategories.category_id != 2';
            }elseif($cek == 6){  /*gudang Reagen*/
                $whereCategory = 'SubCategories.category_id = 2';
            }
            if(!empty($category)){
                $whereCategory = 'SubCategories.category_id = "'. $category .'"';
            }
            // dd($category);
            // dd($where);
            if($type_lap == 1){
                $results = $this->InternalOrdersDetails->find('all', [
                    'contain' => [
                        'Products.SubCategories',
                        'InternalOrders',
                        'InternalOrders.Institutes',
                    ],
                    'conditions' => [
                        $where,
                        $whereCategory
                    ]
                ])->select([
                    'code1' => 'InternalOrders.code',
                    'name1' => 'Institutes.name',
                    'code' => 'Products.code',
                    'unit' => 'Products.unit',
                    'name' => 'Products.name',
                    'date' => 'InternalOrders.date',
                    'qty' => 'InternalOrdersDetails.qty',
                ])->where([
                    'DATE(InternalOrders.date) BETWEEN "' . $start . '" AND "' . $end . '"'
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
                    $sheet->getStyle('A1:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->setCellValue('A1', 'LAPORAN PERMINTAAN USER');
                    $sheet->setCellValue('A2', 'PERIODE : ' . $periode);
                    $sheet->mergeCells('A1:E1');
                    $sheet->mergeCells('A2:E2');
                    //set style header
                    $sheet->getStyle('A1:E2')->getFont()->setBold(true);
                    $sheet->getStyle('A1:E2')->getFill()
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
                    $sheet->setCellValue('B' . $start, 'Kode Permintaan User');
                    $sheet->setCellValue('C' . $start, 'Nama Instalasi');
                    $sheet->setCellValue('D' . $start, 'Nama Barang');
                    $sheet->setCellValue('E' . $start, 'Jumlah Barang');
                    $col = $start + 1;
                    $no = 1;
    
                    $total = 0;
                    foreach ($results as $grant) {
                        $sheet->setCellValue('A' . $col, $no);
                        $sheet->setCellValue('B' . $col, $grant->code1);
                        $sheet->setCellValue('C' . $col, $grant->name1);
                        $sheet->setCellValue('D' . $col, $grant->code . '-' . $grant->name);
                        $sheet->setCellValue('E' . $col, number_format($grant->qty) . ' ' . $grant->unit);
                        $no++;
                        $col++;
    
                        $total += $grant->qty;
                    }
    
                    $sheet->setCellValue('A' . $col, 'TOTAL');
                    $sheet->setCellValue('E' . $col, number_format($total));
                    $sheet->mergeCells('A' . $col . ':D' . $col);
                    $sheet->getStyle('A' . $col . ':D' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
                    $no++;
                    $col++;
    
                    $end = $col - 1;
                    $sheet->getStyle('A1:E' . $end)->applyFromArray($styleArray);
    
                    $writer = new Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.ms-excel');
                    header('Content-Disposition: attachment;filename="Laporan Permintaan User' . rand(2, 32012) . '.xlsx"');
                    header('Cache-Control: max-age=0');
                    $writer->save('php://output');
                } else {
                    $this->viewBuilder()->layout('report');
                    $this->render('pdf/index');
                }
            }else{
                $groupProduct = $this->InternalOrdersDetails->find('all', [
                    'contain' => [
                        'Products.SubCategories',
                        'InternalOrders',
                        'InternalOrders.Institutes',
                    ],
                    'conditions' => [
                        $where,
                        $whereCategory
                    ]
                ])
                ->where([
                    'DATE(InternalOrders.date) BETWEEN "' . $start . '" AND "' . $end . '"'
                ])
                ->select([
                    'name'      => 'Products.name',
                    'unit'      => 'Products.unit',
                    'code'      => 'Products.code',
                    'product_id' => 'product_id',
                    'total'     => 'SUM(InternalOrdersDetails.qty)'
                ])
                ->group(['product_id']); 

                $results = $this->InternalOrdersDetails->find('all', [
                    'contain' => [
                        'Products.SubCategories',
                        'InternalOrders',
                        'InternalOrders.Institutes',
                    ],
                    'conditions' => [
                        $where,
                        $whereCategory
                    ]
                ])->select([
                    'code1' => 'InternalOrders.code',
                    'name1' => 'Institutes.name',
                    'code' => 'Products.code',
                    'product_id' => 'Products.id',
                    'unit' => 'Products.unit',
                    'name' => 'Products.name',
                    'date' => 'InternalOrders.date',
                    'qty' => 'SUM(InternalOrdersDetails.qty)',
                ])->where([
                    'DATE(InternalOrders.date) BETWEEN "' . $start . '" AND "' . $end . '"'
                ])->group(['name1', 'product_id']);
                $this->set(compact('titleModule', 'results', 'periode', 'start', 'end','groupProduct'));
                $this->autoRender = false;

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
                    $this->render('indexDetail');
                } else {
                    $this->viewBuilder()->layout('report');
                    $this->render('pdf/index_detail');
                }
            }
        }
    }
}
