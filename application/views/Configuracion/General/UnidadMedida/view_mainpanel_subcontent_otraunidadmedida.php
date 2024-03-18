<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="OtraUnidadMedidaModel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="OtraUnidadMedidaModel_content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="click : vistaModeloGeneral.vmgUnidadMedida.CloseModal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <div class="tab-content">
                <div class="container-fluid">
                  <?php echo $view_subcontent_buscador_otraunidadmedida; ?>
                  <table id="DataTables_Table_0_otraunidadmedida" class="datalist__table table display" width="100%" data-products="brand" >
                    <thead>
                      <tr>
                        <th class="products__id">Código</th>
                        <th class="products__title">Nombre</th>
                        <th class="products__title">Abreviatura</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- ko foreach : OtraUnidadesMedida -->
                      <tr class="clickable-row text-uppercase" data-bind="attr : { id: IdUnidadMedida() +'_tr_unidadmedida' }">
                        <td data-bind="text: CodigoUnidadMedidaSunat"></td>
                        <td data-bind="text: NombreUnidadMedida"></td>
                        <td data-bind="text: AbreviaturaUnidadMedida"></td>
                        <td>
                          <input type="checkbox" data-bind="checked: EstadoSelector, event: {change: CambiarEstadoCheck}">
                        </td>
                      </tr>
                      <!-- /ko -->
                    </tbody>
                  </table>
                  <?php echo $view_subcontent_paginacion_otraunidadmedida; ?>
                </div>
              </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
              <button id="btn_AgregarOtraUnidadMedida" class="btn btn-success" type="button" name="button" data-bind="click : vistaModeloGeneral.vmgUnidadMedida.AgregarOtraUnidadMedida">Agregar</button>
            </div>
        </div>
    </div>
</div>
