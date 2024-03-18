<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Right - Bootstrap Admin Template</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="apple-touch-icon-precomposed" href="img/apple-touch-favicon.png">
    <link href="<?php echo base_url()?>assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url()?>assets/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/libs/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    <link class="demo__css" href="<?php echo base_url()?>assets/css/right.dark.css" rel="stylesheet">
    <link href="<?php echo base_url()?>assets/css/demo.css" rel="stylesheet"><!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body class="framed">
    <div class="wrapper">
      <div class="login">
        <!-- ko with : $root.data.Seguridad  -->
        <form class="login__form" action="index.html">
          <div class="login__logo"></div>
          <div class="form-group">
            <input id="usuario" class="form-control" type="text" placeholder="Login" data-bind="value: NombreUsuario">
          </div>
          <div class="form-group">
            <input id="clave" class="form-control" type="password" placeholder="Password" data-bind="value: ClaveUsuario">
          </div>
          <div class="form-group login__action">
            <div class="checkbox login__remember">
              <input id="chb1" type="checkbox">
              <label for="chb1">Remember</label>
            </div>
            <div class="login__submit">
              <button id="btn-login" class="btn btn-default" data-bind="click: $root.Login" onclick="return false;">Sign in</button>
            </div>
          </div>
        </form>
        <!-- /ko -->
      </div>
    </div>
    <div class="demo">
      <div class="demo__ico"></div>
      <div class="demo__cont">
        <div class="demo__settings">
          <div class="demo__group">
            <div class="demo__label">Color theme:</div>
            <div class="demo__themes">
              <div class="demo__theme demo__theme_active demo__theme_dark" data-css="<?php echo base_url()?>assets/css/right.dark.css" title="Dark"></div>
              <div class="demo__theme demo__theme_lilac" data-css="<?php echo base_url()?>assets/css/right.lilac.css" title="Lilac"></div>
              <div class="demo__theme demo__theme_light" data-css="<?php echo base_url()?>assets/css/right.light.css" title="Light"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="<?php echo base_url()?>assets/libs/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url()?>assets/libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/demo.js"></script>

    <script>
        var data=<?php echo json_encode($data); ?>
    </script>

    <script>
      var existecambio = false;
    </script>

    <script src="<?php echo base_url()?>assets/js/ViewModel/Seguridad/Seguridad.js"></script>

    <script>
        var Models = new Index(data);
        ko.applyBindingsWithValidation(Models);
    </script>

    <script>
    $(document).ready(function(){

      /*$('body').on('click', '#btn-login', function() {

        var usuario = $('#usuario').val();
        var clave = $('#clave').val();

        //var datajs = ko.toJS({'nombreusuario:'+ usuario, 'claveusuario:' + clave});

        $.ajax({
          type: 'POST',
          data : {"nombreusuario": usuario, "claveusuario": clave},

          url: 'Test/Login',
          success: function (data) {
            console.log(data);

            }
          });

    	});
*/


    });
    </script>

  </body>
</html>
