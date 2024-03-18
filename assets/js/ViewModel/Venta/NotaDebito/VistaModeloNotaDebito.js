
VistaModeloNotaDebito = function (data, options) {

  var self = this;
  var ClienteValido = false;
  var CopiaFiltros = null;
  ko.mapping.fromJS(data, MappingVenta, self);
  self.CheckNumeroDocumento = ko.observable(true);
  self.IndicadorReseteoFormulario = true;
  self.NotaUsuario = ko.observable("");
  self.Options = options;
  self.CopiaNotaDebito = ko.observable([]);
  self.CopiaMotivos = ko.observable(self.MotivosNotaDebito());

  ModeloComprobanteVenta.call(this, self);
  ModeloNotaDebito.call(this, self);

  var $form = $(options.IDForm);
  var $header = $(options.IDPanelHeader);
  var $buscador = $(options.IDModalBusqueda);

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.InicializarModelo(event);
      CopiaFiltros = ko.mapping.toJS(ViewModels.data.FiltrosND);
      console.log(CopiaFiltros);

      var target = options.IDForm + " " + "#Cliente";

      $form.find("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, target);
      $form.find("#nletras").autoDenominacionMoneda(self.Total());
      $form.find("#FechaEmision").inputmask({ "mask": "99/99/9999" });
      $form.find("#FechaVencimiento").inputmask({ "mask": "99/99/9999" });

      $form.find("#FechaIngreso").inputmask({ "mask": "99/99/9999" });

      $buscador.find("#fecha-inicio").inputmask({ "mask": "99/99/9999" });
      $buscador.find("#fecha-fin").inputmask({ "mask": "99/99/9999" });

      $form.find("#Cliente").on("focusout", function (event) {
        self.ValidarCliente(undefined, event);
      });

      self.InicializarValidator(event);

      self.CambiarSerieDocumento(window);
    }
  }

  self.InicializarValidator = function (event) {
    if (event) {

      $.formUtils.addValidator({
        name: 'autocompletado_cliente',
        validatorFunction: function (value, $el, config, language, $form) {
          var texto = $el.attr("data-validation-text-found");
          var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
          return resultado;
        },
        errorMessageKey: 'badautocompletado_cliente'
      });

      $.formUtils.addValidator({
        name: 'fecha_vencimiento',
        validatorFunction: function (value, $el, config, language, $form) {
          if (value !== "") {
            var dateFormat = $el.valAttr('format') || config.dateFormat || 'yyyy-mm-dd';
            var addMissingLeadingZeros = $el.valAttr('require-leading-zero') === 'false';
            return $.formUtils.parseDate(value, dateFormat, addMissingLeadingZeros) !== false;
          }
          else {
            if (self.IdFormaPago() === ID_FORMA_PAGO_CREDITO)
              return false;
            else
              return true;
          }
        }
      });

      $.formUtils.addValidator({
        name: 'validacion_producto',
        validatorFunction: function (value, $el, config, language, $form) {
          var texto = $el.attr("data-validation-found");
          var resultado = ("true" === texto) ? true : false;
          return resultado;
        },
        errorMessageKey: 'badvalidacion_producto'
      });

      $.formUtils.addValidator({
        name: 'autocompletado_producto',
        validatorFunction: function (value, $el, config, language, $form) {
          var $referencia = $("#" + $el.attr("data-validation-reference"));
          var texto = $referencia.attr("data-validation-text-found").toUpperCase();
          var resultado = (value.toUpperCase() === texto && value.toUpperCase() !== "") ? true : false;
          return resultado;
        },
        errorMessageKey: 'badautocompletado_producto'
      });

    }
  }

  self.InicializarVistaModeloDetalle = function (data, event) {
    if (event) {
      var item;

      if (self.DetallesNotaDebito().length > 0) {
        ko.utils.arrayForEach(self.DetallesNotaDebito(), function (el) {
          el.InicializarVistaModelo(event);
        });
      }

      var item = self.DetallesNotaDebito.AgregarDetalle(undefined, event);
      item.InicializarVistaModelo(event);
      $(item.InputOpcion()).hide();

    }
  }

  self.ValidarCliente = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdCliente(null);
          self.Direccion("");
        }
      });
    }
  }

  self.ValidarAutoCompletadoCliente = function (data, event) {
    if (event) {

      if (data === -1) {
        if ($form.find("#Cliente").attr("data-validation-text-found") === $form.find("#Cliente").val()) {
          var $evento = { target: "#Cliente" };
          self.ValidarCliente(data, $evento);
        }
        else {
          $form.find("#Cliente").attr("data-validation-text-found", "");
          var $evento = { target: "#Cliente" };
          self.ValidarCliente(data, $evento);
        }
        $form.find("#btn_buscardocumentoreferencia").focus();
      }
      else {
        if ($form.find("#Cliente").attr("data-validation-text-found") !== $form.find("#Cliente").val()) {
          $form.find("#Cliente").attr("data-validation-text-found", data.NumeroDocumentoIdentidad + " - " + data.RazonSocial);
        }

        var $evento = { target: "#Cliente" };
        self.ValidarCliente(data, $evento);
        //var $data = { IdPersona : }
        data.IdCliente = data.IdPersona;
        ko.mapping.fromJS(data, MappingVenta, self);

        if (self.DireccionesCliente().length > 0) {
          var direccionclientedefecto = self.DireccionesCliente()[0].Direccion();
          self.Direccion(direccionclientedefecto);
        }
        else {
          self.Direccion("");
        }

        $form.find("#btn_buscardocumentoreferencia").focus();

      }
    }
  }

  self.OnChangeTipoVenta = function (data, event) {
    if (event) {
      if (data.TipoVenta() == TIPO_VENTA.MERCADERIAS) {
        $form.find(".op-codigo").removeClass("ocultar");
        $form.find(".op-mercaderia").removeClass("ocultar");
        $form.find(".op-unidad").removeClass("ocultar");
      }
      else if (data.TipoVenta() == TIPO_VENTA.SERVICIOS) {
        $form.find(".op-codigo").removeClass("ocultar");
        $form.find(".op-mercaderia").addClass("ocultar");
        $form.find(".op-unidad").addClass("ocultar");
      }

      else if (data.TipoVenta() == TIPO_VENTA.ACTIVOS) {
        $form.find(".op-codigo").removeClass("ocultar");
        $form.find(".op-mercaderia").addClass("ocultar");
        $form.find(".op-unidad").addClass("ocultar");
      }
      else if (data.TipoVenta() == TIPO_VENTA.OTRASVENTAS) {
        $form.find(".op-codigo").addClass("ocultar");
        $form.find(".op-mercaderia").addClass("ocultar");
        $form.find(".op-unidad").addClass("ocultar");
      }

      if (self.opcionProceso() == opcionProceso.Nuevo) {
        self.Limpiar(data, event);
      }

      self.IdTipoVenta(data.TipoVenta());
    }
  }

  self.OnClickTipoVenta = function (data, event) {
    if (event) {
      if (self.CambiosFormulario() == true) {
        alertify.confirm(self.titulo, "Se perderá el registro actual, ¿Desea cambiar de todas formas?", function () {
          $(event.target).prop("checked", true);
          self.OnChangeTipoVenta(data, event);
        }, function () {

        });
      }
      else {
        return true;
      }
    }
  }

  self.OnChooseTipoCambio = function (data, event) {
    if (event) {
      var tecla = event.keyCode;
      if (tecla == TECLA_ENTER) {
        if (self.IdMoneda() != ID_MONEDA_SOLES)
          self.TipoCambio.ObtenerTipoCambio(data, self.PostBusquedaTipoCambio);
        else
          self.ValorTipoCambio("");
      }
    }
  }

  self.PostBusquedaTipoCambio = function (data) {
    if (data) {
      self.ValorTipoCambio(data.TipoCambioVenta);
    }
    else {
      self.ValorTipoCambio("");
      alertify.alert("No se encontro un tipo de cambio para la fecha emision");
    }
  }

  self.OnChangeFormaPago = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-formapago option:selected").text();
      data.NombreFormaPago(texto);
    }
  }

  self.OnChangeSerieDocumento = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-seriedocumento option:selected").text();
      self.CambiarMotivoNotaDebito(event);
      data.SerieDocumento(texto);
      self.CambiarSerieDocumento(event);
    }
  }

  self.CambiarSerieDocumento = function (event) {
    if (event) {
      var texto = $form.find("#combo-seriedocumento option:selected").text();
      if (texto.search(CODIGO_SERIE_BOLETA) >= 0) {
        ViewModels.data.FiltrosND.IdTipoDocumento(ID_TIPO_DOCUMENTO_BOLETA);
        $form.find("#combo-tipodocumento").val(ID_TIPO_DOCUMENTO_BOLETA)
      }
      else {
        ViewModels.data.FiltrosND.IdTipoDocumento(ID_TIPO_DOCUMENTO_FACTURA);
        $form.find("#combo-tipodocumento").val(ID_TIPO_DOCUMENTO_FACTURA)
      }
    }
  }

  self.OnChangeMoneda = function (data, event) {
    if (event) {
      var texto = $header.find("#combo-moneda option:selected").text();
      data.NombreMoneda(texto);
    }
  }

  self.PostBusquedaProducto = function (data, event, $callback) {
    if (event) {
      if (data != null) {
        // var item = self.DetallesNotaDebito.ReemplazarDetalle(data,event);
        //item.InicializarVistaModelo(event,self.PostBusquedaProducto);
        setTimeout(function () {
          self.Seleccionar(data, event);
        }, 250);

        // $form.find("#nletras").autoDenominacionMoneda(self.Total());
      }
      if ($callback) $callback(data, event);
    }
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      var id = "#" + data.IdDetalleComprobanteVenta();
      $(id).addClass('active').siblings().removeClass('active');
      // self.SeleccionarDetalleNotaDebito(data,event);
    }
  }

  self.Deshacer = function (data, event) {
    if (event) {
      self.Editar(self.CopiaNotaDebito, event, self.callback);
    }
  }

  self.Editar = function (data, event, callback, blocked) {
    if (event) {
      var objetoData = ko.mapping.toJS(data);
      if (self.IndicadorReseteoFormulario === true) $form.resetearValidaciones();//'#formComprobanteVenta'
      if (callback) self.callback = callback;
      // self.EditarNotaDebito(data,event);
      self.InicializarVistaModelo(undefined, event);

      // $form.find("#Cliente").attr("data-validation-text-found",self.NumeroDocumentoIdentidad() +" - "+ self.RazonSocial());

      $('#loader').show();
      self.ConsultarDocumentosReferencia(data, event, function ($dataref, $eventref) {

        self.ConsultarDetallesComprobanteVenta(data, event, function ($data, $event) {
          // self.InicializarVistaModeloDetalle(undefined,event);

          if (self.DetallesNotaDebito().length > 0) {
            ko.utils.arrayForEach(self.DetallesNotaDebito(), function (item) {
              $(item.InputCodigoMercaderia()).attr("data-validation-found", "true");
              $(item.InputCodigoMercaderia()).attr("data-validation-text-found", $(item.InputProducto()).val());
            })
          }

          $('#loader').hide();
          setTimeout(function () {
            $form.find('#combo-seriedocumento').focus();
          }, 350);

          $(self.Options.IDPanelHeader).bloquearSelector(blocked);//'#panelheaderComprobanteVenta'
          // $(options.IDPanelHeader).bloquearSelector(blocked);//'#panelheaderComprobanteVenta'
          $form.bloquearSelector(blocked);//'#formComprobanteVenta'
          $form.find('#btn_Cerrar').removeAttr('disabled');

          // var idmotivo = $form.find("#combo-motivo").val();
          var idmotivo = objetoData.IdMotivoNotaDebito;
          self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
          self.EditarNotaDebito(data, event);

          self.TipoVenta(self.IdTipoVenta());
          self.OnChangeTipoVenta(self, event);

          $form.find("#Cliente").attr("data-validation-text-found", self.NumeroDocumentoIdentidad() + " - " + self.RazonSocial());

          self.CopiaNotaDebito = data;
        });

      });

    }
  }

  self.Limpiar = function (data, event) {
    if (event) {
      var copia_data = ko.mapping.toJS(data);
      var comprobante_nuevo = ko.mapping.toJS(self.ComprobanteVentaInicial);
      comprobante_nuevo.IdTipoVenta = copia_data.IdTipoVenta;
      self.DetallesNotaDebito([]);
      self.Nuevo(comprobante_nuevo, event, self.callback);
      self.DocumentosReferencia([]);
      var idmotivo = $form.find("#combo-motivo").val();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
      $form.find("#Cliente").val("");
      // self.LimpiarPorConcepto(event);
      self.Total("0.00");
      self.ValorVentaGravado("0.00");

      self.MotivosNotaDebito([]);
      ko.utils.arrayForEach(self.CopiaMotivos(), function (entry) {
        var ids = entry.AfectacionVenta();
        var res = ids.split(",");
        if (res.indexOf(self.TipoVenta()) >= 0) {
          self.MotivosNotaDebito.push(entry);
        }
      });
    }
  }

  self.OnVer = function (data, event, callback) {
    if (event) {
      self.Editar(data, event, callback, true);
    }
  }

  self.Nuevo = function (data, event, callback) {
    if (event) {
      $form.resetearValidaciones();
      if (callback != undefined) self.callback = callback;
      self.NuevoComprobanteVenta(data, event);
      self.InicializarVistaModelo(undefined, event);
      self.InicializarVistaModeloDetalle(undefined, event);
      setTimeout(function () {
        $form.find('#combo-seriedocumento').trigger("focus");
      }, 300);
    }
  }

  self.Guardar = function (data, event) {
    if (event) {
      if ($("#loader").is(":visible")) {
        // console.log("PETICIONES MULTIPLES PARADAS");
        return false;
      }

      var numero_facturas = self.MiniComprobantesVentaND().length;
      if (numero_facturas <= 0) {
        alertify.alert("VALIDACION", "Por Favor, Ingrese al ingrese un comprobante de referencia para proceder.");
        return false;
      }

      self.AplicarExcepcionValidaciones(data, event);

      if (!$form.isValid()) {//lang, conf, false
        //displayErrors( errors );
      }
      else {
        if (parseFloatAvanzado(self.Total()) <= 0) {
          alertify.alert("VALIDACION", "El total debe ser mayor a cero.");
          return false;
        }
        alertify.confirm("Emision de Nota Debito", "¿Desea guardar los cambios?", function () {
          $("#loader").show();

          if (window.Motivo.Reglas.BorrarDetalles == 1) {
            self.DetallesNotaDebito([]);
          }

          self.GuardarNotaDebito(event, self.PostGuardar);
        }, function () {

        });
      }
    }
  }

  self.PostGuardar = function (data, event) {
    if (event) {
      if (data.error) {
        $("#loader").hide();
        alertify.alert("Error en " + self.titulo, data.error.msg, function () {
        });
      }
      else {
        $("#loader").hide();
        if (self.opcionProceso() == opcionProceso.Nuevo) {
          alertify.alert(self.titulo, self.mensaje, function () {
            self.Limpiar(data, event);
            if (self.ParametroEnvioEmail() == 1) {
              self.EnviarEmailCliente(data, event);
            }
            setTimeout(function () {
              $form.find("#combo-seriedocumento").focus();
            }, 400);
            alertify.alert().destroy();
          });
        }
        else {
          var mensaje = "Se actualizó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.";
          alertify.alert("Edicion de Nota Debito", mensaje, function () {
            if (self.ParametroEnvioEmail() == 1) {
              self.EnviarEmailCliente(data, event);
            }
            setTimeout(function () {
              $(options.IDModalComprobanteVenta).modal("hide");
            }, 400);
            if (self.callback) self.callback(data, event);
          });
        }

      }
    }
  }

  self.CalculoTotalDescuentoGlobal = ko.computed(function () {
    var resultado = accounting.formatNumber(self.DescuentoGlobal(), NUMERO_DECIMALES_VENTA);
    return resultado;
  }, this);

  self.CalculoTotalIGV = ko.computed(function () {
    var resultado = accounting.formatNumber(self.IGV(), NUMERO_DECIMALES_VENTA);
    return resultado;
  }, this);

  self.CalculoTotalVenta = ko.computed(function () {
    var resultado = accounting.formatNumber(self.Total(), NUMERO_DECIMALES_VENTA);
    return resultado;
  }, this);

  self.DenominacionTotal = ko.computed(function () {

    var resultado = self.Total();

    if (resultado != null) {
      if (self.IdMoneda() == ID_MONEDA_SOLES)
        self.MontoLetra(Number(resultado).DenominacionMonedaSOLES());
      else
        self.MontoLetra(Number(resultado).DenominacionMonedaDOLARES());
    }
    else {
      self.MontoLetra("");
    }

    return self.MontoLetra();
  }, this);

  self.TieneAccesoEditar = ko.observable(self.ValidarEstadoComprobanteVenta(self, window));
  self.TieneAccesoAnular = ko.observable(self.ValidarEstadoComprobanteVenta(self, window));

  self.OnChangeCheckNumeroDocumento = function (data, event) {
    if (event) {
      if ($form.find("#CheckNumeroDocumento").prop("checked")) {
        $form.find("#NumeroDocumento").attr("readonly", false);
        $form.find("#NumeroDocumento").removeClass("no-tab");
        $form.find("#NumeroDocumento").attr("data-validation-optional", "false");
        $form.find("#NumeroDocumento").focus();
      }
      else {
        self.NumeroDocumento("");
        $form.find("#NumeroDocumento").attr("data-validation-optional", "true");
        $form.find("#NumeroDocumento").attr("readonly", true);
        $form.find("#NumeroDocumento").addClass("no-tab");
        $form.find("#NumeroDocumento").focus();
        $form.find("#CheckNumeroDocumento").focus();
      }

      self.NumeroDocumento("");
    }
  }

  self.QuitarDetalleNotaDebito = function (data, event) {
    if (event) {
      var tr = $(data.InputProducto()).closest("tr");
      // var IdDetalleComprobanteVenta = tr.next().attr("id");
      var IdDetalleComprobanteVenta = tr[0].id;
      var itemsgte = self.DetallesNotaDebito.Obtener(IdDetalleComprobanteVenta, event);
      setTimeout(function () {
        self.Seleccionar(itemsgte, event);
      }, 250);
      self.DetallesNotaDebito.RemoverDetalle(data, event);
      self.ActualizarTotales(data, event);
    }
  }

  self.ValidarNumeroDocumento = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        //console.log('Element '+elem.name+' is '+( valid ? 'valid' : 'invalid'));
      });
      data.NumeroDocumento($(event.target).zFill(data.NumeroDocumento(), 8));

    }
  }

  self.ValidarFechaEmision = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        //console.log('Element '+elem.name+' is '+( valid ? 'valid' : 'invalid'));
        if (valid) self.ValorTipoCambio(self.CalcularTipoCambio(data, event));// self.SetearTipoCambio(data,event);
      });
    }
  }


  self.CalcularTipoCambio = function (data, event) {
    if (event) {
      var resultado = 0.00;
      if (self.IdMoneda() != ID_MONEDA_SOLES)
        self.TipoCambio.ObtenerTipoCambio(data, function ($data) {
          if ($data)
            resultado = data.TipoCambioVenta;
          else
            alertify.alert("No se encontro un tipo de cambio para la fecha emision");
        });
      return resultado;
    }
  }

  self.AplicarExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesNotaDebito().length;
      var ultimoItem = self.DetallesNotaDebito()[total - 1];
      var resultado = "false";
      if ($form.find("#tablaDetalleNotaDebito").is(":visible")) {
        if (ultimoItem.CodigoMercaderia() == "" && ultimoItem.NombreProducto() == ""
          && (ultimoItem.Cantidad() == "" || ultimoItem.Cantidad() == "0")
          && (ultimoItem.PrecioUnitario() == "" || ultimoItem.PrecioUnitario() == "0")
          && (ultimoItem.DescuentoItem() == "" || ultimoItem.DescuentoItem() == "0")
        ) {
          resultado = "true";
        }

        $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputPrecioUnitario()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputDescuentoItem()).attr("data-validation-optional", resultado);
      }
      else if ($form.find("#tablaConceptoComprobanteVenta").is(":visible")) {
        if ((data.Porcentaje() == "" || data.Porcentaje() == "0")
          && (data.Importe() == "" || data.Importe() == "0")
        ) {
          resultado = "true";
        }
      }

      //console.log(ultimoItem);

    }
  }

  self.OnKeyEnter = function (data, event) {
    var resultado = $(event.target).enterToTab(event);
    return resultado;
  }

  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }
  }

  self.ValidarPorcentaje = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.OnEditar = function (data, event, callback) {
    if (event) {
      self.Editar(data, event, callback, false);
    }
  }

  self.ValidarImporte = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.QuitarDetalleComprobanteVenta = function (data, event) {
    if (event) {
      var tr = $(data.InputProducto()).closest("tr");
      // var IdDetalleComprobanteVenta = tr.next().attr("id");
      var IdDetalleComprobanteVenta = tr[0].id;
      var itemsgte = self.DetallesNotaDebito.Obtener(IdDetalleComprobanteVenta, event);
      setTimeout(function () {
        self.Seleccionar(itemsgte, event);
      }, 250);
      self.DetallesNotaDebito.RemoverDetalle(data, event);
      self.ActualizarTotales(data, event);
    }
  }

  //NUEVAS FUNCIOENS
  self.LimpiarDetalleConcepto = function (event) {
    if (event) {
      self.Porcentaje("0.00");
      self.Importe("0.00");
      self.SaldoNotaCredito("0.00");
    }
  }

  //NUEVAS FUNCIONES
  self.AbrirConsultaComprobanteVenta = function (data, event) {
    if (event) {
      var _idcliente = $form.find("#IdCliente").val();
      if (_idcliente != "") {
        var filas_mcv = self.MiniComprobantesVentaND().length;
        var cant_items = window.Motivo.Reglas.CantidadFacturas;
        if (filas_mcv > 0 && cant_items == 0) {
          alertify.alert("Usted tiene un Comprobante de Venta en Proceso. Elimine la factura y vuelva a buscar.");
          return false;
        }

        // var tipo_documento = $form.find("#combo-tipodocumento").val();
        var tipo_documento = ViewModels.data.FiltrosND.IdTipoDocumento();
        if (filas_mcv > 0) {
          if (self.MiniComprobantesVentaND()[0].IdTipoDocumento() != tipo_documento) {
            alertify.alert("Usted tiene añadido un Comprobante de Venta de un Tipo. Elimine y vuelva a consultar.");
            return false;
          }
        }

        ViewModels.data.FiltrosND.IdPersona(self.IdPersona());
        // ViewModels.data.FiltrosND.IdTipoDocumento($form.find("#combo-tipodocumento").val());
        ViewModels.data.FiltrosND.IdTipoVenta(self.TipoVenta());
        ViewModels.data.FiltrosND.IdMoneda($header.find("#combo-moneda").val());
        ViewModels.data.FiltrosND.FechaInicio(ViewModels.data.FiltrosND.FechaHoy());
        ViewModels.data.FiltrosND.FechaFin(ViewModels.data.FiltrosND.FechaHoy());
        var _data = ko.mapping.toJS(ViewModels.data.FiltrosND, mappingIgnore);
        ViewModels.data.FiltrosND.ConsultarComprobantesVentaPorCliente(_data, event, ViewModels.data.FiltrosND.PostConsultar);
        self.BusquedaComprobanteVentaND([]);
        $buscador.find("#btn_AgregarComprobantesVenta").prop("disabled", true);
        $buscador.modal('show');
        setTimeout(function () {
          $buscador.find("#input-text-filtro-mercaderia").focus();
        }, 500);
      }
      else {
        alertify.alert("Por favor, busque un cliente.", function () {
          setTimeout(function () {
            $form.find("#Cliente").focus();
          }, 500);
        });
      }
    }
  }

  self.AgregarComprobantesVentaPorCliente = function (data, event) {
    if (event) {
      var objeto = Knockout.CopiarObjeto(self.BusquedaComprobanteVentaND);
      var i = 0;
      // self.DetallesNotaDebito([]);
      //ELMINAMOS DetalleNotaDebito
      self.DetallesNotaDebito([]);

      ko.utils.arrayFirst(objeto(), function (item) {
        var data_items = ko.mapping.toJS(item);

        self.AliasUsuarioVenta(item.AliasUsuarioVenta());
        self.MiniComprobantesVentaND.push(new MiniComprobantesVentaNDModel(data_items));

        ko.utils.arrayFirst(item.DetallesComprobanteVenta(), function (item2) {
          var data_item = ko.mapping.toJS(item2, { ignore: ["SaldoPendienteNotaCredito"] });
          data_item.IdReferenciaDCV = data_item.IdDetalleComprobanteVenta;

          if (window.Motivo.Reglas.IniciarCampoDetalle.length > 0) {
            window.Motivo.Reglas.IniciarCampoDetalle.forEach(function (elemento) {
              var nombre_elemento = elemento.Id;
              delete data_item[nombre_elemento];
              data_item[nombre_elemento] = elemento.Value;
            });
          }
          console.log(data_item);

          var _objeto = self.DetallesNotaDebito.AgregarDetalleNotaDebito(data_item, event);
          // var _objeto = new VistaModeloDetalleNotaDebito(data_item);
          // self.DetallesNotaDebito.push(_objeto);
          _objeto.InicializarVistaModelo(event, self.PostBusquedaProducto);
          // self.DetallesNotaDebito.Agregar(item2, event);

        });
        i++;
      });

      if (window.Motivo.Reglas.CantidadFacturas != 1 && window.Motivo.Reglas.MontoCero == 0) {
        var comprobante = ko.mapping.toJS(objeto()[0], ignoreNotaDebito);
        var includesList = Object.keys(mapeadoNotaDebito);
        var nuevadata = leaveJustIncludedProperties(comprobante, includesList);

        ko.mapping.fromJS(nuevadata, self);
        // console.log(comprobante);
        // self.CalcularTotales(event);
      }
      else {
        var comprobante = ko.mapping.toJS(self.MiniComprobantesVentaND()[0], ignoreNotaDebito);
        var includesList = Object.keys(mapeoPropiedadesNotaDebito);
        var nuevadata = leaveJustIncludedProperties(comprobante, includesList);

        ko.mapping.fromJS(nuevadata, self);
      }

      self.SumarComprobantesElegidos(event);

      self.BusquedaComprobanteVentaND([]);
      self.BusquedaComprobantesVentaND([]);
      $buscador.resetearValidaciones();
      $buscador.modal("hide");

      //HABILITAMOS Y DESHABILITAMOS SEGUN MOTIVO
      self.HabilitarCampos(window.Motivo, event);
      self.LimpiarPorConcepto(event);

      //HABILITAMOS DEL PRIMER CAMPO DE LA TABLA seleccionada
      if ($form.find("#tablaDetalleNotaDebito").is(":visible")) {

        if ($form.find("#tablaDetalleNotaDebito").find("select:not([disabled]), input:not([disabled])").length > 0) {
          $form.find("#tablaDetalleNotaDebito").find("select:not([disabled]), input:not([disabled])")[0].focus();
        }
        else {
          if ($form.find("#footer_notadebito").find("select:not([disabled]), input:not([disabled])").length > 0) {
            $form.find("#footer_notadebito").find("select:not([disabled]), input:not([disabled])")[0].focus();
          }
          else {
            $form.find("#btn_Grabar").focus();
          }
        }
      }
      else if ($form.find("#tablaConceptoComprobanteVenta").is(":visible")) {
        if ($form.find("#tablaConceptoComprobanteVenta").find("select:not([disabled]), input:not([disabled])").length > 0) {
          $form.find("#tablaConceptoComprobanteVenta").find("select:not([disabled]), input:not([disabled])")[0].focus();
        }
        else {
          if ($form.find("#footer_notadebito").find("select:not([disabled]), input:not([disabled])").length > 0) {
            $form.find("#footer_notadebito").find("select:not([disabled]), input:not([disabled])")[0].focus();
          }
          else {
            $form.find("#btn_Grabar").focus();
          }
        }
      }
      //self.CalcularTotales(event);
      // if(self.MiniComprobantesVentaND().length>0 && window.Motivo.Reglas.BorrarDetalles == 1)
      // {
      //   self.CalcularPorcentaje(data, event);
      // }
    }
  }

  self.LimpiarPorConcepto = function (data, event) {
    if (event) {
      //LIMPIAMOS LA PARTE FOOTER DE LA NOTA NotaDebito

      self.Porcentaje("0.00");
      self.Importe("0.00");
      self.Total("0.00");
      self.IGV("0.00");
      self.ValorVentaGravado("0.00");
      self.ValorVentaNoGravado("0.00");
      self.ValorVentaInafecto("0.00");
    }
  }

  self.SumarComprobantesElegidos = function (event) {
    if (event) {
      var total = 0.00;
      var row_comprobantes = self.MiniComprobantesVentaND().length;
      if (row_comprobantes > 0) {
        ko.utils.arrayFirst(self.MiniComprobantesVentaND(), function (item2) {
          // item2.IdReferencia = item2.IdDetalleComprobanteVenta;
          // var _total = parseFloatAvanzado(item2.SaldoNotaCredito());
          var _total = parseFloatAvanzado(item2.SaldoNotaCredito());
          total += _total;
        });
      }

      self.TotalSaldo(total);
    }
  }

  self.CambiarMotivoNotaDebito = function (event) {
    if (event) {
      self.DetallesNotaDebito([]);
      self.InicializarVistaModeloDetalle(undefined, event);


      self.LimpiarDetalleConcepto(event);

      self.MiniComprobantesVentaND([]);
      self.CalcularTotales(event);
      self.SumarComprobantesElegidos(event);

      var idmotivo = $form.find("#combo-motivo").val();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
    }
  }

  self.CambiarFiltro = function (item, callback, event) {
    window.DataMotivosNotaDebito.forEach(function (elemento) {
      if (item == elemento.Data.IdMotivoNotaDebito) {
        window.Motivo.Data = elemento.Data;
        window.Motivo.Reglas = elemento.Reglas;
      }
    });
    console.log(window.Motivo);
    callback(window.Motivo, event);
  };

  self.CorrerReglas = function (motivo, event) {
    if (event) {
      var mostrar_concepto_detalle = motivo.Reglas.MotivoDetalle;
      var mostrar_nota = motivo.Reglas.MostrarNota;
      var actualizarconceptos = motivo.Reglas.ActualizarConceptos;
      if (mostrar_concepto_detalle == 1) {
        // self.Concepto(motivo.Data.NombreMotivoNotaDebito);
        // self.Concepto(motivo.Reglas.IdConcepto);
        $form.find(".vista_detallesComprobanteVenta").hide();
        $form.find(".vista_concepto").show();
      }
      else {
        $form.find(".vista_detallesComprobanteVenta").show();
        $form.find(".vista_concepto").hide();
      }

      self.NotaUsuario(motivo.Reglas.MensajeNota);
      if (mostrar_nota == 1) {
        $form.find(".mostrarnota").show();
      }
      else {
        $form.find(".mostrarnota").hide();
      }

      self.HabilitarCampos(motivo, event);
      // self.RestringuirFecha(motivo, event)
      if (actualizarconceptos == 1) {
        var nueva_data = {};
        // nueva_data.IdMotivoNotaDebito = self.IdMotivoNotaDebito();
        nueva_data.IdMotivoNotaDebito = motivo.Data.IdMotivoNotaDebito;
        self.ActualizarConceptos(nueva_data, event, self.PostActualizarConceptos);
      }
    }
  }

  self.PostActualizarConceptos = function (data, event) {
    if (event) {
      self.ConceptosNotaDebito([]);
      data.forEach(function (item) {
        self.ConceptosNotaDebito.push(item);
      });
    }
  }

  self.HabilitarCampos = function (motivo, event) {
    if (event) {
      self.OcultarCampos(motivo, event);

      $form.find(".NotaDebito_Todos").prop("disabled", true);
      $form.find(".NotaDebito_Todos").closest("button").hide();
      //SE AGREGA CLASE no-tab
      $form.find(".NotaDebito_Todos").addClass("no-tab");
      if (motivo.Reglas.CamposEditables.length > 0) {
        motivo.Reglas.CamposEditables.forEach(function (item) {
          window.CamposNotaDebito.forEach(function (item2) {
            var id = "." + item2.Clase;
            if (item == item2.IdCampo) {
              if (item2.Tipo == 0) {
                $(id).prop("disabled", false);
                $(id).removeClass("no-tab");
              }
              else {
                $(id).show();
                $(id).prop("disabled", false);
              }
            }

          });
        });
      }

      //Deshabilitamos todos los campos
      //$form.find(".NotaDebito_Todos").prop("disabled", true);
    }
  }

  self.OcultarCampos = function (motivo, event) {
    if (event) {
      if (motivo.Reglas.OcultarCampos.length > 0) {
        $form.find(".NotaDebito_Todos").show();
        motivo.Reglas.OcultarCampos.forEach(function (item) {
          window.CamposNotaDebito.forEach(function (item2) {
            var id = "." + item2.Clase;
            if (item == item2.IdCampo) {
              $(id).hide();
            }
            // else
            // {
            //   $(id).show();
            // }
          });
        });
      }
      else {
        $form.find(".NotaDebito_Todos").show();
      }

      //Deshabilitamos todos los campos
      //$form.find(".NotaDebito_Todos").prop("disabled", true);
    }
  }

  self.ActualizarTotales = function (data, event) {
    if (event) {
      var detalle = ko.mapping.toJS(self.DetallesNotaDebito);
      var importe = 0;
      var igv = 0;
      var gravado = 0;
      var nogravado = 0;
      var inafecto = 0;
      ko.utils.arrayFirst(detalle, function (item) {
        var importe_detalle = parseFloatAvanzado(item.SubTotal);
        if (item.CodigoTipoAfectacionIGV == CODIGO_AFECTACION_IGV_GRAVADO) {
          var igv_fila = importe_detalle * VALOR_IGV;
          importe += importe_detalle;
          igv += igv_fila;
          gravado += (importe_detalle - igv_fila);
        }
        else if (item.CodigoTipoAfectacionIGV == CODIGO_AFECTACION_IGV_EXONERADO) {
          importe += importe_detalle;
          nogravado += importe_detalle;
        }
        else {
          importe += importe_detalle;
          inafecto += importe_detalle;
        }
      });
      self.ValorVentaGravado(gravado.toFixed(NUMERO_DECIMALES_VENTA));
      self.ValorVentaNoGravado(nogravado.toFixed(NUMERO_DECIMALES_VENTA));
      self.ValorVentaInafecto(inafecto.toFixed(NUMERO_DECIMALES_VENTA));
      self.IGV(igv.toFixed(NUMERO_DECIMALES_VENTA));
      self.Total(importe.toFixed(NUMERO_DECIMALES_VENTA));

    }
  }

  self.CalcularTotalesByFooter = function (data, event) {
    if (event) {
      var gravado = parseFloatAvanzado(data.ValorVentaGravado());
      var nogravado = parseFloatAvanzado(data.ValorVentaNoGravado());
      var inafecto = parseFloatAvanzado(data.ValorVentaInafecto());
      var igv = gravado * VALOR_IGV;
      var total_gravado = gravado + igv;
      var total = total_gravado + nogravado + inafecto;

      self.IGV(igv.toFixed(NUMERO_DECIMALES_VENTA));
      self.Total(total.toFixed(NUMERO_DECIMALES_VENTA));
    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $(self.Options.IDModalComprobanteVenta).modal("hide");//"#modalComprobanteVenta"
      // $(options.IDModalNotaCredito).modal("hide");//"#modalComprobanteVenta"
      // if (self.callback) self.callback(self,event);
    }
  }

  self.Show = function (event) {
    if (event) {
      self.showNotaDebito(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showNotaDebito(false);
      self.callback = undefined;
      self.OnClickBtnCerrar(self, event);
    }
  }

  self.EnviarEmailCliente = function (data, event) {
    if (event) {
      var data_objeto = ko.mapping.toJS(data);
      if ((data_objeto.SerieDocumento.search(CODIGO_SERIE_BOLETA) >= 0 && data_objeto.IdTipoDocumento == ID_TIPO_DOCUMENTO_NOTA_DEBITO) || (data_objeto.SerieDocumento.search(CODIGO_SERIE_FACTURA) >= 0 && data_objeto.IdTipoDocumento == ID_TIPO_DOCUMENTO_NOTA_DEBITO)) {
        var rpta = self.ObtenerFilaClienteJSON(data_objeto, event);
        if (rpta != null) {
          if (data_objeto.NombreArchivoComprobante) {
            if (rpta.Email == null || rpta.Email == "") {
              //NONE
            }
            else {
              data_notify = {};
              data_notify.title = "¿Desea Enviar el CPE por Email al Cliente?";
              data_notify.message = "Se enviará el XML y PDF respectivo.";
              LoadNotificacionEmail(data_notify, function (res) {
                if (res) {
                  if (validarEmail(rpta.Email) == false) {
                    var val_data = {
                      title: "<strong>Validación!</strong>",
                      type: "danger",
                      clase: "notify-danger",
                      message: "El email del cliente es invalido."
                    };
                    CargarNotificacionDetallada(val_data);
                    return false;
                  }
                  rpta.NombreArchivo = data_objeto.NombreArchivoComprobante;
                  rpta.IdComprobanteVenta = data_objeto.IdComprobanteVenta;
                  rpta.SerieDocumento = data_objeto.SerieDocumento;
                  rpta.NumeroDocumento = data_objeto.NumeroDocumento;
                  rpta.Total = data_objeto.Total;
                  rpta.NombreAbreviado = data_objeto.NombreAbreviado;
                  rpta.NombreTipoDocumento = data_objeto.NombreTipoDocumento;
                  rpta.IdTipoDocumento = data_objeto.IdTipoDocumento;
                  self.EnviarMail(rpta, event, function ($data, $event) {
                    if (!$data.error) {
                      CargarNotificacionDetallada($data);
                    }
                  });
                }
              });

            }
          }
        }

      }

    }
  }

  self.OnChangeDireccion = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-direcciones option:selected").text();
      var valor = $form.find("#combo-direcciones option:selected").val();
      self.Direccion(texto);
    }
  }

  self.OnChangeMontoRecibido = function (data, event) {
    if (event) {
    }
  }
}
