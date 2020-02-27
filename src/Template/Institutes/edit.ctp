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
            <?= $this->Form->create($institute,['class'=>'kt-form','type'=>'file']) ?>
            <div class="kt-portlet__body">
					<div class="kt-section kt-section--first">
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $this->Form->setTemplates([
                                    'inputContainer' => '<div class="form-group">{{content}}</div>',
                                ]);

                                echo $this->Form->control('name',[
                                    'class'=>'form-control m-input',
                                    'required',
                                    'label' => [
                                        'text'=>'Nama Unit Kerja '
                                    ],
                                ]);
                                echo $this->Form->control('head_of_institute',[
                                    'class'=>'form-control m-input',
                                    'required',
                                    'label' => [
                                        'text'=>'Nama Kepala Unit '
                                    ],
                                ]);
                                echo $this->Form->control('position_head_institute',[
                                    'class'=>'form-control m-input',
                                    'required',
                                    'label' => [
                                        'text'=>'Jabatan '
                                    ],
                                ]);
                                echo $this->Form->control('head_of_institute_id',[
                                    'class'=>'form-control m-input',
                                    'type' => 'text',
                                    'label' => [
                                        'text'=>'NIP'
                                    ],
                                ]);
                                $this->Form->setTemplates([
                                    'inputContainer' => '<div class="form-group row">{{content}}</div>',
                                ]);

                                echo $this->Form->control('status',[
                                    'class'=>'form-control m-input',
                                    'templateVars' => [
                                        'colsize' => 'col-lg-8 col-xl-6',
                                        'typeLine' => 'kt-checkbox-inline',
                                        'labeltext' => 'Status'
                                    ],
                                    'type' => 'checkbox',
                                    'required' => false,
                                    'label' => [
                                        'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                        'text'=>'AKTIF'
                                    ]
                                ]);

                                ?>
                            </div>
                        </div>
                        
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