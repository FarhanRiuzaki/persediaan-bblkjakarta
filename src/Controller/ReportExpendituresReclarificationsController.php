<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Core\Configure;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * ReportReceivesController
 */
class ReportExpendituresReclarificationsController extends AppController
{
    public function index()
    {
        $request_print = $this->request->query('print');
        $titleModule = 'Laporan Pengeluaran Reklarifikasi';
        if (empty($request_print)) {
            $titlesubModule = 'Filter ' . $titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => $titlesubModule
            ];
            $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule'));
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

            $periode = $startSql . ' s.d ' . $endSql;
            $titleModule = 'Laporan Pengeluaran Reklarifikasi ' . $periode;

            $this->loadModel('ExpendituresReclarificationsDetails');
            $results = $this->ExpendituresReclarificationsDetails->find('all', [
                'contain' => [
                    'Products',
                    'ExpendituresReclarifications',
                ]
            ])->select([
                'code1' => 'ExpendituresReclarifications.code',
                'code' => 'Products.code',
                'unit' => 'Products.unit',
                'name' => 'Products.name',
                'date' => 'ExpendituresReclarifications.date',
                'qty' => 'ExpendituresReclarificationsDetails.qty',
                'price' => 'ExpendituresReclarificationsDetails.price',
                'subtotal' => '(ExpendituresReclarificationsDetails.price * ExpendituresReclarificationsDetails.qty)',
            ])->where([
                'DATE(ExpendituresReclarifications.date) BETWEEN "' . $start . '" AND "' . $end . '"'
            ]);
            $this->set(compact('titleModule', 'results', 'periode', 'start', 'end'));
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
                $sheet->getStyle('A1:F2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A1', 'LAPORAN PENGELUARAN REKLARIFIKASI');
                $sheet->setCellValue('A2', 'PERIODE : ' . $periode);
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                //set style header
                $sheet->getStyle('A1:F2')->getFont()->setBold(true);
                $sheet->getStyle('A1:F2')->getFill()
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
                $sheet->setCellValue('B' . $start, 'Kode Pengeluaran Reklarifikasi');
                $sheet->setCellValue('C' . $start, 'Nama Barang');
                $sheet->setCellValue('D' . $start, 'Jumlah Barang');
                $sheet->setCellValue('E' . $start, 'Harga Barang');
                $sheet->setCellValue('F' . $start, 'Subtotal');
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
                    $sheet->setCellValue('C' . $col, $grant->code . '-' . $grant->name);
                    $sheet->setCellValue('D' . $col, number_format($grant->qty) . ' ' . $grant->unit);
                    $sheet->setCellValue('E' . $col, number_format($grant->price));
                    $sheet->setCellValue('F' . $col, number_format($grant->subtotal));
                    $no++;
                    $col++;
                }

                $sheet->setCellValue('A' . $col, 'TOTAL');
                $sheet->setCellValue('D' . $col, number_format($total_qty));
                $sheet->setCellValue('E' . $col, number_format($total_price));
                $sheet->setCellValue('F' . $col, number_format($total_subtotal));
                $sheet->mergeCells('A' . $col . ':C' . $col);
                $sheet->getStyle('A' . $col . ':C' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $col++;

                $end = $col - 1;
                $sheet->getStyle('A1:E' . $end)->applyFromArray($styleArray);

                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="LAPORAN PENGELUARAN REKLARIFIKASI.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                $this->viewBuilder()->layout('report');
                $this->render('pdf/index');
            }
        }
    }
}
