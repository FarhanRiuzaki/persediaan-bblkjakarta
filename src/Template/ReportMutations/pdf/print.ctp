<style>
        .kt-portlet__body,
        .table-invoice{
            margin-top: 0!important;
            padding-top: 0!important;
        }
        .table-invoice *{
            font-size: 11px!important;
        }
        .header table{
            border-collapse: collapse;
        }
        .th-bd{
            border: 1px solid black;
            padding-top: 30px;
            padding-bottom: 30px;
        }
        table.body{
            border-collapse: collapse;            
        }
        .body tr,.body th,.body td{
            border: 1px solid black;

        }
        table.footer{
            border-collapse: collapse;            
        }
        .footer tr,.footer th,.footer td{
            border: 1px solid black;

        }
        tr:nth-child(even) {
            background-color: #f2f2f2
        }

        #datatableStok{
            border-collapse: collapse;
        }
        td{
            padding: 5px;
        }
    </style>
    <div class="m-portlet__body">
        <div class="header">
        <table class="text-center header" width="100%;" style="font-size: 20px;">
            <thead>
            <tr>
                <td class="th-bd"><img type="image" src="
                <?= $this->Url->build('/img/logo-bblk.png',true);?>
                " max-width="100px"  height="80px"></td>
                <td class="text-center th-bd">
                    <div style='font-size: 15px !important'>
                        <b style='font-size: 15px !important'>
                            KEMENTERIAN KESEHATAN R.I <br>
                            DIREKTORAT JENDERAL BINA PELAYANAN MEDIK <br>
                            BALAI BESAR LABORATOTIUM KESEHATAN (BBLK) JAKARTA <br>
                        </b>
                        Jalan Percetakan Negara No.23 B Jakarta Pusat - 10560 <br>
                        Telp. ( 021 ) 4212524, 4245516, Fax ( 021 ) 42804339
                    </div>
                </td>
            </tr>
            </thead>
        </table>
        </div><br>
        <table width="100%;" style="font-size: 15px !important;">
            <tr>
                <td class="text-center">
                    <b>
                        STOK BARANG<br>
                    </b>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%;" style="font-size: 13px !important;" class="table table-striped body" id='datatableStok'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <!-- <th>Stok Awal</th> -->
                    <th>IN</th>
                    <th>OUT</th>
                    <th>Stok Akhir</th>
                </tr>
            </thead>

            <tbody>

                <?php if (!empty($results)): ?>
                    <?php $no = 1; foreach ($results as $result): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= h($result->product_code) ?></td>
                        <td><?= h($result->product_name) ?></td>
                        <td><?= h($result->unit) ?></td>
                        <!-- <td><?= h($result->saldo_awal) ?></td> -->
                        <td><?= h($result->in) ?></td>
                        <td><?= h($result->out) ?></td>
                        
                        <?php if($result->saldo_akhir < 0):?>
                        <td  class="bg-warning text-white"><?= h($result->saldo_akhir) ?></td>
                        <?php elseif($result->saldo_akhir <= $result->min_unit):?>
                        <td  class="bg-danger text-white"><?= h($result->saldo_akhir) ?></td>
                        <?php else:?>
                            <td  class="bg-info text-white"><?= h($result->saldo_akhir) ?></td>
                        <?php endif;?>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>
