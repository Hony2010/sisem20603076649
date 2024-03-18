<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx" id="principal">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
      <!-- ko if: MostarBetaSunat() == 1 -->
      <div class="row" id="nota-beta" style="display: none;">
          <div class="col-md-12">
            <div class="nota-beta" style="color: #fff; padding: 2px 10px; margin-bottom: 10px; background-color: red;">
              <center>
                <h5 style="text-transform: uppercase;">
                  <label><b>ADVERTENCIA: ESTOS COMPROBANTES NO SERAN ENVIADOS A SUNAT, COMUNIQUE INMEDIATAMENTE AL PROVEEDOR DEL SISTEMA.</b></label>
                </h5>
              </center>
            </div>
          </div>
        </div>
        <!-- /ko -->
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Resumen Diario</h3>
              </div>
              <div class="panel-body">

                <div class="datalist__result">
                  <ul class="nav nav-tabs" role="tablist">
                    <li id="opcion-resumendiario" class="active" role="presentation">
                      <a href="#brand" aria-controls="brand" role="tab" data-toggle="tab" data-bind="event:{click: $root.ActualizarPendientes}">
                      GENERACION Y ENVIO DE RESUMEN &nbsp;
                      </a>
                    </li>
                    <li id="opcion-resumendiarionuevo" role="presentation">
                      <a href="#resumendiario" aria-controls="resumendiario" role="tab" data-toggle="tab" data-bind="event:{click: $root.ActualizarGenerados}">
                        CONSULTA DE RESUMEN DIARIO
                      </a>
                    </li>
                  </ul>

                  <div class="tab-content">
                    <div class="tab-pane active" id="brand" role="tabpanel">
                      <?php echo $view_subcontent_consulta_resumendiarios; ?>
                    </div>

                    <div class="tab-pane" id="resumendiario" role="tabpanel">
                      <?php echo $view_subcontent_consulta_consultaresumendiarios; ?>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalPreview">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <strong><span>LISTA DE COMPROBANTES DE VENTA</span></strong>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <br>
              <div class="form-group">
                <b data-bind="text: 'TOTAL DE DOCUMENTOS: ' + DetalleResumenesDiario().length"></b>
              </div>
              <fieldset>
                <table class="datalist__table table" width="100%" data-products="brand">
                  <thead>
                    <tr>
                      <th class="products__id">Documento</th>
                      <th  class="products__title">Cliente</th>
                      <th  class="products__title">Total</th>
                      <th  class="products__title">Estado RD</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- ko foreach : DetalleResumenesDiario -->
                    <tr class="clickable-row" data-bind="attr : { id: IdDetalleResumenDiario }" style="text-transform: UpperCase;">
                      <td data-bind="text: Numero">FT F001-1524</td>
                      <td data-bind="text: RazonSocial">30/04/2016</td>
                      <td data-bind="text: MontoTotal">50.00</td>
                      <td data-bind="text: NombreEstado"></td>
                    </tr>
                    <!-- /ko -->
                  </tbody>
                </table>
              </fieldset>
            </div>
        </div>
    </div>
</div>

<!-- /ko -->
