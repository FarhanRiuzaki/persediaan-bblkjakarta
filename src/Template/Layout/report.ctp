<!DOCTYPE html>
<html lang="en">
<head>
  <title><?=$defaultAppSettings['App.Name'];?><?=(!empty($titleModule) ? ' | '.$titleModule : '');?></title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="<?=$defaultAppSettings['App.Name'];?>">
    <meta name="keywords" content="<?=$defaultAppSettings['App.Name'];?>">
    <meta name="author" content="Iqbal Ardiansyah">
    <link rel="shortcut icon" href="<?=$this->request->base;?><?=$defaultAppSettings['App.Favico'];?>" />
    <!-- Favicon icon -->
    <style>
            @import url('https://fonts.googleapis.com/css?family=Roboto');
            .table-primary{
                font-size:18px;
                font-weight:bold;
                font-family: 'Roboto', sans-serif;
                width:100%;
                border-collapse: collapse;
            }
            .table-primary > thead > tr > th{
                text-align:center;
                border-bottom:3px double #ccc;
                text-transform:uppercase;
                padding:5px;
                padding-bottom:15px;
            }
            .table-secondary{
                font-size:11px;
                font-weight:100;
                font-family: 'Roboto', sans-serif;
                width:100%;
                border-collapse: collapse;
                border: 1px solid #ccc;
                margin-top:5px;
            }
            .table-secondary > thead > tr > th{
                vertical-align:middle;
                border-bottom:1px solid #ccc;
                border-right:1px solid #ccc;
                text-align:center;
                background:#fafafa;
                padding:5px 10px;;
            }
            .table-secondary > tbody > tr > td{
                vertical-align:top;
                border-bottom:1px solid #ccc;
                border-right:1px solid #ccc;
                padding:2px 10px;;
            }
            .text-center{
                text-align:center !important;
            }
            .text-right{
                text-align:right !important;
            }
        </style>

</head>
    <body class="">
        <?=$this->fetch('content');?>
    </body>
</html>