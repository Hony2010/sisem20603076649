<!-- ko with : vmgGiroNegocio.dataGiroNegocio -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Giro de Negocio &nbsp; <button id="btnAgregarGiroNegocio" class="btn btn-info" type="button" data-bind="click : vistaModeloGeneral.vmgGiroNegocio.AgregarGiroNegocio"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <!-- <p>Tienes 62 clientes.</p> -->
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
        <!-- table-hover -->
        <table id="DataTables_Table_0_gironegocio" class="datalist__table table display table-border" width="100%" data-products="brand" >
          <thead>
            <tr>
              <th class="products__id ocultar">Código</th>
              <th class="products__title">Nombre</th>
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <!-- ko foreach : GirosNegocio -->
            <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloGeneral.vmgGiroNegocio.Seleccionar, attr : { id: IdGiroNegocio() +'_tr_gironegocio' }">

              <td class="ocultar" data-bind="text : IdGiroNegocio, click:vistaModeloGeneral.vmgGiroNegocio.FilaButtonsGiroNegocio"></td>

              <td data-bind="event : { click : vistaModeloGeneral.vmgGiroNegocio.OnClickGiroNegocio },attr : { id : IdGiroNegocio() + '_td_NombreGiroNegocio'}">
                <span class="class_SpanGiroNegocio" data-bind="text : NombreGiroNegocio , visible : true , attr : { id : IdGiroNegocio() + '_span_NombreGiroNegocio'}"></span>
                <input name="NombreGiroNegocio" class="class_InputGiroNegocio form-control formulario"
                data-bind="value : NombreGiroNegocio ,
                visible : false , attr : { id : IdGiroNegocio() + '_input_NombreGiroNegocio' } ,
                event : { keyup : vistaModeloGeneral.vmgGiroNegocio.OnKeyUpGiroNegocio }"
                type="text" >

              </td>

              <td align="center" data-bind="click:vistaModeloGeneral.vmgGiroNegocio.FilaButtonsGiroNegocio">
                  <button class="btn btn-sm btn-success guardar_button_GiroNegocio btn-operaciones" data-bind="visible : false, attr : { id : IdGiroNegocio() + '_button_GiroNegocio' } , click : vistaModeloGeneral.vmgGiroNegocio.GuardarGiroNegocio" data-toogle="tooltip" title="Guardar" >
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                  </button>
                  <button data-bind="attr : { id : IdGiroNegocio() + '_editar_button_GiroNegocio' } , click : vistaModeloGeneral.vmgGiroNegocio.EditarGiroNegocio" class="btn btn-sm btn-warning editar_button_GiroNegocio btn-operaciones" data-toogle="tooltip" title="Editar">
                    <span class="glyphicon glyphicon-pencil"></span>
                  </button>
                  <button data-bind="attr : { id : IdGiroNegocio() + '_borrar_button_GiroNegocio' } , click : vistaModeloGeneral.vmgGiroNegocio.PreBorrarGiroNegocio" class="btn btn-sm btn-danger borrar_button_GiroNegocio btn-operaciones" data-toogle="tooltip" title="Borrar">
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
