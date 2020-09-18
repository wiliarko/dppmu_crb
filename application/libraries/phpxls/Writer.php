<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'PEAR.php';
require_once 'ExcelWriter/Workbook.php';

class Spreadsheet_Excel_Writer extends Spreadsheet_Excel_Writer_Workbook
{
    public function __construct($filename = '')
    // added by sikelopes
    // function Spreadsheet_Excel_Writer($filename = '')
    {
        $this->_filename = $filename;
        // added by sikelopes
        parent::__construct($filename);
        // $this->Spreadsheet_Excel_Writer_Workbook($filename);
    }

    function send($filename)
    {
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Pragma: public");
    }

    static function rowcolToCell($row, $col)
    {
        if ($col > 255) {
            return new PEAR_Error("Maximum column value exceeded: {$col}");
        }

        $int    = (int)($col / 26);
        $frac   = $col % 26;
        $chr1   = '';

        if ($int > 0) $chr1 = chr(ord('A') + $int - 1);

        $chr2 = chr(ord('A') + $frac);
        $row++;

        return $chr1.$chr2.$row;
    }
}