<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
  //var modo=0;//0=Ninguno;1=Nuevo ; 2=Edicion
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/FamiliaProducto.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/SubFamiliaProducto.js"></script>

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
            //Validar si esta en vista familia o subfamilia
            //alert(code);
            if( $("#opcion-familiaproducto").attr("class")=="active")
            {
              Models.AgregarFamiliaProducto(null,event);
            }
            else {
              Models.AgregarSubFamiliaProducto(null,event);              
            }
          }
        }
   });
});

var _primera_familia = Models.data.FamiliasProducto()[0];
//console.log(_primera_familia);
Models.Seleccionar(_primera_familia,null);
Models.PrimerSubFamiliaProducto(_primera_familia);

</script>
