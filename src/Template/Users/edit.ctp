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
					<div class="kt-section kt-section--first" id="form-input">
                        <?php
                            echo $this->Form->control('username',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-3',
                                ],
                                'label' => [
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'Username *'
                                ],
                                'required'
                            ]);
                            echo $this->Form->control('password',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-3',
                                ],
                                'label' => [
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'Password *'
                                ],
                                'required'
                            ]);
                            echo $this->Form->control('name',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-3',
                                ],
                                'label' => [
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'Nama Pengguna *'
                                ],
                                'required'
                            ]);
                            echo $this->Form->control('email',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-3',
                                ],
                                'label' => [
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'Email Pengguna *'
                                ],
                                'required'
                            ]);
                            echo $this->Form->control('user_group_id',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-3',
                                ],
                                'label' => [
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'Group User *'
                                ],
                                'options' => $userGroups,
                                'empty' => 'Pilih Grup Pengguna',
                                'required'
                            ]);
                            
                        ?>
                        <div class='hide'>
                            <?php
                            echo $this->Form->control('institute_id',[
                                'class'=>'form-control m-input',
                                'templateVars' => [
                                    'colsize' => 'col-lg-4 col-xl-3',
                                ],
                                'label' => [
                                    'class'=> 'col-lg-3 col-xl-2 col-form-label',
                                    'text'=>'Nama Unit Instalasi'
                                ],
                                'options' => $institutes,
                                'empty' => 'Pilih Instalasi'
                            ]);
                            ?>
                        </div>
                        <?php
                        echo $this->Form->control('status',[
                            'class'=>'form-control m-input',
                            'templateVars' => [
                                'colsize' => 'col-lg-4 col-xl-3',
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
        $(document).ready(function(){
            var data = $('#user-group-id').val();
            console.log(data);
            if(data == 3 || data.id == 7){
                $('.hide').show();
                $("#institute-id").select2();
                $("#institute-id").prop('required',true);
            }else{
                $('.hide').hide();
                $("#institute-id").prop('required',false);
            }
        })
        // $('.hide').hide();
        $('#user-group-id').select2().on('select2:select', function(e){
            var data = e.params.data;
            // console.log(data);
            if(data.id == 3 || data.id == 7){
                $('.hide').show();
                $("#institute-id").select2();
                $("#institute-id").prop('required',true);
            }else{
                $('.hide').hide();
                $("#institute-id").prop('required',false);
            }
        });
        

    </script>
<?php $this->end();?>