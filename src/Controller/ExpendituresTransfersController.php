<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * ExpendituresTransfers Controller
 *
 * @property \App\Model\Table\ExpendituresTransfersTable $ExpendituresTransfers
 *
 * @method \App\Model\Entity\ExpendituresTransfer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExpendituresTransfersController extends AppController
{
    private $titleModule = "Pengeluaran Barang Transfer Antar Lembaga";
    private $primaryModel = "ExpendituresTransfers";

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
                $this->primaryModel . '.code',
                $this->primaryModel . '.date',
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
     * @param string|null $id Expenditures Transfer id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request_print = $this->request->query('print');
        if (empty($request_print)) {
            $expendituresTransfer = $this->ExpendituresTransfers->get($id, [
                'contain' => ['Institutions', 'ExpendituresTransfersDetails', 'ExpendituresTransfersDetails.Products', 'CreatedUsers', 'ModifiedUsers']
            ]);
            $this->set('expendituresTransfer', $expendituresTransfer);
        } else {
            $expendituresTransfer = $this->ExpendituresTransfers->get($id, [
                'contain' => ['Institutions', 'ExpendituresTransfersDetails', 'ExpendituresTransfersDetails.Products', 'CreatedUsers', 'ModifiedUsers']
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
                $this->set('expendituresTransfer', $expendituresTransfer);
            }
        }

        $titleModule = 'Pengeluaran Barang Transfer Antar Lembaga';
        $titleModuleRelated = 'Detail Barang';
        $titlesubModule = 'View ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'titleModuleRelated'));
        $this->set('expendituresTransfer', $expendituresTransfer);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $expendituresTransfer = $this->ExpendituresTransfers->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $expendituresTransfer = $this->ExpendituresTransfers->patchEntity($expendituresTransfer, $data, [
                'associated' => [
                    'ExpendituresTransfersDetails'
                ]
            ]);

            if ($this->ExpendituresTransfers->save($expendituresTransfer)) {
                $code = 200;
                $message = __('Pengeluaran barang transfer Antar Lembaga berhasil disimpan.');
                $var = $this->request->getData();
            } else {
                $code = 50;
                $message = __('Pengeluaran barang transfer Antar Lembaga gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data', 'var'));
            $this->set('_serialize', ['code', 'message', 'expendituresTransfer', 'data', 'var']);
        }
        $institutions = $this->ExpendituresTransfers->Institutions->find('list', ['limit' => 200]);

        $titleModule = 'Pengeluaran Barang Transfer Antar Lembaga';
        $titlesubModule = 'Tambah Pengeluaran Barang Transfer Antar Lembaga';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'expendituresTransfer', 'institutions'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Expenditures Transfer id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $expendituresTransfer = $this->ExpendituresTransfers->get($id, [
            'contain' => ['ExpendituresTransfersDetails', 'ExpendituresTransfersDetails.Products']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $expendituresTransfer = $this->ExpendituresTransfers->patchEntity($expendituresTransfer, $data, [
                'associated' => [
                    'ExpendituresTransfersDetails'
                ]
            ]);

            if ($this->ExpendituresTransfers->save($expendituresTransfer)) {
                if (!empty($data['delete_detail'])) {
                    $explodeId = explode(',', $data['delete_detail']);

                    foreach ($explodeId as $id) {
                        $ExpendituresTransfersDetails = $this->ExpendituresTransfers->ExpendituresTransfersDetails->get($id);
                        $this->ExpendituresTransfers->ExpendituresTransfersDetails->delete($ExpendituresTransfersDetails);
                    }
                }
                $code = 200;
                $message = __('Pengeluaran barang transfer berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Pengeluaran barang transfer gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'expendituresTransfer', 'data']);
        }

        $institutions = $this->ExpendituresTransfers->Institutions->find('list', ['limit' => 200]);
        $expendituresTransfer->date = $expendituresTransfer->date->format('d-m-Y');

        $titleModule = 'Pengeluaran Barang Transfer Antar Lembaga';
        $titlesubModule = 'Edit ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'expendituresTransfer', 'institutions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Expenditures Transfer id.
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
