<div class="scrollable scrollbar-macosx">

  <div class="container-fluid">

    <!-- ko with : $root.data.Buscador  -->
    <?php echo $view_subcontent_buscador_comunicacionbaja; ?>
    <!-- /ko -->
    <div>
      <strong class="alert-info">*Sólo se muestran los documentos que previamente han sido ACEPTADOS POR SUNAT y luego se ANULARON.</strong>
    </div>
    <br>
    <fieldset>
      <table class="datalist__table table" width="100%" data-products="brand">
        <thead>
          <tr>
            <th class="col-md-auto"><center>Documento</center></th>
            <th class="col-md-auto"><center>Fecha de Emisión</center></th>
            <th class="col-md-4"><center>Cliente</center></th>
            <th class="col-md-1">RUC/DNI</th>
            <th class="col-md-1"><center>Monto</center></th>
            <th class="col-md-1">Estado</th>
            <th class="col-md-auto">&nbsp;</th>
            <th class="col-md-auto">Motivo de Baja</th>
            <th class="col-md-1">Seleccionar</th>
          </tr>
        </thead>
        <tbody>
          <!-- ko foreach : ComunicacionesBaja -->
          <tr class="clickable-row" data-bind="attr : { id: IdComprobanteVenta }" style="text-transform: UpperCase;">
            <td align="center" class="col-md-auto col-md-auto-height" data-bind="text: Numero"></td>
            <td align="center" class="col-md-auto col-md-auto-height" data-bind="text: FechaFormateada"></td>
            <td class="col-md-4 col-md-auto-height" data-bind="text: RazonSocialCliente"></td>
            <td class="col-md-1 col-md-auto-height" data-bind="text: NumeroDocumentoIdentidad"></td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="text: TotalComprobante"></td>
            <td class="col-md-1 col-md-auto-height"><span data-bind="text: EstadoCE"></span></td>
            <td class="col-md-auto col-md-auto-height" align="right">
              <span class="fa fa-fw" style="font-size: 2em" data-bind="css: Icono, style:{color: Color}"></span>
            </td>
            <td class="col-md-auto col-md-auto-height" data-bind="event : { click : OnClickMotivoBaja }, attr : { id : IdComprobanteVenta() + '_td_MotivoBaja'}">
              <span class="class_SpanTipoCambio" data-bind="text: MotivoBaja"></span>
              <input id="" type="text" class="class_InputTipoCambio form-control formulario"  data-bind="value: MotivoBaja, visible : true, event: {blur: OnBlurMotivoBaja}"/>
            </td>
            <td class="col-md-1 col-md-auto-height">
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
    <button id="GenerarBaja" class="btn btn-primary" data-bind="event:{click: $root.GenerarBaja}">Enviar a SUNAT</button>
    <p>
  </div>
</div>
