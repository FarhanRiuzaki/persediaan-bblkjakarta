<style>
	/* Define the hover highlight color for the table row */
    .hoverTable tr.detailIn:hover {
          background-color: #ffff99;
    }

    .hoverTable tr.detailOut:hover {
          background-color: #ffff99;
    }

</style>
<!--Begin::Main Portlet-->
<div class="row">
<div class="col-xl-12">
        <!--begin:: Widgets/Best Sellers-->
        <div class="kt-portlet m-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    Daftar Pengajuan Pembelian Barang
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin::Content-->
                <div class="tab-content">
                    <div class="tab-pane active table-scroll" id="m_widget5_tab1_content" aria-expanded="true">
                        <div class=" col-md-12">
                            <table class="table table-striped datatable">
                                <thead>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Asal</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal Diproses</th>
                                    <th>Status</th>
                                </thead>

                                <tbody>

                                    <?php $no = 1;  ?>
                                    <?php foreach ($purchaseSubmisions as $val): ?>
                                        <tr>
                                            <th><?=$no++;?></th>
                                            <th><?=$val->code;?></th>
                                            <th><?=$val->created_user->name;?></th>
                                            <th><?=$this->Utilities->indonesiaDateFormat($val->date->format('Y-m-d'));?></th>
                                            <th><?= $val->approve_date == null ? '-' : $this->Utilities->indonesiaDateFormat($val->approve_date->format('Y-m-d'));?></th>
                                            <th><?=$this->Utilities->statusPP($val->status, 'PSview', $val->id, $val->institute_id);?></th>
                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
        <!-- <div Widgets/Best Sellers-->
    </div>

<div class="col-xl-6">
        <!--begin:: Widgets/Best Sellers-->
        <div class="kt-portlet m-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Barang Yang Harus Dibeli
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="#" class="btn btn-label-success btn-sm btn-bold dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Export
                    </a>
                    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(309px, 46px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <ul class="kt-nav">
                            <li class="kt-nav__item">
                                <a href="<?= $this->Url->build(['action'=>'print'])?>" target='_BLANK' class="kt-nav__link">
                                    <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                    <span class="kt-nav__link-text">Print Pdf</span>
                                </a>
                            </li>
                        </ul>			
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin::Content-->
                <div class="tab-content">
                    <select name="choice" id="choice" class='form-control'>
                        <option value="1">Tampilkan Semua</option>
                        <?php $no = 2; foreach($categories as $key => $val):?>
                            <option value="<?= $no++?>"><?= $val->name?></option>
                        <?php endforeach;?>
                    </select>
                    <br>
                    <div class="tab-pane active table-scroll" id="m_widget5_tab1_content" aria-expanded="true">
                        <div class=" col-md-12">
                            
                            <table class="table table-striped" id='datatableStok'>
                                <thead>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th width='80px'>Stok</th>
                                </thead>
                                
                                <tbody>
                                    
                                    <?php
                                    use App\Controller\Component\UtilitiesComponent;

                                    $no = 1;  ?>
                                    <?php foreach ($stokKurang as $val): ?>
                                    <tr>
                                        <th><?=$no++;?></th>
                                        <th><?=$val['category'];?></th>
                                        <th><?=$val['kode'];?></th>
                                        <th><?=$val['nama'] ;?></th>
                                        <th><?=$val['saldo_akhir']  ;?></th>
                                    </tr>
                                    <?php endforeach; ?>
                                    
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
        <!-- <div Widgets/Best Sellers-->
    </div>
    <div class="col-xl-6">
        <!--begin:: Widgets/Best Sellers-->
        <div class="kt-portlet m-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    Daftar Permintaan Pembelian Barang
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin::Content-->
                <div class="tab-content">
                    <div class="tab-pane active table-scroll" id="m_widget5_tab1_content" aria-expanded="true">
                        <div class="col-md-12">
                        <table class="table table-striped datatable">
                            <thead>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Instalasi</th>
                                <th>Kode Permintaan</th>
                                <th>Nama Barang</th>
                                <th>no Katalog</th>
                                <th>Jumlah</th>
                            </thead>
                            <tbody>
                                <?php $no = 1;  ?>
                                <?php foreach ($purchaseRequestsDetails as $r): ?>
                                    <tr>
                                        <th><?=$no++;?></th>
                                        <th><?=$this->Utilities->indonesiaDateFormat($r->purchase_request->date->format('Y-m-d'));?></th>
                                        <th><?=$r->purchase_request->institute->name;?></th>
                                        <th><?=$r->purchase_request->code;?></th>
                                        <th><?=$r->product->name;?></th>
                                        <th><?=$r->product->no_catalog;?></th>
                                        <th><?= $r->qty?></th>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
        <!--end:: Widgets/Best Sellers-->
    </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<?php $this->start('script'); ?>

<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    
<script type="text/javascript">
    $('.datatable').DataTable({
        "language": {
            "emptyTable":     "Tidak ada transaksi atau data kosong."
        }
    });
    var table = $('#datatableStok').DataTable();

    $("#choice").on("change",function(){
        
        var _val = $(this).val();
        console.log(_val);
        if(_val == 2){   
               table
               .columns(1)
               .search('Alat Tulis Kantor', true, false)
               .draw();
         }else if(_val == 3){   
               table
               .columns(1)
               .search('Media Reagensia dan Barang Habis Pakai', true, false)
               .draw();
         }else if(_val == 4){   
               table
               .columns(1)
               .search('Perlengkapan Kebersihan dan Peralatan Rumah Tangga', true, false)
               .draw();
         }else if(_val == 5){   
               table
               .columns(1)
               .search('Persediaan Lainnya', true, false)
               .draw();
         }else{
               table
               .columns()
               .search('')
               .draw(); 
         }
    });

</script>

<?php $this->end(); ?>
