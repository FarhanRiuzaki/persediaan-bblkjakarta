<?php
    $this->start('sub_header_toolbar');
?>
    <?php if($this->Acl->check(['action'=>'index']) == true):?>
        <a href="<?=$this->Url->build(['action'=>'index']);?>" class="btn btn-warning">
            <i class="la la-angle-double-left"></i> Kembali
        </a>
    <?php endif;?>
    <?php if($this->Acl->check(['action'=>'add']) == true):?>
        <a href="<?=$this->Url->build(['action'=>'add']);?>" class="btn btn-primary">
            <i class="la la-plus-circle"></i> Tambah Data
        </a>
    <?php endif;?>
    <?php if($this->Acl->check(['action'=>'edit']) == true):?>
        <a href="<?=$this->Url->build(['action'=>'edit',$record->id]);?>" class="btn btn-success">
            <i class="la la-edit"></i> Edit Data
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
            <div class="kt-portlet__body">
                <table class="table table-striped table-bordered table-hover table-checkable">
                
                <tr>
                <th scope="row"><?= __('Kode') ?></th>
                    <td><?= h($record->code) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Sub Kategori') ?></th>
                    <td><?= $record->has('sub_category') ? $this->Html->link($record->sub_category->name, ['controller' => 'SubCategories', 'action' => 'view', $record->sub_category->id]) : '' ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Nama Barang') ?></th>
                    <td><?= h($record->name) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Satuan Barang') ?></th>
                    <td><?= h($record->unit) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Status') ?></th>
                    <td><?= $this->Utilities->statusLabelAktif($record->status) ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Tanggal Dibuat') ?></th>
                    <td><?=$this->Utilities->indonesiaDateFormat($record->created->format('Y-m-d'));?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Tanggal Diubah') ?></th>
                    <td><?= (!empty($record->modified) ? $this->Utilities->indonesiaDateFormat($record->modified->format('Y-m-d')) : '-') ;?></td>
                </tr>

                <tr>
                    <th scope="row"><?= __('Dibuat Oleh') ?></th>
                    <td><?= $record->created_user->name ?></td>
                </tr>
                <tr>
                    <th scope="row"><?= __('Diubah Oleh') ?></th>
                    <td><?= (!empty($record->modified_user) ? $record->modified_user->name : '-') ?></td>
                </tr>
                </table>
            </div>
		</div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title">
                    Satuan Terkecil <?=$record->name;?>
					</h3>
				</div>
			</div>
            <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table">
                <tr>
                    <th width="10%">No</th>
                    <th>Satuan</th>
                    <th>Jumlah</th>
                </tr>

                <?php if(empty($record->product_unit)): ?>
                    <tr>
                        <td colspan="3"><center>Tidak ada satuan terkecil</center></td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= h($record->product_unit->unit) ?></td>
                        <td><?= h($record->product_unit->qty) ?></td>
                    </tr>
                <?php endif ?>
            </table>
            <!--end: Datatable -->
            </div>
        </div>
    </div>
</div>
