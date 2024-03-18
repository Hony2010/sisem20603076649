<!-- ko with : vmgGrupoParametro.dataGrupoParametro -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">GRUPO PARAMETRO &nbsp; <button id="btnAgregarGrupoParametro" class="btn btn-info" type="button" data-bind="click : vistaModeloGeneral.vmgGrupoParametro.AgregarGrupoParametro"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
        <!-- table-hover -->
        <table id="DataTables_Table_0_grupoparametro" class="datalist__table table display table-border" width="100%" data-products="brand" >
          <thead>
            <tr>
              <th class="products__id ocultar">Código</th>
              <th class="products__title">Nombre</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <!-- ko foreach : GruposParametro -->
            <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloGeneral.vmgGrupoParametro.Seleccionar, attr : { id: IdGrupoParametro() +'_tr_grupoparametro' }">

              <td class="ocultar" data-bind="text : IdGrupoParametro, click:vistaModeloGeneral.vmgGrupoParametro.FilaButtonsGrupoParametro"></td>

              <td data-bind="event : { click : vistaModeloGeneral.vmgGrupoParametro.OnClickGrupoParametro },attr : { id : IdGrupoParametro() + '_td_NombreGrupoParametro'}">
                <span class="class_SpanGrupoParametro" data-bind="text : NombreGrupoParametro , visible : true , attr : { id : IdGrupoParametro() + '_span_NombreGrupoParametro'}"></span>
                <input name="NombreGrupoParametro" class="class_InputGrupoParametro form-control formulario"
                data-bind="value : NombreGrupoParametro ,
                visible : false , attr : { id : IdGrupoParametro() + '_input_NombreGrupoParametro' } ,
                event : { keyup : vistaModeloGeneral.vmgGrupoParametro.OnKeyUpGrupoParametro }"
                type="text">

              </td>

              <td align="center" data-bind="click:vistaModeloGeneral.vmgGrupoParametro.FilaButtonsGrupoParametro">
                  <button class="btn btn-sm btn-success guardar_button_GrupoParametro btn-operaciones" data-bind="visible : false, attr : { id : IdGrupoParametro() + '_button_GrupoParametro' } , click : vistaModeloGeneral.vmgGrupoParametro.GuardarGrupoParametro" data-toogle="tooltip" title="Guardar" >
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                  </button>
                  <button data-bind="attr : { id : IdGrupoParametro() + '_editar_button_GrupoParametro' } , click : vistaModeloGeneral.vmgGrupoParametro.EditarGrupoParametro" class="btn btn-sm btn-warning editar_button_GrupoParametro btn-operaciones" data-toogle="tooltip" title="Editar">
                    <span class="glyphicon glyphicon-pencil"></span>
                  </button>
                  <button data-bind="attr : { id : IdGrupoParametro() + '_borrar_button_GrupoParametro' } , click : vistaModeloGeneral.vmgGrupoParametro.PreBorrarGrupoParametro" class="btn btn-sm btn-danger borrar_button_GrupoParametro btn-operaciones" data-toogle="tooltip" title="Borrar">
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
