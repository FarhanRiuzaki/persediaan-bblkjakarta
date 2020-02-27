<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
/**
 * ReceiptOthers Controller
 *
 * @property \App\Model\Table\ReceiptOthersTable $ReceiptOthers
 *
 * @method \App\Model\Entity\ReceiptOther[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ItemReceiptsController extends AppController
{
    private $titleModule = "Barang Masuk";
    private $primaryModel = "ItemReceipts";

    public function initialize()
    {
        parent::initialize();
        $this->set([
            'titleModule' => $this->titleModule,
            'primaryModel' => $this->primaryModel,
        ]);
        $this->set('categories',[
            1 => 'Pembelian',
            2 => 'Hibah',
            3 => 'Transfer',
            4 => 'Lainnya',
        ]);
        $this->loadModel('ExportProductsDetails');
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

        // dd($where);
        if($this->request->is('ajax')){
            $source = $this->{$this->primaryModel}->find('all', [
                'conditions' => $where
            ])->order([$this->primaryModel.'.id DESC']);
            $searchAble = [
                $this->primaryModel. '.code',
                $this->primaryModel. '.category',
                $this->primaryModel. '.date'
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
     * @param string|null $id Receipt Other id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $request_print = $this->request->query('print');
        if (empty($request_print)) {
            $record = $this->{$this->primaryModel}->get($id, [
                'contain' => ['ItemReceiptsDetails', 'ItemReceiptsDetails.Products', 'CreatedUsers', 'ModifiedUsers']
            ]);
            $this->set('record', $record);
        } else {
            $record = $this->{$this->primaryModel}->get($id, [
                'contain' => ['ItemReceiptsDetails', 'ItemReceiptsDetails.Products', 'CreatedUsers', 'ModifiedUsers']
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

        $titleModule = 'Barang Masuk';
        $titleModuleRelated = 'Detail Barang Masuk';
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
        $record = $this->{$this->primaryModel}->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);
            $record = $this->{$this->primaryModel}->patchEntity($record, $data, [
                'associated' => [
                    'ItemReceiptsDetails'
                ]
            ]);

            foreach($data['item_receipts_details'] as $key => $val){
                $exps = $this->ExportProductsDetails->find('all', [
                    'conditions' => [
                        'product_id = '.$val['product_id'].' AND status = 0' 
                    ],
                    'contain' => [
                        'ExportProducts'
                    ]
                ])->order(['ExportProducts.date DESC'])->first();
                // dd($exps);
                if(!empty($exps)){
                    $exp['date']        =  $data['date'];
                    $exp['qty_in']      =  $val['qty'];
                    $exp['status']      =  1;
                    $exps = $this->ExportProductsDetails->patchEntity($exps, $exp);
                    // dump($exps);
                    $this->ExportProductsDetails->save($exps);
                }
            }

            // dd($record);
            if ($this->{$this->primaryModel}->save($record)) {
                $code = 200;
                $message = __('Barang Masuk berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Barang Masuk gagal disimpan. Silahkan ulangi kembali.');
            }
            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'receiptOther', 'data']);
        }
        $this->set(compact('record'));

        $titleModule = 'Barang Masuk';
        $titlesubModule = 'Tambah ' . $titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titleModule,
            Router::url(['action' => 'add']) => $titlesubModule
        ];

        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Receipt Other id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->loadModel('ItemReceiptsDetails');
        $this->loadModel('Stocks');
        $record = $this->{$this->primaryModel}->get($id, [
            'contain' => ['ItemReceiptsDetails', 'ItemReceiptsDetails.Products']
        ]);
        if ($this->request->is(['patch', 'post', 'put', 'json'])) {
            $data = $this->request->getData();
            $data['date'] = $this->Utilities->generalitationDateFormat($data['date']);
            $record = $this->{$this->primaryModel}->patchEntity($record, $data, [
                'associated' => [
                    'ItemReceiptsDetails'
                    ]
            ]);

            
            // get data hapus detail barang masuk
            $gets = [];
            foreach($data['item_receipts_details'] as $key => $val){
                if(!empty($val['id'])){
                    $gets['get_id'][$key] = $val['id'];
                }else{
                    $gets['get_id'][$key] = 0;
                }
            }
            $imp = implode(', ', $gets['get_id']);
            

            // get data hapus id di stok
            $find = $this->ItemReceiptsDetails->find('all')
            ->where(['item_receipt_id = '.$id.' AND id NOT IN('.$imp.')']);
            $stok_id = [];
            foreach($find as $key => $val){
                $stok_id['id'][$key] = $val->id;
            }

            if(!empty($stok_id)){
                $ref_id = implode(', ', $stok_id['id']);
                //hapus stok 
                $this->Stocks->deleteAll('ref_table = "item_receipts_details" AND ref_id IN ('. $ref_id .')',false);     
            }
            
            //hapus detail barang masuk
            $this->ItemReceiptsDetails->deleteAll('item_receipt_id = '. $id .' AND id NOT IN ('. $imp .')',false);
            

            // dd($imp);
            if ($this->{$this->primaryModel}->save($record)) {
                $code = 200;
                $message = __('Barang Masuk berhasil disimpan.');
            } else {
                $code = 50;
                $message = __('Barang Masuk gagal disimpan. Silahkan ulangi kembali.');
            }
            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'receiptOther', 'data']);
        }
        // $institutes = $this->{$this->primaryModel}->Institutes->find('list', ['limit' => 200]);
        $record->date = $record->date->format('d-m-Y');
        $this->set(compact('record'));

        $titleModule = 'Barang Masuk';
        $titlesubModule = 'Edit Barang Masuk';
        $breadCrumbs = [
            Router::url(['action' => 'index']) => 'List ' . $titleModule,
            Router::url(['action' => 'update']) => $titlesubModule
        ];
        $this->set(compact('titleModule', 'breadCrumbs', 'titlesubModule'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Receipt Other id.
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
