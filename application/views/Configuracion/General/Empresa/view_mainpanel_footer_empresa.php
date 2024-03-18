<script>
    var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/Empresa.js"></script>

<script>
    var Models = new Index(data);
    ko.applyBindingsWithValidation(Models);

</script>

<script>
/*
window.onbeforeunload = function(e) {

    if (existecambio)
    {
      e.preventDefault = true;
      e.cancelBubble = true;
      e.returnValue = 'test';
    }
    //e.preventDefault();
    console.log("-window-");
    console.log(e);
}*/

$(document).ready(function(){

   var _idempresa = Models.data.Empresa.IdEmpresa();
   var _logotipo = Models.data.Empresa.Logotipo();

   if (_idempresa=="" || _idempresa == null || _logotipo == null || _logotipo == "")
   {
     src=ImageURL + "../nocover.png";
   }
   else
   {
     src=ImageURL+_idempresa+"/"+_logotipo;
   }
       
   $('#img_FileFoto').attr('src', src);

});



/*var _primera_linea = Models.data.Mercaderias()[0];
console.log(_primera_linea);
Models.Seleccionar(_primera_linea,null);*/
</script>
