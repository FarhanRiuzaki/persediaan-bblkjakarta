<?php
    $this->start('sub_header_toolbar');
?>
    <!-- <?php if($this->Acl->check(['action'=>'add']) == true):?>
        <a href="<?=$this->Url->build(['action'=>'add']);?>" class="btn btn-primary">
            <i class="la la-plus-circle"></i> Tambah Data
        </a>
    <?php endif;?> -->
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
                            // if(deleteUrl != ""){
                            //     listLink += '<a class="dropdown-item btn-delete-on-table" href="'+deleteUrl+id+'"><i class="la la-delete	la-trash"></i> Delete</a>\n ';
                            // }
                            // if(editUrl != ""){
                            //     listLink += '<a class="dropdown-item" href="'+editUrl+id+'"><i class="la la-edit"></i> Edit</a>\n ';
                            // }
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
                        render:function(e) {
                            var asiaTime = new Date(e).toLocaleString("en-US", {timeZone: "UTC"});
                            return Utils.dateIndonesia(new Date(asiaTime), true, true);
                        }
                    },
                    { 
                        "title": "Tgl. Diapprove Kepala Ruangan", 
                        "name": "InternalOrders.approve_date",
                        "searchable": !1,  
                        "targets": 5,
                        "data" : 'approve_date',
                        width:'15%',
                        render:function(e) {
                            var asiaTime = new Date(e).toLocaleString("en-US", {timeZone: "UTC"});
                            return Utils.dateIndonesia(new Date(asiaTime), true, true);
                        }
                    },
                    { 
                        "title": "Diapprove Oleh", 
                        "name": "InternalOrders.approve_by", 
                        "targets": 6,
                        "data" : 'approve_by',
                        render:function(e) {
                            console.log(e);
                            if(e == 1){
                                return 'Administrator'
                            }else{
                                return 'Kepala Ruangan/ Atasan Langsung'
                            }
                            // return Utils.dateIndonesia(date,true,false)
                        }
                    },
                ],
                "order": [[ 1, "DESC" ]]
            }
            DatatableRemoteAjaxDemo.init("",options,"<?=$this->request->getParam('_csrfToken');?>")
        });
    </script>
<?php $this->end();?>