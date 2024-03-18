<!-- ko with : $root.data.Buscador  -->
<?php echo $view_subcontent_buscador_consultaguiaremisionremitenteelectronica; ?>
<!-- /ko -->
<fieldset>
  <table class="datalist__table table" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="">Documento</th>
        <th class="text-center">Fecha Emisión</th>
        <th class="text-center">Transportista</th>
        <th class="text-center">RUC/DNI</th>
        <th class="text-center">Estado</th>
        <th class="text-center">Cod. Error</th>
        <th class="text-center">PDF</th>
        <th class="text-center">XML</th>
        <th class="text-center">CDR</th>
        <!-- ko if: ParametroEnvioEmail() == 1-->
        <th class="text-center">EMAIL</th>
        <!-- /ko -->
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : ConsultasGuiaRemisionRemitenteElectronica -->
      <tr class="clickable-row text-upercase" data-bind="click: Seleccionar, attr : { id: IdGuiaRemisionRemitente }">
        <td class="" data-bind="text: Numero"></td>
        <td class="" data-bind="text: FechaFormateada"></td>
        <td class="" data-bind="text: RazonSocialTransportista"></td>
        <td class="" data-bind="text: NumeroDocumentoTransportista"></td>
        <td class="text-left">
          <span data-bind="text: EstadoCE"></span>
          <span class="fa fa-fw" style="font-size: 2em" data-bind="css: Icono, style:{color: Color}"></span>
        </td>

        <td class="text-center" data-bind="text: CodigoRespuesta, attr:{'title': DescripcionRespuesta()}"></td>
        <td class="text-center">
          <button data-bind="click : GenerarPDF, attr : { id : IdGuiaRemisionRemitente() + '_btnGenerarPDF' } " class="btn btn-danger btn-operaciones" data-toogle="tooltip" title="Generar PDF">
            <span class="far fa-file-pdf fa-lg"></span>
          </button>
        </td>
        <td class="text-center">
          <button data-bind="attr : { id : IdGuiaRemisionRemitente() + '_btnDescargarXML', name : '<?php echo site_url(); ?>/FacturacionElectronica/cComprobanteElectronico/DescargarXML?nombre=' +  NombreArchivoComprobante(), value: NombreArchivoComprobante()} " class="btn btn-warning DescargaXML btn-operaciones" data-toogle="tooltip" title="Descargar XML">
            <span class="far fa-file-code fa-lg"></span>
          </button>
        </td>
        <td class="text-center">
          <div data-bind="css: VistaCDR">
            <button data-bind="attr : { id : IdGuiaRemisionRemitente() + '_btnDescargarCDR', name : '<?php echo site_url(); ?>/FacturacionElectronica/cComprobanteElectronico/DescargarCDR?nombre=' +  NombreArchivoComprobante(), value: NombreArchivoComprobante()} " class="btn btn-info DescargaCDR btn-operaciones" data-toogle="tooltip" title="Descargar XML">
              <span class="far fa-file-code fa-lg"></span>
            </button>
          </div>
        </td>
        <!-- ko if: $parent.ParametroEnvioEmail() == 1-->
        <td class="text-center">
          <button data-bind="attr : { id : IdGuiaRemisionRemitente() + '_btnEnviarXML'}, event: {click: EnviarEmailCliente}, disable: IdTransportista() == '1'" class="btn btn-success btn-operaciones" data-toogle="tooltip" title="Enviar Email Cliente">
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