<!-- ko with : vmgSede.dataSede -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Sede&nbsp; <button id="btnAgregarSede" class="btn btn-info" type="button" data-bind="click : vistaModeloGeneral.vmgSede.AgregarSede"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="tab-pane active" id="brand_sede" role="tabpanel"  >
      <div class="container-fluid">
        <!-- table-hover -->
        <table id="DataTables_Table_0_sede" class="datalist__table table display table-border" width="100%" data-products="brand" >
          <thead>
            <tr>
              <th class="products__id">Código</th>
              <th class="products__title">Nombre</th>
              <th class="products__title">Dirección</th>
              <th class="products__title">Tipo Sede</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <!-- ko foreach : Sedes -->
            <tr class="clickable-row text-uppercase" data-bind=" attr : { id: IdSede() +'_tr_sede' }">

              <td data-bind="event : { click : vistaModeloGeneral.vmgSede.OnClickSede },attr : { id : IdSede() + '_td_CodigoSede'}">
                <span class="class_SpanSede" data-bind="text : CodigoSede , visible : true , attr : { id : IdSede() + '_span_CodigoSede'}"></span>
                <input name="CodigoSede" class="class_InputSede form-control formulario"
                data-bind="value : CodigoSede ,
                visible : false , attr : { id : IdSede() + '_input_CodigoSede' } ,
                event : { keyup : vistaModeloGeneral.vmgSede.OnKeyUpSede }"
                type="text" style="width : 100%">

              </td>

              <td data-bind="event : { click : vistaModeloGeneral.vmgSede.OnClickSede },attr : { id : IdSede() + '_td_NombreSede'}">
                <span class="class_SpanSede" data-bind="text : NombreSede , visible : true , attr : { id : IdSede() + '_span_NombreSede'}"></span>
                <input name="NombreSede" class="class_InputSede form-control formulario"
                data-bind="value : NombreSede ,
                visible : false , attr : { id : IdSede() + '_input_NombreSede' } ,
                event : { keyup : vistaModeloGeneral.vmgSede.OnKeyUpSede }"
                type="text">

              </td>

              <td data-bind="event : { click : vistaModeloGeneral.vmgSede.OnClickSede },attr : { id : IdSede() + '_td_Direccion'}">
                <span class="class_SpanSede" data-bind="text : Direccion , visible : true , attr : { id : IdSede() + '_span_Direccion'}"></span>
                <input name="Direccion" class="class_InputSede form-control formulario"
                data-bind="value : Direccion ,
                visible : false , attr : { id : IdSede() + '_input_Direccion' } ,
                event : { keyup : vistaModeloGeneral.vmgSede.OnKeyUpSede }"
                type="text">
              </td>
              <td align="right" data-bind="attr : { id : IdSede() + '_td_TipoSede'}">
                <button type="button" style="width:auto;" data-bind="visible : true, attr : { id : IdSede() + '_span_TipoSede'}, event : { click : vistaModeloGeneral.vmgSede.OnClickSede}" class="btn btn-sm btn-default class_SpanSede btn-operaciones" data-toogle="tooltip">
                  TIPOS SEDE
                  <span  class="badge" data-bind="text:NumeroItemsSeleccionadas , attr:{id:IdSede()+'_span_numero_items'}"></span>
                </button>
                  <div class="multiselect-native-select class_InputSede" data-bind = "visible : false , attr :{ id : IdSede() + '_input_TipoSede'}">
                      <div class="btn-group">
                          <button type="button" class="multiselect dropdown-toggle btn btn-default form-control formulario" data-toggle="dropdown">
                              <span class="multiselect-selected-text">TIPOS SEDE </span>
                              <span  class="badge" data-bind="text:NumeroItemsSeleccionadas , attr:{id:IdSede()+'_numero_items'}"></span>
                              <b class="caret"></b>
                          </button>
                          <ul class="multiselect-container dropdown-menu" data-bind="">
                              <li>
                                <div class="form-group">
                                  <div class="inpu-group">
                                    <div class="checkbox">
                                      <input type="checkbox" data-bind="checked: SeleccionarTodos, attr : {id: IdSede() + '_selector_tipo_todos'}, event: {change: SeleccionarTodasItems}" />
                                      <label class="checkbox"> <b>Seleccionar Todos </b></label>
                                    </div>
                                  </div>
                                </div>
                              </li>
                              <!-- ko foreach: TiposSede -->
                              <li style="text-transform: none;">
                                  <div class="checkbox">
                                      <input type="checkbox" data-bind="checked: Seleccionado, event:{change: $parent.CambioTipoSede},  attr : { id: $parent.IdSede() +'_'+ IdTipoSede()+'_tipoSede'}"/>
                                      <label class="checkbox" data-bind="text: NombreTipoSede"></label>
                                  </div>
                              </li>
                              <!-- /ko -->
                          </ul>
                      </div>
                  </div>
              </td>
              <td align="center" data-bind="click:vistaModeloGeneral.vmgSede.FilaButtonsSede">
                  <button class="btn btn-sm btn-success guardar_button_Sede btn-operaciones" data-bind="visible : false, attr : { id : IdSede() + '_button_Sede' } , click : vistaModeloGeneral.vmgSede.GuardarSede" data-toogle="tooltip" title="Guardar" >
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                  </button>
                  <button data-bind="attr : { id : IdSede() + '_editar_button_Sede' } , click : vistaModeloGeneral.vmgSede.EditarSede" class="btn btn-sm btn-warning editar_button_Sede btn-operaciones" data-toogle="tooltip" title="Editar">
                    <span class="glyphicon glyphicon-pencil"></span>
                  </button>
                  <button data-bind="attr : { id : IdSede() + '_borrar_button_Sede' } , click : vistaModeloGeneral.vmgSede.PreBorrarSede" class="btn btn-sm btn-danger borrar_button_Sede btn-operaciones" data-toogle="tooltip" title="Borrar">
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
