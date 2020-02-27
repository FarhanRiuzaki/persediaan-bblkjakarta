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
   <div class="m-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-invoice" border="">
            <thead>
                <tr>
                    <th width="20%" colspan="4">
                    </th>
                    <th class="text-right" colspan="3">
                        <div class="information-bill">
                            <h3>PENERIMAAN BARANG PEMBELIAN</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th width="15%">Kode </th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$receiptPurchase->code;?></th>
                    <th width="15%"></th>
                    <th width="17%">Nomor PO</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$receiptPurchase->purchase_order->nomor_po;?></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=(!empty($this->Utilities->indonesiaDateFormat($receiptPurchase->created->format('Y-m-d'))))? $this->Utilities->indonesiaDateFormat($receiptPurchase->created->format('Y-m-d')) : '-' ;?></th>
                    <th width="15%"></th>
                    <th width="17%">Tanggal</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($receiptPurchase->date->format('Y-m-d'));?></th>
                </tr>

                <tr>
                     <th width="15%"></th>
                    <th width="2%"></th>
                    <th width="18%"></th>
                    <th width="15%"></th>
                    <th width="17%">Diubah Oleh</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"> <?= (!empty($receiptPurchase->modified_user->name)) ? $receiptPurchase->modified_user->name : '-' ?></th>
                </tr>


                    <td colspan="7">
                        <table class="table table-bordered table-detail" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="6"  style="background:#fafafa; text-align: center;">DATA BARANG</th>
                                </tr>
                                <tr>
                                   <th width="2%">No</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah PO</th>
                                    <th width="15%">Jumlah RP</th>
                                    <th>Harga</th> 
                                    
                                </tr>
                            </thead>

                            <tbody>
                           
                                <?php $no = 0; ?>

                                <?php if (!empty($receiptPurchase->receipt_purchases_details)): ?>
                                    <?php foreach ($receiptPurchase->receipt_purchases_details as $ReceiptPurchasesDetails): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?=h($ReceiptPurchasesDetails->receipt_purchase->code)?></td>
                                        <td><?= h($ReceiptPurchasesDetails->product->name) ?></td>
                                       <td><?=  h($ReceiptPurchasesDetails->purchase_orders_detail->qty) ?> <?= h($ReceiptPurchasesDetails->product->unit)?></td>
                                        <td><?= h($ReceiptPurchasesDetails->qty) ?> <?= h($ReceiptPurchasesDetails->product->unit)?></td>
                                        <td><?= h($ReceiptPurchasesDetails->purchase_orders_detail->price) ?> </td>
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
