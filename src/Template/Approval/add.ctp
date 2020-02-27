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
            <?= $this->Form->create($expendituresDistribution,['class'=>'kt-form form-primary','type'=>'file']) ?>
            <div class="kt-portlet__body">
					<div class="kt-section kt-section--first">                        
                        <h3 class="kt-section__title">Detail Barang:</h3>
                        <div class="kt-section__body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-condensed table-bordered table-input">
                                        <thead>
                                            <tr>
                                                <th width="50px">No.</th>
                                                <th width="300px">Nama Barang</th>
                                                <th width="100px">Stok</th>
                                                <th width="100px">Satuan</th>
                                                <th width="150px">Jumlah Permintaan</th>
                                                <th width="150px">Jumlah</th>
                                                <th width="150px">Keterangan</th>
                                                <th width="50px">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($results as $key => $r):?>
                                        <tr>
                                            <td class="number"><?=$key + 1;?></td>
                                            <td><?=$r['name'];?></td>
                                            <td><?=$this->Number->format($r['saldo'], ['precision' => 2]);?></td>
                                            <td class="text-center"><?=$r['unit'];?></td>
                                            <td class="text-right"><?=$r['qty'];?></td>
                                            <td>
                                                <input type="text" class="form-control onlyNumberWithoutSeparator" data-name="qty" value="<?=$r['sisa'];?>" name="internal_orders_details[<?=$key;?>][qty]">
                                                <input type="hidden" class="form-control onlyNumberWithoutSeparator" data-name="qty" value="<?=$r['sisa'];?>" name="internal_orders_details[<?=$key;?>][qty_request]">
                                                
                                                <input type="hidden" data-name="id" value="<?=$r['internal_order_detail_id'];?>" name="internal_orders_details[<?=$key;?>][id]">
                                                <input type="hidden" data-name="product_id" value="<?=$r['id'];?>" name="internal_orders_details[<?=$key;?>][product_id]">
                                                <input type="hidden" data-name="internal_order_id" value="<?=$r['internal_order_id'];?>" name="internal_orders_details[<?=$key;?>][internal_order_id]">
                                            </td>
                                            <td><?= $r['note']?></td>
                                            <td><a href="#" class="btn btn-danger btn-delete-detail" data-id="<?=$r['internal_order_id'];?>"><i class="fa fa-times"></i></a></td>
                                        </tr>
                                        <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

		            </div>
	            </div>
	            <div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-lg-12">
                            <input type="submit" class="btn btn-primary btn-submit" value="Approve">

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
        var arrayProduct = [];
        var oldProduct = [];
        var countDetail = $(".table-input tbody tr:not(.tr-input)").length;
        //delete detail
        $("body").on("click",".btn-delete-detail",function(e){
            e.preventDefault();
            var dataId = $(this).data("id");
            var tr = $(this).closest("tr");
            var productOldId = tr.find('input[data-name="product_id"]').val();

            if(dataId != undefined){
                var deleteDetailText = $("#delete_detail").val();
                if(deleteDetailText == ""){
                    var newValueDeleteDetail = dataId;
                }else{
                    var newValueDeleteDetail = deleteDetailText + "," + dataId;
                }

                $("#delete_detail").val(newValueDeleteDetail);
            }
            tr.remove();

            countDetail = countDetail - 1;
            $.each($(".table-input tbody tr:not(.tr-input)"),function(e,item){
                //ganti nomor per TR
                var no = e*1 +1;
                $(this).find(".number").html(no);
                //NEANGAN INPUTAN PER TR
                $.each($(this).find("input"),function(s,f){
                    var dataName = $(this).data("name");
                    $(this).attr("name","internal_orders_details["+e+"]["+dataName+"]");
                })

                var productId = $(this).find('input[data-name="product_id"]').val();
                var qty = $(this).find("input[data-name='qty']").val();
                if(arrayProduct[productId] != undefined ){
                    arrayProduct[productId] = qty*1 + arrayProduct[productId] *1;
                }else{
                    arrayProduct[productId] = qty;
                }
            })

            if($("#product-id").val() == productOldId){
                var productData = $("#product-id").select2('data');

                var productSaldo = productData[<?=$key;?>].saldo;
                if(arrayProduct[productOldId] == undefined){
                    arrayProduct[productOldId] = 0;
                }
                if(oldProduct[productOldId] != undefined){
                    $("#saldo").val((productSaldo *1 + oldProduct[productOldId] *1) - arrayProduct[productOldId]);
                }else{
                    $("#saldo").val(productSaldo - arrayProduct[productOldId]);
                }
            }

        })
        var validatorPrimary = $(".form-primary").validate({
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
                //Utils.showAlertMsg($(".form-primary"),'danger','Data belum lengkap.');
                swal.fire('Gagal disimpan','Harap lengkapi data terlebih dahulu','error')
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
                                swal.fire('Berhasil diapprove','Data berhasil diapprove','success');
                                setTimeout(function(e){
                                    document.location.href='<?=$this->Url->build(['controller' => 'Dashboard','action' => 'index']);?>'
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
                }else{
                    swal.fire('Gagal disimpan','Harap refresh page','error');
                }
            }
        })

    </script>
<?php $this->end();?>