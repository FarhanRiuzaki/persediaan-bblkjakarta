<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Core\Configure;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * ReportReceivesController
 */
class ReportReceiptReclarificationsController extends AppController
{
    public function index()
    {
        $request_print = $this->request->query('print');
        $titleModule = 'Laporan Penerimaan Barang Reklarifikasi Masuk';
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
            $titleModule = 'Laporan Penerimaan Barang Reklarifikasi Masuk ' . $periode;

            $this->loadModel('ReceiptReclarificationsDetails');
            $results = $this->ReceiptReclarificationsDetails->find('all', [
                'contain' => [
                    'Products',
                    'ReceiptReclarifications',
                    'ReceiptReclarifications.ExpendituresReclarifications',
                    'ExpendituresReclarificationsDetails'
                ]
            ])->select([
                'code1' => 'ReceiptReclarifications.code',
                'code_ex' => 'ExpendituresReclarifications.code',
                'code' => 'Products.code',
                'unit' => 'Products.unit',
                'name' => 'Products.name',
                'date' => 'ReceiptReclarifications.date',
                'qty' => 'ReceiptReclarificationsDetails.qty',
                'qty_ex' => 'ExpendituresReclarificationsDetails.qty',
                'price' => 'ReceiptReclarificationsDetails.price',
                'price_ex' => 'ExpendituresReclarificationsDetails.price',
            ])->where([
                'DATE(ReceiptReclarifications.date) BETWEEN "' . $start . '" AND "' . $end . '"'
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
                $sheet->getColumnDimension('G')->setAutoSize(true);
                $sheet->getColumnDimension('H')->setAutoSize(true);

                $sheet->getStyle('A1:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A1', 'LAPORAN PENERIMAAN BARANG REKLARIFIKASI MASUK');
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
                $sheet->setCellValue('B' . $start, 'Kode Penerimaan Barang Reklarifikasi Masuk');
                $sheet->setCellValue('C' . $start, 'Kode Pengeluaran Reklarifikasi');
                $sheet->setCellValue('D' . $start, 'Nama Barang');
                $sheet->setCellValue('E' . $start, 'Jumlah Barang Reklarifikasi Masuk');
                $sheet->setCellValue('F' . $start, 'Jumlah Barang Reklarifikasi Keluar');
                $sheet->setCellValue('G' . $start, 'Harga Barang Reklarifikasi Masuk');
                $sheet->setCellValue('H' . $start, 'Harga Barang Reklarifikasi Keluar');
                $col = $start + 1;
                $no = 1;
                foreach ($results as $grant) {
                    $sheet->setCellValue('A' . $col, $no);
                    $sheet->setCellValue('B' . $col, $grant->code1);
                    $sheet->setCellValue('C' . $col, $grant->code_ex);
                    $sheet->setCellValue('D' . $col, $grant->code . '-' . $grant->name);
                    $sheet->setCellValue('E' . $col, number_format($grant->qty) . ' ' . $grant->unit);
                    $sheet->setCellValue('F' . $col, number_format($grant->qty_ex) . ' ' . $grant->unit);
                    $sheet->setCellValue('G' . $col, number_format($grant->price));
                    $sheet->setCellValue('H' . $col, number_format($grant->price_ex));
                    $no++;
                    $col++;
                }

                $end = $col - 1;
                $sheet->getStyle('A1:H' . $end)->applyFromArray($styleArray);

                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Laporan Penerimaan Barang Reklarifikasi Masuk' . rand(2, 32012) . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                $this->viewBuilder()->layout('report');
                $this->render('pdf/index');
            }
        }
    }
}
