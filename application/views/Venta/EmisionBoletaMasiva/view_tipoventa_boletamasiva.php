
<!-- ko with : BoletaMasiva -->
  <fieldset>
    <div class="col-md-12">
      <div class="col-md-3">
        <div class="form-group text-center">
          <div class="radio radio-info text-shadow">
          <input id="radioMercaderia" type="radio" name="TipoVenta" class="no-tab" value="1" data-bind="checked : TipoVenta, event : { click : OnClickTipoVenta, change : OnChangeTipoVenta}">
          <label id="radLblMercaderias" for="radioMercaderia"  style="font-size:13px;">Venta de Mercaderías</label>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group text-center">
          <div class="radio radio-info text-shadow">
          <input id="radioServicio" type="radio" name="TipoVenta" class="no-tab" value="2" data-bind="checked : TipoVenta, event : { click : OnClickTipoVenta, change : OnChangeTipoVenta }">
          <label id="radLblServicios" for="radioServicio" style="font-size:13px;">Venta de Servicios</label>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group text-center">
          <div class="radio radio-info text-shadow">
          <input id="radioActivo" type="radio" name="TipoVenta" class="no-tab" value="3" data-bind="checked : TipoVenta, event : { click : OnClickTipoVenta, change : OnChangeTipoVenta }">
          <label id="radLblActivos" for="radioActivo" style="font-size:13px;">Venta de Activos</label>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group text-center">
          <div class="radio radio-info text-shadow">
          <input id="radioOtras" type="radio" name="TipoVenta" class="no-tab" value="4" data-bind="checked : TipoVenta, event : { click : OnClickTipoVenta, change : OnChangeTipoVenta }">
          <label id="radLblOtras" for="radioOtras" style="font-size:13px;">Otras Ventas</label>
          </div>
        </div>
      </div>
    </div>
  </fieldset>
<!-- /ko -->
