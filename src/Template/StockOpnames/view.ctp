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
                                    <h3>STOK OPNAME </h3>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th width="15%">Tanggal Dibuat</th>
                            <th width="2%">:</th>
                            <th width="18%"><?=$this->Utilities->indonesiaDateFormat($stockOpname->created->format('Y-m-d'));?></th>
                            <th width="18%"></th>
                            <th width="17%">Kode Stok Opname</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"><?=$stockOpname->code;?></th>
                        </tr>

                        <tr>
                            <th width="15%"></th>
                            <th width="2%"></th>
                            <th width="18%"></th>
                            <th width="18%"></th>
                            <th width="17%">Tanggal</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($stockOpname->date->format('Y-m-d'));?></th>
                        </tr>

                        <tr>
                            <td colspan="15">
                                <table class="table table-bordered table-detail">
                                    <thead>
                                        <tr>
                                            <th colspan="7" class="text-center" style="background:#fafafa;">DATA BARANG</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center" width="2%">No</th>
                                            <th style="text-align: center">Nama Barang</th>
                                            <th style="text-align: center" width="10%">Stok Sistem</th>
                                            <th style="text-align: center" width="10%">Stok Fisik</th>
                                            <th style="text-align: center" width="10%">Selisih</th>
                                            <th style="text-align: center" width="12%">Harga</th>
                                            <th style="text-align: center" >Keterangan</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 0; ?>
                                        
                                        <?php if (!empty($stockOpname->stock_opnames_details)): ?>
                                        <?php foreach ($stockOpname->stock_opnames_details as $stockOpnamesDetail): ?>
                                            <?php if(!empty($stockOpnamesDetail->product)):?>
                                                <tr  align='center'>
                                                    <td><?= h($no += 1) ?></td>
                                                    <td align='left'>[<?= h($stockOpnamesDetail->product->code)?>] <?= h($stockOpnamesDetail->product->name) ?></td>
                                                    <td><span class="onlyNumberWithoutSeparator"><?= h($stockOpnamesDetail->last_qty ) ?></span> <?= h($stockOpnamesDetail->product->unit) ?></td>
                                                    <td><span class="onlyNumberWithoutSeparator"><?= h($stockOpnamesDetail->qty) ?></span> <?= h($stockOpnamesDetail->product->unit) ?></td>
                                                    <td><span class="onlyNumberWithoutSeparator"><?= h($stockOpnamesDetail->qty_diff) ?></span> <?= h($stockOpnamesDetail->product->unit) ?></td>
                                                    <td align='right'><span class="onlyNumber"><?= h($stockOpnamesDetail->price) ?></td>
                                                    <td><?= h($stockOpnamesDetail->info) ?></td>
                                                </tr>
                                            <?php endif;?>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>