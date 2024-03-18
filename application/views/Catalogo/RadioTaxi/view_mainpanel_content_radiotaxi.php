<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Radio Taxi &nbsp; <button id="btnAgregarRadioTaxi" class="btn btn-info" type="button" data-bind="click : $root.AgregarRadioTaxi"><u>N</u>uevo</button></h3>
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
                        <!-- ko foreach : RadiosTaxi -->
                        <tr class="clickable-row text-uppercase" data-bind="click : $root.Seleccionar, attr : { id: IdRadioTaxi }">
                          <td data-bind="event : { click : $root.OnClickRadioTaxi },attr : { id : IdRadioTaxi() + '_td_NombreRadioTaxi'}">
                            <span class="class_SpanRadioTaxi" data-bind="text : NombreRadioTaxi , visible : true , attr : { id : IdRadioTaxi() + '_span_NombreRadioTaxi'}"></span>
                            <input name="NombreRadioTaxi" class="class_InputRadioTaxi form-control formulario"
                            data-bind="value : NombreRadioTaxi ,
                            visible : false , attr : { id : IdRadioTaxi() + '_input_NombreRadioTaxi' } ,
                            event : { keyup : $root.OnKeyUpRadioTaxi }">
                          </td>
                          <td class="text-center" data-bind="click:$root.FilaButtonsRadioTaxi">
                            <button data-bind="visible : false, attr : { id : IdRadioTaxi() + '_button_RadioTaxi' } , click : $root.GuardarRadioTaxi"class="btn btn-sm btn-success guardar_button_RadioTaxi btn-operaciones"  data-toogle="tooltip" title="Guardar" >
                              <span class="glyphicon glyphicon-floppy-disk"></span>
                            </button>
                            <button data-bind="attr : { id : IdRadioTaxi() + '_editar_button_RadioTaxi' } , click : $root.EditarRadioTaxi" class="btn btn-sm btn-warning editar_button_RadioTaxi btn-operaciones" data-toogle="tooltip" title="Editar">
                              <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                            <button data-bind="attr : { id : IdRadioTaxi() + '_borrar_button_RadioTaxi' } , click : $root.PreBorrarRadioTaxi" class="btn btn-sm btn-danger borrar_button_RadioTaxi btn-operaciones" data-toogle="tooltip" title="Borrar">
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
