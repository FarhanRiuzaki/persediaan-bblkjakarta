<style>
	/* Define the hover highlight color for the table row */
    .hoverTable tr.detailIn:hover {
          background-color: #ffff99;
    }

    .hoverTable tr.detailOut:hover {
          background-color: #ffff99;
    }

</style>
<!--Begin::Main Portlet-->
<div class="row">
    <div class="col-xl-12">
        <!--begin:: Widgets/Best Sellers-->
        <div class="kt-portlet m-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    Daftar Pengajuan Pembelian Barang
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin::Content-->
                <div class="tab-content">
                    <div class="tab-pane active table-scroll" id="m_widget5_tab1_content" aria-expanded="true">
                        <div class=" col-md-12">
                            <table class="table table-striped datatable">
                                <thead>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Asal</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal Diproses</th>
                                    <th>Status</th>
                                </thead>

                                <tbody>

                                    <?php $no = 1;  ?>
                                    <?php foreach ($purchaseSubmisions as $val): ?>
                                        <tr>
                                            <th><?=$no++;?></th>
                                            <th><?=$val->code;?></th>
                                            <th><?=$val->created_user->name;?></th>
                                            <th><?=$this->Utilities->indonesiaDateFormat($val->date->format('Y-m-d'));?></th>
                                            <th><?= $val->approve_date == null ? '-' : $this->Utilities->indonesiaDateFormat($val->approve_date->format('Y-m-d'));?></th>
                                            <th><?=$this->Utilities->statusPP($val->status, 'PSview', $val->id, $val->institute_id);?></th>
                                        </tr>
                                    <?php endforeach; ?>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
        <!-- <div Widgets/Best Sellers-->
    </div>

</div>
<!--End::Main Portlet-->


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<?php $this->start('script'); ?>

<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    
<script type="text/javascript">

    $('.datatable').DataTable({
        "language": {
            "emptyTable":     "Tidak ada transaksi atau data kosong."
        }
    });
</script>

<?php $this->end(); ?>
