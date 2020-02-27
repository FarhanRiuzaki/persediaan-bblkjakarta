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
            <th class="header-title" colspan="9">
                <?=$titleModule;?>
            </th>
        </tr>

        <tr>
            <th width="2%">No.</th>
            <th width="5%">Nama Pemasok</th>
            <th width="5%">Kode Permintaan Pembelian</th>
            <th width="5%">Nomor SPK</th>
            <th width="5%">Nama Barang</th>
            <th width="5%">Jumlah Barang Permintaan Pembelian</th>
            <th width="5%">Jumlah Barang Pesanan Pembelian</th>
            <th width="5%">Harga Barang Pesanan Pembelian</th>
            <th width="5%">Subtotal</th>
        </tr>
    </thead>
    <tbody>

        <?php
            $total_qty_pr = 0;
            $total_qty = 0;
            $total_price = 0;
            $total_subtotal = 0;
        ?>

        <?php foreach ($results as $key => $result):?>
            <?php
                $total_qty_pr += $result->qty_pr;
                $total_qty += $result->qty;
                $total_price += $result->price;
                $subtotal   = $result->qty * $result->price;
                
                $total_subtotal += $subtotal;

            ?>

            <tr>
                <td><?=$key + 1;?></td>
                <td class="text-center"><?=$result->nama_pemasok;?></td>
                <td class="text-center"><?=$result->code_ex;?></td>
                <td><?=$result->nomor_spk;?></td>
                <td>[<?=$result->code;?>] <?=$result->name;?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->qty_pr, 5);?> <?=$result->unit;?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->qty, 5);?> <?=$result->unit;?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->price) ?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($subtotal) ?></td>
            </tr>

        <?php endforeach;?>

        <td colspan="5" class="text-center">Total</td>
        <td class="text-right"><?=$this->Utilities->formatNumber($total_qty_pr, 5);?></td>
        <td class="text-right"><?=$this->Utilities->formatNumber($total_qty, 5);?></td>
        <td class="text-right"><?=$this->Utilities->formatNumber($total_price) ?></td>
        <td class="text-right"><?=$this->Utilities->formatNumber($total_subtotal) ?></td>
    </tbody>
</table>