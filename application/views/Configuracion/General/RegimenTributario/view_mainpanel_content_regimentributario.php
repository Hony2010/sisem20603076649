<!-- ko with : vmgRegimenTributario.dataRegimenTributario -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Regimen Tributario &nbsp; <button id="btnAgregarRegimenTributario" class="btn btn-info ocultar" type="button" data-bind="click : vistaModeloGeneral.vmgRegimenTributario.AgregarRegimenTributario"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
        <!-- table-hover -->
        <table id="DataTables_Table_0_regimentributario" class="datalist__table table display table-border" width="100%" data-products="brand" >
          <thead>
            <tr>
              <th class="products__id">CÓdigo</th>
              <th class="products__title">Nombre</th>
              <th class="products__title">Abreviatura</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <!-- ko foreach : RegimenesTributario -->
            <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloGeneral.vmgRegimenTributario.Seleccionar, attr : { id: IdRegimenTributario() +'_tr_regimentributario' }">

              <td data-bind="text : IdRegimenTributario"></td><!--, click:vistaModeloGeneral.vmgRegimenTributario.FilaButtonsRegimenTributario-->

              <td data-bind="attr : { id : IdRegimenTributario() + '_td_NombreRegimenTributario'}"> <!-- event : { click : vistaModeloGeneral.vmgRegimenTributario.OnClickRegimenTributario }, -->
                <span class="class_SpanRegimenTributario" data-bind="text : NombreRegimenTributario , visible : true , attr : { id : IdRegimenTributario() + '_span_NombreRegimenTributario'}"></span>
                <input name="NombreRegimenTributario" class="class_InputRegimenTributario form-control formulario"
                data-bind="value : NombreRegimenTributario ,
                visible : false , attr : { id : IdRegimenTributario() + '_input_NombreRegimenTributario' } ,
                event : { keyup : vistaModeloGeneral.vmgRegimenTributario.OnKeyUpRegimenTributario }"
                type="text" >

              </td>

              <td data-bind="attr : { id : IdRegimenTributario() + '_td_NombreAbreviado'}"> <!-- event : { click : vistaModeloGeneral.vmgRegimenTributario.OnClickRegimenTributario }, -->
                <span class="class_SpanRegimenTributario" data-bind="text : NombreAbreviado , visible : true , attr : { id : IdRegimenTributario() + '_span_NombreAbreviado'}"></span>
                <input name="NombreAbreviado" class="class_InputRegimenTributario form-control formulario"
                data-bind="value : NombreAbreviado ,
                visible : false , attr : { id : IdRegimenTributario() + '_input_NombreAbreviado' } ,
                event : { keyup : vistaModeloGeneral.vmgRegimenTributario.OnKeyUpRegimenTributario }"
                type="text" style="width : 100%" >

              </td>

              <td align="center" ><!--data-bind="click:vistaModeloGeneral.vmgRegimenTributario.FilaButtonsRegimenTributario" -->
                  <button class="btn btn-sm btn-success guardar_button_RegimenTributario btn-operaciones" data-bind="visible : false, attr : { id : IdRegimenTributario() + '_button_RegimenTributario' } , click : vistaModeloGeneral.vmgRegimenTributario.GuardarRegimenTributario, css: VistaOptions " data-toogle="tooltip" title="Guardar" >
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                  </button>
                  <button data-bind="attr : { id : IdRegimenTributario() + '_editar_button_RegimenTributario' } , click : vistaModeloGeneral.vmgRegimenTributario.EditarRegimenTributario, css: VistaOptions " class="btn btn-sm btn-warning editar_button_RegimenTributario btn-operaciones" data-toogle="tooltip" title="Editar">
                    <span class="glyphicon glyphicon-pencil"></span>
                  </button>
                  <button data-bind="attr : { id : IdRegimenTributario() + '_borrar_button_RegimenTributario' } , click : vistaModeloGeneral.vmgRegimenTributario.PreBorrarRegimenTributario,css: VistaOptions " class="btn btn-sm btn-danger borrar_button_RegimenTributario btn-operaciones " data-toogle="tooltip" title="Borrar">
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
