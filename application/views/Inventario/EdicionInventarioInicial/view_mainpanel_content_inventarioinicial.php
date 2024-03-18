<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx" id="principal">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Inventario Inicial <button id="btnNuevo" class="btn btn-info" type="button" data-bind="click : $root.OnClickBtnNuevaMercaderia"><u>N</u>uevo</button></h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                    <?php echo $view_subcontent_consulta_inventarioinicial; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $view_subcontent_form_inventarioinicial; ?>
<?php echo $view_subcontent_modal_form_mercaderia; ?>
<!-- /ko -->
