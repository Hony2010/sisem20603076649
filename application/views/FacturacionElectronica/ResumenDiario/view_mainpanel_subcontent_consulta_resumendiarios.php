<div class="scrollable scrollbar-macosx">

  <div class="container-fluid">
    <!-- ko with : $root.data.Buscador  -->
    <?php echo $view_subcontent_buscador_resumendiario; ?>
    <!-- /ko -->
    <fieldset>
      <table class="datalist__table table" width="100%" data-products="brand">
        <thead>
          <tr>
            <th class="col-md-auto"><center>Documento</center></th>
            <th class="col-md-auto"><center>Fecha de Emisión</center></th>
            <th class="col-md-4"><center>Cliente</center></th>
            <th class="col-md-1">RUC/DNI</th>
            <th class="col-md-1"><center>Monto</center></th>
            <th class="col-md-1"><center>Estado</center></th>
            <th class="col-md-auto">&nbsp;</th>
            <th class="col-md-1" style="text-align: left;">
              <input id="SelectorTodo" class="input-checkbox" name="SelectorTodo" type="checkbox" data-bind="checked: $root.SelectorTodo, event: {change: $root.SeleccionarTodo}">
              <label class="label-checkbox">Todo</label>
            </th>

          </tr>
        </thead>
        <tbody>
          <!-- ko foreach : ResumenesDiario -->
          <tr class="clickable-row" data-bind="attr : { id: IdComprobanteVenta }" style="text-transform: UpperCase;">
            <td align="center" class="col-md-auto col-md-auto-height" data-bind="text: Numero"></td>
            <td align="center" class="col-md-auto col-md-auto-height" data-bind="text: FechaFormateada"></td>
            <td class="col-md-4 col-md-auto-height" data-bind="text: RazonSocialCliente"></td>
            <td class="col-md-1 col-md-auto-height" data-bind="text: NumeroDocumentoIdentidad"></td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="text: TotalComprobante"></td>
            <td class="col-md-1 col-md-auto-height"><span data-bind="text: EstadoCE"></span></td>
            <td class="col-md-auto col-md-auto-height"><span class="fa fa-fw" style="font-size: 2em" data-bind="css: Icono, style:{color: Color}"></span></td>
            <td  class="col-md-1 col-md-auto-height">
              <div data-bind="css: VistaCheck">
                <input type="checkbox" data-bind="checked: EstadoSelector, event: {change: CambiarEstadoCheck}">
              </div>
            </td>
          </tr>
          <!-- /ko -->
        </tbody>
      </table>
    </fieldset>
    <br>
    <button id="GenerarResumen" class="btn btn-primary" data-bind="event:{click: $root.GenerarResumen}">Generar Resumen y Enviar SUNAT</button>
    <p>
  </div>
</div>
