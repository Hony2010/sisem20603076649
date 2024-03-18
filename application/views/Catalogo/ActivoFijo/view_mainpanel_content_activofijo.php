<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <!-- <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <?php //echo $view_subcontent_preview_activofijo; ?>
          </div> -->
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Activos Fijos &nbsp; <button id="btnNuevo" class="btn btn-info" type="button" data-bind="click : $root.Nuevo"><u>N</u>uevo</button></h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <?php echo $view_subcontent_consulta_activofijos; ?>
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

<?php echo $view_subcontent_form_activofijo; ?>
