<!-- begin:: Aside -->
<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>

<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
    <!-- begin:: Aside -->
    <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
        <div class="kt-aside__brand-logo">
            <a href="<?=$this->Url->build(['controller'=>'Pages','action'=>'index']);?>">
                <?php $image = $this->Utilities->generateUrlImage(null,$defaultAppSettings['App.Logo']); ?>
                <img style="max-height: 50px; max-width:100%; " class="" alt="Logo" src="<?=$image?>" />
            </a>
        </div>
        <div class="kt-aside__brand-tools">
            <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon id="Shape" points="0 0 24 0 24 24 0 24"/>
                            <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" id="Path-94" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) "/>
                            <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) "/>
                        </g>
                    </svg>
                </span>
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon id="Shape" points="0 0 24 0 24 24 0 24"/>
                            <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" id="Path-94" fill="#000000" fill-rule="nonzero"/>
                            <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) "/>
                        </g>
                    </svg>
                </span>
            </button>
            <!--
            <button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
            -->
        </div>
    </div>
    <!-- end:: Aside -->	
    <!-- begin:: Aside Menu -->
    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        
        <div 
            id="kt_aside_menu"
            class="kt-aside-menu "
            data-ktmenu-vertical="1"
            data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500"  
            >		
            
            <ul class="kt-menu__nav ">

                <?php if(array_key_exists('Dashboard',$sidebarList)):?>
                    <li class="kt-menu__item " aria-haspopup="true" >
                        <a  href="<?= $this->Url->build(['controller'=>'Dashboard','action'=>'index'])?>" class="kt-menu__link ">
                            <i class="kt-menu__link-icon flaticon2-architecture-and-city"></i><span class="kt-menu__link-text">Dashboard</span></a>
                    </li>
                <?php endif;?>


                <!-- MASTER -->
                <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['Categories','SubCategories','Products', 'ProductsUnits', 'Institutes', 'Suppliers', 'Institutions', 'InitStocks']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">MASTER</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'Categories' => (object)[
                            'label' => 'Kategori',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'Categories','action'=>'index'])
                        ],
                        'SubCategories' => (object)[
                            'label' => 'Sub Kategori',
                            'icon' => 'fa fa-align-left',
                            'url' => $this->Url->build(['controller'=>'SubCategories','action'=>'index'])
                        ],
                        'Products' => (object)[
                            'label' => 'Barang',
                            'icon' => 'fa fa-cube',
                            'url' => $this->Url->build(['controller'=>'Products','action'=>'index'])
                        ],
                        'ProductUnits' => (object)[
                            'label' => 'Satuan Barang Terkecil',
                            'icon' => 'fa fa-genderless',
                            'url' => $this->Url->build(['controller'=>'ProductUnits','action'=>'index'])
                        ],
                         'Institutes' => (object)[
                            'label' => 'Unit Kerja',
                            'icon' => 'fa fa-laptop',
                            'url' => $this->Url->build(['controller'=>'Institutes','action'=>'index'])
                        ],
                         'Suppliers' => (object)[
                            'label' => 'Pemasok',
                            'icon' => ' fa fa-archive',
                            'url' => $this->Url->build(['controller'=>'Suppliers','action'=>'index'])
                        ],
                        'Institutions' => (object)[
                            'label' => 'Lembaga',
                            'icon' => ' fa fa-window-maximize',
                            'url' => $this->Url->build(['controller'=>'Institutions','action'=>'index'])
                        ],
                        'InitStocks' => (object)[
                            'label' => 'Stok Awal',
                            'icon' => ' fa fa-arrow-down',
                            'url' => $this->Url->build(['controller'=>'InitStocks','action'=>'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>
    <!-- Stok -->
    <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['ItemReceipts','ItemHandovers', 'ReportItemReceipts','ReportItemHandovers']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">STOK</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'ItemReceipts' => (object)[
                            'label' => 'Barang Masuk',
                            'icon' => 'fa fa-chevron-circle-down text-success',
                            'url' => $this->Url->build(['controller'=>'ItemReceipts','action'=>'index'])
                        ],
                        'ItemHandovers' => (object)[
                            'label' => 'Barang Keluar',
                            'icon' => 'fa fa-chevron-circle-up text-primary',
                            'url' => $this->Url->build(['controller'=>'ItemHandovers','action'=>'index'])
                        ],
                        'ReportItemReceipts' => (object)[
                            'label' => 'Laporan Barang Masuk',
                            'icon' => 'fa fa-file-pdf',
                            'url' => $this->Url->build(['controller'=>'ReportItemReceipts','action'=>'index'])
                        ],
                        'ReportItemHandovers' => (object)[
                            'label' => 'Laporan Barang Keluar',
                            'icon' => 'fa fa-file-pdf',
                            'url' => $this->Url->build(['controller'=>'ReportItemHandovers','action'=>'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>
    <!-- Permintaan user -->

                 <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['InternalOrders','PurchaseRequests','Approval','PurchaseSubmision',
                    'ExportProducts']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">PERMINTAAN</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'InternalOrders' => (object)[
                            'label' => 'Permintaan User',
                            'icon' => 'fa fa-download text-primary',
                            'url' => $this->Url->build(['controller'=>'InternalOrders','action'=>'index'])
                        ],
                        'Approval' => (object)[
                            'label' => 'Approval Permintaan User',
                            'icon' => 'flaticon2-checkmark text-primary',
                            'url' => $this->Url->build(['controller'=>'Approval','action'=>'index'])
                        ],
                        'PurchaseRequests' => (object)[
                            'label' => 'Permintaan Pembelian',
                            'icon' => 'fa fa-download text-success',
                            'url' => $this->Url->build(['controller'=>'PurchaseRequests','action'=>'index'])
                        ],
                        'ApprovalPR' => (object)[
                            'label' => 'Approval Permintaan Pembelian',
                            'icon' => 'flaticon2-checkmark text-success',
                            'url' => $this->Url->build(['controller'=>'ApprovalPR','action'=>'index'])
                        ],
                        'PurchaseSubmision' => (object)[
                            'label' => 'Pengajuan Pembelian barang',
                            'icon' => 'flaticon2-checkmark text-success',
                            'url' => $this->Url->build(['controller'=>'PurchaseSubmision','action'=>'index'])
                        ],
                        'ExportProducts' => (object)[
                            'label' => 'Export Pengajuan',
                            'icon' => 'fa fa-download  text-danger',
                            'url' => $this->Url->build(['controller'=>'ExportProducts','action'=>'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>

    <!-- Pembelian -->

                <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['PurchaseOrders']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">PEMBELIAN</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'PurchaseOrders' => (object)[
                            'icon' => 'fa fa-shopping-cart ',
                            'label' => 'Pembelian Pesanan',
                            'url' => $this->Url->build(['controller' => 'PurchaseOrders', 'action' => 'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>

    <!-- Penerimaan -->
                <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['ReceiptOthers','ReceiptGrants','ReceiptTransfers','ReceiptReclarifications']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">PENERIMAAN</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'ReceiptOthers' => (object)[
                            'label' => 'Barang Lainnya',
                            'icon' => ' fa fa-chevron-circle-down',
                            'url' => $this->Url->build(['controller'=>'ReceiptOthers','action'=>'index'])
                        ],

                        'ReceiptGrants' => (object)[
                            'icon' => ' fa fa-chevron-circle-down',
                            'label' => 'Barang Hibah',
                            'url' => $this->Url->build(['controller'=>'ReceiptGrants','action'=>'index'])
                        ],

                        'ReceiptTransfers' => (object)[
                            'label' => 'Barang Transfer Masuk',
                            'icon' => ' fa fa-chevron-circle-down',
                            'url' => $this->Url->build(['controller'=>'ReceiptTransfers','action'=>'index'])
                        ],

                        'ReceiptPurchases' => (object)[
                            'label' => 'Barang Pembelian',
                            'icon' => ' fa fa-chevron-circle-down',
                            'url' => $this->Url->build(['controller'=>'ReceiptPurchases','action'=>'index'])
                        ],

                        'ReceiptReclarifications' => (object)[
                            'label' => 'Barang Reklarifikasi Masuk',
                            'icon' => ' fa fa-chevron-circle-down',
                            'url' => $this->Url->build(['controller'=>'ReceiptReclarifications','action'=>'index'])
                        ]
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>

    <!-- pengeluaran -->

                 <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['ExpendituresGrants','ExpendituresOthers','ExpendituresReclarifications', 'ExpendituresTransfers', 'ExpendituresDistributions','UseInstitutes']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">PENGELUARAN</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'ExpendituresGrants' => (object)[
                            'label' => 'Barang Hibah',
                            'icon' => 'fa fa-chevron-circle-up',
                            'url' => $this->Url->build(['controller'=>'ExpendituresGrants','action'=>'index'])
                        ],

                        'ExpendituresOthers' => (object)[
                            'label' => 'Barang Lainnya',
                            'icon' => 'fa fa-chevron-circle-up',
                            'url' => $this->Url->build(['controller'=>'ExpendituresOthers','action'=>'index'])
                        ],

                        'ExpendituresReclarifications' => (object)[
                            'label' => 'Barang Reklarifikasi Keluar',
                            'icon' => 'fa fa-chevron-circle-up',
                            'url' => $this->Url->build(['controller'=>'ExpendituresReclarifications','action'=>'index'])
                        ],

                        'ExpendituresTransfers' => (object)[
                            'label' => 'Barang Transfer Keluar',
                            'icon' => 'fa fa-chevron-circle-up',
                            'url' => $this->Url->build(['controller'=>'ExpendituresTransfers','action'=>'index'])
                        ],

                        'ExpendituresDistributions' => (object)[
                            'label' => 'Distribusi Barang',
                            'icon' => 'fa fa-chevron-circle-up',
                            'url' => $this->Url->build(['controller'=>'ExpendituresDistributions','action'=>'index'])
                        ],

                        'UseInstitutes' => (object)[
                            'label' => 'Pemakaian Barang Unit Kerja',
                            'icon' => 'fa fa-chevron-circle-up',
                            'url' => $this->Url->build(['controller'=>'UseInstitutes','action'=>'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>
    <!-- STOK MANAGEMENT -->

                <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['MutationInstitutes','ReportMutations','ReportMutationRupiah','CardStocks']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">STOK MANAGEMENT</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'MutationInstitutes' => (object)[
                            'label' => 'Monitoring Stok Barang Unit Kerja',
                            'icon' => 'la la-area-chart',
                            'url'  => $this->Url->build(['controller'=>'MutationInstitutes','action'=>'index'])
                        ],
                        'ReportMutations' => (object)[
                            'label' => 'Monitoring Stok Barang Gudang',
                            'icon' => 'la la-area-chart',
                            'url'  => $this->Url->build(['controller'=>'ReportMutations','action'=>'index'])
                        ],
                        'ReportMutationRupiah' => (object)[
                            'label' => 'Monitoring Rupiah',
                            'icon' => 'la la-area-chart',
                            'url'  => $this->Url->build(['controller'=>'ReportMutationRupiah','action'=>'index'])
                        ],
                        'CardStocks' => (object)[
                            'label' => 'Kartu Stok',
                            'icon' => 'la la-clipboard',
                            'url'  => $this->Url->build(['controller'=>'CardStocks','action'=>'index'])
                        ],
                        'StockOpnames' => (object)[
                            'label' => 'Stok Opname',
                            'icon' => 'la la-ambulance',
                            'url'  => $this->Url->build(['controller'=>'StockOpnames','action'=>'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>
    <!-- LAPORAN -->
                <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['ReportMutationsStok','ReportMutationInstitutes','ReportInternalOrders','ReportPurchaseRequests','ReportPurchaseOrders','ReportExpendituresGrants','ReportExpendituresOthers','ReportExpendituresReclarifications','ReportExpendituresTransfers','ReportExpendituresDistributions','ReportReceiptGrants','ReportReceiptOthers','ReportReceiptReclarifications','ReportReceiptTransfers','ReportReceiptPurchases']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">LAPORAN</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'ReportMutationsStok' => (object)[
                            'label' => 'Laporan Mutasi Stok',
                            'icon' => 'fa fa-file-archive',
                            'url' => $this->Url->build(['controller'=>'ReportMutationsStok','action'=>'index'])
                        ],
                        'ReportMutationInstitutes' => (object)[
                            'label' => 'Laporan Pemakaian Barang Unit Kerja',
                            'icon' => 'fa fa-file-archive',
                            'url' => $this->Url->build(['controller'=>'ReportMutationInstitutes','action'=>'index'])
                        ],
                        'ReportInternalOrders' => (object)[
                            'label' => 'Laporan Permintaan User',
                            'icon' => 'fa fa-file-archive',
                            'url' => $this->Url->build(['controller'=>'ReportInternalOrders','action'=>'index'])
                        ],
                        'ReportPurchaseOrders' => (object)[
                            'label' => 'Laporan Pembelian Pesanan',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportPurchaseOrders','action'=>'index'])
                        ],
                        'ReportExpendituresGrants' => (object)[
                            'label' => 'Laporan Barang Hibah',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportExpendituresGrants','action'=>'index'])
                        ],
                        'ReportExpendituresOthers' => (object)[
                            'label' => 'Laporan Barang Lainnya',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportExpendituresOthers','action'=>'index'])
                        ],
                          'ReportExpendituresReclarifications' => (object)[
                            'label' => 'Laporan Reklarifikasi Keluar',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportExpendituresReclarifications','action'=>'index'])
                        ],
                         'ReportExpendituresTransfers' => (object)[
                            'label' => 'Laporan Barang Transfer Keluar',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportExpendituresTransfers','action'=>'index'])
                        ],
                        'ReportExpendituresDistributions' => (object)[
                            'label' => 'Laporan Distribusi Barang',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportExpendituresDistributions','action'=>'index'])
                        ],'ReportReceiptOthers' => (object)[
                            'label' => 'Laporan Barang Lainnya',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportReceiptOthers','action'=>'index'])
                        ],
                        'ReportReceiptGrants' => (object)[
                            'label' => 'Laporan Barang Hibah',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportReceiptGrants','action'=>'index'])
                        ],
                         'ReportReceiptTransfers' => (object)[
                            'label' => 'Laporan Barang Transfer Masuk',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportReceiptTransfers','action'=>'index'])
                        ],
                         'ReportReceiptPurchases' => (object)[
                            'label' => 'Laporan Barang Pembelian',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportReceiptPurchases','action'=>'index'])
                        ],
                        'ReportReceiptReclarifications' => (object)[
                            'label' => 'Laporan Barang Reklarifikasi Masuk',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportReceiptReclarifications','action'=>'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>
    

    <!-- Laporan Mutasi Stok -->

                <!-- <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['ReportMutationsStok']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">LAPORAN MUTASI STOK</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'ReportMutationsStok' => (object)[
                            'label' => 'Laporan Mutasi Stok',
                            'icon' => 'fa fa-file-archive',
                            'url' => $this->Url->build(['controller'=>'ReportMutationsStok','action'=>'index'])
                        ],
                        'ReportMutationInstitutes' => (object)[
                            'label' => 'Laporan Pemakaian Barang Unit Kerja',
                            'icon' => 'fa fa-file-archive',
                            'url' => $this->Url->build(['controller'=>'ReportMutationInstitutes','action'=>'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?> -->

    <!-- Laporan Mutasi Stok -->

                <!-- <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['ReportInternalOrders','ReportPurchaseRequests']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">LAPORAN PERMINTAAN</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'ReportInternalOrders' => (object)[
                            'label' => 'Laporan Permintaan User',
                            'icon' => 'fa fa-file-archive',
                            'url' => $this->Url->build(['controller'=>'ReportInternalOrders','action'=>'index'])
                        ],

                        //  'ReportPurchaseRequests' => (object)[
                        //     'label' => 'Laporan Permintaan Pembelian',
                        //     'icon' => 'fa fa-file-archive',
                        //     'url' => $this->Url->build(['controller'=>'ReportPurchaseRequests','action'=>'index'])
                        // ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?> -->

    <!-- Laporan Mutasi Stok -->

                <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['ReportPurchaseOrders']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">LAPORAN PEMBELIAN PESANAN</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'ReportPurchaseOrders' => (object)[
                            'label' => 'Laporan Pembelian Pesanan',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportPurchaseOrders','action'=>'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>

    <!-- Laporan Pengeluaran -->

                <!-- <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['ReportExpendituresGrants','ReportExpendituresOthers','ReportExpendituresReclarifications','ReportExpendituresTransfers','ReportExpendituresDistributions']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">LAPORAN PENGELUARAN</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'ReportExpendituresGrants' => (object)[
                            'label' => 'Laporan Barang Hibah',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportExpendituresGrants','action'=>'index'])
                        ],
                        'ReportExpendituresOthers' => (object)[
                            'label' => 'Laporan Barang Lainnya',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportExpendituresOthers','action'=>'index'])
                        ],
                          'ReportExpendituresReclarifications' => (object)[
                            'label' => 'Laporan Reklarifikasi Keluar',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportExpendituresReclarifications','action'=>'index'])
                        ],
                         'ReportExpendituresTransfers' => (object)[
                            'label' => 'Laporan Barang Transfer Keluar',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportExpendituresTransfers','action'=>'index'])
                        ],
                        'ReportExpendituresDistributions' => (object)[
                            'label' => 'Laporan Distribusi Barang',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportExpendituresDistributions','action'=>'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?> -->

<!-- Laporan Penerimaan -->

                <!-- <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['ReportReceiptGrants','ReportReceiptOthers','ReportReceiptReclarifications','ReportReceiptTransfers','ReportReceiptPurchases']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">LAPORAN PENERIMAAN</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'ReportReceiptOthers' => (object)[
                            'label' => 'Laporan Barang Lainnya',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportReceiptOthers','action'=>'index'])
                        ],
                        'ReportReceiptGrants' => (object)[
                            'label' => 'Laporan Barang Hibah',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportReceiptGrants','action'=>'index'])
                        ],
                         'ReportReceiptTransfers' => (object)[
                            'label' => 'Laporan Barang Transfer Masuk',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportReceiptTransfers','action'=>'index'])
                        ],
                         'ReportReceiptPurchases' => (object)[
                            'label' => 'Laporan Barang Pembelian',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportReceiptPurchases','action'=>'index'])
                        ],
                        'ReportReceiptReclarifications' => (object)[
                            'label' => 'Laporan Barang Reklarifikasi Masuk',
                            'icon' => 'fa fa-list',
                            'url' => $this->Url->build(['controller'=>'ReportReceiptReclarifications','action'=>'index'])
                        ]
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?> -->
    <!-- user -->

                <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['UserGroups','Users']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">AKSESIBILITAS</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'UserGroups' => (object)[
                            'label' => 'Grup Pengguna',
                            'icon' => 'flaticon-users',
                            'url'  => $this->Url->build(['controller'=>'UserGroups','action'=>'index'])
                        ],
                        'Users' => (object)[
                            'label' => 'Pengguna',
                            'icon' => 'flaticon-user',
                            'url' => $this->Url->build(['controller'=>'Users','action'=>'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>


    <!-- next sidebar -->
                <?php 
                    $countCheckSideBar = $this->Utilities->sideBarArrayCheck($sidebarList,['AppSettings']);
                    if($countCheckSideBar > 0):
                ?>
                    <li class="kt-menu__section ">
                        <h4 class="kt-menu__section-text">SYSTEMS</h4>
                        <i class="kt-menu__section-icon flaticon2-gear"></i>
                    </li>
                    <?php 
                    $navList = (object)[
                        'AppSettings' => (object)[
                            'label' => 'Z-APP Settings',
                            'icon' => 'flaticon-cogwheel',
                            'url' => $this->Url->build(['controller'=>'AppSettings','action'=>'index'])
                        ],
                    ];
                    foreach($navList as $key => $nav):
                        if(array_key_exists($key,$sidebarList)):
                    ?>
                        <li class="kt-menu__item <?=($this->request->getParam('controller')==$key ? 'kt-menu__item--active' : '');?>" aria-haspopup="true" >
                            <a  href="<?=$nav->url;?>" class="kt-menu__link ">
                                <i class="kt-menu__link-icon <?=$nav->icon;?>"></i>
                                <span class="kt-menu__link-text"><?=$nav->label;?></span>
                            </a>
                        </li>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>
            </ul>
            
        </div>
    </div>
    <!-- end:: Aside Menu -->				
</div>
<!-- end:: Aside -->