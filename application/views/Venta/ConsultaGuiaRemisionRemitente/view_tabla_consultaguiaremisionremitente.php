<fieldset>
  <table class="datalist__table table display table-border" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="">Documento</th>
        <th class="">F. Emisión</th>
        <th class="">Destinatario</th>
        <th class="">DNI/RUC Dest.</th>
        <th class="">Transportista</th>
        <th class="">DNI/RUC Transp.</th>
        <th class="">Estado</th>
        <th class="">Usuario</th>
        <th class="">Situacion CPE</th>
        <th width="41"></th>
        <th width="41"></th>
        <th width="41"></th>
        <th width="41"></th>
        <th width="41"></th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : GuiasRemisionRemitente -->
      <tr class="clickable-row" data-bind="click : $root.Seleccionar, attr : { id: IdGuiaRemisionRemitente }, css: IndicadorEstado() == 'N' ? 'anulado' : ''">
        <td class="">
          <span data-bind="text : Numero"></span>
        </td>
        <td class="">
          <span data-bind="text : FechaEmision"></span>
        </td>
        <td class="">
          <span data-bind="text : RazonSocialDestinatario"></span>
        </td>
        <td class="">
          <span data-bind="text : NumeroDocumentoIdentidadDestinatario"></span>
        </td>
        <td class="">
          <span data-bind="text : RazonSocialTransportista"></span>
        </td>
        <td class="">
          <span data-bind="text : NumeroDocumentoIdentidadTransportista"></span>
        </td>
        <td class="">
          <span data-bind="text : EstadoGuiaRemisionRemitente"></span>
        </td>
        <td class="">
          <span data-bind="text : UsuarioRegistro"></span>
        </td>
        <td class="">
          <span data-bind="text: AbreviaturaSituacionCPE"></span>
        </td>
        <td class="">
          <button class="btn btn-info btn-consulta" title="Ver Guia Remisión" data-bind="click: $root.OnClickBtnVer">
            <span class="fa fa-fw fa-eye"></span>
          </button>
        </td>
        <td class="">
          <button class="btn btn-warning btn-consulta" title="Editar Guia Remisión" data-bind="click: $root.OnClickBtnEditar, enable: OnEnableBtnEditar">
            <span class="glyphicon glyphicon-pencil"></span>
          </button>
        </td>
        <td class="">
          <button class="btn btn-baja btn-consulta" title="Anular Guia Remisión" data-bind="click: $root.Anular, enable: OnEnableBtnAnular">
            <span class="fa fa-fw fa-times"></span>
          </button>
        </td>
        <td class="">
          <button class="btn btn-danger btn-consulta" title="Eliminar Guia Remisión" data-bind="click: $root.Eliminar, enable: OnEnableBtnEliminar">
            <span class="glyphicon glyphicon-trash"></span>
          </button>
        </td>
        <td class="">
          <button class="btn btn-print btn-consulta" title="Imprimir Guia Remisión" data-bind="click : $root.OnClickBtnImprimir">
            <span class="glyphicon glyphicon-print"></span>
          </button>
        </td>
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</fieldset>