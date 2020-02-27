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
            <?= $this->Form->create($internalOrder,['class'=>'kt-form form-primary','type'=>'file']) ?>
            <div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
                        <div class="row">
                            <div class="col-md-4">
                                <?php
                                $this->Form->setTemplates([
                                    'inputContainer' => '<div class="form-group">{{content}}</div>',
                                ]);

                                echo $this->Form->control('date', ['datepicker' => 'true', 'type' => 'text', 'label' => 'Tanggal', 'autocomplete' => 'off', 'class' => 'form-control', 'style'=>'width: 100%']);

                                ?>
                            </div>
                            <div class="col-md-3">
                                <?php                                 
                                echo $this->Form->control('type', ['label' => 'Tipe', 'autocomplete' => 'off', 'class' => 'form-control', 
                                'empty' => 'Pilih Tipe',
                                'options' => [
                                    '1' => 'ATK',
                                    '2' => 'Media dan Reagensia'
                                ],
                                'required'
                                ]);
                                ?>
                            </div>
                            <div class="col-md-4" id="institute-hidden">
                            <?=$this->Form->control('institute_id', ['label' => 'Nama Unit Unit Kerja', 'options' => $institutes, 'empty' => 'Pilih Unit Kerja']);?>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-condensed table-bordered table-input">
                                    <thead>
                                        <tr>
                                            <th width="50px">No.</th>
                                            <th>Nama Barang</th>
                                            <th width="100px">Stok</th>
                                            <th width="150px">Jumlah diminta</th>
                                            <th width="150px">Satuan</th>
                                            <th width="50px" class="text-center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody class="hide">
                                        <tr class="tr-input">
                                            <td colspan="2">
                                                <select class="product_id" id="product-id" style="width:100%">
                                                    <option value="">Pilih Barang</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" id="saldo" disabled class="saldo form-control onlyNumberWithoutSeparator">
                                            </td>
                                            <td>
                                                <input type="text" id="qty" class="qty form-control onlyNumberWithoutSeparator">
                                            </td>
                                            <td>
                                                <p id="satuan">-</p>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-info btn-add-detail"><i class="fa fa-check"></i></a>
                                            </td>
                                        </tr>
                                        <?php foreach ($internalOrder->internal_orders_details as $key => $internal_orders_detail):?>
                                            <tr>
                                                <td class="number"><?=$key + 1;?></td>
                                                <td colspan="2">[<?=$internal_orders_detail->product->code;?>] <?=$internal_orders_detail->product->name;?></td>
                                                
                                                <td>
                                                    <input type="text" data-name="qty" data-id="<?= $internal_orders_detail->product_id?>" value="<?=$internal_orders_detail->qty;?>" name="internal_orders_details[<?=$key;?>][qty]" class="price form-control getStok onlyNumberWithoutComa">
                                                </td>
                                                <td>
                                                    <?=$internal_orders_detail->product->unit == null ? '-' : $internal_orders_detail->product->unit?>
                                                </td>

                                                <td class="text-center">
                                                    <input type="hidden" data-name="id" value="<?=$internal_orders_detail->id;?>" name="internal_orders_details[<?=$key;?>][id]">
                                                    <input type="hidden" data-name="product_id" value="<?=$internal_orders_detail->product_id;?>" name="internal_orders_details[<?=$key;?>][product_id]">
                                                    <a href="#" class="btn btn-danger btn-delete-detail" data-id="<?=$internal_orders_detail->id;?>"><i class="fa fa-times"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
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
        // $('.hide').hide();
        var arrayProduct = [];
        var oldProduct = [];
        var endDate = '<?=date('Y-m-d', strtotime($internalOrder->date))?>';

        var hidden = <?=$userData->institute_id?>;
        if (hidden != 0) {
            $("#institute-hidden").hide();
        }

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

        $("#institute-id").select2()
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
            var satuan = result['satuan'];
            if(oldProduct[result.id] != undefined){
                saldo = saldo *1 + oldProduct[result.id] *1;
            }

            if(arrayProduct[result.id] != undefined){
                saldo = saldo - arrayProduct[result.id];
            }

            if(satuan == null){
                satuan = '-'
            }
            console.log(satuan);
            $('#saldo').val(saldo);
            $('p#satuan').html(satuan);
        })

        var countDetail = $(".table-input tbody tr:not(.tr-input)").length;

        //add detail//
        $(".btn-add-detail").on("click",function(e){
            e.preventDefault();
            var productData = $("#product-id").select2('data');
            var productId = productData[0].id;
            var productText = productData[0].text;
            var productSatuan = productData[0].satuan;
            if(productSatuan == null){
                productSatuan = '-'
            }
            var productSaldo = $("#saldo").val();
            var qty = $("#qty").val();
            var valid = true;
            var validMsg = "";
            if(productId == "" || productId == 0){
                valid = false;
                validMsg = "Harap pilih nama barang";
            } 
            // else if(productSaldo == "" || productSaldo < 1){
            //         valid = false;
            //         validMsg = "Saldo tidak cukup";
            // }
             else if(qty == "" || qty < 1){
                valid = false;
                validMsg = "Harap masukan qty barang";
            }

            if(valid){
                var no = countDetail + 1;
                var html =   "<tr>"
                            +"<td class='number'>"
                            +no
                            +"</td>"
                            +"<td colspan='2'>"
                            +productText
                            +"</td>"
                            +"<td>"
                            +Utils.labelNumberWithoutSeparator(qty)
                            +"</td>"
                            +"<td>"
                            +productSatuan
                            +"</td>"
                            +"<td>"
                            +"<input type='hidden' data-name='product_id' value='"+productId+"' name='internal_orders_details["+countDetail+"][product_id]'>"
                            +"<input type='hidden' data-name='qty' value='"+qty+"' name='internal_orders_details["+countDetail+"][qty]'>"
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
                $("#qty").val('');
                $('#saldo').val('');
                $('p#satuan').html('-');
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
                    required: "Harap pilih Unit Kerja"
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
                                swal.fire('Berhasil diubah','Data permintaan user berhasil diubah','success');
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

        $('.getStok').on('keyup', function(){
            var val    = $(this).val(),
                idData  = $(this).data('id');

            // console.log('====================================');
            // console.log(val);
            // console.log(idData);
            // console.log('====================================');

            $.ajax({
            type: 'get',
            dataType: 'json',
            data: {id: idData},
            url : "<?=$this->Url->build(['action'=>'cekStok','controller'=>'Apis']);?>",
            success : function(rsp){
                var e = rsp.source[0];
                // console.log('====================================');
                // console.log(e.qty);
                // console.log(val);
                // console.log('====================================');
                var cek = parseInt(val),
                    qty = parseInt(e.qty);

                if(qty <= cek){
                    Swal.fire(
                    'Saldo Kurang',
                    'Saldo ' + e.product_name + ' : <b>' + e.qty + '</b>',
                    'info'
                    );
                }
                }
            });

        });
    </script>
<?php $this->end();?>