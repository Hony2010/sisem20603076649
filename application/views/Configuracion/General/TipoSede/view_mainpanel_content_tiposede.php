<!-- ko with : vmgTipoSede.dataTipoSede -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Tipo Sede &nbsp; <button id="btnAgregarTipoSede" class="btn btn-info ocultar" type="button" data-bind="click : vistaModeloGeneral.vmgTipoSede.AgregarTipoSede"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
        <!-- table-hover -->
        <table id="DataTables_Table_0_tiposede" class="datalist__table table display table-border" width="100%" data-products="brand" >
          <thead>
            <tr>
              <th class="products__id">Código</th>
              <th class="products__title">Nombre</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <!-- ko foreach : TiposSede -->
            <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloGeneral.vmgTipoSede.Seleccionar, attr : { id: IdTipoSede() +'_tr_tiposede' }">

              <td data-bind="text : IdTipoSede, click:vistaModeloGeneral.vmgTipoSede.FilaButtonsTipoSede"></td>

              <td data-bind="event : { click : vistaModeloGeneral.vmgTipoSede.OnClickTipoSede },attr : { id : IdTipoSede() + '_td_NombreTipoSede'}">
                <span class="class_SpanTipoSede" data-bind="text : NombreTipoSede , visible : true , attr : { id : IdTipoSede() + '_span_NombreTipoSede'}"></span>
                <input name="NombreTipoSede" class="class_InputTipoSede form-control formulario"
                data-bind="value : NombreTipoSede ,
                visible : false , attr : { id : IdTipoSede() + '_input_NombreTipoSede' } ,
                event : { keyup : vistaModeloGeneral.vmgTipoSede.OnKeyUpTipoSede }"
                type="text" >

              </td>

              <td align="center" data-bind="click:vistaModeloGeneral.vmgTipoSede.FilaButtonsTipoSede">
                  <button class="btn btn-sm btn-success guardar_button_TipoSede btn-operaciones" data-bind="visible : false, attr : { id : IdTipoSede() + '_button_TipoSede' } , click : vistaModeloGeneral.vmgTipoSede.GuardarTipoSede, css: VistaOptions" data-toogle="tooltip" title="Guardar" >
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                  </button>
                  <button data-bind="attr : { id : IdTipoSede() + '_editar_button_TipoSede' } , click : vistaModeloGeneral.vmgTipoSede.EditarTipoSede, css: VistaOptions" class="btn btn-sm btn-warning editar_button_TipoSede btn-operaciones" data-toogle="tooltip" title="Editar">
                    <span class="glyphicon glyphicon-pencil"></span>
                  </button>
                  <button data-bind="attr : { id : IdTipoSede() + '_borrar_button_TipoSede' } , click : vistaModeloGeneral.vmgTipoSede.PreBorrarTipoSede, css: VistaOptions" class="btn btn-sm btn-danger borrar_button_TipoSede btn-operaciones" data-toogle="tooltip" title="Borrar">
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
