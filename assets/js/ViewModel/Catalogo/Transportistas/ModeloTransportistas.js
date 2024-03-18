ModeloTransportistas = function (data) {
    var self = this;
    var base = data;
    
    self.copiatextofiltro = ko.observable("");

    self.ListarTransportistas =function(data,event,callback) {
      if(event) {
        self.ConsultarTransportistas(data,event,function($data,$event){
          base.data.Transportistas([]);

          ko.utils.arrayForEach($data.resultado, function (item) {
            base.data.Transportistas.push(new VistaModeloTransportista(item));
          });

          callback($data,$event);
        });
      }
    }

    self.ListarTransportistasPorPagina =function(data,event,callback) {
      if(event) {
        self.ConsultarTransportistasPorPagina(data,event,function($data,$event){
          base.data.Transportistas([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.Transportistas.push(new VistaModeloTransportista(item));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarTransportistas = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Catalogo/cTransportistas/ConsultarTransportistas',
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

    self.ConsultarTransportistasPorPagina = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Catalogo/cTransportistas/ConsultarTransportistasPorPagina',
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
