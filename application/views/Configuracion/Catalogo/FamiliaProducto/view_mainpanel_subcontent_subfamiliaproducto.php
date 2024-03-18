<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalSubFamiliaProducto">
  <div class="modal-dialog" role="document">
    <div id="modalSubFamiliaProducto_content" class="modal-content">
      <div class="modal-header" style="display:flex;justify-content: center;">
        <!-- <div style="font-size: 15px;padding-top: 5px;"> -->
          <h5 style="margin: 0px; width: 100%;">
            <center>
              <b>Familia : <span  class="text-uppercase" id="TituloNombreFamiliaProducto"></b></span>
              <button id="btnAgregarSubFamiliaProducto" class="btn btn-info" type="button" data-bind="click : vistaModeloCatalogo.vmcFamilia.AgregarSubFamiliaProducto"><u>N</u>uevo</button>
            </center>
          </h5>

        <!-- </div> -->
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="event:{click: $root.Cerrar}"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">

        <div class="scrollable scrollbar-macosx">
          <div class="container-fluid">
            <table id="tabla-subfamiliaproducto" class="datalist__table table datatable display DataTables_Table_subfamiliaproducto" width="100%" data-products="digital"><!-- table-hover -->
              <thead>
                <tr>
                  <th class="products__id ocultar">Código</th>
                  <th class="products__title">SubFamilia</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <!-- ko foreach : SubFamiliasProducto -->
                <tr class="clickable-row-subfamilia text-uppercase" data-bind="click : vistaModeloCatalogo.vmcFamilia.SeleccionarNormalSubFamiliaProducto, attr : { id: IdSubFamiliaProducto()+'_subfamiliaproducto' }">
                  <td align="left" data-bind="text : IdSubFamiliaProducto, click : vistaModeloCatalogo.vmcFamilia.FilaButtonsSubFamiliaProducto" class="ocultar"></td>
                  <td hiden data-bind="event : { click : vistaModeloCatalogo.vmcFamilia.OnClickNombreSubFamiliaProducto },attr : { id : IdSubFamiliaProducto() + '_td_NombreSubFamiliaProducto'}" data-bind="text : NombreSubFamiliaProducto">
                    <span class="class_SpanSubFamiliaProducto text-uppercase" data-bind="html : NombreSubFamiliaProducto , visible : true , attr : { id : IdSubFamiliaProducto() + '_span_NombreSubFamiliaProducto'}"></span>
                    <input name="NombreSubFamiliaProducto" class="class_NombreSubFamiliaProducto class_InputSubFamiliaProducto text-uppercase form-control formulario"
                    data-bind="value : NombreSubFamiliaProducto ,
                    visible : false , attr : { id : IdSubFamiliaProducto() + '_input_NombreSubFamiliaProducto' } ,
                    event : { keyup : vistaModeloCatalogo.vmcFamilia.OnKeyUpNombreSubFamiliaProducto }"
                    type="text" style="width : 100%" >
                  </td>
                  <td align="center"  data-bind="click:vistaModeloCatalogo.vmcFamilia.FilaButtonsSubFamiliaProducto">
                    <div data-bind="css: VistaOptions">
                      <button class="btn btn-sm btn-success guardar_button_SubFamiliaProducto btn-operaciones" data-bind="visible : false, attr : { id : IdSubFamiliaProducto() + '_button_SubFamiliaProducto' } , click : vistaModeloCatalogo.vmcFamilia.GuardarSubFamiliaProducto" data-toogle="tooltip" title="Guardar" >
                        <span class="glyphicon glyphicon-floppy-disk"></span>
                      </button>
                      <button data-bind="attr : { id : IdSubFamiliaProducto() + '_editar_button_SubFamiliaProducto' },  click : vistaModeloCatalogo.vmcFamilia.EditarSubFamiliaProducto" class="btn btn-sm btn-warning opt_subfamiliaproducto btn-operaciones" data-toogle="tooltip" title="Editar">
                        <span class="glyphicon glyphicon-pencil"></span>
                      </button>
                      <button data-bind="attr : { id : IdSubFamiliaProducto() + '_borrar_button_SubFamiliaProducto' }, click : vistaModeloCatalogo.vmcFamilia.PreBorrarSubFamiliaProducto" class="btn btn-sm btn-danger opt_subfamiliaproducto btn-operaciones" data-toogle="tooltip" title="Borrar">
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
  </div>
</div>
