//FALTA REAJUSTAR PARA QUE SE PUEDA USAR
VistaModeloNotaDebitoCompra = function (data, options) {
  var self = this;
  var ClienteValido = false;
  var CopiaFiltros = null;
  ko.mapping.fromJS(data, MappingCompra, self);
  self.CheckNumeroDocumento = ko.observable(false);
  self.IndicadorReseteoFormulario = true;
  self.NotaUsuario = ko.observable("");
  self.CheckPendiente = ko.observable(false);
  self.Options = options;
  self.CopiaNotaDebitoCompra = ko.observableArray([]);
  self.CopiaMotivos = ko.observable(self.MotivosNotaDebitoCompra());

  self.VistaConceptoODetalle = ko.observable(0);//0-ConceptoOculto/DetalleVisible ?? 1-DetalleOculto/ConceptoVisible
  self.VistaNota = ko.observable(0);//0-Oculto??1-Visible

  ModeloNotaDebitoCompra.call(this, self);

  var $form = $(options.IDForm);
  var $header = $(options.IDPanelHeader);
  var $buscador = $(options.IDModalBusqueda);

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.InicializarModelo(event);
      CopiaFiltros = ko.mapping.toJS(ViewModels.data.FiltrosND);
      $form.find("#ProveedorND").autoCompletadoProveedor(event, self.ValidarAutoCompletadoProveedor);
      $form.find("#nletras").autoDenominacionMoneda(self.Total());
      $form.find("#FechaEmision").inputmask({ "mask": "99/99/9999" });
      $form.find("#FechaVencimiento").inputmask({ "mask": "99/99/9999" });

      $form.find("#FechaIngreso").inputmask({ "mask": "99/99/9999" });

      $form.find("#fecha-inicio").inputmask({ "mask": "99/99/9999" });
      $form.find("#fecha-fin").inputmask({ "mask": "99/99/9999" });

      $form.find("#ProveedorND").on("focusout", function (event) {
        self.ValidarCliente(undefined, event);
      });

      self.InicializarValidator(event);

      self.CambiosFormulario(false);
    }
  }


  self.InicializarValidator = function (event) {
    if (event) {

      $.formUtils.addValidator({
        name: 'autocompletado_proveedor',
        validatorFunction: function (value, $el, config, language, $form) {
          var texto = $el.attr("data-validation-text-found");
          var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
          return resultado;
        },
        errorMessageKey: 'badautocompletado_proveedor'
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


  self.OnChangeTipoCompra = function (data, event) {
    if (event) {
      if (data.TipoCompra() == TIPO_COMPRA.MERCADERIAS) {
        $form.find("#GrupoAlmacen").removeClass("ocultar");
        $(".codigo-producto").removeClass("ocultar");
        $(".unidad-producto").removeClass("ocultar");
        $(".cantidad-producto").removeClass("ocultar");
        $(".costounitario-producto").removeClass("ocultar");
        $(".afectoisc-producto").removeClass("ocultar");
        $(".iscporcentaje-producto").removeClass("ocultar");
        $(".descuentounitario-producto").removeClass("ocultar");
        $(".importe-producto").removeClass("ocultar");
        $(".valorventa-producto").addClass("ocultar");
        $(".panel-almacen").find("select").removeClass("no-tab");
        $(".panel-almacen").find("select").removeAttr("tabIndex");
      }
      else if (data.TipoCompra() == TIPO_COMPRA.GASTOS) {
        $form.find("#GrupoAlmacen").addClass("ocultar");
        $(".codigo-producto").addClass("ocultar");
        $(".unidad-producto").addClass("ocultar");
        $(".cantidad-producto").addClass("ocultar");
        $(".costounitario-producto").addClass("ocultar");
        $(".afectoisc-producto").addClass("ocultar");
        $(".iscporcentaje-producto").addClass("ocultar");
        $(".descuentounitario-producto").addClass("ocultar");
        $(".importe-producto").addClass("ocultar");
        $(".valorventa-producto").removeClass("ocultar");
        $(".panel-almacen").find("select").addClass("no-tab");
        $(".panel-almacen").find("select").prop("tabIndex", "-1");
      }
      else if (data.TipoCompra() == TIPO_COMPRA.COSTOSAGREGADO) {
        $form.find("#GrupoAlmacen").addClass("ocultar");
        $(".codigo-producto").addClass("ocultar");
        $(".unidad-producto").addClass("ocultar");
        $(".cantidad-producto").addClass("ocultar");
        $(".costounitario-producto").addClass("ocultar");
        $(".afectoisc-producto").addClass("ocultar");
        $(".iscporcentaje-producto").addClass("ocultar");
        $(".descuentounitario-producto").addClass("ocultar");
        $(".importe-producto").addClass("ocultar");
        $(".valorventa-producto").removeClass("ocultar");
        $(".panel-almacen").find("select").addClass("no-tab");
        $(".panel-almacen").find("select").prop("tabIndex", "-1");
      }

      self.Limpiar(data, event);
      self.IdTipoCompra(data.TipoCompra());


    }
  }

  self.OnClickTipoCompra = function (data, event) {
    if (event) {
      if (self.CambiosFormulario() == true) {
        alertify.confirm(self.titulo, "Puede haber cambios sin guardar en el formulario, ¿Desea cambiar de todas formas?", function () {
          $(event.target).prop("checked", true);
          self.OnChangeTipoCompra(data, event);
        }, function () {

        });
      }
      else {
        return true;
      }
    }
  }

  self.InicializarVistaModeloDetalle = function (data, event) {
    if (event) {
      var item;

      if (self.DetallesNotaDebitoCompra().length > 0) {
        ko.utils.arrayForEach(self.DetallesNotaDebitoCompra(), function (el) {
          el.InicializarVistaModelo(event);
        });
      }

      var item = self.DetallesNotaDebitoCompra.AgregarDetalle(undefined, event);
      // item.InicializarVistaModelo(event);
      item.InicializarVistaModelo(event, self.PostBusquedaProducto, self.CrearDetalleComprobanteCompra);

      $(item.InputOpcion()).hide();

    }
  }

  self.ValidarCliente = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdProveedor(null);
          self.Direccion("");
        }
      });
    }
  }

  self.ValidarAutoCompletadoProveedor = function (data, event) {
    if (event) {

      if (data === -1) {
        if ($form.find("#ProveedorND").attr("data-validation-text-found") === $form.find("#ProveedorND").val()) {
          var $evento = { target: "#ProveedorND" };
          self.ValidarCliente(data, $evento);
        }
        else {
          $form.find("#ProveedorND").attr("data-validation-text-found", "");
          var $evento = { target: "#ProveedorND" };
          self.ValidarCliente(data, $evento);
        }
        if ($form.find(".panel-almacen").is(":visible")) {
          $form.find("#combo-sede").focus();
        }
        else {
          $form.find("#combo-tipodocumento").focus();
        }
      }
      else {
        if ($form.find("#ProveedorND").attr("data-validation-text-found") !== $form.find("#ProveedorND").val()) {
          $form.find("#ProveedorND").attr("data-validation-text-found", data.NumeroDocumentoIdentidad + " - " + data.RazonSocial);
        }

        var $evento = { target: "#ProveedorND" };
        self.ValidarCliente(data, $evento);
        //var $data = { IdPersona : }
        data.IdProveedor = data.IdPersona;
        ko.mapping.fromJS(data, MappingCompra, self);
        $form.find("#combo-tipodocumento").focus();
      }
      self.CambiarMotivoNotaDebitoCompra(event);
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
      self.ValorTipoCambio(data.TipoCambioCompra);
    }
    else {
      self.ValorTipoCambio("");
      alertify.alert("VALIDACION!", "No se encontro un tipo de cambio para la fecha emision");
    }
  }

  /********/
  self.OnChangeFechaEmision = function (data, event) {
    if (event) {
      data.FechaIngreso(data.FechaEmision());
    }
  }
  /********/
  self.OnChangeSerieDocumento = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-seriedocumento option:selected").text();
      data.SerieDocumento(texto);
    }
  }

  self.OnChangeMoneda = function (data, event) {
    if (event) {
      var texto = $header.find("#combo-moneda option:selected").text();
      data.NombreMoneda(texto);
      self.CargarCajas(data, event);

    }
  }

  self.PostBusquedaProducto = function (data, event, $callback) {
    if (event) {
      if (data != null) {
        // var item = self.DetallesNotaDebitoCompra.ReemplazarDetalle(data,event);
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
      var id = "#" + data.IdDetalleComprobanteCompra();
      $(id).addClass('active').siblings().removeClass('active');
      // self.SeleccionarDetalleComprobanteCompra(data,event);
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
      $form.find("#modalCliente").modal("hide");

      if (self.Cliente.EstaProcesado() == true) {
        $form.find("#ProveedorND").focus();
      }
      else {
        $form.find("#combo-formapago").focus();
      }
    }
  }

  self.Deshacer = function (data, event) {
    if (event) {
      self.Editar(self.CopiaNotaDebitoCompra(), event, self.callback);
    }
  }

  self.Editar = function (data, event, callback, blocked) {
    if (event) {
      var objetoData = ko.mapping.toJS(data);
      if (self.IndicadorReseteoFormulario === true) $form.resetearValidaciones();//'#formComprobanteCompra'
      if (callback) self.callback = callback;
      // self.EditarNotaDebitoCompra(data,event);
      self.InicializarVistaModelo(undefined, event);
      self.CargarCajas(data, event);

      if (!blocked) {
        $(self.Options.IDPanelHeader).bloquearSelector(false);//'#panelheaderComprobanteCompra'
        $form.bloquearSelector(false);//'#formComprobanteCompra'
      }

      $('#loader').show();
      self.ConsultarDocumentosReferencia(data, event, function ($dataref, $eventref) {

        self.ConsultarDetalleNotaDebitoCompra(data, event, function ($data, $event) {
          // self.InicializarVistaModeloDetalle(undefined,event);

          if (self.DetallesNotaDebitoCompra().length > 0) {
            ko.utils.arrayForEach(self.DetallesNotaDebitoCompra(), function (item) {
              $(item.InputCodigoMercaderia()).attr("data-validation-found", "true");
              $(item.InputCodigoMercaderia()).attr("data-validation-text-found", $(item.InputProducto()).val());
            })
          }

          $('#loader').hide();
          setTimeout(function () {
            $form.find('#combo-seriedocumento').focus();
          }, 350);


          // var idmotivo = $form.find("#combo-motivo").val();
          var idmotivo = objetoData.IdMotivoNotaDebito;
          self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
          self.EditarNotaDebitoCompra(data, event);

          $form.find("#ProveedorND").attr("data-validation-text-found", self.NumeroDocumentoIdentidad() + " - " + self.RazonSocial());
          self.CopiaNotaDebitoCompra(data);
          if (blocked) {
            $(self.Options.IDPanelHeader).bloquearSelector(blocked);//'#panelheaderComprobanteCompra'
            $form.bloquearSelector(blocked);//'#formComprobanteCompra'
          }
          $form.find('#btn_Cerrar').removeAttr('disabled');
          $form.find("#combo-motivo").prop('disabled', true);
        });

      });

    }
  }

  self.Limpiar = function (data, event) {
    if (event) {
      self.DetallesNotaDebitoCompra([]);
      self.Nuevo(self.ComprobanteCompraInicial, event, self.callback);
      self.DocumentosReferencia([])
      var idmotivo = $form.find("#combo-motivo").val();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
      $form.find("#ProveedorND").val("");
      // self.LimpiarPorConcepto(event);
      self.Total("0.00");
      self.ValorCompraGravado("0.00");
      self.EstadoPendienteNota("0");
      self.CheckPendiente(false);

      self.MotivosNotaDebitoCompra([]);
      ko.utils.arrayForEach(self.CopiaMotivos(), function (entry) {
        var ids = entry.AfectacionCompra();
        var res = ids.split(",");
        if (res.indexOf(self.TipoCompra()) >= 0) {
          self.MotivosNotaDebitoCompra.push(entry);
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
      self.NuevoComprobanteCompra(data, event);
      self.InicializarVistaModelo(undefined, event);
      self.InicializarVistaModeloDetalle(undefined, event);
      self.CargarCajas(data, event);

      self.EstadoPendienteNota("0");
      self.CheckPendiente(false);
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
      // $("#loader").show();
      var numero_facturas = self.MiniComprobantesCompraND().length;
      if (numero_facturas <= 0) {
        alertify.alert("VALIDACION!", "Por Favor, Ingrese al ingrese un comprobante de referencia para proceder.");
        return false;
      }

      // self.AplicarExcepcionValidaciones(data,event);

      if (!$form.isValid()) {//lang, conf, false
        //displayErrors( errors );
      }
      else {
        if (window.Motivo.Reglas.MotivoDetalle != 1) {
          var filtrado = ko.utils.arrayFilter(self.DetallesNotaDebitoCompra(), function (item) {
            return item.IdProducto() != null;
          });
          if (filtrado.length <= 0) {
            alertify.alert("Validación", "Debe ingresar por lo menos 1 ítem.");
            return false;
          }
        }

        alertify.confirm("Emisión de Nota Débito de Compra", "¿Desea guardar los cambios?", function () {
          $("#loader").show();

          if (window.Motivo.Reglas.BorrarDetalles == 1) {
            self.DetallesNotaDebitoCompra([]);
          }

          self.GuardarNotaDebitoCompra(event, self.PostGuardar);
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
            alertify.alert().destroy();
            setTimeout(function () {
              $form.find("#combo-seriedocumento").focus();
            }, 400);
          });
        }
        else {
          var mensaje = "Se actualizó el comprobante " + self.SerieDocumento() + " - " + self.NumeroDocumento() + " correctamente.";
          alertify.alert("Edicion de Nota Credito", mensaje, function () {
            setTimeout(function () {
              $(options.IDModalComprobanteCompra).modal("hide");
            }, 400);
            alertify.alert().destroy();
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

  self.CalculoTotalISC = ko.computed(function () {
    var resultado = accounting.formatNumber(self.ISC(), NUMERO_DECIMALES_VENTA);
    return resultado;
  }, this);


  self.CalculoTotalCompra = ko.computed(function () {
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

  self.TieneAccesoEditar = ko.observable(self.ValidarEstadoComprobanteCompra(self, window));
  self.TieneAccesoAnular = ko.observable(self.ValidarEstadoComprobanteCompra(self, window));

  self.QuitarDetalleComprobanteCompra = function (data, event) {
    if (event) {
      var tr = $(data.InputProducto()).closest("tr");
      // var IdDetalleComprobanteCompra = tr.next().attr("id");
      var IdDetalleComprobanteCompra = tr[0].id;
      var itemsgte = self.DetallesNotaDebitoCompra.Obtener(IdDetalleComprobanteCompra, event);
      setTimeout(function () {
        self.Seleccionar(itemsgte, event);
      }, 250);
      self.DetallesNotaDebitoCompra.RemoverDetalle(data, event);
      self.ActualizarTotales(data, event);
    }
  }

  self.ValidarSerieDocumento = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
      data.SerieDocumento($(event.target).zFill(data.SerieDocumento(), 4));
    }
  }

  self.ValidarNumeroDocumento = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
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
            resultado = data.TipoCambioCompra;
          else
            alertify.alert("VALIDACION!", "No se encontro un tipo de cambio para la fecha emision");
        });
      return resultado;
    }
  }

  self.AplicarExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesNotaDebitoCompra().length;
      var ultimoItem = self.DetallesNotaDebitoCompra()[total - 1];
      var resultado = "false";
      if ($form.find("#tablaDetalleComprobanteCompra").is(":visible")) {
        if (ultimoItem.CodigoMercaderia() == "" && ultimoItem.NombreProducto() == ""
          && (ultimoItem.Cantidad() == "" || ultimoItem.Cantidad() == "0")
          && (ultimoItem.CostoUnitario() == "" || ultimoItem.CostoUnitario() == "0")
          && (ultimoItem.DescuentoUnitario() == "" || ultimoItem.DescuentoUnitario() == "0")
        ) {
          resultado = "true";
        }

        $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputCostoUnitario()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputDescuentoUnitario()).attr("data-validation-optional", resultado);
      }
      else if ($form.find("#tablaConceptoComprobanteCompra").is(":visible")) {
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
      self.Total("0.00");
    }
  }

  self.CalcularPorcentaje = function (data, event) {
    if (event) {

      var num_filas = self.MiniComprobantesCompraND().length;
      if (num_filas > 0) {
        var primera_fila = self.MiniComprobantesCompraND()[0];

        var totalsaldo = parseFloatAvanzado(data.TotalSaldo());
        var importe = parseFloatAvanzado(data.Importe());
        var porcentaje = 0;
        var igv = 0;
        var gravado = 0;
        var nogravado = 0;
        if (importe > totalsaldo) {
          importe = 0;
          alertify.alert("VALIDACION!", "El Importe no puede ser menor al Saldo", function () {
            $form.find("#input-importe").focus();
          });
        }
        else {
          var valorGravado = parseFloatAvanzado(primera_fila.ValorCompraGravado());
          var importe = parseFloatAvanzado(data.Importe());
          if (valorGravado > 0) {
            porcentaje = (importe / totalsaldo) * 100;
            igv = importe * VALOR_IGV;
            gravado = importe - igv;
          }
          else {
            porcentaje = (importe / totalsaldo) * 100;
            nogravado = importe;
          }
        }

        self.Importe(importe.toFixed(NUMERO_DECIMALES_COMPRA));
        self.Porcentaje(porcentaje.toFixed(NUMERO_DECIMALES_COMPRA));
        self.Total(importe.toFixed(NUMERO_DECIMALES_COMPRA));
        self.IGV(igv.toFixed(NUMERO_DECIMALES_COMPRA));
        self.ValorCompraGravado(gravado.toFixed(NUMERO_DECIMALES_COMPRA));
        self.ValorCompraNoGravado(nogravado.toFixed(NUMERO_DECIMALES_COMPRA));
      }

    }
  }

  self.CalcularTotalByPorcentaje = function (data, event) {
    if (event) {
      var num_filas = self.MiniComprobantesCompraND().length;

      if (num_filas > 0) {
        var porcentaje = parseFloatAvanzado(data.Porcentaje());
        var importe = 0;
        var igv = 0;
        var gravado = 0;
        var nogravado = 0;

        if (porcentaje > 100) {
          porcentaje = 0;
          alertify.alert("VALIDACION!", "El Importe No puede ser mayor al Saldo", function () {
            $form.find("#input-porcentaje").focus();
          });
        }
        else {
          var primera_fila = self.MiniComprobantesCompraND()[0];
          var porcentajeData = parseFloatAvanzado(data.Porcentaje());
          var porcentaje_decimal = porcentajeData / 100;
          var totalSaldo = parseFloatAvanzado(data.TotalSaldo());

          var valorCompraGravado = parseFloatAvanzado(primera_fila.ValorCompraGravado());
          importe = totalSaldo * porcentaje_decimal;
          if (valorCompraGravado > 0) {
            igv = importe * VALOR_IGV;
            // porcentaje = (importe / totalsaldo) * 100;
            // igv = parseFloatAvanzado(data.Importe()) * VALOR_IGV;
            gravado = importe - igv;
          }
          else {
            nogravado = importe;
          }
        }

        self.Porcentaje(porcentaje.toFixed(NUMERO_DECIMALES_COMPRA));
        self.Importe(importe.toFixed(NUMERO_DECIMALES_COMPRA));
        self.Total(importe.toFixed(NUMERO_DECIMALES_COMPRA));
        self.IGV(igv.toFixed(NUMERO_DECIMALES_COMPRA));
        self.ValorCompraGravado(gravado.toFixed(NUMERO_DECIMALES_COMPRA));
        self.ValorCompraNoGravado(nogravado.toFixed(NUMERO_DECIMALES_COMPRA));
      }

    }
  }

  //NUEVAS FUNCIONES
  self.OnChangeAlmacen = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-sede option:selected").text();
      data.NombreAlmacen(texto);
    }
  }

  self.OnChangeCheckPendiente = function (data, event) {
    if (event) {
      if (self.CheckPendiente() == true) {
        self.EstadoPendienteNota("2");
        $form.find(".panel-almacen").hide();
      }
      else {
        self.EstadoPendienteNota("0");
        $form.find(".panel-almacen").show();
      }
    }
  }

  self.AbrirConsultaComprobanteCompra = function (data, event) {
    if (event) {
      var _idproveedor = $form.find("#IdProveedor").val();
      if (_idproveedor != "") {
        var filas_mcv = self.MiniComprobantesCompraND().length;
        var cant_items = window.Motivo.Reglas.CantidadFacturas;
        if (filas_mcv > 0 && cant_items == 0) {
          alertify.alert("VALIDACION!", "Usted tiene un Comprobante de Compra en Proceso. Elimine la factura y vuelva a buscar.");
          return false;
        }

        var tipo_documento = $form.find("#combo-tipodocumento").val();
        if (filas_mcv > 0) {
          if (self.MiniComprobantesCompraND()[0].IdTipoDocumento() != tipo_documento) {
            alertify.alert("VALIDACION!", "Usted tiene añadido un Comprobante de Compra de un Tipo. Elimine y vuelva a consultar.");
            return false;
          }
        }

        ViewModels.data.FiltrosND.IdPersona(self.IdPersona());
        ViewModels.data.FiltrosND.IdTipoDocumento($form.find("#combo-tipodocumento").val());
        ViewModels.data.FiltrosND.IdMoneda($header.find("#combo-moneda").val());
        ViewModels.data.FiltrosND.FechaInicio(ViewModels.data.FiltrosND.FechaHoy());
        ViewModels.data.FiltrosND.FechaFin(ViewModels.data.FiltrosND.FechaHoy());
        var _data = ko.mapping.toJS(ViewModels.data.FiltrosND, mappingIgnore);
        ViewModels.data.FiltrosND.ConsultarComprobantesCompraPorCliente(_data, event, ViewModels.data.FiltrosND.PostConsultar);
        self.BusquedaComprobanteCompraND([]);
        $buscador.find("#btn_AgregarComprobantesCompra").prop("disabled", true);
        $buscador.modal('show');
        setTimeout(function () {
          $buscador.find("#input-text-filtro-mercaderia").focus();
        }, 500);
      }
      else {
        alertify.alert("VALIDACION!", "Por favor, busque un proveedor.", function () {
          setTimeout(function () {
            $form.find("#ProveedorND").focus();
          }, 500);
        });
      }
    }
  }

  self.AgregarComprobantesCompraPorCliente = function (data, event) {
    if (event) {
      var objeto = Knockout.CopiarObjeto(self.BusquedaComprobanteCompraND);
      var i = 0;
      // self.DetallesNotaDebitoCompra([]);
      //ELMINAMOS DetalleComprobanteCompra
      self.DetallesNotaDebitoCompra([]);

      ko.utils.arrayFirst(objeto(), function (item) {
        var data_items = ko.mapping.toJS(item);
        self.MiniComprobantesCompraND.push(new MiniComprobantesCompraNDModel(data_items));

        ko.utils.arrayFirst(item.DetallesComprobanteCompra(), function (item2) {
          // var data_item = ko.mapping.toJS(item2, {ignore : ["SaldoPendienteNotaDebito"]});
          var data_item = ko.mapping.toJS(item2);
          data_item.IdReferenciaDCV = data_item.IdDetalleComprobanteCompra;

          if (window.Motivo.Reglas.IniciarCampoDetalle.length > 0) {
            window.Motivo.Reglas.IniciarCampoDetalle.forEach(function (elemento) {
              var nombre_elemento = elemento.Id;
              delete data_item[nombre_elemento];
              data_item[nombre_elemento] = elemento.Value;
            });
          }
          console.log(data_item);

          var _objeto = new VistaModeloDetalleNotaDebitoCompra(data_item, self);
          self.DetallesNotaDebitoCompra.push(_objeto);
          _objeto.InicializarVistaModelo(event, self.PostBusquedaProducto);
          _objeto.OcultarCamposTipoCompra(self, event);
          // self.DetallesNotaDebitoCompra.Agregar(data_item, event);
        });
        i++;
      });

      if (window.Motivo.Reglas.CantidadFacturas != 1 && window.Motivo.Reglas.MontoCero == 0) {
        var comprobante = ko.mapping.toJS(objeto()[0], ignoreNotaDebitoCompra);
        var includesList = Object.keys(mapeadoNotaDebitoCompra);
        var nuevadata = leaveJustIncludedProperties(comprobante, includesList);

        ko.mapping.fromJS(nuevadata, self);
        // console.log(comprobante);
        // self.CalcularTotales(event);
      }
      else {
        var comprobante = ko.mapping.toJS(self.MiniComprobantesCompraND()[0], ignoreNotaDebitoCompra);
        var includesList = Object.keys(mapeoPropiedadesNotaDebitoCompra);
        var nuevadata = leaveJustIncludedProperties(comprobante, includesList);

        ko.mapping.fromJS(nuevadata, self);
      }

      self.SumarComprobantesElegidos(event);

      self.BusquedaComprobanteCompraND([]);
      self.BusquedaComprobantesCompraND([]);
      $buscador.resetearValidaciones();
      $buscador.modal("hide");

      //HABILITAMOS Y DESHABILITAMOS SEGUN MOTIVO
      self.HabilitarCampos(window.Motivo, event);
      self.LimpiarPorConcepto(event);

      //PARA FOCUS
      var inputInicio = $form.find("#btn_buscardocumentoreferencia");
      var form = inputInicio.closest('form');
      var items = $(form).find("input, select, button").not(':disabled, [readonly], .no-tab');
      var indice = items.index(inputInicio);
      items.eq(indice + 1).focus();

      if (self.MiniComprobantesCompraND().length > 0 && window.Motivo.Reglas.BorrarDetalles == 1) {
        self.CalcularPorcentaje(data, event);
      }
      if (window.Motivo.Reglas.ActualizarTotales == 1) {
        self.ActualizarTotales(data, event);
      }
    }
  }

  self.LimpiarPorConcepto = function (data, event) {
    if (event) {

      //LIMPIAMOS LA PARTE FOOTER DE LA NOTA NotaDebitoCompra
      self.Porcentaje("0.00");
      self.Importe("0.00");
      self.Total("0.00");
      self.IGV("0.00");
      self.ValorCompraGravado("0.00");
      self.ValorCompraNoGravado("0.00");
    }
  }

  self.SumarComprobantesElegidos = function (event) {
    if (event) {
      var total = 0.00;
      var row_comprobantes = self.MiniComprobantesCompraND().length;
      if (row_comprobantes > 0) {
        ko.utils.arrayFirst(self.MiniComprobantesCompraND(), function (item2) {
          // item2.IdReferencia = item2.IdDetalleComprobanteCompra;
          var totalComprobante = parseFloatAvanzado(item2.Total());
          var _total = totalComprobante;
          total += _total;
        });
      }

      self.TotalSaldo(total);
    }
  }

  self.CambiarMotivoNotaDebitoCompra = function (event) {
    if (event) {
      self.DetallesNotaDebitoCompra([]);
      self.InicializarVistaModeloDetalle(undefined, event);


      self.LimpiarDetalleConcepto(event);

      self.MiniComprobantesCompraND([]);
      self.CalcularTotales(event);
      self.SumarComprobantesElegidos(event);

      var idmotivo = $form.find("#combo-motivo").val();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
    }
  }

  self.CambiarFiltro = function (item, callback, event) {
    window.DataMotivosNotaDebitoCompra.forEach(function (elemento) {
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
      self.EstadoPendienteNota("0");
      $form.find("#CheckPendiente").prop("checked", false);
      var mostrar_concepto_detalle = motivo.Reglas.MotivoDetalle;
      var mostrar_nota = motivo.Reglas.MostrarNota;
      var actualizarconceptos = motivo.Reglas.ActualizarConceptos;

      //VISTAS
      self.VistaConceptoODetalle(mostrar_concepto_detalle);
      self.NotaUsuario(motivo.Reglas.MensajeNota);
      self.VistaNota(mostrar_nota);

      self.HabilitarCampos(motivo, event);

      if (actualizarconceptos == 1) {
        var nueva_data = {};
        // nueva_data.IdMotivoNotaDebito = self.IdMotivoNotaDebito();
        nueva_data.IdMotivoNotaDebito = motivo.Data.IdMotivoNotaDebito;
        self.ActualizarConceptos(nueva_data, event, self.PostActualizarConceptos);
      }

      // self.RestringuirFecha(motivo, event)
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
      self.ConceptosNotaDebitoCompra([]);
      data.forEach(function (item) {
        self.ConceptosNotaDebitoCompra.push(item);
      });
    }
  }

  self.HabilitarCampos = function (motivo, event) {
    if (event) {
      self.OcultarCampos(motivo, event);

      $form.find(".NotaDebitoCompra_Todos").prop("disabled", true);
      $form.find(".NotaDebitoCompra_Todos").closest("button").hide();
      //SE AGREGA CLASE no-tab
      $form.find(".NotaDebitoCompra_Todos").addClass("no-tab");
      if (motivo.Reglas.CamposEditables.length > 0) {
        motivo.Reglas.CamposEditables.forEach(function (item) {
          window.CamposNotaDebitoCompra.forEach(function (item2) {
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
        $form.find(".NotaDebitoCompra_Todos").show();
        motivo.Reglas.OcultarCampos.forEach(function (item) {
          window.CamposNotaDebitoCompra.forEach(function (item2) {
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
        $form.find(".NotaDebitoCompra_Todos").show();
      }

      //Deshabilitamos todos los campos
      //$form.find(".NotaDebitoCompra_Todos").prop("disabled", true);
    }
  }


  self.ActualizarTotales = function (data, event) {
    if (event) {
      var detalle = ko.mapping.toJS(self.DetallesNotaDebitoCompra);
      var importe = 0;
      var igv = 0;
      var gravado = 0;
      var nogravado = 0;
      ko.utils.arrayFirst(detalle, function (item) {
        var costoItem = parseFloatAvanzado(item.CostoItem);
        var importe_detalle = costoItem;
        if (item.AfectoIGV != 0) {
          var igv_fila = importe_detalle * VALOR_IGV;
          importe += importe_detalle;
          igv += igv_fila;
          // gravado += (importe_detalle - igv_fila);
          gravado += importe_detalle;
        }
        else {
          importe += importe_detalle;
          nogravado += importe_detalle;
        }
      });
      importe += igv;
      self.ValorCompraGravado(gravado.toFixed(NUMERO_DECIMALES_COMPRA));
      self.ValorCompraNoGravado(nogravado.toFixed(NUMERO_DECIMALES_COMPRA));
      self.IGV(igv.toFixed(NUMERO_DECIMALES_COMPRA));
      self.Total(importe.toFixed(NUMERO_DECIMALES_COMPRA));

    }
  }

  self.CalcularTotalesByFooter = function (data, event) {
    if (event) {
      var gravado = parseFloatAvanzado(data.ValorCompraGravado());
      var nogravado = parseFloatAvanzado(data.ValorCompraNoGravado());
      var igv = gravado * VALOR_IGV;
      var total_gravado = gravado + igv;
      var total = total_gravado + nogravado;

      self.IGV(igv.toFixed(NUMERO_DECIMALES_COMPRA));
      self.Total(total.toFixed(NUMERO_DECIMALES_COMPRA));
    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $(self.Options.IDModalComprobanteCompra).modal("hide");//"#modalComprobanteCompra"
      // $(options.IDModalNotaDebitoCompra).modal("hide");//"#modalComprobanteCompra"
      // if (self.callback) self.callback(self,event);
    }
  }

  self.Show = function (event) {
    if (event) {
      self.showNotaDebitoCompra(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showNotaDebitoCompra(false);
      self.callback = undefined;
      self.OnClickBtnCerrar(self, event);
    }
  }

  self.OnChangeNotaDebitoCompra = function (event) {
    if (event) {
      self.CambiosFormulario(true);
    }
  }

  self.OnChangeFormaPago = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-formapago option:selected").text();
      data.NombreFormaPago(texto);
      if (self.IdFormaPago() == ID_FORMA_PAGO_CREDITO) {
        self.IdCaja(null);
      } else {
        self.CargarCajas(data, event);
      }
    }
  }
  
  self.CargarCajas = function (data, event) {
    if (event) {
      $form.find("#combo-caja").empty()
      var cajas = ko.mapping.toJS(self.Cajas());
      var data = ko.mapping.toJS(data);
      var id_caja = data.IdCaja;
      $.each(cajas, function (key, entry) {
        if (self.IdMoneda() == entry.IdMoneda) {
          var sel = "";
          if (id_caja != "" || id_caja != null) {
            if (id_caja == entry.IdCaja) {
              sel = 'selected="true"';
            }
          }
          $form.find("#combo-caja").append($('<option ' + sel + '></option>').attr('value', entry.IdCaja).text(entry.NombreCaja));
        }
      })
      self.OnChangeCajas(data, event);
    }
  }

  self.OnChangeCajas = function (data, event) {
    if (event) {
      if (self.IdFormaPago() == ID_FORMA_PAGO_CONTADO) {
        var id = $form.find("#combo-caja option:selected").val()
        self.IdCaja(id);
      }
    }
  }
}
