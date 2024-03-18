<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalBusquedaDocumentoIngreso"
data-bind="">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content" id="modalBusquedaDocumentoIngreso_content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind=""><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <?php echo $view_subcontent_modal_buscador_documentoingreso; ?>
              <div  style="max-height: 300px;overflow: auto">
                <fieldset class="content-size-fieldset">
                  <table id="DataTables_Table_0_documentoscompra" class="datalist__table table display" width="100%" data-products="brand" >
                    <thead>
                      <tr>
                        <th class="products__id col-md-2">Tipo Documento</th>
                        <th class="products__id">Documento</th>
                        <th class="products__title">Fecha Emision</th>
                        <th class="products__title">Total</th>
                        <th class="products__title">Forma Pago</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- ko foreach : DocumentosIngreso -->
                      <tr class="clickable-row text-uppercase" data-bind="attr : { id: IdComprobanteCompra() +'_tr_comprobantecompraproveedor' }, event:{click: function(data, event){AgregarDocumentoIngreso(data, event, $parent.AgregarDocumentoIngreso)}}">
                        <td class="col-md-2" data-bind="text: NombreAbreviado">DI</td>
                        <td data-bind="text: Documento">F001-00000055</td>
                        <td data-bind="text: FechaEmision">01/01/2018</td>
                        <td data-bind="text: Total">S/ 119.99</td>
                        <td data-bind="text: NombreFormaPago">CONTADO</td>
                      </tr>
                      <!-- /ko -->
                    </tbody>
                  </table>
                </fieldset>

              </div>
            </div>
        </div>
    </div>
</div>
