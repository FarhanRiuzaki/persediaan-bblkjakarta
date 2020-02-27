<!--Begin::Main Portlet-->
<div class="row">
    <div class="col-xl-12">
        <!--begin:: Widgets/Best Sellers-->
        <div class="kt-portlet m-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                    Daftar Permintaan User
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <!--begin::Content-->
                <div class="tab-content">
                    <div class="tab-pane active" id="m_widget5_tab1_content" aria-expanded="true">

                        <table class="table table-striped">
                            <thead>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </thead>

                            <tbody>

                                <?php $no = 1;  ?>
                                <?php foreach ($internalOrders as $internalOrder): ?>
                                    <tr>
                                        <th><?=$no++;?></th>
                                        <th><?=$internalOrder->code;?></th>
                                        <th><?=$this->Utilities->indonesiaDateFormat($internalOrder->date->format('Y-m-d'));?></th>
                                        <th><?=$this->Utilities->statusLabelIo($internalOrder->status, 'IOview', $internalOrder->id);?></th>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if ($no == 1):?>
                                    <tr>
                                        <td colspan="4" align="center">Data tidak tersedia.</td>
                                    </tr>
                                <?php endif;?>

                            </tbody>
                        </table>

                    </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
        <!--end:: Widgets/Best Sellers-->
    </div>
</div>
<!--End::Main Portlet-->
<?php $this->start('script');?>
<script>
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
                        window.location.reload();
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