<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx" id="principal">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <!-- ko if: MostarBetaSunat() == 1 -->
        <div class="row" id="nota-beta" style="display: none;">
          <div class="col-md-12">
            <div class="nota-beta text-center" style="color: #fff; padding: 2px 10px; margin-bottom: 10px; background-color: red;">
              <h5 class="text-uppercase">
                <label><b>ADVERTENCIA: ESTOS COMPROBANTES NO SERAN ENVIADOS A SUNAT, COMUNIQUE INMEDIATAMENTE AL PROVEEDOR DEL SISTEMA.</b></label>
              </h5>
            </div>
          </div>
        </div>
        <!-- /ko -->
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Envío de Guias Remisión</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <ul class="nav nav-tabs" role="tablist">
                    <li id="opcion-enviofactura" class="active" role="presentation">
                      <a href="#brand" aria-controls="brand" role="tab" data-toggle="tab" data-bind="event:{click: $root.ActualizarEnvios}">
                        ENVIO DE GUIAS REMISION ELECTRONICAS &nbsp;
                      </a>
                    </li>
                    <li id="opcion-enviofacturanuevo" role="presentation">
                      <a href="#enviofactura" aria-controls="enviofactura" role="tab" data-toggle="tab" data-bind="event:{click: $root.ActualizarPedientes}">
                        CONSULTA DE GUIAS REMISION ELECTRONICAS
                      </a>
                    </li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="brand" role="tabpanel">
                      <?php echo $view_subcontent_consulta_envioguiasremisionremitente; ?>
                    </div>

                    <div class="tab-pane" id="enviofactura" role="tabpanel">
                      <?php echo $view_subcontent_consulta_envioguiasremisionremitenteconsulta; ?>
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
<!-- /ko -->