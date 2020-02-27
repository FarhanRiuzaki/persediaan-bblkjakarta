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
    <?php if($userData->user_group_id == 1):?>
    <div class="col-xl-6">
        <!--begin:: Widgets/Best Sellers-->
        <div class="kt-portlet m-portlet--tab"  style="border-color: #007bff!important; border: 2px solid black;">
            <div class="kt-portlet__head bg-primary">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title text-white">
                    Daftar Permintaan User
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
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </thead>
                                
                                <tbody>
                                    
                                    <?php

                                    $no = 1;  ?>
                                    <?php foreach ($internalOrders as $internalOrder): ?>
                                    <tr>
                                        <th><?=$no++;?></th>
                                        <th><?=$internalOrder->code;?></th>
                                        <th><?=$internalOrder->institute->name;?></th>
                                        <th><?=$this->Utilities->indonesiaDateFormat($internalOrder->date->format('Y-m-d'));?></th>
                                        <th><?=$this->Utilities->statusLabelIo($internalOrder->status, 'IO', $internalOrder->id, $internalOrder->institute_id);?></th>
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
        <div class="kt-portlet m-portlet--tab " style="border-color:#ffc107!important; border: 2px solid black;">
            <div class="kt-portlet__head bg-warning">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    Daftar Pending Approval
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
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </thead>
                                
                                <tbody>
                                    
                                    <?php

                                    $no = 1;  ?>
                                    <?php foreach ($pendingApproval as $internalOrder): ?>
                                    <tr>
                                        <th><?=$no++;?></th>
                                        <th><?=$internalOrder->code;?></th>
                                        <th><?=$internalOrder->institute->name;?></th>
                                        <th><?=$this->Utilities->indonesiaDateFormat($internalOrder->date->format('Y-m-d'));?></th>
                                        <th><?=$this->Utilities->statusLabelIo($internalOrder->status, 'IO', $internalOrder->id, $internalOrder->institute_id);?></th>
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
        <div class="kt-portlet m-portlet--tab" style="border-color: #0abb87 !important; border: 2px solid black;">
            <div class="kt-portlet__head bg-success">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title text-white">
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
        <div class="kt-portlet m-portlet--tab" style="border-color: #0abb87 !important; border: 2px solid black;">
            <div class="kt-portlet__head bg-success">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title text-white">
                    Daftar Approval Pembelian Barang
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
                                    <?php foreach ($approvalPR as $val): ?>
                                        <tr>
                                            <th><?=$no++;?></th>
                                            <th><?=$val->code;?></th>
                                            <th><?=$val->institute->name;?></th>
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
    <?php else:?>
    <div class="col-xl-12">
        <!--begin:: Widgets/Best Sellers-->
        <div class="kt-portlet m-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    Daftar Permintaan User
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
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </thead>
                                
                                <tbody>
                                    
                                    <?php

                                    $no = 1;  ?>
                                    <?php foreach ($internalOrders as $internalOrder): ?>
                                    <tr>
                                        <th><?=$no++;?></th>
                                        <th><?=$internalOrder->code;?></th>
                                        <th><?=$internalOrder->institute->name;?></th>
                                        <th><?=$this->Utilities->indonesiaDateFormat($internalOrder->date->format('Y-m-d'));?></th>
                                        <th><?=$this->Utilities->statusLabelIo($internalOrder->status, 'IO', $internalOrder->id, $internalOrder->institute_id);?></th>
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
    <?php endif;?>
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
                    <?php if($userId == 1):?>
                    <select name="choice" id="choice" class='form-control'>
                        <option value="1">Tampilkan Semua</option>
                        <?php $no = 2; foreach($categories as $key => $val):?>
                            <option value="<?= $no++?>"><?= $val->name?></option>
                        <?php endforeach;?>
                    </select>
                    <br>
                    <?php endif;?>
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
        <!-- <div class="kt-portlet m-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    Daftar Permintaan Pembelian
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body"> -->
                <!--begin::Content-->
                <!-- <div class="tab-content">
                    <div class="tab-pane active table-scroll" id="m_widget5_tab1_content" aria-expanded="true">
                        <div class="col-md-12">
                        <table class="table table-striped">
                            <thead>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Asal</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </thead>

                            <tbody>

                                <?php $no = 1;  ?>
                                <?php foreach ($purchaseRequests as $purchaseRequest): ?>
                                    <tr>
                                        <th><?=$no++;?></th>
                                        <th><?=$purchaseRequest->code;?></th>
                                        <th><?=$purchaseRequest->institute->name;?></th>
                                        <th><?=$this->Utilities->indonesiaDateFormat($purchaseRequest->date->format('Y-m-d'));?></th>
                                        <th><?=$this->Utilities->statusLabelIo($purchaseRequest->status, 'PR', $purchaseRequest->id, $purchaseRequest->institute_id);?></th>
                                    </tr>
                                <?php endforeach; ?>
                                <?php foreach ($purchaseSubmisions as $val): ?>
                                    <tr>
                                        <th><?=$no++;?></th>
                                        <th><?=$val->code;?></th>
                                        <th><?=$val->institute->name;?></th>
                                        <th><?=$this->Utilities->indonesiaDateFormat($val->date->format('Y-m-d'));?></th>
                                        <th><?=$this->Utilities->statusLabelIo($val->status, 'PS', $val->id, $val->institute_id);?></th>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>
                        </div>
                    </div>
                </div> -->
                <!--end::Content-->
            <!-- </div>
        </div> -->
        <!--end:: Widgets/Best Sellers-->
        <div class="kt-portlet m-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    Daftar Permintaan Pembelian Barang
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <a href="#" class="btn btn-label-success btn-sm btn-bold dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        Proses
                    </a>
                    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(309px, 46px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <ul class="kt-nav">
                            <li class="kt-nav__item">
                                <a href="<?= $this->Url->build(['controller' => 'PurchaseSubmision', 'action'=>'add'])?>?type=0" target='_BLANK' class="kt-nav__link">
                                    <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                    <span class="kt-nav__link-text">Ajukan Permintaan</span>
                                </a>
                            </li>
                        </ul>			
                    </div>
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
    </div>
</div>
<!--End::Main Portlet-->


<!--Begin::Main Portlet-->
<div class="row">
    <div class="col-xl-12">
        <!--begin:: Widgets/Best Sellers-->
        <div class="kt-portlet m-portlet--tab">
            <div class="kt-portlet__body">
                <!--begin::Content-->
                <div class="tab-content">
                    <div class="tab-pane active" id="m_widget5_tab1_content" aria-expanded="'PR'">
                        <div>
                            <div id="bar-chart"></div>
                        </div>
                    </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
        <!--end:: Widgets/Best Sellers-->
    </div>
</div>
<!--End::Main Portlet-->

<!--Begin::Main Portlet-->
<div class="row">
    <div class="col-xl-6">
        <!--begin:: Widgets/Best Sellers-->

        <div class="kt-portlet m-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    10 Daftar Transaksi Terakhir Barang Masuk
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin::Content-->
                <div class="tab-content">
                    <div class="tab-pane active" id="m_widget5_tab1_content" aria-expanded="true">

                        <table class="table table-striped hoverTable">
                            <thead>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                            </thead>

                            <tbody>
                                <?php $no_in = 1;  ?>
                                <?php foreach ($in_unions as $in_union): 
                                    if(is_numeric($in_union->type)){
                                        $type = $this->Utilities->categorieIn()[$in_union->type];
                                    }else{
                                        $type = $in_union->type;
                                    }
                                    ?>
                                    <tr class='detailIn'>
                                        <th><?=$no_in++;?></th>
                                        <th class='code'><?=$in_union->code;?></th>
                                        <th><?=$this->Utilities->indonesiaDateFormat($in_union->date->format('Y-m-d'));?></th>
                                        <th><?= $type;?></th>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if($no_in == 1):?>
                                    <tr>
                                        <td colspan="4" align='center'> Data Tidak Tersedia</td>
                                    </tr>
                                <?php endif;?>
                            </tbody>
                        </table>

                    </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
        <!--end:: Widgets/Best Sellers-->
    </div>
<!--End::Main Portlet-->

<!--Begin::Main Portlet-->
    <div class="col-xl-6">
        <!--begin:: Widgets/Best Sellers-->
       
        <div class="kt-portlet m-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    10 Daftar Transaksi Terakhir Barang Keluar
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin::Content-->
                <div class="tab-content">
                    <div class="tab-pane active" id="m_widget5_tab1_content" aria-expanded="true">

                        <table class="table table-striped hoverTable">
                            <thead>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                            </thead>

                            <tbody>

                                <?php $no_out = 1;  ?>
                                <?php foreach ($out_unions as $out_union): 
                                        if(is_numeric($out_union->type)){
                                            $type = $this->Utilities->categorieOut()[$out_union->type];
                                        }else{
                                            $type = $out_union->type;
                                        }
                                    ?>
                                    <tr class='detailOut'>
                                        <th><?=$no_out++;?></th>
                                        <th class='code'><?=$out_union->code;?></th>
                                        <th><?=$this->Utilities->indonesiaDateFormat($out_union->date->format('Y-m-d'));?></th>
                                        <th><?=$type;?></th>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if($no_out == 1):?>
                                    <tr>
                                        <td colspan="4" align='center'> Data Tidak Tersedia</td>
                                    </tr>
                                <?php endif;?>

                            </tbody>
                        </table>

                    </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
        <!--end:: Widgets/Best Sellers-->
    </div>
</div>
<!--End::Main Portlet-->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Transaksi: <span id='codeHeader'></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 align='center' id=titleDetail></h4>
        <br>
        <table class="table table-bordered ">
            <thead>
                <th>No</th>
                <th class="text-center">Nama Barang</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Kadaluwarsa</th>
            </thead>
            <tbody class='bodyDetail'>

            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<?php $this->start('script'); ?>

<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    
<script type="text/javascript">
    $('body').on('click', '.detailIn', function(){
        var code = $(this).children('th.code').text();
        $.ajax({
            url: '<?=$this->Url->build(['action'=>'getDetail', 'controller' => 'apis']);?>',
            data: {code: code},
            dataType : 'json',
            beforeSend : function(){
                    swal.fire({
                        type : 'info',
                        title: 'Harap menunggu',
                        text: 'Sedang mengambil data',
                        showCancelButton: false,
                        showConfirmButton: false,
                        allowOutsideClick : false,
                        allowEscapeKey : false,
                        allowEnterKey : false
                    })
                },
            success: function(response){
                swal.fire('Berhasil','','success');  
                $('#detailModal').modal('show');
                $('#codeHeader').text(code);
                $('h4#titleDetail').text('Barang Masuk');
                var data = response.results[0],
                    no  = 1;
                $('.bodyDetail').empty();
                $.each(data.item_receipts_details, function(key, val){
                    var exp     = (val.expired == null) ? '-' : Utils.dateIndonesia(val.expired,true,false),
                        unit    = (val.product.unit == null) ? '' : val.product.unit;

                    var html = '<tr>'
                            +'<td>'+ (no++) +'</td>'
                            +'<td>'+ val.product.name +'</td>'
                            +'<td>'+ val.qty + ' ' + unit +'</td>'
                            +'<td>'+ $.number(val.price, 2) +'</td>'
                            +'<td align="center">'+ exp +'</td>'
                            + '</tr>';
                    $('.bodyDetail').append(html);
                })
            },
            error: function(){
                swal.fire('Error!','Terjadi kesalahan','error');  

            }
        })
    });

    $('body').on('click', '.detailOut', function(){
        var code = $(this).children('th.code').text();
        $.ajax({
            url: '<?=$this->Url->build(['action'=>'getDetailOut', 'controller' => 'apis']);?>',
            data: {code: code},
            dataType : 'json',
            beforeSend : function(){
                    swal.fire({
                        type : 'info',
                        title: 'Harap menunggu',
                        text: 'Sedang mengambil data',
                        showCancelButton: false,
                        showConfirmButton: false,
                        allowOutsideClick : false,
                        allowEscapeKey : false,
                        allowEnterKey : false
                    })
                },
            success: function(response){
                swal.fire('Berhasil','','success');  
                $('#detailModal').modal('show');
                $('#codeHeader').text(code);
                $('h4#titleDetail').text('Barang Keluar');
                var data = response.results,
                    no  = 1;
                    console.log(response);
                    
                $('.bodyDetail').empty();
                $.each(data, function(key, val){
                    var html = '<tr>'
                            +'<td>'+ (no++) +'</td>'
                            +'<td>'+ val.name +'</td>'
                            +'<td>'+ val.qty +'</td>'
                            +'<td>'+ $.number(val.harga, 2) +'</td>'
                            +'<td align="center">'+ val.exp +'</td>'
                            + '</tr>';
                    $('.bodyDetail').append(html);
                })
            },
            error: function(){
                swal.fire('Error!','Terjadi kesalahan','error');  
                
            }
        })
    });

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

     $(document).ready(function(){
        var barChart;
        var xhr;

        function visitorData (data) {
           $('#bar-chart').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Grafik Mutasi Barang Selama Tahun <?=date('Y')?>'
            },
            xAxis: {
                categories: data.categories,
                crosshair: true
            },
            yAxis: {
                title: {
                    text: 'Jumlah Barang'
                },
                labels : {
                    step : 1
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Stok Awal',
                data: data.data.saldo_awal,

            }, {
                name: 'Masuk',
                data: data.data.in,
            },{
                name: 'Keluar',
                data: data.data.out,

            },{
                name: 'Stok Akhir',
                data: data.data.saldo,

            }]
          });
        }

        function loadChart(){
            if(xhr != undefined){
                if(xhr.readyState == 4){
                    xhr.abort();
                }
            }

            xhr = $.ajax({
                url: '<?=$this->Url->build('/dashboard');?>',
                dataType: 'json',
                type: 'GET',
                success: function(datas){
                    var idx = 0;
                    var newDataIn = new Array;
                    var newDataOut = new Array;
                    var newDataSaldo = new Array;
                    var newDataSaldoSAwal = new Array;
                    var months = new Array;

                    $.each(datas.datas,function(e,i){
                        months[idx] = e;
                        newDataIn[idx] = i.in*1;
                        newDataOut[idx] = i.out*1;
                        newDataSaldo[idx] = i.saldo*1;
                        newDataSaldoSAwal[idx] = i.saldo_awal*1;

                        idx++;
                    })

                    var setData = {
                        categories : months,
                        data : {
                            in : newDataIn,
                            out : newDataOut,
                            saldo : newDataSaldo,
                            saldo_awal : newDataSaldoSAwal
                        }
                    };

                   visitorData(setData);
                }
             })
        }

        function runLoad(){
            setInterval(loadChart,5000);
        }

        if(barChart == undefined ){
            loadChart()
        }
    });
</script>

<?php $this->end(); ?>
