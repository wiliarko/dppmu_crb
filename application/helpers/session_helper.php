<?php

@session_start();
define("USERDATA_KEY", config_item('session_id'));
define("FLASHDATA_KEY", "flashdata_" . USERDATA_KEY);

function set_userdata($name, $value = NULL) {
    if ($value == NULL) {
        foreach ($name as $key => $val) {
            $_SESSION[USERDATA_KEY][$key] = $val;
        }
    } else
        $_SESSION[USERDATA_KEY][$name] = $value;
}

function unset_userdata($name) {
    for ($i = 0; $i < count($name); $i++) {
        if (isset($_SESSION[USERDATA_KEY][$name[$i]])) {
            unset($_SESSION[USERDATA_KEY][$name[$i]]);
        }
    }
}

function userdata($name) {
    return isset($_SESSION[USERDATA_KEY][$name]) ? $_SESSION[USERDATA_KEY][$name] : false;
}

function all_userdata() {
    if (isset($_SESSION[USERDATA_KEY])) {
        print_r($_SESSION[USERDATA_KEY]);
    } else
        return array();
}

function set_flashdata($name, $value = NULL) {
    if ($value == NULL) {
        foreach ($name as $key => $val) {
            $_SESSION[FLASHDATA_KEY][$key] = $val;
        }
    } else
        $_SESSION[FLASHDATA_KEY][$name] = $value;
}

function flashdata($name) {
    $flashdata = false;

    if (isset($_SESSION[FLASHDATA_KEY][$name])) {
        $flashdata = $_SESSION[FLASHDATA_KEY][$name];
        unset($_SESSION[FLASHDATA_KEY][$name]);
    }

    return $flashdata;
}

function keep_flashdata($name) {
    return isset($_SESSION[FLASHDATA_KEY][$name]) ? $_SESSION[FLASHDATA_KEY][$name] : false;
}

function sess_destroy() {
    if (isset($_SESSION[USERDATA_KEY])) {
        unset($_SESSION[USERDATA_KEY]);
    }
    if (isset($_SESSION[FLASHDATA_KEY])) {
        unset($_SESSION[FLASHDATA_KEY]);
    }
}
