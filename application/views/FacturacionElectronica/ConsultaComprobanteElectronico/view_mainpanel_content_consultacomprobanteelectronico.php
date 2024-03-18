<!-- ko with : data -->
<div class="main__scroll scrollbar-macosx" id="principal">
  <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products float-right">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Consulta de Comprobantes Electrónicos</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <?php echo $view_subcontent_consulta_consultacomprobanteelectronicos;?>
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
<?php echo $view_mainpanel_modal_enviocomprobante?>


<div class="modal fade bd-example-modal-lg" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalPDFGenerado">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <iframe class="embed-responsive-item" id="DescargarPDF_iframe" src="" style="width: 100%;height: 550px;"></iframe>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalXML">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <!-- <div id="editor">some text</div> -->
              <textarea id="editor" name="editor" rows="8" cols="80"></textarea>
              <!-- <iframe class="embed-responsive-item" id="MostrarXML_iframe" src="" style="width: 100%;height: 550px;"></iframe> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
