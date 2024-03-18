<!-- ko with : vmcTipoExistencia.dataTipoExistencia -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Tipo de Existencia &nbsp; <button id="btnAgregarTipoExistencia" class="btn btn-info" type="button" data-bind="click : vistaModeloCatalogo.vmcTipoExistencia.AgregarTipoExistencia"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
          <!-- table-hover -->
          <table id="DataTables_Table_0_tipoExistencia" class="datalist__table table display table-border" width="100%" data-products="brand" >
            <thead>
              <tr>
                <th class="products__id">Código</th>
                <th class="products__title">Nombre</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <!-- ko foreach : TiposExistencia -->
              <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloCatalogo.vmcTipoExistencia.SeleccionarNormal, attr : { id: IdTipoExistencia() + '_tr_TipoExistencia' }">

                <td data-bind="event : { click : vistaModeloCatalogo.vmcTipoExistencia.OnClickTipoExistencia },attr : { id : IdTipoExistencia() + '_td_CodigoTipoExistencia'}">
                  <span class="class_SpanTipoExistencia" data-bind="text : CodigoTipoExistencia , visible : true , attr : { id : IdTipoExistencia() + '_span_CodigoTipoExistencia'}"></span>
                  <input name="CodigoTipoExistencia" class="class_InputTipoExistencia text-uppercase form-control formulario"
                  data-bind="value : CodigoTipoExistencia ,
                  visible : false , attr : { id : IdTipoExistencia() + '_input_CodigoTipoExistencia' } ,
                  event : { keyup : vistaModeloCatalogo.vmcTipoExistencia.OnKeyUpTipoExistencia }"
                  type="text" style="max-width: 65px;">

                </td>

                <td data-bind="event : { click : vistaModeloCatalogo.vmcTipoExistencia.OnClickTipoExistencia },attr : { id : IdTipoExistencia() + '_td_NombreTipoExistencia'}">
                  <span class="class_SpanTipoExistencia" data-bind="text : NombreTipoExistencia , visible : true , attr : { id : IdTipoExistencia() + '_span_NombreTipoExistencia'}"></span>
                  <input name="NombreTipoExistencia" class="class_InputTipoExistencia text-uppercase form-control formulario"
                  data-bind="value : NombreTipoExistencia ,
                  visible : false , attr : { id : IdTipoExistencia() + '_input_NombreTipoExistencia' } ,
                  event : { keyup : vistaModeloCatalogo.vmcTipoExistencia.OnKeyUpTipoExistencia }"
                  type="text" >

                </td>

                <td align="center" data-bind="click:vistaModeloCatalogo.vmcTipoExistencia.FilaButtonsTipoExistencia">
                    <button class="btn btn-sm btn-success guardar_button_TipoExistencia btn-operaciones" data-bind="visible : false, attr : { id : IdTipoExistencia() + '_button_TipoExistencia' } , click : vistaModeloCatalogo.vmcTipoExistencia.GuardarTipoExistencia" data-toogle="tooltip" title="Guardar" >
                      <span class="glyphicon glyphicon-floppy-disk"></span>
                    </button>
                    <button data-bind="attr : { id : IdTipoExistencia() + '_editar_button_TipoExistencia' } , click : vistaModeloCatalogo.vmcTipoExistencia.EditarTipoExistencia, css:VistaOptions" class="btn btn-sm btn-warning editar_button_TipoExistencia btn-operaciones" data-toogle="tooltip" title="Editar">
                      <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                    <button data-bind="attr : { id : IdTipoExistencia() + '_borrar_button_TipoExistencia' } , click : vistaModeloCatalogo.vmcTipoExistencia.PreBorrarTipoExistencia, css:VistaOptions" class="btn btn-sm btn-danger borrar_button_TipoExistencia btn-operaciones" data-toogle="tooltip" title="Borrar">
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
