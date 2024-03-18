<fieldset>
  <div class="consulta">
    <div class="row">
      <div class="col-md-12">
        <!-- ko with : ControlMesa -->
        <div class="col-md-2 mesas">
          <div class="col-xs-12 form-group">
            <div class="form-group text-center">
              <b>MESAS</b>
            </div>
          </div>
          <!-- ko foreach : Mesas -->
          <div class="col-xs-6 form-group">
            <button type="button" class="btn btn-selection btn-control btn-mesa" data-bind="
            text : NumeroMesa,
            event:{ click: function (data, event) { return $root.OnClickBtnMesaCajero(data, event, $parent);}},
            attr: {'data-idmesa': IdMesa()},
            css : SituacionMesa() == SITUACION_MESA.OCUPADO ? 'mesa-ocupado' : 'mesa-disponible'
            "></button>
          </div>
          <!-- /ko -->
        </div>
        <!-- /ko -->
        <div class="col-md-10 consulta-preventas">
          <div class="col-md-12 group-btn-preventa" style="display: none;">
            <div class="form-group">
              <div class="btn-group">
                <b id="IdMesaSeleccionado"></b>
              </div>
            </div>
          </div>
          <div class="col-md-12 group-btn-preventa" style="display: none;">
            <div class="form-group">
              <div class="btn-group">
                <button type="button" data-preventa="Comanda" class="btn btn-selection btn-preventa" data-bind="event: { click: $parent.ObtenerComandas }"> Comandas </button>
              </div>
              <div class="btn-group">
                <button type="button" data-preventa="PreCuenta" class="btn btn-selection btn-preventa" data-bind="event: { click: $parent.ObtenerPreCuentas }"> Pre Cuentas </button>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="container-preventa Comanda" style="display: none;">
              <table class="table table-border table-hover" data-bind="visible: Comandas().length > 0">
                <thead>
                  <tr>
                    <td>Nro Comanda</td>
                    <td with="100px" class="text-center">Fecha</td>
                    <td with="50px" class="text-right">Importe</td>
                    <td with="100px" class="text-center">¿Pre Cuenta?</td>
                  </tr>
                </thead>
                <tbody>
                  <!-- ko foreach : Comandas -->
                  <tr  data-bind="
                  event: { click: function (data, event) { return OnClickComanda(data, event, $parent) } },
                  attr: { id: IdComprobanteVenta() + '_tr_comanda' },
                  css: IndicadorPreCuenta() == INDICADOR_PRECUENTA.NO ? 'pendiente-canjeado' : ''">
                    <td data-bind="text: SerieDocumento() + ' - ' + NumeroDocumento()"></td>
                    <td with="100px" class="text-center" data-bind="text: FechaEmision"></td>
                    <td with="50px" class="text-right" data-bind="text: Total"></td>
                    <td with="100px" class="text-center" data-bind="text: IndicadorPreCuenta() == INDICADOR_PRECUENTA.NO ? 'NO' : 'SI'"></td>
                  </tr>
                  <!-- /ko -->
                </tbody>
              </table>
              <div class=" text-center" data-bind="visible: Comandas().length == 0">
                <h1>Sin resultados...</h1>
              </div>
            </div>
            <div class="container-preventa PreCuenta" style="display: none;">
              <table class="table table-border table-hover" data-bind="visible: PreCuentas().length > 0">
                <thead>
                  <tr>
                    <td>Nro Precuenta</td>
                    <td with="100px" class="text-center">Fecha</td>
                    <td with="50px" class="text-right">Importe</td>
                    <td with="100px" class="text-center">Pago</td>
                    <td with="100px" class="text-center">Canjeado</td>
                  </tr>
                </thead>
                <tbody>
                  <!-- ko foreach : PreCuentas -->
                  <tr data-bind="
                  event: { click: function (data, event) { return OnClickPreCuenta(data, event, $parent) } },
                  attr: { id: IdComprobanteVenta() + '_tr_precuenta' },
                  css: IndicadorCanjeado() == INDICADOR_PAGADO.PENDIENTE ? 'pendiente-canjeado' : ''">
                    <td data-bind="text: SerieDocumento() + ' - ' + NumeroDocumento()"></td>
                    <td with="100px" class="text-center" data-bind="text: FechaEmision"></td>
                    <td with="50px" class="text-right" data-bind="text: Total"></td>
                    <td with="100px" class="text-center" data-bind="text: IndicadorPagado() == INDICADOR_PAGADO.PARCIAL ? 'PARCIAL' : IndicadorPagado() == INDICADOR_PAGADO.PAGADO ? 'PAGADO' : 'PENDIENTE' "></td>
                    <td with="100px" class="text-center" data-bind="text: IndicadorCanjeado() == INDICADOR_CANJEADO.PARCIAL ? 'PARCIAL' : IndicadorCanjeado() == INDICADOR_CANJEADO.CANJEADO ? 'CANJEADO' : 'PENDIENTE'"></td>
                  </tr>
                  <!-- /ko -->
                </tbody>
              </table>
            </table>
            <div class=" text-center" data-bind="visible: PreCuentas().length == 0">
              <h1>Sin resultados...</h1>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</fieldset>

<style media="screen">
  .mesas .btn-mesa {
    height: 30px;
    font-size: 13px;
  }
  .mesas .col-xs-6 {
    padding-right: 2px;
    padding-left: 1px;
  }
  .mesas .form-group {
    margin-bottom : 3px;
  }
  #IdMesaSeleccionado {
    font-size: medium;
    color: #ffff;
    text-decoration: underline;
  }
  .pendiente-canjeado{
    background: red;
    color: #ffffff;
  }
</style>
