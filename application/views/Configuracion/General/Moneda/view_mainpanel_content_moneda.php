<!-- ko with : vmgMoneda.dataMoneda -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">  Moneda &nbsp; <button id="btnAgregarMoneda" class="btn btn-info ocultar" type="button" data-bind="click : vistaModeloGeneral.vmgMoneda.AgregarMoneda"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
          <!-- table-hover -->
          <table id="DataTables_Table_0_moneda" class="datalist__table table display table-border" width="100%" data-products="brand" >
            <thead>
              <tr>
                <th class="products__id">Código</th>
                <th class="products__title">Nombre</th>
                <th class="products__title">Símbolo</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <!-- ko foreach : Monedas -->
              <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloGeneral.vmgMoneda.Seleccionar, attr : { id: IdMoneda() +'_tr_moneda' }">

                <td data-bind="event : { click : vistaModeloGeneral.vmgMoneda.OnClickMoneda },attr : { id : IdMoneda() + '_td_CodigoMoneda'}">
                  <span data-bind="text : CodigoMoneda , visible : true , attr : { id : IdMoneda() + '_span_CodigoMoneda'}"></span>
                  <!-- <input name="CodigoMoneda" class="class_InputMoneda form-control formulario"
                  data-bind="value : CodigoMoneda ,
                  visible : false , attr : { id : IdMoneda() + '_input_CodigoMoneda' } ,
                  event : { keyup : vistaModeloGeneral.vmgMoneda.OnKeyUpMoneda }"
                  type="text"> -->

                </td>

                <td data-bind="event : { click : vistaModeloGeneral.vmgMoneda.OnClickMoneda },attr : { id : IdMoneda() + '_td_NombreMoneda'}">
                  <span class="class_SpanMoneda" data-bind="text : NombreMoneda , visible : true , attr : { id : IdMoneda() + '_span_NombreMoneda'}"></span>
                  <input name="NombreMoneda" class="class_InputMoneda form-control formulario"
                  data-bind="value : NombreMoneda ,
                  visible : false , attr : { id : IdMoneda() + '_input_NombreMoneda' } ,
                  event : { keyup : vistaModeloGeneral.vmgMoneda.OnKeyUpMoneda }"
                  type="text">

                </td>

                <td data-bind="event : { click : vistaModeloGeneral.vmgMoneda.OnClickMoneda },attr : { id : IdMoneda() + '_td_SimboloMoneda'}">
                  <span class="class_SpanMoneda" data-bind="text : SimboloMoneda , visible : true , attr : { id : IdMoneda() + '_span_SimboloMoneda'}"></span>
                  <input name="SimboloMoneda" class="class_InputMoneda form-control formulario"
                  data-bind="value : SimboloMoneda ,
                  visible : false , attr : { id : IdMoneda() + '_input_SimboloMoneda' } ,
                  event : { keyup : vistaModeloGeneral.vmgMoneda.OnKeyUpMoneda }"
                  type="text">

                </td>

                <td align="center" data-bind="click:vistaModeloGeneral.vmgMoneda.FilaButtonsMoneda">
                    <button class="btn btn-sm btn-success guardar_button_Moneda btn-operaciones" data-bind="visible : false, attr : { id : IdMoneda() + '_button_Moneda' } , click : vistaModeloGeneral.vmgMoneda.GuardarMoneda" data-toogle="tooltip" title="Guardar" >
                      <span class="glyphicon glyphicon-floppy-disk"></span>
                    </button>
                    <button data-bind="attr : { id : IdMoneda() + '_editar_button_Moneda' } , click : vistaModeloGeneral.vmgMoneda.EditarMoneda" class="btn btn-sm btn-warning editar_button_Moneda btn-operaciones" data-toogle="tooltip" title="Editar">
                      <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                    <button data-bind="attr : { id : IdMoneda() + '_borrar_button_Moneda' } , click : vistaModeloGeneral.vmgMoneda.PreBorrarMoneda" class="btn btn-sm btn-danger borrar_button_Moneda btn-operaciones ocultar" data-toogle="tooltip" title="Borrar">
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
