<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;

/**
 * Institutes Controller
 *
 * @property \App\Model\Table\InstitutesTable $Institutes
 *
 * @method \App\Model\Entity\Institute[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InstitutesController extends AppController
{

    private $titleModule = "Instalasi";
    private $primaryModel = "Institutes";

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
            $source = $this->{$this->primaryModel}->find('all')->order([$this->primaryModel.'.id DESC']);
            $searchAble = [
                $this->primaryModel.'.name',
                $this->primaryModel.'.head_of_institute',
                $this->primaryModel.'.head_of_institute_id',
            ];
            $data = [
                'source'=>$source,
                'searchAble' => $searchAble,
                'defaultField' => $this->primaryModel.'.id',
                'defaultSort' => 'desc',
                'defaultSearch' => [
                    [    
                        'keyField' => $this->primaryModel.'.id',
                        'condition' => 'IS NOT',
                        'value' => null
                    ]
                ],
                'contain' => []
                    
            ];
            $dataTable   = $this->Datatables->make($data);  
            $this->set('aaData',$dataTable['aaData']);
            $this->set('iTotalDisplayRecords',$dataTable['iTotalDisplayRecords']);
            $this->set('iTotalRecords',$dataTable['iTotalRecords']);
            $this->set('sColumns',$dataTable['sColumns']);
            $this->set('sEcho',$dataTable['sEcho']);
            $this->set('_serialize',['aaData','iTotalDisplayRecords','iTotalRecords','sColumns','sEcho']);
        }else{
            $titlesubModule = "Daftar ".$this->titleModule;
            $breadCrumbs = [
                Router::url(['action' => 'index']) => $titlesubModule
            ];
            $this->set(compact('titleModule','breadCrumbs','titlesubModule'));
        }
    }

    /**
     * View method
     *
     * @param string|null $id Institute id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $record = $this->{$this->primaryModel}->get($id, [
            'contain' => ['CreatedUsers', 'ModifiedUsers']
        ]);
        $titlesubModule = "View ".$this->titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => "List ".$this->titleModule,
            Router::url(['action' => 'view',$id]) => $titlesubModule
        ];
        $this->set(compact('titleModule','breadCrumbs','titlesubModule'));
        $this->set('record', $record);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $institute = $this->Institutes->newEntity();
        if ($this->request->is('post')) {
            $institute = $this->Institutes->patchEntity($institute, $this->request->getData());
            // dd($institute);
            if ($this->Institutes->save($institute)) {
                $this->Flash->success(__('The institute has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The institute could not be saved. Please, try again.'));
        }
        $this->set(compact('institute'));

        $titlesubModule = "Add ".$this->titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titlesubModule
        ];
        $this->set(compact('titleModule','breadCrumbs','titlesubModule'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Institute id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $institute = $this->Institutes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $institute = $this->Institutes->patchEntity($institute, $this->request->getData());
            if ($this->Institutes->save($institute)) {
                $this->Flash->success(__('The institute has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The institute could not be saved. Please, try again.'));
        }
        $this->set(compact('institute'));

        $titlesubModule = "Edit ".$this->titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titlesubModule
        ];
        $this->set(compact('titleModule','breadCrumbs','titlesubModule'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Institute id.
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
