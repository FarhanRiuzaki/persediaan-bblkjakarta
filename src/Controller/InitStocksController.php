<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;

/**
 * InitStocks Controller
 *
 * @property \App\Model\Table\InitStocksTable $InitStocks
 *
 * @method \App\Model\Entity\InitStock[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InitStocksController extends AppController
{
    private $titleModule = "Stok Awal";
    private $primaryModel = "InitStocks";

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
    
        // if(isset($this->Security) && $this->request->isAjax() && ($this->action = 'index' || $this->action = 'delete')){
    
        //     //$this->getEventManager()->off($this->Csrf);
            
        // }
        $this->Security->config('validatePost',false);
    
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
                $this->primaryModel.'.code',
                $this->primaryModel.'.name',
                $this->primaryModel.'.name_pic',
                $this->primaryModel.'.address',
                $this->primaryModel.'.phone',
            ];
            $data = [
                'source'=>$source,
                'searchAble' => $searchAble,
                'defaultField' => $this->primaryModel.'.created',
                'defaultSort' => 'desc',
                'defaultSearch' => [
                    [    
                        'keyField' => $this->primaryModel.'.id',
                        'condition' => 'IS NOT',
                        'value' => null
                    ]
                ],
                    
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
     * @param string|null $id Init Stock id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request_print = $this->request->query('print');
        if (empty($request_print)) {
            $initStock = $this->InitStocks->get($id, [
                'contain' => ['InitStocksDetails', 'InitStocksDetails.Products', 'CreatedUsers', 'ModifiedUsers']
            ]);
            $this->set('initStock', $initStock);
        } else {
            $initStock = $this->InitStocks->get($id, [
                'contain' => ['InitStocksDetails', 'InitStocksDetails.Products', 'CreatedUsers', 'ModifiedUsers']
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
                $this->set('initStock', $initStock);
            }
        }

        $titleModule = 'Stok Awal';
        $titleModuleRelated = 'Detail Stok Awal';
        $titlesubModule = 'View ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'view', $id]) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'titleModuleRelated'));
        $this->set('initStock', $initStock);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $initStock = $this->InitStocks->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $initStock = $this->InitStocks->patchEntity($initStock, $data, [
                'associated' => [
                    'InitStocksDetails'
                ]
            ]);
            // dd($initStock);
            if ($this->InitStocks->save($initStock)) {
                // dd($initStock);
                $code = 200;
                $message = __('Stok Awal berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Stok Awal gagal disimpan. Silahkan ulangi kembali.');
            }
            $this->set('_serialize',['code','message']);
            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'initStock', 'data']);
        }

        $titleModule = 'Stok Awal';
        $titlesubModule = 'Tambah Stok Awal';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'initStock', 'institutes'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Init Stock id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('InitStocksDetails');
        $this->loadModel('Stocks');
        $initStock = $this->InitStocks->get($id, [
            'contain' => ['InitStocksDetails', 'InitStocksDetails.Products']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);

            $initStock = $this->InitStocks->patchEntity($initStock, $data, [
                'associated' => [
                    'InitStocksDetails'
                ]
            ]);
            // dd($data);
            //get id
            $gets = [];
            foreach($data['init_stocks_details'] as $key => $val){
                if(!empty($val['id'])){
                    $gets['get_id'][$key] = $val['id'];
                }else{
                    $gets['get_id'][$key] = 0;
                }
            }
            $imp = implode(', ', $gets['get_id']);
            $this->InitStocksDetails->deleteAll('init_stock_id = '. $id .' AND id NOT IN ('. $imp .')',false);     
            $this->Stocks->deleteAll('ref_table = "init_stocks_details" AND ref_id NOT IN ('. $imp .')',false);     
            // dd($gets);
            // dd($data['init_stocks_details']);


            if ($this->InitStocks->save($initStock)) {
                if (!empty($data['delete_detail'])) {
                    $explodeId = explode(',', $data['delete_detail']);

                    foreach ($explodeId as $id) {
                        $InitStocksDetails = $this->InitStocks->InitStocksDetails->get($id);
                        $this->InitStocks->InitStocksDetails->delete($InitStocksDetails);
                    }
                }
                $code = 200;
                $message = __('Stok Awal berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Stok Awal gagal disimpan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'initStock', 'data']);
        }

        $initStock->date = $initStock->date->format('d-m-Y');

        $titleModule = 'Stok Awal';
        $titlesubModule = 'Edit Stok Awal';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule', 'initStock', 'institutes'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Init Stock id.
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
