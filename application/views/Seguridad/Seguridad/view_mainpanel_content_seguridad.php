<!-- ko with : data -->
<div class="wrapper">
  <div class="login">
    <!-- ko with : $root.data.Seguridad  -->
      <form class="login__form" action="">
        <div class="login__logo"></div>
        <div class="form-group">
          <input id="usuario" class="form-control formulario" type="text" placeholder="USUARIO" data-bind="value: NombreUsuario, event: {focus : OnFocus, keydown : OnKeyEnter}">
        </div>
        <div class="form-group">
          <input id="clave" class="form-control formulario" type="password" placeholder="CONTRASEÑA" data-bind="value: ClaveUsuario, event : {focus : OnFocus, keydown : OnKeyEnter}">
        </div>
        <div class="form-group ">
          <button id="btn-login" type="button" class="btn btn-default btn-control" data-bind="click: $root.Login">INGRESAR</button>
        </div>
      </form>
      <!-- <div class="col-md-12">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
          <div class="gallery js-flickity">
            <div class="gallery-cell"></div>
            <div class="gallery-cell"></div>
            <div class="gallery-cell"></div>
            <div class="gallery-cell"></div>
            <div class="gallery-cell"></div>
            <div class="gallery-cell"></div>
            <div class="gallery-cell"></div>
            </div>
          </div>
        <div class="col-md-3">
        </div>
      </div> -->
    <!-- /ko -->
  </div>
</div>
<!-- /ko -->
