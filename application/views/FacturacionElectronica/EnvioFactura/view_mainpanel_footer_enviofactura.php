
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
<script>
  var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/ModeloVistaEnvioFactura.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/EnvioFactura.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var Models = new Index(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(Models, koNode);
</script>

<script type="text/javascript">
  Models.Inicializar();
</script>

<script type="text/javascript">
AccesoKey.Agregar("BuscadorEnvio", 66);
</script>
