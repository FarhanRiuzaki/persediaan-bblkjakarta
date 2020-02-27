<?php
namespace App\View\Helper;

use Cake\View\Helper;

class UtilitiesHelper extends Helper
{
    public $helpers = ['Url'];
    public function monthArray()
    {
        $array = array (1 =>   'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                );
        return $array;
    }
    public function categorieIn()
    {
        $array = array (1 =>   'Pembelian',
                    'Hibah',
                    'Transfer',
                    'Lainnya'
                );
        return $array;
    }
    public function categorieOut()
    {
        $array = array (1 =>   'Distribusi',
                    'Hibah',
                    'Transfer',
                    'Lainnya'
                );
        return $array;
    }
    
    public function indonesiaDateFormat($tanggal,$time = false,$toIndonesia = true)
    {
        $bulan = $this->monthArray();
        $datepick = explode(" ", $tanggal);
        $split = explode("-", $datepick[0]);
        if($toIndonesia == true){
            if($time == false){
                return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
            }else{
                return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0]. ' ' . $datepick[1];
            }
        }else{
            if($time == false){
                return $split[2] . '-' . $split[1]. '-' . $split[0];
            }else{
                return $split[2] . '-' . $split[1] . '-' . $split[0].' '.$datepick[1];
            }
        }
        
    }  
    
    public function sideBarArrayCheck( array $array, $keys ) {
        $count = 0;
        if ( ! is_array( $keys ) ) {
            $keys = func_get_args();
            array_shift( $keys );
        }
        foreach ( $keys as $key ) {
            if ( isset( $array[$key] ) || array_key_exists( $key, $array ) ) {
                $count ++;
            }
        }
    
        return $count;
    }
    public function statusLabelIo($status, $type = '', $request = '', $institute_id = '')
    {
        // dd($type);
        if ($type == 'IO') {
            if ($status == 0) {
                return '<a href="' . $this->Url->build('/expenditures-distributions/add/') . $institute_id . '/' . $request . '"><span class="kt-badge  kt-badge--brand kt-badge--inline kt-badge--pill">PENDING</span></a>';
            }
            if ($status == 1) {
                return '<a href="' . $this->Url->build('/expenditures-distributions/add/') . $institute_id . '/' . $request . '"><span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">ON PROCESS</span></a>';
            }
            if ($status == 3) {
                return '<a href="' . $this->Url->build('/approval/add/') . $institute_id . '/' . $request . '"><span class="kt-badge  kt-badge--warning kt-badge--inline kt-badge--pill">PENDING APPROVAL</span></a>';
            }
            if ($status == 4) {
                return '<a href="' . $this->Url->build('/internal-orders/view/') . $request . '"><span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill">PENDING SELESAI</span></a>';
            }
        } elseif ($type == 'PR') {
            if ($status == 0) {
                return '<a href="' . $this->Url->build('/purchase-submision/add/') . $institute_id . '/' . $request . '"><span class="kt-badge  kt-badge--brand kt-badge--inline kt-badge--pill">PENDING</span></a>';
            }
            // if ($status == 1) {
            //     return '<a href="' . $this->Url->build('/purchase-submision/add/') . $institute_id . '/' . $request . '"><span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">ON PROCESS</span></a>';
            // }

        }
        if ($type == 'PS') {
            if ($status == 0) {
                return '<a href="' . $this->Url->build('/purchase-submision/view/') . $request . '"><span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">PENDING PPK</span></a>';
            }

        }
        if ($type == 'POview') {
            if ($status == 0) {
                return '<a href="' . $this->Url->build('/purchase-requests/view/') . $request . '"><span class="kt-badge  kt-badge--brand kt-badge--inline kt-badge--pill">PENDING</span></a>';
            }
            if ($status == 1) {
                return '<a href="' . $this->Url->build('/purchase-requests/view/') . $request . '"><span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">ON PROCESS</span></a>';
            }
            if ($status == 2) {
                return '<a href="' . $this->Url->build('/purchase-requests/view/') . $request . '"><span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">SELESAI</span></a>';
            }
        } elseif ($type == 'IOview') {
            if ($status == 0) {
                return '<a href="' . $this->Url->build('/internal-orders/view/') . $request . '"><span class="kt-badge  kt-badge--brand kt-badge--inline kt-badge--pill">PENDING</span></a>';
            }
            if ($status == 1) {
                return '<a href="' . $this->Url->build('/internal-orders/view/') . $request . '"><span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">ON PROCESS</span></a>';
            }
            if ($status == 2) {
                return '<a href="' . $this->Url->build('/internal-orders/view/') . $request . '"><span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">SELESAI</span></a>';
            }
            if ($status == 3) {
                return '<a href="' . $this->Url->build('/internal-orders/view/') . $request . '"><span class="kt-badge  kt-badge--warning kt-badge--inline kt-badge--pill">PENDING APPROVAL</span></a>';
            }
            if ($status == 4) {
                return '<a href="' . $this->Url->build('/internal-orders/view/') . $request . '"><span class="kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill">PENDING SELESAI</span></a>';
            }
        } else {
            if ($status == 0) {
                return '<span class="kt-badge  kt-badge--brand kt-badge--inline kt-badge--pill">PENDING</span>';
            }
            if ($status == 1) {
                return '<span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">ON PROCESS</span>';
            }
            if ($status == 2) {
                return '<span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">SELESAI</span>';
            }
        }
    }
    public function statusPP($status, $type = '', $request = '', $institute_id = ''){
        if ($type == 'PSview') {
            if ($status == 0) {
                return '<a href="' . $this->Url->build('/purchase-submision/view/') . $request . '"><span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">PENDING PPK</span></a>';
            }
            if ($status == 1) {
                return '<a href="' . $this->Url->build('/purchase-submision/view/') . $request . '"><span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">DISETUJUI PPK</span></a>';
            }
            if ($status == 2) {
                return '<a href="' . $this->Url->build('/approval-p-r/add/') . $request . '"><span class="kt-badge kt-badge--warning  kt-badge--inline kt-badge--pill">PENDING APPROVAL</span></a>';
            }
        }else{
            if ($status == 0) {
                return '<span class="kt-badge  kt-badge--info kt-badge--inline kt-badge--pill">PENDING PPK</span>';
            }
            if ($status == 1) {
                return '<span class="kt-badge  kt-badge--success kt-badge--inline kt-badge--pill">DISETUJUI PPK</span>';
            }
            if ($status == 2) {
                return '<span class="kt-badge kt-badge--warning  kt-badge--inline kt-badge--pill">PENDING APPROVAL</span>';
            }
        }
    }

