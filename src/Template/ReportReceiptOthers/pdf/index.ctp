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
            <th class="header-title" colspan="7">
                <?=$titleModule;?>
            </th>
        </tr>

        <tr>
            <th width="2%">No.</th>
            <th width="5%">Kode Penerimaan Barang Lainnya</th>
            <th width="5%">Nama barang</th>
            <th width="5%">Jumlah Barang</th>
            <th width="5%">Harga Barang</th>
            <th width="5%">Subtotal</th>
        </tr>
    </thead>
    <tbody>

        <?php
            $total_qty = 0;
            $total_price = 0;
            $total_subtotal = 0;
        ?>
        <?php foreach ($results as $key => $result):?>
            <?php
                $total_qty += $result->qty;
                $total_price += $result->price;
                $total_subtotal += $result->subtotal;
            ?>

            <tr>
                <td><?=$key + 1;?></td>
                <td class="text-center"><?=$result->code1;?></td>
                <td>[<?=$result->code;?>] <?=$result->name;?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->qty, 5);?> <?=$result->unit;?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->price, 5);?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->subtotal, 5);?></td>
            </tr>
        <?php endforeach;?>

        <!-- TOTAL -->
        <tr>
            <td class="text-center" colspan="3">Total</td>
            <td class="text-right"><?=$this->Utilities->formatNumber($total_qty);?></td>
            <td class="text-right"><?=$this->Utilities->formatNumber($total_price);?></td>
            <td class="text-right"><?=$this->Utilities->formatNumber($total_subtotal);?></td>
        </tr>
    </tbody>
</table>