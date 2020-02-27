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
<!--end::Modal-->

<?php $this->start('script');?>
    <script>
        $('.input-daterange').datepicker({
            format: "yyyy-mm-dd",
            orientation: "bottom auto",
            todayHighlight:'TRUE',
            autoclose: true
        });
        $(".btn-print").on("click",function(e){
            e.preventDefault();
            var date = $("#start").val();
            var date2 = $("#end").val();
            var printData = $(this).data("print");
            if(date.length != 0 && date2.length != 0){
                var url = "<?=$this->Url->build(['action'=>'index']);?>?print="+printData+"&start="+date+"&end="+date2;
                window.open(url);
                console.log(url);
            }else{
                swal('Ooopps','Harap pilih bulan & tahun','error')
            }
            
        })
    </script>
<?php $this->end();?>