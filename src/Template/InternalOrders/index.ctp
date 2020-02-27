<?php
    $this->start('sub_header_toolbar');
?>
    <?php if($this->Acl->check(['action'=>'add']) == true):?>
        <a href="<?=$this->Url->build(['action'=>'add']);?>" class="btn btn-primary">
            <i class="la la-plus-circle"></i> Tambah Data
        </a>
    <?php endif;?>
<?php
    $this->end();
?>
<?=$this->element('widget/datatable');?>
<?php $this->start('script');?>
    <script>
        <?php
            $viewUrl = $this->Url->build(['action'=>'view'])."/";
            if($this->Acl->check(['action'=>'view']) == false){
                $viewUrl = "";
            }
            $editUrl      = $this->Url->build(['action'=>'edit'])."/";
            if($this->Acl->check(['action'=>'edit']) == false){
                $editUrl = "";
            }
            $deleteUrl    = $this->Url->build(['action'=>'delete'])."/";
            if($this->Acl->check(['action'=>'delete']) == false){
                $deleteUrl = "";
            }
            $approvalUrl = $this->Url->build('/approval/add/');
            $pendingUrl = $this->Url->build('/expenditures-distributions/add/');
            $userGroup = $userData->user_group_id;

        ?>
        jQuery(document).ready(function() {
            var deleteUrl = "<?=$deleteUrl;?>";
            var editUrl = "<?=$editUrl;?>";
            var viewUrl = "<?=$viewUrl;?>";
            var pendingUrl = "<?=$pendingUrl;?>";
            var approvalUrl = "<?=$approvalUrl;?>";
            var userGroup = "<?=$userGroup;?>";
            var options = {
                "columnDefs": [
                    { 
                        "title": "#", 
                        "name": "#", 
                        "targets": 0,
                        "orderable" : !1,
                        "searchable" : !1,
                        "className": "text-center",
                        width: '5%',
                        data : "id",
                        render:function(id, e, t, n) {
                            var listLink = "";
                            // if(t.status == 3){
                            //     listLink += '<a class="dropdown-item text-warning" href="'+approvalUrl+ '/' + t.institute_id + '/' + t.id +'"><i class="text-warning flaticon2-checkmark"></i> Proses Approval</a>\n ';
                            // }
                            // if(t.status == 0){
                            //     listLink += '<a class="dropdown-item text-primary" href="'+pendingUrl+ '/' + t.institute_id + '/' + t.id +'"><i class="flaticon2-checkmark text-primary"></i> Proses Pending</a>\n ';
                            // }
                            if(viewUrl != ""){
                                listLink += '<a class="dropdown-item" href="'+viewUrl+id+'"><i class="la la-search-plus"></i> View</a>\n ';
                            }
                            if(editUrl != "" && t.status == 0 || t.status == 3){
                                listLink += '<a class="dropdown-item" href="'+editUrl+id+'"><i class="la la-edit"></i> Edit</a>\n ';
                            }
                            if(deleteUrl != "" && t.status == 0 || t.status == 3){
                                listLink += '<a class="dropdown-item btn-delete-on-table" href="'+deleteUrl+id+'"><i class="la la-delete	la-trash"></i> Delete</a>\n ';
                            }
                            if(listLink != ""){
                                return'\n<span class="dropdown">\n'
                                + '\n<a href="#" class="btn btn-sm btn-primary btn-icon btn-icon-md\n'
                                + '" data-toggle="dropdown"'
                                + 'aria-expanded="true">\n<i class="la la-reorder"></i>\n'               
                                +'</a>\n'
                                +'<div class="dropdown-menu">\n '+listLink+'                    </div>\n</span>\n';
                            }
                            return "-";
                        },
                        responsivePriority: -1

                    },
                    { 
                        "title": "Kode", 
                        "name": "InternalOrders.code", 
                        "targets": 1,
                        "data" : 'code'
                    },
                    { 
                        "title": "Nama Unit Instalasi", 
                        "name": "Institutes.name", 
                        "targets": 2,
                        "data" : 'institute.name'
                    },
                    { 
                        "title": "Tanggal Permintaan", 
                        "name": "InternalOrders.date", 
                        "targets": 3,
                        "data" : 'date',
                        render:function(date) {
                            return Utils.dateIndonesia(date,true,false)
                        }
                    },
                    { 
                        "title": "Tgl. Dibuat User", 
                        "name": "InternalOrders.created",
                        "searchable": !1,  
                        "targets": 4,
                        "data" : 'created',
                        width:'15%',
                        render:function(created) {
                            return Utils.dateIndonesia(created,true,true)
                        }
                    },
                    { 
                        "title": "Status", 
                        "className": "text-center",
                        "searchable": !1,  
                        "orderable" : !1,
                        "targets": 5,
                        width:'15%',
                        render:function(id, e, t, n) {
                            if (t.status == 0) {
                                if(userGroup == 1 || userGroup == 4 || userGroup == 6 ){
                                    return '<a href="'+pendingUrl+ '/' + t.institute_id + '/' + t.id +'"><span class="kt-badge  kt-badge--brand kt-badge--inline kt-badge--pill">PENDING</span></a>';
                                }else{
                                    return '<span class="kt-badge  kt-badge--brand kt-badge--inline kt-badge--pill">PENDING</span>'
                                }
                            }
                            if (t.status == 1) {
                                return '<span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">ON PROCESS</span>';
                            }
                            if (t.status == 2) {
                                return '<span class="kt-badge kt-badge--success  kt-badge--inline kt-badge--pill">SELESAI</span>';
                            }
                            if (t.status == 3) {
                                if(userGroup == 1){
                                    return '<a href="'+approvalUrl+ '/' + t.institute_id + '/' + t.id +'"><span class="kt-badge kt-badge--warning  kt-badge--inline kt-badge--pill">PENDING APPROVAL</span></a>';
                                }else{
                                    return '<span class="kt-badge kt-badge--warning  kt-badge--inline kt-badge--pill">PENDING APPROVAL</span>';
                                }
                            }
                            if (t.status == 4) {
                                if(userGroup == 4 || userGroup == 6 || userGroup == 5 ){
                                    return '<span class="kt-badge kt-badge--danger  kt-badge--inline kt-badge--pill">PENDING SELESAI</span>';
                                }else{
                                    return '<a href="#" class="confirm" data-id='+ t.id +'><span class="kt-badge kt-badge--danger  kt-badge--inline kt-badge--pill">PENDING SELESAI</span></a>';
                                }
                            }
                        }
                    },
                ],
                "order": [[ 1, "DESC" ]]
            }
            DatatableRemoteAjaxDemo.init("",options,"<?=$this->request->getParam('_csrfToken');?>")
        });
        $('body').on('click', '.confirm', function(){
            var id = $(this).data('id');
            var csrf = "<?= $this->request->_csrfToken ?>";
            // console.log(csrf);
            Swal.fire({
            title: 'Anda yakin akan menyelesaikan Amprahan Barang?',
            text: "Data tidak dapat diubah kembali",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Selesai',
            cancelButtonText: 'Batal'
            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?= $this->Url->build('/approval/UpdateStatus/')?>",
                    type: "POST",
                    headers: { 'X-XSRF-TOKEN' : csrf },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', csrf);
                    },
                    dataType: 'json',
                    data: {
                        id:id
                    },
                    success: function(){
                        Swal.fire(
                        'Berhasil!',
                        'Data berhasil diperbaharui.',
                        'success'
                        )
                        $('#kt_table_default').DataTable().ajax.reload();
                    },
                    error: function(){
                        Swal.fire(
                        'Oopss!',
                        'Terjadi kesalahan.',
                        'error'
                        )
                    }
                })
                
            }
            })
        })
    </script>
<?php $this->end();?>