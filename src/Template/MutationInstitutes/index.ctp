<style type="text/css">
    div.container { max-width: 1200px }
</style>

<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                    Daftar Barang
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <!--begin: Search Form -->
        <?php echo $this->Form->create('', ['type' => 'get']); ?>
            <div class="m--margin-bottom-15">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="date" datepicker-report="true" placeholder="Bulan  " autocomplete="off" class="form-control" style="width: 100% !important" id="date" readonly>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-submit">
                            Cari
                        </button>
                        <a href="<?= $this->Url->build(['controller' => 'MutationInstitutes', 'action' => 'index'])  ?>" class="btn btn-primary btn-danger">Refresh</a>
                    </div>
                </div>
            </div>
        <?php echo $this->Form->end(); ?>
        <br>
        <div class="kt-form kt-forkt--label-align-right kt--margin-top-20 kt--margin-bottokt-30">
            <div class="row align-items-center">
                <div class="col-xl-12 order-1 order-xl-2 kt--align-right">
                    <table class="table table-bordered display nowrap text-left" id="tableFixed" style="width:100%">
                        <thead>
                            <tr class="text-center">
                                <th rowspan="2">ID</th>
                                <th rowspan="2">Kode</th>
                                <th rowspan="2">Nama Barang</th>
                                <th rowspan="2">Satuan</th>
                                <th colspan="2">Masuk</th>
                                <th rowspan="2">Stok Akhir</th>
                            </tr>

                            <tr>
                                <td>Jumlah</td>
                                <td>Satuan</td>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach($expendituresDistributionsDetails as $expendituresDistributionsDetail): ?>
                                <?php $product_in = $expendituresDistributionsDetail->product_in * ($expendituresDistributionsDetail->product_unit_qty == '' ? 1 : $expendituresDistributionsDetail->product_unit_qty) ?>
                                <tr>
                                    <td><?=$expendituresDistributionsDetail->product_id?></td>
                                    <td><?=$expendituresDistributionsDetail->product_code?></td>
                                    <td><?=$expendituresDistributionsDetail->product_name?></td>
                                    <td><?=$expendituresDistributionsDetail->product_unit == '' ? '-' : $expendituresDistributionsDetail->product_unit ?></td>
                                    <td><?= number_format($product_in)?></td>
                                    <td><?=$expendituresDistributionsDetail->product_unit_less == '' ? ($expendituresDistributionsDetail->product_unit == '' ? '-' : $expendituresDistributionsDetail->product_unit): $expendituresDistributionsDetail->product_unit_less ?></td>
                                    <td class="text-center"><?= number_format($product_in - $expendituresDistributionsDetail->product_out)?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
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