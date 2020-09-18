<?php

function tr_page($page)
{
    $tr = '<tr>
                <td class="page'.$page.'_column1"></td>
                <td class="page'.$page.'_column2"></td>
                <td class="page'.$page.'_column3"></td>
                <td class="page'.$page.'_column4"></td>
                <td class="page'.$page.'_column5"></td>
                <td class="page'.$page.'_column6"></td>
                <td class="page'.$page.'_column7"></td>
                <td class="page'.$page.'_column8"></td>
                <td class="page'.$page.'_column9"></td>
            </tr>';

    return $tr;
}

function action_response($status, $form_id, $css, $message, $js = '') {
    return '<div class="' . $css . '">' . $message . '</div><script>iFresponse(' . $status . ', "' . $form_id . '");' . $js . '</script>';
}

function slidedown_response($form_id, $css, $message, $js = '') {
    return '<div class="' . $css . '">' . $message . '</div><script>$("#' . $form_id . '").slideDown();' . $js . '</script>';
//    return '<div class="' . $css . '">' . $message . '</div><script>$("#' . $form_id . '").slideUp();setTimeout(function(){$("#' . $form_id . '").slideDown();' . $js . '},200);</script>';
}

if (!function_exists('indonesian_date')) {
    date_default_timezone_set("Asia/Bangkok");

    function indonesian_date($timestamp = '', $date_format = 'l, j F Y', $suffix = '') {
        if (trim($timestamp) == '') {
            $timestamp = time();
        } elseif (!ctype_digit($timestamp)) {
            $timestamp = strtotime($timestamp);
        }
        # remove S (st,nd,rd,th) there are no such things in indonesia :p
        $date_format = preg_replace("/S/", "", $date_format);
        $pattern = array(
            '/Mon[^day]/', '/Tue[^sday]/', '/Wed[^nesday]/', '/Thu[^rsday]/',
            '/Fri[^day]/', '/Sat[^urday]/', '/Sun[^day]/', '/Monday/', '/Tuesday/',
            '/Wednesday/', '/Thursday/', '/Friday/', '/Saturday/', '/Sunday/',
            '/Jan[^uary]/', '/Feb[^ruary]/', '/Mar[^ch]/', '/Apr[^il]/', '/May/',
            '/Jun[^e]/', '/Jul[^y]/', '/Aug[^ust]/', '/Sep[^tember]/', '/Oct[^ober]/',
            '/Nov[^ember]/', '/Dec[^ember]/', '/January/', '/February/', '/March/',
            '/April/', '/June/', '/July/', '/August/', '/September/', '/October/',
            '/November/', '/December/',
        );
        $replace = array('Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min',
            'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu',
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des',
            'Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'Sepember',
            'Oktober', 'November', 'Desember',
        );
        $date = date($date_format, $timestamp);
        $date = preg_replace($pattern, $replace, $date);
        $date = "{$date} {$suffix}";
        return $date;
    }

}

function detailuser($id = 0,$field = 'id'){
    $CI = & get_instance();
    $CI->load->database('default', true);
    $query = "SELECT a.*,b.* FROM t_relawan a LEFT JOIN `user` b ON a.`id` = b.`relawan_id` WHERE b.`user_id` = '$id';";
    $q = $CI->db->query($query);
    if($q->num_rows() > 0){
        $res = $q->result();
        if($field == '*'){
            return $res;
        }else{
            return $res[0]->$field;
        }
    }
}

function set_bar_color($num_rows, $index){
        $arrWarna = array("#FF0F00", "#FF6600", "#FF9E01", "#FCD202", "#F8FF01", "#B0DE09", "#04D215", "#0D8ECF", "#0D52D1", "#2A0CD0");
        if($num_rows > 10){
            $bar_index = ($index % 10);
        }else{
            $bar_index = (10-($num_rows)) + $index;
        }
        
        return $arrWarna[$bar_index];
    }

function active_menu($current, $str) {
    if ($current == $str) {
        echo ' class="active"';
    }
}

function active_class($current, $str) {
    if ($current == $str) {
        echo ' active';
    }
}

function uid() {
    return str_replace(array('.', ' '), '', microtime()) . rand(1, 9);
}

function days_between($from, $to) {
    return abs(strtotime($from) - strtotime($to)) / 60 / 60 / 24;
}

function passwd($str) {
    // return md5(crypt($str, config_item('password_salt')) . '+ salt +');
    $qEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(config_item('password_salt')), $str, MCRYPT_MODE_CBC, md5(md5(config_item('password_salt')))));
    return($qEncoded);
}

function codereff() {
    $uniq = md5(uniqid(rand(), true));
    $str = substr($uniq, -5);
    return $str;
}

function input($str) {
    return htmlspecialchars($str);
}

function option_selected($value, $label, $selected) {
    $data = '<option value="' . $value . '"';
    $data .= ($value == $selected) ? ' selected="selected"' : '';
    $data .= '>' . $label . '</option>';
    return $data;
}

function checked($id) {
    return $id ? ' checked="checked"' : '';
}

function date_picker() {
    echo '<style type="text/css">@import url("' . base_url('static/css/date.css') . '");</style>';
    echo '<script type="text/javascript" src="' . base_url('static/js/date.js') . '"></script>';
}

function str_checking($type, $str, $arr = array()) {
    switch ($type) {
        case 'in_array':
            return in_array($str, $arr, true) ? $str : '';
            break;

        case 'email':
            return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $str) ? $str : false;
            break;

        case 'url':
            return preg_match("/^(http\:\/\/|https\:\/\/)?([0-9a-zA-Z][0-9a-zA-Z\-]*\.)+[0-9a-zA-Z][0-9a-zA-Z\_\-\s\.\/\?\%\#\&\=]*$/i", $str) ? $str : false;
            break;

        case 'sql':
            return str_replace("'", "''", $str);
            break;

        case 'digit':
            return ctype_digit($str) ? $str : '';
            break;
    }
}

