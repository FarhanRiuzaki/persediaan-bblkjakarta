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

                                echo $this->Form->control('date', ['datepicker' => 'true', 'type' => 'text', 'label' => 'Tanggal', 'autocomplete' => 'off', 'class' => 'form-control']);

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
                                <?= $this->Form->control('institute_id', ['label' => 'Nama Unit Unit Kerja', 'options' => $institutes, 'empty' => 'Pilih Unit Kerja', 'value' => $userData->institute_id]);?>
                            </div>
                            <div class="col-md-12">
                            <table class="table table-condensed table-bordered table-input">
                                <thead>
                                    <tr>
                                        <th width="50px">No.</th>
                                        <th>Nama Barang</th>
                                        <th class="text-center" width="100px">No Katalog</th>
                                        <th class="text-center" width="100px">Stok</th>
                                        <th class="text-center" width="100px">Satuan</th>
                                        <th class="text-center" width="150px">Jumlah diminta</th>
                                        <th class="text-center" width="200px">Keterangan</th>
                                        <th width="50px" class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody class="container-detail hide">
                                    <tr class="tr-input">
                                        <td colspan="2">
                                            <select class="product_id" id="product-id" style="width:100%">
                                                <option value="">Pilih Barang</option>
                                            </select>
                                        </td>
                                        <td align="center" style="vertical-align: middle">
                                            <p id='katalog'></p>
                                        </td>
                                        <td>
                                            <input type="text" id="saldo" disabled class="saldo form-control onlyNumberWithoutComa">
                                        </td>
                                        <td align="center" style="vertical-align: middle">
                                            <p id='satuan'></p>
                                        </td>
                                        <td>
                                            <input type="text" id="qty" class="qty form-control onlyNumberWithoutComa">
                                        </td>
                                        <td>
                                            <textarea id="note" class="form-control"></textarea>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-info btn-add-detail"><i class="fa fa-check"></i></a>
                                        </td>
                                    </tr>
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
        
        loadAfterChangeDate();
        $('.hide').hide();

        var arrayProduct = [];
        var oldProduct = [];
        var countDetail = 0;
        var endDate = '';

        var hidden = <?=$userData->institute_id?>;
        if (hidden != 0) {
            $("#institute-hidden").hide();
        }

        $("#institute-id").select2()

        $("#date-picker,.date-picker,input[datepicker='true']").datepicker({
            todayHighlight: !0,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            },
            autoclose: true,
            disabled: true,
            format: "dd-mm-yyyy"
        });

        $('#type').on('change', function (e) { 
            endDate = $("#date").val();
            type    = $("#type").val();
            $('.hide').show();
            
            if(type == ''){
                $('.hide').hide();
            }else{
            var html = '<tr class="tr-input">'
                        +'    <td colspan="2">'
                        +'        <select class="product_id" id="product-id" style="width:100%">'
                        +'            <option value="">Pilih Barang</option>'
                        +'        </select>'
                        +'    </td>'
                        +'    <td align="center" style="vertical-align: middle">'
                        +'        <p id="katalog"></p>'
                        +'    </td>'
                        +'    <td>'
                        +'        <input type="text" id="saldo" disabled class="saldo form-control onlyNumberWithoutComa">'
                        +'    </td>'
                        +'    <td align="center" style="vertical-align: middle">'
                        +'        <p id="satuan"></p>'
                        +'    </td>'
                        +'    <td>'
                        +'        <input type="text" id="qty" class="qty form-control onlyNumberWithoutComa">'
                        +'    </td>'
                        +'    <td>'
                        +'        <textarea id="note" class="form-control"></textarea>'
                        +'    </td>'
                        +'    <td class="text-center">'
                        +'        <a href="#" class="btn btn-info btn-add-detail"><i class="fa fa-check"></i></a>'
                        +'    </td>'
                        +'</tr>';

            $(".container-detail").html(html);
            loadAfterChangeDate();
            countDetail = 0;
            arrayProduct = [];
            oldProduct = []
            }
        });

        function loadAfterChangeDate() {
            $('#product-id').select2({
                minimumInputLength : 2,
                ajax: {
                    url: '<?=$this->Url->build('/apis/get-products');?>',
                    dataType: 'json',
                    data : function (params) {
                        var query = {
                            search: params.term,
                            endDate: endDate,
                            tipe: type,
                            type: 'public'
                        }
                        return query;
                    }
                }
            }).on("select2:select",function(e){
                var result = e.params.data;
                var saldo = result['saldo'];
                var satuan = result['satuan'];
                var no_catalog = result['no_catalog']
                if(satuan == null){
                    satuan = '-';
                }
                if(arrayProduct[result.id] != undefined){
                    saldo = saldo - arrayProduct[result.id];
                }
                $('#saldo').val(saldo);
                $('p#satuan').text(satuan);
                $('p#katalog').text(no_catalog);
                if(no_catalog == null){
                    $('p#katalog').text('-');
                }
            })

            //add detail//
            $(".btn-add-detail").on("click",function(e){
                e.preventDefault();
                var productData = $("#product-id").select2('data');

                var productId       = productData[0].id;
                var productText     = productData[0].text;
                var productSaldo    = $("#saldo").val();
                var note            = $("#note").val();
                var productSatuan   = productData[0].satuan;
                var katalog         = productData[0].no_catalog;

                if(productSatuan == null){
                    productSatuan = '-';
                }
                if(katalog == null){
                    katalog = '-';
                }
                var qty = $("#qty").val();
                var valid = true;
                var validMsg = "";
                console.log(productSaldo);
                console.log(qty);
                if(productId == "" || productId == 0){
                    valid = false;
                    validMsg = "Harap pilih nama barang";
                } else if( productSaldo == "" || parseInt(qty) > parseInt(productSaldo) ){
                    valid = false;
                    validMsg = "Saldo tidak cukup";
                } else if(qty == "" || qty < 1){
                    valid = false;
                    validMsg = "Harap masukan qty barang";
                }

                if(valid){
                    var no = countDetail + 1;
                    var html =   "<tr>"
                                +"<td class='number'>"
                                +no
                                +"</td>"
                                +"<td colspan='4'>"
                                +productText
                                +"</td>"
                                +"<td>"
                                +Utils.labelNumberWithoutSeparator(qty) + ' ' + productSatuan
                                +"</td>"
                                +"<td>"
                                +note
                                +"</td>"
                                +"<td class='text-center'>"
                                +"<input type='hidden' data-name='product_id' value='"+productId+"' name='internal_orders_details["+countDetail+"][product_id]'>"
                                +"<input type='hidden' data-name='qty' value='"+qty+"' name='internal_orders_details["+countDetail+"][qty]'>"
                                +"<input type='hidden' data-name='qty_request' value='"+qty+"' name='internal_orders_details["+countDetail+"][qty_request]'>"
                                +"<input type='hidden' data-name='note' value='"+note+"' name='internal_orders_details["+countDetail+"][note]'>"
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
                    $("#note").val('');
                    $('#saldo').val('');
                    console.log(countDetail);
                }else{
                    swal.fire('Gagal menambahkan detail barang',validMsg,'error')
                }
            })

            //delete detail
            $("body").on("click",".btn-delete-detail",function(e){
                e.preventDefault();
                var tr = $(this).closest("tr");
                tr.remove();
                countDetail = countDetail - 1;
                arrayProduct = [];
                var productOldId = tr.find('input[data-name="product_id"]').val();

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
                    if($("#product-id").val() == productId){
                        var productData = $("#product-id").select2('data');
                        var productSaldo = productData[0].saldo;
                        $("#saldo").val(productSaldo - arrayProduct[productId]);
                    }
                })

                if($("#product-id").val() == productOldId){
                    var productData = $("#product-id").select2('data');

                    var productSaldo = productData[0].saldo;
                    if(arrayProduct[productOldId] == undefined){
                        arrayProduct[productOldId] = 0;
                    }

                    if(oldProduct[productOldId] = undefined){
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
                    },
                    type : {
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
                    type: {
                        required: "Harap pilih tipe"
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

                    console.log(countDetail);
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
                                    swal.fire('Berhasil disimpan','Data permintaan user berhasil disimpan','success');
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
        }

    </script>
<?php $this->end();?>