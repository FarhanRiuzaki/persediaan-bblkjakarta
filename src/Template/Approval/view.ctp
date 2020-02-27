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
                                <a href="<?= $this->Url->build(['action' => 'view', $internalOrder->id, '?' => ['print' => 'pdf']])  ?>" class="kt-nav__link no-print btn-print " target="_BLANK" data-print="pdf">
                                    <i class="kt-nav__link-icon flaticon2-send"></i>
                                    <span class="kt-nav__link-text">Print PDF</span>
                                </a>
                            </li>
                        </ul>			
            </div>
		</div>
	</div>
    <div class="kt-portlet__body" id='section-to-print'>
        <!--begin: Datatable -->
        <table class="table table-invoice">
            <thead>
                <tr>
                    <th width="40%" colspan="3">
                    </th>
                    <th width="10%">&nbsp;</th>
                    <th class="text-right" colspan="3">
                        <div class="information-bill">
                            <h3>PERMINTAAN USER</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th width="15%">Nama Unit Kerja</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$internalOrder->institute->name;?></th>
                    <th width="18%"></th>
                    <th width="17%">Kode Permintaan Masuk</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$internalOrder->code;?></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$this->Utilities->indonesiaDateFormat($internalOrder->created->format('Y-m-d'));?></th>
                    <th width="18%"></th>
                    <th width="17%">Tanggal Permintaan</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($internalOrder->date->format('Y-m-d'));?></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Approval</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$internalOrder->approve_date == null ? '-' : $this->Utilities->indonesiaDateFormat($internalOrder->approve_date->format('Y-m-d')) . ' ' . $internalOrder->approve_date->format('H:i:s');?></th>
                    <th width="18%"></th>
                    <th width="17%">Status</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->statusLabelIo($internalOrder->status);?></th>
                </tr>

                <tr>
                    <td colspan="15">
                        <table class="table table-bordered table-detail">
                            <thead>
                                <tr>
                                    <th colspan="5" class="text-center" style="background:#fafafa;">DATA BARANG</th>
                                </tr>
                                <tr>
                                    <th width="2%">No</th>
                                    <th>Nama Barang</th>
                                    <th width="15%">Jumlah Diminta</th>
                                    <th width="15%">Jumlah Disetujui Kepala Ruangan</th>
                                    <th width="15%">Jumlah Disetujui Gudang</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                                <?php $no_detail = 0; ?>

                                <?php if (!empty($internalOrder->internal_orders_details)): ?>
                                    <?php foreach ($internalOrder->internal_orders_details as $internalOrdersDetails): ?>
                                    <tr>
                                        <td><?= h($no_detail += 1) ?></td>
                                        <td><?= h($internalOrdersDetails->product->name) ?></td>
                                        <td><span class="onlyNumberWithoutSeparator"><?= h($internalOrdersDetails->qty_request) ?></span> <?= h($internalOrdersDetails->product->unit) ?></td>
                                        <?php if($internalOrder->status == 3): ?>
                                            <td>0 <?= h ($internalOrdersDetails->product->unit) ?></td>
                                        <?php else:?>
                                            <td><span class="onlyNumberWithoutSeparator"><?= h($internalOrdersDetails->qty) ?></span> <?= h ($internalOrdersDetails->product->unit) ?></td>
                                        <?php endif;?>
                                        <?php
                                            if(is_null($approve)){
                                                $appr = 0;
                                            }else{
                                                if(isset($approve[$internalOrdersDetails->product_id])){
                                                    $appr = $approve[$internalOrdersDetails->product_id];
                                                }else{
                                                    $appr = 0;
                                                }
                                            }
                                        ?>
                                        <td><span class="onlyNumberWithoutSeparator"><?= h($appr) ?></span> <?= h($internalOrdersDetails->product->unit) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <tbody>

                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

       <table >
            <tr>
                <th width="50%">TTD Ka. Unit Kerja / Kasi <br> / Kasubbag / Kabid / Kabag,</th>
                <th width="0%"></th>
                <th width="50%" class="text-right">TTD Pembuat,</th>
            </tr>
        </table>

        <br><br><br><br>
        <table >
            <tr>
                <td width="50%">.....................................</td>
                <th width="71%"></th>
                <td width="50%" class="text-right">............................</td>
            </tr>
        </table>
    </div>
</div>
<div class="modal fade" id="m_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- <form class="item-detail-form"> -->
                
            <?= $this->Form->create($internalOrder,['class'=>'item-detail-form','type'=>'file']) ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        UBAH STATUS MENJADI SELESAI
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">
                            &times;
                        </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="info">Keterangan</label>
                                <textarea name="info" id="info" class="form-control" rows="8"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary btn-add-item">
                        Simpan
                    </button>
                </div>
			<?=$this->Form->end();?>
            <!-- </form> -->
        </div>
    </div>
    </div>
    </div>
</div>

<?php $this->start('script'); ?>
<script>
    $("body").on("click", ".btn-show-modal",function(e){
        e.preventDefault();
        showModal();
    })

    $('#m_modal_4').modal({
        keyboard : false,
        show : false,
        backdrop: 'static',
    }).on("hidden.bs.modal",function(e){
        resetFormDetail();
    })

    // ShowModal
    function showModal() {
        $("#m_modal_4").modal('show');
    }

    // Reset Form Modal
    function resetFormDetail() {
        var keterangan = $("#info");

        keterangan.val("");
    }
    $(".item-detail-form").validate({
            ignore: ":hidden",
            rules: {

            },

            //== Validation messages
            messages: {

            },
            errorPlacement: function (error, element) {
                var elem = $(element);
                if(elem.data('select2') != undefined){
                    var parent = elem.parent('.form-group');
                    var select2 = parent.find(".select2-container");
                    error.insertAfter(select2);
                }else{
                    error.insertAfter(element);
                }
            },
            //== Display error
            invalidHandler: function(event, validator) {
                Utils.showAlertMsg($(".item-detail-form"),'danger','Data belum lengkap.');
            },

            //== Submit valid form
            submitHandler: function (form) {
                var token = $("input[name=_csrfToken]").val();
                console.log(token);
                swal.fire({
                    title: 'Apakah Anda yakin menyelesaikan permintaan pembelian ini ?',
                    text: 'Please make sure if you want to accept this record',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Selesai',
                    cancelButtonText: 'Nanti saja'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '<?=$this->Url->build('/apis/update-status-io/') . $internalOrder->id;?>',
                            type: "patch",
                            dataType: "json",
                            data: {
                                info: $('#info').val(),
                                _csrfToken: token
                            },
                            beforeSend: function (xhr) {
                                // xhr.setRequestHeader('X-CSRF-Token', token);
                                swal.fire('Please Wait', 'Requesting Data', 'info')
                            },
                            success: function (result) {
                                if (result.code == 200) {
                                    swal.fire(
                                        'Success',
                                        result.message,
                                        'success'
                                    )
                                    location . reload();
                                } else if (result.code == 99) {
                                    swal.fire("Ooopp!!!", result.message, "error");
                                } else {
                                    swal.fire("Ooopp!!!", result.message, "error");
                                }
                            },
                            error: function () {
                                swal.fire("Ooopp!!!", "Gagal menyelesaikan permintaan pembeliaan, Silahkan coba lagi", "error");
                            }
                        })
                    } else if (result.dismiss === 'cancel') {

                    }
                })
            }
        });
</script>
<?php $this->end(); ?>