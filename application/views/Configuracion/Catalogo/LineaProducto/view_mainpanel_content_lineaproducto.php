<!-- ko with : vmcLineaProducto.dataLineaProducto -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Línea de Producto &nbsp; <button id="btnAgregarLineaProducto" class="btn btn-info" type="button" data-bind="click : vistaModeloCatalogo.vmcLineaProducto.AgregarLineaProducto"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <!-- <p>Tienes 62 clientes.</p> -->
    <div class="datalist__result">

      <div class="scrollable scrollbar-macosx">
          <!-- table-hover -->
          <table id="DataTables_Table_0_lineaProducto" class="datalist__table table display table-border" width="100%" data-products="brand" >
            <thead>
              <tr>
                <th class="products__id">Código</th>
                <th class="products__title">Nombre</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <!-- ko foreach : LineasProducto -->
              <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloCatalogo.vmcLineaProducto.Seleccionar, attr : { id: IdLineaProducto() +'_tr_LineaProducto' }">

                <td data-bind="text : IdLineaProducto, click:vistaModeloCatalogo.vmcLineaProducto.FilaButtonsLineaProducto"></td>

                <td data-bind="event : { click : vistaModeloCatalogo.vmcLineaProducto.OnClickLineaProducto },attr : { id : IdLineaProducto() + '_td_NombreLineaProducto'}">
                  <span class="class_SpanLineaProducto" data-bind="text : NombreLineaProducto , visible : true , attr : { id : IdLineaProducto() + '_span_NombreLineaProducto'}"></span>
                  <input name="NombreLineaProducto" class="class_InputLineaProducto text-uppercase form-control formulario"
                  data-bind="value : NombreLineaProducto ,
                  visible : false , attr : { id : IdLineaProducto() + '_input_NombreLineaProducto' } ,
                  event : { keyup : vistaModeloCatalogo.vmcLineaProducto.OnKeyUpLineaProducto }"
                  type="text">
                </td>

                <td align="center" data-bind="click:vistaModeloCatalogo.vmcLineaProducto.FilaButtonsLineaProducto">
                  <div data-bind="css: VistaOptions">
                    <button class="btn btn-sm btn-success guardar_button_LineaProducto btn-operaciones" data-bind="visible : false, attr : { id : IdLineaProducto() + '_button_LineaProducto' } , click : vistaModeloCatalogo.vmcLineaProducto.GuardarLineaProducto" data-toogle="tooltip" title="Guardar" >
                      <span class="glyphicon glyphicon-floppy-disk"></span>
                    </button>
                    <button data-bind="attr : { id : IdLineaProducto() + '_editar_button_LineaProducto' } , click : vistaModeloCatalogo.vmcLineaProducto.EditarLineaProducto" class="btn btn-sm btn-warning editar_button_LineaProducto btn-operaciones" data-toogle="tooltip" title="Editar">
                      <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                    <button data-bind="attr : { id : IdLineaProducto() + '_borrar_button_LineaProducto' } , click : vistaModeloCatalogo.vmcLineaProducto.PreBorrarLineaProducto" class="btn btn-sm btn-danger borrar_button_LineaProducto btn-operaciones" data-toogle="tooltip" title="Borrar">
                      <span class="glyphicon glyphicon-trash"></span>
                    </button>
                  </div>
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
