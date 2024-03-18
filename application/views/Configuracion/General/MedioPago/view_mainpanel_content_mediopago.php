<!-- ko with : vmgMedioPago.dataMedioPago -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Medio de Pago &nbsp; <button id="btnAgregarMedioPago" class="btn btn-info" type="button" data-bind="click : vistaModeloGeneral.vmgMedioPago.AgregarMedioPago"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
        <!-- table-hover -->
        <table id="DataTables_Table_0_mediopago" class="datalist__table table display table-border" width="100%" data-products="brand" >
          <thead>
            <tr>
              <th class="products__id">Código</th>
              <th class="products__title">Nombre</th>
              <th class="products__title">Abreviación</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <!-- ko foreach : MediosPago -->
            <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloGeneral.vmgMedioPago.Seleccionar, attr : { id: IdMedioPago() +'_tr_mediopago' }">

              <td data-bind="event : { click : vistaModeloGeneral.vmgMedioPago.OnClickMedioPago },attr : { id : IdMedioPago() + '_td_CodigoMedioPago'}">
                <span class="class_SpanMedioPago" data-bind="text : CodigoMedioPago , visible : true , attr : { id : IdMedioPago() + '_span_CodigoMedioPago'}"></span>
                <input name="CodigoMedioPago" class="class_InputMedioPago form-control formulario"
                data-bind="value : CodigoMedioPago ,
                visible : false , attr : { id : IdMedioPago() + '_input_CodigoMedioPago' } ,
                event : { keyup : vistaModeloGeneral.vmgMedioPago.OnKeyUpMedioPago }"
                type="text">
              </td>

              <td data-bind="event : { click : vistaModeloGeneral.vmgMedioPago.OnClickMedioPago },attr : { id : IdMedioPago() + '_td_NombreMedioPago'}">
                <span class="class_SpanMedioPago" data-bind="text : NombreMedioPago , visible : true , attr : { id : IdMedioPago() + '_span_NombreMedioPago'}"></span>
                <input name="NombreMedioPago" class="class_InputMedioPago form-control formulario"
                data-bind="value : NombreMedioPago ,
                visible : false , attr : { id : IdMedioPago() + '_input_NombreMedioPago' } ,
                event : { keyup : vistaModeloGeneral.vmgMedioPago.OnKeyUpMedioPago }"
                type="text" >
              </td>

              <td data-bind="event : { click : vistaModeloGeneral.vmgMedioPago.OnClickMedioPago },attr : { id : IdMedioPago() + '_td_NombreAbreviado'}">
                <span class="class_SpanMedioPago" data-bind="text : NombreAbreviado , visible : true , attr : { id : IdMedioPago() + '_span_NombreAbreviado'}"></span>
                <input name="NombreAbreviado" class="class_InputMedioPago form-control formulario"
                data-bind="value : NombreAbreviado ,
                visible : false , attr : { id : IdMedioPago() + '_input_NombreAbreviado' } ,
                event : { keyup : vistaModeloGeneral.vmgMedioPago.OnKeyUpMedioPago }"
                type="text" >
              </td>

              <td align="center" data-bind="click:vistaModeloGeneral.vmgMedioPago.FilaButtonsMedioPago" style="min-width: 150px">
                  <button class="btn btn-sm btn-success guardar_button_MedioPago btn-operaciones" data-bind="visible : false, attr : { id : IdMedioPago() + '_button_MedioPago' } , click : vistaModeloGeneral.vmgMedioPago.GuardarMedioPago" data-toogle="tooltip" title="Guardar" >
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                  </button>
                  <button data-bind="attr : { id : IdMedioPago() + '_editar_button_MedioPago' } , click : vistaModeloGeneral.vmgMedioPago.EditarMedioPago" class="btn btn-sm btn-warning editar_button_MedioPago btn-operaciones" data-toogle="tooltip" title="Editar">
                    <span class="glyphicon glyphicon-pencil"></span>
                  </button>
                  <button data-bind="attr : { id : IdMedioPago() + '_borrar_button_MedioPago' } , click : vistaModeloGeneral.vmgMedioPago.PreBorrarMedioPago" class="btn btn-sm btn-danger borrar_button_MedioPago btn-operaciones" data-toogle="tooltip" title="Borrar">
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
