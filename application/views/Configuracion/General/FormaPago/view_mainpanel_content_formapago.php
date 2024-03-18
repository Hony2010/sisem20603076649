<!-- ko with : vmgFormaPago.dataFormaPago -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Forma de Pago (para compras y ventas) &nbsp; <button id="btnAgregarFormaPago" class="btn btn-info" type="button" data-bind="click : vistaModeloGeneral.vmgFormaPago.AgregarFormaPago"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
        <!-- table-hover -->
        <table id="DataTables_Table_0_formapago" class="datalist__table table display table-border" width="100%" data-products="brand" >
          <thead>
            <tr>
              <th class="products__id ocultar">Código</th>
              <th class="products__title">Nombre</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <!-- ko foreach : FormasPago -->
            <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloGeneral.vmgFormaPago.Seleccionar, attr : { id: IdFormaPago()+'_tr_formapago' }">

              <td class="ocultar" data-bind="text : IdFormaPago, click:vistaModeloGeneral.vmgFormaPago.FilaButtonsFormaPago"></td>

              <td data-bind="event : { click : vistaModeloGeneral.vmgFormaPago.OnClickFormaPago },attr : { id : IdFormaPago() + '_td_NombreFormaPago'}">
                <span class="class_SpanFormaPago" data-bind="text : NombreFormaPago , visible : true , attr : { id : IdFormaPago() + '_span_NombreFormaPago'}"></span>
                <input name="NombreFormaPago" class="class_InputFormaPago form-control formulario"
                data-bind="value : NombreFormaPago ,
                visible : false , attr : { id : IdFormaPago() + '_input_NombreFormaPago' } ,
                event : { keyup : vistaModeloGeneral.vmgFormaPago.OnKeyUpFormaPago }"
                type="text" style="width : 100%" >

              </td>

              <td align="center" data-bind="click:vistaModeloGeneral.vmgFormaPago.FilaButtonsFormaPago">
                  <button class="btn btn-sm btn-success guardar_button_FormaPago btn-operaciones" data-bind="visible : false, attr : { id : IdFormaPago() + '_button_FormaPago' } , click : vistaModeloGeneral.vmgFormaPago.GuardarFormaPago" data-toogle="tooltip" title="Guardar" >
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                  </button>
                  <button data-bind="attr : { id : IdFormaPago() + '_editar_button_FormaPago' } , click : vistaModeloGeneral.vmgFormaPago.EditarFormaPago" class="btn btn-sm btn-warning editar_button_FormaPago btn-operaciones" data-toogle="tooltip" title="Editar">
                    <span class="glyphicon glyphicon-pencil"></span>
                  </button>
                  <button data-bind="attr : { id : IdFormaPago() + '_borrar_button_FormaPago' } , click : vistaModeloGeneral.vmgFormaPago.PreBorrarFormaPago" class="btn btn-sm btn-danger borrar_button_FormaPago btn-operaciones" data-toogle="tooltip" title="Borrar">
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
