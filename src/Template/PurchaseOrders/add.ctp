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
            <?= $this->Form->create($purchaseOrder,['class'=>'kt-form form-primary','type'=>'file']) ?>
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
                                <div class="col-md-4">
                                    <?=$this->Form->control('supplier_id', ['label' => 'Nama Pemasok', 'options' => $suppliers, 'empty' => 'Pilih Pemasok','style'=> 'width: 100%']);?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <?=$this->Form->control('nomor_spk', ['class'=>'form-control','type' => 'text', 'label' => 'Nomor SPK']);?>
                                </div>

                                <div class="col-md-4">
                                    <?=$this->Form->control('date_freeze', ['datepicker' => 'true', 'type' => 'text', 'label' => 'Tanggal Pembukuan', 'value' => $purchaseOrder['date_freeze'], 'class' => 'form-control', 'autocomplete' => 'off']);?>
                                </div>
                            </div>
                        </div>
                        <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                        <h3 class="kt-section__title">Daftar Permintaan Pembelian Barang:</h3>
                        <div class="kt-section__body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="overflow" style="max-width:100%;overflow:auto;">
                                    <table class="table table-condensed table-bordered table-input">
                                        <thead>
                                            <tr>
                                                <th width="50px">No.</th>
                                                <th width="350px">Asal</th>
                                                <th width="350px">No. PR</th>
                                                <th width="350px">Nama Barang</th>
                                                <th width="100px">Jumlah PR</th>
                                                <th width="100px">Jumlah telah disetujui</th>
                                                <th width="200px">Jumlah</th>
                                                <th width="200px">Harga</th>
                                                <th width="200px">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($purchaseRequestsDetails)):?>
                                                <?php foreach ($purchaseRequestsDetails as $key => $r):?>
                                                <tr>
                                                    <td><?=$key + 1;?></td>
                                                    <td><?=$r->purchase_request->institute->name;?></td>
                                                    <td><?=$r->purchase_request->code;?></td>
                                                    <td>[<?=$r->product->code;?>] <?=$r->product->name;?></td>
                                                    <td class="text-right"><?=$this->Number->format($r->qty, ['precision' => 2]);?></td>
                                                    <td class="text-right"><?=$this->Number->format($r->saldo_po, ['precision' => 2]);?></td>
                                                    <td class="text-right">
                                                        <input id="qty_<?=$key?>" type="text" class="onlyNumber form-control m-input" value="0" name="purchase_orders_details[<?=$key;?>][qty]">
                                                    </td>
                                                    <td class="text-right">
                                                        <input id="price_<?=$key?>" type="text" class="onlyNumber form-control m-input" value="<?=$r->price;?>" name="purchase_orders_details[<?=$key;?>][price]">
                                                        <input type="hidden" class="form-control m-input" value="<?=$r->purchase_request_id;?>" name="purchase_orders_details[<?=$key;?>][purchase_request_id]">
                                                        <input type="hidden" class="form-control m-input" value="<?=$r->id;?>" name="purchase_orders_details[<?=$key;?>][purchase_requests_detail_id]">
                                                        <input type="hidden" class="form-control m-input" value="<?=$r->product->id;?>" name="purchase_orders_details[<?=$key;?>][product_id]">
                                                    </td>
                                                    <td id="total_<?=$key?>"><?=$this->Number->format($r->price, ['precision' => 2]);?></td>
                                                </tr>
                                                <?php endforeach;?>
                                            <?php else:?>
                                                <tr>
                                                    <td colspan="6">Belum tersedia pemesanan</td>
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

        <?php if (!empty($purchaseRequestsDetails)):?>
            <?php foreach ($purchaseRequestsDetails as $key => $r):?>

                $("#qty_<?=$key?>").keyup(function() {
                    var qty = $("#qty_<?=$key?>").val();
                    var price = $("#price_<?=$key?>").val();
                    var total = 0;

                    total = (qty*1) * (price*1);
                    $("#total_<?=$key?>").html(total);
                });

                $("#price_<?=$key?>").keyup(function() {
                    var qty = $("#qty_<?=$key?>").val();
                    var price = $("#price_<?=$key?>").val();
                    var total = 0;

                    total = (qty*1) * (price*1);
                    $("#total_<?=$key?>").html(total);
                });

            <?php endforeach;?>
        <?php endif?>

        $("#supplier-id").select2();
        $('#pr-id').select2({
            minimumInputLength : 2,
            ajax: {
                url: '<?=$this->Url->build('/apis/get-prs');?>',
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

        var countDetail = 1;

        $(".btn-add-product").on("click", function(e) {
            e.preventDefault();
            var prData = $("#pr-id").select2('data');
            var productId = prData[0].id;

            $.ajax ({
                url: '<?=$this->url->build('/apis/get-prsdetails');?>',
                dataType: 'json',
                data: {
                    productId: productId
                },
                success: function(rsp){
                    var data = rsp.results;
                    $.each(data, function(key, val) {
                        var no = countDetail + 1;
                        var html = "<tr>"
                            +"<td class='number'>"
                            +no
                            +"</td>"
                            +"<td>"
                            +val.code
                            +"</td>"
                            +"<td>"
                            +val.text
                            +"</td>"
                            +"<td>"
                            +Utils.labelNumberWithoutSeparator(val.qty)
                            +"</td>"
                            +"<td>"
                            +"<input type='text' id='qty' data-name='qty' name='purchase_orders_details["+countDetail+"][qty]'  class='qty form-control onlyNumberWithoutSeparator'>"
                            +"</td>"
                            +"<td>"
                            +"<input type='text' data-name='price' id='price' name='purchase_orders_details["+countDetail+"][price]' class='price form-control onlyNumber'>"
                            +"</td>"
                            +"<td class='text-center'>"
                            +"<input type='hidden' data-name='purchase_requests_detail_id' value='"+val.id+"' name='purchase_orders_details["+countDetail+"][purchase_requests_detail_id]'>"
                            +"<input type='hidden' data-name='product_id' value='"+val.product_id+"' name='purchase_orders_details["+countDetail+"][product_id]'>"
                            +"<a href='#' class='btn btn-danger btn-delete-detail'><i class='fa fa-times'></i></a>"
                            +"</td>"
                            +"</tr>";

                            $(".table-input tbody").append(html);

                            Utils.initNumber();
                            countDetail = no;
                            $('#pr-id').val('');
                            $('#pr-id').trigger('change');
                    })

                }

            })

        })

         $("body").on("click",".btn-delete-detail",function(e){
            e.preventDefault();
            var tr = $(this).closest("tr");
            tr.remove();
            countDetail = countDetail - 1;
            $.each($(".table-input tbody tr:not(.tr-input)"),function(e,item){
                //ganti nomor per TR
                var no = e*1 +1;
                $(this).find(".number").html(no);
                //NEANGAN INPUTAN PER TR
                $.each($(this).find("input"),function(s,f){
                    //neangan nu atributna data-name=
                    var dataName = $(this).data("name");
                    //replace attribute <input name=ieu nu diganti sesuai dengan attribute dataname///
                    $(this).attr("name","purchase_orders_details["+e+"]["+dataName+"]");
                })

            })
        })

        var validatorPrimary = $(".form-primary").validate({
            rules: {
                date: {
                    required: true
                },
                 supplier_id : {
                    required : true
                },
                 nomor_po : {
                    required : true
                },
                 nomor_spk : {
                    required : true
                }
            },

            //== Validation messages
            messages: {
                date: {
                    required: "Harap masukan tanggal"
                },
                supplier_id: {
                    required: "Harap pilih pemasok"
                },
                nomor_po:{
                    required: "Harap masukan nomor po"
                },
                nomor_spk: {
                    required: "Harap masukan nomor spk"
                }

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