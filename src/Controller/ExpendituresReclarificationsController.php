<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * ExpendituresReclarifications Controller
 *
 * @property \App\Model\Table\ExpendituresReclarificationsTable $ExpendituresReclarifications
 *
 * @method \App\Model\Entity\ExpendituresReclarification[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExpendituresReclarificationsController extends AppController
{
    private $titleModule = "Pengeluaran Reklarifikasi";
    private $primaryModel = "ExpendituresReclarifications";

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
                $this->primaryModel . '.date'
            ];
            $data = [
                'source'=>$source,
                'searchAble' => $searchAble,
                'defaultField' => $this->primaryModel.'.id',
                'defaultSort' => 'desc',
                'contain' => [],
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
     * @param string|null $id Expenditures Reclarification id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request_print = $this->request->query('print');
        if (empty($request_print)) {
            $expendituresReclarification = $this->ExpendituresReclarifications->get($id, [
                'contain' => ['ExpendituresReclarificationsDetails', 'ExpendituresReclarificationsDetails.Products', 'CreatedUsers', 'ModifiedUsers']
            ]);
            $this->set('expendituresReclarification', $expendituresReclarification);
        } else {
            $expendituresReclarification = $this->ExpendituresReclarifications->get($id, [
                'contain' => ['ExpendituresReclarificationsDetails', 'ExpendituresReclarificationsDetails.Products', 'CreatedUsers', 'ModifiedUsers']
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
                $this->set('expendituresReclarification', $expendituresReclarification);
            }
        }

        $titleModule = 'Pengeluaran Barang Hibah';
        $titleModuleRelated = 'Detail Barang';
        $titlesubModule = 'View ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'titleModuleRelated'));
        $this->set('expendituresReclarification', $expendituresReclarification);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $expendituresReclarification = $this->ExpendituresReclarifications->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $expendituresReclarification = $this->ExpendituresReclarifications->patchEntity($expendituresReclarification, $data, [
                'associated' => [
                    'ExpendituresReclarificationsDetails'
                ]
            ]);

            if ($this->ExpendituresReclarifications->save($expendituresReclarification)) {
                $code = 200;
                $message = __('Pengeluaran reklarifikasi berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Pengeluaran reklarifikasi gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'expendituresReclarification', 'data']);
        }

        $titleModule = 'Pengeluaran Reklarifikasi';
        $titlesubModule = 'Tambah Pengeluaran Reklarifikasi';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'expendituresReclarification'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Expenditures Reclarification id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $expendituresReclarification = $this->ExpendituresReclarifications->get($id, [
            'contain' => ['ExpendituresReclarificationsDetails', 'ExpendituresReclarificationsDetails.Products']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $expendituresReclarification = $this->ExpendituresReclarifications->patchEntity($expendituresReclarification, $data, [
                'associated' => [
                    'ExpendituresReclarificationsDetails'
                ]
            ]);

            if ($this->ExpendituresReclarifications->save($expendituresReclarification)) {
                if (!empty($data['delete_detail'])) {
                    $explodeId = explode(',', $data['delete_detail']);

                    foreach ($explodeId as $id) {
                        $ExpendituresReclarificationsDetails = $this->ExpendituresReclarifications->ExpendituresReclarificationsDetails->get($id);
                        $this->ExpendituresReclarifications->ExpendituresReclarificationsDetails->delete($ExpendituresReclarificationsDetails);
                    }
                }

                $code = 200;
                $message = __('Pengeluaran reklarifikasi berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Pengeluaran reklarifikasi gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'expendituresReclarification', 'data']);
        }

        $expendituresReclarification->date = $expendituresReclarification->date->format('d-m-Y');

        $titleModule = 'Pengeluaran Reklarifikasi';
        $titlesubModule = 'Edit Pengeluaran Reklarifikasi';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'expendituresReclarification', 'institutes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Expenditures Reclarification id.
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
