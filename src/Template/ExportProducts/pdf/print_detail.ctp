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
            padding: 5px;

        }
        table.footer{
            border-collapse: collapse;            
        }
        .footer tr,.footer th,.footer td{
            border: 1px solid black;

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
                            DIREKTORAT JENDERAL PELAYANAN KESEHATAN <br>
                            BALAI BESAR LABORATORIUM KESEHATAN (BBLK) JAKARTA <br>
                        </b>
                        Jalan Percetakan Negara No.23 B Jakarta Pusat - 10560 <br>
                        Telp. ( 021 ) 4212524, 4245516, Fax ( 021 ) 42804339
                    </div>
                </td>
            </tr>
            </thead>
        </table>
        </div><br>
        
        <!-- body -->
        <table class='body' width="100%;" style="font-size: 15px !important;">
            <thead>
                <tr>
                    <th class="header-title" colspan="6">
                        DETAIL PENGAJUAN
                    </th>
                </tr>

                <tr>
                    <th class="text-center" width='15px'>NO</th>
                    <th class="text-center">Nama Barang</th>
                    <th class="text-center" width='10%'>Qty</th>
                    <th class="text-center">Tanggal Masuk</th>
                    <th class="text-center" width='10%'>Qty Masuk</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 0; foreach($exportProduct->export_products_details  as $key => $val): $no++;?>
                    <tr>
                        <td><?= $no?></td>
                        <td><?= $val->product->name  ?></td>
                        <td><?= $val->qty ?>  <?= $val->product->unit  ?></td>
                        <td><?= $val->date == null ? '-' : $val->date->format('Y-m-d') ?></td>
                        <td><?= $val->qty_in ?></td>
                        <?php if($val->status == 0): ?>
                            <td style='background-color: lightcoral;'> Belum Dibeli </td>
                        <?php elseif($val->status == 1): ?>
                            <td style='background-color: lightgreen;' >Dibeli </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
