<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Costo Servicio&nbsp; <button id="btnAgregarCostoServicio" class="btn btn-info" type="button" data-bind="click : $root.AgregarCostoServicio"><u>N</u>uevo</button></h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                    <div class="scrollable scrollbar-macosx">
                        <fieldset>
                          <table id="DataTables_Table_0" class="datalist__table table display" width="100%" data-products="brand" >
                            <thead>
                              <tr>
                                <th class="products__title">Nombre</th>
                                <th class="products__title">Tipo Producto</th>
                                <th class="products__title">Unidad Medida</th>
                                <th class="products__title">Tipo Existencia</th>
                                <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!-- ko foreach : CostosServicio -->
                              <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdProducto }">
                                <td data-bind="event : { click : $root.OnClickCostoServicio },attr : { id : IdProducto() + '_td_NombreProducto'}">
                                  <span class="class_SpanCostoServicio" data-bind="text : NombreProducto , visible : true , attr : { id : IdProducto() + '_span_NombreProducto'}"></span>
                                  <input name="NombreProducto" class="class_InputCostoServicio form-control formulario"
                                  data-bind="value : NombreProducto ,
                                  visible : false , attr : { id : IdProducto() + '_input_NombreProducto' } ,
                                  event : { keyup : $root.OnKeyUpCostoServicio }">
                                </td>
                                <td data-bind="event : { click : $root.OnClickCostoServicio },attr : { id : IdProducto() + '_td_IdTipoProducto'}">
                                  <span class="class_SpanCostoServicio" data-bind="text : NombreTipoProducto , visible : true , attr : { id : IdProducto() + '_span_IdTipoProducto'}"></span>
                                  <div class="class_InputCostoServicio" data-bind="visible: false, attr : { id : IdProducto() + '_combo_IdTipoProducto' }" >
                                    <select name="IdTipoProducto"  class="form-control formulario" data-bind="
                                    value : IdTipoProducto,
                                    options : $root.data.TiposProducto,
                                    optionsValue : 'IdTipoProducto',
                                    optionsText : 'NombreTipoProducto',
                                    attr : { id : IdProducto() + '_input_IdTipoProducto' } ">
                                  </select>
                                </div>
                              </td>
                              <td data-bind="event : { click : $root.OnClickCostoServicio },attr : { id : IdProducto() + '_td_IdUnidadMedida'}">
                                <span class="class_SpanCostoServicio" data-bind="text : AbreviaturaUnidadMedida , visible : true , attr : { id : IdProducto() + '_span_IdUnidadMedida'}"></span>
                                <div class="class_InputCostoServicio" data-bind="visible: false, attr : { id : IdProducto() + '_combo_IdUnidadMedida' }" >
                                  <select name="IdUnidadMedida"  class="form-control formulario" data-bind="
                                  value : IdUnidadMedida,
                                  options : $root.data.UnidadesMedida,
                                  optionsValue : 'IdUnidadMedida',
                                  optionsText : 'AbreviaturaUnidadMedida',
                                  attr : { id : IdProducto() + '_input_IdUnidadMedida' } ">
                                  </select>
                                </div>
                              </td>

                              <td data-bind="event : { click : $root.OnClickCostoServicio },attr : { id : IdProducto() + '_td_IdTipoExistencia'}">
                                <span class="class_SpanCostoServicio" data-bind="text : NombreTipoExistencia , visible : true , attr : { id : IdProducto() + '_span_IdTipoExistencia'}"></span>
                                <div class="class_InputCostoServicio" data-bind="visible: false, attr : { id : IdProducto() + '_combo_IdTipoExistencia' }" >
                                  <select name="IdTipoExistencia"  class="form-control formulario" data-bind="
                                  value : IdTipoExistencia,
                                  options : $root.data.TiposExistencia,
                                  optionsValue : 'IdTipoExistencia',
                                  optionsText : 'NombreTipoExistencia',
                                  attr : { id : IdProducto() + '_input_IdTipoExistencia' } ">
                                  </select>
                                </div>
                              </td>
                              <td align="center" data-bind="click:$root.FilaButtonsCostoServicio">
                                <button data-bind="visible : false, attr : { id : IdProducto() + '_button_CostoServicio' } , click : $root.GuardarCostoServicio" class="btn btn-sm btn-success guardar_button_CostoServicio btn-operaciones" data-toogle="tooltip" title="Guardar" >
                                  <span class="glyphicon glyphicon-floppy-disk"></span>
                                </button>
                                <button data-bind="attr : { id : IdProducto() + '_editar_button_CostoServicio' } , click : $root.EditarCostoServicio" class="btn btn-sm btn-warning editar_button_CostoServicio btn-operaciones" data-toogle="tooltip" title="Editar">
                                  <span class="glyphicon glyphicon-pencil"></span>
                                </button>
                                <button data-bind="attr : { id : IdProducto() + '_borrar_button_CostoServicio' }, click : $root.PreBorrarCostoServicio" class="btn btn-sm btn-danger borrar_button_CostoServicio btn-operaciones" data-toogle="tooltip" title="Borrar">
                                  <span class="glyphicon glyphicon-trash"></span>
                                </button>
                              </td>
                            </tr>
                            <!-- /ko -->
                          </tbody>
                        </table>
                      </fieldset>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /ko -->
