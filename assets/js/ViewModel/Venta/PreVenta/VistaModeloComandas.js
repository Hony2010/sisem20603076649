VistaModeloComandas = function (data) {
  var self = this;

  ko.mapping.fromJS(data, {}, self);

  self.InicializarModelo = function (data,event) {
    if(event) {
    }
  }

  self.OnClickComanda = function (data, event, $parent) {
    if (event) {
      // if (data.IndicadorPreCuenta() == 1) { return false; }

      if ($(".detail-voucher").is(":visible")) {
        $parent.PreVenta.OnHideOrShowElement(data, event);
      }

      $('#'+ data.IdComprobanteVenta()+'_tr_comanda').addClass('active').siblings().removeClass('active');
      $("#ClienteConTarjeta").text("");
      $("#AplicarDescuentoTarjeta").prop("checked",true);

      $("#loader").show();
      self.ObtenerDetalleComanda(data, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          var datapreventa = self.ParseDataComanda($data, event, $parent, data);
          if (datapreventa.DetallesComprobanteVenta.length <= 0 ) { return false; }
          $parent.PreVenta.AbrirPreVenta(datapreventa, event);
          $parent.PreVenta.IndicadorEstadoPreVenta(ESTADO_INDICADOR_PREVENTA.COMANDA);
          $parent.PreVenta.CambiarClientesVariosPreCuenta(data, event);
          sizeProductDetails();
          $("#ClientePreCuenta").autoCompletadoCliente(event,$parent.PreVenta.ValidarAutoCompletadoClientePreCuenta,CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS,"#ClientePreCuenta");
        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
        }
      })
    }
  }

  self.ParseDataComanda = function (data, event, $parent, datacomanda) {
    if (event) {
      var objetonuevo = ko.mapping.toJS($parent.PreVenta.NuevaPreVenta);
      var serie = objetonuevo.SeriesDocumento.filter( item => item.IdTipoDocumento == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO );
      if (serie.length > 0) {
        var objetoadicional = {
          'DetallesComprobanteVenta' : data,
          'IdCorrelativoDocumento': serie[0].IdCorrelativoDocumento,
          'SerieDocumento': serie[0].SerieDocumento,
          'IdTipoDocumento' : serie[0].IdTipoDocumento,
          'NumeroDocumento': "",
          'IndicadorPreCuenta' : datacomanda.IndicadorPreCuenta(),
          'AliasUsuarioVenta' : datacomanda.AliasUsuarioVenta(),
          'IdComprobantePreVenta': datacomanda.IdComprobanteVenta(),
          'IdMesa': $(".btn-mesa").filter('.active').data('idmesa'),
          'IdCliente' : ID_CLIENTES_VARIOS
        }
      }
      Object.assign(objetonuevo, objetoadicional);
      return objetonuevo;
    }
  }


  self.ObtenerDetalleComanda = function(data,event,callback) {
    if(event) {
      var datajs = {Data: JSON.stringify(ko.mapping.toJS(data))};

      $.ajax({
        type: 'POST',
        data : datajs,
        dataType : "json",
        url: SITE_URL_BASE+'/Venta/cPreVenta/ConsultarDetallesComprobantePreVentaConsolidado',
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
