<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
  //var modo=0;//0=Ninguno;1=Nuevo ; 2=Edicion
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/TipoCambio.js"></script>

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
$(".class_NombreTipoCambio").keyup(function()
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
            Models.AgregarTipoCambio(null,event);
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

   var texto2;
   var decimal2 = "";
   var entero2 = "";

   //var c_enteros = parseInt(Models.data.EnteroValue()[0]);
   //var c_decimal = parseInt(Models.data.DecimalValue()[0]);

   $("#brand").on("keypress keyup", ".decimal-control", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if($(this).val() == "")
        {
          if(event.which == 46)
          {
            event.preventDefault();
          }
        }

         if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();

         }

         var texto = $(this).val();

         var contar = $(this).val().length;
         var inicio = texto.indexOf(".");
         var dtexto = texto.split('.');
         //var fin = texto.substring(inicio + 1, contar);
         console.log("C_ENTEROS: " + c_entero);
         console.log("C_DECIMAL: " + c_decimal);
         console.log("ENTEROS: " + dtexto[0]);
         console.log("DECIMAL: " + dtexto[1]);

         if(inicio > -1){
           if(dtexto[0].length < c_entero + 1){
             texto2 = texto;
             entero2 = dtexto[0];
             /*if(entero2 != dtexto[0])
             {
               entero2 = dtexto[0];
             }*/

           }
           if(dtexto[1].length < c_decimal + 1){
             texto2 = texto;
             decimal2 = dtexto[1];
             /*if(decimal2 != dtexto[1])
             {
               decimal2 = dtexto[1];
             }*/

           }
         }
         else {
           if(dtexto[0].length < c_entero + 1){
             entero2 = dtexto[0];
             texto2 = texto;
           }
         }

         if(inicio > -1){
           //debugger;
           if(dtexto[0].length > c_entero){
             if(dtexto[0] != entero2)
             {
               //alert(entero2);
               console.log("ENTERO[]: " + dtexto[0]);
               console.log("ENTERO: " + entero2);
               console.log("TOTAL: " + entero2 + "." + decimal2);
               //event.preventDefault();
               //$(this).val(texto2);
               //entero2 = dtexto[0];
               $(this).val(entero2 + "." + decimal2);
             }
           }

           if(dtexto[1].length > c_decimal){
             if(dtexto[1] != decimal2)
             {
               console.log("DECIMAL[]: " + dtexto[1]);
               console.log("DECIMAL: " + decimal2);
               console.log("TOTAL: " + entero2 + "." + decimal2);
               //event.preventDefault();
               //decimal2 = dtexto[1];
               //alert(decimal2);
               $(this).val(entero2 + "." + decimal2);
             }
           }
         }
         else {
           if(dtexto[0].length > c_entero)
           {
             if(dtexto[0] != entero2)
             {
               console.log("ENTERO[]: " + dtexto[0]);
               console.log("ENTERO: " + entero2);
                //event.preventDefault();
                $(this).val(entero2);
               //$(this).val(texto2);
             }
           }
         }



         /*if($(this).val().indexOf('.') != -1){
           if(fin.length >= 3)
           {
             //return false;
             //alert(fin);
             event.preventDefault();
           }
         }*/

         //alert(inicio + " " + fin)
         //var res = dec.substring(dec.indexOf(".")+1);
         //var kl = res.split("");

    });
});

var _primera_linea = Models.data.TiposCambio()[0];
console.log(_primera_linea);
Models.Seleccionar(_primera_linea,null);

</script>
