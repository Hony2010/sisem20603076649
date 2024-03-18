<script>
  var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/ModeloVistaComunicacionBaja.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/FacturacionElectronica/ComunicacionBaja.js"></script>

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
// $("#opcion-comunicacionbaja").is(":active")
AccesoKey.AgregarMultiTab("#brand", "btnBuscarGeneracion", "btnBuscarConsulta", TECLA_B);

</script>


<script type="text/javascript">
  $(document).ready(function(){
    $('body').on('click', '.DescargaXML',function(){
      var url = $(this).attr('name');
      var name = $(this).val();
      var data = {};
      data.url = url;
      data.name = name;
      Models.ValidarXML(data, window);
    });

    $('body').on('click', '.DescargaCDR',function(){
      var url = $(this).attr('name');
      var name = $(this).val();
      var data = {};
      data.url = url;
      data.name = name;
      Models.ValidarCDR(data, window);
    });

  });
</script>
