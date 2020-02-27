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
        <div class="kt-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-invoice" border="">
            <thead>
                <tr>
                    <th width="20%" colspan="4">
                    </th>
                    <th class="text-right" colspan="3">
                        <div class="information-bill">
                   			<h3>PENGELUARAN BARANG HIBAH</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th width="15%">Diberikan Kepada</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$expendituresGrant->given_to;?></th>
                    <th width="1%"></th>
                    <th width="17%">Kode Permintaan Masuk</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$expendituresGrant->code;?></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$this->Utilities->indonesiaDateFormat($expendituresGrant->created->format('Y-m-d'));?></th>
                    <th width="1%"></th>
                    <th width="17%">Tanggal</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($expendituresGrant->date->format('Y-m-d'));?></th>
                </tr>

                <tr>
                    <th width="15%"></th>
                    <th width="2%"></th>
                    <th width="18%"></th>
                    <th width="1%"></th>
                    <th width="17%">Diubah Oleh</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"> <?= (!empty($expendituresGrant->modified_user->name)) ? $expendituresGrant->modified_user->name : '-' ?></th>
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

                                <?php if (!empty($expendituresGrant->expenditures_grants_details)): ?>
                                    <?php foreach ($expendituresGrant->expenditures_grants_details as $expendituresGrantDetails): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?= h($expendituresGrantDetails->product->name) ?></td>
                                        <td><?= h($expendituresGrantDetails->price) ?></td>
                                        <td><?= h($expendituresGrantDetails->qty) ?> <?= h($expendituresGrantDetails->product->unit) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                          </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table" >
            <tbody>
                <tr>
                    <th width="50%">TTD Kepala Bagian,</th>
                    <th width="70%"></th>
                    <th width="70%" class="text-right">TTD Pembuat Pengeluaran,</th>
                </tr>
            </tbody>
        </table>
        <br><br><br><br>
        <table class="table1">
            <tr>
                <td width="50%">.....................................</td>
                <th width="70%"></th>
                <td width="70%" class="text-right">............................................</td>
            </tr>
        </table>
    </div>
</div>