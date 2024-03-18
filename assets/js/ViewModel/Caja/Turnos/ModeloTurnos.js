ModeloTurnos = function (data) {
    var self = this;
    var base = data;

    self.copiatextofiltro = ko.observable("");

    self.ListarTurnos =function(data,event,callback) {
      if(event) {
        self.ConsultarTurnos(data,event,function($data,$event){
          base.data.Turnos([]);

          ko.utils.arrayForEach($data.resultado, function (item) {
            base.data.Turnos.push(new VistaModeloTurno(item));
          });

          callback($data,$event);
        });
      }
    }

    self.ListarTurnosPorPagina =function(data,event,callback) {
      if(event) {
        self.ConsultarTurnosPorPagina(data,event,function($data,$event){
          base.data.Turnos([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.Turnos.push(new VistaModeloTurno(item));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarTurnos = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Caja/cTurnos/ConsultarTurnos',
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

    self.ConsultarTurnosPorPagina = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Caja/cTurnos/ConsultarTurnosPorPagina',
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
