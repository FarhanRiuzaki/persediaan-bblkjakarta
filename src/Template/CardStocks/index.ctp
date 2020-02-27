
<div class="kt-portlet">
    <div class="kt-portlet__head kt-portlet__space-x">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                    <?=$titlesubModule;?>
            </h3>
		</div>
		<div class="kt-portlet__head-toolbar" id="print">
			<!-- <a href=7 -->
            <!-- <a href="#" class="kt-nav__link">
                <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                <span class="kt-nav__link-text">Reports</span>
            </a> -->
		</div>
	</div>

    <div class="kt-portlet__body">
        <div class="kt-section kt-section--first">
            <div class="row">
                <div class="col-md-4">
                    <?php
                    $this->Form->setTemplates([
                        'inputContainer' => '<div class="form-group">{{content}}</div>',
                    ]);

                    echo $this->Form->control('product_id', ['label' => false, 'empty' => 'Pilih Barang', 'style' => 'width:100%', 'class' => 'form-control']);?>
                </div>
                <div class="col-md-2"> 
                    <?=$this->Form->control('start', ['datepicker-report' => 'true', 'placeholder' => 'Tanggal Awal', 'type' => 'text', 'label' => false, 'class' => 'form-control', 'style' => 'width:100%','autocomplete'=>'off']);?>
                </div>
                <div class="col-md-2">
                    <?=$this->Form->control('end', ['datepicker-report' => 'true', 'placeholder' => 'Tanggal Akhir', 'type' => 'text', 'label' => false, 'class' => 'form-control', 'style' => 'width:100%','autocomplete'=>'off']);?>
                </div>
                
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-primary btn-submit">
                        Cari
                    </button>
                    <button type="submit" class="btn btn-primary btn-danger btn-refresh">
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PEMASUKAN -->
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                    Riwayat Pemasukan
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div id="m_datatable_in"></div>
    </div>
</div>

<!-- PENGELUARAN -->
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                    Riwayat Pengeluaran
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div id="m_datatable_out"></div>
    </div>
</div>

<!-- STOK OPNAME -->
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                    Stok Opname
            </h3>
        </div>
    </div>
    <div class="kt-portlet__body">
        <div id="m_datatable_stok"></div>
    </div>
</div>

