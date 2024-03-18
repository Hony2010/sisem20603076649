<!-- ko with : vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Tipo de Documento de Identidad &nbsp; <button id="btnAgregarTipoDocumentoIdentidad" class="btn btn-info" type="button" data-bind="click : vistaModeloCatalogo.vmcTipoDocumentoIdentidad.AgregarTipoDocumentoIdentidad"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <!-- <p>Tienes 62 clientes.</p> -->
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
          <!-- table-hover -->
          <table id="DataTables_Table_0_tipodocumentoidentidad" class="datalist__table table display table-border" width="100%" data-products="brand" >
            <thead>
              <tr>
                <th class="products__id">Código</th>
                <th class="products__title">Nombre</th>
                <th class="products__title">Abreviatura</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <!-- ko foreach : TiposDocumentoIdentidad -->
              <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloCatalogo.vmcTipoDocumentoIdentidad.Seleccionar, attr : { id: IdTipoDocumentoIdentidad()+'_tr_tipoDocumentoIdentidad' }">

                <td data-bind="event : { click : vistaModeloCatalogo.vmcTipoDocumentoIdentidad.OnClickTipoDocumentoIdentidad },attr : { id : IdTipoDocumentoIdentidad() + '_td_CodigoDocumentoIdentidad'}">
                  <span class="class_SpanTipoDocumentoIdentidad" data-bind="text : CodigoDocumentoIdentidad , visible : true , attr : { id : IdTipoDocumentoIdentidad() + '_span_CodigoDocumentoIdentidad'}"></span>
                  <input name="CodigoDocumentoIdentidad" class="class_InputTipoDocumentoIdentidad text-uppercase form-control formulario"
                  data-bind="value : CodigoDocumentoIdentidad ,
                  visible : false , attr : { id : IdTipoDocumentoIdentidad() + '_input_CodigoDocumentoIdentidad' } ,
                  event : { keyup : vistaModeloCatalogo.vmcTipoDocumentoIdentidad.OnKeyUpTipoDocumentoIdentidad }"
                  type="text">
                </td>

                <td data-bind="event : { click : vistaModeloCatalogo.vmcTipoDocumentoIdentidad.OnClickTipoDocumentoIdentidad },attr : { id : IdTipoDocumentoIdentidad() + '_td_NombreTipoDocumentoIdentidad'}">
                  <span class="class_SpanTipoDocumentoIdentidad" data-bind="text : NombreTipoDocumentoIdentidad , visible : true , attr : { id : IdTipoDocumentoIdentidad() + '_span_NombreTipoDocumentoIdentidad'}"></span>
                  <input name="NombreTipoDocumentoIdentidad" class="class_InputTipoDocumentoIdentidad text-uppercase form-control formulario"
                  data-bind="value : NombreTipoDocumentoIdentidad ,
                  visible : false , attr : { id : IdTipoDocumentoIdentidad() + '_input_NombreTipoDocumentoIdentidad' } ,
                  event : { keyup : vistaModeloCatalogo.vmcTipoDocumentoIdentidad.OnKeyUpTipoDocumentoIdentidad }"
                  type="text">
                </td>

                <td data-bind="event : { click : vistaModeloCatalogo.vmcTipoDocumentoIdentidad.OnClickTipoDocumentoIdentidad },attr : { id : IdTipoDocumentoIdentidad() + '_td_NombreAbreviado'}">
                  <span class="class_SpanTipoDocumentoIdentidad" data-bind="text : NombreAbreviado , visible : true , attr : { id : IdTipoDocumentoIdentidad() + '_span_NombreAbreviado'}"></span>
                  <input name="NombreAbreviado" class="class_InputTipoDocumentoIdentidad text-uppercase form-control formulario"
                  data-bind="value : NombreAbreviado ,
                  visible : false , attr : { id : IdTipoDocumentoIdentidad() + '_input_NombreAbreviado' } ,
                  event : { keyup : vistaModeloCatalogo.vmcTipoDocumentoIdentidad.OnKeyUpTipoDocumentoIdentidad }"
                  type="text">

                </td>

                <td align="center" data-bind="click:vistaModeloCatalogo.vmcTipoDocumentoIdentidad.FilaButtonsTipoDocumentoIdentidad">
                    <button class="btn btn-sm btn-success guardar_button_TipoDocumentoIdentidad btn-operaciones" data-bind="visible : false, attr : { id : IdTipoDocumentoIdentidad() + '_button_TipoDocumentoIdentidad' } , click : vistaModeloCatalogo.vmcTipoDocumentoIdentidad.GuardarTipoDocumentoIdentidad" data-toogle="tooltip" title="Guardar" >
                      <span class="glyphicon glyphicon-floppy-disk"></span>
                    </button>
                    <button data-bind="attr : { id : IdTipoDocumentoIdentidad() + '_editar_button_TipoDocumentoIdentidad' } , click : vistaModeloCatalogo.vmcTipoDocumentoIdentidad.EditarTipoDocumentoIdentidad" class="btn btn-sm btn-warning editar_button_TipoDocumentoIdentidad btn-operaciones" data-toogle="tooltip" title="Editar">
                      <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                    <button data-bind="attr : { id : IdTipoDocumentoIdentidad() + '_borrar_button_TipoDocumentoIdentidad' } , click : vistaModeloCatalogo.vmcTipoDocumentoIdentidad.PreBorrarTipoDocumentoIdentidad" class="btn btn-sm btn-danger borrar_button_TipoDocumentoIdentidad btn-operaciones" data-toogle="tooltip" title="Borrar">
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
