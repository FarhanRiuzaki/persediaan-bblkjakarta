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
                    <th colspan="4"></th>
                    <th class="text-right" colspan="3">
                        <div class="information-bill">
                            <h3>PENERIMAAN BARANG LAINNYA</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
               <tr>
                    <th width="15%">Sumber Hibah</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$receiptOther->source;?></th>
                    <th width="14%"></th>
                 
                    <th width="17%">Kode Penerimaan Lainnya</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$receiptOther->code;?></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=(!empty($this->Utilities->indonesiaDateFormat($receiptOther->created->format('Y-m-d'))))? $this->Utilities->indonesiaDateFormat($receiptOther->created->format('Y-m-d')) : '-' ;?></th>
                    <th width="14%"></th>
                    <th width="17%">Tanggal</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($receiptOther->date->format('Y-m-d'));?></th>
                </tr>

                <tr>
                    <th width="15%"></th>
                    <th width="2%"></th>
                    <th width="18%"></th>
                    <th width="14%"></th>
                    <th width="17%">Diubah Oleh</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"> <?= (!empty($receiptOther->modified_user->name)) ? $receiptOther->modified_user->name : '-' ?></th>
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
                                    <th width="15%">Jumlah</th>
                                    <th width="15%">Harga</th>

                                </tr>
                            </thead>

                            <tbody>
                                
                            </tbody>
                                <?php $no = 0; ?>

                                <?php if (!empty($receiptOther->receipt_others_details)): ?>
                                    <?php foreach ($receiptOther->receipt_others_details as $ReceiptOthersDetails): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?= h($ReceiptOthersDetails->product->name) ?></td>
                                        <td><?= h($ReceiptOthersDetails->qty) ?> <?= h($ReceiptOthersDetails->product->unit)?></td>
                                        <td><?= h($ReceiptOthersDetails->price)?></td>
                                    </tr>
                               <?php endforeach; ?>
                                <?php endif; ?>
                            <tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table1" >
            <tbody>
                <tr>
                    <th width="50%">TTD Kepala Bagian,</th>
                    <th width="70%"></th>
                    <th width="70%" class="text-right">TTD Pembuat Penerimaan,</th>
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