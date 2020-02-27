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
                            if(deleteUrl != ""){
                                listLink += '<a class="dropdown-item btn-delete-on-table" href="'+deleteUrl+id+'"><i class="la la-delete	la-trash"></i> Delete</a>\n ';
                            }
                            if(editUrl != ""){
                                listLink += '<a class="dropdown-item" href="'+editUrl+id+'"><i class="la la-edit"></i> Edit</a>\n ';
                            }
                            if(viewUrl != ""){
                                listLink += '<a class="dropdown-item" href="'+viewUrl+id+'"><i class="la la-search-plus"></i> View</a>\n ';
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
                        "name": "ExpendituresGrants.code", 
                        "targets": 1,
                        "data" : 'code'
                    },
                    { 
                        "title": "Diberikan Kepada", 
                        "name": "ExpendituresGrants.given_to", 
                        "targets": 2,
                        "data" : 'given_to'
                    },
                    { 
                        "title": "Tanggal", 
                        "name": "ExpendituresGrants.date", 
                        "targets": 3,
                        "data" : 'date',
                        render:function(date) {
                            return Utils.dateIndonesia(date,true,false)
                        }
                    },
                    { 
                        "title": "Tgl. Dibuat", 
                        "name": "ExpendituresGrants.created",
                        "searchable": !1,  
                        "targets": 4,
                        "data" : 'created',
                        width:'15%',
                        render:function(created) {
                            return Utils.dateIndonesia(created,true,true)
                        }
                    },
                    { 
                        "title": "Tgl. Diubah", 
                        "name": "ExpendituresGrants.modified",
                        "searchable": !1,  
                        "targets": 5,
                        "data" : 'modified',
                        width:'15%',
                        render:function(modified) {
                            if(modified == null){
                                return '-';
                            }
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