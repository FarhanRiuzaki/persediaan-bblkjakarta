<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * UseInstitutes Controller
 *
 * @property \App\Model\Table\UseInstitutesTable $UseInstitutes
 *
 * @method \App\Model\Entity\UseInstitute[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UseInstitutesController extends AppController
{

    private $titleModule = "Pemakaian Barang Instalasi";
    private $primaryModel = "UseInstitutes";

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
            $where = '';
            if($this->userData->group_id == 3) {
                $where = ['UseInstitutes.institute_id' => $this->userData->institute_id];
            }

            $source = $this->{$this->primaryModel}->find('all', [
                'conditions' => $where
            ])->order([$this->primaryModel.'.id DESC']);

            $searchAble = [
                $this->primaryModel. '.code',
                $this->primaryModel. '.type',
                'Registrations.no_invoice',
                'InspectionParameters.name',
            ];
            $data = [
                'source'=>$source,
                'searchAble' => $searchAble,
                'defaultField' => $this->primaryModel.'.id',
                'defaultSort' => 'desc',
                'contain' => ['Registrations', 'InspectionParameters'],
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
     * @param string|null $id Use Institute id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $useInstitute = $this->UseInstitutes->get($id, [
            'contain' => ['Registrations', 'InspectionParameters', 'UseInstitutesDetails', 'UseInstitutesDetails.Products', 'UseInstitutesDetails.Products.ProductUnits', 'CreatedUsers', 'ModifiedUsers'],
        ]);

        $titleModule = 'Pemakaian Barang Instalasi';
        $titlesubModule = 'View ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule'));
        $this->set('useInstitute', $useInstitute);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $useInstitute = $this->UseInstitutes->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $useInstitute = $this->UseInstitutes->patchEntity($useInstitute, $data, [
                'associated' => [
                    'UseInstitutesDetails'
                ]
            ]);

            if ($this->UseInstitutes->save($useInstitute)) {
                $code = 200;
                $message = __('Pemakaian Barang Instalasi berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Pemakaian Barang Instalasi gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'UseInstitute', 'data']);
        }
        $institutes = $this->UseInstitutes->Institutes->find('list')->order('Institutes.name ASC');

        $titleModule = 'Pemakaian Barang Instalasi';
        $titlesubModule = 'Tambah ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'useInstitute', 'institutes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Use Institute id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $useInstitute = $this->UseInstitutes->get($id, [
            'contain' => ['UseInstitutesDetails', 'UseInstitutesDetails.Products']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $useInstitute = $this->UseInstitutes->patchEntity($useInstitute, $data, [
                'associated' => [
                    'UseInstitutesDetails'
                ]
            ]);

            if ($this->UseInstitutes->save($useInstitute)) {
                if (!empty($data['delete_detail'])) {
                    $explodeId = explode(',', $data['delete_detail']);

                    foreach ($explodeId as $id) {
                        $UseInstitutesDetails = $this->UseInstitutes->UseInstitutesDetails->get($id);
                        $this->UseInstitutes->UseInstitutesDetails->delete($UseInstitutesDetails);
                    }
                }
                $code = 200;
                $message = __('Pemakaian Barang Instalasi berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Pemakaian Barang Instalasi gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'useInstitute', 'data']);
        }

        $useInstitute->date = $useInstitute->date->format('d-m-Y');
        $institutes = $this->UseInstitutes->Institutes->find('list')->order('Institutes.name ASC');

        $titleModule = 'Pemakaian Barang Instalasi';
        $titlesubModule = 'Edit Pemakaian Barang Instalasi';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'useInstitute', 'institutes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Use Institute id.
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
