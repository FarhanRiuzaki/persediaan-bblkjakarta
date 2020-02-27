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
                    <th width="20%" colspan="4"></th>
                    <th class="text-right" colspan="3">
                        <div class="information-bill">
                   			 <h3>PENERIMAAN BARANG HIBAH</h3>
                         </div>
                    </th>
                </tr>
            </thead>
            <tbody>
              <tr>
                    <th width="10%">Sumber Hibah</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$receiptGrant->source;?></th>
                    <th width="14%"></th>
                    <th width="17%">Kode Penerimaan Lainnya</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$receiptGrant->code;?></th>

                </tr>

                <tr>
                    <th width="15%">Tanggal BAST</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=($receiptGrant->date_bast == null || $receiptGrant->date_bast == '') ? '' : $this->Utilities->indonesiaDateFormat($receiptGrant->date_bast)?></th>
                    <th width="18%"></th>
                    <th width="17%">Tanggal Pembukuan</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($receiptGrant->date->format('Y-m-d'));?></th>
                </tr>
                <tr>
                    <th width="15%">No. Bast</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$receiptGrant->code_bast?></th>
                    <th width="18%"></th>
                    <th width="17%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=(!empty($this->Utilities->indonesiaDateFormat($receiptGrant->created->format('Y-m-d'))))? $this->Utilities->indonesiaDateFormat($receiptGrant->created->format('Y-m-d')) : '-' ;?></th>
                </tr>
                <tr>
                    <th width="15%"></th>
                    <th width="2%"></th>
                    <th width="18%"></th>
                    <th width="18%"></th>
                    <th width="17%">Diubah Oleh</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"> <?= (!empty($receiptGrant->modified_user->name)) ? $receiptGrant->modified_user->name : '-' ?></th>
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

                                <?php if (!empty($receiptGrant->receipt_grants_details)): ?>
                                    <?php foreach ($receiptGrant->receipt_grants_details as $ReceiptgrantsDetails): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?= h($ReceiptgrantsDetails->product->name) ?></td>
                                        <td><?= h($ReceiptgrantsDetails->qty) ?> <?= h($ReceiptgrantsDetails->product->unit)?></td>
                                        <td align='right'><?= number_format($ReceiptgrantsDetails->price)?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <tbody>
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