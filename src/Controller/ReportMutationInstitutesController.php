<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\Core\Configure;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\I18n\Time;

/**
 * ReportMutationsController
 */
class ReportMutationInstitutesController extends AppController
{
    public function index()
    {
        $request_print = $this->request->query('print');
        $titleModule = 'Laporan Pemakaian Barang Instalasi';
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
            $titleModule = 'Laporan Pemakaian Barang ' . $periode;

            $this->loadModel('UseInstitutes');
            $results = $this->UseInstitutes->find('all', [
                'conditions' => [
                    'UseInstitutes.institute_id' => $this->userData->institute_id
                ],
                'contain' => [
                    'UseInstitutesDetails', 'Registrations', 'InspectionParameters', 'UseInstitutesDetails.Products', 'UseInstitutesDetails.Products.ProductUnits'
                ]
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
            } else {
                $this->viewBuilder()->layout('report');
                $this->render('pdf/index');
            }
        }
    }
}
