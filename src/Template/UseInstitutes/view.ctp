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
                                    <h3>PEMAKAIAN BARANG INSTALASI</h3>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th width="15%">Parameter</th>
                            <th width="2%">:</th>
                            <th width="18%"><?=$useInstitute->inspection_parameter->name;?></th>
                            <th width="18%"></th>
                            <th width="17%">Kode Pemakaian Barang </th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"><?=$useInstitute->code;?></th>
                        </tr>

                        <tr>
                            <th width="15%">Tanggal Dibuat</th>
                            <th width="2%">:</th>
                            <th width="18%"><?=$this->Utilities->indonesiaDateFormat($useInstitute->created->format('Y-m-d'));?></th>
                            <th width="18%"></th>
                            <th width="17%">Tanggal</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($useInstitute->date->format('Y-m-d'));?></th>
                        </tr>

                        <tr>
                            <td colspan="15">
                                <table class="table table-bordered table-detail">
                                    <thead>
                                        <tr>
                                            <th colspan="4" class="text-center" style="background:#fafafa;">DATA BARANG</th>
                                        </tr>
                                        <tr>
                                            <th width="2%">No</th>
                                            <th>Nama Barang</th>
                                            <th width="20%">Satuan</th>
                                            <th width="15%">Jumlah</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php $no = 0; ?>

                                        <?php if (!empty($useInstitute->use_institutes_details)): ?>
                                            <?php foreach ($useInstitute->use_institutes_details as $use_institutes_detail): ?>
                                            <tr>
                                                <td><?= h($no += 1) ?></td>
                                                <td><?= h($use_institutes_detail->product->name) ?></td>
                                                <td><?= h($use_institutes_detail->product->product_unit->unit) ?></td>
                                                <td><span class="onlyNumberWithoutSeparator"><?= h($use_institutes_detail->qty) ?></span></td>
                                            </tr>
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