    public function labelSettings($label){
        return str_replace(".","",$label);
    }

    public function statusLabelAktif($label){
        if($label){
            return "<span class=\"bg-success text-white\" style=\" padding: 5px; border-radius: 15px\"> ENABLE</span>";
        }else{
            return "<span class=\"bg-danger text-white\" style=\" padding: 5px; border-radius: 15px\"> DISABLED</span>";
        }
    }


    public function formatIdr($number, $coma = 2)
    {
        $number = number_format($number, $coma);
        $number = $this->checkComa($number);
        return $number;
    }

    public function formatNumber($number, $coma = 2)
    {
        $number = number_format($number, $coma);
        $number = $this->checkComa($number);
        return $number;
    }

    public function checkComa($number)
    {
        $explodeNumber = explode('.', $number);
        $newComa = '';
        if (!empty($explodeNumber[1])) {
            $lengthComa = strlen($explodeNumber[1]);
            $coma = $explodeNumber[1];
            $stop = false;
            for ($a = $lengthComa; $a >= 1; $a = $a - 1) {
                if ($stop == false) {
                    $c = $a - 1;
                    $lastNomComa = substr($coma, $c, 1);
                    if ($lastNomComa == 0 || empty($lastNomComa)) {
                        $newComa = substr($coma, 0, $c);
                    } else {
                        $stop = true;
                    }
                }
            }
        } else {
            $newComa = '';
        }

        if ($newComa == '') {
            return $explodeNumber[0];
        } else {
            return $explodeNumber[0] . '.' . $newComa;
        }
    }

    public function generateUrlImage($img_dir = null, $img, $prefix = null, $img_not_found = null)
    {
        //full_path_dir
        $baseDir = '';
        if ($img_dir == null) {
            $img_dir = $img;
            if (substr($img_dir, 0, 1) == '/' || substr($img_dir, 0, 1) == '"') {
                $img_dir = substr($img_dir, 1);
            }
            $changeSlash = str_replace('\\', DS, $img_dir);
            $changeSlash = str_replace('/', DS, $changeSlash);
            $changeSlash = str_replace('webroot\\', '', $changeSlash);
            $changeSlash = str_replace('webroot/', '', $changeSlash);
            $full_path = WWW_ROOT . $changeSlash;
            $noDir = true;
        } else {
            $img_dir = $baseDir . $img_dir . '/';
            $img = $prefix . $img;
            $changeSlash = str_replace('\\', DS, $img_dir);
            $changeSlash = str_replace('/', DS, $changeSlash);
            $full_path = ROOT . DS . $changeSlash . $img;
            $noDir = false;
        }

        //check image exist
        if (file_exists($full_path)) {
            if ($noDir == false) {
                $dir = str_replace('\\', '/', $img_dir) . $img;
            } else {
                $dir = str_replace('\\', '/', $img_dir);
            }
            $dir = str_replace('webroot/', '', $dir);
            $url = $this->Url->build('/' . $dir, true);
        } else {
            if ($img_not_found == null) {
                $url = $this->Url->build('/img/no-image.png', true);
            } else {
                $url = $this->Url->build('/' . $img_not_found, true);
            }
        }
        return $url;
    }


}