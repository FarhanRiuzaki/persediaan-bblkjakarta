<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * InternalOrders Controller
 *
 * @property \App\Model\Table\InternalOrdersTable $InternalOrders
 *
 * @method \App\Model\Entity\InternalOrder[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InternalOrdersController extends AppController
{
    private $titleModule = "Permintaan User";
    private $primaryModel = "InternalOrders";

    public function initialize()
    {
        parent::initialize();
        $this->set([
            'titleModule' => $this->titleModule,
            'primaryModel' => $this->primaryModel,
        ]);
        $this->loadModel('Users');
    }

    function beforeFilter(\Cake\Event\Event $event){
        parent::beforeFilter($event);
    
        if(isset($this->Security) && $this->request->isAjax() && ($this->action = 'index' || $this->action = 'delete')){
    
            //$this->getEventManager()->off($this->Csrf);
            $this->Security->config('validatePost',false);
        }
    
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        // dd($this->whereInstitute);
        $cek = $this->userData->user_group_id;
        // dd($cek);
        $where = [
            $this->whereInstitute,
        ];
        if($cek == 4){ /*gudang ATK*/
            $where = [
                $this->whereInstitute,
                'type' => 1,
            ];
        }elseif($cek == 6){  /*gudang Reagen*/
            $where = [
                $this->whereInstitute,
                'type' => 2,
            ];
        }
        if($this->request->is('ajax')){
            $source = $this->{$this->primaryModel}->find('all', [
                'conditions' => $where
            ]);
            $searchAble = [
                $this->primaryModel.'.code',
            ];
            $data = [
                'source'=>$source,
                'searchAble' => $searchAble,
                'defaultField' => $this->primaryModel.'.id',
                'defaultSort' => 'desc',
                'defaultSearch' => [
                    [    
                        'keyField' => $this->primaryModel.'.id',
                        'condition' => 'IS NOT',
                        'value' => null
                    ]
                ],
                'contain' => ['Institutes'],
            ];
            $dataTable   = $this->Datatables->make($data);  
            $this->set('aaData',$dataTable['aaData']);
            $this->set('iTotalDisplayRecords',$dataTable['iTotalDisplayRecords']);
            $this->set('iTotalRecords',$dataTable['iTotalRecords']);
            $this->set('sColumns',$dataTable['sColumns']);
            $this->set('sEcho',$dataTable['sEcho']);
            $this->set('_serialize',['aaData','iTotalDisplayRecords','iTotalRecords','sColumns','sEcho']);
        }else{
            $titlesubModule = "List ".$this->titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => $titlesubModule
            ];
            $this->set(compact('breadCrumbs','titlesubModule'));
        }
    }

    /**
     * View method
     *
     * @param string|null $id Internal Order id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request_print = $this->request->query('print');
        if (empty($request_print)) {
            $internalOrder = $this->{$this->primaryModel}->get($id, [
                'contain' => ['Institutes', 'InternalOrdersDetails', 'InternalOrdersDetails.Products', 'CreatedUsers', 'ModifiedUsers', 'ExpendituresDistributions', 'ExpendituresDistributions.ExpendituresDistributionsDetails', 'ExpendituresDistributions.Institutes', 'ExpendituresDistributions.ExpendituresDistributionsDetails.Products', 'ExpendituresDistributions.ExpendituresDistributionsDetails.InternalOrdersDetails'],
            ]);

            $approve = [];
            foreach($internalOrder->expenditures_distributions as $expenditures_distribution) {
                foreach($expenditures_distribution->expenditures_distributions_details as $expenditures_distributions_detail) {
                    if(empty($approve[$expenditures_distributions_detail->product_id])) {
                        $approve[$expenditures_distributions_detail->product_id] = $expenditures_distributions_detail->qty;
                    } else {
                        $approve[$expenditures_distributions_detail->product_id] += $expenditures_distributions_detail->qty;
                    }
                }
            }

            $this->set('internalOrder', $internalOrder);
        } else {
            $internalOrder = $this->{$this->primaryModel}->get($id, [
                'contain' => ['Institutes', 'InternalOrdersDetails', 'InternalOrdersDetails.Products', 'CreatedUsers', 'ModifiedUsers', 'ExpendituresDistributions', 'ExpendituresDistributions.ExpendituresDistributionsDetails', 'ExpendituresDistributions.Institutes', 'ExpendituresDistributions.ExpendituresDistributionsDetails.Products', 'ExpendituresDistributions.ExpendituresDistributionsDetails.InternalOrdersDetails'],
            ]);

            $approve = [];
            foreach($internalOrder->expenditures_distributions as $expenditures_distribution) {
                foreach($expenditures_distribution->expenditures_distributions_details as $expenditures_distributions_detail) {
                    if(empty($approve[$expenditures_distributions_detail->product_id])) {
                        $approve[$expenditures_distributions_detail->product_id] = $expenditures_distributions_detail->qty;
                    } else {
                        $approve[$expenditures_distributions_detail->product_id] += $expenditures_distributions_detail->qty;
                    }
                }
            }

            if ($request_print == 'pdf') {
                Configure::write('CakePdf', [
                    'engine' => [
                        // 'className' => 'CakePdf.WkHtmlToPdf', 'binary' => '/usr/local/bin/wkhtmltopdf',
                        'className' => 'CakePdf.WkHtmlToPdf', 'binary' => 'C:\wkhtmltopdf\bin\wkhtmltopdf.exe',
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
                $this->set('internalOrder', $internalOrder);
                if($this->userData->user_group_id == 3){
                    $this->render('viewUser');
                }
            }
        }

        $titleModule = 'Permintaan User';
        $titleModuleRelated = 'Detail Permintaan User';
        $titlesubModule = 'View ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];
        $user = $this->Auth->user();
        $this->set(compact('user','titleModule', 'breadCrumbs', 'titlesubModule', 'titleModuleRelated', 'approve'));
        $this->set('internalOrder', $internalOrder);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $internalOrder = $this->{$this->primaryModel}->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date']   = $this->Utilities->generalitationDateFormat($data['date']);
            $data['status'] = 3;

            $internalOrder = $this->{$this->primaryModel}->patchEntity($internalOrder, $data, [
                'associated' => [
                    'InternalOrdersDetails'
                ]
            ]);
            // dd($internalOrder);

            if ($this->{$this->primaryModel}->save($internalOrder)) {
                $code = 200;
                $message = __('Permintaan user berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Permintaan user gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'internalOrder', 'data']);
        }

        $institutes = $this->{$this->primaryModel}->Institutes->find('list')->order('Institutes.name ASC');

        $titleModule = 'Permintaan User';
        $titlesubModule = 'Tambah Permintaan User';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'internalOrder', 'institutes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Internal Order id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('InternalOrdersDetails');
        $this->loadModel('Stocks');
        $internalOrder = $this->InternalOrders->get($id, [
            'contain' => ['InternalOrdersDetails', 'InternalOrdersDetails.Products']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);
            $data['status'] = 3;
            
            $internalOrder = $this->InternalOrders->patchEntity($internalOrder, $data, [
                'associated' => [
                    'InternalOrdersDetails'
                ]
            ]);
            
            // get data hapus detail barang masuk
            $gets = [];
            foreach($data['item_receipts_details'] as $key => $val){
                if(!empty($val['id'])){
                    $gets['get_id'][$key] = $val['id'];
                }else{
                    $gets['get_id'][$key] = 0;
                }
            }
            $imp = implode(', ', $gets['get_id']);
            
            // get data hapus id di stok
            $find = $this->InternalOrdersDetails->find('all')
            ->where(['internal_order_id = '.$id.' AND id NOT IN('.$imp.')']);
            $stok_id = [];
            foreach($find as $key => $val){
                $stok_id['id'][$key] = $val->id;
            }

            if(!empty($stok_id)){
                $ref_id = implode(', ', $stok_id['id']);
            
                //hapus stok 
                $this->Stocks->deleteAll('ref_table = "internal_orders_details" AND ref_id IN ('. $ref_id .')',false);
            }
            
            //hapus detail barang masuk
            $this->InternalOrdersDetails->deleteAll('internal_order_id = '. $id .' AND id NOT IN ('. $imp .')',false);
            

            if ($this->InternalOrders->save($internalOrder)) {
                // if (!empty($data['delete_detail'])) {
                //     $explodeId = explode(',', $data['delete_detail']);

                //     foreach ($explodeId as $id) {
                //         $InternalOrdersDetails = $this->InternalOrders->InternalOrdersDetails->get($id);
                //         $this->InternalOrders->InternalOrdersDetails->delete($InternalOrdersDetails);
                //     }
                // }
                $code = 200;
                $message = __('Permintaan user berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Permintaan user gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'internalOrder', 'data']);
        }

        $institutes = $this->InternalOrders->Institutes->find('list')->order('Institutes.name ASC');
        $internalOrder->date = $internalOrder->date->format('d-m-Y');

        $titleModule = 'Permintaan User';
        $titlesubModule = 'Edit Permintaan User';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'internalOrder', 'institutes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Internal Order id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $record = $this->{$this->primaryModel}->get($id);
        if ($this->{$this->primaryModel}->delete($record)) {
            $code = 200;
            $message = __($this->Utilities->message_alert($this->titleModule,5));
            $status = 'success';
        } else {
            $code = 99;
            $message = __($this->Utilities->message_alert($this->titleModule,6));
            $status = 'error';
        }
        if($this->request->is('ajax')){
            $this->set('code',$code);
            $this->set('message',$message);
            $this->set('_serialize',['code','message']);
        }else{
            $this->Flash->{$status}($message);
            return $this->redirect(['action' => 'index']);
        }
    }
}
