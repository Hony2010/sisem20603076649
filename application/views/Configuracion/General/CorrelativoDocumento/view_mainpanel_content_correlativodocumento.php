<!-- ko with : vmgCorrelativoDocumento.dataCorrelativoDocumento -->
<div class="main__scroll scrollbar-macosx">
  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title">Correlativo de Documento &nbsp; <button id="btnAgregarCorrelativoDocumento" class="btn btn-info" type="button" data-bind="click : vistaModeloGeneral.vmgCorrelativoDocumento.AgregarCorrelativoDocumento"><u>N</u>uevo</button></h3>
    </div>
    <div class="panel-body">
      <div class="datalist__result">
        <div class="scrollable scrollbar-macosx">
            <!-- table-hover -->
            <table id="DataTables_Table_0_correlativodocumento" class="datalist__table table display table-border" width="100%" data-products="brand" >
              <thead>
                <tr>

                  <th class="products__id ocultar">Código</th>
                  <th class="products__id">Tipo Documento</th>
                  <th class="products__title">Serie Documento</th>
                  <th class="products__title">Ultimo Documento</th>
                  <th class="products__title">Sede</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <!-- ko foreach : CorrelativosDocumento -->
                <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloGeneral.vmgCorrelativoDocumento.Seleccionar, attr : { id: IdCorrelativoDocumento()+'_tr_correlativodocumento' }">
                  <td class="ocultar" data-bind="text: IdCorrelativoDocumento"></td>

                  <td data-bind="event : { click : vistaModeloGeneral.vmgCorrelativoDocumento.OnClickCorrelativoDocumento },attr : { id : IdCorrelativoDocumento() + '_td_IdTipoDocumento'}">
                    <span class="class_SpanCorrelativoDocumento" data-bind="text : NombreAbreviado , visible : true , attr : { id : IdCorrelativoDocumento() + '_span_IdTipoDocumento'}"></span>
                    <div class="class_InputCorrelativoDocumento" data-bind="visible: false, attr : { id : IdCorrelativoDocumento() + '_combo_IdTipoDocumento' }" >
                      <select name="IdTipoCorrelativoDocumento"  class="form-control formulario" data-bind="
                              value : IdTipoDocumento,
                              options : vistaModeloGeneral.vmgCorrelativoDocumento.dataCorrelativoDocumento.TiposDocumento,
                              optionsValue : 'IdTipoDocumento',
                              optionsText : 'NombreAbreviado',
                              attr : { id : IdCorrelativoDocumento() + '_input_IdTipoDocumento' } ">
                      </select>
                    </div>
                  </td>

                  <td data-bind="event : { click : vistaModeloGeneral.vmgCorrelativoDocumento.OnClickCorrelativoDocumento },attr : { id : IdCorrelativoDocumento() + '_td_SerieDocumento'}">
                    <span class="class_SpanCorrelativoDocumento" data-bind="text : SerieDocumento , visible : true , attr : { id : IdCorrelativoDocumento() + '_span_SerieDocumento'}"></span>
                    <input name="NombreCorrelativoDocumento" class="class_InputCorrelativoDocumento form-control formulario"
                    data-bind="value : SerieDocumento ,
                    visible : false , attr : { id : IdCorrelativoDocumento() + '_input_SerieDocumento' } ,
                    event : { keyup : vistaModeloGeneral.vmgCorrelativoDocumento.OnKeyUpCorrelativoDocumento }"
                    type="text">

                  </td>

                  <td data-bind="event : { click : vistaModeloGeneral.vmgCorrelativoDocumento.OnClickCorrelativoDocumento },attr : { id : IdCorrelativoDocumento() + '_td_UltimoDocumento'}">
                    <span class="class_SpanCorrelativoDocumento" data-bind="text : UltimoDocumento , visible : true , attr : { id : IdCorrelativoDocumento() + '_span_UltimoDocumento'}"></span>
                    <input name="Direccion" class="class_InputCorrelativoDocumento form-control formulario"
                    data-bind="value : UltimoDocumento , valueUpdate : 'keyup',
                    visible : false , attr : { id : IdCorrelativoDocumento() + '_input_UltimoDocumento' } ,
                    event : { keydown : vistaModeloGeneral.vmgCorrelativoDocumento.OnKeyUpCorrelativoDocumento }"
                    type="text" >

                  </td>

                  <td data-bind="event : { click : vistaModeloGeneral.vmgCorrelativoDocumento.OnClickCorrelativoDocumento },attr : { id : IdCorrelativoDocumento() + '_td_IdSede'}">
                    <span class="class_SpanCorrelativoDocumento" data-bind="text : NombreSede , visible : true , attr : { id : IdCorrelativoDocumento() + '_span_IdSede'}"></span>
                    <div class="class_InputCorrelativoDocumento" data-bind="visible: false, attr : { id : IdCorrelativoDocumento() + '_combo_IdSede' }" >
                      <select name="IdTipoCorrelativoDocumento"  class="form-control formulario" data-bind="
                              value : IdSede,
                              options : vistaModeloGeneral.vmgCorrelativoDocumento.dataCorrelativoDocumento.Sedes,
                              optionsValue : 'IdSede',
                              optionsText : 'NombreSede',
                              attr : { id : IdCorrelativoDocumento() + '_input_IdSede' } ">
                      </select>
                    </div>
                  </td>

                  <td align="center" data-bind="click:vistaModeloGeneral.vmgCorrelativoDocumento.FilaButtonsCorrelativoDocumento">
                      <button class="btn btn-sm btn-success guardar_button_CorrelativoDocumento btn-operaciones" data-bind="visible : false, attr : { id : IdCorrelativoDocumento() + '_button_CorrelativoDocumento' } , click : vistaModeloGeneral.vmgCorrelativoDocumento.GuardarCorrelativoDocumento" data-toogle="tooltip" title="Guardar" >
                        <span class="glyphicon glyphicon-floppy-disk"></span>
                      </button>
                      <button data-bind="attr : { id : IdCorrelativoDocumento() + '_editar_button_CorrelativoDocumento' } , click : vistaModeloGeneral.vmgCorrelativoDocumento.EditarCorrelativoDocumento" class="btn btn-sm btn-warning editar_button_CorrelativoDocumento btn-operaciones" data-toogle="tooltip" title="Editar">
                        <span class="glyphicon glyphicon-pencil"></span>
                      </button>
                      <button data-bind="attr : { id : IdCorrelativoDocumento() + '_borrar_button_CorrelativoDocumento' } , click : vistaModeloGeneral.vmgCorrelativoDocumento.PreBorrarCorrelativoDocumento" class="btn btn-sm btn-danger borrar_button_CorrelativoDocumento btn-operaciones" data-toogle="tooltip" title="Borrar">
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
</div>
<!-- /ko -->
