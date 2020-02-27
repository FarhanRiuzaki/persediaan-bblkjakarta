<?php if($this->request->query('print') == "webview"):?>
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
            <th class="header-title" colspan="8">
                <?=strtoupper($titleModule);?>
                <br>
                <?= $periode?>
            </th>
        </tr>

        <tr>
            <th width="1%">No.</th>
            <th width="10%">Kode Distribusi Barang</th>
            <th width="10%">Kode Permintaan User</th>
            <th width="10%">Kode Barang</th>
            <th width="30%">Nama Barang</th>
            <th width="10%">Jumlah Barang</th>
            <th width="10%">Harga Barang</th>
            <th width="19%">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach($results as $key => $result):?>
            <tr>
                <td><?=$no++;?></td>
                <td class="text-center"><?=$result->code1;?></td>
                <td class="text-center"><?=$result->code2;?></td>
                <td class="text-center"><?=$result->code;?></td>
                <td> <?=$result->name;?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->qty,5);?> <?=$result->unit;?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->price);?></td>
                <td class="text-right"><?=$this->Utilities->formatNumber($result->price * $result->qty);?></td>
            </tr>
        <?php endforeach;?>
        <?php if($no == 1):?>
            <tr>
                <td colspan='8' align='center'>Data tidak tersedia</td>
            </tr>
        <?php endif;?>
    </tbody>
</table>