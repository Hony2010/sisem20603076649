<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
  //var modo=0;//0=Ninguno;1=Nuevo ; 2=Edicion
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Catalogo/RadioTaxi/RadioTaxi.js"></script>

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
            Models.AgregarRadioTaxi(null,event);
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

if(Models.data.RadiosTaxi().length > 0)
{
  var _primera_linea = Models.data.RadiosTaxi()[0];
  Models.Seleccionar(_primera_linea,null);
}

RecorrerTabla.Agregar("#DataTables_Table_0 tbody");
</script>
