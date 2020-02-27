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
            <div class="row m--margin-bottom-15">
                <div class="col-md-4">
                    <input type="text" name="keywordName" id='keywordName' placeholder="Nama Barang" class="form-control" value="<?=$keywordName?>">
                </div>
                <?php if($userData->user_group_id == 1):?>
                    <div class="col-md-4">
                        <?=$this->Form->control('keywordCategory', ['options' => $categories, 'label' => false, 'class' => 'category_id', 'empty' => 'Pilih Kategori', 'value' => $keywordCategory]);?>
                    </div>
                <?php endif;?>
                <div class="m-form__actions">
                    <button type="submit" class="btn btn-primary btn-submit">
                        Cari
                    </button>
                    <a href="<?= $this->Url->build(['controller' => 'ReportMutations', 'action' => 'index'])  ?>" class="btn btn-primary btn-danger">Refresh</a>
                    <button type="button" class="btn btn-warning btn-submit print">
                        Print
                    </button>
                </div>
            </div>
        <?php echo $this->Form->end(); ?>

        <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
            <div class="row align-items-center">
                <div class="col-xl-12 order-1 order-xl-2 m--align-right">
                    <table class="table table-bordered display nowrap text-left" id="tableFixed" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>No Katalog</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <!-- <th>Stok Awal</th> -->
                                <th>IN</th>
                                <th>OUT</th>
                                <th>Stok Akhir</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (!empty($results)): ?>
                                <?php $no = 1; foreach ($results as $result): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= h($result->product_code) ?></td>
                                    <td><?= h($result->product_no_catalog) == null ? '-' : $result->product_no_catalog?></td>
                                    <td><?= h($result->product_name) ?></td>
                                    <td><?= h($result->unit) ?></td>
                                    <!-- <td><?= h($result->saldo_awal) ?></td> -->
                                    <td><?= h($result->in) ?></td>
                                    <td><?= h($result->out) ?></td>
                                    
                                    <?php if($result->saldo_akhir < 0):?>
                                    <td  class="bg-warning text-white"><?= h($result->saldo_akhir) ?></td>
                                    <?php elseif($result->saldo_akhir <= $result->min_unit):?>
                                    <td  class="bg-danger text-white"><?= h($result->saldo_akhir) ?></td>
                                    <?php else:?>
                                        <td  class="bg-info text-white"><?= h($result->saldo_akhir) ?></td>
                                    <?php endif;?>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

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
        $("#keywordcategory").select2();

        $('.print').on('click', function(){
            var keywordName         = $('#keywordName').val();
            var keywordCategory     = $('#keywordcategory').val();
            var url                 = "<?= $this->Url->build(['action' => 'index'])?>";

            window.open(url + '?keywordName=' + keywordName + '&keywordCategory=' + keywordCategory + '&print=1', '_BLANK');
        });
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