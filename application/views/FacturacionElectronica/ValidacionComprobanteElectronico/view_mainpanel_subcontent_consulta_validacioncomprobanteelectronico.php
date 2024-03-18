<!-- ko with : $root.data.Filtros  -->
<?php echo $view_subcontent_buscador_validacioncomprobanteelectronico; ?>
<!-- /ko -->
<fieldset>
  <table class="datalist__table table" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="" width="110px">Documento</th>
        <th class="" width="110px">Fecha</th>
        <th class="" width="210px">Cliente</th>
        <th class="text-right" width="60px">Total</th>
        <th class="text-center">Estado</th>
        <th class="text-center" width="20px">
          <div class="checkbox">
            <input id="SelectorTodo" class="input-checkbox" name="SelectorTodo" type="checkbox" data-bind="checked: $root.SelectorTodo, event: {change: $root.SeleccionarTodo}">
            <label  for="SelectorTodo"  class="label-checkbox"></label>
          </div>
        </th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : ComprobantesVenta -->
      <tr class="clickable-row text-uppercase" data-bind="attr : { id: IdComprobanteVenta }">
        <td data-bind="text: SerieDocumento()+' - '+NumeroDocumento()"></td>
        <td data-bind="text: FechaEmision"></td>
        <td data-bind="text: RazonSocialCiente"></td>
        <td class="text-right" data-bind="text: Total"></td>
        <td class="" data-bind="text: Estado"></th>
        <td class="text-center">
          <div class="checkbox">
            <input type="checkbox" class="input-checkbox" data-bind="checked: EstadoSelector, event: {change: CambiarEstadoCheck}, attr: { id:'check_'+IdComprobanteVenta() }">
            <label class="label-checkbox" data-bind="attr: { for:'check_'+IdComprobanteVenta() }"></label>
          </div>
        </td>
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</fieldset>
<br />
  <button id="ValidarComprobantes" class="btn btn-primary" data-bind="event:{click: $root.ValidarComprobantes}, enable: ComprobanteVenta().length > 0">Validar Comprobantes</button>
<br />
