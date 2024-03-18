<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Costo Agregado &nbsp; <button id="btnAgregarCostoAgregado" class="btn btn-info" type="button" data-bind="click : $root.AgregarCostoAgregado"><u>N</u>uevo</button></h3>
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
                          <!-- ko foreach : CostosAgregado -->
                          <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdProducto }">

                            <td data-bind="event : { click : $root.OnClickCostoAgregado },attr : { id : IdProducto() + '_td_NombreProducto'}">
                              <span class="class_SpanCostoAgregado" data-bind="text : NombreProducto , visible : true , attr : { id : IdProducto() + '_span_NombreProducto'}"></span>
                              <input name="NombreProducto" class="class_InputCostoAgregado formulario form-control"
                              data-bind="value : NombreProducto ,
                              visible : false , attr : { id : IdProducto() + '_input_NombreProducto' } ,
                              event : { keyup : $root.OnKeyUpCostoAgregado }">

                            </td>

                            <td align="center" data-bind="click:$root.FilaButtonsCostoAgregado">
                              <button data-bind="visible : false, attr : { id : IdProducto() + '_button_CostoAgregado' } , click : $root.GuardarCostoAgregado"class="btn btn-sm btn-success guardar_button_CostoAgregado btn-operaciones"  data-toogle="tooltip" title="Guardar" >
                                <span class="glyphicon glyphicon-floppy-disk"></span>
                              </button>
                              <button data-bind="attr : { id : IdProducto() + '_editar_button_CostoAgregado' } , click : $root.EditarCostoAgregado" class="btn btn-sm btn-warning editar_button_CostoAgregado btn-operaciones" data-toogle="tooltip" title="Editar">
                                <span class="glyphicon glyphicon-pencil"></span>
                              </button>
                              <button data-bind="attr : { id : IdProducto() + '_borrar_button_CostoAgregado' } , click : $root.PreBorrarCostoAgregado" class="btn btn-sm btn-danger borrar_button_CostoAgregado btn-operaciones" data-toogle="tooltip" title="Borrar">
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
