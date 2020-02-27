<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * ReceiptTransfers Controller
 *
 * @property \App\Model\Table\ReceiptTransfersTable $ReceiptTransfers
 *
 * @method \App\Model\Entity\ReceiptTransfer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReceiptTransfersController extends AppController
{
    private $titleModule = "Penerimaan Barang Transfer Antar Lembaga";
    private $primaryModel = "ReceiptTransfers";

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
                $this->primaryModel. '.date',
                'Institutions.name',
            ];
            $data = [
                'source'=>$source,
                'searchAble' => $searchAble,
                'defaultField' => $this->primaryModel.'.id',
                'defaultSort' => 'desc',
                'contain' => ['Institutions'],
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
     * @param string|null $id Receipt Transfer id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request_print = $this->request->query('print');
        if (empty($request_print)) {
            $receiptTransfer = $this->{$this->primaryModel}->get($id, [
                'contain' => ['Institutions', 'ReceiptTransfersDetails', 'ReceiptTransfersDetails.Products', 'CreatedUsers', 'ModifiedUsers']
            ]);
            $this->set('receiptTransfer', $receiptTransfer);
        } else {
            $receiptTransfer = $this->{$this->primaryModel}->get($id, [
                'contain' => ['Institutions', 'ReceiptTransfersDetails', 'ReceiptTransfersDetails.Products', 'CreatedUsers', 'ModifiedUsers']
            ]);
            $receiptTransfer->date_bast = ($receiptTransfer->date_bast == null || $receiptTransfer->date_bast == '') ? '' : $receiptTransfer->date_bast->format('Y-m-d');
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
                $this->set('receiptTransfer', $receiptTransfer);
            }
        }

        $titleModule = 'Penerimaan barang transfer antar lembaga';
        $titleModuleRelated = 'Detail Penerimaan barang transfer antar lembaga';
        $titlesubModule = 'View ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'titleModuleRelated'));
        $this->set('receiptTransfer', $receiptTransfer);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $receiptTransfer = $this->{$this->primaryModel}->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);
            $data['date_bast'] = $this->Utilities->generalitationDateFormat($data['date_bast']);

            $receiptTransfer = $this->{$this->primaryModel}->patchEntity($receiptTransfer, $data, [
                'associated' => [
                    'ReceiptTransfersDetails'
                ]
            ]);

            if ($this->{$this->primaryModel}->save($receiptTransfer)) {
                $code = 200;
                $message = __('Penerimaan barang transfer antar lembaga berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Penerimaan barang transfer antar lembaga  gagal disimpan. Silahkan ulangi kembali.');
            }
            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'receiptTransfer', 'data']);
        }

        $institutions = $this->{$this->primaryModel}->Institutions->find('list', ['limit' => 200]);

        $titleModule = 'Penerimaan barang transfer antar lembaga';
        $titlesubModule = 'Tambah Penerimaan barang transfer antar lembaga';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'receiptTransfer', 'institutions'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Receipt Transfer id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $receiptTransfer = $this->{$this->primaryModel}->get($id, [
            'contain' => ['ReceiptTransfersDetails', 'ReceiptTransfersDetails.Products']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);
            $data['date_bast'] = $this->Utilities->generalitationDateFormat($data['date_bast']);

            $receiptTransfer = $this->{$this->primaryModel}->patchEntity($receiptTransfer, $data, [
                'associated' => [
                    'ReceiptTransfersDetails'
                ]
            ]);

            if ($this->{$this->primaryModel}->save($receiptTransfer)) {
                if (!empty($data['delete_detail'])) {
                    $explodeId = explode(',', $data['delete_detail']);

                    foreach ($explodeId as $id) {
                        $ReceiptTransfersDetails = $this->{$this->primaryModel}->ReceiptTransfersDetails->get($id);
                        $this->{$this->primaryModel}->ReceiptTransfersDetails->delete($ReceiptTransfersDetails);
                    }
                }
                $code = 200;
                $message = __('Penerimaan barang transfer antar lembaga berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Penerimaan barang transfer antar lembaga gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'receiptTransfer', 'data']);
        }

        $institutions = $this->{$this->primaryModel}->Institutions->find('list', ['limit' => 200]);
        $receiptTransfer->date = $receiptTransfer->date->format('d-m-Y');
        $receiptTransfer->date_bast = ($receiptTransfer->date_bast == null || $receiptTransfer->date_bast == '') ? '' : $receiptTransfer->date_bast->format('d-m-Y');

        $titleModule = 'Penerimaan barang transfer antar lembaga';
        $titlesubModule = 'Edit Penerimaan barang transfer antar lembaga';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];
        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'receiptTransfer', 'institutions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Receipt Transfer id.
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
