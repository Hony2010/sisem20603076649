<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <?php echo $view_subcontent_preview_proveedor; ?>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Proveedor
                  <button id="btnNuevo" class="btn btn-info" type="button" data-bind="click : $root.OnClickBtnNuevo"><u>N</u>uevo</button>
                </h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <?php echo $view_subcontent_consulta_proveedores; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $view_subcontent_modal_proveedor;?>
<?php echo $view_subcontent_modal_preview_foto_proveedor;?>
<!-- /ko -->
