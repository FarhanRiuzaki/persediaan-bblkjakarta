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
                            <h3>BARANG MASUK</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
               <tr>
                    <th width="15%">Jenis</th>
                    <th width="2%">:</th>
                    <th width="18%"><?= $this->Utilities->categorieIn()[$record->category];?></th>
                    <th width="14%"></th>
                 
                    <th width="17%">Kode Barang Masuk</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$record->code;?></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=(!empty($this->Utilities->indonesiaDateFormat($record->created->format('Y-m-d'))))? $this->Utilities->indonesiaDateFormat($record->created->format('Y-m-d')) : '-' ;?></th>
                    <th width="14%"></th>
                    <th width="17%">Tanggal</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($record->date->format('Y-m-d'));?></th>
                </tr>

                <tr>
                    <th width="15%"></th>
                    <th width="2%"></th>
                    <th width="18%"></th>
                    <th width="14%"></th>
                    <th width="17%">Diubah Oleh</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"> <?= (!empty($record->modified_user->name)) ? $record->modified_user->name : '-' ?></th>
                </tr>

                <tr>

                    <td colspan="7">
                        <table class="table table-bordered table-detail" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="5"  style="background:#fafafa; text-align: center;">DATA BARANG</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;"  width="2%">No</th>
                                    <th style="text-align: center;" >Nama Barang</th>
                                    <th style="text-align: center;"  width="15%">Jumlah</th>
                                    <th style="text-align: center;"  width="15%">Harga</th>
                                    <th style="text-align: center;"  width="15%">Kadaluwarsa</th>

                                </tr>
                            </thead>

                            <tbody>
                                
                            </tbody>
                                <?php $no = 0; ?>

                                <?php if (!empty($record->item_receipts_details )): ?>
                                    <?php foreach ($record->item_receipts_details  as $val): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?= h($val->product->name) ?></td>
                                        <td><?= h($val->qty) ?> <?= h($val->product->unit)?></td>
                                        <td align="right"><?= number_format($val->price)?></td>
                                        <td align="center"><?= $val->expired == '' ? '-' : $this->Utilities->indonesiaDateFormat($val->expired->format('Y-m-d'))?></td>
                                    </tr>
                               <?php endforeach; ?>
                                <?php endif; ?>
                            <tbody>
                        </table>
                    </td>
                </thead>
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