<!-- ko with : $root.data.BuscadorConsulta  -->
<?php echo $view_subcontent_buscador_envioguiaremisionremitenteconsulta; ?>
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
        <th class="text-left">
          <input id="SelectorTodoConsulta" class="input-checkbox" name="SelectorTodoConsulta" type="checkbox" data-bind="checked: $root.SelectorTodoConsulta, event: {change: $root.SeleccionarTodoConsulta}">
          <label class="label-checkbox">Todo</label>
        </th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : EnviosGuiaRemisionRemitenteConsulta -->
      <tr class="clickable-row" data-bind="attr : { id: IdComprobanteVenta }" style="text-transform: UpperCase;">
        <td class="text-center" data-bind="text: Numero"></td>
        <td class="text-center" data-bind="text: FechaFormateada"></td>
        <td class="" data-bind="text: RazonSocialCliente"></td>
        <td class="" data-bind="text: NumeroDocumentoIdentidad"></td>
        <td class="text-center">
          <span data-bind="text: EstadoCE"></span>
        </td>
        <td class="text-right">
          <span class="fa fa-fw" style="font-size: 2em" data-bind="css: Icono, style:{color: Color}"></span>
        </td>
        <td class="">
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
<button id="EnviarSunatConsulta" class="btn btn-primary" data-bind="event:{click: $root.ConsultarSUNAT}">Consultar CDR</button>
<p></p>

