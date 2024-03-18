<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
  //var modo=0;//0=Ninguno;1=Nuevo ; 2=Edicion
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/Catalogo/TipoActivo.js"></script>

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

/*
$(".class_NombreTipoActivo").keyup(function()
{
  console.log("-class-");
  existecambio = true;
  console.log(existecambio);
})
*/
    $('#pagination-demo').pagination({
            items: 20,
            itemOnPage: 8,
            currentPage: 1,
            cssStyle: '',
            prevText: '<span aria-hidden="true">&laquo;</span>',
            nextText: '<span aria-hidden="true">&raquo;</span>',
            onInit: function () {
                // fire first page loading
            },
            onPageClick: function (page, evt) {
                // some code
                console.log(page);
                console.log(evt);
            }
        });

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
            Models.AgregarTipoActivo(null,event);
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

var _primera_linea = Models.data.TiposActivo()[0];
console.log(_primera_linea);
Models.Seleccionar(_primera_linea,null);

</script>
