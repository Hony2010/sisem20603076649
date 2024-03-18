<!-- ko with : PuntoVenta -->
<div class="datalist__result"  id="panelHeaderPuntoVenta">
  <form class="" action="" method="post">
    <!-- ko if: ParametroAdministrador() == 0 -->
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <div class="form-group">
        <div class="input-group" >
          <div class="input-group-addon"><span class="glyphicon glyphicon-user"></div>
          <input id="Vendedor" readonly class="form-control formulario no-tab" tabindex="-1" type="text"   placeholder="Vendedor" data-bind="value : AliasUsuarioVenta">
        </div>
      </div>
    </div>
    <!-- /ko -->
    <div class="col-md-4">
      <div class="">
        <div class="input-group">
          <div class="input-group-addon"><span class="glyphicon glyphicon-home"></span></div>
          <input id="Agencia"  readonly class="form-control formulario no-tab" tabindex="-1" type="text" placeholder="Agencia" data-bind="value: NombreSedeAgencia">
        </div>
      </div>
    </div>
  </form>
</div>
<!-- /ko -->
