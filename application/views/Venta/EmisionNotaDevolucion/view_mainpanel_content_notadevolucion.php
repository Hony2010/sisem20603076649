<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx" id="principal">
  <div class="main__cont">
    <div class="form-group">
      <?php echo $view_tipoventa_notadevolucion ?>
    </div>
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
                        EMISION NOTA DE DEVOLUCION
                      </h3>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <?php echo $view_subcontent_form_header_notadevolucion; ?>
                  </div>
                </div>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <?php echo $view_subcontent_form_notadevolucion; ?>
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

<!-- ko with : data.NotaCredito  -->
<?php echo $view_subcontent_modal_comprobantesventa; ?>
<!-- /ko -->
