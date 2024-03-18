<fieldset>
  <table class="datalist__table table display table-border" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="text-left">Documento</th>
        <th class="text-center">Fec. Oper.</th>
        <th class="text-left">Doc. Referencia</th>
        <th class="text-left">Motivo</th>
        <th class="text-right">Ingreso</th>
        <th class="text-right">Salida</th>
        <th class="text-right">Caja</th>
        <th class="text-right">Medio de Pago</th>
        <th class="text-right">Usuario</th>
        <th width="41" ></th>
        <th width="41"></th>
        <th width="41"></th>
        <th width="41"></th>
        <th width="41"></th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : ComprobantesCaja -->
      <tr class="clickable-row" data-bind=" event: { click : $root.Seleccionar }, attr : { id: IdComprobanteCaja }, css: IndicadorEstado() == 'N' ? 'anulado' : ''">
        <td class="text-left" data-bind="text : NombreAbreviado() + ' ' + SerieDocumento() + ' - ' + NumeroDocumento()"></td>
        <td class="text-center" data-bind="text : FechaComprobante"></td>
        <td class="text-left" data-bind="text : DocumentoReferencia"></td>
        <td class="text-left" data-bind="text : NombreConceptoCaja"></td>
        <td class="text-right" data-bind="text : MontoIngreso"></td>
        <td class="text-right" data-bind="text : MontoSalida"></td>
        <td class="text-right" data-bind="text : NombreCaja"></td>
        <td class="text-right" data-bind="text : NombreAbreviadoMedioPago"></td> 
        <td class="text-right" data-bind="text : NombreUsuario "></td>
        <td lass="text-center">
          <button class="btn btn-sm btn-info btn-consulta" data-toogle="tooltip" title="Ver Comprobante Caja" data-bind="event: { click : $root.OnClickBtnVer }">
            <span class="fa fa-fw fa-eye"></span>
          </button>
        </td>
        <td  lass="text-center">
          <button  class="btn btn-sm btn-warning btn-consulta" data-toogle="tooltip" title="Editar Comprobante Caja"  data-bind="event: { click : $root.OnClickBtnEditar }, disable : OnDisableBtnEditar()">
            <span class="glyphicon glyphicon-pencil"></span>
          </button>
        </td>
        <td lass="text-center">
          <button class="btn btn-sm btn-baja btn-consulta"  data-toogle="tooltip" title="Anular Comprobante Caja" data-bind="event: { click : $root.OnClickBtnAnular }, disable : OnDisableBtnAnular">
            <span class="fa fa-fw fa-times"></span>
          </button>
        </td>
        <td lass="text-center">
          <button  class="btn btn-sm btn-danger btn-consulta" data-toogle="tooltip" title="Eliminar Comprobante Caja" data-bind="event: { click : $root.OnClickBtnEliminar }, disable : OnDisableBtnBorrar">
            <span class="glyphicon glyphicon-trash"></span>
          </button>
        </td>
        <td lass="text-center">
          <button class="btn btn-sm btn-print btn-consulta" data-toogle="tooltip" title="Imprimir Comprobante Caja" data-bind="event: { click : $root.OnClickBtnImprimir }">
            <span class="glyphicon glyphicon-print"></span>
          </button>
        </td>
      </tr>
      <!-- /ko -->
  </tbody>
</table>
</fieldset>
