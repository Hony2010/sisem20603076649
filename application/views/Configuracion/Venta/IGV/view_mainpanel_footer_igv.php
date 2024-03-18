<script>

var data=<?php echo json_encode($data); ?>;

$(document).ready(function functionName() {

  $('#ValorIgv').val(data.igv * 100)

  $('body').on('click', '#GuardarIgv', function() {
    var $data = $('#ValorIgv').val();
    var datajs = {'Data':$data};
    $("#loader").show();
    $.ajax({
      type: 'POST',
      data : datajs,
      dataType: "json",
      url: SITE_URL+'/Configuracion/Venta/cIgv/ActualizarIgv',
      success: function (data){
        $("#loader").hide();
        if (data.ValorParametroSistema) {
          alertify.alert("Configuracion Igv","Se guardo correctamente.");
        }
        else {
          alertify.alert("Configuracion Igv",data);
        }
      },
      error : function (jqXHR, textStatus, errorThrown) {
        $("#loader").hide();
        alertify.alert("Ha ocurrido un error",jqXHR.responseText);
      }
    });
  });
});
</script>
