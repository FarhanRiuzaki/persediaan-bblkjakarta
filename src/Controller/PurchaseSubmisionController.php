<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * PurchaseSubmisions Controller
 *
 * @property \App\Model\Table\PurchaseOrdersTable $PurchaseSubmisions
 *
 * @method \App\Model\Entity\PurchaseOrder[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PurchaseSubmisionController extends AppController
{
    private $titleModule = "Pengajuan Pembelian Barang";
    private $primaryModel = "PurchaseSubmisions";

    public function initialize()
    {
        parent::initialize();
        $this->set([
            'titleModule' => $this->titleModule,
            'primaryModel' => $this->primaryModel,
        ]);
        $this->loadModel('PurchaseRequestsDetails');
        $this->loadModel('PurchaseSubmisions');
        $this->loadModel('PurchaseSubmisionsDetails');
        $this->Auth->allow(['updateStatus']);
    }

    function beforeFilter(\Cake\Event\Event $event){
        parent::beforeFilter($event);
    
        if(isset($this->Security) && $this->request->isAjax() && ($this->action = 'index' || $this->action = 'delete')){
    
            $this->Security->config('validatePost',false);
            //$this->getEventManager()->off($this->Csrf);
    
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
        $whereType = '';
        if($this->userData->user_group_id == 4){ /* ATK */
            $whereType  = 'PurchaseSubmisions.type = 1';
        }elseif($this->userData->user_group_id == 6){ /* Reagen */
            $whereType  = 'PurchaseSubmisions.type = 2';
        }

        $status = '';
        if($this->userData->user_group_id == 5){  /*ULP*/
            $status = 'PurchaseSubmisions.status NOT IN(0,2)';
        }
        if($this->request->is('ajax')){
            $source = $this->{$this->primaryModel}->find('all', [
                'conditions' => [
                    $whereType,
                    $status
                ]
            ])->order([$this->primaryModel.'.id DESC']);
            $searchAble = [
                $this->primaryModel.'.date',

            ];
            $data = [
                'source'        => $source,
                'searchAble'    => $searchAble,
                'defaultField'  => 'PurchaseSubmisions.id',
                'defaultSort'   => 'desc',
                'defaultSearch' => [],
                'contain'       => [
                     'PurchaseSubmisionsDetails', 'PurchaseSubmisionsDetails.PurchaseRequests', 'CreatedUsers',
                ]
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
     * @param string|null $id Purchase Order id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request_print = $this->request->query('print');
        $productGroup = $this->PurchaseSubmisionsDetails->find('all',[
            'contain' => [
                'Products','PurchaseSubmisions'
            ],
            'conditions' => [
                'PurchaseSubmisions.id' => $id
            ]
        ])
        ->select([
            'name'      => 'Products.name',
            'unit'      => 'Products.unit',
            'code'      => 'Products.code',
            'product_id' => 'product_id',
            'total'     => 'SUM(PurchaseSubmisionsDetails.qty)'
        ])
        ->group(['product_id']); 
        // dd($productGroup->all());

        if (empty($request_print)) {
            $purchaseOrder = $this->PurchaseSubmisions->get($id, [
                'contain' => [ 'PurchaseSubmisionsDetails', 'PurchaseSubmisionsDetails.Products', 'CreatedUsers', 'ModifiedUsers', 'PurchaseSubmisionsDetails.PurchaseRequestsDetails', 'PurchaseSubmisionsDetails.PurchaseRequests.Institutes']
            ]);
            $this->set('purchaseOrder', $purchaseOrder);
        } else {
            $purchaseOrder = $this->PurchaseSubmisions->get($id, [
                'contain' => [ 'PurchaseSubmisionsDetails', 'PurchaseSubmisionsDetails.Products', 'CreatedUsers', 'ModifiedUsers', 'PurchaseSubmisionsDetails.PurchaseRequestsDetails', 'PurchaseSubmisionsDetails.PurchaseRequests.Institutes']
            ]);
            if ($request_print == 'pdf') {
                Configure::write('CakePdf', [
                    'engine' => [
                        'className' => 'CakePdf.WkHtmlToPdf',
                        'binary' => '/usr/local/bin/wkhtmltopdf',
                        // 'className' => 'CakePdf.WkHtmlToPdf', 'binary' => 'C:\wkhtmltopdf\bin\wkhtmltopdf.exe',
                        'options' => [
                            'print-media-type' => false,
                            'outline' => true,
                            'dpi' => 96,
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
                // $this->viewBuilder()->layout('printview');
                $this->set('purchaseOrder', $purchaseOrder);
            }
        }

        $name = '-';
        if(!empty($purchaseOrder->approve_by)){
            $user = $this->Users->get($purchaseOrder->approve_by);
            $name = $user->name;
        }

        $titleModuleRelated = 'Detail '.$this->titleModule;
        $titlesubModule = 'View ' . $this->titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $this->titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'titleModuleRelated','productGroup','name'));
        $this->set('purchaseOrder', $purchaseOrder);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($institute_id = '', $io = '')
    {
        $whereType = '';
        $type= 0;
        if($this->userData->user_group_id == 4){ /* ATK */
            $whereType  = 'PurchaseRequests.type = 1';
            $type= 1;
    }elseif($this->userData->user_group_id == 6){ /* Reagen */
            $whereType  = 'PurchaseRequests.type = 2';
            $type= 2;
    }

        $where = [
            'PurchaseRequests.institute_id' => $institute_id,
            'PurchaseRequests.status != '   => 2,
        ];

        $purchaseOrder = $this->PurchaseSubmisions->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = date('Y-m-d');
            $data['type'] = $type;

            $purchaseOrder = $this->PurchaseSubmisions->patchEntity($purchaseOrder, $data, [
                'associated' => [
                    'PurchaseSubmisionsDetails'
                ]
            ]);

            if ($this->PurchaseSubmisions->save($purchaseOrder)) {
                foreach($data['purchase_submisions_details'] as $key => $r){
                    $articles = $this->PurchaseRequestsDetails->get($r['purchase_requests_detail_id']);
                    $articles->status = '1';
                    $this->PurchaseRequestsDetails->save($articles);
                }

                $code = 200;
                $message = __('Permohonan Pembelian Barang berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Permohonan Pembelian Barang gagal disimpan. Silahkan ulangi kembali.');
                $var = $purchaseOrder->errors();
            }

            $this->set(compact('code', 'message', 'receive', 'data', 'var'));
            $this->set('_serialize', ['code', 'message', 'purchaseOrder', 'data', 'var']);
        }

        $titlesubModule = 'Proses '. $this->titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $this->titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $data = $this->PurchaseRequestsDetails->find('all', [
            'conditions' => [
                'PurchaseRequestsDetails.status' => '0',
                $whereType
            ],
            'contain' => [
                'Products','PurchaseRequests.Institutes'
            ]
        ]);
        $productGroup = $this->PurchaseRequestsDetails->find('all', [
            'conditions' => [
                'PurchaseRequestsDetails.status' => '0',
                $whereType
            ],
            'contain' => [
                'Products','PurchaseRequests.Institutes'
            ]
        ])
        ->select([
            'name'      => 'Products.name',
            'unit'      => 'Products.unit',
            'code'      => 'Products.code',
            'product_id' => 'product_id',
        ])
        ->group(['product_id']); 

        $type = '';
        if($this->request->getQuery('type') != null){
            $type = $this->request->getQuery('type');
            $this->set(compact('titleModule','cek' ,'breadCrumbs', 'titlesubModule', 'purchaseOrder',  'purchaseRequestsDetails','productGroup','data'));
            $this->render('add_pp');
        }
        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'purchaseOrder',  'purchaseRequestsDetails','productGroup','data'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Purchase Order id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('PurchaseRequestsDetails');
        $this->loadModel('Stocks');
        $priceStocks = $this->Stocks->find();
        $prd = $this->PurchaseSubmisions->PurchaseSubmisionsDetails->find();
        $productGroup = $this->PurchaseSubmisionsDetails->find('all',[
            'contain' => [
                'Products','PurchaseSubmisions'
            ],
            'conditions' => [
                'PurchaseSubmisions.id' => $id
            ]
        ])
        ->select([
            'name'      => 'Products.name',
            'unit'      => 'Products.unit',
            'code'      => 'Products.code',
            'product_id' => 'product_id',
        ])
        ->group(['product_id']); 
        $purchaseOrder = $this->PurchaseSubmisions->get($id, [
            'contain' => [ 'PurchaseSubmisionsDetails', 'PurchaseSubmisionsDetails.Products', 'CreatedUsers', 'ModifiedUsers', 'PurchaseSubmisionsDetails.PurchaseRequestsDetails', 'PurchaseSubmisionsDetails.PurchaseRequests.Institutes']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = date('Y-m-d');
            

            $purchaseOrder = $this->PurchaseSubmisions->patchEntity($purchaseOrder, $data, [
                'associated' => [
                    'PurchaseSubmisionsDetails'
                ]
            ]);
            // dd($purchaseOrder);
            if ($this->PurchaseSubmisions->save($purchaseOrder)) {
                $code = 200;
                $message = __('Permohonan Pembelian Barang berhasil diupdate.');
            } else {
                $code = 50;
                $message = __('Permohonan Pembelian Barang gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data', 'explodeId'));
            $this->set('_serialize', ['code', 'message', 'purchaseOrder', 'data', 'explodeId']);
        }

        $purchaseOrder->date = $purchaseOrder->date->format('d-m-Y');

        if ($purchaseOrder->date_freeze) {
            $purchaseOrder->date_freeze = $purchaseOrder->date_freeze->format('d-m-Y');
        }

        $titlesubModule = 'Edit ' . $this->titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $this->titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'purchaseOrder',  'purchaseRequestsDetails','productGroup'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Purchase Order id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $record = $this->{$this->primaryModel}->get($id, ['contain' => 'PurchaseSubmisionsDetails']);
        foreach($record['purchase_submisions_details'] as $key => $r){
            $articles = $this->PurchaseRequestsDetails->get($r['purchase_requests_detail_id']);
            $articles->status = '0';
            $this->PurchaseRequestsDetails->save($articles);
        }
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

    public function updateStatus($id = null)
    {
        $data           = $this->request->getData();
        $save = $this->PurchaseSubmisions->get($id, [
            'contain' => [
                'PurchaseSubmisionsDetails'
            ]
        ]);
        $data['approve_by']   = $this->userData->id;
        $data['approve_date'] = date('Y-m-d H:i:s');
        $data['status']       = '1';

        $save = $this->PurchaseSubmisions->patchEntity($save, $data, [
            'associated' => [
                'PurchaseSubmisionsDetails'
            ]
        ]);
        // dd($save);
        if($this->PurchaseSubmisions->save($save)){
            // dd($save);
            $code = 200;
            $message = __('Berhasil disimpan.');
        } else {
            // dd($save);
            $code = 50;
            $message = __('gagal disimpan. Silahkan ulangi kembali.');
        }

        $this->set(compact('code', 'message'));
        $this->set('_serialize', ['code', 'message']);
    }
}
