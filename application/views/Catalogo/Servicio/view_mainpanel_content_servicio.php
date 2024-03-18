<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <?php echo $view_subcontent_preview_servicio; ?>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Servicios <button id="btnNuevo" class="btn btn-info" type="button" data-bind="click : $root.Nuevo"><u>N</u>uevo</button></h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                    <?php echo $view_subcontent_consulta_servicios; ?>
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
<?php echo $view_subcontent_form_servicio; ?>

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
