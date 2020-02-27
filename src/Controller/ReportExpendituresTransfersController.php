<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Core\Configure;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * ReportReceivesController
 */
class ReportExpendituresTransfersController extends AppController
{
    public function index()
    {
        $request_print = $this->request->query('print');
        $titleModule = 'Laporan Pengeluaran Barang Transfer Antar Lembaga';
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
            $titleModule = 'Laporan Pengeluaran Barang Transfer Antar Lembaga ' . $periode;

            $this->loadModel('ExpendituresTransfersDetails');
            $results = $this->ExpendituresTransfersDetails->find('all', [
                'contain' => [
                    'Products',
                    'ExpendituresTransfers',
                    'ExpendituresTransfers.Institutions'
                ]
            ])->select([
                'code1' => 'ExpendituresTransfers.code',
                'lembaga' => 'Institutions.name',
                'code_lembaga' => 'Institutions.code',
                'code' => 'Products.code',
                'unit' => 'Products.unit',
                'name' => 'Products.name',
                'date' => 'ExpendituresTransfers.date',
                'qty' => 'ExpendituresTransfersDetails.qty',
                'price' => 'ExpendituresTransfersDetails.price',
                'subtotal' => '(ExpendituresTransfersDetails.price * ExpendituresTransfersDetails.qty)',
            ])->where([
                'DATE(ExpendituresTransfers.date) BETWEEN "' . $start . '" AND "' . $end . '"'
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
                $sheet->getStyle('A1:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A1', 'LAPORAN PENGELUARAN BARANG TRANSFER ANTAR LEMBAGA');
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
                $sheet->setCellValue('B' . $start, 'Kode Pengeluaran Barang Transfer Antar Lembaga');
                $sheet->setCellValue('C' . $start, 'Nama Lembaga');
                $sheet->setCellValue('D' . $start, 'Nama Barang');
                $sheet->setCellValue('E' . $start, 'Jumlah Barang');
                $sheet->setCellValue('F' . $start, 'Harga Barang');
                $sheet->setCellValue('G' . $start, 'Subtotal');
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
                    $sheet->setCellValue('C' . $col, $grant->code_lembaga . '-' . $grant->lembaga);
                    $sheet->setCellValue('D' . $col, $grant->code . '-' . $grant->name);
                    $sheet->setCellValue('E' . $col, number_format($grant->qty) . ' ' . $grant->unit);
                    $sheet->setCellValue('F' . $col, number_format($grant->price));
                    $sheet->setCellValue('G' . $col, number_format($grant->subtotal));
                    $no++;
                    $col++;
                }

                $sheet->setCellValue('A' . $col, 'TOTAL');
                $sheet->setCellValue('E' . $col, number_format($total_qty));
                $sheet->setCellValue('F' . $col, number_format($total_price));
                $sheet->setCellValue('G' . $col, number_format($total_subtotal));
                $sheet->mergeCells('A' . $col . ':D' . $col);
                $sheet->getStyle('A' . $col . ':D' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $col++;

                $end = $col - 1;
                $sheet->getStyle('A1:G' . $end)->applyFromArray($styleArray);

                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Laporan Pengeluaran Barang Transfer Antar Lembaga' . rand(2, 32012) . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                $this->viewBuilder()->layout('report');
                $this->render('pdf/index');
            }
        }
    }
}
