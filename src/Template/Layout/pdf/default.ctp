<html>
    <head>
        <title><?=$titleModule;?></title>
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
                /*text-transform:uppercase;*/
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
            .table-secondary > tbody > tr > th{
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
            .header-title{
                font-size:14px;
                padding:15px;
            }
            table, tr, td, th, tbody, thead, tfoot {
                page-break-inside: avoid !important;
            }
            .col-tr-primary th{
                background : #000 !important;
            }
        </style>
    </head>
    <body>
        <?=$this->fetch('content');?>
    </body>
</html>