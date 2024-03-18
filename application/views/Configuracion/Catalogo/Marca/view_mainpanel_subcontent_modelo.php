<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalModelo">
  <div class="modal-dialog" role="document">
    <div id="modalModelo_content" class="modal-content">
      <div class="modal-header" style="display:flex;justify-content: center;">
        <!-- <div style="font-size: 15px;padding-top: 5px;"> -->
          <h5 style="margin: 0px; width: 100%;">
            <center><b>Marca : <span class="text-uppercase" id="TituloNombreMarca"></b></span> <button id="btnAgregarModelo" class="btn btn-info" type="button" data-bind="click : vistaModeloCatalogo.vmcMarca.AgregarModelo"><u>N</u>uevo</button></center>
          </h5>

        <!-- </div> -->
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="event:{click: $root.Cerrar}"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">

        <div class="scrollable scrollbar-macosx">
          <div class="container-fluid">
            <table id="tabla-modelo" class="datalist__table table datatable display DataTables_Table_modelo" width="100%" data-products="digital">
              <thead>
                <tr>
                  <th class="products__id ocultar">Código</th>
                  <th class="products__title">Modelo</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <!-- ko foreach : Modelos -->
                <tr class="clickable-row-modelo text-uppercase" data-bind="click : vistaModeloCatalogo.vmcMarca.SeleccionarNormalModelo, attr : { id: IdModelo()+'_modelo' }">
                  <td align="left" data-bind="text : IdModelo, click:vistaModeloCatalogo.vmcMarca.FilaButtonsModelo" class="ocultar"></td>
                  <td data-bind="event : { click : vistaModeloCatalogo.vmcMarca.OnClickNombreModelo },attr : { id : IdModelo() + '_td_NombreModelo'}" data-bind="text : NombreModelo">
                    <span class="class_SpanModelo text-uppercase" data-bind="html : NombreModelo , visible : true , attr : { id : IdModelo() + '_span_NombreModelo'}"></span>
                    <input name="NombreModelo" class="class_NombreModelo class_InputModelo text-uppercase form-control formulario"
                    data-bind="value : NombreModelo ,
                    visible : false , attr : { id : IdModelo() + '_input_NombreModelo' } ,
                    event : { keyup : vistaModeloCatalogo.vmcMarca.OnKeyUpNombreModelo }"
                    type="text" style="width : 100%" >
                  </td>
                  <td align="center"  data-bind="click:vistaModeloCatalogo.vmcMarca.FilaButtonsModelo">
                    <div data-bind="css: VistaOptions">
                      <button class="btn btn-sm btn-success guardar_button_Modelo btn-operaciones" data-bind="visible : false, attr : { id : IdModelo() + '_button_Modelo' } , click : vistaModeloCatalogo.vmcMarca.GuardarModelo" data-toogle="tooltip" title="Guardar" >
                        <span class="glyphicon glyphicon-floppy-disk"></span>
                      </button>
                      <button data-bind="attr : { id : IdModelo() + '_editar_button_Modelo' }, click : vistaModeloCatalogo.vmcMarca.EditarModelo" class="btn btn-sm btn-warning opt_modelo btn-operaciones" data-toogle="tooltip" title="Editar">
                        <span class="glyphicon glyphicon-pencil"></span>
                      </button>
                      <button data-bind="attr : { id : IdModelo() + '_borrar_button_Modelo' }, click : vistaModeloCatalogo.vmcMarca.PreBorrarModelo" class="btn btn-sm btn-danger opt_modelo btn-operaciones" data-toogle="tooltip" title="Borrar">
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
