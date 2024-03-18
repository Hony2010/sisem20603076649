<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx" id="principal">
  <div class="main__cont">
    <div class="form-group">
      <?php echo $view_tipoventa_notadebito ?>
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
                        EMISION DE NOTA DE DEBITO
                      </h3>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <?php echo $view_subcontent_form_header_notadebito; ?>
                  </div>
                </div>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <?php echo $view_subcontent_form_notadebito; ?>
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

<!-- ko with : data.NotaDebito  -->
<?php echo $view_subcontent_modal_comprobantesventa; ?>
<!-- /ko -->
