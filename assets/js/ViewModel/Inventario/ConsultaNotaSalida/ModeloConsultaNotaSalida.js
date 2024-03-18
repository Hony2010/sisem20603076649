ModeloConsultaNotaSalida = function (data) {
    var self = this;
    var base = data;

    self.ListarNotasSalida = function(data,event,callback){
      if(event) {
          self.ConsultarNotasSalida(data,event,function($data,$event) {
          base.data.NotasSalida([]);

          ko.utils.arrayForEach($data.resultado, function (item) {
            base.data.NotasSalida.push(new VistaModeloNotaSalida(item));
          });

          callback($data,$event);

        });
      }
    }

    self.ListarNotasSalidaPorPagina =function(data,event,callback) {
      if(event) {
          self.ConsultarNotasSalidaPorPagina(data,event,function($data,$event){
          base.data.NotasSalida([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.NotasSalida.push(new VistaModeloNotaSalida(item));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarNotasSalida = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Inventario/NotaSalida/cConsultaNotaSalida/ConsultarNotasSalida',
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

    self.ConsultarNotasSalidaPorPagina = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Inventario/NotaSalida/cConsultaNotaSalida/ConsultarNotasSalidaPorPagina',
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
