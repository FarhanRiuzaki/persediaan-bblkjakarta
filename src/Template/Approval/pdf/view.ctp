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
        <table class="table table-invoice">
            <thead>
                <tr>
                    <th width="20%" colspan="4"></th>
                    <th class="text-right" colspan="3">
                        <div class="information-bill">
                            <h3>PERMINTAAN USER</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th width="15%">Nama Unit Instalasi</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$internalOrder->institute->name;?>lor</th>
                    <th width="15%"></th>
                    <th width="17%">Kode Permintaan Masuk</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$internalOrder->code;?></th>
                    <th></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$this->Utilities->indonesiaDateFormat($internalOrder->created->format('Y-m-d'));?></th>
                    <th width="15%"></th>
                    <th width="17%">Tanggal</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($internalOrder->date->format('Y-m-d'));?></th>
                    <th></th>

                </tr>
                <tr>
                    <td colspan="8">
                        <table class="table table-bordered table-detail" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="4"  style="background:#fafafa; text-align: center;">DATA BARANG</th>
                                </tr>
                                <tr>
                                   <th width="2%">No</th>
                                    <th width="53%">Nama Barang</th>
                                    <th width="20%">Jumlah</th>
                                    <th width="25%">Jumlah Disetujui Gudang</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                                 <?php $no = 0; ?>

                              <?php if (!empty($internalOrder->internal_orders_details)): ?>
                                    <?php foreach ($internalOrder->internal_orders_details as $internalOrdersDetails): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?= h($internalOrdersDetails->product->name) ?></td>
                                        <td><?= h($internalOrdersDetails->qty) ?> <?= h($internalOrdersDetails->product->unit) ?></td>
                                        <td><span class="onlyNumberWithoutSeparator"><?= $approve == null ? '-' : h($approve[$internalOrdersDetails->product_id]) ?></span> <?= h($internalOrdersDetails->product->unit) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <tbody>

                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table">
            <tbody>
                <tr>
                    <th width="30%">TTD Ka. Instalasi / Kasi / Kasubbag / Kabid / Kabag,</th>
                    <th width="70%"></th>
                    <th width="20%">TTD Pembuat,</th>
                </tr>
            </tbody>
        </table>
        <br><br><br><br>
        <table class="table1">
            <tr>
                <td width="30%">.....................................</td>
                <th width="45%"></th>
                <td width="20%">.....................................</td>
            </tr>
        </table>
    </div>
</div>
