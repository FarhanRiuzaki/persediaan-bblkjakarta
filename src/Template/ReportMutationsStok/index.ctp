<div class="kt-portlet m-portlet--tab">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                <?=$titlesubModule;?>
                <?php $this->Form->setTemplates([
                                    'inputContainer' => '<div class="form-group">{{content}}</div>',
                                ]);?>
            </h3>
        </div>
    </div>
    <?= $this->Form->create(null, ['class' => 'm-form m-form--fit m-form--label-align-right form-primary', 'type' => 'file']) ?>
        <div class="kt-portlet__body">
            <div class="row m--margin-bottom-15">
                <div class="col-md-4">
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="input-sm form-control" name="start" id="start" autocomplete="off"/>
                        <span class="input-group-text">to</span>
                        <input type="text" class="input-sm form-control" name="end" id="end" autocomplete="off"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot m-portlet__foot--fit">
            <div class="kt-form__actions">
                <a href="#" class="btn btn-primary btn-print" data-print="webview">
                    Print Web
                </a>
                <a href="#" class="btn btn-primary btn-print" data-print="pdf">
                    Print PDF
                </a>
                   <a href="#" class="btn btn-primary btn-print" data-print="xlsx">
                    Print Excel
                </a>
                <button type="reset" class="btn btn-secondary">
                    Reset
                </button>
            </div>
        </div>
    </form>
</div>
<div class="kt-portlet m-portlet--tab">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                <?=$titlesubModule;?> Detail Perbulan
            </h3>
        </div>
    </div>
    <?= $this->Form->create(null, ['class' => 'm-form m-form--fit m-form--label-align-right form-primary', 'type' => 'file']) ?>
        <div class="kt-portlet__body">
            <div class="row m--margin-bottom-15">
                <?php if($userData->user_group_id == 1):?>
                    <div class="col-md-3">
                        <?=$this->Form->control('category_id', ['options' => $categories, 'label' => false, 'empty' => 'Pilih Kategori','class'=>'form-control','style'=>'100%']);?>
                    </div>
                <?php endif;?>
                <div class="col-md-3">
                    <?=$this->Form->control('date', ['datepicker-report' => 'true', 'placeholder' => 'Bulan  ', 'type' => 'text', 'label' => false, 'autocomplete' => 'off','class'=>'form-control','style'=>'100%']);?>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot m-portlet__foot--fit">
            <div class="kt-form__actions">
                <a href="#" class="btn btn-primary btn-print-month" data-print="xlsx">
                    Print Excel
                </a>
                <button type="reset" class="btn btn-secondary">
                    Reset
                </button>
            </div>
        </div>
    </form>
</div>
<div class="kt-portlet m-portlet--tab">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                <?=$titlesubModule;?> Tahunan
            </h3>
        </div>
    </div>
    <?= $this->Form->create(null, ['class' => 'm-form m-form--fit m-form--label-align-right form-primary', 'type' => 'file']) ?>
        <div class="kt-portlet__body">
            <div class="row m--margin-bottom-15">
                <?php if($userData->user_group_id == 1):?>
                    <div class="col-md-3">
                        <?=$this->Form->control('category_id_year', ['options' => $categories, 'label' => false, 'empty' => 'Pilih Kategori','class'=>'form-control','style'=>'100%']);?>
                    </div>
                <?php endif;?>
                <div class="col-md-3">
                    <?=$this->Form->control('year', ['placeholder' => 'Tahun', 'type' => 'text', 'label' => false,'class'=>'form-control','style'=>'100%']);?>
                </div>
            </div>
        </div>
        <div class="kt-portlet__foot m-portlet__foot--fit">
            <div class="kt-form__actions">
                <a href="#" class="btn btn-primary btn-print-year" data-print="xlsx">
                    Print Excel
                </a>
                <button type="reset" class="btn btn-secondary">
                    Reset
                </button>
            </div>
        </div>
    </form>
</div>

<?php $this->start('script');?>
    <script>
        $("#category-id").select2();
        $("#category-id-year").select2();

        $('.input-daterange').datepicker({
            format: "yyyy-mm-dd",
            orientation: "bottom auto",
            todayHighlight:'TRUE',
            autoclose: true
        });
        $('#year').datepicker({
            viewMode: "years",
            minViewMode: "years",
            format: "yyyy",
            orientation: "bottom auto",
            todayHighlight:'TRUE',
            autoclose: true,
        });

        $(".btn-print").on("click",function(e){
            e.preventDefault();
            var date = $("#start").val();
            var date2 = $("#end").val();
            var printData = $(this).data("print");
            if(date.length != 0 && date2.length != 0){
                var url = "<?=$this->Url->build(['action' => 'index']);?>?print="+printData+"&start="+date+"&end="+date2;
                window.open(url);
                console.log(url);
            }else{
                swal.fire('Ooopps','Harap pilih bulan & tahun','error')
            }
        })
        $(".btn-print-month").on("click",function(e){
            e.preventDefault();
            var date        = $("#date").val();
            var cek         = "<?= $userData->user_group_id ?>";
            var category    = $("#category-id").val();

            if(cek == 4){
                category = '1,3,4';
            }else if(cek == 6){
                category = '2';
            }

            var printData   = $(this).data("print");
            if (category == '' || category == 0) {
                swal.fire('Ooopps','Harap pilih kategori','error');
            } else if(date.length != 0){
                var url = "<?=$this->Url->build(['action' => 'printMonth']);?>?print="+printData+"&month="+date+"&category_id="+category;
                window.open(url);
                console.log(url);
            }else{
                swal.fire('Ooopps','Harap pilih bulan & tahun','error')
            }
        })
        $(".btn-print-year").on("click",function(e){
            e.preventDefault();
            var year = $("#year").val();
            var category = $("#category-id-year").val();
            var printData = $(this).data("print");
            var cek         = "<?= $userData->user_group_id ?>";
            
            if(cek == 4){
                category = '1,3,4';
            }else if(cek == 6){
                category = '2';
            }
            
            if (category == '' || category == 0) {
                swal.fire('Ooopps','Harap pilih kategori','error');
            } else if (year == null) {
                swal.fire('Ooopps','Harap pilih tahun','error');
            } else {
                var url = "<?=$this->Url->build(['action' => 'printYear']);?>?print="+printData+"&year="+year+"&category_id="+category;
                window.open(url);
            }
        });
    </script>
<?php $this->end();?>