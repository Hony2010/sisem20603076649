<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Otra Venta &nbsp; <button id="btnAgregarOtraVenta" class="btn btn-info" type="button" data-bind="click : $root.AgregarOtraVenta"><u>N</u>uevo</button></h3>
              </div>
              <div class="panel-body">
                <!-- <p>Tienes 62 clientes.</p> -->
                <div class="datalist__result">
                  <div class="scrollable scrollbar-macosx">
                      <!-- table-hover -->
                      <fieldset>
                        <table id="DataTables_Table_0" class="datalist__table table display" width="100%" data-products="brand" >
                          <thead>
                            <tr>
                              <th class="products__title">Codigo</th>
                              <th class="products__title">Nombre</th>
                              <th class="products__title">Tipo Producto</th>
                              <th class="products__title">Tipo Afectación IGV</th>
                              <th>&nbsp;</th>
                            </tr>
                          </thead>
                          <tbody>
                            <!-- ko foreach : OtrasVenta -->
                            <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdProducto }">
                              <td data-bind="event : { click : $root.OnClickOtraVenta },attr : { id : IdProducto() + '_td_CodigoOtraVenta'}">
                                <span class="class_SpanOtraVenta" data-bind="text : CodigoOtraVenta , visible : true , attr : { id : IdProducto() + '_span_CodigoOtraVenta'}"></span>
                                <input name="CodigoOtraVenta" class="class_InputOtraVenta form-control formulario"
                                data-bind="value : CodigoOtraVenta ,
                                visible : false , attr : { id : IdProducto() + '_input_CodigoOtraVenta' } ,
                                event : { keyup : $root.OnKeyUpOtraVenta }">
                              </td>
                              <td data-bind="event : { click : $root.OnClickOtraVenta },attr : { id : IdProducto() + '_td_NombreProducto'}">
                                <span class="class_SpanOtraVenta" data-bind="text : NombreProducto , visible : true , attr : { id : IdProducto() + '_span_NombreProducto'}"></span>
                                <input name="NombreProducto" class="class_InputOtraVenta form-control formulario"
                                data-bind="value : NombreProducto ,
                                visible : false , attr : { id : IdProducto() + '_input_NombreProducto' } ,
                                event : { keyup : $root.OnKeyUpOtraVenta }">
                              </td>
                              <td data-bind="event : { click : $root.OnClickOtraVenta },attr : { id : IdProducto() + '_td_IdTipoProducto'}">
                                <span class="class_SpanOtraVenta" data-bind="text : NombreTipoProducto , visible : true , attr : { id : IdProducto() + '_span_IdTipoProducto'}"></span>
                                <div class="class_InputOtraVenta" data-bind="visible: false, attr : { id : IdProducto() + '_combo_IdTipoProducto' }" >
                                  <select name="IdTipoProducto"  class="form-control formulario" data-bind="
                                  value : IdTipoProducto,
                                  options : $root.data.TiposProducto,
                                  optionsValue : 'IdTipoProducto',
                                  optionsText : 'NombreTipoProducto',
                                  attr : { id : IdProducto() + '_input_IdTipoProducto' } ">
                                </select>
                              </div>
                            </td>
                            <td data-bind="event : { click : $root.OnClickOtraVenta },attr : { id : IdProducto() + '_td_IdTipoProducto'}">
                              <span class="class_SpanOtraVenta" data-bind="text : NombreTipoAfectacionIGV , visible : true , attr : { id : IdProducto() + '_span_IdTipoAfectacionIGV'}"></span>
                              <div class="class_InputOtraVenta" data-bind="visible: false, attr : { id : IdProducto() + '_combo_IdTipoAfectacionIGV' }" >
                                <select name="IdTipoAfectacionIGV"  class="form-control formulario" data-bind="
                                value : IdTipoAfectacionIGV,
                                options : $root.data.TiposAfectacionIGV,
                                optionsValue : 'IdTipoAfectacionIGV',
                                optionsText : 'NombreTipoAfectacionIGV',
                                attr : { id : IdProducto() + '_input_IdTipoAfectacionIGV'},
                                event { change : $root.OnChangeTipoAfectacionIGV} ">
                              </select>
                            </div>
                          </td>
                            <td align="center" data-bind="click:$root.FilaButtonsOtraVenta">
                              <button data-bind="visible : false, attr : { id : IdProducto() + '_button_OtraVenta' } , click : $root.GuardarOtraVenta" class="btn btn-sm btn-success guardar_button_OtraVenta btn-operaciones" data-toogle="tooltip" title="Guardar" >
                                <span class="glyphicon glyphicon-floppy-disk"></span>
                              </button>
                              <button data-bind="attr : { id : IdProducto() + '_editar_button_OtraVenta' } , click : $root.EditarOtraVenta" class="btn btn-sm btn-warning editar_button_OtraVenta btn-operaciones" data-toogle="tooltip" title="Editar">
                                <span class="glyphicon glyphicon-pencil"></span>
                              </button>
                              <button data-bind="attr : { id : IdProducto() + '_borrar_button_OtraVenta' } , click : $root.PreBorrarOtraVenta" class="btn btn-sm btn-danger borrar_button_OtraVenta btn-operaciones" data-toogle="tooltip" title="Borrar">
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
