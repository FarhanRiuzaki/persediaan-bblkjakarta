<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Core\Configure;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * ReportReceivesController
 */
class ReportPurchaseOrdersController extends AppController
{
    public function index()
    {
        $request_print = $this->request->query('print');
        $titleModule = 'Report Pembelian Pesanan';
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
            $titleModule = 'Laporan Pembelian Pesanan ' . $periode;

            $this->loadModel('PurchaseOrdersDetails');
            $results = $this->PurchaseOrdersDetails->find('all', [
                'contain' => [
                    'Products',
                    'PurchaseOrders',
                    'PurchaseOrders.Suppliers',
                    'PurchaseRequestsDetails.PurchaseRequests'
                ]
            ])->select([
                'nama_pemasok' => 'Suppliers.name',
                'nomor_spk' => 'PurchaseOrders.nomor_spk',
                'code_ex' => 'PurchaseRequests.code',
                'code' => 'Products.code',
                'unit' => 'Products.unit',
                'name' => 'Products.name',
                'date' => 'PurchaseOrders.date',
                'qty' => 'PurchaseOrdersDetails.qty',
                'qty_pr' => 'PurchaseRequestsDetails.qty',
                'price' => 'PurchaseOrdersDetails.price',
                'subtotal' => '((PurchaseRequestsDetails.qty*1) * (PurchaseOrdersDetails.price*1))'
            ])->where([
                'DATE(PurchaseOrders.date) BETWEEN "' . $start . '" AND "' . $end . '"'
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
                $sheet->getColumnDimension('I')->setAutoSize(true);
                $sheet->getStyle('A1:J2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A1', 'LAPORAN PEMBELIAN PESANAN');
                $sheet->setCellValue('A2', 'PERIODE : ' . $periode);
                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:J2');
                //set style header
                $sheet->getStyle('A1:J2')->getFont()->setBold(true);
                $sheet->getStyle('A1:J2')->getFill()
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
                $sheet->setCellValue('B' . $start, 'Nama Pemasok');
                $sheet->setCellValue('C' . $start, 'Kode Permintaan Pembelian');
                $sheet->setCellValue('D' . $start, 'Nomor PO');
                $sheet->setCellValue('E' . $start, 'Nomor SPK');
                $sheet->setCellValue('F' . $start, 'Nama barang');
                $sheet->setCellValue('G' . $start, 'Jumlah Barang Permintaan Pembelian');
                $sheet->setCellValue('H' . $start, 'Jumlah Barang PO');
                $sheet->setCellValue('I' . $start, 'Harga Barang Pesanan Pembelian');
                $sheet->setCellValue('J' . $start, 'Subtotal');
                $col = $start + 1;
                $no = 1;

                $total_qty_pr = 0;
                $total_qty = 0;
                $total_price = 0;
                $total_subtotal = 0;

                foreach ($results as $grant) {
                    $total_qty_pr += $grant->qty_pr;
                    $total_qty += $grant->qty;
                    $total_price += $grant->price;
                    $total_subtotal += $grant->subtotal;

                    $sheet->setCellValue('A' . $col, $no);
                    $sheet->setCellValue('B' . $col, $grant->nama_pemasok);
                    $sheet->setCellValue('C' . $col, $grant->code_ex);
                    $sheet->setCellValue('D' . $col, $grant->nomor_po);
                    $sheet->setCellValue('E' . $col, $grant->nomor_spk);
                    $sheet->setCellValue('F' . $col, $grant->code . '-' . $grant->name);
                    $sheet->setCellValue('G' . $col, number_format($grant->qty_pr) . ' ' . $grant->unit);
                    $sheet->setCellValue('H' . $col, number_format($grant->qty) . ' ' . $grant->unit);
                    $sheet->setCellValue('I' . $col, number_format($grant->price));
                    $sheet->setCellValue('J' . $col, number_format($grant->subtotal));
                    $no++;
                    $col++;
                }
                $sheet->setCellValue('A' . $col, 'Total');
                $sheet->setCellValue('G' . $col, number_format($total_qty_pr));
                $sheet->setCellValue('H' . $col, number_format($total_qty));
                $sheet->setCellValue('I' . $col, number_format($total_price));
                $sheet->setCellValue('J' . $col, number_format($total_subtotal));
                $sheet->mergeCells('A' . $col . ':F' . $col);
                $sheet->getStyle('A' . $col . ':F' . $col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $col++;

                $end = $col - 1;
                $sheet->getStyle('A1:J' . $end)->applyFromArray($styleArray);
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Laporan Pembelian Pesanan' . rand(2, 32012) . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output');
            } else {
                $this->viewBuilder()->layout('report');
                $this->render('pdf/index');
            }
        }
    }
}
