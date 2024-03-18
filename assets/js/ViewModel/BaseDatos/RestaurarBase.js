IndexRestaurarBase = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.OnClickBtnRestaurarBase = function(data, event) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data" : data});
        $.ajax({
            type: 'POST',
            data : datajs,
            dataType: "json",
            url: SITE_URL+'/BaseDatos/cRestaurarBase/RestaurarBase',
            success: function (data) {
              if(data.error)
              {
                alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
              }
              else {
                alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
              }
              $("#loader").hide();
            },
            error : function (jqXHR, textStatus, errorThrown) {
                //console.log(jqXHR.responseText);
                $("#loader").hide();
                alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
            }
        });
      }
    }

    self.OnClickBtnCargarArchivo = function(data, event)
    {
      if(event)
      {
        data.NombreArchivo('C:/Users/Usuario/Desktop/Base/14.01.2019.sisem.sql');
        $("#ParseExcel").val("");
      }
    }


}
