VistaModeloPreCuentas = function (data) {
  var self = this;

  ko.mapping.fromJS(data, {}, self);

  self.InicializarModelo = function (data,event) {
    if(event) {
    }
  }

  self.OnClickPreCuenta = function (data, event, $parent) {
    if (event) {
      if ($(".detail-voucher").is(":visible")) {
        $parent.PreVenta.OnHideOrShowElement(data, event);
      }
      $('#'+data.IdComprobanteVenta()+'_tr_precuenta').addClass('active').siblings().removeClass('active');
      $("#AplicarDescuentoTarjeta").prop("checked",true);
      $("#loader").show();
      self.ObtenerDetallePreCuenta(data, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          var datapreventa = self.ParseDataPreCuenta($data, event, $parent, data);
          $parent.PreVenta.AbrirPreVenta(datapreventa, event);
          $parent.PreVenta.IndicadorEstadoPreVenta(ESTADO_INDICADOR_PREVENTA.PRECUENTA);
          $parent.PreVenta._IdCliente(data.IdCliente());
          sizeProductDetails();

        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
        }
      })
    }
  }

  self.ParseDataPreCuenta = function (data, event, $parent, dataprecuenta) {
    if (event) {
      var objetonuevo = ko.mapping.toJS($parent.PreVenta.NuevaPreVenta);
      var serie = objetonuevo.SeriesDocumento.filter( item => item.IdTipoDocumento == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO );
      if (serie.length > 0) {
        var detalles = data.filter( item => item.SaldoPendientePreVenta > 0 );
        var objetoadicional = {
          'DetallesComprobanteVenta' : detalles.length > 0 ? detalles : data,
          'IdCorrelativoDocumento': "",
          'SerieDocumento': "",
          'IdTipoDocumento' : "",
          'NumeroDocumento': "",
          'IdComprobantePreVenta': dataprecuenta.IdComprobanteVenta(),
          'IdCliente': dataprecuenta.IdCliente(),
          'IndicadorCanjeado': dataprecuenta.IndicadorCanjeado(),
          'AliasUsuarioVenta': dataprecuenta.AliasUsuarioVenta(),
          'IdMesa': $(".btn-mesa").filter('.active').data('idmesa')
        }
      }
      Object.assign(objetonuevo, objetoadicional);
      return objetonuevo;
    }
  }


  self.ObtenerDetallePreCuenta = function(data,event,callback) {
    if(event) {
      var datajs = {Data: JSON.stringify(ko.mapping.toJS(data))};

      $.ajax({
        type: 'POST',
        data : datajs,
        dataType : "json",
        url: SITE_URL_BASE+'/Venta/cPreVenta/ConsultarDetallesComprobanteVenta',
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
