<!-- ko with : $root.data.Buscador  -->
<?php echo $view_subcontent_buscador_consultacomprobanteelectronico; ?>
<!-- /ko -->
<fieldset>
  <table class="datalist__table table" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="products__id col-sm-2">Documento</th>
        <th  class="products__title col-sm-1 text-center">Fecha Emisión</th>
        <th  class="products__title col-sm-3 text-center">Cliente</th>
        <th class="products__title col-sm-1 text-center">RUC/DNI</th>
        <th class="products__title col-sm-1 text-right" >Monto</th>
        <th class="col-sm-2 text-center">Estado</th>
        <th class="col-sm-1 text-center">Cod. Error</th>
        <th class="col-sm-1 text-center">PDF</th>
        <th class="col-sm-1 text-center">XML</th>
        <th class="col-sm-1 text-center">CDR</th>
        <!-- ko if: ParametroEnvioEmail() == 1-->
        <th class="col-sm-1 text-center">EMAIL</th>
        <!-- /ko -->
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : ConsultasComprobanteElectronico -->
      <tr class="clickable-row" data-bind="click: Seleccionar, attr : { id: IdComprobanteVenta }" style="text-transform: UpperCase;">
        <td class="col-sm-2"data-bind="text: Numero">FT F0001-4256</td>
        <td class="col-sm-1"data-bind="text: FechaFormateada">05/05/2018</td>
        <td class="col-sm-4"data-bind="text: RazonSocialCliente">Juan Casco Mendoza</td>
        <td class="col-sm-1"data-bind="text: NumeroDocumentoIdentidad">20449458436</td>
        <td align="right" class="col-sm-1"data-bind="text: TotalComprobante">1200.00</td>
        <td class="col-sm-2"  align="right">
          <span data-bind="text: EstadoCE"></span>
          <span class="fa fa-fw" style="font-size: 2em" data-bind="css: Icono, style:{color: Color}"></span>
        </td>

        <td align="center" class="col-sm-1" data-bind="text: CodigoRespuesta, attr:{'title': DescripcionRespuesta()}"></td>
        <td class="col-sm-1" align="center" >
          <button data-bind="click : GenerarPDF, attr : { id : IdComprobanteVenta() + '_btnGenerarPDF' } "
          class="btn btn-danger btn-operaciones" data-toogle="tooltip" title="Generar PDF">
            <span class="far fa-file-pdf fa-lg"></span>
          </button>
        </td>
        <td class="col-sm-1" align="center" >
          <button data-bind="attr : { id : IdComprobanteVenta() + '_btnDescargarXML', name : '<?php echo site_url();?>/FacturacionElectronica/cComprobanteElectronico/DescargarXML?nombre=' +  NombreArchivoComprobante(), value: NombreArchivoComprobante()} "
          class="btn btn-warning DescargaXML btn-operaciones" data-toogle="tooltip" title="Descargar XML">
            <span class="far fa-file-code fa-lg"></span>
          </button>
        </td>
        <td class="col-sm-1" align="center" >
          <div data-bind="css: VistaCDR">
            <button data-bind="attr : { id : IdComprobanteVenta() + '_btnDescargarCDR', name : '<?php echo site_url();?>/FacturacionElectronica/cComprobanteElectronico/DescargarCDR?nombre=' +  NombreArchivoComprobante(), value: NombreArchivoComprobante()} "
              class="btn btn-info DescargaCDR btn-operaciones" data-toogle="tooltip" title="Descargar XML">
              <span class="far fa-file-code fa-lg"></span>
            </button>
          </div>
        </td>
        <!-- ko if: $parent.ParametroEnvioEmail() == 1-->
        <td class="col-sm-1" align="center" >
          <button data-bind="attr : { id : IdComprobanteVenta() + '_btnEnviarXML'}, event: {click: $root.AbrirModalDeEnvioDeComprobantes}, disable: IdCliente() == '1'"
            class="btn btn-success btn-operaciones" data-toogle="tooltip" title="Enviar Email Cliente">
            <span class="fas fa-at fa-lg"></span>
          </button>
        </td>
        <!-- /ko -->
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</fieldset>
<p>
