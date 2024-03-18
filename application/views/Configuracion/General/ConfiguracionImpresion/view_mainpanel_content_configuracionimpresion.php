<!-- ko with : data -->
  <div class="main__scroll scrollbar-macosx" id="principal">
    <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12">
            <?php echo $view_subcontent_preview_canvas; ?>
          </div>
          <div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Configuracion de Impresion</h3>
              </div>
              <div class="panel-body">

                <div class="datalist__result">
                  <ul class="nav nav-tabs" role="tablist">
                    <li id="opcion-brand" class="active" role="presentation" data-bind="event:{click: $root.OnClickConfiguracion}">
                      <a href="#brand_configuracionimpresion" aria-controls="brand" role="tab" data-toggle="tab">
                        Configuración de Impresion
                      </a>
                    </li>
                    <li id="opcion-canvas" role="presentation">
                      <a href="#canvas" aria-controls="canvas" role="tab" data-toggle="tab">
                        Canvas
                      </a>
                    </li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="brand_configuracionimpresion" role="tabpanel">
                      <?php echo $view_subcontent_consulta_configuracionimpresion; ?>
                    </div>
                    <div class="tab-pane" id="canvas" role="tabpanel">
                      <?php echo $view_subcontent_consulta_canvas; ?>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <center>
                    <img src="" width="60%" height="60%" id="foto_previa" name="foto_previa">
                </center>
            </div>
        </div>
    </div>
</div>
<!-- /ko -->
