<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Cajas &nbsp; <button id="btnAgregarCaja" class="btn btn-info" type="button" data-bind="click : $root.AgregarCaja"><u>N</u>uevo</button></h3>
              </div>
              <div class="panel-body">
              <div class="datalist__result">
                <div class="scrollable scrollbar-macosx">
                  <fieldset>
                    <table id="DataTables_Table_0" class="datalist__table table display" width="100%" data-products="brand" >
                      <thead>
                        <tr>
                          <th class="products__title">Nombre</th>
                          <th class="products__title">Moneda</th>
                          <th class="products__title">Nro de Caja</th>
                          <th>&nbsp;</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- ko foreach : Cajas -->
                        <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdCaja }">
                          <td data-bind="event : { click : $root.OnClickCaja },attr : { id : IdCaja() + '_td_NombreCaja'}">
                            <span class="class_SpanCaja" data-bind="text : NombreCaja , visible : true , attr : { id : IdCaja() + '_span_NombreCaja'}"></span>
                            <input name="NombreCaja" class="class_InputCaja form-control formulario"
                            data-bind="value : NombreCaja ,
                            visible : false , attr : { id : IdCaja() + '_input_NombreCaja' } ,
                            event : { keyup : $root.OnKeyUpCaja }">
                          </td>
                          <td data-bind="event : { click : $root.OnClickCaja },attr : { id : IdCaja() + '_td_NombreCaja'}">
                            <span class="class_SpanCaja" data-bind="text : NombreMoneda , visible : true , attr : { id : IdCaja() + '_span_NombreMoneda'}"></span>
                            <div class="class_InputCaja" data-bind="visible: false, attr : { id : IdCaja() + '_combo_IdMoneda' }" >
                              <select name="IdMoneda"  class="form-control class_InputCaja formulario" data-bind="
                                value : IdMoneda,
                                options : $root.data.Monedas,
                                optionsValue : 'IdMoneda',
                                optionsText : 'NombreMoneda',
                                attr : { id : IdCaja() + '_input_IdMoneda'}">
                              </select>
                            </div>
                          </td>
                          <td data-bind="event : { click : $root.OnClickCaja },attr : { id : IdCaja() + '_td_NumeroCaja'}">
                            <span class="class_SpanCaja" data-bind="text : NumeroCaja , visible : true , attr : { id : IdCaja() + '_span_NumeroCaja'}"></span>
                            <input name="NumeroCaja" class="class_InputCaja form-control formulario"
                            data-bind="value : NumeroCaja ,
                            visible : false , attr : { id : IdCaja() + '_input_NumeroCaja' } ,
                            event : { keyup : $root.OnKeyUpCaja }">
                          </td>
                          <td align="center" data-bind="click:$root.FilaButtonsCaja">
                            <button data-bind="visible : false, attr : { id : IdCaja() + '_button_Caja' } , click : $root.GuardarCaja"class="btn btn-sm btn-success guardar_button_Caja btn-operaciones"  data-toogle="tooltip" title="Guardar" >
                              <span class="glyphicon glyphicon-floppy-disk"></span>
                            </button>
                            <button data-bind="attr : { id : IdCaja() + '_editar_button_Caja' } , click : $root.EditarCaja" class="btn btn-sm btn-warning editar_button_Caja btn-operaciones" data-toogle="tooltip" title="Editar">
                              <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                            <button data-bind="attr : { id : IdCaja() + '_borrar_button_Caja' } , click : $root.PreBorrarCaja" class="btn btn-sm btn-danger borrar_button_Caja btn-operaciones" data-toogle="tooltip" title="Borrar">
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
