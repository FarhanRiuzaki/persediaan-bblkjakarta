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
            <?= $this->Form->create($stockOpname,['class'=>'kt-form form-primary','type'=>'file']) ?>
            
				<div class="kt-portlet__body">
                    <div class="kt-section kt-section--first">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <?=$this->Form->control('keywordCategory', ['options' => $categories, 'label' => false, 'class' => 'category_id', 'empty' => 'Pilih Kategori', 'class'=>'form-control']);?>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id='find' class="btn btn-primary btn-submit">
                                        Cari
                                    </button>
                                    <a href="<?= $this->Url->build(['action' => 'add'])  ?>" class="btn btn-primary btn-danger">Refresh</a>
                                </div>
                            </div>
                        </div>
                    <?=$this->Form->control('date', ['datepicker' => 'true', 'type' => 'hidden', 'value' => date('d-m-Y')]);?>
                        <div class="col-md-12">
                            <input id="searchInput" class="form-control" placeholder="Cari barang">
                            <br>
                            <table class="table table-condensed table-bordered table-input myTable">
                                <thead>
                                    <tr>
                                        <th width="50px">No.</th>
                                        <th>Nama Barang</th>
                                        <th width="120px">Stok System</th>
                                        <th width="200px">Stok Fisik</th>
                                        <th width="200px">Selisih</th>
                                        <th width="200px">Harga</th>
                                        <th width="200px">Keterangan</th>
                                        <th width="50px" class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody id="fbody">

                                </tbody>
                            </table>
                        </div>
		            </div>
	            </div>
	            <div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<div class="row">
							<div class="col-lg-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
								<button type="reset" class="btn btn-warning">Reset</button>
							</div>
						</div>
					</div>
				</div>
			<?=$this->Form->end();?>
			<!--end::Form-->
		</div>
    </div>
</div>

