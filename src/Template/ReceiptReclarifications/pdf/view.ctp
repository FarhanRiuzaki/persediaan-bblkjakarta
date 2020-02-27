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
    </style>s
   <div class="kt-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-invoice" border="">
            <thead>
                <tr>
                    <th width="20%" colspan="3">
                    </th>
                    <th class="text-right" colspan="4">
                        <div class="information-bill">
                            <h3>PENERIMAAN REKLARIFIKASI MASUK</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                 <tr>
                    <th width="15%">Kode :</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$receiptReclarification->code;?></th>
                    <th width="1%"></th>
                    <th width="17%">Kode Reklasifikasi Pengeluaran</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$receiptReclarification->expenditures_reclarification->code;?></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=(!empty($this->Utilities->indonesiaDateFormat($receiptReclarification->created->format('Y-m-d'))))? $this->Utilities->indonesiaDateFormat($receiptReclarification->created->format('Y-m-d')) : '-' ;?></th>
                    <th width="1%"></th>
                    <th width="17%">Tanggal</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($receiptReclarification->date->format('Y-m-d'));?></th>
                </tr>

                <tr>
                    <th width="15%"></th>
                    <th width="2%"></th>
                    <th width="18%"></th>
                    <th width="1%"></th>
                    <th width="17%">Diubah Oleh</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"> <?= (!empty($receiptReclarification->modified_user->name)) ? $receiptReclarification->modified_user->name : '-' ?></th>
                </tr>

                    <td colspan="7">
                        <table class="table table-bordered table-detail" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="6"  style="background:#fafafa; text-align: center;">DATA BARANG</th>
                                </tr>
                                   <tr>
                                    <th width="2%">No.</th>
                                    <th width="">Kode Reklarifikasi Pengeluaran</th>
                                    <th width="">Nama Barang</th>
                                    <th width="">Jumlah PR</th>
                                    <th width="">Jumlah</th>
                                    <th width="">Harga</th>
                                </tr>
                            </thead>

                            <tbody>
                                
                                <?php $no = 0; ?>

                                <?php if (!empty($receiptReclarification->receipt_reclarifications_details)): ?>
                                    <?php foreach ($receiptReclarification->receipt_reclarifications_details as $ReceiptReclarificationsDetails): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?=h($ReceiptReclarificationsDetails->expenditures_reclarifications_detail->expenditures_reclarification->code)?></td>
                                        <td><?= h($ReceiptReclarificationsDetails->product->name) ?></td>
                                       <td><?=  h($ReceiptReclarificationsDetails->expenditures_reclarifications_detail->qty) ?> <?= h($ReceiptReclarificationsDetails->product->unit)?></td>
                                        <td><?= h($ReceiptReclarificationsDetails->qty) ?> <?= h($ReceiptReclarificationsDetails->product->unit)?></td>
                                        <td><?= h($ReceiptReclarificationsDetails->price) ?> </td>
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
                    <th width="70%" class="text-right">TTD Pembuat Penerimaan,</th>
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
