<?php
  $tema__sistema = $this->session->userdata('Usuario_'.LICENCIA_EMPRESA_RUC)['TemaSistema'];
  $tema__sistema = ($tema__sistema != "") ? $tema__sistema : "right.lilac.css";
 ?>
<link rel="icon" type="image/png" href="<?php echo base_url()?>assets/img/favicon.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo base_url()?>assets/img/apple-touch-favicon.png">
<link href="<?php echo base_url()?>assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- <link href="http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic" rel="stylesheet" type="text/css"> -->
<link href="<?php echo base_url()?>assets/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/libs/jquery.scrollbar/jquery.scrollbar.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/libs/ionrangeslider/css/ion.rangeSlider.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/libs/ionrangeslider/css/ion.rangeSlider.skinFlat.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/libs/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/libs/datatables/css/dataTables.bootstrap.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/libs/selectize/css/selectize.default.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/libs/selectize/css/selectize.bootstrap3.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/libs/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/libs/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
<!--<link href="<?php echo base_url()?>assets/libs/jquery-ui/jquery-ui.css" rel="stylesheet">-->
<link href="<?php echo base_url()?>assets/libs/jquery-ui/jquery-ui.css" rel="stylesheet">
<!-- CSS file -->
<link rel="stylesheet" href="<?php echo base_url()?>assets/libs/easyautocomplete/easy-autocomplete.min.css">
<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">-->
<!--<link href="<?php echo base_url()?>assets/libs/boostrap-checkbox/boostrap-checkbox.css" rel="stylesheet">-->
<link class="demo__css" href="<?php echo base_url().'assets/css/'.$tema__sistema?>" rel="stylesheet">
<link href="<?php echo base_url()?>assets/css/demo.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url()?>assets/libs/bootstrap-notify/bootstrap-notify.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/libs/alertifyjs/alertify.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/libs/inputAlert/inputAlert.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/libs/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/libs/simplePagination/simplePagination.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/libs/jquery-form-validator/jquery.form-validator.min.css">


<!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/libs/simplePagination/simplePagination.css"> -->

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!--Knockout -->
<script type="text/javascript" src="<?php echo base_url()?>assets/js/Knockout/knockout-3.2.0.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/Knockout/knockout.mapping-latest.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/Knockout/knockout.validation.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/Knockout/json2.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/Knockout/respond.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/Knockout/utils.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>assets/js/Knockout/knockout-file-bind.js"></script>
