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
            <?= $this->Form->create($purchaseRequest,['class'=>'kt-form form-primary','type'=>'file']) ?>
            <div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
                        <h3 class="kt-section__title">Permintaan Pembelian:</h3>
                        <div class="kt-section__body">
                            <div class="row">
                                <div class="col-md-4">
                                    <?php
                                    $this->Form->setTemplates([
                                        'inputContainer' => '<div class="form-group">{{content}}</div>',
                                    ]);

                                    echo $this->Form->control('date', ['datepicker' => 'true', 'type' => 'text', 'label' => 'Tanggal', 'autocomplete' => 'off', 'class' => 'form-control']);

                                    ?>
                                </div>
                                <div class="col-md-4" id="institute-hidden">
                                    <?= $this->Form->control('institute_id', ['label' => 'Nama Unit Instalasi', 'options' => $institutes, 'empty' => 'Pilih instalasi', 'value' => $userData->institute_id]);?>
                                </div>
                            </div>
                        </div>
                        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                        <h3 class="kt-section__title">Detail Barang:</h3>
                        <div class="kt-section__body">
                            <div class="row">
                                <div class="col-md-12">
                                <table class="table table-condensed table-bordered table-input">
                                <thead>
                                    <tr>
                                        <th width="50px">No.</th>
                                        <th>Nama Barang</th>
                                        <th>No. Katalog</th>
                                        <th width="250px">Spesifikasi</th>
                                        <th width="250px">Merk</th>
                                        <th width="150px">Jumlah</th>
                                        <th width="50px" class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="tr-input">
                                        <td colspan="2">
                                            <select class="product_id" id="product-id" style="width:100%">
                                                <option value="">Pilih Barang</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" id="no_catalog" class="no_catalog form-control">
                                        </td>
                                        <td>
                                            <input type="text" id="spec" class="spec form-control">
                                        </td>
                                        <td>
                                            <input type="text" id="merk" class="merk form-control">
                                        </td>
                                        <td>
                                            <input type="text" id="qty" class="qty form-control onlyNumberWithoutSeparator">
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-info btn-add-detail"><i class="fa fa-check"></i></a>
                                        </td>
                                    </tr>
                                    <?php foreach ($purchaseRequest->purchase_requests_details as $key => $purchase_requests_detail):?>
                                        <tr>
                                            <td class="number"><?=$key + 1;?></td>
                                            <td>[<?=$purchase_requests_detail->product->code;?>] <?=$purchase_requests_detail->product->name;?></td>
                                            <td><input type="text" class="form-control" data-name="no_catalog" value="<?=$purchase_requests_detail->no_catalog;?>" name="purchase_requests_details[<?=$key;?>][no_catalog]"></td>
                                            <td><input type="text" class="form-control" data-name="spec" value="<?=$purchase_requests_detail->spec;?>" name="purchase_requests_details[<?=$key;?>][spec]"></td>
                                            <td><input type="text" class="form-control" data-name="merk" value="<?=$purchase_requests_detail->merk;?>" name="purchase_requests_details[<?=$key;?>][merk]"></td>
                                            <td class="onlyNumberWithoutComa"><?=$purchase_requests_detail->qty;?></td>
                                            <td class="text-center">
                                                <input type="hidden" data-name="id" value="<?=$purchase_requests_detail->id;?>" name="purchase_requests_details[<?=$key;?>][id]">
                                                <input type="hidden" data-name="product_id" value="<?=$purchase_requests_detail->product_id;?>" name="purchase_requests_details[<?=$key;?>][product_id]">
                                                <input type="hidden" data-name="qty" value="<?=$purchase_requests_detail->qty;?>" name="purchase_requests_details[<?=$key;?>][qty]">
                                                <a href="#" class="btn btn-danger btn-delete-detail" data-id="<?=$purchase_requests_detail->id;?>"><i class="fa fa-times"></i></a>
                                            </td>
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
        var hidden = <?=$userData->institute_id?>;
        if (hidden != 0) {
            $("#institute-hidden").addClass("hidden-form");
        }

        $("#institute-id").select2()
        $('#product-id').select2({
            minimumInputLength : 2,
            ajax: {
                url: '<?=$this->Url->build('/apis/get-products');?>',
                dataType: 'json',
                data : function (params) {
                    var query = {
                        search: params.term,
                        type: 'public'
                    }
                    return query;
                }
            }
        }).on("select2:select",function(e){
            var result = e.params.data;
        })

        var countDetail = $(".table-input tbody tr:not(.tr-input)").length;

        //add detail//
        $(".btn-add-detail").on("click",function(e){
            e.preventDefault();
            var productData = $("#product-id").select2('data');
            var productId = productData[0].id;
            var productText = productData[0].text;
            var qty = $("#qty").val();
            var spec = $("#spec").val();
            var merk = $("#merk").val();
            var no_catalog  = $("#no_catalog").val();
            var valid = true;
            var validMsg = "";
            if(productId == "" || productId == 0){
                valid = false;
                validMsg = "Harap pilih nama barang";
            }else if(qty == "" || qty < 1){
                valid = false;
                validMsg = "Harap masukan qty barang";
            }

            if(valid){
                var no = countDetail + 1;
                var html =   "<tr>"
                            +"<td class='number'>"
                            +no
                            +"</td>"
                            +"<td>"
                            +productText
                            +"</td>"
                            +"<td>"
                            +"<input type='text' class='form-control' data-name='no_catalog' value='"+no_catalog+"' name='purchase_requests_details["+countDetail+"][no_catalog]'>"
                            +"</td>"
                            +"<td>"
                            +"<input type='text' class='form-control' data-name='spec' value='"+spec+"' name='purchase_requests_details["+countDetail+"][spec]'>"
                            +"</td>"
                            +"<td>"
                            +"<input type='text' class='form-control' data-name='merk' value='"+merk+"' name='purchase_requests_details["+countDetail+"][merk]'>"
                            +"</td>"
                            +"<td>"
                            +Utils.labelNumberWithoutSeparator(qty)
                            +"</td>"
                            +"<td>"
                            +"<input type='hidden' data-name='product_id' value='"+productId+"' name='purchase_requests_details["+countDetail+"][product_id]'>"
                            +"<input type='hidden' data-name='qty' value='"+qty+"' name='purchase_requests_details["+countDetail+"][qty]'>"
                            +"<a href='#' class='btn btn-danger btn-delete-detail'><i class='fa fa-times'></i></a>"
                            +"</td>"
                            +"</tr>";
                $(".table-input tbody").append(html);
                countDetail = no;
                $('#product-id').val('');
                $('#product-id').trigger('change');
                $("#qty").val('');
                $("#spec").val('');
                $("#merk").val('');
                $("#no_catalog").val('');
            }else{
                swal.fire('Gagal menambahkan detail barang',validMsg,'error')
            }

        })
        //delete detail
        $("body").on("click",".btn-delete-detail",function(e){
            e.preventDefault();
            var dataId = $(this).data("id");
            if(dataId != undefined){
                var deleteDetailText = $("#delete_detail").val();
                if(deleteDetailText == ""){
                    var newValueDeleteDetail = dataId;
                }else{
                    var newValueDeleteDetail = deleteDetailText + "," + dataId;
                }

                $("#delete_detail").val(newValueDeleteDetail);
            }
            var tr = $(this).closest("tr");
            tr.remove();

            countDetail = countDetail - 1;
            $.each($(".table-input tbody tr:not(.tr-input)"),function(e,item){
                //ganti nomor per TR
                var no = e*1 +1;
                $(this).find(".number").html(no);
                //NEANGAN INPUTAN PER TR
                $.each($(this).find("input"),function(s,f){
                    var dataName = $(this).data("name");
                    $(this).attr("name","purchase_requests_details["+e+"]["+dataName+"]");
                })
            })

        })

        var validatorPrimary = $(".form-primary").validate({
            rules: {
                code: {
                    required: true
                },
                date: {
                    required: true
                },
                institute_id : {
                    required : true
                }
            },

            //== Validation messages
            messages: {
                code: {
                    required: "Harap masukan kode permintaan user"
                },
                date: {
                    required: "Harap masukan tanggal"
                },
                institute_id: {
                    required: "Harap pilih instalasi"
                },
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
                swal.fire('Gagal diubah','Harap lengkapi data terlebih dahulu','error')
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
                                swal.fire('Berhasil diubah','Data permintaan pembelian berhasil diubah','success');
                                setTimeout(function(e){
                                    document.location.href='<?=$this->Url->build(['action' => 'index']);?>'
                                },2000)
                            }else{
                                swal.fire('Gagal diubah','terjadi kesalahan','error');
                                $(".btn-submit").removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                                $(form).find("input,select").attr('disabled', false);
                            }
                        },
                        error : function(){
                            swal.fire('Gagal diubah','terjadi kesalahan','error');
                            $(".btn-submit").removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                            $(form).find("input,select").attr('disabled', false);
                        }
                    });
                    return false;
                }else{
                    swal.fire('Gagal diubah','Harap lengkapi data detail barang per container','error');
                }
            }
        })

    </script>
<?php $this->end();?>