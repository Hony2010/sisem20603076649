<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx" id="principal">
  <div class="main__cont">

    <!-- <div class="form-group"> -->
      <!-- <fieldset> -->
        <!-- ko with : NotaCreditoCompra -->
        <!-- <div class="row">
          <div class="col-md-4">
            <div class="form-group text-center">
              <div class="radio radio-info text-shadow">
              <input id="radioMercaderia" type="radio" name="TipoCompra" class="no-tab" value="1" data-bind="checked : TipoCompra, event : { click : OnClickTipoCompra, change : OnChangeTipoCompra}">
              <label id="radLblMercaderias" for="radioMercaderia"  style="font-size:13px;">Mercaderías</label>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group text-center">
              <div class="radio radio-info text-shadow">
              <input id="radioServicio" type="radio" name="TipoCompra" class="no-tab" value="2" data-bind="checked : TipoCompra, event : { click : OnClickTipoCompra, change : OnChangeTipoCompra }">
              <label id="radLblServicios" for="radioServicio" style="font-size:13px;">Gastos</label>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group text-center">
              <div class="radio radio-info text-shadow">
              <input id="radioActivo" type="radio" name="TipoCompra" class="no-tab" value="3" data-bind="checked : TipoCompra, event : { click : OnClickTipoCompra, change : OnChangeTipoCompra }">
              <label id="radLblActivos" for="radioActivo" style="font-size:13px;">Costos Agregados</label>
              </div>
            </div>
          </div>
        </div> -->
        <!-- /ko -->
      <!-- </fieldset> -->
    <!-- </div> -->


    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <h3 class="panel-title"style="margin-top: 6px;">
                        REGISTRO DE NOTA DE CREDITO COMPRA
                      </h3>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <?php echo $view_subcontent_form_header_notacreditocompra; ?>
                  </div>
                </div>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <?php echo $view_subcontent_form_notacreditocompra; ?>
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

<!-- ko with : data.NotaCreditoCompra  -->
<?php echo $view_subcontent_modal_comprobantescompra; ?>
<!-- /ko -->
