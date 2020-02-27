var Utils = function(){
    var arrayMonth = function(){
        var month = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return month;
    }
    var categoriesIn = function(bt){
        var type = ["", "Pembelian", "Hibah", "Transfer", "Lainnya"];
        return type[bt];
    }
    var categoriesOut = function(bt){
        var type = ["", "Hibah", "Transfer", "Lainnya"];
        return type[bt];
    }
    var dateIndonesia = function(date, month = true,time = false){
        $output = "";
        if(date != null){
            var newDate = new Date(date)
            var tahun = newDate.getYear();
            tahun = (tahun < 1000 ) ? tahun + 1900 : tahun;
            var bulan = newDate.getMonth() + 1;
            var tanggal  = newDate.getDate(); 
            if(month == false){
                if(bulan < 10){
                    bulan = "0" + bulan;
                }
                var output = tanggal + "-" + bulan + "-" + tahun;
            }else{
                bulan = arrayMonth()[bulan];
                var output = tanggal + " " + bulan + " " + tahun;
            }
            if(time == true){
                var jam = newDate.getHours();
                if(jam < 10){
                    jam = "0" + jam;
                }
                var minutes = newDate.getMinutes();
                if(minutes < 10){
                    minutes = "0" + minutes;
                }
                output = output + " " + jam + ":" + minutes;
            }
        }
        
        return output;
    };
    var numberInputFormat = function(){
        $(".onlyNumber,input[textnumber='true']").number( true, 2 );
        $(".onlyNumberWithoutComa,input[textnumberWithoutComa='true']").number( true, 0 );
        $(".onlyNumberWithoutSeparator,input[textnumberWithoutSeparator='true']").number( true, 0,'');
    }
    var numberLabelFormat = function(numberVal){
        var numberVal = $.number(numberVal, 2);
        var explode   = numberVal.split(".");
        console.log(explode);
    }
    var showAlertMsg = function(form, type, msg) {
        var alert = $('<div class="m-alert m-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\
            <span></span>\
        </div>');

        form.find('.alert').remove();
        alert.prependTo(form);
        alert.animateClass('fadeIn animated');
        alert.html(msg);
    }
    var datePickerInput = function(){
        $("#date-time-picker,.date-time-picker,input[datetimepicker='true']").datetimepicker({todayHighlight:!0,autoclose:!0,pickerPosition:"bottom-left",format:"dd-mm-yyyy hh:ii"})
        $("#date-picker,.date-picker,input[datepicker='true']").datepicker({
            todayHighlight:!0,orientation:"bottom left",templates:{
                leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'
            },
            autoclose : true,
            format : "dd-mm-yyyy"
        })
        $("#date-picker-report,.date-picker-report,input[datepicker-report='true']").datepicker({
            todayHighlight:!0,orientation:"bottom left",templates:{
                leftArrow:'<i class="la la-angle-left"></i>',rightArrow:'<i class="la la-angle-right"></i>'
            },
            autoclose : true,
            format : "yyyy-mm",
            startView: 2,
            minViewMode: 1,
            maxViewMode: 2
        })
    }
    return{
        dateIndonesia : function(date, month = true,time = false){
            return dateIndonesia(date,month,time);
        },
        categoriesIn : function(bt) {
            return categoriesIn(bt);
        },
        categoriesOut : function(bt) {
            return categoriesOut(bt);
        },
        statusLabel : function(status){
            if(status == true){
                return '<span class="kt-badge kt-badge--primary  kt-badge--inline kt-badge--pill">ENABLED</span>';
            }else{
                return '<span class="kt-badge kt-badge--danger  kt-badge--inline kt-badge--pill">DISABLED</span>';
            }
        },
        statusLabelActive : function(status){
            if(status == true){
                return '<span class="kt-badge kt-badge--primary  kt-badge--inline kt-badge--pill">AKTIF</span>';
            }else{
                return '<span class="kt-badge kt-badge--danger  kt-badge--inline kt-badge--pill">TIDAK AKTIF</span>';
            }
        },
        statusLabelIO: function(status) {
            if (status == 0) {
                return '<span class="kt-badge  kt-badge--brand kt-badge--inline kt-badge--pill">PENDING</span>';
            }
            if (status == 1) {
                return '<span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">ON PROCESS</span>';
            }
            if (status == 2) {
                return '<span class="kt-badge kt-badge--success  kt-badge--inline kt-badge--pill">SELESAI</span>';
            }
            if (status == 3) {
                return '<span class="kt-badge kt-badge--warning  kt-badge--inline kt-badge--pill">PENDING APPROVAL</span>';
            }
        },
        statusPP: function(status) {
            if (status == 0) {
                return '<span class="kt-badge  kt-badge--brand kt-badge--inline kt-badge--pill">PENDING PPK</span>';
            }
            if (status == 1) {
                return '<span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">DISETUJUI PPK</span>';
            }
            if (status == 2) {
                return '<span class="kt-badge kt-badge--danger  kt-badge--inline kt-badge--pill">DITOLAK PPK</span>';
            }
        },
        statusRefTable: function(status) {
            if (status == "expenditures_distributions_details") {
                return "Distribusi Barang";
            } else if (status == "expenditures_grants_details") {
                return "Barang Hibah";
            } else if (status == "expenditures_others_details") {
                return "Barang Lainnya";
            } else if (status == "expenditures_reclarifications_details") {
                return "Reklarifikasi";
            } else if (status == "expenditures_transfers_details") {
                return "Barang Transfer Keluar";
            } else if (status == "receipt_grants_details") {
                return "Barang Hibah";
            } else if (status == "receipt_others_details") {
                return "Barang Lainnya";
            } else if (status == "receipt_purchases_details") {
                return "Barang Pembelian";
            } else if (status == "receipt_reclarifications_details") {
                return "Barang Reklarifikasi Masuk";
            } else if (status == "receipt_transfers_details") {
                return "Barang Transfer Masuk";
            } else if (status == "stock_opnames_details") {
                return "Stok Opname";
            } else if (status == "init_stocks_details") {
                return "Saldo Awal";
            }
        },
        labelNumber: function(number) {
            var numberVal = $.number(number, 2);
            return numberVal;
        },
        labelNumberWithoutSeparator: function(number) {
            var numberVal = $.number(number);
            return numberVal;
        },
        initNumber : function(){
            numberInputFormat()
        },
        showAlertMsg : function(form, type, msg){
            showAlertMsg(form, type, msg)
        },
        init : function(){
            numberInputFormat();
            datePickerInput();
        }
    }
}();
$(document).ready(function(){
    Utils.init();
})
