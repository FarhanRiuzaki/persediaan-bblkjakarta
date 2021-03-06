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
            $deleteUrl    = $this->Url->build(['action'=>'delete'])."/";
            if($this->Acl->check(['action'=>'delete']) == false){
                $deleteUrl = "";
            }
            $editUrl      = $this->Url->build(['action'=>'edit'])."/";
            if($this->Acl->check(['action'=>'edit']) == false){
                $editUrl = "";
            }
            $viewUrl = $this->Url->build(['action'=>'view'])."/";
            if($this->Acl->check(['action'=>'view']) == false){
                $viewUrl = "";
            }
        ?>
        jQuery(document).ready(function() {
            var deleteUrl = "<?=$deleteUrl;?>";
            var editUrl = "<?=$editUrl;?>";
            var viewUrl = "<?=$viewUrl;?>";
            var no = 0;
            var options = {
                "columnDefs": [
                    { 
                        "title": "#", 
                        "name": "#", 
                        "targets": no++,
                        "orderable" : !1,
                        "searchable" : !1,
                        "className": "text-center",
                        width: '5%',
                        data : "id",
                        render:function(id, e, t, n) {
                            var listLink = "";
                            if(viewUrl != ""){
                                listLink += '<a class="dropdown-item" href="'+viewUrl+id+'"><i class="la la-search-plus"></i> View</a>\n ';
                            }
                            if(editUrl != "" && t.status == 2){
                                listLink += '<a class="dropdown-item" href="'+editUrl+id+'"><i class="la la-edit"></i> Edit</a>\n ';
                            }
                            if(deleteUrl != "" && t.status == 0 || t.status == 2){
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
                        "name": "PurchaseRequests.code", 
                        "targets": no++,
                        "data" : 'code'
                    },
                    { 
                        "title": "Nama Unit Instalasi", 
                        "name": "Institutes.name", 
                        "targets": no++,
                        "data" : 'institute.name'
                    },
                    { 
                        "title": "Tanggal", 
                        "name": "PurchaseRequests.date", 
                        "targets": no++,
                        "data" : 'date',
                        render:function(date) {
                            return Utils.dateIndonesia(date,true,false)
                        }
                    },
                    { 
                        "title": "Status", 
                        "name": "PurchaseRequests.status",
                        "className": "text-center",
                        "searchable": !1,  
                        "targets": no++,
                        "data" : 'status',
                        width:'15%',
                        render:function(status) {
                            if(status == 0){
                                return '<span class="kt-badge  kt-badge--brand kt-badge--inline kt-badge--pill">PENDING</span>'
                            }else if(status == 2){
                                return '<span class="kt-badge  kt-badge--warning kt-badge--inline kt-badge--pill">PENDING APPROVE</span>'
                            }else{
                                return '<span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">TELAH DIPROSES</span>'
                            }
                        }
                    },
                    { 
                        "title": "Tgl. Dibuat", 
                        "name": "PurchaseRequests.created",
                        "searchable": !1,  
                        "targets": no++,
                        "data" : 'created',
                        width:'15%',
                        render:function(created) {
                            return Utils.dateIndonesia(created,true,true)
                        }
                    },
                    { 
                        "title": "Tgl. Diubah", 
                        "name": "PurchaseRequests.modified",
                        "searchable": !1,  
                        "targets": no++,
                        "data" : 'modified',
                        width:'15%',
                        render:function(modified) {
                            return Utils.dateIndonesia(modified,true,true)
                        }
                    },
                ],
                "order": [[ 1, "ASC" ]]
            }
            DatatableRemoteAjaxDemo.init("",options,"<?=$this->request->getParam('_csrfToken');?>")
        });
    </script>
<?php $this->end();?>