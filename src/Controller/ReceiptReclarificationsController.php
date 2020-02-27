<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * ReceiptReclarifications Controller
 *
 * @property \App\Model\Table\ReceiptReclarificationsTable $ReceiptReclarifications
 *
 * @method \App\Model\Entity\ReceiptReclarification[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReceiptReclarificationsController extends AppController
{

    private $titleModule = "Penerimaan Barang Reklarifikasi Masuk";
    private $primaryModel = "ReceiptReclarifications";

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
                'ExpendituresReclarifications.code'
            ];
            $data = [
                'source'=>$source,
                'searchAble' => $searchAble,
                'defaultField' => $this->primaryModel.'.id',
                'defaultSort' => 'desc',
                'contain' => ['ExpendituresReclarifications'],
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
     * @param string|null $id Receipt Reclarification id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request_print = $this->request->query('print');
        if (empty($request_print)) {
            $receiptReclarification = $this->ReceiptReclarifications->get($id, [
                'contain' => ['ExpendituresReclarifications', 'ReceiptReclarificationsDetails', 'ReceiptReclarificationsDetails.ExpendituresReclarificationsDetails', 'ReceiptReclarificationsDetails.Products', 'ReceiptReclarificationsDetails.ExpendituresReclarificationsDetails.ExpendituresReclarifications', 'CreatedUsers', 'ModifiedUsers']
            ]);
            $this->set('receiptReclarification', $receiptReclarification);
        } else {
            $receiptReclarification = $this->ReceiptReclarifications->get($id, [
                'contain' => ['ExpendituresReclarifications', 'ReceiptReclarificationsDetails', 'ReceiptReclarificationsDetails.ExpendituresReclarificationsDetails', 'ReceiptReclarificationsDetails.Products', 'ReceiptReclarificationsDetails.ExpendituresReclarificationsDetails.ExpendituresReclarifications', 'CreatedUsers', 'ModifiedUsers']
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
                $this->set('receiptReclarification', $receiptReclarification);
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
        $this->set('receiptReclarification', $receiptReclarification);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $receiptReclarification = $this->ReceiptReclarifications->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $receiptReclarification = $this->ReceiptReclarifications->patchEntity($receiptReclarification, $data, [
                'associated' => [
                    'ReceiptReclarificationsDetails'
                ]
            ]);

            if ($this->ReceiptReclarifications->save($receiptReclarification)) {
                $code = 200;
                $message = __('Reklarifikasi masuk berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Reklarifikasi masuk gagal disimpan. Silahkan ulangi kembali.');
                $var = $receiptReclarification->errors();
            }

            $this->set(compact('code', 'message', 'receive', 'data', 'var'));
            $this->set('_serialize', ['code', 'message', 'receiptReclarification', 'data', 'var']);
        }

        $expendituresReclarifications = $this->ReceiptReclarifications->ExpendituresReclarifications->find('list', ['keyField' => 'id', 'valueField' => 'code', 'limit' => 200]);

        $titleModule = 'Penerimaan Barang Reklarifikasi Masuk';
        $titlesubModule = 'Tambah ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'receiptReclarification', 'expendituresReclarifications'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Receipt Reclarification id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $receiptReclarification = $this->ReceiptReclarifications->get($id, [
            'contain' => ['ReceiptReclarificationsDetails', 'ReceiptReclarificationsDetails.Products', 'ReceiptReclarificationsDetails.ExpendituresReclarificationsDetails', 'ReceiptReclarificationsDetails.ExpendituresReclarificationsDetails.ExpendituresReclarifications']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $receiptReclarification = $this->ReceiptReclarifications->patchEntity($receiptReclarification, $data, [
                'associated' => [
                    'ReceiptReclarificationsDetails'
                ]
            ]);

            if ($this->ReceiptReclarifications->save($receiptReclarification)) {
                if (!empty($data['delete_detail'])) {
                    $explodeId = explode(',', $data['delete_detail']);

                    foreach ($explodeId as $id) {
                        $ReceiptReclarificationsDetails = $this->ReceiptReclarifications->ReceiptReclarificationsDetails->get($id);
                        $this->ReceiptReclarifications->ReceiptReclarificationsDetails->delete($ReceiptReclarificationsDetails);
                    }
                }
                $code = 200;
                $message = __('Reklarifikasi masuk berhasil diupdate.');
            } else {
                $code = 50;
                $message = __('Reklarifikasi masuk gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'receiptReclarification', 'data']);
        }

        $expendituresReclarifications = $this->ReceiptReclarifications->ExpendituresReclarifications->find('list', ['keyField' => 'id', 'valueField' => 'code', 'limit' => 200]);
        $receiptReclarification->date = $receiptReclarification->date->format('d-m-Y');

        $titleModule = 'Penerimaan Barang Reklarifikasi Masuk';
        $titlesubModule = 'Edit ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'receiptReclarification', 'expendituresReclarifications'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Receipt Reclarification id.
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
