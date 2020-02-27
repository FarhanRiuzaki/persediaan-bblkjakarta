<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;

/**
 * ProductUnits Controller
 *
 * @property \App\Model\Table\ProductUnitsTable $ProductUnits
 *
 * @method \App\Model\Entity\ProductUnit[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductUnitsController extends AppController
{

    private $titleModule = "Satuan Barang";
    private $primaryModel = "ProductUnits";

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
                $this->primaryModel.'.unit',
                $this->primaryModel.'.qty',
                'Products.name'
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
                'contain' => ['Products']
                    
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
     * @param string|null $id Product Unit id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $record = $this->{$this->primaryModel}->get($id, [
            'contain' => ['Products']
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
        $productUnit = $this->ProductUnits->newEntity();
        if ($this->request->is('post')) {
            $productUnit = $this->ProductUnits->patchEntity($productUnit, $this->request->getData());
            if ($this->ProductUnits->save($productUnit)) {
                $this->Flash->success(__('The product unit has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product unit could not be saved. Please, try again.'));
        }
        $products = $this->ProductUnits->Products->find('list', ['limit' => 200]);
        $this->set(compact('productUnit', 'products'));

        $titlesubModule = "Add ".$this->titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titlesubModule
        ];
        $this->set(compact('titleModule','breadCrumbs','titlesubModule'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product Unit id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productUnit = $this->ProductUnits->get($id, [
            'contain' => ['Products']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productUnit = $this->ProductUnits->patchEntity($productUnit, $this->request->getData());
            if ($this->ProductUnits->save($productUnit)) {
                $this->Flash->success(__('The product unit has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product unit could not be saved. Please, try again.'));
        }
        $products = $this->ProductUnits->Products->find('list', ['limit' => 2000]);
        $this->set(compact('productUnit', 'products'));

        $titlesubModule = "Add ".$this->titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titlesubModule
        ];
        $this->set(compact('titleModule','breadCrumbs','titlesubModule'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product Unit id.
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
