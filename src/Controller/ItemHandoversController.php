<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * ItemHandovers Controller
 *
 * @property \App\Model\Table\ItemHandoversTable $ItemHandovers
 *
 * @method \App\Model\Entity\record[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ItemHandoversController extends AppController
{
    private $titleModule = "Barang Keluar";
    private $primaryModel = "ItemHandovers";

    public function initialize()
    {
        parent::initialize();
        $this->set([
            'titleModule' => $this->titleModule,
            'primaryModel' => $this->primaryModel,
        ]);
        $this->set('categories',[
            1 => 'Hibah',
            2 => 'Transfer',
            3 => 'Lainnya',
        ]);
        $this->loadModel('ItemHandoversDetails');
        $this->loadModel('Stocks');
        $this->loadModel('Users');
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
        $cek = $this->userData->user_group_id;
        $where = [];
        if($cek == 4 || $cek == 6){ /*gudang ATK dan Reagen*/
            $find_group = $this->Users->find('all', [
                'conditions' => [
                    'user_group_id' => $cek
                ],
            ]);

            $id = [];
            foreach($find_group as $key => $val){
                $id[] = $val->id;
            }
            $id =  implode(", ",$id);

            $where = [
                'created_by IN' => [$id],
            ];
        }

        if($this->request->is('ajax')){
            $source = $this->{$this->primaryModel}->find('all', [
                'conditions' => $where
            ])->order([$this->primaryModel.'.id DESC']);
            $searchAble = [
                $this->primaryModel . '.code',
                $this->primaryModel . '.given_to',
                $this->primaryModel . '.category',
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
     * @param string|null $id Expenditures Other id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request_print = $this->request->query('print');
        if (empty($request_print)) {
            $record = $this->ItemHandovers->get($id, [
                'contain' => ['ItemHandoversDetails', 'ItemHandoversDetails.Products', 'CreatedUsers', 'ModifiedUsers']
            ]);
            $this->set('record', $record);
        } else {
            $record = $this->ItemHandovers->get($id, [
                'contain' => ['ItemHandoversDetails', 'ItemHandoversDetails.Products', 'CreatedUsers', 'ModifiedUsers']
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
                $this->set('record', $record);
            }
        }

        $titleModule = 'Barang Keluar';
        $titleModuleRelated = 'Detail Barang';
        $titlesubModule = 'View ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'titleModuleRelated'));
        $this->set('record', $record);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    { 
        $record = $this->ItemHandovers->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $record = $this->ItemHandovers->patchEntity($record, $data, [
                'associated' => [
                    'ItemHandoversDetails'
                ]
            ]);

            // dd($record);
            if ($this->ItemHandovers->save($record)) {
                $code = 200;
                $message = __('Barang Keluar berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Barang Keluar gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'record', 'data']);
        }

        $titleModule = 'Barang Keluar';
        $titlesubModule = 'Tambah Barang Keluar';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'record'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Expenditures Other id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $record = $this->ItemHandovers->get($id, [
            'contain' => ['ItemHandoversDetails', 'ItemHandoversDetails.Products']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $record = $this->ItemHandovers->patchEntity($record, $data, [
                'associated' => [
                    'ItemHandoversDetails'
                ]
            ]);

            // get data hapus detail barang masuk
            $gets = [];
            foreach($data['item_handovers_details'] as $key => $val){
                if(!empty($val['id'])){
                    $gets['get_id'][$key] = $val['id'];
                }else{
                    $gets['get_id'][$key] = 0;
                }
            }
            $imp = implode(', ', $gets['get_id']);
            
            // get data hapus id di stok
            $find = $this->ItemHandoversDetails->find('all')
            ->where(['item_handover_id = '.$id.' AND id NOT IN('.$imp.')']);

            $stok_id = [];
            foreach($find as $key => $val){
                $stok_id['id'][$key] = $val->id;
            }

            if(!empty($stok_id)){
                $ref_id = implode(', ', $stok_id['id']);
                //hapus stok 
                $this->Stocks->deleteAll('ref_table = "item_handovers_details" AND ref_id IN ('. $ref_id .')',false);
            }
            
            //hapus detail barang masuk
            $this->ItemHandoversDetails->deleteAll('item_handover_id = '. $id .' AND id NOT IN ('. $imp .')',false);
            
            // dd($data);

            if ($this->ItemHandovers->save($record)) {
                if (!empty($data['delete_detail'])) {
                    $explodeId = explode(',', $data['delete_detail']);

                    foreach ($explodeId as $id) {
                        $ItemHandoversDetails = $this->ItemHandovers->ItemHandoversDetails->get($id);
                        $this->ItemHandovers->ItemHandoversDetails->delete($ItemHandoversDetails);
                    }
                }
                $code = 200;
                $message = __('Barang Keluar berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Barang Keluar gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'record', 'data']);
        }

        $record->date = $record->date->format('d-m-Y');

        $titleModule = 'Barang Keluar';
        $titlesubModule = 'Edit Barang Keluar';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'record', 'institutes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Expenditures Other id.
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
