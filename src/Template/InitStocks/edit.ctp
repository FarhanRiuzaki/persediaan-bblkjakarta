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
            <?= $this->Form->create($initStock,['class'=>'kt-form form-primary','type'=>'file']) ?>
            <div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $this->Form->setTemplates([
                                    'inputContainer' => '<div class="form-group">{{content}}</div>',
                                ]);

                                echo $this->Form->control('date', ['datepicker' => 'true', 'type' => 'text', 'label' => 'Tanggal', 'autocomplete' => 'off', 'class' => 'form-control']);

                                
                                ?>
                            </div>
                            <div class="col-md-12">
                            <table class="table table-condensed table-bordered table-input">
                                <thead>
                                    <tr>
                                        <th width="50px">No.</th>
                                        <th>Nama Barang</th>
                                        <th width="150px">Jumlah</th>
                                        <th width="150px">Satuan</th>
                                        <th width="150px">Harga</th>
                                        <th width="150px">Kedaluwarsa/Exp</th>
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
                                            <input type="text" id="qty" class="qty form-control onlyNumberWithoutComa">
                                        </td>
                                        <td align='center' style='vertical-align: middle'>
                                            <p  id='satuan'></p>
                                        </td>
                                        <td>
                                            <input type="text" id="price" class="price form-control onlyNumber">
                                        </td>
                                        <td>
                                            <input type="text" id="expired" class="form-control datepicker">
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-info btn-add-detail"><i class="fa fa-check"></i></a>
                                        </td>
                                    </tr>

                                    <?php foreach ($initStock->init_stocks_details as $key => $init_stocks_detail):?>
                                        <tr>
                                            <td style='vertical-align: middle' class="number"><?=$key + 1;?></td>
                                            <td style='vertical-align: middle'>
                                                [<?=$init_stocks_detail->product->code;?>] <?=$init_stocks_detail->product->name;?>
                                            </td>
                                            <td > 
                                                <input type="text" data-name="qty" value="<?=$init_stocks_detail->qty;?>" name="init_stocks_details[<?=$key;?>][qty]" class="price form-control onlyNumberWithoutComa">
                                            </td>
                                            <td align='center' style='vertical-align: middle'>
                                                <?=$init_stocks_detail->product->unit;?>
                                            </td>
                                            <td>
                                                <input type="text" data-name="price" value="<?=$init_stocks_detail->price;?>" name="init_stocks_details[<?=$key;?>][price]" class="price form-control onlyNumber">
                                            </td>
                                            <td> 
                                                <input type="text" data-name="expired" value="<?= date('Y-m-d', strtotime($init_stocks_detail->expired));?>" name="init_stocks_details[<?=$key;?>][expired]" class="form-control datepicker">
                                            </td>
                                            <td class="text-center">
                                                <input type="hidden" data-name="id" value="<?=$init_stocks_detail->id;?>" name="init_stocks_details[<?=$key;?>][id]">
                                                <input type="hidden" data-name="product_id" value="<?=$init_stocks_detail->product_id;?>" name="init_stocks_details[<?=$key;?>][product_id]">
                                                <a href="#" class="btn btn-danger btn-delete-detail" data-id="<?=$init_stocks_detail->id;?>"><i class="fa fa-times"></i></a>
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
        $('.datepicker').datepicker({ 
            format: 'yyyy-mm-dd',
            showOtherMonths: true,
            selectOtherMonths: true,
            autoclose: true,
            changeMonth: true,
            changeYear: true,
            orientation: 'bottom',
            todayHighlight: true,
        });
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
            var satuan = result['satuan'];
            if(satuan == null){
                satuan = '-';
            }
            $('p#satuan').text(satuan);
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
                productSatuan = '-';
            }
            var qty = $("#qty").val();
            var price = $("#price").val();
            var expired = $("#expired").val();
            var valid = true;
            var validMsg = "";

            if(productId == "" || productId == 0){
                valid = false;
                validMsg = "Harap pilih nama barang";
            } else if(qty == "" || qty < 1){
                valid = false;
                validMsg = "Harap masukan qty barang";
            }else if(price == "" || price < 1){
                price = 0;
            }

            if(valid){
                var no = countDetail + 1;
                var html =   "<tr>"
                            +"<td class='number' style='vertical-align: middle'>"
                            +no
                            +"</td>"
                            +"<td style='vertical-align: middle'>"
                            +productText
                            +"</td>"
                            +"<td align='center' style='vertical-align: middle'>"
                            +Utils.labelNumberWithoutSeparator(qty)
                            +"</td>"
                            +"<td align='center' style='vertical-align: middle'>"
                            +productSatuan
                            +"</td>"
                            +"<td align='center' style='vertical-align: middle'>"
                            +Utils.labelNumber(price)
                            +"</td>"
                            +"<td align='center' style='vertical-align: middle'>"
                            +expired
                            +"</td>"
                            +"<td class='text-center'>"
                            +"<input type='hidden' data-name='product_id' value='"+productId+"' name='init_stocks_details["+countDetail+"][product_id]'>"
                            +"<input type='hidden' data-name='qty' value='"+qty+"' name='init_stocks_details["+countDetail+"][qty]'>"
                            +"<input type='hidden' data-name='price' value='"+price+"' name='init_stocks_details["+countDetail+"][price]'>"
                            +"<input type='hidden' data-name='expired' value='"+expired+"' name='init_stocks_details["+countDetail+"][expired]'>"
                            +"<a href='#' class='btn btn-danger btn-delete-detail'><i class='fa fa-times'></i></a>"
                            +"</td>"
                            +"</tr>";
                $(".table-input tbody").append(html);
                countDetail = no;

                $('#product-id').val('');
                $('#product-id').trigger('change');
                $("#qty").val('');
                $("#price").val('');
                $("#expired").val('');
            }else{
                swal.fire('Gagal menambahkan detail barang',validMsg,'error')
            }
        })

        //delete detail
        $("body").on("click",".btn-delete-detail",function(e){
            e.preventDefault();
            var dataId = $(this).data("id");
            var tr = $(this).closest("tr");

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
                    $(this).attr("name","init_stocks_details["+e+"]["+dataName+"]");
                })
            })
        })
        var validatorPrimary = $(".form-primary").validate({
            rules: {
                date: {
                    required: true
                }
            },

            //== Validation messages
            messages: {
                date: {
                    required: "Harap masukan tanggal"
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
                                swal.fire('Berhasil disimpan','Data saldo awal berhasil disimpan','success');
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
                }else{
                    swal.fire('Gagal disimpan','Harap lengkapi data detail barang per container','error');
                }
            }
        })

    </script>
<?php $this->end();?>