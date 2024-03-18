ModeloConsultaCanjeLetraCobrar = function (data) {
    var self = this;
    var base = data;

    self.ParseCanjesLetraCobrar = function (data, event) {
      if (event) {
        return new VistaModeloCanjeLetraCobrar(data, configCanjeLetraCobrar);
      }
    }

    self.ListarCanjesLetraCobrar = function (data,event,callback) {
      if(event) {
        var objeto = ko.mapping.toJS(data);

        objeto.IdTipoDocumento = "%";

        self.ConsultarCanjesLetraCobrar(objeto,event,function($data,$event) {
        base.data.CanjesLetraCobrar([]);

        ko.utils.arrayForEach($data.resultado, function (item) {
          base.data.CanjesLetraCobrar.push(self.ParseCanjesLetraCobrar(item, event));
        });

        callback($data,$event);

        });
      }
    }

    self.ListarCanjesLetraCobrarPorPagina =function(data,event,callback) {
      if(event) {

        objeto.IdTipoDocumento = "%";

        self.ConsultarCanjesLetraCobrarPorPagina(data,event,function($data,$event){
          base.data.CanjesLetraCobrar([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.CanjesLetraCobrar.push(self.ParseCanjesLetraCobrar(item, event));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarCanjesLetraCobrar = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'POST',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/CuentaCobranza/cConsultaCanjeLetraCobrar/ConsultarCanjesLetraCobrar',
          success: function (data) {
              $("#loader").hide();
              callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert(jqXHR.responseText);
          }
        });
      }
    }

    self.ConsultarCanjesLetraCobrarPorPagina = function(data,event,callback) {
      if(event) {
        $("#loader").show();
        var datajs = { Data: JSON.stringify(ko.mapping.toJS(data, mappingIgnore))}
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/CuentaCobranza/cConsultaCanjeLetraCobrar/ConsultarCanjesLetraCobrarPorPagina',
          success: function (data) {
              $("#loader").hide();
              callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert(jqXHR.responseText);
          }
        });
      }
    }

}
