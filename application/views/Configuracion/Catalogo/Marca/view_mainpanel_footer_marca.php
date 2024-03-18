<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
  //var modo=0;//0=Ninguno;1=Nuevo ; 2=Edicion
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/Marca.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/Modelo.js"></script>

<script>
    var Models = new Index(data);
    ko.applyBindingsWithValidation(Models);
</script>

<script>

window.onbeforeunload = function(e) {
    //e = e || window.event;
    if (existecambio)
    {
      e.preventDefault = true;
      e.cancelBubble = true;
      e.returnValue = 'test';
    }
    //e.preventDefault();
    console.log("-window-");
    console.log(e);
}


$(document).ready(function(){
   $(window).keydown(function(event){
      //alert(event.keyCode);
      //switch (event.keyCode) {
        if (event.altKey)
        {
          var code = event.keyCode;
          if(code == 78 || code == 110) // ALT + N = Nuevo FAmilia
          {
            //Validar si esta en vista marca o modelo
            //alert(code);
            //console.log("MARCAOPCION; "+ $("#opcion-marca").attr("class"));
            if($("#opcion-marca").attr("class") == "active")
            {
              Models.AgregarMarca(null,event);
            }
            else{
              Models.AgregarModelo(null,event);
            }

          }
        }
   });

});

var _primera_marca = Models.data.Marcas()[0];
//console.log(_primera_marca);
Models.Seleccionar(_primera_marca,null);
Models.PrimerModelo(_primera_marca);

</script>
