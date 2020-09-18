<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Upload Excel - Sewa Beli @ismiadi </title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/bootstrap-extended.css" rel="stylesheet" type="text/css">

        <!-- Custom styles for this template -->
        <link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>assets/css/chosen.min.css" rel="stylesheet">

        <link href="<?php echo base_url(); ?>assets/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url(); ?>assets/css/plugins.min.css" rel="stylesheet" type="text/css" />

    </head>

    <body>
        
        <div id="ifbox_body">
            <div class="iheader">Add Bulk Data</div>
            <div class="ibody">
                <div id="iform_r1"></div>
                <input type="hidden" id="timereload" value="0">
                <form method="post" action="<?php echo site_url('insertcsv') ?>" id="iform_f1" onsubmit="return iForm_s(1);" enctype="multipart/form-data">
                    <table border="0" width="100%" class="lists" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>BULK FILE </td>
                            <td>:</td>
                            <td><input type="file" name="epfile" /></td>
                        </tr>
                    </table>
                    <div class="ifooter">
                        <input type="submit" value="Submit" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <div id="ifbox_body" style="padding: 20px">
            <?php echo "<div>" . flashdata('msg_response') . "</div>"; ?>
        </div>

    </body>
</html>
