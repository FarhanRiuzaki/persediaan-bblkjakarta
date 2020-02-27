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

<style type="text/css">
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
            <th>NO</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Stok</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php $subtotal = $total_subtotal = $no = 1 ?>
        <?php if (!empty($results)): ?>
            <?php foreach ($results as $result): ?>
            <?php $subtotal = $result->saldo_akhir * $result->product_price ?>
            <?php $total_subtotal += $subtotal ?>

            <tr>
                <td><?= h($no++) ?></td>
                <td><?= h($result->product_code) ?></td>
                <td><?= h($result->product_name) ?></td>
                <td class="text-right"><?= h($result->saldo_akhir) ?></td>
                <td class="text-right"><?= number_format($result->product_price) ?></td>
                <td class="text-right"><?= number_format($subtotal) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>

        <tr>
            <td colspan="5" class="text-center">TOTAL</td>
            <td class="text-right"><?= number_format($total_subtotal) ?></td>
        </tr>
    </tbody>
</table>