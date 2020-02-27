<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * PurchaseOrders Controller
 *
 * @property \App\Model\Table\PurchaseOrdersTable $PurchaseOrders
 *
 * @method \App\Model\Entity\PurchaseOrder[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PurchaseOrdersController extends AppController
{
    private $titleModule = "Pembelian Pesanan";
    private $primaryModel = "PurchaseOrders";

    public function initialize()
    {
        parent::initialize();
        $this->set([
            'titleModule' => $this->titleModule,
            'primaryModel' => $this->primaryModel,
        ]);
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
        if($this->request->is('ajax')){
            $source = $this->{$this->primaryModel}->find('all', [
                'conditions' => [
                    $this->whereInstitute
                ]
            ])->order([$this->primaryModel.'.id DESC']);
            $searchAble = [
                $this->primaryModel.'.nomor_spk',
                $this->primaryModel.'.date',
                'Suppliers.name',

            ];
            $data = [
                'source'        => $source,
                'searchAble'    => $searchAble,
                'defaultField'  => 'PurchaseRequests.id',
                'defaultSort'   => 'desc',
                'defaultSearch' => [],
                'contain'       => [
                    'Suppliers', 'PurchaseOrdersDetails', 'PurchaseOrdersDetails.PurchaseRequests', 'PurchaseOrdersDetails.PurchaseRequests.Institutes'
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

        if (empty($request_print)) {
            $purchaseOrder = $this->PurchaseOrders->get($id, [
                'contain' => ['Suppliers', 'PurchaseOrdersDetails', 'PurchaseOrdersDetails.Products', 'CreatedUsers', 'ModifiedUsers', 'PurchaseRequestsDetails', 'PurchaseOrdersDetails.PurchaseRequestsDetails', 'PurchaseOrdersDetails.PurchaseRequestsDetails.PurchaseRequests']
            ]);
            $this->set('purchaseOrder', $purchaseOrder);
        } else {
            $purchaseOrder = $this->PurchaseOrders->get($id, [
                'contain' => ['Suppliers', 'PurchaseOrdersDetails', 'PurchaseOrdersDetails.Products', 'CreatedUsers', 'ModifiedUsers', 'PurchaseRequestsDetails', 'PurchaseOrdersDetails.PurchaseRequestsDetails', 'PurchaseOrdersDetails.PurchaseRequestsDetails.PurchaseRequests']
            ]);
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
                $this->set('purchaseOrder', $purchaseOrder);
            }
        }

        $titleModule = 'Pembelian Pesanan';
        $titleModuleRelated = 'Detail Pembelian Pesanan';
        $titlesubModule = 'View ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'titleModuleRelated'));
        $this->set('purchaseOrder', $purchaseOrder);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($institute_id = '', $io = '')
    {
        $where = [
            'PurchaseRequests.institute_id' => $institute_id,
            'PurchaseRequests.status != '   => 2,
        ];

        $purchaseOrder = $this->PurchaseOrders->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);
            if ($data['date_freeze'] != null) {
                $data['date_freeze'] = $this->Utilities->generalitationDateFormat($data['date_freeze']);
            }

            $dataDetail = [];
            foreach($data['purchase_orders_details'] as $key => $r){
                if($r['qty'] != '0'){
                    $dataDetail[] = $r;
                }
            }

            $data['purchase_orders_details'] = $dataDetail;
            $purchaseOrder = $this->PurchaseOrders->patchEntity($purchaseOrder, $data, [
                'associated' => [
                    'PurchaseOrdersDetails'
                ]
            ]);

            if ($this->PurchaseOrders->save($purchaseOrder)) {
                $code = 200;
                $message = __('pembelian Pesanan berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('pembelian Pesanan gagal disimpan. Silahkan ulangi kembali.');
                $var = $purchaseOrder->errors();
            }

            $this->set(compact('code', 'message', 'receive', 'data', 'var'));
            $this->set('_serialize', ['code', 'message', 'purchaseOrder', 'data', 'var']);
        }

        $suppliers = $this->PurchaseOrders->Suppliers->find('list', ['order' => 'name asc']);
        $this->loadModel('PurchaseRequestsDetails');
        $this->loadModel('Stocks');
        $priceStocks = $this->Stocks->find();
        $prd = $this->PurchaseOrders->PurchaseOrdersDetails->find();
        $purchaseRequestsDetails = $this->PurchaseRequestsDetails->find('all', [
            'contain' => [
                'Products',
                'PurchaseRequests',
                'PurchaseRequests.Institutes'
            ],
            'conditions' => [
                $where
            ]
        ])->select([
            'PurchaseRequestsDetails__id' => 'PurchaseRequestsDetails.id',
            'PurchaseRequestsDetails__purchase_request_id' => 'PurchaseRequestsDetails.purchase_request_id',
            'PurchaseRequestsDetails__qty' => 'PurchaseRequestsDetails.qty',
            'PurchaseRequestsDetails__product_id' => 'PurchaseRequestsDetails.product_id',
            'Products__id' => 'Products.id',
            'Products__code' => 'Products.code',
            'Products__name' => 'Products.name',
            'PurchaseRequests__id' => 'PurchaseRequests.id',
            'PurchaseRequests__code' => 'PurchaseRequests.code',
            'PurchaseRequests__institute_id' => 'PurchaseRequests.institute_id',
            'Institutes__id' => 'Institutes.id',
            'Institutes__name' => 'Institutes.name',
            'saldo_po' => '(IFNULL((' .
                $prd->select(
                    [
                        'SUM' => $prd->func()->sum('qty')
                    ]
                )->where([
                    'purchase_request_id = PurchaseRequests__id',
                    'purchase_requests_detail_id = PurchaseRequestsDetails__id',
                    'product_id = Products.id'
                ])
            . '), 0))',
            'PurchaseRequestsDetails__price' => $priceStocks->select([
                'price'
            ])->where([
                'type = "IN"',
                'product_id = Products.id',
            ])->order(['date' => 'DESC'])->limit(1)
        ])->having('PurchaseRequestsDetails__qty > saldo_po');

        $titleModule = 'Pembelian Pesanan';
        $titlesubModule = 'Tambah Pembelian Pesanan';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'purchaseOrder', 'suppliers', 'purchaseRequestsDetails'));
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
        $prd = $this->PurchaseOrders->PurchaseOrdersDetails->find();
        $purchaseOrder = $this->PurchaseOrders->get($id, [
            'contain' => [
                'PurchaseOrdersDetails' => function ($q) use ($priceStocks,$prd) {
                    return $q->contain([
                        'Products',
                        'PurchaseRequestsDetails',
                        'PurchaseRequestsDetails.PurchaseRequests',
                        'PurchaseRequestsDetails.PurchaseRequests.Institutes',
                    ])->select([
                        'PurchaseOrdersDetails__id' => 'PurchaseOrdersDetails.id',
                        'PurchaseOrdersDetails__purchase_order_id' => 'PurchaseOrdersDetails.purchase_order_id',
                        'PurchaseOrdersDetails__purchase_request_id' => 'PurchaseOrdersDetails.purchase_request_id',
                        'PurchaseOrdersDetails__purchase_requests_detail_id' => 'PurchaseOrdersDetails.purchase_request_id',
                        'PurchaseOrdersDetails__purchase_product_id' => 'PurchaseOrdersDetails.product_id',
                        'PurchaseOrdersDetails__price' => 'PurchaseOrdersDetails.price',
                        'PurchaseOrdersDetails__qty' => 'PurchaseOrdersDetails.qty',
                        'PurchaseRequestsDetails__id' => 'PurchaseRequestsDetails.id',
                        'PurchaseRequestsDetails__purchase_request_id' => 'PurchaseRequestsDetails.purchase_request_id',
                        'PurchaseRequestsDetails__qty' => 'PurchaseRequestsDetails.qty',
                        'Products__id' => 'Products.id',
                        'Products__code' => 'Products.code',
                        'Products__name' => 'Products.name',
                        'PurchaseRequests__id' => 'PurchaseRequests.id',
                        'PurchaseRequests__code' => 'PurchaseRequests.code',
                        'PurchaseRequests__institute_id' => 'PurchaseRequests.institute_id',
                        'Institutes__id' => 'Institutes.id',
                        'Institutes__name' => 'Institutes.name',
                        'saldo_po' => '(IFNULL((' .
                            $prd->select(
                                [
                                    'SUM' => $prd->func()->sum('qty')
                                ]
                            )->where([
                                'purchase_request_id = PurchaseRequests__id',
                                'purchase_requests_detail_id = PurchaseRequestsDetails__id',
                                'product_id = Products.id',
                                'id != PurchaseOrdersDetails__id'
                            ])
                        . '), 0))'
                    ])->having('PurchaseRequestsDetails__qty > saldo_po');
                }
            ]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);
            if ($data['date_freeze'] != null) {
                $data['date_freeze'] = $this->Utilities->generalitationDateFormat($data['date_freeze']);
            }

            $purchaseOrder = $this->PurchaseOrders->patchEntity($purchaseOrder, $data, [
                'associated' => [
                    'PurchaseOrdersDetails'
                ]
            ]);

            if ($this->PurchaseOrders->save($purchaseOrder)) {
                if (!empty($data['delete_detail'])) {
                    $explodeId = explode(',', $data['delete_detail']);

                    foreach ($explodeId as $id) {
                        $PurchaseOrdersDetails = $this->PurchaseOrders->PurchaseOrdersDetails->get($id);
                        $this->PurchaseOrders->PurchaseOrdersDetails->delete($PurchaseOrdersDetails);
                    }
                }
                $code = 200;
                $message = __('pembelian Pesanan berhasil diupdate.');
            } else {
                $code = 50;
                $message = __('pembelian Pesanan gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data', 'explodeId'));
            $this->set('_serialize', ['code', 'message', 'purchaseOrder', 'data', 'explodeId']);
        }

        $suppliers = $this->PurchaseOrders->Suppliers->find('list', ['limit' => 200]);
        $purchaseOrder->date = $purchaseOrder->date->format('d-m-Y');

        if ($purchaseOrder->date_freeze) {
            $purchaseOrder->date_freeze = $purchaseOrder->date_freeze->format('d-m-Y');
        }

        $titleModule = 'Pembelian Pesanan';
        $titlesubModule = 'Edit Pembelian Pesanan';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'purchaseOrder', 'suppliers', 'purchaseRequestsDetails'));
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
