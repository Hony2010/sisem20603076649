<!-- ko with : $root.data.Buscador  -->
<?php echo $view_subcontent_buscador_envioguiaremisionremitente; ?>
<!-- /ko -->
<fieldset>
  <table class="datalist__table table" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="text-center">Documento</th>
        <th class="text-center">Fecha Emisión</th>
        <th class="">Transportista</th>
        <th class="">RUC</th>
        <th class="text-center">Estado</th>
        <th class="">&nbsp;</th>
        <th class="text-center" style="text-align: left;">
          <input id="SelectorTodo" class="input-checkbox" name="SelectorTodo" type="checkbox" data-bind="checked: $root.SelectorTodo, event: {change: $root.SeleccionarTodo}">
          <label class="label-checkbox">Todo</label>
        </th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : EnviosGuiaRemisionRemitente -->
      <tr class="clickable-row" data-bind="attr : { id: IdComprobanteVenta }" style="text-transform: UpperCase;">
        <td class="text-center" data-bind="text: Numero"></td>
        <td class="text-center" data-bind="text: FechaFormateada"></td>
        <td class="" data-bind="text: RazonSocialCliente"></td>
        <td class="" data-bind="text: NumeroDocumentoIdentidad"></td>
        <td>
          <span data-bind="text: EstadoCE"></span>
        </td>
        <td class="text-right">
          <span class="fa fa-fw" style="font-size: 2em" data-bind="css: Icono, style:{color: Color}"></span>
        </td>
        <td class="col-md-1 col-md-auto-height">
          <div class="text-left" data-bind="css: VistaCheck">
            <input type="checkbox" class="input-checkbox" data-bind="checked: EstadoSelector, event: {change: CambiarEstadoCheck}">
          </div>
        </td>
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</fieldset>
<br />
<button id="EnviarSunat" class="btn btn-primary" data-bind="event:{click: $root.EnviarSUNAT}">Enviar a SUNAT</button>
<p></p>