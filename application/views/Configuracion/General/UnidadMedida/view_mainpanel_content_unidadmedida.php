<!-- ko with : vmgUnidadMedida.dataUnidadMedida -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Unidad de Medida  &nbsp; <button id="btnAgregarUnidadMedida" class="btn btn-info" type="button" data-bind="click : vistaModeloGeneral.vmgUnidadMedida.AbrirUnidadesMedidaOculta"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
        <!-- table-hover -->
        <table id="DataTables_Table_0_unidadmedida" class="datalist__table table display table-border" width="100%" data-products="brand" >
          <thead>
            <tr>
              <th class="products__id">Código</th>
              <th class="products__title">Nombre</th>
              <th class="products__title">Abreviatura</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <!-- ko foreach : UnidadesMedida -->
            <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloGeneral.vmgUnidadMedida.Seleccionar, attr : { id: IdUnidadMedida() +'_tr_unidadmedida' }">

              <td data-bind="text: CodigoUnidadMedidaSunat, click:vistaModeloGeneral.vmgUnidadMedida.OnClickUnidadMedida ,attr : { id : IdUnidadMedida() + '_td_CodigoSunat'}" title="Codigo SUNAT"></td>
              <td data-bind="text: NombreUnidadMedida, click:vistaModeloGeneral.vmgUnidadMedida.OnClickUnidadMedida ,attr : { id : IdUnidadMedida() + '_td_Nombresunat'}" title="Nombre SUNAT"></td>

              <td data-bind="event : { click : vistaModeloGeneral.vmgUnidadMedida.OnClickUnidadMedida },attr : { id : IdUnidadMedida() + '_td_AbreviaturaUnidadMedida'}">
                <span class="class_SpanUnidadMedida" data-bind="text : AbreviaturaUnidadMedida , visible : true , attr : { id : IdUnidadMedida() + '_span_AbreviaturaUnidadMedida'}"></span>
                <input name="AbreviaturaUnidadMedida" class="class_InputUnidadMedida form-control formulario"
                data-bind="value : AbreviaturaUnidadMedida ,
                visible : false , attr : { id : IdUnidadMedida() + '_input_AbreviaturaUnidadMedida' } ,
                event : { keyup : vistaModeloGeneral.vmgUnidadMedida.OnKeyUpUnidadMedida }"
                type="text" style="width : 100%" >

              </td>

              <td align="center" data-bind="click:vistaModeloGeneral.vmgUnidadMedida.FilaButtonsUnidadMedida">
                  <button class="btn btn-sm btn-success guardar_button_UnidadMedida btn-operaciones" data-bind="visible : false, attr : { id : IdUnidadMedida() + '_button_UnidadMedida' } , click : vistaModeloGeneral.vmgUnidadMedida.GuardarUnidadMedida" data-toogle="tooltip" title="Guardar" >
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                  </button>
                  <button data-bind="attr : { id : IdUnidadMedida() + '_editar_button_UnidadMedida' } , click : vistaModeloGeneral.vmgUnidadMedida.EditarUnidadMedida" class="btn btn-sm btn-warning editar_button_UnidadMedida btn-operaciones" data-toogle="tooltip" title="Editar">
                    <span class="glyphicon glyphicon-pencil"></span>
                  </button>
                  <button data-bind="attr : { id : IdUnidadMedida() + '_borrar_button_UnidadMedida' } , click : vistaModeloGeneral.vmgUnidadMedida.PreBorrarUnidadMedida" class="btn btn-sm btn-danger borrar_button_UnidadMedida btn-operaciones" data-toogle="tooltip" title="Borrar">
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
