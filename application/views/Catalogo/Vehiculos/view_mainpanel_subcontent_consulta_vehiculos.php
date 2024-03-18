<div class="scrollable scrollbar-macosx">
  <?php echo $view_subcontent_buscador_vehiculos; ?>
  <fieldset>
    <table id="TablaConsultaVehiculos" class="datalist__table table display table-border" width="100%" data-products="brand">
      <thead>
        <tr>
          <th class="products__id">Código</th>
          <th class="products__title">Radio Taxi</th>
          <th class="col-md-2 products__title">Numero Placa</th>
          <th class="products__title">Ultimo Kilometraje</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <!-- ko foreach : Vehiculos -->
        <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdVehiculo }">
          <td align="left" data-bind="text : IdVehiculo">
            <input type="hidden" data-bind="value : IdVehiculo">
          </td>
          <td data-bind="text : NombreRadioTaxi" ></td>
          <td class="col-md-2" data-bind="text : NumeroPlaca"></td>
          <td data-bind="text : UltimoKilometraje"></td>
          <td align="center" >
            <div data-bind="css: VistaOpciones">
              <button data-bind="click : $root.OnClickBtnEditar , attr : { id : IdVehiculo() + '_btnEditar' } "
                class="btn btn-sm btn-warning btn-operaciones" title="Editar Vehiculo">
                <span class="glyphicon glyphicon-pencil"></span>
              </button>
            </div>
          </td>
          <td align="center" >
            <div data-bind="css: VistaOpciones">
              <button data-bind="click : $root.OnClickBtnEliminar"
                class="btn btn-sm btn-danger btn-operaciones" title="Eliminar Vehiculo">
                <span class="glyphicon glyphicon-trash"></span>
              </button>
            </div>
          </td>
    </tr>
    <!-- /ko -->
  </tbody>
</table>

  </fieldset>
      <?php echo $view_subcontent_paginacion_vehiculos; ?>
</div>
