ModeloConsultaNotaEntrada = function (data) {
    var self = this;
    var base = data;

    self.ListarNotasEntrada = function(data,event,callback){
      if(event) {
          self.ConsultarNotasEntrada(data,event,function($data,$event) {
          base.data.NotasEntrada([]);

          ko.utils.arrayForEach($data.resultado, function (item) {
            base.data.NotasEntrada.push(new VistaModeloNotaEntrada(item));
          });

          callback($data,$event);

        });
      }
    }

    self.ListarNotasEntradaPorPagina =function(data,event,callback) {
      if(event) {
          self.ConsultarNotasEntradaPorPagina(data,event,function($data,$event){
          base.data.NotasEntrada([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.NotasEntrada.push(new VistaModeloNotaEntrada(item));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarNotasEntrada = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Inventario/NotaEntrada/cConsultaNotaEntrada/ConsultarNotasEntrada',
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

    self.ConsultarNotasEntradaPorPagina = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Inventario/NotaEntrada/cConsultaNotaEntrada/ConsultarNotasEntradaPorPagina',
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
