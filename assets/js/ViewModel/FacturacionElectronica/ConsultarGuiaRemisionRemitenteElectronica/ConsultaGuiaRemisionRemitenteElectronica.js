var cantidad_filas = data.data.Numero_Filas;
var filas_seleccionadas = 0;
var option_button = 0;

var Mapping = Object.assign(
  MappingConsultaGuiaRemisionRemitenteElectronica
);

Index = function (data) {
    var self = this;
    ko.mapping.fromJS(data, Mapping, self);

    self.Inicializar = function ()  {
      $("#fecha-inicio").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-fin").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
    }

    self.ValidarXML = function (data, event) {
      if(event)
      {
        $("#loader").show();
        var old_data = data;

        $.ajax({
                type: 'POST',
                dataType: 'json',
                data : {"nombre": old_data.name},
                url: SITE_URL+'/FacturacionElectronica/cGuiaRemisionRemitenteElectronica/ValidarXML',
                success: function (data) {
                  if(data == '1')
                  {
                    window.location = old_data.url;
                    // window.open(old_data.url,'_blank');
                    console.log("Descarga" + old_data.url);
                  }
                  else {
                    alertify.alert("ADVERTENCIA!", "El archivo xml no se encuentra disponible. Consulte con el administrador.");
                  }
                  $("#loader").hide();
              },
              error : function (jqXHR, textStatus, errorThrown) {
                $("#loader").hide();
                   console.log(jqXHR.responseText);
               }
        });

      }
    }

    self.ValidarCDR = function (data, event) {
      if(event)
      {
        $("#loader").show();
        var old_data = data;

        $.ajax({
                type: 'POST',
                dataType: 'json',
                data : {"nombre": old_data.name},
                url: SITE_URL+'/FacturacionElectronica/cGuiaRemisionRemitenteElectronica/ValidarCDR',
                success: function (data) {
                  if(data == '1')
                  {
                    window.location = old_data.url;
                    // window.open(old_data.url,'_blank');
                    console.log("Descarga" + old_data.url);
                  }
                  else {
                    alertify.alert("ADVERTENCIA!", "El CDR no se encuentra disponible. Consulte con el administrador.");
                  }
                  $("#loader").hide();
              },
              error : function (jqXHR, textStatus, errorThrown) {
                $("#loader").hide();
                   console.log(jqXHR.responseText);
               }
        });

      }
    }


}
