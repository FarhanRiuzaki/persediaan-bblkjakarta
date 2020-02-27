<style>
        *{
            font-size: 11px!important;
        }
        .m-portlet__body,
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
                    <th width="20%" colspan="3">
                    </th>
                    <th width="15%">&nbsp;</th>
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
                    <th width="13%"></th>
                    <th width="17%">Kode Permintaan Masuk</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$expendituresDistribution->code;?></th>
                </tr>

                <tr>
                    <th width="15%">Tanggal Dibuat</th>
                    <th width="2%">:</th>
                    <th width="18%"><?=$this->Utilities->indonesiaDateFormat($expendituresDistribution->created->format('Y-m-d'));?></th>
                    <th width="13%"></th>
                    <th width="17%">Tanggal</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"><?=$this->Utilities->indonesiaDateFormat($expendituresDistribution->date->format('Y-m-d'));?></th>
                </tr>

                <tr>
                    <th width="15%"></th>
                    <th width="2%"></th>
                    <th width="18%"></th>
                    <th width="13%"></th>
                    <th width="17%">Diubah Oleh</th>
                    <th width="2%">:</th>
                    <th width="18%" class="text-right"> <?= (!empty($expendituresDistribution->modified_user->name)) ? $expendituresDistribution->modified_user->name : '-' ?></th>
                </tr>

                <tr>

                    <td colspan="7">
                        <table class="table table-bordered table-detail" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="3"  style="background:#fafafa; text-align: center;">DATA BARANG</th>
                                </tr>
                                <tr>
                                    <th width="2%">No</th>
                                    <th>Nama Barang</th>
                                    <!-- <th width="20%">Harga</th> -->
                                    <th width="15%">Jumlah</th>
                                </tr>
                            </thead>

                            <tbody>
                                
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
                                        <!-- <td><?= h($expendituresDistributionsDetails->price) ?></td> -->
                                        <td><?= h($expendituresDistributionsDetails->qty) ?> <?= h($expendituresDistributionsDetails->product->unit) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <td colspan="2" class="text-right">Total</td>
                                <td class="onlyNumberWithoutSeparator"><?=$jml?></td>
                            </tfoot>
                        </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%'">
            <tr>
                <th style="text-align:center;">
                    <p>Diterima <br><br><br><br>
                    
                    .........................</p>
                </th>
                <th style="text-align:center;">
                    <p>Petugas Persediaan <br><br><br><br>
                    
                    .........................</p>
                </th>
            </tr>
        </table>
    </div>
</div>