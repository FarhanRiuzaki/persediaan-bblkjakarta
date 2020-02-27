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
            <th >ID</th>
            <th >Kode</th>
            <th >Nama Barang</th>
            <th >Stok Awal</th>
            <th >IN</th>
            <th >OUT</th>
            <th >Stok Akhir</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $total_saldo_awal = 0;
            $total_in       = 0;
            $total_out      = 0;
            $total_akhir    = 0;
            $no             = 1;
        ?>

        <?php foreach ($results as $key => $result):?>
            <?php
                $total_saldo_awal += $result->saldo_awal;
                $total_in += $result->in;
                $total_out += $result->out;
                $total_akhir += ($result->saldo_awal + $result->in - $result->out);
            ?>

             <tr>
                <td><?= $no++ ?></td>
                <td><?= $result->product_code ?></td>
                <td><?= $result->product_name ?><?=$result->unit;?></td>
                <td class="text-right"><?= $result->saldo_awal ?></td>
                <td class="text-right"><?= $result->in ?></td>
                <td class="text-right"><?= $result->out ?></td>
                <td class="text-right"><?= $result->saldo_awal + $result->in - $result->out ?></td>
            </tr>
        <?php endforeach;?>

        <td colspan="3" class="text-center"><?= 'Total' ?></td>
        <td class="text-right"><?= $total_saldo_awal ?></td>
        <td class="text-right"><?= $total_in ?></td>
        <td class="text-right"><?= $total_out ?></td>
        <td class="text-right"><?= $total_akhir ?></td>
    </tbody>
</table>