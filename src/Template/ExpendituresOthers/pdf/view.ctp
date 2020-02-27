<style>
        *{
            font-size: 11px!important;
        }
        .kt-portlet__body,
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
                    <th class="text-right" colspan="3">
                        <div class="information-bill">
                             <h3>PENGELUARAN BARANG LAINNYA</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th width="15%">Diberikan Kepada</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$expendituresOther->given_to;?></th>
                    <th width=""></th>
                    <th width="17%">Kode Permintaan Masuk</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$expendituresOther->code;?></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$this->Utilities->indonesiaDateFormat($expendituresOther->created->format('Y-m-d'));?></th>
                    <th width=""></th>
                    <th width="17%">Tanggal</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($expendituresOther->date->format('Y-m-d'));?></th>
                </tr>

                <tr>
                    <th width="15%"></th>
                    <th width="2%"></th>
                    <th width="18%"></th>
                    <th width=""></th>
                    <th width="17%">Diubah Oleh</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"> <?= (!empty($expendituresOther->modified_user->name)) ? $expendituresOther->modified_user->name : '-' ?></th>
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

                                <?php if (!empty($expendituresOther->expenditures_others_details)): ?>
                                    <?php foreach ($expendituresOther->expenditures_others_details as $expendituresOtherDetails): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?= h($expendituresOtherDetails->product->name) ?></td>
                                        <td><?= h($expendituresOtherDetails->price) ?></td>
                                        <td><?= h($expendituresOtherDetails->qty) ?> <?= h($expendituresOtherDetails->product->unit) ?></td>
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