<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
  //var modo=0;//0=Ninguno;1=Nuevo ; 2=Edicion
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/Gasto.js"></script>

<script>
  var koNode = document.getElementById('maincontent');
  var Models = new Index(data);
  ko.cleanNode(koNode);
  ko.applyBindingsWithValidation(Models, koNode);
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

/*
$(".class_NombreGasto").keyup(function()
{
  console.log("-class-");
  existecambio = true;
  console.log(existecambio);
})
*/

$(document).ready(function(){

   $(window).keydown(function(event){
      //alert(event.keyCode);
      //switch (event.keyCode) {
        if (event.altKey)
        {
          var code = event.keyCode;
          if(code == 78 || code == 110) // ALT + N = Nuevo Linea
          {
            //Validar si esta en vista linea
            //alert(code);
            Models.AgregarGasto(null,event);
          }

      }
   });

   $('body').keydown(function(){
     var code = event.keyCode;
     if(code == 27)
     {
       Models.EscaparGlobal(event);
     }
   });
});

var _primera_linea = Models.data.Gastos()[0];
if (_primera_linea !== undefined) {
  console.log(_primera_linea);
  Models.Seleccionar(_primera_linea,null);
  RecorrerTabla.Agregar("#DataTables_Table_0 tbody");
}
</script>