<?php $this->start('script'); ?>

    <script>
        $("#searchInput").keyup(function () {
            //split the current value of searchInput
            var data = this.value.split(" ");
            //create a jquery object of the rows
            var jo = $("body").find("#fbody tr");
            if (this.value == "") {
                jo.show();
                return;
            }
            //hide all the rows
            jo.hide();

            //Recusively filter the jquery object to get results.
            jo.filter(function (i, v) {
                var $t = $(this);
                for (var d = 0; d < data.length; ++d) {
                    if ($t.is(":contains('" + data[d] + "')")) {
                        return true;
                    }
                }
                return false;
            })
            //show the rows that match.
            .show();
        }).focus(function () {
            this.value = "";
            $(this).css({
                "color": "black"
            });
            $(this).unbind('focus');
        }).css({
            "color": "#C0C0C0"
        });
        
        var countDetail = 0;

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
        });

        $('#find').on('click', function(){
            var cek = $('#keywordcategory').val();
            console.log(cek);
            if(cek != ''){
                $(".table-input tbody").empty();
                var countDetail = 0;
                $.ajax({
                    url: '<?=$this->Url->build('/apis/get-stock-opnames');?>',
                    data: {cek:cek},
                    dataType: 'json',
                    success: function(result){
                        datas = result.results

                        var no = 0;
                        $.each(datas, function(index, data) {
                                var html = '';

                            // if(data.product_saldo > 0) {
                                no = countDetail + 1;
                                html =   "<tr>"
                                            +"<td class='number'>"
                                            +no
                                            +"</td>"
                                            +"<td>"
                                            +data.name
                                            +"</td>"
                                            +"<td>"
                                            +Utils.labelNumberWithoutSeparator(data.product_saldo)
                                            +"</td>"
                                            +"<td>"
                                            +"<input type='text' data-name='qty' class='form-control onlyNumberWithoutSeparator' id='product-qty' data-id='"+ countDetail +"' name='stock_opnames_details["+ countDetail +"][qty]' value='"+ data.product_saldo +"'>"
                                            +"</td>"
                                            +"<td>"
                                            +"<input type='text' data-name='qty_diff' class='form-control onlyNumberWithoutSeparator' id='product-qty-diff' readonly='true' name='stock_opnames_details["+ countDetail +"][qty_diff]' value='"+ 0 +"'>"
                                            +"</td>"
                                            +"<td>"
                                            +"<input type='text' data-name='price' class='form-control onlyNumber' id='product-price' readonly='true' value='"+ data.price +"' name='stock_opnames_details["+ countDetail +"][price]'>"
                                            +"</td>"
                                            +"<td>"
                                            +"<input type='text' data-name='info' class='form-control' value='-' name='stock_opnames_details["+ countDetail +"][info]'>"
                                            +"</td>"
                                            +"<td class='text-center'>"
                                            +"<input type='hidden' data-name='product_id' value='"+data.id+"' name='stock_opnames_details["+countDetail+"][product_id]'>"
                                            +"<input type='hidden' data-name='last_qty' value='"+ data.product_saldo +"' id='product-last-qty' name='stock_opnames_details["+ countDetail +"][last_qty]'>"
                                            +"<a href='#' class='btn btn-danger btn-delete-detail'><i class='fa fa-times'></i></a>"
                                            +"</td>"
                                            +"</tr>";

                                $(".table-input tbody").append(html);

                                $('[name="stock_opnames_details['+ countDetail +'][qty]"]').rules("add", {
                                    required: true,
                                    messages : {
                                        required : 'Harap masukan jumlah stok fisik'
                                    }
                                });

                                $("#product-qty[data-id='"+ countDetail +"']" ).keyup(function() {
                                    var qty = $(this).val()
                                    var product_saldo = data.product_saldo
                                    var total = 0;

                                    total = product_saldo*1 - qty*1;

                                    $(this).parent().parent().find('#product-qty-diff').val(total)
                                });

                                countDetail = no;
                            // }
                        })

                        Utils.initNumber();
                    }
                })

            }
            // return false;
        });
        $( document ).ready(function() {
            $.ajax({
                url: '<?=$this->Url->build('/apis/get-stock-opnames');?>',
                dataType: 'json',
                success: function(result){
                    datas = result.results

                    var no = 0;
                    $.each(datas, function(index, data) {
                            var html = '';

                        // if(data.product_saldo > 0) {
                            no = countDetail + 1;
                            html =   "<tr>"
                                        +"<td class='number'>"
                                        +no
                                        +"</td>"
                                        +"<td>"
                                        +data.name
                                        +"</td>"
                                        +"<td>"
                                        +Utils.labelNumberWithoutSeparator(data.product_saldo)
                                        +"</td>"
                                        +"<td>"
                                        +"<input type='text' data-name='qty' class='form-control onlyNumberWithoutSeparator' id='product-qty' data-id='"+ countDetail +"' name='stock_opnames_details["+ countDetail +"][qty]' value='"+ data.product_saldo +"'>"
                                        +"</td>"
                                        +"<td>"
                                        +"<input type='text' data-name='qty_diff' class='form-control onlyNumberWithoutSeparator' id='product-qty-diff' readonly='true' name='stock_opnames_details["+ countDetail +"][qty_diff]' value='"+ 0 +"'>"
                                        +"</td>"
                                        +"<td>"
                                        +"<input type='text' data-name='price' class='form-control onlyNumber' id='product-price' readonly='true' value='"+ data.price +"' name='stock_opnames_details["+ countDetail +"][price]'>"
                                        +"</td>"
                                        +"<td>"
                                        +"<input type='text' data-name='info' class='form-control' value='-' name='stock_opnames_details["+ countDetail +"][info]'>"
                                        +"</td>"
                                        +"<td class='text-center'>"
                                        +"<input type='hidden' data-name='product_id' value='"+data.id+"' name='stock_opnames_details["+countDetail+"][product_id]'>"
                                        +"<input type='hidden' data-name='last_qty' value='"+ data.product_saldo +"' id='product-last-qty' name='stock_opnames_details["+ countDetail +"][last_qty]'>"
                                        +"<a href='#' class='btn btn-danger btn-delete-detail'><i class='fa fa-times'></i></a>"
                                        +"</td>"
                                        +"</tr>";

                            $(".table-input tbody").append(html);

                            $('[name="stock_opnames_details['+ countDetail +'][qty]"]').rules("add", {
                                required: true,
                                messages : {
                                    required : 'Harap masukan jumlah stok fisik'
                                }
                            });

                            $("#product-qty[data-id='"+ countDetail +"']" ).keyup(function() {
                                var qty = $(this).val()
                                var product_saldo = data.product_saldo
                                var total = 0;

                                total = product_saldo*1 - qty*1;

                                $(this).parent().parent().find('#product-qty-diff').val(total)
                            });

                            countDetail = no;
                        // }
                    })

                    Utils.initNumber();
                }
            })
        });



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
                    $(this).attr("name","stock_opnames_details["+e+"]["+dataName+"]");
                })
            })
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
                                swal.fire('Berhasil disimpan','Data stok opname berhasil disimpan','success');
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