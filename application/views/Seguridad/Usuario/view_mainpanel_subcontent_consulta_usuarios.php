<div class="scrollable scrollbar-macosx">
  <?php echo $view_subcontent_buscador_usuario; ?>
  <table class="datalist__table table display" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="products__id">Código</th>
        <th  class="products__title">Nombre de Usuario</th>
        <th  class="products__title">Alias para Emitir Venta</th>
        <th class="products__title">Nombre Empleado</th>
        <th class="products__title">Cargo</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : Usuarios -->
      <tr class="clickable-row" data-bind="click : Seleccionar, attr : { id: IdUsuario }, css: IndicadorEstadoUsuario() ? '' : 'anulado'" style="text-transform: UpperCase;">

        <td align="left" data-bind="text : IdUsuario">
          <input type="hidden" data-bind="value : IdUsuario">
        </td>

        <td data-bind="text : NombreUsuario"></td>
        <td data-bind="text : AliasUsuarioVenta"></td>
        <td>
          <span data-bind="text : NombreCompleto"></span>
          <span data-bind="text : ApellidoCompleto"></span>
        </td>
        <td data-bind="text : NombreRol"></td>

        <td align="center" >
            <button data-bind="click : Editar , attr : { id : IdUsuario() + '_btnEditar' } "
                class="btn btn-sm btn-warning btn-operaciones" data-toogle="tooltip" title="Editar Usuario">
                <span class="glyphicon glyphicon-pencil"></span>
            </button>
            <button data-bind="click : PreEliminar"
              class="btn btn-sm btn-danger btn-operaciones" data-toogle="tooltip" title="Eliminar Usuario">
              <span class="glyphicon glyphicon-trash"></span>
            </button>
        </td>
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</div>
