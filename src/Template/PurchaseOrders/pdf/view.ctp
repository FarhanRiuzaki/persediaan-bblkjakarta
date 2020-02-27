<style>
        *{
            font-size: 12px!important;
        }
    </style>
    <div class="m-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-invoice">
            <thead>
                <tr>
                    <th width="20%" colspan="3">
                    </th>
                    <th width="15%">&nbsp;</th>
                    <th class="text-right" colspan="3">
                        <div class="information-bill">
                            <h3>PEMBELIAN PESANAN</h3>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
            <tr>
                    <th width="20%">Nama Pemasok</th>
                    <th width="">:</th>
                    <th width="18%" ><?=$purchaseOrder->supplier->name;?></th>
                    <th width="1%"></th>
                    <th width="17%">Nomor PO</th>
                    <th width="">:</th>
                    <th width="18%" class="text-right"><?=$purchaseOrder->nomor_po;?></th>
                </tr>
            
                <tr>
                    <th width="20%">Nomor SPK</th>
                    <th width="">:</th>
                    <th width="18%"><?=$purchaseOrder->nomor_spk;?></th>
                    <th width="1%"></th>
                    <th width="17%">Tanggal</th>
                    <th width="">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($purchaseOrder->date->format('Y-m-d'));?></th>
                </tr>
                <tr>
                    <th width="20%">Tanggal Dibuat</th>
                    <th width="">:</th>
                    <th width="18%"><?=(!empty($this->Utilities->indonesiaDateFormat($purchaseOrder->created->format('Y-m-d'))))? $this->Utilities->indonesiaDateFormat($purchaseOrder->created->format('Y-m-d')) : '-' ;?></th>
                     <th width="1%"></th>
                    <th width="17%">Diubah Oleh</th>
                    <th width="">:</th>
                    <th width="18%" class="text-right"> <?= (!empty($purchaseOrder->modified_user->name)) ? $purchaseOrder->modified_user->name : '-' ?></th>
                </tr>
                <tr>
                    <td colspan="15">
                    <table class="table table-bordered table-detail" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="6"  style="background:#fafafa; text-align: center;">DATA BARANG</th>
                                </tr>
                                <tr>
                                    <th width="2%">No</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Prs</th>
                                    <th width="15%">Jumlah Po</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>

                            <tbody>
                                
                                <?php $no = 0; ?>

                                <?php if (!empty($purchaseOrder->purchase_orders_details)): ?>
                                    <?php $total = 0; foreach ($purchaseOrder->purchase_orders_details as $PurchaseOrdersDetails): ?>
                                    <tr>
                                        <td><?= h($no += 1) ?></td>
                                        <td><?=h($PurchaseOrdersDetails->purchase_requests_detail->purchase_request->code)?></td>
                                        <td><?= h($PurchaseOrdersDetails->product->name) ?></td>
                                       <td><?= h($PurchaseOrdersDetails->purchase_requests_detail->qty) ?> <?= h($PurchaseOrdersDetails->product->unit)?></td>
                                        <td><?= h($PurchaseOrdersDetails->qty) ?> <?= h($PurchaseOrdersDetails->product->unit)?></td>
                                        <td><?= h($PurchaseOrdersDetails->price) ?> </td>
                                    </tr>
                                    <?php $total += $PurchaseOrdersDetails->price; endforeach; ?>
                                <?php endif; ?>
                                <tr>
                                    <td colspan='5' align='right'>Total</td>
                                    <td><?= h($total)?></td>
                                </tr>
                            </tbody>
                                
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
