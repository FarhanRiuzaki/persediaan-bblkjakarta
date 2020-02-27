<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;


/**
 * ExpendituresDistributions Controller
 *
 * @property \App\Model\Table\ExpendituresDistributionsTable $ExpendituresDistributions
 *
 * @method \App\Model\Entity\ExpendituresDistribution[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ApprovalController extends AppController
{

    private $titleModule = "Approval Permintaan User";
    private $primaryModel = "InternalOrders";

    public function initialize()
    {
        parent::initialize();
        $this->set([
            'titleModule' => $this->titleModule,
            'primaryModel' => $this->primaryModel,
        ]);
        $this->Auth->allow(['add', 'UpdateStatus']);
        $this->loadModel('InternalOrders');
        $this->loadModel('InternalOrdersDetails');
        $this->loadModel('ExpendituresDistributions');
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
        if($this->request->is('ajax')){
            
            $where = 'InternalOrders.institute_id = '.$this->Auth->user('institute_id').'';
            if($this->Auth->user('user_group_id') == 1){
                $where = '';
            }
            $source = $this->{$this->primaryModel}->find('all', [
                'conditions' => [
                    'InternalOrders.status' => '0',
                    $where
                ]
            ]);
            $searchAble = [
                $this->primaryModel . '.code',
                $this->primaryModel . '.date',
                'Institutes.name',
            ];
            $data = [
                'source'=>$source,
                'searchAble' => $searchAble,
                'defaultField' => $this->primaryModel.'.id',
                'defaultSort' => 'desc',
                'defaultSearch' => [
                    [    
                        'keyField' => 'institute_id',
                        'condition' => '=',
                        'value' => $this->userData->institute_id
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
     * @param string|null $id Expenditures Distribution id.
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
                        'className' => 'CakePdf.DomPdf',
                        'options' => [
                            'print-media-type' => false,
                            'outline' => true,
                            'dpi' => 96
                        ],
                    ],
                    'margin' => [
                        'bottom' => 30,
                        'left' => 30,
                        'right' => 32,
                        'top' => 30
                    ],
                    'orientation' => 'potrait',
                    'pageSize' => 'A4',
                    'download' => false,
                ]);
                $this->viewBuilder()->layout('printview');
                $this->RequestHandler->renderAs($this, 'pdf');
                $this->set('internalOrder', $internalOrder);
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


    public function UpdateStatus()
    {
        if ($this->request->is(['ajax'])) {
            // dd($this->request->getData());
            $id = $this->request->getData('id');

            if($id !=null){
                $internalOrder = $this->InternalOrders->get($id, [
                    'contain' => ['InternalOrdersDetails']
                ]);
                $data['status'] = '2';
                $internalOrder = $this->InternalOrders->patchEntity($internalOrder, $data, [
                    'associated' => [
                        'InternalOrdersDetails'
                    ]
                ]);
                // dd($internalOrder);
    
                if ($this->InternalOrders->save($internalOrder)) {
                    $code = 200;
                    $message = __('Permintaan user berhasil disimpan.');
                } else {
                    $code = 50;
                    $message = __('Permintaan user gagal disimpan. Silahkan ulangi kembali.');
                }
                $this->set(compact('code', 'message'));
                $this->set('_serialize', ['code', 'message']);
            }
        }
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($institute_id = null, $internal_order_id = null)
    {
        date_default_timezone_set("Asia/Jakarta");
        $expendituresDistribution = $this->InternalOrders->get($internal_order_id, [
            'contain' => ['InternalOrdersDetails']
        ]);
        // dd($internal_order_id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['status']         = 0;
            $data['approve_date']   = date("Y-m-d H:i:s");
            $data['approve_by']     = $this->userData->id;
            
            $expendituresDistribution = $this->InternalOrders->patchEntity($expendituresDistribution, $data,[ 
                'associated' => [
                    'InternalOrdersDetails'
                ]
            ]);

            if ($this->InternalOrders->save($expendituresDistribution)) {
                $this->loadModel('InternalOrders');
                //hapus 
                $gets = [];
                foreach($data['internal_orders_details'] as $key => $val){
                    if(!empty($val['id'])){
                        $gets['get_id'][$key] = $val['id'];
                    }else{
                        $gets['get_id'][$key] = 0;
                    }
                }
                $imp = implode(', ', $gets['get_id']);
                $this->InternalOrdersDetails->deleteAll('internal_order_id = '. $internal_order_id .' AND id NOT IN ('. $imp .')',false);     

                $code = 200;
                $message = __('Approve Permintaan User berhasil disimpan.');
                $errors = [];
            } else {
                $code = 50;
                $message = __('Approve Permintaan User gagal disimpan. Silahkan ulangi kembali.');
                $errors = $expendituresDistribution->errors();
            }

            $this->set(compact('code', 'message', 'expendituresDistribution', 'data','errors'));
            $this->set('_serialize', ['code', 'message', 'expendituresDistribution', 'data','errors']);
        } else {
            if (!empty($institute_id)) {
                $expendituresDistribution->institute_id = $institute_id;
            }

            if (!empty($internal_order_id)) {
                $expendituresDistribution->internal_order_id = $internal_order_id;
            }
            $institutes = $this->ExpendituresDistributions->Institutes->find('list', ['order' => 'name asc']);

            $results = [];
            if (!empty($expendituresDistribution->institute_id)) {
                $internalOrders = $this->ExpendituresDistributions->InternalOrders->find('list', [
                    'conditions' => [
                        'institute_id' => $expendituresDistribution->institute_id
                    ],
                    'keyField'  =>'id',
                    'valueField'=> 'code'
                ]);

                $this->loadModel('InternalOrdersDetails');

                $this->loadModel('Products');
                $this->loadModel('Stocks');
                $this->loadModel('ExpendituresDistributionsDetails');

                $InStocks = $this->Stocks->find();
                $OutStocks = $this->Stocks->find();
                $priceStocks = $this->Stocks->find();
                $expendituresDistributionsDetails = $this->ExpendituresDistributionsDetails->find();

                $data = $this->InternalOrdersDetails->find('all', [
                    'conditions' => [
                        'internal_order_id' => $expendituresDistribution->internal_order_id
                    ],
                    'contain' => ['Products']
                ])->select([
                    'id' => 'Products.id',
                    'internal_order_detail_id' => 'InternalOrdersDetails.id',
                    'internal_order_id' => 'InternalOrdersDetails.internal_order_id',
                    'code' => 'Products.code',
                    'unit' => 'Products.unit',
                    'name' => 'Products.name',
                    'qty' => 'InternalOrdersDetails.qty',
                    'note' => 'InternalOrdersDetails.note',
                    'saldo_product' => '(IFNULL((' .
                                            $InStocks->select(
                                                [
                                                    'SUM' => $InStocks->func()->sum('qty')
                                                ]
                                            )->where([
                                                'type = "IN"',
                                                'product_id = Products.id'
                                            ])
                                        . '), 0)) - (IFNULL((' .
                                            $OutStocks->select(
                                                [
                                                    'SUM' => $OutStocks->func()->sum('qty')
                                                ]
                                            )->where([
                                                'type = "OUT"',
                                                'product_id = Products.id'
                                            ])
                                        . '), 0))',
                    'saldo_dist' => '(IFNULL((' .
                                            $expendituresDistributionsDetails->select(
                                                [
                                                    'SUM' => $expendituresDistributionsDetails->func()->sum('qty')
                                                ]
                                            )->where([
                                                'internal_order_id = InternalOrdersDetails.internal_order_id',
                                                'internal_orders_detail_id = InternalOrdersDetails.id',
                                                'product_id = Products.id'
                                            ])
                                        . '), 0))',
                    'price' => $priceStocks->select([
                        'price'
                    ])->where([
                        'type = "IN"',
                        'product_id = Products.id',
                    ])->order(['date' => 'DESC'])->limit(1)
                ])->having('qty > saldo_dist');

                $a = 0;

                foreach ($data as $key => $val) {
                    $results[$a]['id'] = $val->id;
                    $results[$a]['internal_order_detail_id'] = $val->internal_order_detail_id;
                    $results[$a]['internal_order_id'] = $val->internal_order_id;
                    $results[$a]['name'] = '[' . $val->code . '] ' . $val->name;
                    $results[$a]['qty'] = $val->qty;
                    $results[$a]['note'] = $val->note;
                    $results[$a]['unit'] = $val->unit;
                    $results[$a]['saldo'] = $val->saldo_product;
                    $results[$a]['saldo_dist'] = $val->saldo_dist;
                    $results[$a]['sisa'] = $val->qty - $val->saldo_dist;
                    $results[$a]['price'] = $val->price;
                    $a++;
                }
            }

            $titleModule = 'Permintaan user';
            $titlesubModule = 'Approve ' . $titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => $titleModule,
                Router::url(['action' => 'add']) => $titlesubModule
            ];

            $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'expendituresDistribution', 'internalOrders', 'institutes', 'results'));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Expenditures Distribution id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('Stocks');

        $InStocks = $this->Stocks->find();
        $OutStocks = $this->Stocks->find();
        $priceStocks = $this->Stocks->find();

        $expendituresDistributionsDetails = $this->ExpendituresDistributions->ExpendituresDistributionsDetails->find();
        $expendituresDistribution = $this->ExpendituresDistributions->get($id, [
            'contain' => [
                'ExpendituresDistributionsDetails' => function ($q) use ($InStocks,$OutStocks,$expendituresDistributionsDetails) {
                    return $q->contain(['Products', 'InternalOrdersDetails'])->select([
                        'ExpendituresDistributionsDetails__id' => 'ExpendituresDistributionsDetails.id',
                        'ExpendituresDistributionsDetails__expenditures_distribution_id' => 'ExpendituresDistributionsDetails.expenditures_distribution_id',
                        'ExpendituresDistributionsDetails__product_id' => 'ExpendituresDistributionsDetails.product_id',
                        'ExpendituresDistributionsDetails__internal_order_id' => 'ExpendituresDistributionsDetails.internal_order_id',
                        'ExpendituresDistributionsDetails__internal_order_detail_id' => 'ExpendituresDistributionsDetails.internal_orders_detail_id',
                        'ExpendituresDistributionsDetails__qty' => 'ExpendituresDistributionsDetails.qty',
                        'InternalOrdersDetails__qty' => 'InternalOrdersDetails.qty',
                        'ExpendituresDistributionsDetails__price' => 'ExpendituresDistributionsDetails.price',
                        'saldo_product' => '(IFNULL((' .
                                        $InStocks->select(
                                            [
                                                'SUM' => $InStocks->func()->sum('qty')
                                            ]
                                        )->where([
                                            'type = "IN"',
                                            'product_id = Products.id'
                                        ])
                                    . '), 0)) - (IFNULL((' .
                                        $OutStocks->select(
                                            [
                                                'SUM' => $OutStocks->func()->sum('qty')
                                            ]
                                        )->where([
                                            'type = "OUT"',
                                            'product_id = Products.id',
                                            'ref_id != ExpendituresDistributionsDetails__id',
                                            'ref_table != "expenditures_distributions_details"',
                                        ])
                                    . '), 0))',
                        'saldo_dist' => '(IFNULL((' .
                                        $expendituresDistributionsDetails->select(
                                            [
                                                'SUM' => $expendituresDistributionsDetails->func()->sum('qty')
                                            ]
                                        )->where([
                                            'internal_order_id = ExpendituresDistributionsDetails__internal_order_id',
                                            'ExpendituresDistributionsDetails__internal_order_detail_id',
                                            'product_id = Products.id',
                                            'id != ExpendituresDistributionsDetails__id'
                                        ])
                                    . '), 0))',
                        'Products__id' => 'Products.id',
                        'Products__code' => 'Products.code',
                        'Products__name' => 'Products.name',
                    ]);
                }
            ]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $expendituresDistribution = $this->ExpendituresDistributions->patchEntity($expendituresDistribution, $data, [
                'associated' => [
                    'ExpendituresDistributionsDetails'
                ]
            ]);
            if ($this->ExpendituresDistributions->save($expendituresDistribution)) {
                if (!empty($data['delete_detail'])) {
                    $explodeId = explode(',', $data['delete_detail']);

                    foreach ($explodeId as $id) {
                        $ExpendituresDistributionsDetails = $this->ExpendituresDistributions->ExpendituresDistributionsDetails->get($id);
                        $this->ExpendituresDistributions->ExpendituresDistributionsDetails->delete($ExpendituresDistributionsDetails);
                    }
                }
                $this->loadModel('InternalOrders');
                // $this->InternalOrders->updateStatus($expendituresDistribution->internal_order_id);

                $code = 200;
                $message = __('Distribusi barang berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Distribusi barang gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'expendituresDistribution', 'data'));
            $this->set('_serialize', ['code', 'message', 'expendituresDistribution', 'data']);
        } else {
            $institutes = $this->ExpendituresDistributions->Institutes->find('list', ['order' => 'name asc']);
            $internalOrders = $this->ExpendituresDistributions->InternalOrders->find('list', [
                'conditions' => [
                    'institute_id' => $expendituresDistribution->institute_id
                ],
                'keyField'  =>'id',
                'valueField'=> 'code'
            ]);
            $expendituresDistribution->date = $expendituresDistribution->date->format('d-m-Y');

            $titleModule = 'Distribusi Barang';
            $titlesubModule = 'Edit ' . $titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => 'List ' . $titleModule,
                Router::url(['action' => 'update']) => $titlesubModule
            ];

            $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'expendituresDistribution', 'institutes', 'internalOrders'));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Expenditures Distribution id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $record = $this->{$this->primaryModel}->get($id);
        if ($this->{$this->primaryModel}->delete($record)) {
            $this->Redis->destroyCacheUserAuth($record);
            $this->Redis->deleteAllCacheAcos($record);
            $this->Redis->destroyCacheUrlHome($record);
            $this->Redis->destroyCacheSideNav($record);
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
