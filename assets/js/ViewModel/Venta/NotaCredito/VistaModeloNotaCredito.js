//FALTA REAJUSTAR PARA QUE SE PUEDA USAR
VistaModeloNotaCredito = function (data, options) {
  var self = this;
  var ClienteValido = false;
  var CopiaFiltros = null;

  ko.mapping.fromJS(data, MappingVenta, self);
  self.CheckNumeroDocumento = ko.observable(true);
  self.IndicadorReseteoFormulario = true;
  self.NotaUsuario = ko.observable("");
  self.CheckPendiente = ko.observable(false);
  self.Options = options;
  self.CopiaNotaCredito = ko.observable([]);
  self.CopiaMotivos = ko.observable(self.MotivosNotaCredito());

  ModeloComprobanteVenta.call(this, self);
  ModeloNotaCredito.call(this, self);

  self.$form = "";
  self.$header = "";
  self.$buscador = "";

  self.InicializarVistaModelo = function (data, event) {
    if (event) {

      //por nota devolucion
      data = Knockout.CopiarObjeto(data);
      self.IdForm = data.IdTipoDocumento() == ID_TIPO_DOCUMENTO_NOTADEVOLUCION ? options.NV.IDForm : options.NC.IDForm;
      self.$form = data.IdTipoDocumento() == ID_TIPO_DOCUMENTO_NOTADEVOLUCION ? $(options.NV.IDForm) : $(options.NC.IDForm);
      self.$header = data.IdTipoDocumento() == ID_TIPO_DOCUMENTO_NOTADEVOLUCION ? $(options.NV.IDPanelHeader) : $(options.NC.IDPanelHeader);
      self.$buscador = data.IdTipoDocumento() == ID_TIPO_DOCUMENTO_NOTADEVOLUCION ? $(options.NV.IDModalBusqueda) : $(options.NC.IDModalBusqueda);
      self.$modal = data.IdTipoDocumento() == ID_TIPO_DOCUMENTO_NOTADEVOLUCION ? options.NV.IDModalComprobanteVenta : options.NC.IDModalComprobanteVenta;
      self.FiltarTiposDocumentoVenta(data, event);


      self.InicializarModelo(event);
      CopiaFiltros = ko.mapping.toJS(ViewModels.data.FiltrosNC);
      // console.log(CopiaFiltros);
      var target = self.IdForm + " " + "#Cliente";
      self.$form.find("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, target);
      self.$form.find("#nletras").autoDenominacionMoneda(self.Total());
      self.$form.find("#FechaEmision").inputmask({ "mask": "99/99/9999" });
      self.$form.find("#FechaVencimiento").inputmask({ "mask": "99/99/9999" });

      self.$form.find("#FechaIngreso").inputmask({ "mask": "99/99/9999" });

      self.$buscador.find("#fecha-inicio").inputmask({ "mask": "99/99/9999" });
      self.$buscador.find("#fecha-fin").inputmask({ "mask": "99/99/9999" });

      self.IdTipoVenta(self.TipoVenta());

      self.$form.find("#Cliente").on("focusout", function (event) {
        self.ValidarCliente(undefined, event);
      });
      self.CambiarSerieDocumento(window);
      self.InicializarValidator(event);
    }
  }

  self.FiltarTiposDocumentoVenta = function (data, event) {
    if (event) {
      var documentos = ViewModels.data.TiposDocumentoCompleto
      var resultado = data.IdTipoDocumento() == ID_TIPO_DOCUMENTO_NOTADEVOLUCION ? documentos.TiposDocumentoVentaNotaDevolucion() : documentos.TiposDocumentoVenta()

      ViewModels.data.TiposDocumento(resultado);
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

      if (self.DetallesNotaCredito().length > 0) {
        ko.utils.arrayForEach(self.DetallesNotaCredito(), function (el) {
          el.InicializarVistaModelo(event);
        });
      }

      var item = self.DetallesNotaCredito.AgregarDetalle(undefined, event);
      // item.InicializarVistaModelo(event);
      item.InicializarVistaModelo(event, self.PostBusquedaProducto, self.CrearDetalleComprobanteVenta);

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
        if (self.$form.find("#Cliente").attr("data-validation-text-found") === self.$form.find("#Cliente").val()) {
          var $evento = { target: "#Cliente" };
          self.ValidarCliente(data, $evento);
        }
        else {
          self.$form.find("#Cliente").attr("data-validation-text-found", "");
          var $evento = { target: "#Cliente" };
          self.ValidarCliente(data, $evento);
        }
        if (self.$form.find(".panel-almacen").is(":visible")) {
          self.$form.find("#combo-sede").focus();
        }
        else {
          self.$form.find("#btn_buscardocumentoreferencia").focus();
        }
      }
      else {
        if (self.$form.find("#Cliente").attr("data-validation-text-found") !== self.$form.find("#Cliente").val()) {
          self.$form.find("#Cliente").attr("data-validation-text-found", data.NumeroDocumentoIdentidad + " - " + data.RazonSocial);
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

        if (self.$form.find(".panel-almacen").is(":visible")) {
          self.$form.find("#combo-sede").focus();
        }
        else {
          self.$form.find("#btn_buscardocumentoreferencia").focus();
        }



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
      var texto = self.$form.find("#combo-formapago option:selected").text();
      data.NombreFormaPago(texto);
    }
  }

  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }
  }

  self.OnChangeTipoVenta = function (data, event) {
    if (event) {
      if (data.TipoVenta() == TIPO_VENTA.MERCADERIAS) {
        self.$form.find(".panel-pendientenota").removeClass("ocultar");
        self.$form.find(".op-codigo").removeClass("ocultar");
        self.$form.find(".op-mercaderia").removeClass("ocultar");
        self.$form.find(".op-unidad").removeClass("ocultar");
        self.$form.find(".panel-pendientenota").find(".form-control").removeClass("no-tab");
        self.$form.find(".panel-pendientenota").find(".form-control").removeAttr("tabIndex");
      }
      else if (data.TipoVenta() == TIPO_VENTA.SERVICIOS) {
        self.$form.find(".panel-pendientenota").addClass("ocultar");
        self.$form.find(".op-codigo").removeClass("ocultar");
        self.$form.find(".op-mercaderia").addClass("ocultar");
        self.$form.find(".op-unidad").addClass("ocultar");
        self.$form.find(".panel-pendientenota").find(".form-control").addClass("no-tab");
        self.$form.find(".panel-pendientenota").find(".form-control").prop("tabIndex", "-1");
      }

      else if (data.TipoVenta() == TIPO_VENTA.ACTIVOS) {
        self.$form.find(".panel-pendientenota").addClass("ocultar");
        self.$form.find(".op-codigo").removeClass("ocultar");
        self.$form.find(".op-mercaderia").addClass("ocultar");
        self.$form.find(".op-unidad").addClass("ocultar");
        self.$form.find(".panel-pendientenota").find(".form-control").addClass("no-tab");
        self.$form.find(".panel-pendientenota").find(".form-control").prop("tabIndex", "-1");
      }
      else if (data.TipoVenta() == TIPO_VENTA.OTRASVENTAS) {
        self.$form.find(".panel-pendientenota").addClass("ocultar");
        self.$form.find(".op-codigo").addClass("ocultar");
        self.$form.find(".op-mercaderia").addClass("ocultar");
        self.$form.find(".op-unidad").addClass("ocultar");
        self.$form.find(".panel-pendientenota").find(".form-control").addClass("no-tab");
        self.$form.find(".panel-pendientenota").find(".form-control").prop("tabIndex", "-1");
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

  self.OnChangeFechaEmision = function (data, event) {
    if (event) {
      data.FechaIngreso(data.FechaEmision());
    }
  }

  self.OnChangeSerieDocumento = function (data, event) {
    if (event) {
      var texto = self.$form.find("#combo-seriedocumento option:selected").text();

      self.CambiarMotivoNotaCredito(event);

      data.SerieDocumento(texto);
      self.CambiarSerieDocumento(event);

    }
  }

  self.CambiarSerieDocumento = function (event) {
    if (event) {
      var texto = self.$form.find("#combo-seriedocumento option:selected").text();
      if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_NOTADEVOLUCION) {
        ViewModels.data.FiltrosNC.IdTipoDocumento(ID_TIPO_DOCUMENTO_ORDEN_PEDIDO);
        self.$form.find("#combo-tipodocumento").val(ID_TIPO_DOCUMENTO_ORDEN_PEDIDO)
      } else {
        if (texto.search(CODIGO_SERIE_BOLETA) >= 0) {
          ViewModels.data.FiltrosNC.IdTipoDocumento(ID_TIPO_DOCUMENTO_BOLETA);
          self.$form.find("#combo-tipodocumento").val(ID_TIPO_DOCUMENTO_BOLETA)
        }
        else {
          ViewModels.data.FiltrosNC.IdTipoDocumento(ID_TIPO_DOCUMENTO_FACTURA);
          self.$form.find("#combo-tipodocumento").val(ID_TIPO_DOCUMENTO_FACTURA)
        }
      }
    }
  }

  self.OnChangeMoneda = function (data, event) {
    if (event) {
      var texto = self.$header.find("#combo-moneda option:selected").text();
      data.NombreMoneda(texto);
    }
  }

  self.PostBusquedaProducto = function (data, event, $callback) {
    if (event) {
      if (data != null) {
        // var item = self.DetallesNotaCredito.ReemplazarDetalle(data,event);
        //item.InicializarVistaModelo(event,self.PostBusquedaProducto);
        setTimeout(function () {
          self.Seleccionar(data, event);
        }, 250);

        // self.$form.find("#nletras").autoDenominacionMoneda(self.Total());
      }
      if ($callback) $callback(data, event);
    }
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      var id = "#" + data.IdDetalleComprobanteVenta();
      $(id).addClass('active').siblings().removeClass('active');
      // self.SeleccionarDetalleComprobanteVenta(data,event);
    }
  }

  self.AbrirVistaCliente = function (data, event) {
    if (event) {
      self.Cliente.showCliente(true);
      self.Cliente.Nuevo(self.NuevoCliente, event, self.PostCerrarCliente);
    }
  }

  self.PostCerrarCliente = function (data, event) {
    if (event) {
      self.$form.find("#modalCliente").modal("hide");

      if (self.Cliente.EstaProcesado() == true) {
        self.$form.find("#Cliente").focus();
      }
      else {
        self.$form.find("#combo-formapago").focus();
      }
    }
  }

  self.Deshacer = function (data, event) {
    if (event) {
      self.Editar(self.CopiaNotaCredito, event, self.callback);
    }
  }

  self.Editar = function (data, event, callback, blocked) {
    if (event) {
      var objetoData = ko.mapping.toJS(data);
      self.InicializarVistaModelo(data, event);
      self.EditarNotaCredito(data, event);
      if (self.IndicadorReseteoFormulario === true) self.$form.resetearValidaciones();//'#formComprobanteVenta'
      if (callback) self.callback = callback;


      // self.$form.find("#Cliente").attr("data-validation-text-found",self.NumeroDocumentoIdentidad() +" - "+ self.RazonSocial());

      $('#loader').show();
      self.ConsultarDocumentosReferencia(data, event, function ($dataref, $eventref) {

        self.ConsultarDetallesComprobanteVenta(data, event, function ($data, $event) {
          // self.InicializarVistaModeloDetalle(undefined,event);

          if (self.DetallesNotaCredito().length > 0) {
            ko.utils.arrayForEach(self.DetallesNotaCredito(), function (item) {
              $(item.InputCodigoMercaderia()).attr("data-validation-found", "true");
              $(item.InputCodigoMercaderia()).attr("data-validation-text-found", $(item.InputProducto()).val());
            })
          }

          $('#loader').hide();
          setTimeout(function () {
            self.$form.find('#combo-seriedocumento').focus();
          }, 350);

          $(self.Options.IDPanelHeader).bloquearSelector(blocked);//'#panelheaderComprobanteVenta'
          self.$form.bloquearSelector(blocked);//'#formComprobanteVenta'
          self.$form.find('#btn_Cerrar').removeAttr('disabled');

          var idmotivo = objetoData.IdMotivoNotaCredito;
          self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
          data.DetallesNotaCredito = $data.DetallesNotaCredito();
          self.TipoVenta(self.IdTipoVenta());
          self.OnChangeTipoVenta(self, event);

          self.$form.find("#Cliente").attr("data-validation-text-found", self.NumeroDocumentoIdentidad() + " - " + self.RazonSocial());
          self.$form.find("#Cliente").val(self.NumeroDocumentoIdentidad() + " - " + self.RazonSocial());

          self.SumarComprobantesElegidos(event);
          self.$form.find("#combo-motivo").prop('disabled', true);
          self.Importe(self.Total());
          self.CopiaNotaCredito = data;
          self.ActualizarTotales(data, event)
        });

      });

    }
  }

  self.Limpiar = function (data, event) {
    if (event) {
      var copia_data = ko.mapping.toJS(data);
      var comprobante_nuevo = ko.mapping.toJS(self.ComprobanteVentaInicial);
      comprobante_nuevo.IdTipoVenta = copia_data.IdTipoVenta;
      self.DetallesNotaCredito([]);
      self.Nuevo(comprobante_nuevo, event, self.callback);
      self.DocumentosReferencia([])
      var idmotivo = self.$form.find("#combo-motivo").val();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
      self.$form.find("#Cliente").val("");
      // self.LimpiarPorConcepto(event);
      self.Total("0.00");
      self.ValorVentaGravado("0.00");
      self.EstadoPendienteNota(PENDIENTE_NOTA_NINGUNA);
      self.CheckPendiente(false);

      self.MotivosNotaCredito([]);
      ko.utils.arrayForEach(self.CopiaMotivos(), function (entry) {
        var ids = entry.AfectacionVenta();
        var res = ids.split(",");
        if (res.indexOf(self.TipoVenta()) >= 0) {
          self.MotivosNotaCredito.push(entry);
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
      self.InicializarVistaModelo(data, event);
      //self.NuevoComprobanteVenta(data, event);
      self.NuevoNotaCredito(data, event);
      self.$form.resetearValidaciones();
      if (callback != undefined) self.callback = callback;
      self.InicializarVistaModeloDetalle(undefined, event);

      self.EstadoPendienteNota(PENDIENTE_NOTA_NINGUNA);
      self.CheckPendiente(false);
      setTimeout(function () {
        self.$form.find('#combo-seriedocumento').trigger("focus");
      }, 300);
    }
  }

  self.Guardar = function (data, event) {
    if (event) {
      if ($("#loader").is(":visible")) {
        // console.log("PETICIONES MULTIPLES PARADAS");
        return false;
      }

      var  numero_facturas = self.MiniComprobantesVentaNC().length;
      if (numero_facturas <= 0) {
        alertify.alert("VALIDACION", "Por Favor, Ingrese al menos un comprobante de referencia para proceder.");
        return false;
      }

      self.AplicarExcepcionValidaciones(data, event);

      if (!self.$form.isValid()) {//lang, conf, false
        //displayErrors( errors );
      }
      else {
          var idmotivo = self.$form.find("#combo-motivo").val();
          if(idmotivo !=13 && idmotivo != 3) {
              if (parseFloatAvanzado(self.Total()) <= 0 && window.Motivo.Reglas.TotalCero != 1) {
              
                alertify.alert(self.titulo + " - VALIDACION", "El total debe ser mayor a cero.");
                return false;
              }
          }          

          alertify.confirm(self.titulo, "¿Desea guardar los cambios?", function () {
          $("#loader").show();
          
          // if(idmotivo ==13) {
          //   self.DetallesNotaCredito([]);
          // }
          // else {
          if (window.Motivo.Reglas.BorrarDetalles == 1) {
            self.DetallesNotaCredito([]);
          }          
          //}
          self.GuardarNotaCredito(event, self.PostGuardar);
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
              self.$form.find("#combo-seriedocumento").focus();
            }, 400);
          });
        }
        else {
          var mensaje = "Se actualizó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.";

          alertify.alert(self.titulo, mensaje, function () {
            if (self.ParametroEnvioEmail() == 1) {
              self.EnviarEmailCliente(data, event);
            }
            setTimeout(function () {
              $(self.$modal).modal("hide");
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
      if (self.$form.find("#CheckNumeroDocumento").prop("checked")) {
        self.$form.find("#NumeroDocumento").attr("readonly", false);
        self.$form.find("#NumeroDocumento").removeClass("no-tab");
        self.$form.find("#NumeroDocumento").attr("data-validation-optional", "false");
        self.$form.find("#NumeroDocumento").focus();
      }
      else {
        self.NumeroDocumento("");
        self.$form.find("#NumeroDocumento").attr("data-validation-optional", "true");
        self.$form.find("#NumeroDocumento").attr("readonly", true);
        self.$form.find("#NumeroDocumento").addClass("no-tab");
        self.$form.find("#NumeroDocumento").focus();
        self.$form.find("#CheckNumeroDocumento").focus();
      }
    }
    self.NumeroDocumento("");
  }

  self.QuitarDetalleComprobanteVenta = function (data, event) {
    if (event) {
      var tr = $(data.InputProducto()).closest("tr");
      // var IdDetalleComprobanteVenta = tr.next().attr("id");
      var IdDetalleComprobanteVenta = tr[0].id;
      var itemsgte = self.DetallesNotaCredito.Obtener(IdDetalleComprobanteVenta, event);
      setTimeout(function () {
        self.Seleccionar(itemsgte, event);
      }, 250);
      self.DetallesNotaCredito.RemoverDetalle(data, event);
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
      var total = self.DetallesNotaCredito().length;
      var ultimoItem = self.DetallesNotaCredito()[total - 1];
      var resultado = "false";
      if (self.$form.find("#tablaDetalleComprobanteVenta").is(":visible")) {
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
      else if (self.$form.find("#tablaConceptoComprobanteVenta").is(":visible")) {
        if ((data.Porcentaje() == "" || data.Porcentaje() == "0")
          && (data.Importe() == "" || data.Importe() == "0")
        ) {
          resultado = "true";
        }
      }


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

  self.ValidarImporte = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
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

  self.CalcularPorcentaje = function (data, event) {
    if (event) {

      var num_filas = self.MiniComprobantesVentaNC().length;
      if (num_filas > 0) {
        var primera_fila = self.MiniComprobantesVentaNC()[0];

        var totalsaldo = parseFloatAvanzado(data.TotalSaldo());
        var importe = parseFloatAvanzado(data.Importe());
        var porcentaje = 0;
        var igv = 0;
        var gravado = 0;
        var nogravado = 0;
        if (importe > totalsaldo) {
          importe = 0;
          alertify.alert("El Importe no puede ser menor al Saldo", function () {
            self.$form.find("#input-importe").focus();
          });
        }
        else {
          if (parseFloatAvanzado(primera_fila.ValorVentaGravado()) > 0) {
            porcentaje = (importe / totalsaldo) * 100;
            igv = parseFloatAvanzado(data.Importe()) * 0.18;
            gravado = parseFloatAvanzado(data.Importe()) - igv;
          }
          else {
            porcentaje = (importe / totalsaldo) * 100;
            nogravado = parseFloatAvanzado(data.Importe());
          }
        }

        self.Importe(importe.toFixed(NUMERO_DECIMALES_VENTA));
        self.Porcentaje(porcentaje.toFixed(NUMERO_DECIMALES_VENTA));
        self.Total(importe.toFixed(NUMERO_DECIMALES_VENTA));
        self.IGV(igv.toFixed(NUMERO_DECIMALES_VENTA));
        self.ValorVentaGravado(gravado.toFixed(NUMERO_DECIMALES_VENTA));
        self.ValorVentaNoGravado(nogravado.toFixed(NUMERO_DECIMALES_VENTA));
      }

    }
  }

  self.CalcularTotalByPorcentaje = function (data, event) {
    if (event) {
      var num_filas = self.MiniComprobantesVentaNC().length;

      if (num_filas > 0) {
        var porcentaje = parseFloatAvanzado(data.Porcentaje());
        var importe = 0;
        var igv = 0;
        var gravado = 0;
        var nogravado = 0;

        if (porcentaje > 100) {
          porcentaje = 0;
          alertify.alert("El Importe No puede ser mayor al Saldo", function () {
            self.$form.find("#input-porcentaje").focus();
          });
        }
        else {
          var primera_fila = self.MiniComprobantesVentaNC()[0];

          var porcentaje_decimal = parseFloatAvanzado(data.Porcentaje()) / 100;
          importe = parseFloatAvanzado(data.TotalSaldo()) * porcentaje_decimal;
          if (parseFloatAvanzado(primera_fila.ValorVentaGravado()) > 0) {
            igv = importe * 0.18;
            // porcentaje = (importe / totalsaldo) * 100;
            // igv = parseFloatAvanzado(data.Importe()) * 0.18;
            gravado = importe - igv;
          }
          else {
            nogravado = importe;
          }
        }

        self.Porcentaje(porcentaje.toFixed(NUMERO_DECIMALES_VENTA));
        self.Importe(importe.toFixed(NUMERO_DECIMALES_VENTA));
        self.Total(importe.toFixed(NUMERO_DECIMALES_VENTA));
        self.IGV(igv.toFixed(NUMERO_DECIMALES_VENTA));
        self.ValorVentaGravado(gravado.toFixed(NUMERO_DECIMALES_VENTA));
        self.ValorVentaNoGravado(nogravado.toFixed(NUMERO_DECIMALES_VENTA));
      }

    }
  }

  self.OnEditar = function (data, event, callback) {
    if (event) {
      self.Editar(data, event, callback, false);
    }
  }

  //NUEVAS FUNCIONES
  self.OnChangeAlmacen = function (data, event) {
    if (event) {
      var texto = self.$form.find("#combo-sede option:selected").text();
      data.NombreAlmacen(texto);
    }
  }

  self.OnChangeCheckPendiente = function (data, event) {
    if (event) {
      if (self.CheckPendiente() == true) {
        self.EstadoPendienteNota(PENDIENTE_NOTA_ENTRADA);
        self.$form.find(".panel-almacen").hide();
      }
      else {
        self.EstadoPendienteNota(PENDIENTE_NOTA_NINGUNA);
        self.$form.find(".panel-almacen").show();
      }
    }
  }

  self.AbrirConsultaComprobanteVenta = function (data, event) {
    if (event) {
      var _idcliente = self.$form.find("#IdCliente").val();
      if (_idcliente != "") {
        var filas_mcv = self.MiniComprobantesVentaNC().length;
        var cant_items = window.Motivo.Reglas.CantidadFacturas;
        if (filas_mcv > 0 && cant_items == 0) {
          alertify.alert("Usted tiene un Comprobante de Venta en Proceso. Elimine la factura y vuelva a buscar.");
          return false;
        }

        // var tipo_documento = self.$form.find("#combo-tipodocumento").val();
        var tipo_documento = ViewModels.data.FiltrosNC.IdTipoDocumento();
        if (filas_mcv > 0) {
          if (self.MiniComprobantesVentaNC()[0].IdTipoDocumento() != tipo_documento) {
            alertify.alert("Usted tiene añadido un Comprobante de Venta de un Tipo. Elimine y vuelva a consultar.");
            return false;
          }
        }

        ViewModels.data.FiltrosNC.IdPersona(self.IdPersona());
        // ViewModels.data.FiltrosNC.IdTipoDocumento(self.$form.find("#combo-tipodocumento").val());
        ViewModels.data.FiltrosNC.IdMoneda(self.$header.find("#combo-moneda").val());
        ViewModels.data.FiltrosNC.IdTipoVenta(self.TipoVenta());
        ViewModels.data.FiltrosNC.FechaInicio(ViewModels.data.FiltrosNC.FechaHoy());
        ViewModels.data.FiltrosNC.FechaFin(ViewModels.data.FiltrosNC.FechaHoy());
        var _data = ko.mapping.toJS(ViewModels.data.FiltrosNC, mappingIgnore);
        ViewModels.data.FiltrosNC.ConsultarComprobantesVentaPorCliente(_data, event, ViewModels.data.FiltrosNC.PostConsultar);
        self.BusquedaComprobanteVentaNC([]);
        self.$buscador.find("#btn_AgregarComprobantesVenta").prop("disabled", true);
        self.$buscador.modal('show');
        setTimeout(function () {
          self.$buscador.find("#input-text-filtro-mercaderia").focus();
        }, 500);
      }
      else {
        alertify.alert("Por favor, busque un cliente.", function () {
          setTimeout(function () {
            self.$form.find("#Cliente").focus();
          }, 500);
        });
      }
    }
  }

self.AgregarComprobantesVentaPorCliente = function (data, event) {
  if (event) {
    var objeto = Knockout.CopiarObjeto(self.BusquedaComprobanteVentaNC);
    var i = 0;

    self.DetallesNotaCredito([]);

    ko.utils.arrayFirst(objeto(), function (item) {
      var data_items = ko.mapping.toJS(item);

      self.AliasUsuarioVenta(item.AliasUsuarioVenta());
      self.MiniComprobantesVentaNC.push(new MiniComprobantesVentaNCModel(data_items));

      ko.utils.arrayFirst(item.DetallesComprobanteVenta(), function (item2) {
        var data_item = ko.mapping.toJS(item2);
        data_item.IdReferenciaDCV = data_item.IdDetalleComprobanteVenta;
        data_item.IdDetalleReferencia = data_item.IdDetalleComprobanteVenta;

        if (window.Motivo.Reglas.IniciarCampoDetalle.length > 0) {
          window.Motivo.Reglas.IniciarCampoDetalle.forEach(function (elemento) {
            var nombre_elemento = elemento.Id;
            delete data_item[nombre_elemento];
            data_item[nombre_elemento] = elemento.Value;
          });
        }
        console.log(data_item);

        var _objeto = self.DetallesNotaCredito.AgregarDetalleNotaCredito(data_item, event);
        _objeto.InicializarVistaModelo(event, self.PostBusquedaProducto);
      });
      i++;
    });

    // Declarar idmotivo antes de usarlo
    var idmotivo = self.$form.find("#combo-motivo").val();

    if (window.Motivo.Reglas.CantidadFacturas != 1 && window.Motivo.Reglas.MontoCero == 0) {
      if (idmotivo != 13) {
        var comprobante = ko.mapping.toJS(objeto()[0], ignoreNotaCredito);
        var includesList = Object.keys(mapeadoNotaCredito);
        var nuevadata = leaveJustIncludedProperties(comprobante, includesList);

        ko.mapping.fromJS(nuevadata, self);
      }
    } else {
      if (idmotivo != 13) {
        var comprobante = ko.mapping.toJS(self.MiniComprobantesVentaNC()[0], ignoreNotaCredito);
        var includesList = Object.keys(mapeoPropiedadesNotaCredito);
        var nuevadata = leaveJustIncludedProperties(comprobante, includesList);

        ko.mapping.fromJS(nuevadata, self);
      }
    }

    self.SumarComprobantesElegidos(event);

    self.BusquedaComprobanteVentaNC([]);
    self.BusquedaComprobantesVentaNC([]);
    self.$buscador.resetearValidaciones();
    self.$buscador.modal("hide");

    self.HabilitarCampos(window.Motivo, event);
    self.LimpiarPorConcepto(event);

    if (self.$form.find("#tablaDetalleComprobanteVenta").is(":visible")) {
      if (self.$form.find("#tablaDetalleComprobanteVenta").find("select:not([disabled]), input:not([disabled])").length > 0) {
        self.$form.find("#tablaDetalleComprobanteVenta").find("select:not([disabled]), input:not([disabled])")[0].focus();
      } else {
        if (self.$form.find("#footer_notacredito").find("select:not([disabled]), input:not([disabled])").length > 0) {
          self.$form.find("#footer_notacredito").find("select:not([disabled]), input:not([disabled])")[0].focus();
        } else {
          self.$form.find("#btn_Grabar").focus();
        }
      }
    } else if (self.$form.find("#tablaConceptoComprobanteVenta").is(":visible")) {
      if (self.$form.find("#tablaConceptoComprobanteVenta").find("select:not([disabled]), input:not([disabled])").length > 0) {
        self.$form.find("#tablaConceptoComprobanteVenta").find("select:not([disabled]), input:not([disabled])")[0].focus();
      } else {
        if (self.$form.find("#footer_notacredito").find("select:not([disabled]), input:not([disabled])").length > 0) {
          self.$form.find("#footer_notacredito").find("select:not([disabled]), input:not([disabled])")[0].focus();
        } else {
          self.$form.find("#btn_Grabar").focus();
        }
      }
    }

    if (self.MiniComprobantesVentaNC().length > 0 && window.Motivo.Reglas.BorrarDetalles == 1) {
      if (idmotivo != 13) self.CalcularPorcentaje(data, event);
    }
  }
};

  self.LimpiarPorConcepto = function (data, event) {
    if (event) {

      //LIMPIAMOS LA PARTE FOOTER DE LA NOTA NotaCredito
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
      var row_comprobantes = self.MiniComprobantesVentaNC().length;
      if (row_comprobantes > 0) {
        ko.utils.arrayFirst(self.MiniComprobantesVentaNC(), function (item2) {
          // item2.IdReferencia = item2.IdDetalleComprobanteVenta;
          // var _total = parseFloatAvanzado(item2.SaldoNotaCredito());
          // var _total = parseFloatAvanzado(item2.SaldoNotaCredito());
          var _total = parseFloatAvanzado(item2.SaldoNotaCredito()) + parseFloatAvanzado(item2.DiferenciaSaldo());
          total += _total;
        });
      }

      self.TotalSaldo(total);
    }
  }

  self.CambiarMotivoNotaCredito = function (event) {
    if (event) {
      self.DetallesNotaCredito([]);
      var idmotivo = self.$form.find("#combo-motivo").val();
      if(idmotivo != 13) {
        self.InicializarVistaModeloDetalle(undefined, event);
      }

      self.LimpiarDetalleConcepto(event);

      self.MiniComprobantesVentaNC([]);
      self.CalcularTotales(event);
      self.SumarComprobantesElegidos(event);

      var idmotivo = self.$form.find("#combo-motivo").val();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
    }
  }

  self.CambiarFiltro = function (item, callback, event) {
    console.log("CambiarFiltro");
    
    
    window.DataMotivosNotaCredito.forEach(function (elemento) {
      if (item == elemento.Data.IdMotivoNotaCredito) {
        window.Motivo.Data = elemento.Data;
        window.Motivo.Reglas = elemento.Reglas;
      }
      console.log("item :"+item);
    });
    console.log(window.Motivo);
    callback(window.Motivo, event);
  };

  self.CorrerReglas = function (motivo, event) {
    if (event) {
      self.EstadoPendienteNota("0");
      self.$form.find("#CheckPendiente").prop("checked", false);
      var mostrar_concepto_detalle = motivo.Reglas.MotivoDetalle;
      var mostrar_nota = motivo.Reglas.MostrarNota;
      var actualizarconceptos = motivo.Reglas.ActualizarConceptos;

      var idmotivo = self.$form.find("#combo-motivo").val();
      if(idmotivo == 13) {
        self.$form.find(".vista_detallesComprobanteVenta").hide();
        self.$form.find(".vista_concepto").hide();
      }
      else {
        if (mostrar_concepto_detalle == 1) {
          self.$form.find(".vista_detallesComprobanteVenta").hide();
          self.$form.find(".vista_concepto").show();
        }
        else {
          self.$form.find(".vista_detallesComprobanteVenta").show();
          self.$form.find(".vista_concepto").hide();
        }
      }

      self.NotaUsuario(motivo.Reglas.MensajeNota);
      if (mostrar_nota == 1) {
        self.$form.find(".mostrarnota").show();
      }
      else {
        self.$form.find(".mostrarnota").hide();
      }

      self.HabilitarCampos(motivo, event);

      if (actualizarconceptos == 1) {
        var nueva_data = {};
        // nueva_data.IdMotivoNotaCredito = self.IdMotivoNotaCredito();
        nueva_data.IdMotivoNotaCredito = motivo.Data.IdMotivoNotaCredito;
        self.ActualizarConceptos(nueva_data, event, self.PostActualizarConceptos);
      }

      // self.RestringuirFecha(motivo, event)
      self.OcultarAlmacen(motivo, event);
      self.OpcionesActualizacion(motivo, event);
    }
  }

  self.OpcionesActualizacion = function (motivo, event) {
    if (event) {
      if (motivo.Reglas.ActualizarDetalle == 0) {
        self.ActualizarDetalle("0");
      }
      else {
        self.ActualizarDetalle("1");
      }

      if (motivo.Reglas.TotalProporcional == 0) {
        self.TotalProporcional("0");
      }
      else {
        self.TotalProporcional("1");
      }
    }
  }

  self.PostActualizarConceptos = function (data, event) {
    if (event) {
      self.ConceptosNotaCredito([]);
      data.forEach(function (item) {
        self.ConceptosNotaCredito.push(item);
      });

    }
  }

  self.HabilitarCampos = function (motivo, event) {
    if (event) {
      self.OcultarCampos(motivo, event);

      self.$form.find(".NotaCredito_Todos").prop("disabled", true);
      self.$form.find(".NotaCredito_Todos").closest("button").hide();
      //SE AGREGA CLASE no-tab
      self.$form.find(".NotaCredito_Todos").addClass("no-tab");
      if (motivo.Reglas.CamposEditables.length > 0) {
        motivo.Reglas.CamposEditables.forEach(function (item) {
          window.CamposNotaCredito.forEach(function (item2) {
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

    }
  }

  self.OcultarCampos = function (motivo, event) {
    if (event) {
      if (motivo.Reglas.OcultarCampos.length > 0) {
        self.$form.find(".NotaCredito_Todos").show();
        motivo.Reglas.OcultarCampos.forEach(function (item) {
          window.CamposNotaCredito.forEach(function (item2) {
            var id = "." + item2.Clase;
            if (item == item2.IdCampo) {
              $(id).hide();
            }

          });
        });
      }
      else {
        self.$form.find(".NotaCredito_Todos").show();
      }

      //Deshabilitamos todos los campos
      //self.$form.find(".NotaCredito_Todos").prop("disabled", true);
    }
  }

  self.OcultarAlmacen = function (motivo, event) {
    if (event) {
      if (window.Motivo.Reglas.OcultarAlmacen == 1) {
        self.$form.find(".panel-almacen").hide();
        self.$form.find(".panel-pendientenota").hide();

        // self.$form.find(".panel-pendientenota").addClass("ocultar");
        self.$form.find(".panel-pendientenota").find(".form-control").addClass("no-tab");
        self.$form.find(".panel-pendientenota").find(".form-control").prop("tabIndex", "-1");
      }
      else {
        if (self.TipoVenta() == TIPO_VENTA.MERCADERIAS) {
          self.$form.find(".panel-almacen").show();
          self.$form.find(".panel-pendientenota").show();

          // self.$form.find(".panel-pendientenota").removeClass("ocultar");
          self.$form.find(".panel-pendientenota").find(".form-control").removeClass("no-tab");
          self.$form.find(".panel-pendientenota").find(".form-control").removeAttr("tabIndex");
        }
        else {
          self.$form.find(".panel-almacen").hide();
          self.$form.find(".panel-pendientenota").hide();

          // self.$form.find(".panel-pendientenota").addClass("ocultar");
          self.$form.find(".panel-pendientenota").find(".form-control").addClass("no-tab");
          self.$form.find(".panel-pendientenota").find(".form-control").prop("tabIndex", "-1");
        }

      }
    }
  }

  self.ActualizarTotales = function (data, event) {
    if (event) {

      var tasaigv = parseFloatAvanzado(VALOR_IGV);

      var valorventainfecto = self.CalcularTotalOperacionInafecto();
      self.ValorVentaInafecto(valorventainfecto);

      var valorventanogravado = self.CalcularTotalOperacionNoGravada();
      self.ValorVentaNoGravado(valorventanogravado);

      var total = self.CalcularTotal();

      var totalmanual = total - (valorventanogravado + valorventainfecto);
      var valorventagravado = (totalmanual / (1 + tasaigv)).toFixed(NUMERO_DECIMALES_VENTA);//self.CalcularTotalOperacionGravada();
      var igvmanual = (totalmanual - valorventagravado).toFixed(NUMERO_DECIMALES_VENTA);//self.CalcularMontoIGV();
      
      self.Total(total);

      if (valorventagravado > 0) {
        self.IGV(igvmanual);
        self.ValorVentaGravado(valorventagravado);
      } else {
        self.IGV(0);
        self.ValorVentaGravado(0);
      }
    }
  }

  self.CalcularTotalesByFooter = function (data, event) {
    if (event) {
      var gravado = parseFloatAvanzado(data.ValorVentaGravado());
      var nogravado = parseFloatAvanzado(data.ValorVentaNoGravado());
      var inafecto = parseFloatAvanzado(data.ValorVentaInafecto());
      var igv = gravado * 0.18;
      var total_gravado = gravado + igv;
      var total = total_gravado + nogravado + inafecto;

      self.IGV(igv.toFixed(NUMERO_DECIMALES_VENTA));
      self.Total(total.toFixed(NUMERO_DECIMALES_VENTA));
    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $(self.$modal).modal("hide");//"#modalComprobanteVenta"
    }
  }

  self.Show = function (event) {
    if (event) {
      self.showNotaCredito(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showNotaCredito(false);
      self.callback = undefined;
      self.OnClickBtnCerrar(self, event);
    }
  }

  self.EnviarEmailCliente = function (data, event) {
    if (event) {
      var data_objeto = ko.mapping.toJS(data);
      if ((data_objeto.SerieDocumento.search(CODIGO_SERIE_BOLETA) >= 0 && data_objeto.IdTipoDocumento == ID_TIPO_DOCUMENTO_NOTA_CREDITO) || (data_objeto.SerieDocumento.search(CODIGO_SERIE_FACTURA) >= 0 && data_objeto.IdTipoDocumento == ID_TIPO_DOCUMENTO_NOTA_CREDITO)) {
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
      var texto = self.$form.find("#combo-direcciones option:selected").text();
      var valor = self.$form.find("#combo-direcciones option:selected").val();
      self.Direccion(texto);
    }
  }

  self.OnChangeMontoRecibido = function (data, event) {
    if (event) {
    }
  }
  
	self.OnClickBtnNuevaCuotaPagoClienteComprobanteVenta = function (data,event) {
		if (event) {			
			
			var nuevaCuotaPago = new VistaModeloCuotaPagoClienteComprobanteVenta(ko.mapping.toJS(self.NuevaCuotaPagoClienteComprobanteVenta),self);
			
			var idMaximo = 0;
			if (self.CuotasPagoClienteComprobanteVenta().length > 0) 
				idMaximo = Math.max.apply(null, ko.utils.arrayMap(self.CuotasPagoClienteComprobanteVenta(), function (e) { return e.IdCuotaPagoClienteComprobanteVenta(); }));

			if (nuevaCuotaPago.IdCuotaPagoClienteComprobanteVenta() == "" || nuevaCuotaPago.IdCuotaPagoClienteComprobanteVenta() == null || nuevaCuotaPago.IdCuotaPagoClienteComprobanteVenta() == undefined || nuevaCuotaPago.IdCuotaPagoClienteComprobanteVenta() == 0) {
				nuevaCuotaPago.IdCuotaPagoClienteComprobanteVenta(idMaximo + 1);
			}
		
      
			self.CuotasPagoClienteComprobanteVenta.push(nuevaCuotaPago);
			nuevaCuotaPago.InicializarVistaModelo(undefined,event);

			self.EnumerarCuotasPago(data,event);

			$("#modalCuotasPagoClienteComprobanteVenta").find("input").last().focus();
		}
	};

	self.OnClickBtnRemoverCuotaPagoClienteComprobanteVenta = function (data,event) {
		if (event) {
			self.CuotasPagoClienteComprobanteVenta.remove(data);
			self.EnumerarCuotasPago(data,event);
		}
	};

	self.OnClickBtnAbrirPlanCuotasPago = function (data, event) {
		if (event) {
			self.showCuotasPago(true);
		}
	};

	self.HideCuotasPago = function(data,event) {
    if(event) {
      
    }
  }

	self.EnumerarCuotasPago = function(data,event) {
		if(event) {
      console.log("EnumerarCuotasPago");
			if (self.CuotasPagoClienteComprobanteVenta != undefined) {
				var detalles = self.CuotasPagoClienteComprobanteVenta();
			} else {
				var detalles = [];
			}
			
			ko.utils.arrayForEach(detalles, function (item, key) {
				var prefijoCuota="Cuota";
				var numeroCuota=(key+1).toString().padStart(3,0);
				var identificadorCuota=prefijoCuota+numeroCuota;
				item.NumeroCuota(key+1);
				item.IdentificadorCuota(identificadorCuota);		
        item.IdMoneda(self.IdMoneda());
			});
			
		}
	}

	self.TotalMontoCuotasPago =  ko.computed(function () {
		
		if (self.CuotasPagoClienteComprobanteVenta != undefined) {
			var detalles = self.CuotasPagoClienteComprobanteVenta();
		} else {
			var detalles = [];
		}

		var total = 0;
		ko.utils.arrayForEach(detalles, function (item, key) {
			var monto = item.MontoCuota() == "" ? 0 : item.MontoCuota();
			total = total+ parseFloatAvanzado(monto);			
		});

		return (total.toFixed(NUMERO_DECIMALES_VENTA));
	}, this);

  self.ValidarFechaVencimiento = function (data, event) {
		if (event) {
			$(event.target).validate(function (valid, elem) {});
		}
	};
}
