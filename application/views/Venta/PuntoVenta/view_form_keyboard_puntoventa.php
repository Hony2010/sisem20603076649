<div class="keyboard-virtual">
  <fieldset>
      <div class="row">
        <div class="col-md-12">
          <div class="numpad col-md-12">
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="1" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }" id="number_one">1</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="2" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }">2</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="3" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }">3</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="4" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }">4</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="5" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }">5</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="6" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }">6</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="7" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }">7</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="8" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }">8</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="9" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }">9</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="POINT" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }">.</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button number-char" data-number="0" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }">0</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4">
              <button type="button" class="btn btn-default btn-control input-button numpad-backspace " data-number="BACKSPACE" data-bind="event:{click: function(data, event){return PushNumber(data, event, $parent.PuntoVenta);} }">&#8592;</button>
            </div>
            <div class="col-md-12">
              <button type="button" class="btn btn-primary btn-control input-button btn-pagar"data-bind="event: { click: $parent.PuntoVenta.OnHideOrShowElement }">Pagar</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4 btn-opcions" style="display: none;">
              <button type="button" class="btn btn-primary btn-control input-button"data-bind="event: { click: $parent.PuntoVenta.OnHideOrShowElement }">Atras</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4 btn-opcions" style="display: none;">
              <button type="button" class="btn btn-success btn-control input-button"data-bind="event: { click: $parent.PuntoVenta.Guardar }">Guardar</button>
            </div>
            <div class="col-md-4 col-xs-4 col-lg-4 btn-opcions" style="display: none;">
              <button type="button" class="btn btn-primary btn-control input-button"data-bind="event: { click: $parent.PuntoVenta.LimpiarPuntoVenta }">Limpiar</button>
            </div>
          </div>
        </div>
      </div>
  </fieldset>
</div>
