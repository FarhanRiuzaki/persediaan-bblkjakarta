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
			<?= $this->Form->create($record,['class'=>'kt-form','type'=>'file']) ?>
				<div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
                        <?php
                            // echo $this->Form->control('code',[
                            //     'class'=>'form-control m-input',
                            //     'templateVars' => [
                            //         'colsize' => 'col-lg-4 col-xl-4',
                            //     ],
                            //     'required',
                            //     'label' => [
                            //         'class'=> 'col-lg-3 col-xl-2 col-form-label',
                            //         'text'=>'Kode '
                            //     ],
                            // ]);
                            echo $this->Form->control('no_catalog',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-4',
                                ],
                                'label' => [
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'No Katalog'
                                ],
                            ]);
                            echo $this->Form->control('name',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-4',
                                ],
                                'required',
                                'label' => [
                                    'required',
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'Nama Barang'
                                ],
                            ]);
                            echo $this->Form->control('unit',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-4',
                                ],
                                'required',
                                'label' => [
                                    'required',
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'Satuan Barang'
                                ],
                            ]);
                            echo $this->Form->control('min_unit',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-4',
                                ],
                                'required',
                                'label' => [
                                    'required',
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'Minimal Stok Barang'
                                ],
                            ]);
                            echo $this->Form->control('sub_category_id',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-4',
                                ],
                                'label' => [
                                    'required',
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'Sub Kategori'
                                ],
                                'required',
                                'options' => $subCategories,
                                'empty' => 'Pilih Sub Kategori'
                            ]);
                            echo $this->Form->control('status',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-4',
                                    'typeLine' => 'kt-checkbox-inline',
                                    'labeltext' => 'Status'
                                ],
                                'type' => 'checkbox',
                                'label' => [
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'AKTIF'
                                ]
                            ]);
                        ?>
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

<?php $this->start('script');?>
<script>
$('#sub-category-id').select2();
</script>
<?php $this->end();?>
