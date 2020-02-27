<style>
    @media print{
        body *{
            visibility: hidden;
        }
        #section-to-print, #section-to-print * {
            visibility: visible;
        }
        #section-to-print {
            /* position: absolute; */
            left: 0;
            top: 0;

        }
        .btn-show-modal{
            display: none;
        }
        body{
            background-color: transparent!important;
        }
        .kt-portlet__body,
        .table-invoice{
            margin-top: 0!important;
            padding-top: 0!important;
        }
        .table-invoice *{
            font-size: 15px!important;
        }
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="kt-portlet kt-portlet--height-fluid">

            <div class="kt-portlet__head" id='hide'>
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
    						<?=$titlesubModule;?>
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="#" class="btn btn-outline-primary  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Print
                        </a>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(837px, 46px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <ul class="kt-nav">
                            <li class="kt-nav__item">
                                <a href="#" class="kt-nav__link no-print"  onclick="window.print()">
                                    <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                    <span class="kt-nav__link-text">Print View</span>

                                </a>
                            </li>
                            <li class="kt-nav__item">
                                <a href="<?= $this->Url->build(['action' => 'view', $purchaseOrder->id, '?' => ['print' => 'pdf']])  ?>" class="kt-nav__link no-print btn-print " target="_BLANK" data-print="pdf">
                                    <i class="kt-nav__link-icon flaticon2-send"></i>
                                    <span class="kt-nav__link-text">Print PDF</span>
                                </a>
                            </li>
                        </ul>			
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body" id='section-to-print'>
                <table class="table table-invoice">
                    <thead>
                        <tr>
                            <th width="40%" colspan="3">
                                
                            </th>
                            <th width="10%">&nbsp;</th>
                            <th class="text-right" colspan="3">
                                <div class="information-bill">
                                    <h3>PENGAJUAN PEMBELIAN BARANG</h3>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th width="15%">Kode</th>
                            <th width="2%">:</th>
                            <th width="18%"><?=$purchaseOrder->code;?></th>
                            <th width="18%"></th>
                            <th width="15%" >Tanggal Dibuat</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"><?=(!empty($this->Utilities->indonesiaDateFormat($purchaseOrder->created->format('Y-m-d')))) ? $this->Utilities->indonesiaDateFormat($purchaseOrder->created->format('Y-m-d')) : '-' ;?></th>
                        </tr>
                        <tr>
                            <th width="17%">Tanggal Diproses</th>
                            <th width="2%">:</th>
                            <th width="18%"><?= $purchaseOrder->approve_date == null ? '-' : $this->Utilities->indonesiaDateFormat($purchaseOrder->approve_date->format('Y-m-d'));?></th>
                            <th width="18%"></th>
                            <th width="17%">Dibuat Oleh</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"> <?= (!empty($purchaseOrder->created_user->name)) ? $purchaseOrder->created_user->name : '-' ?></th>
                        </tr>
                        <tr>
                            <th width="17%">Status</th>
                            <th width="2%">:</th>
                            <th width="18%" ><?=$this->Utilities->statusPP($purchaseOrder->status);?></th>
                            <th width="18%"></th>
                            <th width="17%">Diproses Oleh</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"> <?= (!empty($name)) ? $name : '-' ?></th>
                        </tr>
                        
                    </tbody>
                </table>
                <br>
                <table class="table table-condensed table-bordered table-input">
                    <thead>
                        <tr class="text-center">
                            <th  style="vertical-align: middle" width="50px">No.</th>
                            <th  style="vertical-align: middle" width="350px">Nama Unit Kerja</th>
                            <th  style="vertical-align: middle" width="150px">No. Permintaan</th>
                            <th  style="vertical-align: middle" width="350px">Nama Barang</th>
                            <th  style="vertical-align: middle" width="150px">No Katalog</th>
                            <th  style="vertical-align: middle" width="150px">Spesifikasi</th>
                            <th  style="vertical-align: middle" width="150px">Merek</th>
                            <th  style="vertical-align: middle" width="100px">Jumlah Permintaan</th>
                            <!-- <th  style="vertical-align: middle" width="100px">Satuan</th> -->
                            <th  style="vertical-align: middle" width="200px">Jumlah Disetujui Gudang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form id='edit'>
                        <?php if (!empty($purchaseOrder->purchase_submisions_details)):?>
                            <?php  $no = 1;  foreach($productGroup as $key =>$g):?>
                                <tr style="background-color: #dfe6e9">
                                    <th><?= $no++ ?></th>
                                    <th colspan="8"><?= $g->name?></th>
                                </tr>
                                <?php 
                                $total = 0;
                                $unit = '';
                                foreach ($purchaseOrder->purchase_submisions_details as $key => $r):?>
                                    <?php if($g->product_id == $r->product->id):?>
                                        <tr>
                                            <td class="noBorder"></td>
                                            <td><?=$r->purchase_request->institute->name;?></td>
                                            <td><?=$r->purchase_request->code;?></td>
                                            <td>[<?=$r->product->code;?>] <?=$r->product->name;?></td>
                                            <td><?=$r->purchase_requests_detail->no_catalog;?></td>
                                            <td><?=$r->purchase_requests_detail->spec;?></td>
                                            <td><?=$r->purchase_requests_detail->merk;?></td>
                                            <td class="text-center"><?=$this->Number->format($r->purchase_requests_detail->qty, ['precision' => 2]);?></td>
                                            <!-- <td><?=$r->product->unit ;?></td> -->
                                            <?php if($purchaseOrder->status == 0):?>
                                            <td class="text-right">

                                                <input id="qty_<?=$key?>" type="text" data-type='number' class="onlyNumberWithoutSeparator form-control m-input item_qty" value="<?=$r->qty?>" name="purchase_submisions_details[<?=$key;?>][qty]" data-name='item_qty<?= $g->product_id?>' min='0'>

                                                <input type="hidden" class="form-control m-input" value="<?=$r->id;?>" name="purchase_submisions_details[<?=$key;?>][id]">
                                                <input type="hidden" class="form-control m-input" value="<?=$r->purchase_request_id;?>" name="purchase_submisions_details[<?=$key;?>][purchase_request_id]">
                                                <input type="hidden" class="form-control m-input" value="<?=$r->purchase_requests_detail_id;?>" name="purchase_submisions_details[<?=$key;?>][purchase_requests_detail_id]">
                                                <input type="hidden" class="form-control m-input" value="<?=$r->product->id;?>" name="purchase_submisions_details[<?=$key;?>][product_id]">
                                                <input type="hidden" class="form-control m-input" value="<?=$r->purchase_submision_id;?>" name="purchase_submisions_details[<?=$key;?>][purchase_submision_id]">
                                            </td>
                                            <?php else:?>
                                            <td class="text-center">
                                                <?=$r->qty?>
                                            </td>
                                            <?php endif;?>
                                        </tr>
                                    <?php 
                                    $total += $r->qty;
                                    $unit = $r->product->unit;
                                    endif; ?>
                                <?php endforeach;?>
                                <tr >
                                    <td colspan="8" class="text-right">Total:</td>
                                    <td  class="text-center"><p><b  id='total<?= $g->product_id?>'> <?= $total ?> </b> <?= $unit?></p></td>
                                </tr>
                            <?php endforeach;?>
                        <?php else:?>
                            <tr>
                                <td colspan="6">Belum tersedia pemesanan</td>
                            </tr>
                        <?php endif;?>
                        </form>

                    </tbody>
                </table>
                <?php if($purchaseOrder->status == 0 && ($userData->user_group_id != 6 && $userData->user_group_id != 4 && $userData->user_group_id != 5)): ?>
                <div class="row">
                    <div class="col-md-2">
                        <span ><a href="#" class="btn btn-outline-success m-btn confirm btn-block" data-id='<?= $purchaseOrder->id?>'>Setujui</a></span>
                    </div>
                    <!-- <div class="col-md-2">
                        <span ><a href="#" class="btn btn-outline-danger m-btn cancel btn-block" data-id='<?= $purchaseOrder->id?>'>Tolak</a></span>                    
                    </div> -->
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php $this->start('script'); ?>
<script>
    $('body').on('click', '.confirm', function(){
        var id      = $(this).data('id');
        var csrf    = "<?= $this->request->_csrfToken ?>";
        var form    = $('#edit').serialize();
        console.log(form);
        // return false;
        Swal.fire({
        title: 'Anda yakin untuk Menyetujui Permohonan?',
        text: "Data tidak dapat diubah kembali",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Selesai',
        cancelButtonText: 'Batal'
        }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "<?= $this->Url->build(['controller' => 'PurchaseSubmision', 'action' =>'updateStatus'])?>" + '/' + id,
                type: "POST",
                headers: { 'X-XSRF-TOKEN' : csrf },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrf);
                },
                dataType: 'json',
                data: form,
                success: function(){
                    Swal.fire(
                    'Berhasil!',
                    'Telah Berhasil Disetujui.',
                    'success'
                    )
                    window.location.reload();
                },
                error: function(){
                    Swal.fire(
                    'Oopss!',
                    'Terjadi kesalahan.',
                    'error'
                    )
                }
            })
            
        }
        })
    });

    $('body').on('click', '.cancel', function(){
        var id = $(this).data('id');
        var csrf = "<?= $this->request->_csrfToken ?>";
        // console.log(csrf);
        Swal.fire({
        title: 'Anda yakin untuk Menolak Permohonan?',
        text: "Data tidak dapat diubah kembali",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Selesai',
        cancelButtonText: 'Batal'
        }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "<?= $this->Url->build(['controller' => 'PurchaseSubmision', 'action' =>'updateStatus'])?>",
                type: "POST",
                headers: { 'X-XSRF-TOKEN' : csrf },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrf);
                },
                dataType: 'json',
                data: {
                    id:id,
                    status: '2'
                },
                success: function(){
                    Swal.fire(
                    'Berhasil!',
                    'Telah Berhasil Ditolak.',
                    'success'
                    )
                    window.location.reload();
                },
                error: function(){
                    Swal.fire(
                    'Oopss!',
                    'Terjadi kesalahan.',
                    'error'
                    )
                }
            })
            
        }
        })
    });
</script>
<?php $this->end(); ?>