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
                                <a href="<?= $this->Url->build(['action' => 'view', $purchaseRequest->id, '?' => ['print' => 'pdf']])  ?>" class="kt-nav__link no-print btn-print " target="_BLANK" data-print="pdf">
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
                        <!-- <?php if ($purchaseRequest->status != 2): ?>
                            <span ><a class="btn btn-info m-btn text-white btn-show-modal" href="" id="selesai">UBAH MENJADI SELESAI</a></span>
                        <?php elseif ($purchaseRequest->status == 2 && $purchaseRequest->info != ''): ?>

                        <?php endif; ?> -->
                    </th>
                    <th width="10%">&nbsp;</th>
                    <th class="text-right" colspan="3">
                        <div class="information-bill">
                            <h3>PERMINTAAN PEMBELIAN</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th width="15%">Nama Unit Instalasi</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$purchaseRequest->institute->name;?></th>
                    <th width="18%"></th>
                    <th width="17%">Kode Permintaan Masuk</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$purchaseRequest->code;?></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=(!empty($this->Utilities->indonesiaDateFormat($purchaseRequest->created->format('Y-m-d')))) ? $this->Utilities->indonesiaDateFormat($purchaseRequest->created->format('Y-m-d')) : '-' ;?></th>
                    <th width="18%"></th>
                    <th width="17%">Tanggal</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($purchaseRequest->date->format('Y-m-d'));?></th>
                </tr>

                <tr>
                    <?php if ($purchaseRequest->status == 2 && $purchaseRequest->info != ''): ?>
                        <th width="15%">Keterangan Selesai</th>
                        <th width="2%"> : </th>
                        <th width="18%"><?=$purchaseRequest->info?></th>
                    <?php else: ?>
                        <th width="15%"></th>
                        <th width="2%"></th>
                        <th width="18%"></th>
                    <?php endif; ?>

                    <!-- <th width="18%"></th>
                    <th width="17%">Status</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->statusLabelIo($purchaseRequest->status);?></th> -->
                </tr>

                <tr>
                    <td colspan="15">
                        <table class="table table-bordered table-detail">
                            <thead>
                                <tr>
                                    <th colspan="6" class="text-center" style="background:#fafafa;">DATA BARANG</th>
                                </tr>
                                <tr>
                                    <th width="2%">No</th>
                                    <th>Nama Barang</th>
                                    <th>No. Katalog</th>
                                    <th>Spesifikasi</th>
                                    <th>Merek</th>
                                    <th width="15%">Jumlah</th>
                                </tr>
                            </thead>

                            </tbody>
                                <?php $no = 0; ?>

                                <?php if (!empty($purchaseRequest->purchase_requests_details)) : ?>
                                    <?php foreach ($purchaseRequest->purchase_requests_details as $PurchaseRequestsDetails): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?= h($PurchaseRequestsDetails->product->name) ?></td>
                                        <td><?= h(($PurchaseRequestsDetails->no_catalog == null || $PurchaseRequestsDetails->no_catalog == '') ? '-' : $PurchaseRequestsDetails->no_catalog ) ?></td>
                                        <td><?= h(($PurchaseRequestsDetails->spec == null || $PurchaseRequestsDetails->spec == '') ? '-' : $PurchaseRequestsDetails->spec ) ?></td>
                                        <td><?= h(($PurchaseRequestsDetails->merk == null || $PurchaseRequestsDetails->merk == '') ? '-' : $PurchaseRequestsDetails->merk ) ?></td>
                                        <td><span class="onlyNumberWithoutSeparator"><?= h($PurchaseRequestsDetails->qty) ?></span> <?= h($PurchaseRequestsDetails->product->unit)?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <tbody>

                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php $no = 1; ?>
        <?php if (!empty($purchaseOrders)): ?>
            <?php foreach ($purchaseOrders as $purchaseOrder): ?>
                <div class="information-bill">
                    <h3>PEMBELIAN PESANAN <?= $no++ ?></h3>
                </div>

                <table class="table table-invoice">
                    <tbody>
                        <tr>
                            <th width="15%">Nama Pemasok</th>
                            <th width="2%">:</th>
                            <th width="18%"><?=$purchaseOrder->supplier->name;?></th>
                            <th width="18%"></th>
                        </tr>

                        <tr>
                            <th width="15%">Nomor SPK</th>
                            <th width="2%">:</th>
                            <th width="18%"><?=$purchaseOrder->nomor_spk;?></th>
                            <th width="18%"></th>
                            <th width="17%">Tanggal</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($purchaseOrder->date->format('Y-m-d'));?></th>
                        </tr>
                        <tr>
                            <td colspan="15">
                                <table class="table table-bordered table-detail" width="100%">
                                    <thead>
                                        <tr>
                                            <th colspan="6" class="text-center" style="background:#fafafa;">DATA BARANG</th>
                                        </tr>
                                        <tr>
                                            <th width="2%">No</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah Prs</th>
                                            <th width="15%">Jumlah Po</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    </tbody>
                                        <?php $no_detail = 0; ?>

                                        <?php if (!empty($purchaseOrder->purchase_orders_details)) : ?>
                                            <?php foreach ($purchaseOrder->purchase_orders_details as $PurchaseOrdersDetails): ?>
                                            <tr>
                                                <td><?= h($no_detail += 1) ?></td>
                                                <td><?= h($PurchaseOrdersDetails->product->name) ?></td>
                                            <td><span class="onlyNumberWithoutSeparator"><?= h($PurchaseOrdersDetails->purchase_requests_detail->qty) ?></span> <?= h($PurchaseOrdersDetails->product->unit)?></td>
                                                <td><span class="onlyNumberWithoutSeparator"><?= h($PurchaseOrdersDetails->qty) ?></span> <?= h($PurchaseOrdersDetails->product->unit)?></td>
                                                <td class="onlyNumber"><?= h($PurchaseOrdersDetails->price) ?> </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <tbody>

                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>

        <table >
            <tr>
                <th width="50%">TTD Ka. Instalasi / Kasi / <br> Kasubbag / Kabid / Kabag,</th>
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
                
            <?= $this->Form->create($purchaseRequest,['class'=>'item-detail-form','type'=>'file']) ?>
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


<?php $this->start('script');?>
    <script>

        $("body").on("click", "#print",function(e){
            $('#selesai').hide();
            window.print()
        })

         // show modal
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

        // Submit Item
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
                            url: '<?=$this->Url->build('/apis/update-status-ps/') . $purchaseRequest->id;?>',
                            type: "patch",
                            data: {
                                info: $('#info').val(),
                                _csrfToken: token

                            },
                            dataType: "json",
                            beforeSend: function () {
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

        $("body").on("click", ".btn-update-status-on-table", function (e) {
            e.preventDefault();
            console.log('s');
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
                        url: '<?=$this->Url->build('/apis/update-status-ps/') . $purchaseRequest->id;?>',
                        type: "patch",
                        dataType: "json",
                        beforeSend: function () {
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
        });
    </script>
<?php $this->end();?>