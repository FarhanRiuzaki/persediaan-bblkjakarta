<?php if ($this->request->query('print') == 'webview'):?>
<style>
    .table-secondary{
        background:#fff;
    }
    .table100{
        width:1800px;
    }
    th{
        padding:5px;
    }
    td{
        padding:3px;
    }
</style>
<?php endif;?>

<style>
    .text-right {
        text-align: right;
    }
    .header-title {
        font-size: 16px;
    }
</style>

<table class="table100 ver2 table-secondary">
    <thead>
        <tr>
            <th class="header-title" colspan="8">
                <?=$titleModule;?>
            </th>
        </tr>

        <tr>
            <th width="2%">No.</th>
            <th width="5%">Kode Penerimaan Barang Reklarifikasi Masuk</th>
            <th width="10%">Kode Pengeluaran Reklarifikasi</th>
            <th width="5%">Nama barang</th>
            <th width="5%">Jumlah Barang Reklarifikasi Masuk</th>
            <th width="5%">Jumlah Barang Reklarifikasi Keluar</th>
            <th width="5%">Harga Barang Reklarifikasi Masuk</th>
            <th width="5%">Harga Barang Reklarifikasi Keluar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $key => $result):?>
            <tr>
                <td><?=$key + 1;?></td>
                <td class="text-center"><?=$result->code1;?></td>
                <td><?=$result->code_ex;?></td>
                <td>[<?=$result->code;?>] <?=$result->name;?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->qty, 5);?> <?=$result->unit;?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->qty_ex, 5);?> <?=$result->unit;?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->price) ?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->price_ex) ?></td>

            </tr>
        <?php endforeach;?>
    </tbody>
</table>