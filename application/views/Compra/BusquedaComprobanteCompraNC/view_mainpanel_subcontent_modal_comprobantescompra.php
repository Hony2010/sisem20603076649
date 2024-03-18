<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="BusquedaComprobantesCompraModelNC">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content" id="BusquedaComprobantesCompraModel_content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind=""><span aria-hidden="true">&times;</span></button>
              <h4 class="panel-title" >Consulta Documento de Referencia</h4>
            </div>
            <div class="modal-body">
              <?php echo $view_subcontent_modal_buscador_comprobantescompra; ?>
              <div  style="max-height: 300px;overflow: auto">
                <fieldset>
                  <table id="DataTables_Table_0_otraunidadmedida" class="datalist__table table display" width="100%" data-products="brand" >
                    <thead>
                      <tr>
                        <th class="products__id">Documento</th>
                        <th class="products__title">Fecha Emision</th>
                        <th class="products__title">Total</th>
                        <th class="products__title">Forma Pago</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- ko foreach : BusquedaComprobantesCompraNC -->
                      <tr class="clickable-row text-uppercase" data-bind="attr : { id: IdComprobanteCompra() +'_tr_comprobanteventaporcliente' }">
                        <td data-bind="text: Documento">F001-00000055</td>
                        <td data-bind="text: FechaEmision">01/01/2018</td>
                        <td data-bind="text: Total">S/ 119.99</td>
                        <td data-bind="text: NombreFormaPago">CONTADO</td>
                        <td>
                          <input type="checkbox" data-bind="checked: EstadoSelector, event: {change: CambiarEstadoCheck}">
                          <!-- <div class="css: VistaCheck">
                        </div> -->
                      </td>
                    </tr>
                    <!-- /ko -->
                  </tbody>
                </table>
                </fieldset>
              </div>
              <?php //echo $view_subcontent_modal_paginacion_comprobantescompra; ?>
            </div>
            <div class="modal-footer" style="text-align: center;">
              <button id="btn_AgregarComprobantesCompra" class="btn btn-success" type="button" name="button" data-bind="event:{click: AgregarComprobantesCompraPorCliente}">Agregar</button>
            </div>
        </div>
    </div>
</div>
