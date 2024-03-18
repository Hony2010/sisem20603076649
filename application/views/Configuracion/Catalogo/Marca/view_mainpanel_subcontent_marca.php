<div class="scrollable scrollbar-macosx">
  <!-- table-hover -->
  <table id="DataTables_Table_0" class="datalist__table table display DataTables_Table_marca table-border" width="100%" data-products="brand" >
    <thead>
      <tr>
        <th class="products__id">Código</th>
        <th class="products__title">Nombre</th>
        <th class="products__title" style="width : 50px;"> <center>Modelo</center></th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : Marcas -->
      <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloCatalogo.vmcMarca.SeleccionarNormal, attr : { id: IdMarca() +'_tr_marca', name:IdMarca() }">
        <td align="left" data-bind="text : IdMarca, click:vistaModeloCatalogo.vmcMarca.FilaButtonsMarca"></td>
        <td data-bind="event : { click : vistaModeloCatalogo.vmcMarca.OnClickNombreMarca },attr : { id : IdMarca() + '_td_NombreMarca'}">
          <span class="class_SpanMarca" data-bind="html : NombreMarca , visible : true , attr : { id : IdMarca() + '_span_NombreMarca'}"></span>
          <input name="NombreMarca" class="class_NombreMarca class_InputMarca text-uppercase form-control formulario"
          data-bind="value : NombreMarca ,
          visible : false , attr : { id : IdMarca() + '_input_NombreMarca' } ,
          event : {  keyup : vistaModeloCatalogo.vmcMarca.OnKeyUpNombreMarca }"
          type="text" style="width : 100%">
        </td>
        <td align="center" data-bind="click:vistaModeloCatalogo.vmcMarca.FilaButtonsMarca">
          <div data-bind="css: VistaOptions">
            <button data-bind="click : vistaModeloCatalogo.vmcMarca.ConsultarModelo , attr : { id : IdMarca() + '_button_Modelo_Marca' } " class="btn btn-sm btn-default btn_modelo btn-operaciones" data-toogle="tooltip" title="Modelo">
                <span class="fa fa-fw fa-folder"></span>
            </button>
          </div>
        </td>
        <td align="center" data-bind="click:vistaModeloCatalogo.vmcMarca.FilaButtonsMarca">
          <div data-bind="css: VistaOptions">
            <button class="btn btn-sm btn-success guardar_button_Marca btn-operaciones" data-bind="visible : false, attr : { id : IdMarca() + '_button_Marca' } , click : vistaModeloCatalogo.vmcMarca.GuardarMarca" data-toogle="tooltip" title="Guardar" >
              <span class="glyphicon glyphicon-floppy-disk"></span>
            </button>
            <button data-bind="attr : { id : IdMarca() + '_editar_button_Marca' }, click : vistaModeloCatalogo.vmcMarca.EditarMarca" class="btn btn-sm btn-warning opt_marca btn-operaciones" data-toogle="tooltip" title="Editar">
              <span class="glyphicon glyphicon-pencil"></span>
            </button>
            <button data-bind="attr : { id : IdMarca() + '_borrar_button_Marca' }, click : vistaModeloCatalogo.vmcMarca.PreBorrarMarca" class="btn btn-sm btn-danger opt_marca btn-operaciones" data-toogle="tooltip" title="Borrar">
              <span class="glyphicon glyphicon-trash"></span>
            </button>
          </div>
        </td>
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</div>