function str_replacing($type, $str) {
    switch ($type) {
        case 'alpha_num':
            return preg_replace('/[^a-zA-Z0-9]/i', '', $str);
            break;

        case 'alpha':
            return preg_replace('/[^a-zA-Z]/i', '', $str);
            break;

        case 'digit':
            return preg_replace('/[^0-9]/i', '', $str);
            break;

        case 'float':
            return preg_replace('/[^0-9.-]/i', '', $str);
            break;
    }
}

function mycustomreplace($str) {
    $rep = str_replace("KABUPATEN", "KAB", $str);
    $rep = str_replace(" ", ".", $rep);
    return $rep;
}

function encode($values) {
    $len = strlen($values);
    for ($i = 0; $i < $len; $i++) {
        $numeric[$i] = substr($values, $i, 1);
    }
    $arand[0] = rand(0, 700);
    srand((double) microtime() * 1000000);
    $random = rand(0, 8);
    $result = ($random + 1) * 1000 + $arand[0];
    $result = $result . "";
    for ($i = 1; $i <= $len; $i++) {
        $random = rand(0, 8);
        $arand[$i] = ($random + 1) * 1000 + $arand[0] + ord($numeric[$i - 1]);
        $result = $result . $arand[$i];
    }
    return $result;
}

function decode($values) {
    $len = strlen($values);
    $lens = ($len / 4) - 1;
    $arand[0] = substr($values, 0, 4);
    $arand[0] = $arand[0] % 1000;
    $result = "";
    for ($i = 1; $i <= $lens; $i++) {
        $arand[$i] = substr($values, $i * 4, 4);
        $arand[$i] = $arand[$i] % 1000;
        $arand[$i] = $arand[$i] - $arand[0];
        $result = $result . chr($arand[$i]);
    }
    return $result;
}

function ddr_name($well, $date) {
    return md5($date . $well);
}

function ddr_file($dir, $well, $date) {
    $file = ddr_name($well, $date);
    if (file_exists($dir . $file . '.xls')) {
        return $file . '.xls';
    } else
        return $file . '.xlsx';
}

function re_name($src, $dest) {
    if (file_exists($src)) {
        rename($src, $dest);
        return true;
    } else
        return false;
}

function un_link($src) {
    if (file_exists($src)) {
        unlink($src);
        return true;
    } else
        return false;
}

function upload($dir, $files_name, $files_tmp, $fn = NULL, $restrict = NULL) {
    $fileext = explode('.', $files_name);
    $file_ext = strtolower(end($fileext));

    $new_name = $fn ? $fn : date("YmdHms");
    $new_file_name = $new_name . '.' . $file_ext;

    $file_path = $dir . $new_file_name;
    if (!in_array($file_ext, array('php', 'html'), true)) {

        if ($restrict) {
            if (in_array($file_ext, $restrict, true)) {
                move_uploaded_file($files_tmp, $file_path);
            } else
                $new_file_name = '';
        } else
            move_uploaded_file($files_tmp, $file_path);
    } else
        $new_file_name = '';

    return $new_file_name;
}

function print_arr($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function replace_txt($string = '') {
    $replace = array('December');
    $pattern = array('/Desember/');
    return preg_replace($pattern, $replace, $string);
}

function super_unique($array) {
    $result = array_map("unserialize", array_unique(array_map("serialize", $array)));

    foreach ($result as $key => $value) {
        if (is_array($value)) {
            $result[$key] = super_unique($value);
        }
    }

    return $result;
}

function indonesian_date($timestamp = '', $date_format = 'l, j F Y', $suffix = '') {
    if (trim($timestamp) == '') {
        $timestamp = time();
    } elseif (!ctype_digit($timestamp)) {
        $timestamp = strtotime($timestamp);
    }
    # remove S (st,nd,rd,th) there are no such things in indonesia :p
    $date_format = preg_replace("/S/", "", $date_format);
    $pattern = array(
        '/Mon[^day]/', '/Tue[^sday]/', '/Wed[^nesday]/', '/Thu[^rsday]/',
        '/Fri[^day]/', '/Sat[^urday]/', '/Sun[^day]/', '/Monday/', '/Tuesday/',
        '/Wednesday/', '/Thursday/', '/Friday/', '/Saturday/', '/Sunday/',
        '/Jan[^uary]/', '/Feb[^ruary]/', '/Mar[^ch]/', '/Apr[^il]/', '/May/',
        '/Jun[^e]/', '/Jul[^y]/', '/Aug[^ust]/', '/Sep[^tember]/', '/Oct[^ober]/',
        '/Nov[^ember]/', '/Dec[^ember]/', '/January/', '/February/', '/March/',
        '/April/', '/June/', '/July/', '/August/', '/September/', '/October/',
        '/November/', '/December/',
    );
    $replace = array('Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min',
        'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu',
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des',
        'Januari', 'Februari', 'Maret', 'April', 'Juni', 'Juli', 'Agustus', 'Sepember',
        'Oktober', 'November', 'Desember',
    );
    $date = date($date_format, $timestamp);
    $date = preg_replace($pattern, $replace, $date);
    $date = "{$date} {$suffix}";
    return $date;
}

function _outputjson($data = array()) {
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Content-type: application/json');
    header("access-control-allow-origin: *");
    echo json_encode($data);
}
