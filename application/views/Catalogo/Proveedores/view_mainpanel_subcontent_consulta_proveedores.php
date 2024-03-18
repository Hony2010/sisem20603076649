<div class="scrollable scrollbar-macosx">
  <?php echo $view_subcontent_buscador_proveedores; ?>
  <fieldset>
    <table id="TablaConsultaProveedores" class="datalist__table table display table-border" width="100%" data-products="brand">
      <thead>
        <tr>
          <th class="products__id">Código</th>
          <th  class="products__title"> Nombre/Razón social</th>
          <th class="products__title">Tipo</th>
          <th class="products__title">Número Doc.</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <!-- ko foreach : Proveedores -->
        <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdPersona }">
          <td align="left" data-bind="text : IdPersona">
            <input type="hidden" data-bind="value : IdPersona">
          </td>
          <td class="col-md-6" data-bind="text : RazonSocial" ></td>
          <td data-bind="text : NombreTipoPersona"></td>
          <td data-bind="text : NumeroDocumentoIdentidad"></td>
          <td align="center" >
            <button data-bind="click : $root.OnClickBtnEditar , attr : { id : IdPersona() + '_btnEditar' } "
            class="btn btn-sm btn-warning btn-operaciones" data-toogle="tooltip" title="Editar Proveedor">
              <span class="glyphicon glyphicon-pencil"></span>
            </button>
          </td>
          <td align="center">
            <button data-bind="click : $root.OnClickBtnEliminar"
            class="btn btn-sm btn-danger btn-operaciones" data-toogle="tooltip" title="Eliminar Proveedor">
              <span class="glyphicon glyphicon-trash"></span>
            </button>
          </td>
        </tr>
        <!-- /ko -->
      </tbody>
    </table>
  </fieldset>
      <?php echo $view_subcontent_paginacion_proveedores; ?>
</div>
