<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $site_info['title'] ?></title>

        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="author" content="Nazmul Hasan, Alamgir Kabir, Rashida Sultana, Ridoy">
        <meta name="description" content="Social">
        <meta name="keywords" content=""/>
        <meta charset="UTF-8">

        <link href="<?php echo base_url(); ?>resources/images/money.ico" rel="icon" type="image/x-icon" id="page_favicon">
        <link href="<?php echo base_url(); ?>resources/css/datepicker.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>resources/css/chosen.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>resources/css/recharge.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>resources/css/menu.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>resources/css/styles.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>resources/css/theme-default.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>resources/css/bootstrap.min.css" rel="stylesheet">
<!--        <link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/superadmin/zebra_datePicker.css" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>resources/css/superadmin/zebra_datePicker_custom.css" type="text/css">-->



        <script src="<?php echo base_url(); ?>resources/js/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>resources/js/jquery-2.0.3.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>resources/js/chosen.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>resources/js/datepicker.js" type="text/javascript"></script>    
        <script src="<?php echo base_url(); ?>resources/js/ajax_req.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>resources/js/smscounter.js" type="text/javascript"></script>
        <script src="<?php echo base_url(); ?>resources/js/recharge.js" type="text/javascript"></script>
<!--        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/superadmin/zebra_datepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url() ?>resources/js/superadmin/zebra_datepicker_core.js"></script>-->
        <script src="<?php echo base_url(); ?>resources/js/utils.js" type="text/javascript"></script>

        <!--<angular>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/angular/angular.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/angular/angular-animate.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/angular/angular-file-upload.min.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/angular-bootstrap/ui-bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/bootstrap.min.js" ></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/csv_file_dependencies/angular-sanitize.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/csv_file_dependencies/ng-csv.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/angular/dirPagination.js"></script>
        <!--<angular Services>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularService/transctionService.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularService/resellerService.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularService/paymentService.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularService/reportService.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularService/packageService.js"></script>
        <!--<angular Controller>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularController/transactionController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularController/resellerController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularController/paymentController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularController/leftController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularController/reportController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularController/smsFileUploadController.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularController/packageController.js"></script>
        <!--<angular Apps>-->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularApp/transctionApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularApp/resellerApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularApp/paymentApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularApp/reportApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularApp/smsFileUploadApp.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/AngularApp/packageApp.js"></script>


    </head>
    <body ng-app="<?php echo $app; ?>" style="margin: 0; padding: 0;">
        <div class="container-fluid">
            <?php $this->load->view('admin/templates/sections/header'); ?>
            <div class="row">
                <div class="col-md-2" >
                    <?php $this->load->view('admin/templates/sections/left_pane'); ?>
                </div>
                <div class="col-md-10">
                    <?php echo $contents; ?>
                </div>
            </div>
            <?php $this->load->view('admin/templates/sections/footer'); ?>
        </div>
    </body>
</html>
<?php $this->load->view('common/common_modal'); ?>
<?php $this->load->view('common/common_confirmation_modal'); ?>
<?php $this->load->view('wating_loader'); ?>