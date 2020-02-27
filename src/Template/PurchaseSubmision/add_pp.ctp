<?php
    $this->start('sub_header_toolbar');
    ?>
    <?php if($this->Acl->check(['action'=>'index']) == true):?>
    <a href="<?=$this->Url->build(['action'=>'index']);?>" class="btn btn-warning">
            <i class="la la-angle-double-left"></i> Kembali
        </a>
        <?php endif;?>
        <?php
    $this->end();
    ?>
<style>
/* BORDER BOTOM th  */
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #BFBFBF !important;
}

/* BORDER th & td */
.table-bordered th, .table-bordered td {
    border: 1px solid #BFBFBF !important;
}

/* BORDER INPUT */
.form-control[readonly], .form-control {
    border-color: #BFBFBF;
    color: #575962;
}

/* BORDER SELECT2 */
.select2-container--default .select2-selection--single {
    border-color: #BFBFBF !important;
}

.table-bordered td.noBorder {
    border: 0px !important;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
						<?=$titlesubModule;?>
					</h3>
				</div>
			</div>
			<!--begin::Form-->
            <?= $this->Form->create($purchaseOrder,['class'=>'kt-form form-primary','type'=>'file']) ?>
            <div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
                        <h3 class="kt-section__title">Daftar Permintaan Pembelian Barang: </h3>
                        <div class="kt-section__body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="overflow" style="max-width:100%;overflow:auto;">
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
                                                <th  style="vertical-align: middle" width="200px">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php  $no = 1;  foreach($productGroup as $key =>$g):?>
                                                <tr style="background-color: #dfe6e9">
                                                    <th><?= $no++ ?></th>
                                                    <th colspan="8"><?= $g->name?></th>
                                                </tr>
                                                <?php 
                                                $total = 0;
                                                $unit = '';
                                                foreach ($data as $key => $r):?>
                                                    <?php if($g->product_id == $r->product->id):?>
                                                        <tr>
                                                            <td class="noBorder"></td>
                                                            <td><?=$r->purchase_request->institute->name;?></td>
                                                            <td><?=$r->purchase_request->code;?></td>
                                                            <td>[<?=$r->product->code;?>] <?=$r->product->name;?></td>
                                                            <td><?=$r->no_catalog;?></td>
                                                            <td><?=$r->spec;?></td>
                                                            <td><?=$r->merk;?></td>
                                                            <td class="text-right"><?=$this->Number->format($r->qty, ['precision' => 2]);?></td>
                                                            <!-- <td><?=$r->product->unit ;?></td> -->
                                                            <td class="text-right">


                                                                <input id="qty_<?=$key?>" type="text" data-type='number' class="onlyNumberWithoutSeparator form-control m-input item_qty" value="<?=$r->qty?>" name="purchase_submisions_details[<?=$key;?>][qty]" data-name='item_qty<?= $g->product_id?>' min='0'>


                                                                <input type="hidden" class="form-control m-input" value="<?=$r->purchase_request_id;?>" name="purchase_submisions_details[<?=$key;?>][purchase_request_id]">
                                                                <input type="hidden" class="form-control m-input" value="<?=$r->purchase_request->institute->id;?>" name="institute_id">
                                                                <input type="hidden" class="form-control m-input" value="<?=$r->id;?>" name="purchase_submisions_details[<?=$key;?>][purchase_requests_detail_id]">
                                                                <input type="hidden" class="form-control m-input" value="<?=$r->product->id;?>" name="purchase_submisions_details[<?=$key;?>][product_id]">
                                                            </td>
                                                        </tr>
                                                    <?php 
                                                    $total += $r->qty;
                                                    $unit = $r->product->unit;
                                                    endif; ?>
                                                <?php endforeach;?>
                                                <tr >
                                                    <td colspan="8" class="text-right">Total:</td>
                                                    <td><p><b  id='total<?= $g->product_id?>'> <?= $total ?> </b> <?= $unit?></p></td>
                                                </tr>
                                            <?php endforeach;?>
                                            <?php if($no == 1):?>
                                                <tr>
                                                    <td colspan="6">Belum tersedia permintaan</td>
                                                </tr>
                                            <?php endif;?>
                                        </tbody>
                                    </table>
                                   
                                    </div>
                                </div>
                            </div>
                        </div>

		            </div>
	            </div>
	            <div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-lg-12">
                            <input type="submit" class="btn btn-primary btn-submit" value="Submit">

                            <button type="reset" class="btn btn-secondary">
                                Cancel
                            </button>
							</div>
						</div>
					</div>
				</div>
			<?=$this->Form->end();?>
			<!--end::Form-->
		</div>
    </div>
</div>


<?php $this->start('script');?>
    <script>

        $('input[data-type="number"]').on('keyup', function (e) {
            if (e.target.value == '') {
            e.target.value = 0
            }
        })

        //jumlah input
        var group   = '<?= json_encode($productGroup)?>';
        var obj      = JSON.parse(group);

        $('body').on('keyup', '.item_qty', function(e){
            e.preventDefault();
            
            $.each(obj, function(k,v) {
                var test_qty = 0
                $("input[data-name^='item_qty"+v.product_id+"']").each(function() { 
                    test_qty +=parseInt($(this).val(), 10)  
                })
                $('b#total'+v.product_id).text(test_qty);
                console.log(test_qty);
            });
        })                                                
        console.log(obj);
                                                    
        var countDetail = 1;

        var validatorPrimary = $(".form-primary").validate({
            rules: {
                // date: {
                //     required: true
                // },
                //  supplier_id : {
                //     required : true
                // },
                //  nomor_po : {
                //     required : true
                // },
                //  nomor_spk : {
                //     required : true
                // }
            },

            //== Validation messages
            messages: {
                // date: {
                //     required: "Harap masukan tanggal"
                // },
                // supplier_id: {
                //     required: "Harap pilih pemasok"
                // },
                // nomor_po:{
                //     required: "Harap masukan nomor po"
                // },
                // nomor_spk: {
                //     required: "Harap masukan nomor spk"
                // }

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
                //Utils.showAlertMsg($(".form-primary"),'danger','Data belum lengkap.');
                // swal.fire('Gagal disimpan','Harap lengkapi data terlebih dahulu','error')
                alert('Harap lengkapi data terlebih dahulu');
            },

            //== Submit valid form
            submitHandler: function (form) {
                var validateDetail = true;
                var tableDetail = $(".table-input");
                if(countDetail == 0){
                    validateDetail = false;
                }
                if(validateDetail){
                    $(form).ajaxSubmit({
                        dataType : 'json',
                        type : 'post',
                        beforeSubmit : function(){
                            $(".btn-submit").addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
                            $(form).find("input,select").attr('disabled', true);
                        },
                        success : function(result){
                            if(result.code == 200){
                                swal.fire('Berhasil disimpan','Data pembelian pesanan berhasil disimpan','success');
                                setTimeout(function(e){
                                   document.location.href='<?=$this->Url->build(['action' => 'index']);?>'
                                },2000)
                            }else{
                                swal.fire('Gagal disimpan','terjadi kesalahan','error');
                                $(".btn-submit").removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                                $(form).find("input,select").attr('disabled', false);
                            }
                        },
                        error : function(){
                            swal.fire('Gagal disimpan','terjadi kesalahan','error');
                            $(".btn-submit").removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                            $(form).find("input,select").attr('disabled', false);
                        }
                    });
                    return false;
                }else
                {
                    swal.fire('Gagal disimpan','Harap lengkapi data detail barang per container','error');
                }
            }
        })

    </script>
<?php $this->end();?>