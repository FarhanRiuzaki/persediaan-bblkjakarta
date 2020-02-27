<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    private $titleModule = "User";
    private $primaryModel = "Users";

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
            $source = $this->{$this->primaryModel};
            $searchAble = [
                $this->primaryModel.'.id',
                $this->primaryModel.'.username',
                'UserGroups.name'
            ];
            $data = [
                'source'=>$source,
                'searchAble' => $searchAble,
                'defaultField' => $this->primaryModel.'.id',
                'defaultSort' => 'desc',
                'defaultSearch' => [
                    // [    
                    //     'keyField' => 'group_id',
                    //     'condition' => '=',
                    //     'value' => 1
                    // ]
                ],
                'contain' => ['UserGroups']
                    
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
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        
        $record = $this->{$this->primaryModel}->get($id, [
            'contain' => ['UserGroups','CreatedUsers','ModifiedUsers']
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
        $record = $this->{$this->primaryModel}->newEntity();
        if ($this->request->is('post')) {
            $record = $this->{$this->primaryModel}->patchEntity($record, $this->request->getData());
            // dd($record);

            if ($this->{$this->primaryModel}->save($record)) {
                $this->Flash->success(__($this->Utilities->message_alert($this->titleModule,1)));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__($this->Utilities->message_alert($this->titleModule,2)));
        }
        $userGroups = $this->{$this->primaryModel}->UserGroups->find('list', ['limit' => 200,'conditions'=>[
            'status'=>1
        ]])->order('UserGroups.name ASC');;
        $institutes = $this->Users->Institutes->find('list')->order('Institutes.name ASC');

        $this->set(compact('record', 'userGroups','institutes'));
        $titlesubModule = "Tambah ".$this->titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => "List ".$this->titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];
        $this->set(compact('titleModule','breadCrumbs','titlesubModule'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $record = $this->{$this->primaryModel}->get($id, [
            'contain' => ['UserGroups','Aros']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $record = $this->{$this->primaryModel}->patchEntity($record, $this->request->getData());
            if(empty($record->password)){
                unset($record->password);
            }
            if ($this->{$this->primaryModel}->save($record)) {
                $this->Redis->destroyCacheUserAuth($record);
                $this->Redis->createCacheUserAuth($record);
                $this->Redis->deleteAllCacheAcos($id);
                $this->Flash->success(__($this->Utilities->message_alert($this->titleModule,3)));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__($this->Utilities->message_alert($this->titleModule,4)));
        }
        $institutes = $this->Users->Institutes->find('list')->order('name ASC');
        $userGroups = $this->{$this->primaryModel}->UserGroups->find('list', ['limit' => 200,['conditions'=>[
            'status' => 1,
            'OR' => [
                'id' => $record->group_id
            ]
        ]]]);
        $this->set(compact('record', 'userGroups','institutes'));
        $titlesubModule = "Edit ".$this->titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => "List ".$this->titleModule,
            Router::url(['action' => 'edit',$id]) => $titlesubModule
        ];
        $this->set(compact('titleModule','breadCrumbs','titlesubModule'));
        $this->set('record', $record);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $record = $this->{$this->primaryModel}->get($id,['contain'=>'Aros']);
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

    public function configure($id)
    {
        $record = $this->{$this->primaryModel}->get($id,[
            'contain'=>['Aros']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            foreach($data['aco_parent_id'] as $key => $r){
                //check parent child aco//
                $access = 'controllers/'.$key; 
                $this->Acl->deny($record,$access);
                $allow = false;
                if(!empty($data['aco_id'][$key])){
                    foreach($data['aco_id'][$key] as $skey => $rd){
                        $accesssub = $access."/".$skey;
                        if($rd == 0){
                            $this->Acl->deny($record,$accesssub);
                            $acl = explode("/",$skey);
                            $url = Router::url(['controller'=>$key,'action'=>$skey]);
                            $this->Redis->deleteCacheAcos($record->id,$url);
                        }else{
                            $this->Acl->allow($record,$accesssub);
                            if($allow == false){
                                $allow = true;
                            }
                        }
                    }
                }
                if($allow){
                    $this->Acl->allow($record,$key);
                }
            }
            $this->Redis->destroyCacheUrlHome($record);
            $this->Redis->destroyCacheSideNav($record);
            $this->Redis->deleteAllCacheAcos($record);
            $this->Flash->success(__('The user has been configured.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->loadModel('Acos');
        $acos = $this->Acos->find('all',[
            'conditions' => [
                'Aros.model' => 'UserGroups',
                'Aros.foreign_key' => $record->user_group_id,
                'Permissions._create' => 1
            ]
        ])
        ->select([
                'Acos__id' => 'Acos.id', 
                'Acos__parent_id' => 'Acos.parent_id', 
                'Acos__name' => 'Acos.name', 
                'Acos__model' => 'Acos.model', 
                'Acos__foreign_key' => 'Acos.foreign_key', 
                'Acos__alias' => 'Acos.alias', 
                'Acos__lft' => 'Acos.lft', 
                'Acos__rght' => 'Acos.rght', 
                'Acos__status' => 'Acos.status',
                'Acos__read' => 'IFNULL(`UserPermissions`.`_read`,-1)'
            ]
        )
        ->join([
            [
                'table' => 'aros_acos',
                'alias' => 'Permissions',
                'type' => 'INNER',
                'conditions' => 'Acos.id = (Permissions.aco_id)',
            ],[
                'table' => 'aros',
                'alias' => 'Aros',
                'type' => 'INNER',
                'conditions' => 'Aros.id = (Permissions.aro_id)',
            ],[
                'table' => 'aros_acos',
                'alias' => 'UserPermissions',
                'type' => 'LEFT',
                'conditions' => 'Acos.id = (UserPermissions.aco_id) AND UserPermissions.aro_id = '.$record->aro->id,
            ],[
                'table' => 'aros',
                'alias' => 'UserAros',
                'type' => 'LEFT',
                'conditions' => 'UserAros.id = (UserPermissions.aro_id) AND UserAros.model = "Users" AND UserAros.foreign_key = '.$id,
            ]
        ])
        ->group('Acos__id')
        ->order('Acos.sort ASC')
        ->find('threaded');
        
        $this->set(compact('record','acos'));
        $titleModule = "User";
        $titlesubModule = "Configure ".$titleModule. " ". $record->username;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => "List ".$titleModule,
            Router::url(['action' => 'configure',$id]) => $titlesubModule
        ];
        $this->set(compact('titleModule','breadCrumbs','titlesubModule'));
    }

}
