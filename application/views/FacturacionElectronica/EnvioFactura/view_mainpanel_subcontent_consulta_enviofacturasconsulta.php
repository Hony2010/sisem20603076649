<!-- ko with : $root.data.BuscadorConsulta  -->
<?php echo $view_subcontent_buscador_enviofacturaconsulta; ?>
<!-- /ko -->
<fieldset>
  <table class="datalist__table table" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="col-md-auto"><center>Documento</center></th>
        <th class="col-md-auto"><center>Fecha Emisión</center></th>
        <th class="col-md-4"><center>Cliente</center></th>
        <th class="col-md-1">RUC</th>
        <th class="col-md-1"><center>Monto</center></th>
        <th class="col-md-1"><center>Estado</center></th>
        <th class="col-md-auto">&nbsp;</th>
        <th class="col-md-1" style="text-align: left;">
          <input id="SelectorTodoConsulta" class="input-checkbox" name="SelectorTodoConsulta" type="checkbox" data-bind="checked: $root.SelectorTodoConsulta, event: {change: $root.SeleccionarTodoConsulta}">
          <label class="label-checkbox">Todo</label>
        </th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : EnviosFacturaConsulta -->
      <tr class="clickable-row" data-bind="attr : { id: IdComprobanteVenta }" style="text-transform: UpperCase;">
        <td align="center" class="col-md-auto col-md-auto-height" data-bind="text: Numero"></td>
        <td align="center" class="col-md-auto col-md-auto-height" data-bind="text: FechaFormateada"></td>
        <td class="col-md-4 col-md-auto-height" data-bind="text: RazonSocialCliente"></td>
        <td class="col-md-1 col-md-auto-height" data-bind="text: NumeroDocumentoIdentidad"></td>
        <td class="col-md-1 text-right col-md-auto-height" data-bind="text: TotalComprobante"></td>
        <td class="col-md-1 col-md-auto-height">
          <center><span data-bind="text: EstadoCE"></span></center>
        </td>
        <td class="col-md-auto col-md-auto-height" align="right">
          <span class="fa fa-fw" style="font-size: 2em" data-bind="css: Icono, style:{color: Color}"></span>
        </td>
        <td class="col-md-1 col-md-auto-height">
          <div data-bind="css: VistaCheck" align="left">
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
<br />
