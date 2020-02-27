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
        <!--begin: Datatable -->
        <table class="table table-invoice">
            <thead>
                <tr>
                    <th width="40%" colspan="3">

                    </th>
                    <th width="10%">&nbsp;</th>
                    <th class="text-right" colspan="3">
                        <div class="information-bill">
                            <h3>STOK AWAL</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th width="15%">Kode Stok Awal</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$initStock->code;?></th>
                    <th width="18%"></th>
                    <th width="17%">Tanggal</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($initStock->date->format('Y-m-d'));?></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$this->Utilities->indonesiaDateFormat($initStock->created->format('Y-m-d'));?></th>
                    <th width="18%"></th>
                    <th width="17%">Diubah Oleh</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"> <?= (!empty($initStock->modified_user->name)) ? $initStock->modified_user->name : '-' ?></th>
                </tr>

                <tr>
                    <td colspan="15">
                        <table class="table table-bordered table-detail">
                            <thead>
                                <tr>
                                    <th colspan="5" class="text-center" style="background:#fafafa;">DATA BARANG</th>
                                </tr>
                                <tr>
                                    <th width="2%">No</th>
                                    <th>Nama Barang</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="15%">Harga</th>
                                    <th width="15%">Kedaluwarsa</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                                <?php $no = 0; ?>

                                <?php if (!empty($initStock->init_stocks_details)): ?>
                                    <?php foreach ($initStock->init_stocks_details as $init_stocks_detail): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?= h($init_stocks_detail->product->name) ?></td>
                                        <td><span class="onlyNumberWithoutSeparator"><?= h($init_stocks_detail->qty) ?></span> <?= h($init_stocks_detail->product->unit) ?></td>
                                        <td><span class="onlyNumber"><?= h($init_stocks_detail->price) ?></span></td>
                                        <td align='center'><span ><?= $init_stocks_detail->expired == '' ? '-' : $this->Utilities->indonesiaDateFormat($init_stocks_detail->expired->format('Y-m-d')) ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <tbody>

                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>