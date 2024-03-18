<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Gastos &nbsp; <button id="btnAgregarGasto" class="btn btn-info" type="button" data-bind="click : $root.AgregarGasto"><u>N</u>uevo</button></h3>
              </div>
              <div class="panel-body">
              <div class="datalist__result">
                <div class="scrollable scrollbar-macosx">
                  <fieldset>
                    <table id="DataTables_Table_0" class="datalist__table table display" width="100%" data-products="brand" >
                      <thead>
                        <tr>
                          <th class="products__title">Nombre</th>
                          <th>&nbsp;</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- ko foreach : Gastos -->
                        <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdProducto }">
                          <td data-bind="event : { click : $root.OnClickGasto },attr : { id : IdProducto() + '_td_NombreProducto'}">
                            <span class="class_SpanGasto" data-bind="text : NombreProducto , visible : true , attr : { id : IdProducto() + '_span_NombreProducto'}"></span>
                            <input name="NombreProducto" class="class_InputGasto form-control formulario"
                            data-bind="value : NombreProducto ,
                            visible : false , attr : { id : IdProducto() + '_input_NombreProducto' } ,
                            event : { keyup : $root.OnKeyUpGasto }">
                          </td>
                          <td align="center" data-bind="click:$root.FilaButtonsGasto">
                            <button data-bind="visible : false, attr : { id : IdProducto() + '_button_Gasto' } , click : $root.GuardarGasto"class="btn btn-sm btn-success guardar_button_Gasto btn-operaciones"  data-toogle="tooltip" title="Guardar" >
                              <span class="glyphicon glyphicon-floppy-disk"></span>
                            </button>
                            <button data-bind="attr : { id : IdProducto() + '_editar_button_Gasto' } , click : $root.EditarGasto" class="btn btn-sm btn-warning editar_button_Gasto btn-operaciones" data-toogle="tooltip" title="Editar">
                              <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                            <button data-bind="attr : { id : IdProducto() + '_borrar_button_Gasto' } , click : $root.PreBorrarGasto" class="btn btn-sm btn-danger borrar_button_Gasto btn-operaciones" data-toogle="tooltip" title="Borrar">
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
