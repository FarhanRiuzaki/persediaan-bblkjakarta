<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * StockOpnames Controller
 *
 * @property \App\Model\Table\StockOpnamesTable $StockOpnames
 *
 * @method \App\Model\Entity\StockOpname[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StockOpnamesController extends AppController
{

    private $titleModule = "Stok Opname";
    private $primaryModel = "StockOpnames";

    public function initialize()
    {
        parent::initialize();
        $this->set([
            'titleModule' => $this->titleModule,
            'primaryModel' => $this->primaryModel,
        ]);
        $this->loadModel('Categories');
    }

    function beforeFilter(\Cake\Event\Event $event){
        parent::beforeFilter($event);
    
        $this->Security->config('validatePost',false);
        // if(isset($this->Security) && $this->request->isAjax() && ($this->action = 'index' || $this->action = 'delete')){
    
            //$this->getEventManager()->off($this->Csrf);
    
        // }
    
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
                $this->primaryModel.'.id',
                $this->primaryModel.'.code', 

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
     * @param string|null $id Stock Opname id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $stockOpname = $this->StockOpnames->get($id, [
            'contain' => ['StockOpnamesDetails', 'StockOpnamesDetails.Products']
        ]);

        $titleModule = 'Stok Opname';
        $titlesubModule = 'View ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'Daftar ' . $titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];

        $this->set('stockOpname', $stockOpname);
        $this->set('_serialize', ['stockOpname']);
        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $stockOpname = $this->StockOpnames->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $stockOpname = $this->StockOpnames->patchEntity($stockOpname, $data, [
                'associated' => [
                    'StockOpnamesDetails'
                ]
            ]);
            // dd($stockOpname);       
            if ($this->StockOpnames->save($stockOpname)) {
                $code = 200;
                $message = __('Stok Opname berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Stok Opname gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'stockOpname', 'data']);
        }
        $categories = $this->Categories->find('list')->order(['name' => 'ASC']);

        $titleModule = 'Stok Opname';
        $titlesubModule = 'Tambah ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'stockOpname','categories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Stock Opname id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $stockOpname = $this->StockOpnames->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $stockOpname = $this->StockOpnames->patchEntity($stockOpname, $this->request->getData());
            if ($this->StockOpnames->save($stockOpname)) {
                $this->Flash->success(__('The stock opname has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The stock opname could not be saved. Please, try again.'));
        }
        $this->set(compact('stockOpname'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Stock Opname id.
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
