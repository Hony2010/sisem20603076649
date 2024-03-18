<?php
$item__name = $this->session->userdata('item_'.LICENCIA_EMPRESA_RUC);
if($this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC)){
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistemas Empresariales <?php echo $item__name != "0" ? ' | '.$item__name : "";?></title>
    <?php echo $view_header; ?>
  </head>
  <body id="body" class="framed main-scrollable">
    <div class="wrapper">
      <?php echo $view_navigationbar; ?>
      <div class="dashboard">
        <div class="sidebar">
          <?php echo $view_menu; ?>
        </div>
        <div id="main" class="main main_size">
          <div class="btn-menu-sidebar">
            <span class="glyphicon glyphicon-chevron-left"></span>
          </div>
          <div id="maincontent" class="">
            <?php echo $view_content; ?>
          </div>
        </div>
      </div>
    </div>
    <?php echo $view_demo_theme; ?>
    <?php if($this->session->userdata("data_mensaje_demo_".LICENCIA_EMPRESA_RUC)['ParametroDemo'] == '1'): ?>
      <div class="demo__message">
        <div class="demo__cont_message">
          <span>
            <b>SISEM PERU</b><br>
            <?php echo $this->session->userdata("data_mensaje_demo_".LICENCIA_EMPRESA_RUC)['MensajeDemo'] ?>
          </span>
          <span style="font-size:10px">
            <br>Cel: <?php echo $this->session->userdata("data_mensaje_demo_".LICENCIA_EMPRESA_RUC)['CelularDemo'] ?>
            <br>Web: <a target="_blank" href="http://www.sisemperu.com">www.sisemperu.com</a>
            <br>Versión: <?php echo $this->session->userdata("data_mensaje_demo_".LICENCIA_EMPRESA_RUC)['VersionDemo'] ?>
          </span>
        </div>
      </div>
    <?php endif; ?>

    <!-- Footer -->
    <?php echo $view_footer; ?>
    <!-- /Footer -->
  </body>
</html>
<?php
}
else {
  $url = "/Seguridad/cSeguridad/";
  redirect($url);
}
?>
