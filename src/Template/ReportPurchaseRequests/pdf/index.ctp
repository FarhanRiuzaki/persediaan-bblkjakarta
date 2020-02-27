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
            <th class="header-title" colspan="5">
                <?=$titleModule;?>
            </th>
        </tr>

        <tr>
            <th width="2%">No.</th>
            <th width="5%">Kode Permintaan Pembelian</th>
            <th width="5%">Nama Lembaga</th>
            <th width="5%">Nama Barang</th>
            <th width="5%">Jumlah Barang</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0; ?>
        <?php foreach ($results as $key => $result):?>
            <?php $total += $result->qty; ?>

            <tr>
                <td><?=$key + 1;?></td>
                <td class="text-center"><?=$result->code1;?></td>
                <td class="text-center"><?=$result->name1;?></td>
                <td>[<?=$result->code;?>] <?=$result->name;?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->qty, 5);?> <?=$result->unit;?></td>
            </tr>
        <?php endforeach;?>

        <!-- TOTAL -->
        <tr>
            <td class="text-center" colspan="4">Total</td>
            <td class="text-right"><?=$this->Utilities->formatNumber($total);?></td>
        </tr>

    </tbody>
</table>