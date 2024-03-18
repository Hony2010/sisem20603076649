<!-- ko with : vmcTipoServicio.dataTipoServicio -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Tipo de Servicio &nbsp; <button id="btnAgregarTipoServicio" class="btn btn-info" type="button" data-bind="click : vistaModeloCatalogo.vmcTipoServicio.AgregarTipoServicio"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <!-- <p>Tienes 62 clientes.</p> -->
    <div class="datalist__result">
      <dv class="tab-pane active" id="brand_tiposervicio" role="tabpanel"  >
        <div class="scrollable scrollbar-macosx">
            <!-- table-hover -->
            <table id="DataTables_Table_0_tipoServicio" class="datalist__table table display table-border" width="100%" data-products="brand" >
              <thead>
                <tr>
                  <th class="products__id">Código</th>
                  <th class="products__title">Nombre</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <!-- ko foreach : TiposServicio -->
                <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloCatalogo.vmcTipoServicio.Seleccionar, attr : { id: IdTipoServicio() +'_tr_tipoServicio' }">

                  <td data-bind="text : IdTipoServicio, click:vistaModeloCatalogo.vmcTipoServicio.FilaButtonsTipoServicio"></td>

                  <td data-bind="event : { click : vistaModeloCatalogo.vmcTipoServicio.OnClickTipoServicio },attr : { id : IdTipoServicio() + '_td_NombreTipoServicio'}">
                    <span class="class_SpanTipoServicio" data-bind="text : NombreTipoServicio , visible : true , attr : { id : IdTipoServicio() + '_span_NombreTipoServicio'}"></span>
                    <input name="NombreTipoServicio" class="class_InputTipoServicio text-uppercase form-control formulario"
                    data-bind="value : NombreTipoServicio ,
                    visible : false , attr : { id : IdTipoServicio() + '_input_NombreTipoServicio' } ,
                    event : { keyup : vistaModeloCatalogo.vmcTipoServicio.OnKeyUpTipoServicio }"
                    type="text">

                  </td>

                  <td align="center" data-bind="click:vistaModeloCatalogo.vmcTipoServicio.FilaButtonsTipoServicio">
                      <button class="btn btn-sm btn-success guardar_button_TipoServicio btn-operaciones" data-bind="visible : false, attr : { id : IdTipoServicio() + '_button_TipoServicio' } , click : vistaModeloCatalogo.vmcTipoServicio.GuardarTipoServicio" data-toogle="tooltip" title="Guardar" >
                        <span class="glyphicon glyphicon-floppy-disk"></span>
                      </button>
                      <button data-bind="attr : { id : IdTipoServicio() + '_editar_button_TipoServicio' } , click : vistaModeloCatalogo.vmcTipoServicio.EditarTipoServicio" class="btn btn-sm btn-warning editar_button_TipoServicio btn-operaciones" data-toogle="tooltip" title="Editar">
                        <span class="glyphicon glyphicon-pencil"></span>
                      </button>
                      <button data-bind="attr : { id : IdTipoServicio() + '_borrar_button_TipoServicio' } , click : vistaModeloCatalogo.vmcTipoServicio.PreBorrarTipoServicio" class="btn btn-sm btn-danger borrar_button_TipoServicio btn-operaciones" data-toogle="tooltip" title="Borrar">
                        <span class="glyphicon glyphicon-trash"></span>
                      </button>
                  </td>
                </tr>
                <!-- /ko -->
              </tbody>
            </table>
        </div>
    </div>
  </div>
</div>
<!-- /ko -->
