<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;


/**
 * ReceiptPurchases Controller
 *
 * @property \App\Model\Table\ReceiptPurchasesTable $ReceiptPurchases
 *
 * @method \App\Model\Entity\ReceiptPurchase[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReceiptPurchasesController extends AppController
{

    private $titleModule = "Penerimaan Barang Pembelian";
    private $primaryModel = "ReceiptPurchases";

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('PurchaseOrders');

        $this->set([
            'titleModule' => $this->titleModule,
            'primaryModel' => $this->primaryModel,
        ]);
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
            $source = $this->{$this->primaryModel}->find('all')->order([$this->primaryModel.'.id DESC']);
            $searchAble = [
                $this->primaryModel. '.code',
                'PurchaseOrders.nomor_spk',
                'Suppliers.name'
            ];
            $data = [
                'source'=>$source,
                'searchAble' => $searchAble,
                'defaultField' => $this->primaryModel.'.id',
                'defaultSort' => 'desc',
                'contain' => ['PurchaseOrders.Suppliers'],
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
     * @param string|null $id Receipt Purchase id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request_print = $this->request->query('print');
        if (empty($request_print)) {
            $receiptPurchase = $this->{$this->primaryModel}->get($id, [
                'contain' => ['PurchaseOrders', 'ReceiptPurchasesDetails.ReceiptPurchases', 'ReceiptPurchasesDetails.Products', 'ReceiptPurchasesDetails.PurchaseOrdersDetails', 'ReceiptPurchasesDetails.PurchaseOrdersDetails.PurchaseOrders', 'CreatedUsers', 'ModifiedUsers']
            ]);
            $this->set('receiptPurchase', $receiptPurchase);
        } else {
            $receiptPurchase = $this->{$this->primaryModel}->get($id, [
                'contain' => ['PurchaseOrders', 'ReceiptPurchasesDetails.ReceiptPurchases', 'ReceiptPurchasesDetails.Products', 'ReceiptPurchasesDetails.PurchaseOrdersDetails', 'ReceiptPurchasesDetails.PurchaseOrdersDetails.PurchaseOrders', 'CreatedUsers', 'ModifiedUsers']
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
                $this->set('receiptPurchase', $receiptPurchase);
            }
        }

        $titleModule = 'Penerimaan Barang Pembelian';
        $titleModuleRelated = 'Detail Penerimaan Barang Pembelian';
        $titlesubModule = 'View ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'titleModuleRelated'));
        $this->set('receiptPurchase', $receiptPurchase);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $receiptPurchase = $this->{$this->primaryModel}->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $receiptPurchase = $this->{$this->primaryModel}->patchEntity($receiptPurchase, $data, [
                'associated' => [
                    'ReceiptPurchasesDetails'
                ]
            ]);

            if ($this->{$this->primaryModel}->save($receiptPurchase)) {
                $code = 200;
                $message = __('Penerimaan barang pembelian berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Penerimaan barang pembelian gagal disimpan. Silahkan ulangi kembali.');
                $var = $receiptPurchase->errors();
            }

            $this->set(compact('code', 'message', 'receive', 'data', 'var'));
            $this->set('_serialize', ['code', 'message', 'receiptPurchase', 'data', 'var']);
        }

        $purchaseOrders = $this->PurchaseOrders->find('list', [
            'keyField' => 'id',
            'valueField' => function ($purchaseOrder) {
                return $purchaseOrder->supplier->get('name');
            },
            'contain' => [
                'Suppliers',
            ],
            'group' => [
                'PurchaseOrders.id',
                'Suppliers.id'
            ],
            'order' => [
                'Suppliers.name ASC'
            ]
        ])
        ->select($this->PurchaseOrders)
        ->select($this->PurchaseOrders->Suppliers)
        ->select([
            'total' => '
                (
                    SELECT
                    COUNT(*)
                    FROM
                    purchase_orders_details PurchaseOrdersDetails
                    WHERE
                    PurchaseOrdersDetails.purchase_order_id = `PurchaseOrders`.`id`
                    AND
                    (
                        (PurchaseOrdersDetails.qty) - (
                            SELECT
                            IFNULL(SUM(qty),0)
                            FROM
                            receipt_purchases_details
                            WHERE
                            receipt_purchases_details.purchase_orders_detail_id = PurchaseOrdersDetails.id
                        )
                    ) > 0
                )
            '
        ])->having('total > 0');

        $titleModule = 'Penerimaan Barang Pembelian';
        $titlesubModule = 'Tambah Penerimaan Barang Pembelian';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'receiptPurchase', 'purchaseOrders'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Receipt Purchase id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $receiptPurchase = $this->{$this->primaryModel}->get($id, [
            'contain' => ['ReceiptPurchasesDetails', 'ReceiptPurchasesDetails.Products', 'ReceiptPurchasesDetails.PurchaseOrdersDetails.PurchaseOrders']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $receiptPurchase = $this->{$this->primaryModel}->patchEntity($receiptPurchase, $data, [
                'associated' => [
                    'ReceiptPurchasesDetails'
                ]
            ]);

            if ($this->{$this->primaryModel}->save($receiptPurchase)) {
                if (!empty($data['delete_detail'])) {
                    $explodeId = explode(',', $data['delete_detail']);

                    foreach ($explodeId as $id) {
                        $ReceiptPurchasesDetails = $this->{$this->primaryModel}->ReceiptPurchasesDetails->get($id);
                        $this->{$this->primaryModel}->ReceiptPurchasesDetails->delete($ReceiptPurchasesDetails);
                    }
                }
                $code = 200;
                $message = __('Penerimaan barang pembelian berhasil diupdate.');
            } else {
                $code = 50;
                $message = __('Penerimaan barang pembelian gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data', 'explodeId'));
            $this->set('_serialize', ['code', 'message', 'receiptPurchase', 'data', 'explodeId']);
        }

        $purchaseOrders = $this->PurchaseOrders->find('list', [
            'keyField' => 'id',
            'valueField' => function ($purchaseOrder) {
                return $purchaseOrder->supplier->get('name');
            },
            'contain' => [
                'Suppliers',
            ],
            'group' => [
                'PurchaseOrders.id',
                'Suppliers.id'
            ],
            'order' => [
                'Suppliers.name ASC'
            ]
        ])
        ->select($this->PurchaseOrders)
        ->select($this->PurchaseOrders->Suppliers)
        ->select([
            'total' => '
                (
                    SELECT
                    COUNT(*)
                    FROM
                    purchase_orders_details PurchaseOrdersDetails
                    WHERE
                    PurchaseOrdersDetails.purchase_order_id = `PurchaseOrders`.`id`
                    AND
                    (
                        (PurchaseOrdersDetails.qty) - (
                            SELECT
                            IFNULL(SUM(qty),0)
                            FROM
                            receipt_purchases_details
                            WHERE
                            receipt_purchases_details.purchase_orders_detail_id = PurchaseOrdersDetails.id AND receipt_purchases_details.receipt_purchase_id != '. $id.'
                        )
                    ) > 0
                )
            '
        ])->having('total > 0');
        $receiptPurchase->date = $receiptPurchase->date->format('d-m-Y');

        $titleModule = 'Penerimaan Barang Pembelian';
        $titlesubModule = 'Edit Penerimaan Barang Pembelian';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'receiptPurchase', 'purchaseOrders'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Receipt Purchase id.
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
