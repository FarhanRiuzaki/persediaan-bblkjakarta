<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;
/**
 * PurchaseRequests Controller
 *
 * @property \App\Model\Table\PurchaseRequestsTable $PurchaseRequests
 *
 * @method \App\Model\Entity\PurchaseRequest[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PurchaseRequestsController extends AppController
{
    private $titleModule = "Permintaan Pembelian";
    private $primaryModel = "PurchaseRequests";

    public function initialize()
    {
        parent::initialize();
        $this->set([
            'titleModule' => $this->titleModule,
            'primaryModel' => $this->primaryModel,
        ]);
        $this->loadModel('SubCategories');
        $this->Auth->allow(['updateStatus']);
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
            $source = $this->{$this->primaryModel}->find('all', [
                'conditions' => [
                    $this->whereInstitute
                ]
            ])->order([$this->primaryModel.'.id DESC']);
            $searchAble = [
                $this->primaryModel.'.code',
                $this->primaryModel.'.date',
                'Institutes.name'
            ];
            $data = [
                'source'        => $source,
                'searchAble'    => $searchAble,
                'defaultField'  => 'PurchaseRequests.id',
                'defaultSort'   => 'desc',
                'defaultSearch' => [
                    [    
                        'keyField' => $this->primaryModel.'.id',
                        'condition' => 'IS NOT',
                        'value' => null
                    ]
                ],
                'contain'       => [
                    'Institutes'
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
     * @param string|null $id Purchase Request id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request_print = $this->request->query('print');
        if (empty($request_print)) {
            $purchaseRequest = $this->PurchaseRequests->get($id, [
                'contain' => ['Institutes', 'PurchaseRequestsDetails', 'PurchaseRequestsDetails.Products', 'CreatedUsers', 'ModifiedUsers']
            ]);

            $this->loadModel('PurchaseOrdersDetails');
            $purchaseOrders_id = $this->PurchaseOrdersDetails->find('all', [
                'conditions' => [
                    'purchase_request_id' => $purchaseRequest->id,
                    'PurchaseOrdersDetails.qty >' => 0
                ]
            ])->select([
                'id' => 'PurchaseOrdersDetails.purchase_order_id'
            ])->group('purchase_order_id');

            $this->loadModel('PurchaseOrders');
            $index = 0;
            foreach ($purchaseOrders_id as $purchaseOrder_id) {
                $purchaseOrder_id = $purchaseOrder_id->id;

                $purchaseOrders[$index++] = $this->PurchaseOrders->get($purchaseOrder_id, [
                    'contain' => [
                        'PurchaseOrdersDetails' => [
                            'conditions' => [
                                'PurchaseOrdersDetails.purchase_request_id' => $id
                            ]
                        ],
                        'Suppliers', 'PurchaseOrdersDetails.Products', 'PurchaseOrdersDetails.PurchaseRequestsDetails', 'CreatedUsers', 'ModifiedUsers'
                    ]
                ]);
            }

            $this->set(compact('purchaseOrders'));
            $this->set('purchaseRequest', $purchaseRequest);
        } else {
            $purchaseRequest = $this->PurchaseRequests->get($id, [
                'contain' => ['Institutes', 'PurchaseRequestsDetails', 'PurchaseRequestsDetails.Products', 'CreatedUsers', 'ModifiedUsers']
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
                // $this->viewBuilder()->layout('printview');
                $this->RequestHandler->renderAs($this, 'pdf');
                $this->set('purchaseRequest', $purchaseRequest);
            }
        }

        $titleModule = 'Permintaan Pembelian';
        $titleModuleRelated = 'Detail Permintaan Pembelian';
        $titlesubModule = 'View ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'titleModuleRelated'));
        $this->set('purchaseRequest', $purchaseRequest);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $purchaseRequest = $this->{$this->primaryModel}->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date']   = $this->Utilities->generalitationDateFormat($data['date']);
            $data['status'] = '2'; //approval

            $purchaseRequest = $this->{$this->primaryModel}->patchEntity($purchaseRequest, $data, [
                'associated' => [
                    'PurchaseRequestsDetails'
                ]
            ]);
            // dd($purchaseRequest);

            if ($this->{$this->primaryModel}->save($purchaseRequest)) {
                $code = 200;
                $message = __('Permintaan pembelian berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Permintaan pembelian gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'purchaseRequest', 'data']);
        }

        $institutes = $this->{$this->primaryModel}->Institutes->find('list')->order('Institutes.name ASC');
        $subCategories = $this->SubCategories->find('list', ['limit' => 200])->order(['name ASC']);

        $titleModule = 'Permintaan Pembelian';
        $titlesubModule = 'Tambah Permintaan Pembelian';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'purchaseRequest', 'institutes','subCategories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Purchase Request id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $purchaseRequest = $this->PurchaseRequests->get($id, [
            'contain' => ['PurchaseRequestsDetails', 'PurchaseRequestsDetails.Products']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $purchaseRequest = $this->PurchaseRequests->patchEntity($purchaseRequest, $data, [
                'associated' => [
                    'PurchaseRequestsDetails'
                ]
            ]);

            if ($this->PurchaseRequests->save($purchaseRequest)) {
                if (!empty($data['delete_detail'])) {
                    $explodeId = explode(',', $data['delete_detail']);

                    foreach ($explodeId as $id) {
                        $PurchaseRequestsDetails = $this->PurchaseRequests->PurchaseRequestsDetails->get($id);
                        $this->PurchaseRequests->PurchaseRequestsDetails->delete($PurchaseRequestsDetails);
                    }
                }
                $code = 200;
                $message = __('Permintaan pembelian berhasil diupdate.');
            } else {
                $code = 50;
                $message = __('Permintaan pembelian gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'purchaseRequest', 'data']);
        }

        $institutes = $this->PurchaseRequests->Institutes->find('list')->order('Institutes.name ASC');
        $purchaseRequest->date = $purchaseRequest->date->format('d-m-Y');

        $titleModule = 'Permintaan Pembelian';
        $titlesubModule = 'Edit Permintaan Pembelian';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'purchaseRequest', 'institutes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Purchase Request id.
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

    public function updateStatus($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $record = $this->{$this->primaryModel}->get($id);
        // if ($this->{$this->primaryModel}->delete($record)) {
        //     $code = 200;
        //     $message = __($this->Utilities->message_alert($this->titleModule,5));
        //     $status = 'success';
        // } else {
        //     $code = 99;
        //     $message = __($this->Utilities->message_alert($this->titleModule,6));
        //     $status = 'error';
        // }
        // if($this->request->is('ajax')){
        //     $this->set('code',$code);
        //     $this->set('message',$message);
        //     $this->set('_serialize',['code','message']);
        // }else{
        //     $this->Flash->{$status}($message);
        //     return $this->redirect(['action' => 'index']);
        // }
    }
}
