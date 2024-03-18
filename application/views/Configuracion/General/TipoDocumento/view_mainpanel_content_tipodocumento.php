<!-- ko with : vmgTipoDocumento.dataTipoDocumento-->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Tipo de Documento &nbsp; <button id="btnAgregarTipoDocumento" class="btn btn-info" type="button" data-bind="click : vistaModeloGeneral.vmgTipoDocumento.AgregarTipoDocumento"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <!-- <div class="scrollable scrollbar-macosx"> -->
        <!-- table-hover -->
        <table id="DataTables_Table_0_tipodocumento" class="datalist__table table display table-border" width="100%" data-products="brand" >
          <thead>
            <tr>
              <th class="products__id col-md-1">Código</th>
              <th class="products__title col-md-5">Nombre</th>
              <th class="products__title col-md-1">Abreviación</th>
              <th class="products__title col-md-3 text-right">Modulos</th>
              <th class="col-md-2">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <!-- ko foreach : TiposDocumento -->
            <tr class="clickable-row text-uppercase" data-bind="attr : { id: IdTipoDocumento() +'_tr_tipodocumento' }">
              <td class ="col-md-1" data-bind="event : { click : vistaModeloGeneral.vmgTipoDocumento.OnClickTipoDocumento },attr : { id : IdTipoDocumento() + '_td_CodigoTipoDocumento'}">
                <span class="class_SpanTipoDocumento" data-bind="text : CodigoTipoDocumento , visible : true , attr : { id : IdTipoDocumento() + '_span_CodigoTipoDocumento'}"></span>
                <input name="CodigoTipoDocumento" class="class_InputTipoDocumento form-control formulario"
                data-bind="value : CodigoTipoDocumento ,
                visible : false , attr : { id : IdTipoDocumento() + '_input_CodigoTipoDocumento' } ,
                event : { keyup : vistaModeloGeneral.vmgTipoDocumento.OnKeyUpTipoDocumento }"
                type="text" >
              </td>

              <td class="col-md-5" data-bind="event : { click : vistaModeloGeneral.vmgTipoDocumento.OnClickTipoDocumento },attr : { id : IdTipoDocumento() + '_td_NombreTipoDocumento'}">
                <span class="class_SpanTipoDocumento" data-bind="text : NombreTipoDocumento , visible : true , attr : { id : IdTipoDocumento() + '_span_NombreTipoDocumento'}"></span>
                <input name="NombreTipoDocumento" class="class_InputTipoDocumento form-control formulario"
                data-bind="value : NombreTipoDocumento ,
                visible : false , attr : { id : IdTipoDocumento() + '_input_NombreTipoDocumento'},
                event : { keyup : vistaModeloGeneral.vmgTipoDocumento.OnKeyUpTipoDocumento }"
                type="text" >
              </td>
              <td class="col-md-1" data-bind="event : { click : vistaModeloGeneral.vmgTipoDocumento.OnClickTipoDocumento },attr : { id : IdTipoDocumento() + '_td_NombreAbreviado'}">
                <span class="class_SpanTipoDocumento" data-bind="text : NombreAbreviado , visible : true , attr : { id : IdTipoDocumento() + '_span_NombreAbreviado'}"></span>
                <input name="NombreAbreviado" class="class_InputTipoDocumento form-control formulario"
                data-bind="value : NombreAbreviado ,
                visible : false , attr : { id : IdTipoDocumento() + '_input_NombreAbreviado'},
                event : { keyup : vistaModeloGeneral.vmgTipoDocumento.OnKeyUpTipoDocumento }"
                type="text" >
              </td>
              <td class="col-md-3" align="right" data-bind="attr : { id : IdTipoDocumento() + '_td_ModuloSistema'}">
                <button type="button" style="width:auto;" data-bind="visible : true, attr : { id : IdTipoDocumento() + '_span_ModuloSistema'}, event : { click : vistaModeloGeneral.vmgTipoDocumento.OnClickTipoDocumento}" class="btn btn-sm btn-default class_SpanTipoDocumento btn-operaciones" data-toogle="tooltip">
                  MODULOS SISTEMA
                  <span  class="badge" data-bind="text:NumeroItemsSeleccionadas , attr:{id:IdTipoDocumento()+'_span_numero_items'}"></span>
                </button>
                  <div class="multiselect-native-select class_InputTipoDocumento" data-bind = "visible : false , attr :{ id : IdTipoDocumento() + '_input_ModuloSistema'}">
                      <div class="btn-group">
                          <button type="button" class="multiselect dropdown-toggle btn btn-default form-control formulario" data-toggle="dropdown" title="None Selected">
                              <span class="multiselect-selected-text">Modulos Sistema </span><span  class="badge" data-bind="text:NumeroItemsSeleccionadas , attr:{id:IdTipoDocumento()+'_numero_items'}"></span>
                              <b class="caret"></b>
                          </button>
                          <ul class="multiselect-container dropdown-menu" data-bind="css: estilo_combo">
                              <li>
                                <div class="form-group">
                                  <div class="inpu-group">
                                    <div class="checkbox">
                                      <input type="checkbox" data-bind="checked: SeleccionarTodos, attr : {id: IdTipoDocumento() + '_selector_modulo_todos'}, event: {change: SeleccionarTodasItems}" />
                                      <label data-bind = "attr : {for : IdTipoDocumento() + '_selector_modulo_todos'}" class="checkbox"> <b>Seleccionar Todos </b></label>
                                    </div>
                                  </div>
                                </div>
                              </li>
                              <!-- ko foreach: ModulosSistema -->
                              <li style="text-transform: none;">
                                  <div class="checkbox">
                                      <input type="checkbox" data-bind="checked: Seleccionado, event:{change: $parent.CambioModuloSistema},  attr : { id: $parent.IdTipoDocumento() +'_'+ IdModuloSistema()+'_moduloSistema'}"/>
                                      <label class="checkbox" data-bind="text: NombreModuloSistema, attr : {for : $parent.IdTipoDocumento() +'_'+ IdModuloSistema()+'_moduloSistema'}"></label>
                                  </div>
                              </li>
                              <!-- /ko -->
                          </ul>
                      </div>
                  </div>
              </td>
              <td class="col-md-2" align="center" data-bind="click:vistaModeloGeneral.vmgTipoDocumento.FilaButtonsTipoDocumento" style="min-width:150px">
                <button class="btn btn-sm btn-success guardar_button_TipoDocumento btn-operaciones" data-bind="visible : false, attr : { id : IdTipoDocumento() + '_button_TipoDocumento' } , click : vistaModeloGeneral.vmgTipoDocumento.GuardarTipoDocumento" data-toogle="tooltip" title="Guardar" >
                  <span class="glyphicon glyphicon-floppy-disk"></span>
                </button>
                <button data-bind="attr : { id : IdTipoDocumento() + '_editar_button_TipoDocumento' } , click : vistaModeloGeneral.vmgTipoDocumento.EditarTipoDocumento" class="btn btn-sm btn-warning editar_button_TipoDocumento btn-operaciones" data-toogle="tooltip" title="Editar">
                  <span class="glyphicon glyphicon-pencil"></span>
                </button>
                <button data-bind="attr : { id : IdTipoDocumento() + '_borrar_button_TipoDocumento' } , click : vistaModeloGeneral.vmgTipoDocumento.PreBorrarTipoDocumento, css: VistaOptions" class="btn btn-sm btn-danger borrar_button_TipoDocumento btn-operaciones" data-toogle="tooltip" title="Borrar">
                  <span class="glyphicon glyphicon-trash"></span>
                </button>
              </td>
              </tr>
            <!-- /ko -->
          </tbody>
        </table>
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- /ko -->
