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
                            if(editUrl != ""){
                                listLink += '<a class="dropdown-item" href="'+editUrl+id+'"><i class="la la-edit"></i> Edit</a>\n ';
                            }
                            if(deleteUrl != ""){
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
                        "name": "Products.code", 
                        "targets": no++,
                        "data" : 'code',
                    },
                    { 
                        "title": "No Katalog", 
                        "name": "Products.no_catalog", 
                        "targets": no++,
                        "data" : 'no_catalog',
                    },
                    { 
                        "title": "Nama Sub Kategori", 
                        "name": "SubCategories.name", 
                        "targets": no++,
                        "data" : 'sub_category.name',
                    },
                    { 
                        "title": "Nama Barang", 
                        "name": "Products.name", 
                        "targets": no++,
                        "data" : 'name'
                    },
                    { 
                        "title": "Satuan", 
                        "name": "Products.unit", 
                        "targets": no++,
                        "data" : 'unit'
                    },
                    { 
                        "title": "Minimal Stok Barang", 
                        "name": "Products.min_unit", 
                        "targets": no++,
                        "searchable": !1,  
                        "width":'10%',
                        "data" : 'min_unit'
                    },
                    { 
                        "title": "Status", 
                        "name": "Products.status",
                        "className": "text-center",
                        "orderable" : !1,
                        "searchable": !1,  
                        "targets": no++,
                        "data" : 'status',
                        width:'15%',
                        render:function(status) {
                            return Utils.statusLabelActive(status);
                        }
                    },
                ],
                "order": [[ 1, "ASC" ]]
            }
            DatatableRemoteAjaxDemo.init("",options,"<?=$this->request->getParam('_csrfToken');?>")
        });
    </script>
<?php $this->end();?>