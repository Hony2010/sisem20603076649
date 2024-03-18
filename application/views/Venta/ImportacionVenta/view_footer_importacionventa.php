<script>
    var data=<?php echo json_encode($data); ?>;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ImportacionVenta/MappingVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ImportacionVenta/ModeloImportacionVenta.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Venta/ImportacionVenta/VistaModeloImportacionVenta.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var ViewModels = new VistaModeloImportacionVenta(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(ViewModels, koNode);
</script>

<script type="text/javascript">
  ViewModels.Inicializar();
</script>

<script type="text/javascript">
  var optionsWizard = {
      mode: 'wizard',
      autoButtonsNextClass: 'btn btn-primary floating-right',
      autoButtonsPrevClass: 'btn btn-print',
      // stepNumberClass: 'badge badge-pill badge-primary mr-1',
      // stepNumbers: true,
      autoButtonsSubmitText: 'Guardar',
      beforeNextStep: function( currentStep ) { 
        // console.log(currentStep);
        if(currentStep == 1)
        {
          // var pass = false;
          ViewModels.LimpiarObjetos(null, window);

          ViewModels.ValidarListaClientes(null, window, function($data, $event){
            console.log($data);
            // pass = true;
            // ViewModels.Validacion(true);
          });
          
          if(ViewModels.data.ClientesBase().length > 0 || ViewModels.data.ClientesImportacion().length > 0)
          {
            alertify.alert("VALIDACION!", "Verifique los registros para continuar...", function(){
              alertify.alert().destroy();
            });
            return false;
          }
          else
          {
            return true;
          }
        }
        else if(currentStep == 2)
        {
          // var pass = false;
          ViewModels.LimpiarObjetos(null, window);

          ViewModels.ValidarListaProductos(null, window, function($data, $event){
            console.log($data);
            // pass = true;
            // ViewModels.Validacion(true);
          });
          
          if(ViewModels.data.ProductosBase().length > 0 || ViewModels.data.ProductosImportacion().length > 0)
          {
            alertify.alert("VALIDACION!", "Verifique los registros para continuar...", function(){
              alertify.alert().destroy();
            });
            return false;
          }
          else
          {
            ViewModels.CargarComprobantesVenta(null, window, function($data, $event){
              console.log($data);
              // pass = true;
              // ViewModels.Validacion(true);
            });
            return true;
          }
        }
        else
        {
          return true; 
        }
      },
      onSubmit: function( element ) { 
        ViewModels.InsertarVentasJSON(null, window, function($data, $event){
          console.log($data);
        });
        return false; 
      }
    };
  $( "#form-importacion" ).accWizard(optionsWizard);
</script>