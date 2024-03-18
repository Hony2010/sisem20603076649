ModeloConsultaGuiaRemisionRemitente = function (data) {
  var self = this;
  var base = data;

  self.PushGuiaRemisionRemitente = function (data, event) {
    if (event) {
      ko.utils.arrayForEach(data.resultado, function (item) {
        self.data.GuiasRemisionRemitente.push(new VistaModeloGuiaRemisionRemitente(item, configGRR));
      });
    }
  }

  self.ListarGuiasRemisionRemitente = function (data, event, callback) {
    if (event) {
      self.ConsultarGuiasRemisionRemitente(data, event, function ($data, $event) {
        self.data.GuiasRemisionRemitente([]);
        self.PushGuiaRemisionRemitente($data, $event);
        callback($data, $event);
      });
    }
  }
  
  self.ListarGuiasRemisionRemitentePorPagina = function (data, event, callback) {
    if (event) {
      self.ConsultarGuiasRemisionRemitentePorPagina(data, event, function ($data, $event) {
        self.data.GuiasRemisionRemitente([]);
        self.PushGuiaRemisionRemitente($data, $event);        
        callback(data, event);
      });
    }
  }

  self.ConsultarGuiasRemisionRemitente = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = ko.mapping.toJS({ "Data": data });
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: datajs,
        url: SITE_URL + '/Venta/cConsultaGuiaRemisionRemitente/ConsultarGuiasRemisionRemitente',
        success: function (data) {
          $("#loader").hide();
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert(jqXHR.responseText);
        }
      });
    }
  }

  self.ConsultarGuiasRemisionRemitentePorPagina = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = ko.mapping.toJS({ "Data": data });
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: datajs,
        cache: false,
        url: SITE_URL + '/Venta/GuiaRemisionRemitente/cConsultaGuiaRemisionRemitente/ConsultarGuiasRemisionRemitentePorPagina',
        success: function (data) {
          $("#loader").hide();
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert(jqXHR.responseText);
        }
      });
    }
  }

}
