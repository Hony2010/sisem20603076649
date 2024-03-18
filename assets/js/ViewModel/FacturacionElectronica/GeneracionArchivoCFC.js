var cantidad_filas = data.data.Numero_Filas;
var filas_seleccionadas = 0;
var option_button = 0;

var Mapping = Object.assign(
  MappingGeneracionArchivoCFC
);

Index = function (data) {
    var self = this;
    ko.mapping.fromJS(data, Mapping, self);

    self.CheckMotivo = ko.observable(false);

    self.Inicializar = function() {
      $("#fecha-inicio").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      $("#fecha-fin").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
      // $("#EnviarFTP").prop('disabled', true);
    }

    self.GenerarCFC = function (data, event) {
      if(event)
      {
        if(Models.data.ComprobantesVenta().length < 1)
        {
          alertify.alert("Por lo menos debe de existir un registro listado para proceder.");
          return false;
        }

        $("#loader").show();
        var objeto = Knockout.CopiarObjeto(Models.data.ComprobantesVenta);
        // var datajs = ko.mapping.toJS({"Data" : objeto}, mappingIgnore);
        var datajs = ko.mapping.toJS(objeto, mappingIgnore);

        var data_json = JSON.stringify(datajs);
        var fecha = $("#fecha-inicio").val();
        // console.log(data_json);
        //var datajs = null;
        $.ajax({
                type: 'POST',
                data : {Data: data_json, Fecha: fecha},//datajs,
                dataType : "json",
                url: SITE_URL+'/FacturacionElectronica/cGeneracionArchivoCFC/GenerarArchivoCFC',
                success: function (data) {
                      console.log("Generar CFC");
                      console.log(data);
                      if(data.error == "")
                      {
                        alertify.alert("Su Archivo fue generado con exito.", function(){
                          window.location = data.url;
                          alertify.alert().destroy();
                        });
                      }
                      else {
                        alertify.alert(data.error);
                      }
                    $("#loader").hide();
              },
              error : function (jqXHR, textStatus, errorThrown) {
                     console.log(jqXHR.responseText);
                     $("#loader").hide();
              }
        });

      }
    }

    self.OnChangeCheckMotivo = function(data, event)
    {
      if(event)
      {
        if(self.CheckMotivo() == false)
        {
          $("#combo-motivo").prop('disabled', true);
        }
        else {
          var id = $("#combo-motivo option:selected").val();
          self.AplicarMotivoTodos(id, event);
          $("#combo-motivo").prop('disabled', false);
        }
      }
    }

    self.OnChangeMotivo = function(data, event)
    {
      if(event)
      {
        if(self.CheckMotivo() == true)
        {
          var id = $(event.target).val();
          self.AplicarMotivoTodos(id, event);
        }
      }
    }

    self.AplicarMotivoTodos = function(data, event)
    {
      if(event)
      {
        if(Models.data.ComprobantesVenta().length > 0)
        {
          ko.utils.arrayFirst(Models.data.ComprobantesVenta(), function(item) {
            item.IdMotivoComprobanteFisicoContingencia(data);
          });
        }
        else {
          alertify.alert("Necesita registros para usar esta opcion");
          self.CheckMotivo(false);
        }
      }
    }

}
