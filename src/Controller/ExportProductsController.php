<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * ExportProducts Controller
 *
 * @property \App\Model\Table\ExportProductsTable $ExportProducts
 *
 * @method \App\Model\Entity\ExportProduct[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExportProductsController extends AppController
{

    private $titleModule = "Export Pengajuan";
    private $primaryModel = "ExportProducts";

    public function initialize()
    {
        parent::initialize();
        $this->set([
            'titleModule' => $this->titleModule,
            'primaryModel' => $this->primaryModel,
        ]);
        $this->Auth->allow(['print']);
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
            $source = $this->{$this->primaryModel}->find('all',[
                    'conditions' => $where
            ])->order([$this->primaryModel.'.id DESC']);
            $searchAble = [
            ];
            $data = [
                'source'=>$source,
                'searchAble' => $searchAble,
                'defaultField' => $this->primaryModel.'.id',
                'defaultSort' => 'desc',
                'contain' => ['ExportProductsDetails'],
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
     * @param string|null $id Export Product id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $exportProduct = $this->ExportProducts->get($id, [
            'contain' => ['ExportProductsDetails.Products','CreatedUsers']
        ]);

        $this->set('exportProduct', $exportProduct);
        $titlesubModule = "View ".$this->titleModule;
        $breadCrumbs = [
            Router::url(['action' => 'index']) => $titlesubModule
        ];
        $this->set(compact('breadCrumbs','titlesubModule'));
    }

    public function print($id = null,$type = null)
    {
        $exportProduct = $this->ExportProducts->get($id, [
            'contain' => ['ExportProductsDetails.Products','CreatedUsers']
        ]);

        $this->set('exportProduct', $exportProduct);

        $this->set(compact('titleModule'));
        Configure::write('CakePdf', [
            'engine' => [
                'className' => 'CakePdf.WkHtmlToPdf',
                'binary' => '/usr/local/bin/wkhtmltopdf',
                // 'className' => 'CakePdf.WkHtmlToPdf', 'binary' => 'C:\wkhtmltopdf\bin\wkhtmltopdf.exe',
                'options' => [
                    'print-media-type' => false,
                    'outline' => true,
                    'dpi' => 96,
                ],
            ],
            'margin' => [
                'bottom' => 15,
                'left' => 15,
                'right' => 15,
                'top' => 15
            ],

            'pageSize' => 'A4',
            'download' => false
        ]);
        $this->RequestHandler->renderAs($this, 'pdf');
        if($type == 1){
            $this->render('print');
        }else{
            $this->render('print_detail');
        }
    }
}
