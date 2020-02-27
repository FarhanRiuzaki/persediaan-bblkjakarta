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
            <th>No</th>
            <th>No Invoice</th>
            <th>Parameter</th>
            <th>Tanggal</th>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th>Jumlah</th>
        </tr>
    </thead>

    <tbody>
        <?php $no = 1; ?>
        <?php foreach($results as $result): ?>
            <?php foreach($result->use_institutes_details as $use_institutes_detail): ?>
                <tr>
                    <td rowspan=""><?=$no++?></td>
                    <td rowspan=""><?=$result->registration->no_invoice?></td>
                    <td rowspan=""><?=$result->inspection_parameter->name?></td>
                    <td rowspan=""><?=$this->Utilities->indonesiaDateFormat($result->date->format('Y-m-d'))?></td>
                    <td><?= $use_institutes_detail->product->name ?></td>
                    <td><?= $use_institutes_detail->product->product_unit->unit ?></td>
                    <td class="text-right"><?= number_format($use_institutes_detail->qty) ?></td>
                </tr>
            <?php endforeach ?>

        <?php endforeach ?>
    </tbody>
</table>