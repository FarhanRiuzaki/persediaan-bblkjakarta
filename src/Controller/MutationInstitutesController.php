<?php

namespace App\Controller;

use Cake\Routing\Router;
use Cake\I18n\Time;

/**
 * ReportMutationsController
 */
class MutationInstitutesController extends AppController
{
    public function index()
    {
        $this->loadModel('ExpendituresDistributionsDetails');
        $this->loadModel('StockInstitutes');
        $intitute_id = $this->userData->institute_id;


        $where = '';
        if(!empty($this->request->query('date'))){
            $date   = explode('-', $this->request->query('date'));
            $year   = $date[0];
            $month  = $date[1];

            $where = 'MONTH(ExpendituresDistributions.date) = "'.$month.'" AND YEAR(ExpendituresDistributions.date) = "'.$year.'"';
        }

        // dd($where);

        $expendituresDistributionsDetailsIn = $this->ExpendituresDistributionsDetails->find('all', [
            'contain' => [
                'ExpendituresDistributions'
            ]
        ]);
        $stockInstitutes = $this->StockInstitutes->find();

        $expendituresDistributionsDetails = $this->ExpendituresDistributionsDetails->find('all', [
            'conditions' => [
                'ExpendituresDistributions.institute_id' => $intitute_id,
                $where
            ],
            'contain' => [
                'ExpendituresDistributions', 'Products', 'Products.ProductUnits'
            ],
            'group' => ['ExpendituresDistributionsDetails.product_id']
        ])->select([
            'product_id' => 'ExpendituresDistributionsDetails.product_id',
            'product_code' => 'Products.code',
            'product_name' => 'Products.name',
            'product_unit' => 'Products.unit',
            'product_unit_less' => 'ProductUnits.unit',
            'product_unit_qty' => 'ProductUnits.qty',
            'product_in' => '(IFNULL((' . $expendituresDistributionsDetailsIn->select(
                            [
                                'SUM' => $expendituresDistributionsDetailsIn->func()->sum('qty')
                            ])
                            ->where([
                                'ExpendituresDistributions.institute_id = ' . $intitute_id,
                                'product_id = Products.id',
                            ]) . '), 0))',
            'product_out' => '(IFNULL((' . $stockInstitutes->select(
                            [
                                'SUM' => $stockInstitutes->func()->sum('qty')
                            ])
                            ->where([
                                'institute_id = ' . $intitute_id,
                                'product_id = Products.id',
                            ]) . '), 0))',
        ]);
        // dd($expendituresDistributionsDetails->all());
        $this->set(compact('expendituresDistributionsDetails'));
        $titleModule = 'Monitoring Stock Barang Instalasi';
        $titlesubModule = 'Filter ' . $titleModule;
        // dd($intitute_id);
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titlesubModule
        ];
        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule'));
    }
}
