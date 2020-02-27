<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                    Daftar Barang
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <?php echo $this->Form->create('', ['type' => 'get']); ?>
            <div class="row kt--margin-bottom-15">
                <div class="col-md-4">
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="input-sm form-control" name="start" id="start" value="<?=date_format(date_create($startDate), 'Y-m')?>" />
                        <span class="input-group-text">to</span>
                        <input type="text" class="input-sm form-control" name="end" id="end" value="<?=date_format(date_create($endDate), 'Y-m')?>" />
                    </div>
                </div>
                <div class="col-md-4">
                    <?=$this->Form->control('keywordCategory', ['options' => $categories, 'label' => false, 'class' => 'category_id', 'empty' => 'Pilih Kategori', 'value' => $keywordCategory]);?>
                </div>
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-primary btn-submit">
                        Cari
                    </button>
                    <a href="<?= $this->Url->build(['controller' => 'ReportMutations', 'action' => 'index'])  ?>" class="btn btn-primary btn-danger">Refresh</a>
                    <a href="#" class="btn btn-success btn-print" data-print="pdf">Print PDF</a>
                </div>
            </div>
        <?php echo $this->Form->end(); ?>

        <div class="kt-form kt-form--label-align-right kt--margin-top-20 kt--margin-bottom-30">
            <div class="row align-items-center">
                <div class="col-xl-12 order-1 order-xl-2 m--align-right table-scroll">
                    <table class="table table-bordered display nowrap text-left" id="tableFixed" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $subtotal = $total_subtotal = 0 ?>
                            <?php if (!empty($results)): ?>
                                <?php foreach ($results as $result): ?>
                                <?php $subtotal = $result->saldo_akhir * $result->product_price ?>
                                <?php $total_subtotal += $subtotal ?>

                                <tr>
                                    <td><?= h($result->product_id) ?></td>
                                    <td><?= h($result->product_code) ?></td>
                                    <td><?= h($result->product_name) ?></td>
                                    <td><?= h($result->saldo_akhir) ?></td>
                                    <td><?= number_format($result->product_price) ?></td>
                                    <td><?= number_format($subtotal) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-center">TOTAL</th>
                                <th><?= number_format($total_subtotal) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->start('script');?>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
        $("#keywordcategory").select2()

        $('.input-daterange').datepicker({
            todayHighlight: !0,
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            },
            autoclose: true,
            format: "yyyy-mm",
            startView: 2,
            minViewMode: 1,
        });

        $(".btn-print").on("click",function(e){
            e.preventDefault();
            var date = $("#start").val();
            var date2 = $("#end").val();
            var keywordCategory = $("#keywordcategory").val();
            var printData = $(this).data("print");
            if(date.length != 0 && date2.length != 0){
                var url = "<?=$this->Url->build(['action' => 'index']);?>?print="+printData+"&start="+date+"&end="+date2+"&keywordCategory="+keywordCategory;
                window.open(url);
            }else{
                swal.fire('Ooopps','Harap pilih bulan & tahun','error')
            }
        })

        $(document).ready(function() {
            var table = $('#tableFixed').DataTable( {
                responsive: true,
                scrollY: '50vh',
                scrollCollapse: true,
                paging: false,
                searching: false,
                columnDefs: [
                {
                    targets: -1,
                    className: 'dt-body-left'
                }],
            } );
        } );
    </script>

<?php $this->end();?>