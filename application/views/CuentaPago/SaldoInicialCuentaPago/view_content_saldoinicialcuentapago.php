<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Saldos Iniciales Cuenta Pago</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                    <?php echo $view_buscador_saldoinicialcuentapago; ?>
                    <?php echo $view_tabla_saldoinicialcuentapago; ?>
                    <br>
                    <?php echo $view_paginacion_saldoinicialcuentapago; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php echo $view_modal_saldoinicialcuentapago; ?>

<!-- /ko -->
