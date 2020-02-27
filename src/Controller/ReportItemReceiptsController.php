<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Core\Configure;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * ReportReceivesController
 */
class ReportItemReceiptsController extends AppController
{
    public function index()
    {
        $this->loadModel('ItemReceiptsDetails');
        $this->loadModel('Users');
        $this->loadModel('Categories');
        $categories = $this->Categories->find('list')->order(['name' => 'ASC']);
        
        $request_print = $this->request->query('print');
        $titleModule = 'Laporan Barang Masuk';
        if (empty($request_print)) {

            $titlesubModule = 'Filter ' . $titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => $titlesubModule
            ];
            $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule','categories'));
        } else {
            $start = $this->request->query('start');
            $end = $this->request->query('end');
            $category = $this->request->query('category');
            $cek = $this->userData->user_group_id;
            
            $whereCategory = '';
            if($cek == 4){ /*gudang ATK*/
                $whereCategory = 'SubCategories.category_id != 2';
            }elseif($cek == 6){  /*gudang Reagen*/
                $whereCategory = 'SubCategories.category_id = 2';
            }
            if(!empty($category)){
                $whereCategory = 'SubCategories.category_id = '. $category .'';
            }
            // dd($whereCategory);
            
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
            $titleModule = 'Laporan Barang Masuk ' . $periode;

            $cek = $this->userData->user_group_id;
            $where = '';
            if($cek == 4 || $cek == 6){ /*gudang ATK dan Reagen*/
                $find_group = $this->Users->find('all', [
                    'conditions' => [
                        'user_group_id' => $cek
                    ],
                ]);

                $id = [];
                foreach($find_group as $key => $val){
                    $id[] = $val->id;
                }
                $id =  implode(", ",$id);

                $where = 'ItemReceipts.created_by IN('.$id.')';
            }


            $results = $this->ItemReceiptsDetails->find('all', [
                'contain' => [
                    'Products.SubCategories',
                    'ItemReceipts',
                ],
                'conditions' => [
                    $whereCategory
                ]
            ])->select([
                'code1' => 'ItemReceipts.code',
                'code' => 'Products.code',
                'unit' => 'Products.unit',
                'name' => 'Products.name',
                'date' => 'ItemReceipts.date',
                'qty' => 'ItemReceiptsDetails.qty',
                'price' => 'ItemReceiptsDetails.price',
                'subtotal' => '(ItemReceiptsDetails.price * ItemReceiptsDetails.qty)',
            ])->where([
                'DATE(ItemReceipts.date) BETWEEN "' . $start . '" AND "' . $end . '"',
                $where
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
                // $sheet->getColumnDimension('E')->setAutoSize(true);
                // $sheet->getColumnDimension('F')->setAutoSize(true);
                // $sheet->getColumnDimension('G')->setAutoSize(true);
                $sheet->getStyle('A1:D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A1', 'LAPORAN BARANG MASUK');
                $sheet->setCellValue('A2', 'PERIODE : ' . $periode);
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                //set style header
                $sheet->getStyle('A1:D2')->getFont()->setBold(true);
                $sheet->getStyle('A1:D2')->getFill()
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
                $sheet->setCellValue('B' . $start, 'Kode Barang Masuk');
                // $sheet->setCellValue('C' . $start, 'Sumber Hibah');
                $sheet->setCellValue('C' . $start, 'Nama barang');
                $sheet->setCellValue('D' . $start, 'Jumlah Barang');
                // $sheet->setCellValue('F' . $start, 'Harga Barang');
                // $sheet->setCellValue('G' . $start, 'Subtotal');
                $col = $start + 1;
                $no = 1;

                $total_qty = 0;
                $total_price = 0;
                $total_subtotal = 0;
                foreach ($results as $grant) {
                    $total_qty += $grant->qty;
                    $total_price += $grant->price;
                    $total_subtotal += $grant->subtotal;

                    $sheet->setCellValue('A' . $col, $no);
                    $sheet->setCellValue('B' . $col, $grant->code1);
                    // $sheet->setCellValue('C' . $col, $grant->source);
                    $sheet->setCellValue('C' . $col, '[' . $grant->code . '] ' . $grant->name);
                    $sheet->setCellValue('D' . $col, number_format($grant->qty) . ' ' . $grant->unit);
                    // $sheet->setCellValue('F' . $col, number_format($grant->price));
                    // $sheet->setCellValue('G' . $col, number_format($grant->subtotal));
                    $no++;
                    $col++;
                }

                $sheet->setCellValue('A' . $col, 'TOTAL');
                $sheet->setCellValue('D' . $col, number_format($total_qty));
                // $sheet->setCellValue('F' . $col, number_format($total_price));
                // $sheet->setCellValue('G' . $col, number_format($total_subtotal));
                $sheet->mergeCells('A' . $col . ':C' . $col);
                $sheet->getStyle('A' . $col . ':C' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $col++;

                $end = $col - 1;
                $sheet->getStyle('A1:D' . $end)->applyFromArray($styleArray);

                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Laporan Barang Masuk' . rand(2, 32012) . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                $this->viewBuilder()->layout('report');
                $this->render('pdf/index');
            }
        }
    }
}
