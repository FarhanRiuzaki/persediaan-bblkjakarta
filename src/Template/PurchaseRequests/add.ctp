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
.select2{
    width:100% !important;
}
</style>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Barang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?= $this->Form->create(null,['class'=>'kt-form barang', 'id'=> 'formBarang']) ?>
        <div class="modal-body">
            <?php 
            $this->Form->setTemplates([
                                        'inputContainer' => '<div class="form-group">{{content}}</div>',
                                    ]);
            ?>
            <div class="row">
                <div class="col-md-12">
                    <?= $this->Form->control('name', ['type' => 'text', 'label' => 'Nama Barang', 'autocomplete' => 'off', 'class' => 'form-control', 'required']); ?>
                </div>
                <div class="col-md-12">
                    <?= $this->Form->control('sub_category_id', ['option'=>$subCategories,'width'=>'100%', 'label' => 'Sub Kategori Barang', 'autocomplete' => 'off', 'class' => 'form-control', 'required','empty'=>'Pilih Sub Kategori']); ?>
                </div>
                <div class="col-md-12">
                    <?= $this->Form->control('no_catalog', ['type' => 'text', 'label' => 'No Katalog', 'autocomplete' => 'off', 'class' => 'form-control', 'required']); ?>
                </div>
                <div class="col-md-12">
                    <?= $this->Form->control('unit', ['type' => 'text', 'label' => 'Satuan', 'autocomplete' => 'off', 'class' => 'form-control', 'required']); ?>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary saveBarang">Simpan</button>
        </div>
    <?=$this->Form->end();?>

    </div>
  </div>
