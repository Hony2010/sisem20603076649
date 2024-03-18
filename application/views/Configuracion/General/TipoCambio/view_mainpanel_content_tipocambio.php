<!-- ko with : vmgTipoCambio.dataTipoCambio -->
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Tipo de Cambio &nbsp;<button id="btnAgregarTipoCambio" class="btn btn-info" type="button" data-bind="click : vistaModeloGeneral.vmgTipoCambio.AgregarTipoCambio"><u>N</u>uevo</button></h3>
  </div>
  <div class="panel-body">
    <div class="datalist__result">
      <div class="scrollable scrollbar-macosx">
        <?php echo $view_subcontent_buscador_tipocambio; ?>
        <!-- table-hover -->
        <table id="DataTables_Table_0__TipoCambio" class="datalist__table table display table-border" width="100%" data-products="brand" >
          <thead>
            <tr>
              <th class="products__id ocultar">Código</th>
              <th class="products__title">Fecha</th>
              <th class="products__title text-right">Compra</th>
              <th class="products__title text-right">Venta</th>
              <!-- ko if : ParametroPesoChileno() == 1 -->
              <th class="products__title text-right">Peso Chileno</th>
              <!-- /ko -->
              <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <!-- ko foreach : TiposCambio -->
            <tr class="clickable-row text-uppercase" data-bind="click : vistaModeloGeneral.vmgTipoCambio.Seleccionar, attr : { id: IdTipoCambio() +'_tr_tipocambio' }">

              <td class="ocultar" data-bind="text : IdTipoCambio, click:vistaModeloGeneral.vmgTipoCambio.FilaButtonsTipoCambio"></td>

              <td data-bind="event : { click : vistaModeloGeneral.vmgTipoCambio.OnClickTipoCambio },attr : { id : IdTipoCambio() + '_td_FechaCambio'}">
                <span class="class_SpanTipoCambio" data-bind="html : __FechaCambio , visible : true , attr : { id : IdTipoCambio() + '_span_FechaCambio'}"></span>
                <input name="FechaCambio" class="fecha-reporte class_InputTipoCambio form-control formulario" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida"
                data-bind="value : __FechaCambio ,
                visible : false , attr : { id : IdTipoCambio() + '_input_FechaCambio' } ,
                event : { keyup : vistaModeloGeneral.vmgTipoCambio.OnKeyUpTipoCambio, focusout : ValidarFecha }"
                type="text">

              </td>

              <td class="text-right" data-bind="event : { click : vistaModeloGeneral.vmgTipoCambio.OnClickTipoCambio },attr : { id : IdTipoCambio() + '_td_TipoCambioCompra'}">
                <span class="class_SpanTipoCambio" data-bind="text : __TipoCambioCompra , visible : true , attr : { id : IdTipoCambio() + '_span_TipoCambioCompra'}"></span>
                <input name="TipoCambioCompra" class="class_InputTipoCambio text-right decimal-control form-control formulario"
                data-bind="value : __TipoCambioCompra ,
                visible : false , attr : { id : IdTipoCambio() + '_input_TipoCambioCompra' } ,
                event : { keyup : vistaModeloGeneral.vmgTipoCambio.OnKeyUpTipoCambio }"
                type="numeric" onkeypress = "return validarNum(event)" onpaste="return false">
              </td>

              <td class="text-right" data-bind="event : { click : vistaModeloGeneral.vmgTipoCambio.OnClickTipoCambio },attr : { id : IdTipoCambio() + '_td_TipoCambioVenta'}">
                <span class="class_SpanTipoCambio" data-bind="text : __TipoCambioVenta , visible : true , attr : { id : IdTipoCambio() + '_span_TipoCambioVenta'}" ></span>
                <input name="TipoCambioVenta" class="class_InputTipoCambio text-right  decimal-control form-control formulario"
                data-bind="value : __TipoCambioVenta ,
                visible : false , attr : { id : IdTipoCambio() + '_input_TipoCambioVenta' } ,
                event : { keyup : vistaModeloGeneral.vmgTipoCambio.OnKeyUpTipoCambio }"
                type="numeric" onkeypress = "return validarNum(event)" onpaste="return false">

              </td>
              <!-- ko if : $parent.ParametroPesoChileno() == 1 -->
              <td class="text-right" data-bind="event : { click : vistaModeloGeneral.vmgTipoCambio.OnClickTipoCambio },attr : { id : IdTipoCambio() + '_td_TipoCambioPesoChileno'}">
                <span class="class_SpanTipoCambio" data-bind="text : __TipoCambioPesoChileno , visible : true , attr : { id : IdTipoCambio() + '_span_TipoCambioPesoChileno'}" ></span>
                <input name="TipoCambioPesoChileno" class="class_InputTipoCambio text-right  decimal-control form-control formulario"
                data-bind="value : __TipoCambioPesoChileno ,
                visible : false , attr : { id : IdTipoCambio() + '_input_TipoCambioPesoChileno' } ,
                event : { keyup : vistaModeloGeneral.vmgTipoCambio.OnKeyUpTipoCambio }"
                type="numeric" onkeypress = "return validarNum(event)" onpaste="return false">

              </td>
              <!-- /ko -->

              <td align="center" data-bind="click:vistaModeloGeneral.vmgTipoCambio.FilaButtonsTipoCambio">
                  <button class="btn btn-sm btn-success guardar_button_TipoCambio btn-operaciones" data-bind="visible : false, attr : { id : IdTipoCambio() + '_button_TipoCambio' } , click : vistaModeloGeneral.vmgTipoCambio.GuardarTipoCambio" data-toogle="tooltip" title="Guardar" >
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                  </button>
                  <button data-bind="attr : { id : IdTipoCambio() + '_editar_button_TipoCambio' } , click : vistaModeloGeneral.vmgTipoCambio.EditarTipoCambio" class="btn btn-sm btn-warning editar_button_TipoCambio btn-operaciones" data-toogle="tooltip" title="Editar">
                    <span class="glyphicon glyphicon-pencil"></span>
                  </button>
                  <button data-bind="attr : { id : IdTipoCambio() + '_borrar_button_TipoCambio' } , click : vistaModeloGeneral.vmgTipoCambio.PreBorrarTipoCambio" class="btn btn-sm btn-danger borrar_button_TipoCambio btn-operaciones" data-toogle="tooltip" title="Borrar">
                    <span class="glyphicon glyphicon-trash"></span>
                  </button>
              </td>
            </tr>
            <!-- /ko -->
          </tbody>
        </table>
        <?php echo $view_subcontent_paginacion_tipocambio; ?>
      </div>
    </div>
  </div>
</div>
<!-- /ko -->
