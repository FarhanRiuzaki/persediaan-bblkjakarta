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
                        <th scope="row"><?= __('Nama') ?></th>
                        <td><?= h($record->name) ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?= __('Kategori') ?></th>
                        <td><?= $record->has('category') ? $this->Html->link($record->category->name, ['controller' => 'Categories', 'action' => 'view', $record->category->id]) : '' ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?= __('Status') ?></th>
                        <td><?= $this->Utilities->statusLabelAktif($record->status) ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?= __('Tanggal Dibuat') ?></th>
                        <td><?= (!empty($record->created)?$this->Utilities->indonesiaDateFormat($record->created->format('Y-m-d')) : '-' ) ;?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?= __('Tanggal Diubah') ?></th>
                        <td><?= (!empty($record->modified)?$this->Utilities->indonesiaDateFormat($record->modified->format('Y-m-d')) : '-' ) ;?></td>
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