</div>

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
                                'default' => 0,
                                'required'
                                ]);
                                ?>
                            </div>
                                <div class="col-md-4" id="institute-hidden">
                                    <?= $this->Form->control('institute_id', ['label' => 'Nama Unit Instalasi', 'options' => $institutes, 'empty' => 'Pilih instalasi', 'value' => $userData->institute_id]);?>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h3 class="kt-section__title">Detail Barang:</h3>
                        <div class="kt-section__body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-condensed table-bordered table-input">
                                        <thead>
                                            <tr>
                                                <th width="50px">No.</th>
                                                <th width="350px">Nama Barang</th>
                                                <th width="100px">Stok Barang</th>
                                                <th width="250px">No. Katalog</th>
                                                <th width="250px">Spesifikasi</th>
                                                <th width="250px">Merek</th>
                                                <th width="200px">Jumlah</th>
                                                <th width="100px">Satuan</th>
                                                <th width="50px" class="text-center">#</th>
                                            </tr>
                                        </thead>
                                        <tbody class="hide">
                                            <tr class="tr-input">
                                                <td colspan="2">
                                                    <div class="row">
                                                        <!-- <div class="col-md-2">
                                                            <button data-toggle="modal" data-target="#exampleModal" type="button" class="btn btn-success"><span class="fa fa-plus-circle"></span></button>
                                                        </div> -->
                                                        <div class="col-md-12">
                                                            <select class="product_id" id="product-id" style="width:100%">
                                                                <option value="">Pilih Barang</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p id="stok">-</p>
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
                                                    <input type="text" id="qty" class="qty form-control onlyNumberWithoutComa">
                                                </td>
                                                <td>
                                                    <p id="satuan">-</p>
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
        $('.saveBarang').on('click', function(){
            var csrf = "<?= $this->request->_csrfToken ?>";
            var form = $('#formBarang').serialize();
            var unform = $('#formBarang').serializeArray();
            var name            = unform[2].value;
            var sub_category_id = unform[3].value;
            var unit            = unform[5].value;
            var catalog         = unform[4].value;
            if(name == ''){
                    swal.fire('Oops!', 'Masukan Nama Barang', 'error');
            }else if(sub_category_id == ''){
                    swal.fire('Oops!', 'Masukan Sub Kategori Barang', 'error');
            }else if(catalog == ''){
                    swal.fire('Oops!', 'Masukan No Katalog', 'error');
            }else if(unit == ''){
                    swal.fire('Oops!', 'Masukan Satuan Barang', 'error');
            }else{
                $.ajax({
                    url: "<?= $this->Url->build(['controller' =>'Apis', 'action'=>'saveProducts'])?>",
                    type: 'POST',
                    dataType:'JSON',
                    data: form,
                    beforeSend: function(){
                        swal.fire({
                            type : 'info',
                            title: 'Harap menunggu',
                            text: 'Sedang memproses data',
                            showCancelButton: false,
                            showConfirmButton: false,
                            allowOutsideClick : false,
                            allowEscapeKey : false,
                            allowEnterKey : false
                        })
                    },
                    success: function(e) {
                        swal.fire('Berhasil!', 'Data berhasil ditambahkan', 'success');
                        document.getElementById("formBarang").reset();
                        $('#exampleModal').modal('hide');
                    },
                    error: function(e) {
                        swal.fire('Oops!', 'Terjadi kesalahan', 'error');
                        
                    }
                
            });
            }
            // console.log(unform);
        });
        
        var hidden = <?=$userData->institute_id?>;
        if (hidden != 0) {
            $("#institute-hidden").hide();
        }

        $("#institute-id").select2()
        $("#sub-category-id").select2()

        $('.hide').hide();
        $('body').on('change','#type', function(){
            var type = $(this).val();
            console.log(type);
            if(type == ''){
                $('.hide').hide();
                $('.clear').remove();
            }else{
                $('.hide').show();
                $('.clear').remove();
            }
        });
        $('#product-id').select2({
            minimumInputLength : 2,
            ajax: {
                url: '<?=$this->Url->build('/apis/get-products');?>',
                dataType: 'json',
                data : function (params) {
                    var query = {
                        search: params.term,
                        tipe: $('#type').val(),
                        type: 'public'
                    }
                    return query;
                }
            }
        }).on("select2:select",function(e){
            var result = e.params.data;
            $('#no_catalog').val(result.no_catalog);
            $('p#satuan').text(result.satuan);
            $('p#stok').text(result.saldo);
            console.log(result);

        })
        var countDetail = 0;

        //add detail//
        $(".btn-add-detail").on("click",function(e){
            e.preventDefault();
            var productData = $("#product-id").select2('data');
            var productId   = productData[0].id;
            var productText = productData[0].text;
            var productstok = productData[0].saldo;
            var productunit = productData[0].satuan;
            var qty         = $("#qty").val();
            var spec        = $("#spec").val();
            var merk        = $("#merk").val();
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
                var html =   "<tr class='clear'>"
                            +"<td class='number'>"
                            +no
                            +"</td>"
                            +"<td>"
                            +productText
                            +"</td>"
                            +"<td>"
                            +productstok
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
                            +productunit
                            +"</td>"
                            +"<td class='text-center'>"
                            +"<input type='hidden' data-name='status' value='2' name='purchase_requests_details["+countDetail+"][status]'>"
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
                $("p#stok").text('-');
                $("p#satuan").text('-');
                $("#no_catalog").val('');
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
        // $('.form-primary').submit(function(e){
        //     return false;
        //     e.preventDefault();
        // });
        var validatorPrimary = $(".form-primary").validate({
            rules: {
                code: {
                    required: true
                },
                date: {
                    required: true
                },
                'institute-id' : {
                    required : true
                }
            },

            //== Validation messages
            messages: {
                code: {
                    required: "Harap masukan kode permintaan pembelian"
                },
                date: {
                    required: "Harap masukan tanggal"
                },
                'institute-id': {
                    required: "Harap pilih instalasi"
                },
            },
            errorPlacement: function (error, element) {
                // console.log(element.data('select2'));
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
                                swal.fire('Berhasil disimpan','Data permintaan pembelian berhasil disimpan','success');
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