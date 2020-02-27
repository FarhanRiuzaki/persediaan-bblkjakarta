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
            <?= $this->Form->create($expendituresTransfer,['class'=>'kt-form form-primary','type'=>'file']) ?>
            <div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
                        <h3 class="kt-section__title">Pengeluaran Barang Reklarifikasi:</h3>
                        <div class="kt-section__body">
                            <div class="row">
                                <div class="col-md-4">
                                    <?php
                                    $this->Form->setTemplates([
                                        'inputContainer' => '<div class="form-group">{{content}}</div>',
                                    ]);

                                    echo $this->Form->control('date', ['datepicker' => 'true', 'type' => 'text', 'label' => 'Tanggal Pembukaan', 'autocomplete' => 'off', 'class' => 'form-control']);

                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <?=$this->Form->control('institution_id', ['label' => 'Nama Lembaga', 'options' => $institutions, 'empty' => 'Pilih Lembaga']);?>
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
                                                <th width="100px">Saldo</th>
                                                <th width="150px">Jumlah</th>
                                                <th width="200px">Harga</th>
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
                                                    <input type="text" id="saldo" disabled class="saldo form-control onlyNumberWithoutComa">
                                                </td>
                                                <td>
                                                    <input type="text" id="qty" class="qty form-control onlyNumberWithoutComa">
                                                </td>
                                                <td>
                                                    <input type="text" id="price" class="price form-control onlyNumber">
                                                </td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-info btn-add-detail"><i class="fa fa-check"></i></a>
                                                </td>
                                            </tr>
                                            <?php foreach ($expendituresTransfer->expenditures_transfers_details as $key => $expenditures_transfers_detail):?>
                                                <tr>
                                                    <td class="number"><?=$key + 1;?></td>
                                                    <td>[<?=$expenditures_transfers_detail->product->code;?>] <?=$expenditures_transfers_detail->product->name;?></td>
                                                    <td colspan="2" class="onlyNumberWithoutSeparator"><?=$expenditures_transfers_detail->qty;?></td>
                                                    <td class="onlyNumberPrice"><?=$expenditures_transfers_detail->price;?></td>
                                                    <td class="text-center">
                                                        <input type="hidden" data-name="id" value="<?=$expenditures_transfers_detail->id;?>" name="expenditures_transfers_details[<?=$key;?>][id]">
                                                        <input type="hidden" data-name="product_id" value="<?=$expenditures_transfers_detail->product_id;?>" name="expenditures_transfers_details[<?=$key;?>][product_id]">
                                                        <input type="hidden" data-name="price" value="<?=$expenditures_transfers_detail->price;?>" name="expenditures_transfers_details[<?=$key;?>][price]">
                                                        <input type="hidden" data-name="qty" value="<?=$expenditures_transfers_detail->qty;?>" name="expenditures_transfers_details[<?=$key;?>][qty]">
                                                        <a href="#" class="btn btn-danger btn-delete-detail" data-id="<?=$expenditures_transfers_detail->id;?>"><i class="fa fa-times"></i></a>
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
        var arrayProduct = [];
        var oldProduct = [];
        var endDate = '<?=date('Y-m-d', strtotime($expendituresTransfer->date))?>';

        $.each($(".table-input tbody tr:not(.tr-input)"),function(e,item){
            var productId = $(this).find('input[data-name="product_id"]').val();
            var qty = $(this).find("input[data-name='qty']").val();
            if(oldProduct[productId] != undefined ){
                oldProduct[productId] = qty*1 + oldProduct[productId] *1;
            }else{
                oldProduct[productId] = qty;
            }

            if(arrayProduct[productId] != undefined ){
                arrayProduct[productId] = qty*1 + arrayProduct[productId] *1;
            }else{
                arrayProduct[productId] = qty;
            }
        })

        $("#institution-id").select2()
        $('#product-id').select2({
            minimumInputLength : 2,
            ajax: {
                url: '<?=$this->Url->build('/apis/get-products');?>',
                dataType: 'json',
                data : function (params) {
                    var query = {
                        search: params.term,
                        endDate: endDate,
                        type: 'public'
                    }
                    return query;
                }
            }
        }).on("select2:select",function(e){
            var result = e.params.data;
            var saldo = result['saldo'];
            if(oldProduct[result.id] != undefined){
                saldo = saldo *1 + oldProduct[result.id] *1;
            }

            if(arrayProduct[result.id] != undefined){
                saldo = saldo - arrayProduct[result.id];
            }

            $('#saldo').val(saldo);
            $('#price').val(result.price);
        })

        var countDetail = $(".table-input tbody tr:not(.tr-input)").length;

        //add detail//
        $(".btn-add-detail").on("click",function(e){
            e.preventDefault();
            var productData = $("#product-id").select2('data');
            var productId = productData[0].id;
            var productText = productData[0].text;
            var productSaldo = $("#saldo").val();
            var qty = $("#qty").val();
            var price = $("#price").val();
            var valid = true;
            var validMsg = "";
            if(productId == "" || productId == 0){
                valid = false;
                validMsg = "Harap pilih nama barang";
            }else if(qty == "" || qty < 1){
                valid = false;
                validMsg = "Harap masukan qty barang";
            }else if(price == "" || price < 1){
                valid = false;
                validMsg = "Harap masukan price barang";
            } else if(qty > productSaldo*1){
                valid = false;
                validMsg = "Saldo Anda tidak cukup";
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
                            +"<td colspan='2'>"
                            +Utils.labelNumberWithoutSeparator(qty)
                            +"</td>"
                            +"<td>"
                            +Utils.labelNumber(price)
                            +"</td>"
                            +"<td>"
                            +"<input type='hidden' data-name='product_id' value='"+productId+"' name='expenditures_transfers_details["+countDetail+"][product_id]'>"
                            +"<input type='hidden' data-name='price' value='"+price+"' name='expenditures_transfers_details["+countDetail+"][price]'>"
                            +"<input type='hidden' data-name='qty' value='"+qty+"' name='expenditures_transfers_details["+countDetail+"][qty]'>"
                            +"<a href='#' class='btn btn-danger btn-delete-detail'><i class='fa fa-times'></i></a>"
                            +"</td>"
                            +"</tr>";
                $(".table-input tbody").append(html);
                countDetail = no;

                if(arrayProduct[productId] != undefined ){
                    arrayProduct[productId] = qty*1 + arrayProduct[productId] *1;
                }else{
                    arrayProduct[productId] = qty*1;
                }

                $('#product-id').val('');
                $('#product-id').trigger('change');
                $("#price").val('');
                $("#qty").val('');
                $('#saldo').val('');
            }else{
                swal.fire('Gagal menambahkan detail barang',validMsg,'error')
            }

        })
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
            arrayProduct = [];
            $.each($(".table-input tbody tr:not(.tr-input)"),function(e,item){
                //ganti nomor per TR
                var no = e*1 +1;
                $(this).find(".number").html(no);
                //NEANGAN INPUTAN PER TR
                $.each($(this).find("input"),function(s,f){
                    var dataName = $(this).data("name");
                    $(this).attr("name","expenditures_transfers_details["+e+"]["+dataName+"]");
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

                var productSaldo = productData[0].saldo;
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
                code: {
                    required: true
                },
                date: {
                    required: true
                },
                institution_id : {
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
                institution_id: {
                    required: "Harap pilih lembaga"
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
                                swal.fire('Berhasil diubah','Data pengeluaran barang transfer antar lembaga berhasil diubah','success');
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