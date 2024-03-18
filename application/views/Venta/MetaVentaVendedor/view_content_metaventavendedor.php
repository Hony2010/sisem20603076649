<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Metas de vendedor</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <form action="">
                    <?php echo $view_tabla_metaventavendedor; ?>
                  </form>
                  <div class="form-group">
                    <br>
                    <button type="button" class="btn btn-primary" data-bind="event: { click: $root.OnClickBtnGuargarMetaVentaVendedor }">Guardar metas</button>
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