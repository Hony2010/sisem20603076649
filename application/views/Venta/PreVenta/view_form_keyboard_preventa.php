<div class="keyboard-virtual">
  <fieldset>
      <div class="row">
        <div class="col-md-12">
          <div class="numpad col-md-12">
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="1" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }" id="number_one">1</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="2" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }">2</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="3" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }">3</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="4" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }">4</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="5" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }">5</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="6" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }">6</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="7" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }">7</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="8" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }">8</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="9" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }">9</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="POINT" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }">.</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="0" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }">0</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button numpad-backspace" data-number="BACKSPACE" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PreVenta);} }">&#8592;</button>
            </div>
            <div class="col-md-12 col-xs-12 col-lg-12"  data-bind="visible: $parent.PreVenta.IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.COMANDA">
              <button type="button" class="btn btn-primary btn-control input-button btn-precuenta" data-bind="
              event: { click: $parent.PreVenta.GuardarPreCuenta },
              disable: $parent.PreVenta.IndicadorPreCuenta() == INDICADOR_PRECUENTA.SI">Pre Cuenta</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4" data-bind="visible: $parent.PreVenta.IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.PRECUENTA">
              <button type="button" class="btn btn-primary btn-control input-button btn-tipodocumento" data-bind="
              attr: { 'data-tipodocumento': $parent.PreVenta.IdTipoDocumentoBoleta },
              event: { click: $parent.PreVenta.OnClickBtnTipoDocumentoVentaPreCuenta },
              disable: $parent.PreVenta.IndicadorCanjeado() == INDICADOR_CANJEADO.CANJEADO">Boleta</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4" data-bind="visible: $parent.PreVenta.IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.PRECUENTA">
              <button type="button" class="btn btn-primary btn-control input-button btn-tipodocumento" data-bind="
              attr: { 'data-tipodocumento': $parent.PreVenta.IdTipoDocumentoFactura },
              event: { click: $parent.PreVenta.OnClickBtnTipoDocumentoVentaPreCuenta },
              disable: $parent.PreVenta.IndicadorCanjeado() == INDICADOR_CANJEADO.CANJEADO">Factura</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4" data-bind="visible: $parent.PreVenta.IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.PRECUENTA">
              <button type="button" class="btn btn-primary btn-control input-button btn-tipodocumento" data-bind="
              attr: { 'data-tipodocumento': $parent.PreVenta.IdTipoDocumentoTicket },
              event: { click: $parent.PreVenta.OnClickBtnTipoDocumentoVentaPreCuenta },
              disable: $parent.PreVenta.IndicadorCanjeado() == INDICADOR_CANJEADO.CANJEADO">Ticket</button>
            </div>
            <div class="col-md-6 col-xs-6 col-lg-6" data-bind="visible: $parent.PreVenta.IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.POSTPRECUENTA">
              <button type="button" class="btn btn-primary btn-control input-button btn-tipodocumento" data-bind="
              event: { click: $parent.PreVenta.OnHideOrShowElement }">Atras</button>
            </div>
            <div class="col-md-6 col-xs-6 col-lg-6" data-bind="visible: $parent.PreVenta.IndicadorEstadoPreVenta() == ESTADO_INDICADOR_PREVENTA.POSTPRECUENTA">
              <button type="button" class="btn btn-primary btn-control input-button btn-tipodocumento" data-bind="
              event: { click: $parent.PreVenta.GuardarComprobantePostVenta }">Guardar</button>
            </div>
          </div>
        </div>
      </div>
  </fieldset>
</div>
