<!-- ko with : ComprobanteCompra -->
<div class="datalist__result" id="panelheaderComprobanteCompra">
  <div class="col-md-3">
    <!-- <div class="form-group">
      <div class="input-group" >
        <div class="input-group-addon"><span class="glyphicon glyphicon-user"></div>
        <input id="Vendedor" readonly class="form-control formulario" type="text"   placeholder="Vendedor" data-bind="value : AliasUsuarioVenta">
      </div>
    </div> -->
  </div>
  <div class="col-md-1">
  </div>
  <div class="col-md-3">
    <div class="form-group">
      <div class="input-group">
        <div class="input-group-addon">Tipo Cambio</div>
        <input id="ValorTipoCambio" class="form-control formulario numeric text-mumeric" type="text" data-bind="value: ValorTipoCambio">
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="form-group">
      <select id="combo-moneda" class="form-control formulario" data-bind="
      value : IdMoneda,
      options : Monedas,
      optionsValue : 'IdMoneda' ,
      optionsText : 'NombreMoneda',event : { change : OnChangeMoneda } " style="min-height: 22px; padding: 4px 12px;">
      </select>
    </div>
  </div>
  <div class="col-md-3">
    <div class="">
      <div class="input-group">
        <div class="input-group-addon"><span class="glyphicon glyphicon-home"></span></div>
        <input id="Agencia"  readonly class="form-control formulario" type="text" placeholder="Agencia" data-bind="value: NombreSedeAgencia">
      </div>
    </div>
  </div>
</div>
<!-- /ko -->
