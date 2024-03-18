<fieldset>
  <table class="datalist__table table display table-border" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="text-left">Documento</th>
        <th class="text-center">F. Transf.</th>
        <th class="text-left">Almacén Origen</th>
        <th class="text-left">Almacén Destino</th>
        <th class="text-left">Estado</th>
        <th width="41" ></th>
        <th width="41"></th>
        <th width="41"></th>
        <th width="41"></th>
        <th width="41"></th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : TransferenciasAlmacen -->
      <tr class="clickable-row" data-bind=" event: { click : $root.Seleccionar }, attr : { id: IdTransferenciaAlmacen }, css: IndicadorEstado() == 'N' ? 'anulado' : ''">
        <td class="text-left" data-bind="text: Documento"></td>
        <td class="text-center" data-bind="text: FechaTraslado"></td>
        <td class="text-left" data-bind="text: NombreSedeOrigen"></td>
        <td class="text-left" data-bind="text: NombreSedeDestino"></td>
        <td class="text-left" data-bind="text: IndicadorEstado() == 'N' ? 'ANULADO' : 'ACTIVO'"></td>
        <td lass="text-center">
          <button class="btn btn-sm btn-info btn-consulta" data-toogle="tooltip" title="Ver Transferecia Almacén" data-bind="event: { click : $root.OnClickBtnVer }">
            <span class="fa fa-fw fa-eye"></span>
          </button>
        </td>
        <td  lass="text-center">
          <button  class="btn btn-sm btn-warning btn-consulta" data-toogle="tooltip" title="Editar Transferecia Almacén"  data-bind="event: { click : $root.OnClickBtnEditar }, disable : TieneAccesoEditar">
            <span class="glyphicon glyphicon-pencil"></span>
          </button>
        </td>
        <td lass="text-center">
          <button class="btn btn-sm btn-baja btn-consulta"  data-toogle="tooltip" title="Anular Transferecia Almacén" data-bind="event: { click : $root.OnClickBtnAnular }, disable : TieneAccesoAnular">
            <span class="fa fa-fw fa-times"></span>
          </button>
        </td>
        <td lass="text-center">
          <button  class="btn btn-sm btn-danger btn-consulta" data-toogle="tooltip" title="Eliminar Transferecia Almacén" data-bind="event: { click : $root.OnClickBtnEliminar }, disable : TieneAccesoEliminar">
            <span class="glyphicon glyphicon-trash"></span>
          </button>
        </td>
        <!-- <td lass="text-center">
          <button class="btn btn-sm btn-print btn-consulta" data-toogle="tooltip" title="Imprimir Transferecia Almacén" data-bind="event: { click : $root.OnClickBtnImprimir }">
            <span class="glyphicon glyphicon-print"></span>
          </button>
        </td> -->
      </tr>
      <!-- /ko -->
  </tbody>
</table>
</fieldset>
