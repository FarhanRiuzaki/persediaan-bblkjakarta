<?php

namespace App\Controller;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;

/**
 * Apis Controller
 */
class ApisController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        if (php_sapi_name() !== 'cli') {
            $this->Auth->allow([
                'getProductUnit',
                'getRegistrations',
                'getRegistrationsParameters',
                'getProducts',
                'getPrs',
                'getPrsDetails',
                'getRps',
                'getRpsDetails',
                'getRrs',
                'getRrsDetails',
                'getPurchaseOrderDetail',
                'getInternalOrder',
                'getInternalOrderDetail',
                'getStocks',
                'getExpendituresDistributionDetail',
                'getExpendituresReclarificationsDetails',
                'getStockOpnames',
                'getStockOpnamesDetails',
                'updateStatusPs',
                'updateStatusIo',
                'cekStok',
                'getDetail',
                'getDetailOut',
                'saveProducts'
            ]);
            $this->loadModel('Products');
        }
    }

    function beforeFilter(\Cake\Event\Event $event){
        parent::beforeFilter($event);
    
        $this->Security->config('validatePost',false);
    
    }

    public function getPrs() //purchase Order
    {
        $this->loadModel('PurchaseRequestsDetails');
        if ($this->request->is('ajax')) {
            $search = '';

            if ($this->request->query('search')) {
                $search = $this->request->query('search');
            }

            $data = $this->PurchaseRequestsDetails->find('all', [
                'conditions' => [
                    'PurchaseRequestsDetails.status' => 0,
                    'or' => [
                        'name LIKE' => '%' . $search . '%',
                        'PurchaseRequests.code LIKE' => '%' . $search . '%'
                    ]
                ],
                'order' => [
                    'name' => 'ASC'
                ],
                'contain' => ['Products', 'PurchaseRequests']
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['text'] = '[' . $val->purchase_request->code . '] ' . $val->product->name;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getRrs()//receipt ex recla
    {
        $this->loadModel('ExpendituresReclarificationsDetails');
        if ($this->request->is('ajax')) {
            $search = '';

            if ($this->request->query('search')) {
                $search = $this->request->query('search');
            }

            $data = $this->ExpendituresReclarificationsDetails->find('all', [
                'conditions' => [
                    'ExpendituresReclarificationsDetails.status' => 0,
                    'or' => [
                        'Products.name LIKE' => '%' . $search . '%',
                        'ExpendituresReclarifications.code LIKE' => '%' . $search . '%'
                    ]
                ],
                'order' => [
                    'name' => 'ASC'
                ],
                'contain' => ['Products', 'ExpendituresReclarifications']
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['text'] = '[' . $val->expenditures_reclarification->code . '] ' . $val->product->name;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getRps()//receiptpurchase
    {
        $this->loadModel('PurchaseOrdersDetails');
        if ($this->request->is('ajax')) {
            $search = '';

            if ($this->request->query('search')) {
                $search = $this->request->query('search');
            }

            $data = $this->PurchaseOrdersDetails->find('all', [
                'conditions' => [
                    'or' => [
                        'Products.name LIKE' => '%' . $search . '%',
                        'PurchaseOrders.nomor_po LIKE' => '%' . $search . '%'
                    ]
                ],
                'order' => [
                    'name' => 'ASC'
                ],

                'contain' => ['Products', 'PurchaseOrders']
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['text'] = '[' . $val->purchase_order->nomor_po . '] ' . $val->product->name;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getRrsDetails()
    {
        $this->loadModel('ExpendituresReclarificationsDetails');
        if ($this->request->is('ajax')) {
            $productId = '';

            if ($this->request->query('productId')) {
                $productId = $this->request->query('productId');
            }

            $data = $this->ExpendituresReclarificationsDetails->find('all', [
                'conditions' => [
                    'ExpendituresReclarificationsDetails.id' => $productId
                ],
                'order' => [
                    'name' => 'ASC'
                ],
                'contain' => ['Products', 'ExpendituresReclarifications']
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['product_id'] = $val->product_id;
                $results[$a]['code'] = $val->expenditures_reclarification->code;
                $results[$a]['qty'] = $val->qty;
                $results[$a]['text'] = $val->product->name;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getRpsDetails()
    {
        $this->loadModel('PurchaseOrdersDetails');
        if ($this->request->is('ajax')) {
            $productId = '';

            if ($this->request->query('productId')) {
                $productId = $this->request->query('productId');
            }

            $data = $this->PurchaseOrdersDetails->find('all', [
                'conditions' => [
                    'PurchaseOrdersDetails.id' => $productId
                ],
                'order' => [
                    'name' => 'ASC'
                ],
                'contain' => ['Products', 'PurchaseOrders']
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['product_id'] = $val->product_id;
                $results[$a]['nomor_po'] = $val->purchase_order->nomor_po;
                $results[$a]['qty'] = $val->qty;
                $results[$a]['text'] = $val->product->name;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getPrsDetails()
    {
        $this->loadModel('PurchaseRequestsDetails');
        if ($this->request->is('ajax')) {
            $productId = '';

            if ($this->request->query('productId')) {
                $productId = $this->request->query('productId');
            }

            $data = $this->PurchaseRequestsDetails->find('all', [
                'conditions' => [
                    'PurchaseRequestsDetails.status' => 0,
                    'and' => [
                        'PurchaseRequestsDetails.id' => $productId
                    ]
                ],
                'order' => [
                    'name' => 'ASC'
                ],
                'contain' => ['Products', 'PurchaseRequests']
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['product_id'] = $val->product_id;
                $results[$a]['code'] = $val->purchase_request->code;
                $results[$a]['qty'] = $val->qty;
                $results[$a]['text'] = $val->product->name;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getRegistrations()
    {
        $this->loadModel('Registrations');

        if ($this->request->is('ajax')) {
            $search = '';

            if ($this->request->query('search')) {
                $search = $this->request->query('search');
            }

            $data = $this->Registrations->find('all', [
                'conditions' => [
                    'or' => [
                        'no_invoice LIKE' => '%' . $search . '%',
                    ]
                ],
                'order' => [
                    'no_invoice' => 'ASC'
                ]
            ])->select([
                'id' => 'Registrations.id',
                'no_invoice' => 'Registrations.no_invoice',
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['text'] = $val->no_invoice;
                $a++;
            }

            $this->set(compact('results', 'data'));
            $this->set('_serialize', ['results', 'data']);
        }
    }


    public function getRegistrationsParameters($registration_id)
    {
        $this->loadModel('RegistrationsParameters');

        if ($this->request->is('ajax')) {
            $data = $this->RegistrationsParameters->find('all', [
                'conditions' => [
                    'registration_id' => $registration_id,
                ],
                'order' => [
                    'InspectionParameters.name' => 'ASC'
                ],
                'contain' => [
                    'InspectionParameters'
                ]
            ])->select([
                'id'    => 'RegistrationsParameters.inspection_parameter_id',
                'name'  => 'InspectionParameters.name'
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['text'] = $val->name;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getProductUnit($product_id)
    {
        $this->loadModel('ProductUnits');

        if ($this->request->is('ajax')) {
            $data = $this->ProductUnits->find('all', [
                'conditions' => [
                    'product_id' => $product_id
                ],
                'order' => [
                    'ProductUnits.unit' => 'ASC'
                ]
            ])->select([
                'id'    => 'ProductUnits.id',
                'name'  => 'ProductUnits.unit'
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['text'] = $val->name;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getProducts()
    {
        $this->loadModel('Products');
        $this->loadModel('Stocks');

        $InStocks       = $this->Stocks->find();
        $OutStocks      = $this->Stocks->find();
        $priceStocks    = $this->Stocks->find();
        $refStocks      = $this->Stocks->find();

        if ($this->request->is('ajax')) {
            $search = '';
            $endDate = '';
            $tipe = '';

            $now = Time::now();
            $now->month;
    
            $fmonth = $now->month;
            $year = $now->year;
            if($fmonth == 1) {
                $year -= 1;
                $fmonth = 12;
            }

            $fdate = $year . '-' . $fmonth . '-31';
            // dd($fdate);
            $date = date('Y-m-d', strtotime($fdate));

            if ($this->request->query('search')) {
                $search = $this->request->query('search');
            }
            if ($this->request->query('endDate')) {
                $endDate = date("Y-m-d", strtotime($this->request->query('endDate')));
            }
            
            if ($this->request->query('tipe')) {
                $tipe = $this->request->query('tipe');
            }

            if($tipe == 1){
                $where = 'SubCategories.category_id IN (1,3,4)';
            }elseif($tipe == 2){
                $where = 'SubCategories.category_id NOT IN (1,3,4)';
            }else{
                $where = '';
            }
            
            // dd($tipe);
            $data = $this->Products->find('all', [
                'conditions' => [
                    $where,
                    'Products.status IN' => [1,2],
                    'or' => [
                        'Products.name LIKE' => '%' . $search . '%',
                        'Products.code LIKE' => '%' . $search . '%'
                    ]
                ],
                'order' => [
                    'Products.name' => 'ASC',
                ],
                'contain' => ['ProductUnits','SubCategories']
            ])->select([
                'id' => 'Products.id',
                'code' => 'Products.code',
                'name' => 'Products.name',
                'satuan' => 'Products.unit',
                'no_catalog' => 'Products.no_catalog',
                'unit_id' => 'ProductUnits.id',
                'unit' => 'ProductUnits.unit',
                'saldo_product' => '(IFNULL((' .
                    $InStocks->select(
                        [
                            'SUM' => $InStocks->func()->sum('qty')
                        ]
                    )->where([
                        'type = "IN"',
                        'product_id = Products.id',
                        // 'date BETWEEN "2017-12-01" AND "'. $date .'"'
                        ])
                . '), 0)) - (IFNULL((' .
                    $OutStocks->select(
                        [
                            'SUM' => $OutStocks->func()->sum('qty')
                        ]
                    )->where([
                        'type = "OUT"',
                        'product_id = Products.id',
                        // 'date BETWEEN "2017-12-01" AND "'. $date .'"'
                        ])
                . '), 0))',
                'price' => $priceStocks->select([
                    'price'
                    ])->where([
                        'type = "IN"',
                        'product_id = Products.id',
                    ])->order(['date' => 'DESC'])->limit(1)
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['text'] = '[' . $val->code . '] ' . $val->name;
                $results[$a]['unit_id'] = $val->unit_id;
                $results[$a]['unit'] = $val->unit;
                $results[$a]['saldo'] = $val->saldo_product;
                $results[$a]['no_catalog'] = $val->no_catalog;
                $results[$a]['satuan'] = $val->satuan;
                $results[$a]['price'] = $val->price;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getPurchaseOrderDetail($supplier_name)
    {
        $this->loadModel('PurchaseOrdersDetails');
        $this->loadModel('PurchaseOrders');
        $this->loadModel('Products');

        if ($this->request->is('ajax')) {
            $data = $this->PurchaseOrdersDetails->find('all', [
                'conditions' => [
                    'Suppliers.name' => $supplier_name,
                ],
                'contain' => ['Products', 
                'PurchaseOrders' => [
                    'conditions' => [
                        'date_freeze IS NOT NULL'
                    ]
                ], 'PurchaseOrders.Suppliers']
            ])->select($this->PurchaseOrdersDetails)
            ->select($this->PurchaseOrders)
            ->select($this->PurchaseOrders->Suppliers)
            ->select($this->Products)
            ->select([
                'saldo' => '
                    (PurchaseOrdersDetails.qty) - (SELECT IFNULL(SUM(qty),0) FROM receipt_purchases_details WHERE receipt_purchases_details.purchase_orders_detail_id =  PurchaseOrdersDetails.id)
                '
            ])->having('saldo > 0');

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['nomor_spk'] = $val->purchase_order->nomor_spk;
                $results[$a]['product_id'] = $val->product_id;
                $results[$a]['product_name'] = $val->product->name;
                $results[$a]['qty'] = $val->saldo;
                $results[$a]['price'] = $val->price;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getInternalOrder($institute_id)
    {
        $this->loadModel('InternalOrders');

        if ($this->request->is('ajax')) {
            $data = $this->InternalOrders->find('all', [
                'conditions' => [
                    'institute_id' => $institute_id,
                    'status !=' => 2
                ],
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['text'] = $val->code;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getInternalOrderDetail($internal_order_id)
    {
        $this->loadModel('InternalOrdersDetails');

        $this->loadModel('Products');
        $this->loadModel('Stocks');
        $this->loadModel('ExpendituresDistributionsDetails');

        $InStocks = $this->Stocks->find();
        $OutStocks = $this->Stocks->find();
        $priceStocks = $this->Stocks->find();
        $expendituresDistributionsDetails = $this->ExpendituresDistributionsDetails->find();

        if ($this->request->is('ajax')) {
            $data = $this->InternalOrdersDetails->find('all', [
                'conditions' => [
                    'internal_order_id' => $internal_order_id
                ],
                'contain' => ['Products']
            ])->select([
                'id' => 'Products.id',
                'internal_order_detail_id' => 'InternalOrdersDetails.id',
                'internal_order_id' => 'InternalOrdersDetails.internal_order_id',
                'code' => 'Products.code',
                'unit' => 'Products.unit',
                'name' => 'Products.name',
                'qty' => 'InternalOrdersDetails.qty',
                'saldo_product' => '(IFNULL((' .
                                        $InStocks->select(
                                            [
                                                'SUM' => $InStocks->func()->sum('qty')
                                            ]
                                        )->where([
                                            'type = "IN"',
                                            'product_id = Products.id'
                                        ])
                                    . '), 0)) - (IFNULL((' .
                                        $OutStocks->select(
                                            [
                                                'SUM' => $OutStocks->func()->sum('qty')
                                            ]
                                        )->where([
                                            'type = "OUT"',
                                            'product_id = Products.id'
                                        ])
                                    . '), 0))',
                'saldo_dist' => '(IFNULL((' .
                                        $expendituresDistributionsDetails->select(
                                            [
                                                'SUM' => $expendituresDistributionsDetails->func()->sum('qty')
                                            ]
                                        )->where([
                                            'internal_order_id = InternalOrdersDetails.internal_order_id',
                                            'internal_orders_detail_id = InternalOrdersDetails.id',
                                            'product_id = Products.id'
                                        ])
                                    . '), 0))',
                'price' => $priceStocks->select([
                    'price'
                ])->where([
                    'type = "IN"',
                    'product_id = Products.id',
                ])->order(['date' => 'DESC'])->limit(1)
            ])->having('qty > saldo_dist');

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['internal_order_detail_id'] = $val->internal_order_detail_id;
                $results[$a]['internal_order_id'] = $val->internal_order_id;
                $results[$a]['text'] = '[' . $val->code . '] ' . $val->name;
                $results[$a]['qty'] = $val->qty;
                $results[$a]['unit'] = $val->unit;
                $results[$a]['saldo'] = $val->saldo_product;
                $results[$a]['saldo_dist'] = $val->saldo_dist;
                $results[$a]['sisa'] = $val->qty - $val->saldo_dist;
                $results[$a]['price'] = $val->price;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getStocks($product_id, $type, $startDate, $endDate)
    {
        $this->loadModel('Stocks');

        $startDate = date('m', strtotime($startDate));
        $endDate = date('m', strtotime($endDate));
        // dd($endDate);

        if ($this->request->is('ajax')) {
            $source = $this->Stocks->find('all', [
                'conditions' => [
                    'product_id' => $product_id,
                    'type' => $type,
                    'MONTH(date) >="'. $startDate .'" AND MONTH(date) <="'. $endDate .'"'
                ],
                'contain' => ['Products']
            ])->select([
                'date' => 'Stocks.date',
                'product_id' => 'product_id',
                'product_name' => 'Products.name',
                'type' => 'Stocks.type',
                'qty' => 'Stocks.qty',
                'price' => 'Stocks.price',
                'ref_table' => 'Stocks.ref_table',
            ]);

            $searchAble = [
            ];

            $data = [
                'source' => $source,
                'searchAble' => $searchAble,
                'defaultField' => 'Products.id',
                'defaultSort' => 'desc',
                'defaultSearch' => [],
            ];

            $asd = $this->Datatables->makes($data);
            $data = $asd['data'];
            $meta = $asd['meta'];

            $this->set('data', $data);
            $this->set('meta', $meta);
            $this->set('_serialize', ['data', 'meta']);
            
        }
    }

    public function getExpendituresDistributionDetail($expenditures_distribution_id)
    {
        $this->loadModel('ExpendituresDistributionsDetails');

        $this->loadModel('Products');
        $this->loadModel('Stocks');

        $InStocks = $this->Stocks->find();
        $OutStocks = $this->Stocks->find();
        $priceStocks = $this->Stocks->find();

        if ($this->request->is('ajax')) {
            $data = $this->ExpendituresDistributionsDetails->find('all', [
                'conditions' => [
                    'expenditures_distribution_id' => $expenditures_distribution_id
                ],
                'contain' => ['Products']
            ])->select([
                'id' => 'Products.id',
                'expenditures_distribution_detail_id' => 'ExpendituresDistributionsDetails.id',
                'code' => 'Products.code',
                'name' => 'Products.name',
                'qty' => 'ExpendituresDistributionsDetails.qty',
                'price' => 'ExpendituresDistributionsDetails.price',
                'saldo_product' => '(IFNULL((' .
                                        $InStocks->select(
                                            [
                                                'SUM' => $InStocks->func()->sum('qty')
                                            ]
                                        )->where([
                                            'type = "IN"',
                                            'product_id = Products.id'
                                        ])
                                    . '), 0)) - (IFNULL((' .
                                        $OutStocks->select(
                                            [
                                                'SUM' => $OutStocks->func()->sum('qty')
                                            ]
                                        )->where([
                                            'type = "OUT"',
                                            'product_id = Products.id'
                                        ])
                                    . '), 0))'
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['expenditures_distribution_detail_id'] = $val->expenditures_distribution_detail_id;
                $results[$a]['text'] = '[' . $val->code . '] ' . $val->name;
                $results[$a]['qty'] = $val->qty;
                $results[$a]['saldo'] = $val->saldo_product;
                $results[$a]['price'] = $val->price;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getExpendituresReclarificationsDetails($expenditures_reclarification_id)
    {
        $this->loadModel('ExpendituresReclarificationsDetails');
        $this->loadModel('ExpendituresReclarifications');
        $this->loadModel('Products');

        if ($this->request->is('ajax')) {
            $data = $this->ExpendituresReclarificationsDetails->find('all', [
                'conditions' => [
                    'expenditures_reclarification_id' => $expenditures_reclarification_id,
                ],
                'contain' => ['Products', 'ExpendituresReclarifications']
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['product_id'] = $val->product_id;
                $results[$a]['product_name'] = $val->product->name;
                $results[$a]['qty'] = $val->qty;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    // UNTUK MENDAPATKAN PRODUK DI FORM STOCK OPNAMES
    public function getStockOpnames()
    {
        // dd($this->request);
        $cek = $this->request->query('cek');
    
        $where = '';
        if($cek){
            $where = 'category_id = '. $cek .'';
        }
        $this->loadModel('Products');
        $this->loadModel('Stocks');

        $InStocks = $this->Stocks->find();
        $OutStocks = $this->Stocks->find();
        $priceStocks = $this->Stocks->find();

        if ($this->request->is('ajax')) {
            $data = $this->Products->find('all', [
                'conditions' => [
                    $where
                ],
                'contain' =>[
                    'SubCategories'
                ]
            ])->select([
                'id' => 'Products.id',
                'code' => 'Products.code',
                'name' => 'Products.name',
                'product_saldo' => '(IFNULL((' .
                                        $InStocks->select(
                                            [
                                                'SUM' => $InStocks->func()->sum('qty')
                                            ]
                                        )->where([
                                            'type = "IN"',
                                            'product_id = Products.id'
                                        ])
                                    . '), 0)) - (IFNULL((' .
                                        $OutStocks->select(
                                            [
                                                'SUM' => $OutStocks->func()->sum('qty')
                                            ]
                                        )->where([
                                            'type = "OUT"',
                                            'product_id = Products.id'
                                        ])
                                    . '), 0))',
                'price' => $priceStocks->select([
                    'price'
                ])->where([
                    'type = "IN"',
                    'product_id = Products.id',
                ])->order(['date' => 'DESC'])->limit(1)
            ]);

            $results = [];
            $a = 0;

            foreach ($data as $key => $val) {
                $results[$a]['id'] = $val->id;
                $results[$a]['name'] = '[' . $val->code . '] ' . $val->name;
                $results[$a]['product_saldo'] = $val->product_saldo;
                $results[$a]['price'] = $val->price;
                $a++;
            }

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
        }
    }

    public function getStockOpnamesDetails($product_id, $startDate, $endDate)
    {
        $this->loadModel('StockOpnamesDetails');

        $startDate = date('m', strtotime($startDate));
        $endDate = date('m', strtotime($endDate));

        if ($this->request->is('ajax')) {
            $source = $this->StockOpnamesDetails->find('all', [
                'conditions' => [
                    'product_id' => $product_id,
                    'MONTH(date) >= "'. $startDate .'" AND MONTH(date) <= "'. $endDate .'"'
                ],
                'contain' => ['Products', 'StockOpnames']
            ])->select([
                'date' => 'StockOpnames.date',
                'product_id' => 'product_id',
                'product_name' => 'Products.name',
                'last_qty' => 'StockOpnamesDetails.last_qty',
                'qty' => 'StockOpnamesDetails.qty',
                'qty_diff' => 'StockOpnamesDetails.qty_diff',
                'price' => 'StockOpnamesDetails.price',
                'info' => 'StockOpnamesDetails.info',
            ]);

            $searchAble = [
            ];

            $data = [
                'source' => $source,
                'searchAble' => $searchAble,
                'defaultField' => 'Products.id',
                'defaultSort' => 'desc',
                'defaultSearch' => [],
            ];

            $asd = $this->Datatables->makes($data);
            $data = $asd['data'];
            $meta = $asd['meta'];

            $this->set('data', $data);
            $this->set('meta', $meta);
            $this->set('_serialize', ['data', 'meta']);
        }
    }

    // Mengubah status menjadi selesai di PR
    public function updateStatusPs($id)
    {
        $this->loadModel('PurchaseRequests');
        $purchaseRequest = $this->PurchaseRequests->get($id);
        $info = '';
        if ($this->request->data('info')) {
            $info = $this->request->data('info');
        }

        if ($this->request->is(['patch', 'post', 'put', 'ajax'])) {
            $data = $this->request->getData();
            $data['status'] = 2;
            $data['info'] = $info;

            $purchaseRequest = $this->PurchaseRequests->patchEntity($purchaseRequest, $data);

            if ($this->PurchaseRequests->save($purchaseRequest)) {
                $code = 200;
                $message = __('Permintaan pembelian berhasil diselesaikan.');
            } else {
                $code = 50;
                $message = __('Permintaan pembelian gagal diselesaikan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'purchaseRequest', 'data']);
        }
    }
    public function updateStatusIo($id)
    {
        $this->loadModel('InternalOrders');
        $internalOrder = $this->InternalOrders->get($id);
        $info = '';
        if ($this->request->data('info')) {
            $info = $this->request->data('info');
        }

        if ($this->request->is(['patch', 'post', 'put', 'ajax'])) {
            $data = $this->request->getData();
            $data['status'] = 2;
            $data['info'] = $info;

            $internalOrder = $this->InternalOrders->patchEntity($internalOrder, $data);

            if ($this->InternalOrders->save($internalOrder)) {
                $code = 200;
                $message = __('Permintaan pembelian berhasil diselesaikan.');
            } else {
                $code = 50;
                $message = __('Permintaan pembelian gagal diselesaikan. Silahkan ulangi kembali.');
            }

            $this->set(compact('code', 'message', 'receive', 'data'));
            $this->set('_serialize', ['code', 'message', 'internalOrder', 'data']);
        }
    }

    public function cekStok()
    {
        $this->loadModel('Products');
        $data = $this->request->query();
        // dd($data);
        if ($this->request->is('ajax')) {

            $conn = ConnectionManager::get('default');
            $stmt = $conn->execute("SELECT
                SUM( CASE WHEN type = 'IN' THEN stocks.qty ELSE 0 END ) - SUM( CASE WHEN type = 'OUT' THEN stocks.qty ELSE 0 END ) as qty,
                products.name as product_name
            FROM
                products
                LEFT JOIN stocks ON stocks.product_id = products.id 
            WHERE
                products.id = ". $data['id'] . " 
            GROUP BY
                products.id");

            $source = $stmt->fetchAll('assoc');

            // dd($source);

            $this->set(compact('source'));
            $this->set('_serialize', ['source']);
            
        }
    }

    public function getDetail()
    {
        $this->loadModel('ItemReceipts');
        $data = $this->request->query();
        // dd($data);
        if ($this->request->is('ajax')) {

            $results = $this->ItemReceipts->find('all', [
                'conditions' => [
                    'code' => $data['code']
                ],
                'contain' => [
                    'ItemReceiptsDetails.Products'
                ]
            ]);
            // dd($results->all());

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
            
        }
    }

    public function getDetailOut()
    {
        $this->loadModel('ExpendituresDistributions');
        $this->loadModel('ItemHandovers');
        $data = $this->request->query();
        $cek = substr($data['code'], 0, 2);
        if ($this->request->is('ajax')) {

            if($cek == 'ED'){
                $data = $this->ExpendituresDistributions->find('all', [
                    'conditions' => [
                        'code' => $data['code']
                    ],
                    'contain' => [
                        'ExpendituresDistributionsDetails.Products'
                    ]
                ])->first();
                
                $results = [];
                foreach($data->expenditures_distributions_details as $key => $val){
                    $results[$key]['name']  = $val->product->name;
                    $results[$key]['qty']   = $val->qty . ' ' . $val->product->unit ;
                    $results[$key]['harga'] = $val->price;
                    $results[$key]['exp']   = $val->expired == null ? '-' : $this->Utilities->indonesiaDateFormat($val->expired->format('Y-m-d'));
                }
            }elseif($cek == 'IH'){
                $data = $this->ItemHandovers->find('all', [
                    'conditions' => [
                        'code' => $data['code']
                    ],
                    'contain' => [
                        'ItemHandoversDetails.Products'
                    ]
                ])->first();

                $results = [];
                foreach($data->item_handovers_details as $key => $val){
                    $results[$key] ['name'] = $val->product->name;
                    $results[$key] ['qty']  = $val->qty . ' ' . $val->product->unit ;
                    $results[$key] ['harga']= $val->price;
                    $results[$key] ['exp']  = $val->expired == null ? '-' : $this->Utilities->indonesiaDateFormat($val->expired->format('Y-m-d'));
                }
            }
            // dd($data);

            $this->set(compact('results'));
            $this->set('_serialize', ['results']);
            
        }
    }

    public function saveProducts()
    {
        $data           = $this->request->getData();
        // dd($data);
        $save = $this->Products->newEntity();
        $data['min_unit']   = '3';
        $data['status']     = '2';
        $save = $this->Products->patchEntity($save, $data);
        // dd($save);
        if($this->Products->save($save)){
            $code = 200;
            $message = __('Berhasil disimpan.');
        } else {
            $code = 50;
            $message = __('gagal disimpan. Silahkan ulangi kembali.');
        }

        $this->set(compact('code', 'message'));
        $this->set('_serialize', ['code', 'message']);
    }
}