<?php $this->start('script');?>
    <script>
        var i = undefined;
        var o = undefined;
        var s = undefined;

        // SEARCH Barang
        $('#product-id').select2({
            minimumInputLength : 2,
            ajax: {
                url: '<?=$this->Url->build('/apis/get-products');?>',
                dataType: 'json',
                data : function (params) {
                    var query = {
                        search: params.term,
                        type: 'public'
                    }
                    return query;
                }
            }
        })

        // SEARCH
        $("body").on("click",".btn-submit",function(e){
            var productId = $('#product-id').val();
            var startDate = $('#start').val();
            var endDate = $('#end').val();
            var valid = true;
            var validMsg = "";

            if(productId == "" || productId == 0){
                valid = false;
                validMsg = "Harap pilih nama barang";
            } else if(startDate == ""){
                valid = false;
                validMsg = "Harap pilih tanggal awal";
            } else if(endDate == ""){
                valid = false;
                validMsg = "Harap pilih tanggal akhir";
            }

            if(valid){
                // DESTROY DATATABLE PEMASUKAN DAN PENGELUARAN
                if(i != undefined) {
                    i.destroy();
                }
                if (o != undefined) {
                    o . destroy();
                }

                if (s != undefined) {
                    s . destroy();
                }

                // SET PRINT BUTTON
                var html = '<li class="kt-portlet__head-toolbar">'
                            + '    <a href="<?= $this->Url->build(['action' => 'print'])  ?>/'+productId+'/'+startDate+'/'+endDate+'" class="kt-portlet__nav-link btn btn-primary  kt-btn kt-btn--pill kt-btn--air no-print btn-print " target="_BLANK" data-print="pdf">'
                            + '        CETAK KARTU'
                            + '    </a>'
                            + '</li>';

                $("#print").html(html);

                // SET DATATABLE PEMASUKAN
                i = $("#m_datatable_in").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                headers : {
                                    'X-CSRF-Token': "<?= $this->request->getParam('_csrfToken') ?>"   
                                },
                                url: '<?=$this->Url->build('/apis/get-stocks');?>/'+ productId + '/IN/' + startDate + '/' + endDate,
                            }
                        },
                        pageSize: 10,
                        saveState: {
                            cookie: true,
                            webstorage: true
                        },

                        serverPaging: false,
                        serverFiltering: false,
                        serverSorting: false,
                        autoColumns: false
                    },
                    layout: {
                        theme: "default",
                        class: "",
                        scroll: false,
                        footer: false
                    },
                    sortable: true,
                    filterable: true,
                    pagination: true,
                    columns: [
                        {
                            field: "id",
                            title: "No",
                            sortable: false,
                            width: 40,
                            selector: false,
                            textAlign: "center",
                            template: function(t) {
                                return t.id
                            }
                        }, {
                            field: "date",
                            title: "Tanggal",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.dateIndonesia(t.date,true)
                            }
                        }, {
                            field: "ref_table",
                            title: "Referensi",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.statusRefTable(t.ref_table)
                            }
                        }, {
                            field: "qty",
                            title: "Jumlah",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.labelNumberWithoutSeparator(t.qty)
                            }
                        }, {
                            field: "price",
                            title: "Harga",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.labelNumber(t.price)
                            }
                        }, {
                            field: "custom",
                            title: "Subtotal",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.labelNumber(t.price * t.qty)
                            }
                        }
                    ],
                    translate: {
                        records: {
                            processing: 'Harap menunggu...',
                            noRecords: 'Data tidak ditemukan'
                        },
                        toolbar: {
                            pagination: {
                                items: {
                                    info: ''
                                }
                            }
                        }
                    }
                });

                // SET DATATABLE PENGELUARAN
                o = $("#m_datatable_out").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                headers : {
                                    'X-CSRF-Token': "<?= $this->request->getParam('_csrfToken') ?>"   
                                },
                                url: '<?=$this->Url->build('/apis/get-stocks');?>/'+ productId + '/OUT/' + startDate + '/' + endDate,
                            }
                        },
                        pageSize: 10,
                        saveState: {
                            cookie: true,
                            webstorage: true
                        },

                        serverPaging: false,
                        serverFiltering: false,
                        serverSorting: false,
                        autoColumns: false
                    },
                    layout: {
                        theme: "default",
                        class: "",
                        scroll: false,
                        footer: false
                    },
                    sortable: true,
                    filterable: true,
                    pagination: true,
                    columns: [
                        {
                            field: "id",
                            title: "No",
                            sortable: false,
                            width: 40,
                            selector: false,
                            textAlign: "center",
                            template: function(t) {
                                return t.id
                            }
                        }, {
                            field: "date",
                            title: "Tanggal",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.dateIndonesia(t.date,true)
                            }
                        },  {
                            field: "ref_table",
                            title: "Referensi",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.statusRefTable(t.ref_table)
                            }
                        },  {
                            field: "qty",
                            title: "Jumlah",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.labelNumberWithoutSeparator(t.qty)
                            }
                        }, {
                            field: "price",
                            title: "Harga",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.labelNumber(t.price)
                            }
                        }, {
                            field: "custom",
                            title: "Subtotal",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.labelNumber(t.price * t.qty)
                            }
                        }
                    ],
                    translate: {
                        records: {
                            processing: 'Harap menunggu...',
                            noRecords: 'Data tidak ditemukan'
                        },
                        toolbar: {
                            pagination: {
                                items: {
                                    info: ''
                                }
                            }
                        }
                    }
                });

                // SET DATATABLE PENGELUARAN
                s = $("#m_datatable_stok").KTDatatable({
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                headers : {
                                    'X-CSRF-Token': "<?= $this->request->getParam('_csrfToken') ?>"   
                                },
                                url: '<?=$this->Url->build('/apis/get-stock-opnames-details');?>/'+ productId + '/' + startDate + '/' + endDate,
                            }
                        },
                        pageSize: 10,
                        saveState: {
                            cookie: true,
                            webstorage: true
                        },

                        serverPaging: false,
                        serverFiltering: false,
                        serverSorting: false,
                        autoColumns: false
                    },
                    layout: {
                        theme: "default",
                        class: "",
                        scroll: false,
                        footer: false
                    },
                    sortable: true,
                    filterable: true,
                    pagination: true,
                    columns: [
                        {
                            field: "id",
                            title: "No",
                            sortable: false,
                            width: 40,
                            selector: false,
                            textAlign: "center",
                            template: function(t) {
                                return t.id
                            }
                        }, {
                            field: "date",
                            title: "Tanggal",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.dateIndonesia(t.date,true)
                            }
                        }, {
                            field: "product_name",
                            title: "Nama Barang",
                            sort : 'asc',
                            template: function(t) {
                                return t.product_name
                            }
                        }, {
                            field: "last_qty",
                            title: "Stock System",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.labelNumberWithoutSeparator(t.last_qty)
                            }
                        }, {
                            field: "qty",
                            title: "Stok Fisik",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.labelNumberWithoutSeparator(t.qty)
                            }
                        }, {
                            field: "qty_diff",
                            title: "Selisih",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.labelNumberWithoutSeparator(t.qty_diff)
                            }
                        }, {
                            field: "price",
                            title: "Harga",
                            sort : 'asc',
                            template: function(t) {
                                return Utils.labelNumber(t.price)
                            }
                        }, {
                            field: "info",
                            title: "Keterangan",
                            sort : 'asc',
                            template: function(t) {
                                return t.info
                            }
                        }
                    ],
                    translate: {
                        records: {
                            processing: 'Harap menunggu...',
                            noRecords: 'Data tidak ditemukan'
                        },
                        toolbar: {
                            pagination: {
                                items: {
                                    info: ''
                                }
                            }
                        }
                    }
                });
            }else{
                swal.fire('Gagal melakukan pencarian',validMsg,'error')
            }
        });

        // REFRESH
        $("body").on("click",".btn-refresh",function(e){
            if(i != undefined && o != undefined && s != undefined) {
                i.destroy();
                o.destroy();
                s.destroy();
            }

            $('#product-id').val('');
            $('#product-id').trigger('change');
            $("#date").val('');

            $("#print").val('');
        });


    </script>
<?php $this->end();?>