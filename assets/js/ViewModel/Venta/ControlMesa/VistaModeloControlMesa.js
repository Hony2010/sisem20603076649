VistaModeloControlMesa = function (data) {

  var self = this;
  var mesaseleccionado = {};
  ko.mapping.fromJS(data, MappingVenta, self);
  ModeloControlMesa.call(this,self);
  self.Options = ko.observable([])
  self.Inicializar = function ()  {
    $(".Fecha").inputmask({"mask": "99/99/9999"});
    self.data.PreVenta.Total('0.00')
    moversidebar();
    sizeProductList();
    sizeProductDetails();
  }

  self.Seleccionar = function (data,event)  {
    if (event)
    {
      if (data != undefined) {
        var id = "#"+ data.IdComprobanteVenta();
        $(id).addClass('active').siblings().removeClass('active');
      }
    }
  }

  self.OnClickBtnMesaVendedor = function(data, event, $parent) {
    if(event) {
      var idrolusuario = $parent.IdRolUsuario();
      mesaseleccionado = data;
      $("#loader").show();
      self.ObtenerUltimaComandaPorMesa(data, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          self.PostObtenerUltimaComandaPorMesa($data, event, data);
          self.OpenModal(self.Options(), event);
        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
        }
      });
    }
  }

  self.OnClickBtnMesaCajero = function(data, event, $parent) {
    if(event) {
      var idrolusuario = $parent.IdRolUsuario();
      mesaseleccionado = data;
      $("#IdMesaSeleccionado").text("MESA: "+ mesaseleccionado.NumeroMesa());
      mesaseleccionado.FechaInicio = $parent.FechaInicio();
      mesaseleccionado.FechaFin = $parent.FechaInicio();
      self.OnClickPreVentas(data, event);
    }
  }

  self.PostObtenerUltimaComandaPorMesa = function (data, event, mesa) {
    if (event) {
      if (data.IdComprobanteVenta) {
        self.data.Comanda.AbrirPreVenta(data,event,self.PostGuardarComandaMesa);
      } else {
        var nuevacomanda = ko.mapping.toJS(self.data.Comanda.NuevaComanda);
        var adicionaldata = { IdMesa: mesa.IdMesa(), Total:"0.00", IdCliente: ID_CLIENTES_VARIOS  };
        Object.assign(nuevacomanda, adicionaldata);
        self.data.Comanda.AbrirPreVenta(nuevacomanda,event,self.PostGuardarComandaMesa);
      }
      self.data.Comanda.TituloComprobante("Registro de Comanda" + " - " + "MESA " + mesa.NumeroMesa());
      self.data.Comanda.OnClickBtnBuscadorMercaderia(self.data.Comanda, window, self.data);
      self.Options(self.data.Comanda.Options);
    }
  }

  self.OnClickPreVentas = function (mesa, event) {
    if (event) {
      $(".group-btn-preventa").show();
      $(".btn-preventa").eq(0).click();
    }
  }

  self.OpenModal = function (data, event) {
    if (event) {
      if (data.IDForm) {
        $(data.IDForm).find(".btn-familias").eq(0).addClass("active").click();
        $(data.IDModalComprobanteVenta).modal("show");
        self.Options([]);
        setTimeout(function () {
          sizeProductList();
          sizeProductDetails();
        }, 200);
      }
    }
  }
  self.PostGuardarComandaMesa = function (data, event) {
    if (event) {
      if (data.IdComprobanteVenta() != "" && data.IdComprobanteVenta() != null) {
        mesaseleccionado.SituacionMesa(SITUACION_MESA.OCUPADO);
      }
    }
  }

  self.ObtenerComandas = function (data, event) {
    if (event) {
      if ($(".detail-voucher").is(":visible")) { self.data.PreVenta.OnHideOrShowElement(data, event); }
      self.data.PreVenta.DetallesComprobanteVenta([]);
      self.data.PreVenta.IndicadorEstadoPreVenta("");
      self.data.PreVenta.Total('0.00');
      sizeProductDetails();

      if (!mesaseleccionado.IdMesa) {
        alertify.alert("AVISO!","Debe seleccionar una mesa.",function () { });
        return false;
      }

      $("#loader").show();
      self.ConsultarComandas(mesaseleccionado, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          self.data.Comandas([]);
          ko.utils.arrayForEach($data, function (item) {
            self.data.Comandas.push(new VistaModeloComandas(item,data));
          });
          var $data = $(event.target).data("preventa");
          $(".consulta-preventas ."+$data).show();

        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
        }
      })
    }
  }

  self.ObtenerPreCuentas = function (data, event) {
    if (event) {
      if ($(".detail-voucher").is(":visible")) { self.data.PreVenta.OnHideOrShowElement(data, event); }
      self.data.PreVenta.IndicadorEstadoPreVenta("");
      self.data.PreVenta.DetallesComprobanteVenta([]);
      self.data.PreVenta.Total('0.00');
      sizeProductDetails();

      if (!mesaseleccionado.IdMesa) {
        alertify.alert("AVISO!","Debe seleccionar una mesa.",function () { });
        return false;
      }

      $("#loader").show();
      self.ConsultarPreCuentas(mesaseleccionado, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          self.data.PreCuentas([]);
          ko.utils.arrayForEach($data, function (item) {
            self.data.PreCuentas.push(new VistaModeloPreCuentas(item,data));
          });
          var $data = $(event.target).data("preventa");
          $(".consulta-preventas ."+$data).show();

        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
        }
      })
    }
  }

}
