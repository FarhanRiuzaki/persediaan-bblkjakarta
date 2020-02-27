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
        .divFooter{
            position: fixed;
            margin-top: 100px; 
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
        <table width="100%;" style="font-size: 15px !important;">
            <tr>
                <td class="text-center">
                    <b>
                        FORMULIR PERMOHONAN PENGADAAN <br>
                        BARANG DAN JASA 
                    </b>
                </td>
            </tr>
        </table>
        <br>
        <!-- <table width="100%;" style="font-size: 15px !important;">
            <tr>
                <td width='170px'>No. Urut Permintaan</td>
                <td width='10px'>:</td>
                <td><?= $internalOrder->code ?></td>
            </tr>
            <tr>
                <td width='170px'>Bagian/Bidang/Su Bag/Seksi/Instalasi</td>
                <td width='10px'>:</td>
                <td><?= $internalOrder->institute->name ?></td>
            </tr>
        </table> -->
        
        <!-- body -->
        <table class='body' width="100%;" style="font-size: 15px !important;">
            <thead>
                <tr class="text-center">
                    <th width='30px'>No</th>
                    <th width='220px'>Nama Barang</th>
                    <th width='220px'>Unit / Jumlah</th>
                    <th width='220px'>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $no = 1;
                    foreach($productGroup  as $key => $val):
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td style="padding:5px"><?=$val['name'] ;?></td>
                    <td class="text-center" style="padding:5px"><?= (int)$val['total']  ;?> <?=$val['unit']  ;?></td>
                    <td></td>
                </tr>
                <?php endforeach;?>

            </tbody>
        </table>
        <br>
        <!-- FOOTER -->
        <table class='footer text-center' width="100%;" style="font-size: 15px !important;">
            <tr>
                <td rowspan="3" width='33%'>Tanggal: <?= $purchaseOrder->date->format('d-m-Y')?> <br>
                        Yang mengajukan:
                        <p style="padding: 30px"></p>
                        (.....................................................)
                </td>
                <td colspan="2"> Tangal dan disetujui oleh : </td>
            </tr>
            <tr>
                <td width='33%'>Kepala Bagian/Bidang/Sub Bidang/Seksi/instalasi/ULP/UKPBJ</td>
                <td width='33%'>Pejabat Pembuat Komitmen</td>
            </tr>
            <tr>
                <td><p style="padding: 25px"></p>(.....................................................)</td>
                <td><p style="padding: 25px"></p>(.....................................................)</td>
            </tr>
        </table>
    </div>
</div>
<div class="divFooter">FORM 42 : FBB</div>
