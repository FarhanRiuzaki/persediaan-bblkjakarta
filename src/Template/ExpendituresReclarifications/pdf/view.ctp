<style>
        *{
            font-size: 11px!important;
        }
        .m-portlet__body,
        .table-invoice{
            margin-top: 0!important;
            padding-top: 0!important;
        }
        .table-invoice *{
            font-size: 11px!important;
        }
    </style>
        <div class="m-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-invoice" border="">
            <thead>
                <tr>
                    <th width="20%" colspan="4">
                    </th>
                    <th class="text-right" colspan="3" width=""> 
                        <div class="information-bill">
                             <h3>PENGELUARAN REKLARIFIKASI</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$this->Utilities->indonesiaDateFormat($expendituresReclarification->created->format('Y-m-d'));?></th>
                    <th width="11%"></th>
                    <th width="17%">Kode Permintaan Masuk</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$expendituresReclarification->code;?></th>
                </tr>

                <tr>
                    <th width="17%">Tanggal</th>
                    <th width="2%">:</th>
                    <th width="18%" ><?=$this->Utilities->indonesiaDateFormat($expendituresReclarification->date->format('Y-m-d'));?></th><th width="11%"></th>
                    <th width="17%">Diubah Oleh</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"> <?= (!empty($expendituresReclarification->modified_user->name)) ? $expendituresReclarification->modified_user->name : '-' ?></th>
                </tr>


                <tr>

                    <td colspan="7">
                        <table class="table table-bordered table-detail" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="4"  style="background:#fafafa; text-align: center;">DATA BARANG</th>
                                </tr>
                                 <tr>
                                    <th width="2%">No</th>
                                    <th>Nama Barang</th>
                                    <th width="20%">Harga</th>
                                    <th width="15%">Jumlah</th>
                                </tr>
                            </thead>

                            <tbody>
                                
                                <?php $no = 0; ?>

                                <?php if (!empty($expendituresReclarification->expenditures_reclarifications_details)): ?>
                                    <?php foreach ($expendituresReclarification->expenditures_reclarifications_details as $expendituresReclarificationDetails): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?= h($expendituresReclarificationDetails->product->name) ?></td>
                                        <td><?= h($expendituresReclarificationDetails->price) ?></td>
                                        <td><?= h($expendituresReclarificationDetails->qty) ?> <?= h($expendituresReclarificationDetails->product->unit) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </tbody>
                        </td>
                </tr>
            </tbody>
        </table>
        <table class="table1" >
            <tbody>
                <tr>
                    <th width="50%">TTD Kepala Bagian,</th>
                    <th width="70%"></th>
                    <th width="70%" class="text-right">TTD Pembuat Pengeluaran,</th>
                </tr>
            </tbody>
        </table>
        <br><br><br><br>
        <table class="table">
            <tr>
                <td width="50%">.....................................</td>
                <th width="70%"></th>
                <td width="70%" class="text-right">............................................</td>
            </tr>
        </table>
    </div>
</div>