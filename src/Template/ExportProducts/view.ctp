
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
            <div class="kt-portlet__body">
                <div class="row">
                    <div class="col-md-2">
                        <button type="button" class="btn btn-block btn-warning pengajuan">
                                Cetak Pengajuan 
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-block btn-primary detail">
                                Cetak Detail 
                        </button>
                    </div>
                </div>
                <br>
                <table class="table table-striped table-bordered table-hover table-checkable">
                    <tr>
                        <th scope="row"><?= __('Id') ?></th>
                        <td><?= $this->Number->format($exportProduct->id) ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?= __('Waktu Export') ?></th>
                        <td><?= $this->utilities->indonesiaDateFormat($exportProduct->date->format('Y-m-d H:i:s'))?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?= __('Dibuat Oleh') ?></th>
                        <td><?= $exportProduct->created_user->name ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?= __('Dibuat') ?></th>
                        <td><?= h($exportProduct->created->format('Y-m-d')) ?></td>
                    </tr>
                </table>
                <br>
                <div class="row">
                    <div class="col-md-2">
                        <h3>Detail Pengajuan</h3>
                    </div>
                    <div class="col-md-10">
                        <hr>
                    </div>
                </div>
                <br>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" width='15px'>NO</th>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center" width='20%'>Qty</th>
                            <th class="text-center">Tanggal Masuk</th>
                            <th class="text-center" width='20%'>Qty Masuk</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; foreach($exportProduct->export_products_details  as $key => $val): $no++;?>
                            <tr>
                                <td><?= $no?></td>
                                <td><?= $val->product->name  ?></td>
                                <td><?= $val->qty ?>  <?= $val->product->unit  ?></td>
                                <td><?= $val->date == null ? '-' : $val->date->format('Y-m-d') ?></td>
                                <td><?= $val->qty_in ?></td>
                                <?php if($val->status == 0): ?>
                                    <td class="bg-danger text-white"> Belum Dibeli </td>
                                <?php elseif($val->status == 1): ?>
                                    <td class="bg-success text-white">Dibeli </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
		</div>
    </div>
</div>

<?php $this->start('script');?>

    <script type="text/javascript">
        $('.pengajuan').on('click', function(){
            var url = "<?= $this->Url->build(['action' => 'print'])?>";
            var id  = '<?= $exportProduct->id ?>';

            window.open(url + '/' + id +'/1');
        });
        
        $('.detail').on('click', function(){
            var url = "<?= $this->Url->build(['action' => 'print'])?>";
            var id  = '<?= $exportProduct->id ?>';

            window.open(url + '/' + id +'/2');
        });
    </script>

<?php $this->end();?>