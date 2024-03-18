ModeloControlMesa = function (data) {
  var self = this;
  var base = data;

  self.ObtenerUltimaComandaPorMesa = function(data,event,callback) {
    if(event) {
      var datajs ={Data: JSON.stringify(ko.mapping.toJS(data))};
      $.ajax({
        type: 'POST',
        dataType: 'json',
        data : datajs,
        url: SITE_URL+'/Venta/cPreVenta/ConsultarUltimaComandaPorNumeroMesa',
        success: function (data) {
          callback(data,event);
        },
        error : function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.ObtenerUltimaPreVentaPorMesa = function(data,event,callback) {
    if(event) {
      var datajs ={Data: JSON.stringify(ko.mapping.toJS(data))};
      $.ajax({
        type: 'POST',
        dataType: 'json',
        data : datajs,
        url: SITE_URL+'/Venta/cPreVenta/ConsultarUltimaComandaPorNumeroMesa',
        success: function (data) {
          callback(data,event);
        },
        error : function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.ConsultarComandas = function(data,event,callback) {
    if(event) {
      var datajs ={Data: JSON.stringify(ko.mapping.toJS(data))};
      $.ajax({
        type: 'POST',
        dataType: 'json',
        data : datajs,
        url: SITE_URL+'/Venta/cPreVenta/ConsultarComandasPorMesa',
        success: function (data) {
          callback(data,event);
        },
        error : function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.ConsultarPreCuentas = function(data,event,callback) {
    if(event) {
      var datajs ={Data: JSON.stringify(ko.mapping.toJS(data))};
      $.ajax({
        type: 'POST',
        dataType: 'json',
        data : datajs,
        url: SITE_URL+'/Venta/cPreVenta/ConsultarPreVentasPorMesa',
        success: function (data) {
          callback(data,event);
        },
        error : function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

}
