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
            <?= $this->Form->create($receiptPurchase,['class'=>'kt-form form-primary','type'=>'file']) ?>
            <div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
                        <h3 class="kt-section__title">Penerimaan Barang Transfer Antar Lembaga:</h3>
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
                                    <?=$this->Form->control('purchase_order_id', ['label' => 'Nomor SPK', 'options' => $purchaseOrders, 'empty' => 'Pilih Nomor SPK','style' => 'width:100%']);?>
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
                                                <th width="350px">Nama Barang</th>
                                                <th width="100">Jumlah SPK</th>
                                                <th width="200">Jumlah</th>
                                                <th width="150">Harga</th>
                                                <th width="50px" class="text-center">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>

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
        var countDetail = 0;

        $("#purchase-order-id").select2({
        }).on("select2:select",function(e){
            var result = e.params.data;

            console.log(result);

            countDetail = 0;
            $(".table-input tbody").html("");

            if(result.id != 0 && result.id != ""){
                $.ajax({
                    url: '<?=$this->Url->build('/apis/get-purchase-order-detail');?>/'+ result.text,
                    dataType: 'json',
                    success: function(result){
                        var data = result.results;

                        $.each(data, function(key, val) {
                            var no = countDetail + 1;
                            var html = "<tr>"
                                +"<td class='number'>"
                                +no
                                +"</td>"
                                +"<td>"
                                +val.product_name
                                +"</td>"
                                +"<td>"
                                +Utils.labelNumberWithoutSeparator(val.qty)
                                +"</td>"
                                +"<td>"
                                +"<input type='text' id='qty' data-name='qty' name='receipt_purchases_details["+countDetail+"][qty]'  class='qty form-control onlyNumberWithoutSeparator'>"
                                +"</td>"
                                +"<td>"
                                +Utils.labelNumber(val.price)
                                +"</td>"

                                +"<td class='text-center'>"
                                +"<input type='hidden' data-name='purchase_orders_detail_id' value='"+val.id+"' name='receipt_purchases_details["+countDetail+"][purchase_orders_detail_id]'>"
                                +"<input type='hidden' data-name='product_id' value='"+val.product_id+"' name='receipt_purchases_details["+countDetail+"][product_id]'>"
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
            }else{
                $(".table-input tbody").html("");
            }
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
                    $(this).attr("name","receipt_purchases_details["+e+"]["+dataName+"]");
                })

            })
        })

        var validatorPrimary = $(".form-primary").validate({
            rules: {
                date: {
                    required: true
                },
                    purchase_order_id : {
                    required : true
                },

            },

            //== Validation messages
            messages: {
                date: {
                    required: "Harap masukan tanggal"
                },
                    purchase_order_id: {
                    required: "Harap pilih nomor PO"
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
                                swal.fire('Berhasil disimpan','Data penerimaan barang pembelian  berhasil disimpan','success');
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