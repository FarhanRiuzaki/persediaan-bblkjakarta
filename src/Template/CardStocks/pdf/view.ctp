<html>
    <head>
        <style>
            body{
                padding-top:100px;
                font-size:12px;
            }
            header{
                position:fixed;
                z-index:9;
                top:0;
                left:0;
                width:100%;
            }
            header h3{
                margin:0px;
                text-align:right;
            }
            .table-header{
                width:300px;
                margin-left:auto;
            }
            .text-right{
                text-align:right;
            }
            /* .table-bordered{
                border:1px solid #000;
            } */
            .table-bordered {
            border: 1px solid #000; }
            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000; }
            .table-bordered thead th,
            .table-bordered thead td {
                border-bottom-width: 1px; }

            .table {
                border-collapse: collapse !important; }
                .table td,
                .table th {
                background-color: #fff !important; }
            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000 !important; }
            .table-dark {
                color: inherit; }
                .table-dark th,
                .table-dark td,
                .table-dark thead th,
                .table-dark tbody + tbody {
                border-color: #000; }
            .table .thead-dark th {
                color: inherit;
                border-color: #000; } 
            }
            .table th {
            padding: 0.5rem; }

            .table td {
            padding: 0.2rem; }

            .table thead th {
            border-bottom: 2px solid #333 !important; }

            .table-no-border th, .table-no-border td {
            border-top: none; }

            .table-bordered th, .table-bordered td {
            border-color: #333 !important; }
            
        </style>
    </head>
    <body>
        <header>
            <h3>KARTU STOK</h3>
            <table class="table-header">
                <tbody>
                    <tr>
                        <td width="55%">KODE BARANG</td>
                        <td width="5%">:</td>
                        <td class="text-right"><?=$product->code?></td>
                    </tr>
                    <tr>
                        <td>NAMA BARANG</td>
                        <td>:</td>
                        <td class="text-right"><?=$product->name?></td>
                    </tr>
                    <tr>
                        <td>SATUAN</td>
                        <td>:</td>
                        <td class="text-right"><?=$product->unit?></td>
                    </tr>
                </tbody>
            </table>
        </header>
        <br>
        <main>
            <table class="table table-bordered table-detail table-secondary" width="100%">
                <thead>
                    <tr>
                        <th width="" rowspan="2" class="text-center">No</th>
                        <th width="" rowspan="2" class="text-center">Tanggal</th>
                        <th width="" rowspan="2" class="text-center">Uraian</th>
                        <th width="" rowspan="2" class="text-center">Masuk</th>
                        <th width="" rowspan="2" class="text-center">Harga Beli</th>
                        <th width="" rowspan="2" class="text-center">Keluar</th>
                        <th width="" colspan="2" class="text-center">Stok</th>
                        <th width="" rowspan="2" class="text-center border-black">Paraf</th>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <th class='border-black'>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="">1</td>
                        <td><?= (!empty($saldoAwal['date']) ? $this->Utilities->indonesiaDateFormat($saldoAwal['date']->format('Y-m-d')) : '-') ;?></td>
                        <td class="">Saldo Awal</td>
                        <td class=""></td>
                        <td class="text-right onlyNumber"><?=(!empty($saldoAwal['price']) ? $saldoAwal['price'] : '' )?></td>
                        <td class=""></td>
                        <td class="text-right onlyNumberWithoutSeparator"><?=(!empty($saldoAwal['saldo_awal']) ? $saldoAwal['saldo_awal'] : '' )?></td>
                        <td class="text-right onlyNumber"><?=(!empty($saldoAwal['price']) ? $saldoAwal['price'] : 0 ) * (!empty($saldoAwal['saldo_awal']) ? $saldoAwal['saldo_awal'] : 0 )?></td>
                        <td class=""></td>
                    </tr>

                    <?php if (!empty($stocks)): ?>
                        <?php $no = 2 ?>
                        <?php $qty = (!empty($saldoAwal['saldo_awal'])) ? $saldoAwal['saldo_awal'] : 0 ?>

                        <?php foreach ($stocks as $stock): ?>
                        <?php if($stock->qty != 0) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?=$this->Utilities->indonesiaDateFormat($stock->date->format('Y-m-d'));?></td>
                            <td><?=$stock->uraian?></td>
                            <td class="text-right onlyNumberWithoutSeparator"><?= ($stock->type == 'IN') ? $stock->qty : 0 ?></td>
                            <td class="text-right onlyNumber"><?= $stock->price ?></td>
                            <td class="text-right onlyNumberWithoutSeparator"><?= ($stock->type == 'OUT') ? $stock->qty : 0 ?></td>
                            <td class="text-right onlyNumberWithoutSeparator"><?= $qty = (($stock->type == 'IN') ? $qty + $stock->qty : $qty - $stock->qty)?></td>
                            <td class="text-right onlyNumber"><?= $qty * $stock->price ?></td>
                            <td></td>
                        </tr>
                        <?php } ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </body>
</html>