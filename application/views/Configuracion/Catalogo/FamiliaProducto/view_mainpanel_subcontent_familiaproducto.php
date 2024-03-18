<div class="scrollable scrollbar-macosx">
  <?php echo $view_subcontent_buscador_familiaproducto; ?>

  <!-- table-hover -->
  <table id="DataTables_Table_0_familiaProducto" class="datalist__table table display table-border" width="100%" data-products="brand" >
    <thead>
      <tr>
        <th class="products__id text-center">Código</th>
        <th class="products__title">Familia</th>
        <th class="products__title" style="width : 50px;"> <center>SubFamilia</center></th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : FamiliasProducto -->
      <tr class="clickable-row text-uppercase" data-bind="click :vistaModeloCatalogo.vmcFamilia.SeleccionarNormal, attr : { id: IdFamiliaProducto()+'_tr_familiaProducto', name: IdFamiliaProducto()}">
        <td align="left" data-bind="text : IdFamiliaProducto,click:vistaModeloCatalogo.vmcFamilia.FilaButtonsFamiliaProducto" class="text-center"></td>
        <td data-bind="event : { click : vistaModeloCatalogo.vmcFamilia.OnClickNombreFamiliaProducto },attr : { id : IdFamiliaProducto() + '_td_NombreFamiliaProducto'}">
          <span class="class_SpanFamiliaProducto" data-bind="html : NombreFamiliaProducto , visible : true , attr : { id : IdFamiliaProducto() + '_span_NombreFamiliaProducto'}"></span>
          <input name="NombreFamiliaProducto" class="class_NombreFamiliaProducto class_InputFamiliaProducto text-uppercase form-control formulario"
          data-bind="value : NombreFamiliaProducto ,
          visible : false , attr : { id : IdFamiliaProducto() + '_input_NombreFamiliaProducto' } ,
          event : {  keyup : vistaModeloCatalogo.vmcFamilia.OnKeyUpNombreFamiliaProducto }"
          type="text" style="width : 100%" >
        </td>
        <td align="center" data-bind="click:vistaModeloCatalogo.vmcFamilia.FilaButtonsFamiliaProducto">
          <div data-bind="css: VistaOptions">
            <button data-bind="click : vistaModeloCatalogo.vmcFamilia.ConsultarSubFamiliaProducto , attr : { id : IdFamiliaProducto() + '_button_SubFamilia_FamiliaProducto' } " class="btn btn-sm btn-default btn_subfamiliaproducto btn-operaciones" data-toogle="tooltip" title="SubFamilia">
                <span class="fa fa-fw fa-folder"></span>
            </button>
          </div>
        </td>
        <td align="center" data-bind="click:vistaModeloCatalogo.vmcFamilia.FilaButtonsFamiliaProducto">
          <div data-bind="css: VistaOptions">
            <button class="btn btn-sm btn-success guardar_button_FamiliaProducto btn-operaciones" data-bind="visible : false, attr : { id : IdFamiliaProducto() + '_button_FamiliaProducto' } , click : vistaModeloCatalogo.vmcFamilia.GuardarFamiliaProducto" data-toogle="tooltip" title="Guardar" >
              <span class="glyphicon glyphicon-floppy-disk"></span>
            </button>
            <button data-bind="attr : { id : IdFamiliaProducto() + '_editar_button_FamiliaProducto' }, click : vistaModeloCatalogo.vmcFamilia.EditarFamiliaProducto" class="btn btn-sm btn-warning opt_familiaproducto btn-operaciones" data-toogle="tooltip" title="Editar">
              <span class="glyphicon glyphicon-pencil"></span>
            </button>
            <button data-bind="attr : { id : IdFamiliaProducto() + '_borrar_button_FamiliaProducto' }, click : vistaModeloCatalogo.vmcFamilia.PreBorrarFamiliaProducto" class="btn btn-sm btn-danger opt_familiaproducto btn-operaciones" data-toogle="tooltip" title="Borrar">
              <span class="glyphicon glyphicon-trash"></span>
            </button>
          </div>
        </td>
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</div>
