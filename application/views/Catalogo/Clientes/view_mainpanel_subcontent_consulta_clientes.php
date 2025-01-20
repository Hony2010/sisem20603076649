<div class="scrollable scrollbar-macosx">
  <?php echo $view_subcontent_buscador_clientes; ?>
  <fieldset>
    <table id="TablaConsultaClientes" class="datalist__table table display table-border" width="100%" data-products="brand">
      <thead>
        <tr>
          <th class="products__id">Código</th>
          <th class="products__title"> Nombre/Razón social</th>
          <th class="col-md-2 products__title">Tipo</th>
          <th class="products__title">Número Doc.</th>
          <!-- ko if:(Parametro.ParametroAlumno() == "1" ) -->
          <!-- <th class="text-center products__title">Alumno</th> -->
          <!-- /ko -->
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <!-- ko foreach : Clientes -->
        <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdPersona }">
          <td align="left" data-bind="text : IdPersona">
            <input type="hidden" data-bind="value : IdPersona">
          </td>
          <td data-bind="text : RazonSocial" ></td>
          <td class="col-md-2" data-bind="text : NombreTipoPersona"></td>
          <td data-bind="text : NumeroDocumentoIdentidad"></td>
          <!-- ko if:($parent.Parametro.ParametroAlumno() == "1" ) -->
          <td align="center" data-bind="">
            <div data-bind="css: VistaOpciones">
              <button data-bind="event : {click : $root.ConsultarAlumno}, attr : { id : IdPersona() + '_button_modal_Alumno' } " class="btn btn-sm btn-default btn_subfamiliaproducto btn-operaciones" data-toogle="tooltip" title="Agregar Alumno">
                  <span class="fa fa-fw fa-folder"></span>
              </button>
            </div>
          </td>
          <!-- /ko -->
          <td align="center" >
            <div data-bind="css: VistaOpciones">
              <button data-bind="click : $root.OnClickBtnEditar , attr : { id : IdPersona() + '_btnEditar' } "
                class="btn btn-sm btn-warning btn-operaciones" title="Editar Cliente">
                <span class="glyphicon glyphicon-pencil"></span>
              </button>
            </div>
          </td>
          <td align="center" >
            <div data-bind="css: VistaOpciones">
              <button data-bind="click : $root.OnClickBtnEliminar"
                class="btn btn-sm btn-danger btn-operaciones" title="Eliminar Cliente">
                <span class="glyphicon glyphicon-trash"></span>
              </button>
            </div>
          </td>
    </tr>
    <!-- /ko -->
  </tbody>
</table>

  </fieldset>
      <?php echo $view_subcontent_paginacion_clientes; ?>
</div>
