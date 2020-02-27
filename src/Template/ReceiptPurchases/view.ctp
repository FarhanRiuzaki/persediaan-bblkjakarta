<style>
    @media print{
        body *{
            visibility: hidden;
        }
        #section-to-print, #section-to-print * {
            visibility: visible;
        }
        #section-to-print {
            /* position: absolute; */
            left: 0;
            top: 0;

        }
        .btn-show-modal{
            display: none;
        }
        body{
            background-color: transparent!important;
        }
        .kt-portlet__body,
        .table-invoice{
            margin-top: 0!important;
            padding-top: 0!important;
        }
        .table-invoice *{
            font-size: 15px!important;
        }
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="kt-portlet kt-portlet--height-fluid">

            <div class="kt-portlet__head" id='hide'>
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
    						<?=$titlesubModule;?>
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="#" class="btn btn-outline-primary  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Print
                        </a>
                        <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(837px, 46px, 0px); top: 0px; left: 0px; will-change: transform;">
                        <ul class="kt-nav">
                            <li class="kt-nav__item">
                                <a href="#" class="kt-nav__link no-print"  onclick="window.print()">
                                    <i class="kt-nav__link-icon flaticon2-line-chart"></i>
                                    <span class="kt-nav__link-text">Print View</span>

                                </a>
                            </li>
                            <li class="kt-nav__item">
                                <a href="<?= $this->Url->build(['action' => 'view', $receiptPurchase->id, '?' => ['print' => 'pdf']])  ?>" class="kt-nav__link no-print btn-print " target="_BLANK" data-print="pdf">
                                    <i class="kt-nav__link-icon flaticon2-send"></i>
                                    <span class="kt-nav__link-text">Print PDF</span>
                                </a>
                            </li>
                        </ul>			
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body" id='section-to-print'>
                <table class="table table-invoice">
                    <thead>
                        <tr>
                            <th width="40%" colspan="3">
                                
                            </th>
                            <th width="10%">&nbsp;</th>
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
                            <th width="18%"></th>
                            <th width="17%">Nomor SPK</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"><?=$receiptPurchase->purchase_order->nomor_spk;?></th>
                        </tr>

                        <tr>
                            <th width="15%">Tanggal Dibuat</th>
                            <th width="2%">:</th>
                            <th width="18%"><?=(!empty($this->Utilities->indonesiaDateFormat($receiptPurchase->created->format('Y-m-d'))))? $this->Utilities->indonesiaDateFormat($receiptPurchase->created->format('Y-m-d')) : '-' ;?></th>
                            <th width="18%"></th>
                            <th width="17%">Tanggal</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($receiptPurchase->date->format('Y-m-d'));?></th>
                        </tr>

                        <tr>
                            <th width="15%"></th>
                            <th width="2%"></th>
                            <th width="18%"></th>
                            <th width="18%"></th>
                            <th width="17%">Diubah Oleh</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"> <?= (!empty($receiptPurchase->modified_user->name)) ? $receiptPurchase->modified_user->name : '-' ?></th>
                        </tr>


                        <tr>
                            <td colspan="15">
                                <table class="table table-bordered table-detail">
                                    <thead>
                                        <tr>
                                            <th colspan="6" class="text-center" style="background:#fafafa;">DATA BARANG</th>
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
                                        
                                    </tbody>
                                        <?php $no = 0; ?>

                                        <?php if (!empty($receiptPurchase->receipt_purchases_details)): ?>
                                            <?php foreach ($receiptPurchase->receipt_purchases_details as $ReceiptPurchasesDetails): ?>
                                            <tr>
                                                <td><?= h($no += 1) ?></td>
                                                <td><?=h($ReceiptPurchasesDetails->receipt_purchase->code)?></td>
                                                <td><?= h($ReceiptPurchasesDetails->product->name) ?></td>
                                                <td><span class="onlyNumberWithoutSeparator"><?= h($ReceiptPurchasesDetails->purchase_orders_detail->qty) ?></span> <?= h($ReceiptPurchasesDetails->product->unit)?></td>
                                                <td><span class="onlyNumberWithoutSeparator"><?= h($ReceiptPurchasesDetails->qty) ?></span> <?= h($ReceiptPurchasesDetails->product->unit)?></td>
                                                <td class="onlyNumber"><?= h($ReceiptPurchasesDetails->purchase_orders_detail->price)?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <tbody>
                                        
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table >
                    <tr>
                        <th width="50%">TTD Kepala Bagian,</th>
                        <th width="0%"></th>
                        <th width="50%" class="text-right">TTD Pembuat Penerimaan,</th>
                    </tr>
                
                </table>
                <br><br><br><br>
                <table >
                    <tr>
                        <td width="50%">.....................................</td>
                        <th width="71%"></th>
                        <td width="50%" class="text-right">..................................................</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>