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
            <th class="header-title" colspan="6">
                <?=$titleModule;?>
            </th>
        </tr>

        <tr>
            <th width="2%">No.</th>
            <th width="5%">Kode Permintaan User</th>
            <th width="5%">Nama Instalasi</th>
            <th width="5%">Nama Barang</th>
            <th width="5%">Unit Barang</th>
            <th width="5%">Jumlah Barang</th>
        </tr>
    </thead>
    <tbody>

        <?php  $no = 1;  foreach($groupProduct as $key =>$g):?>
            <tr style="background-color: #dfe6e9">
                <th><?= $no++ ?></th>
                <th colspan="5"><?= $g->name?></th>
            </tr>
            <?php 
            $total = 0;
            $unit = '';
            foreach ($results as $key => $r):?>
                <?php if($g->product_id == $r->product_id):?>
                    <tr>
                        <td class="noBorder"></td>
                        <td><?=$r->code1;?></td>
                        <td><?=$r->name1;?></td>
                        <td>[<?=$r->code;?>] <?=$r->name;?></td>
                        <td><?=$r->unit;?></td>
                        <td><?=$r->qty;?></td>
                    </tr>
                <?php 
                $total += $r->qty;
                endif; ?>
            <?php endforeach;?>
            <tr >
                <td colspan="5" class="text-right">Total:</td>
                <td  class="text-center"><p><b> <?= $total ?> </b> <?= $g->unit?></p></td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>