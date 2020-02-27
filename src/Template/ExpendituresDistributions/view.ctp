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
                                <a href="<?= $this->Url->build(['action' => 'view', $expendituresDistribution->id, '?' => ['print' => 'pdf']])  ?>" class="kt-nav__link no-print btn-print " target="_BLANK" data-print="pdf">
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
                                    <h3>DISTRIBUSI BARANG</h3>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th width="15%">Nama Unit Instalasi</th>
                            <th width="2%">:</th>
                            <th width="18%"><?=$expendituresDistribution->institute->name;?></th>
                            <th width="18%"></th>
                            <th width="17%">Kode Permintaan Masuk</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"><?=$expendituresDistribution->code;?></th>
                        </tr>

                        <tr>
                            <th width="15%">Tanggal Dibuat</th>
                            <th width="2%">:</th>
                            <th width="18%"><?=$this->Utilities->indonesiaDateFormat($expendituresDistribution->created->format('Y-m-d'));?></th>
                            <th width="18%"></th>
                            <th width="17%">Tanggal</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($expendituresDistribution->date->format('Y-m-d'));?></th>
                        </tr>

                        <tr>
                            <th width="15%"></th>
                            <th width="2%"></th>
                            <th width="18%"></th>
                            <th width="18%"></th>
                            <th width="17%">Diubah Oleh</th>
                            <th width="2%">:</th>
                            <th width="18%" class="text-right"> <?= (!empty($expendituresDistribution->modified_user->name)) ? $expendituresDistribution->modified_user->name : '-' ?></th>
                        </tr>


                        <tr>
                            <td colspan="15">
                                <table class="table table-bordered table-detail">
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center" style="background:#fafafa;">DATA BARANG</th>
                                        </tr>
                                        <tr>
                                            <th width="2%">No</th>
                                            <th>Nama Barang</th>
                                            <!-- <th width="20%">Harga</th> -->
                                            <th width="15%">Jumlah</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    </tbody>
                                        <?php
                                            $no = 0;
                                            $jml = 0;
                                        ?>

                                        <?php if (!empty($expendituresDistribution->expenditures_distributions_details)): ?>
                                            <?php
                                                foreach ($expendituresDistribution->expenditures_distributions_details as $expendituresDistributionsDetails): 
                                                    $jml += $expendituresDistributionsDetails->qty;
                                            ?>
                                            <tr>
                                                <td><?= h($no += 1) ?></td>
                                                <td><?= h($expendituresDistributionsDetails->product->name) ?></td>
                                                <!-- <td class="onlyNumber"><?= h($expendituresDistributionsDetails->price) ?></td> -->
                                                <td><span class="onlyNumberWithoutSeparator"><?= h($expendituresDistributionsDetails->qty) ?></span> <?= h($expendituresDistributionsDetails->product->unit) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    <tbody>
                                    <tfoot>
                                        <td colspan="2" class="text-right">Total</td>
                                        <td class="onlyNumberWithoutSeparator"><?=$jml?></td>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                    <style>
                        @media print{
                            .m-content--skin-apps{
                                background-color: transparent!important;
                            }
                            .m-portlet__body,
                            .table-invoice{
                                margin-top: 0!important;
                                padding-top: 0!important;
                            }
                            .table-invoice *{
                                font-size: 15px!important;
                            }
                        }
                    </style>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-center">
                                <p>Petugas Persediaan <br><br><br><br>
                                
                                .........................</p>
                            </td>
                            <td colspan="3"></td>
                            <td colspan="2" class="text-center">
                                <p>Diterima <br><br><br><br>
                                
                                .........................</p>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>