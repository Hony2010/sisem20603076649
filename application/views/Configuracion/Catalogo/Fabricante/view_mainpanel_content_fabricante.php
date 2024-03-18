<!-- ko with : vmcFabricante.dataFabricante -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Fabricante &nbsp; <button id="btnAgregarFabricante" class="btn btn-info" type="button" data-bind="click : vistaModeloCatalogo.vmcFabricante.AgregarFabricante"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <!-- <p>Tienes 62 clientes.</p> -->
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
          <!-- table-hover -->
          <table id="DataTables_Table_0_fabricante" class="datalist__table table display table-border" width="100%" data-products="brand" >
            <thead>
              <tr>
                <th class="products__id">Código</th>
                <th class="products__title">Nombre</th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <!-- ko foreach : Fabricantes -->
              <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloCatalogo.vmcFabricante.Seleccionar, attr : { id: IdFabricante() + '_tr_fabricante' }">

                <td data-bind="text : IdFabricante, click:vistaModeloCatalogo.vmcFabricante.FilaButtonsFabricante"></td>

                <td data-bind="event : { click : vistaModeloCatalogo.vmcFabricante.OnClickFabricante },attr : { id : IdFabricante() + '_td_NombreFabricante'}">
                  <span class="class_SpanFabricante" data-bind="text : NombreFabricante , visible : true , attr : { id : IdFabricante() + '_span_NombreFabricante'}"></span>
                  <input name="NombreFabricante" class="class_InputFabricante text-uppercase form-control formulario"
                  data-bind="value : NombreFabricante ,
                  visible : false , attr : { id : IdFabricante() + '_input_NombreFabricante' } ,
                  event : { keyup : vistaModeloCatalogo.vmcFabricante.OnKeyUpFabricante }"
                  type="text">

                </td>

                <td align="center" data-bind="click:vistaModeloCatalogo.vmcFabricante.FilaButtonsFabricante">
                    <button class="btn btn-sm btn-success guardar_button_Fabricante btn-operaciones" data-bind="visible : false, attr : { id : IdFabricante() + '_button_Fabricante' } , click : vistaModeloCatalogo.vmcFabricante.GuardarFabricante" data-toogle="tooltip" title="Guardar" >
                      <span class="glyphicon glyphicon-floppy-disk"></span>
                    </button>
                    <button data-bind="attr : { id : IdFabricante() + '_editar_button_Fabricante' } , click : vistaModeloCatalogo.vmcFabricante.EditarFabricante" class="btn btn-sm btn-warning editar_button_Fabricante btn-operaciones" data-toogle="tooltip" title="Editar">
                      <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                    <button data-bind="attr : { id : IdFabricante() + '_borrar_button_Fabricante' } , click : vistaModeloCatalogo.vmcFabricante.PreBorrarFabricante" class="btn btn-sm btn-danger borrar_button_Fabricante btn-operaciones" data-toogle="tooltip" title="Borrar">
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
