<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="ifbox_body">
    <div class="iheader">Add Bulk Data</div>
    <div class="ibody">
        <div id="iform_r1"></div>
        <input type="hidden" id="timereload" value="0">
        <form method="post" action="<?php echo site_url('proc/master-data/koordinator/insertcsv') ?>" id="iform_f1" onsubmit="return iForm_s(1);" enctype="multipart/form-data">
            <table border="0" width="100%" class="lists" cellpadding="0" cellspacing="0">
                <tr>
                    <td>BULK FILE </td>
                    <td>:</td>
                    <td><input type="file" name="epfile" /></td>
                </tr>
            </table>
            <div class="ifooter">
                <input type="submit" value="Submit" />
                <input type="button" class="ifclose" value="Close" />
            </div>
        </form>
    </div>
</div>