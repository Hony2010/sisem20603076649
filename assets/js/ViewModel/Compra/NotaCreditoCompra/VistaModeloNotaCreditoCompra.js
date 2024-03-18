
//FALTA REAJUSTAR PARA QUE SE PUEDA USAR
VistaModeloNotaCreditoCompra = function (data, options) {

  var self = this;
  var ClienteValido = false;
  var CopiaFiltros = null;
  ko.mapping.fromJS(data, MappingCompra, self);
  self.CheckNumeroDocumento = ko.observable(false);
  self.IndicadorReseteoFormulario = true;
  self.NotaUsuario = ko.observable("");
  self.CheckPendiente = ko.observable(false);
  self.Options = options;
  self.CopiaNotaCreditoCompra = ko.observableArray([]);
  self.CopiaMotivos = ko.observable(self.MotivosNotaCreditoCompra());

  self.VistaConceptoODetalle = ko.observable(0);//0-ConceptoOculto/DetalleVisible ?? 1-DetalleOculto/ConceptoVisible
  self.VistaNota = ko.observable(0);//0-Oculto??1-Visible
  self.VistaAlmacen = ko.observable(0);//0-Oculto??1-Visible
  self.VistaPendienteNota = ko.observable(0);//0-Oculto??1-Visible

  ModeloNotaCreditoCompra.call(this, self);

  var $form = $(options.IDForm);
  var $header = $(options.IDPanelHeader);
  var $buscador = $(options.IDModalBusqueda);

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.InicializarModelo(event);
      CopiaFiltros = ko.mapping.toJS(ViewModels.data.FiltrosNC);
      console.log(CopiaFiltros);
      $form.find("#ProveedorNC").autoCompletadoProveedor(event, self.ValidarAutoCompletadoProveedor);
      $form.find("#nletras").autoDenominacionMoneda(self.Total());
      $form.find("#FechaEmision").inputmask({ "mask": "99/99/9999" });
      $form.find("#FechaVencimiento").inputmask({ "mask": "99/99/9999" });

      $form.find("#FechaIngreso").inputmask({ "mask": "99/99/9999" });

      $form.find("#fecha-inicio").inputmask({ "mask": "99/99/9999" });
      $form.find("#fecha-fin").inputmask({ "mask": "99/99/9999" });

      $form.find("#ProveedorNC").on("focusout", function (event) {
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
        $(".codigo-producto").removeClass("ocultar");
        $(".unidad-producto").removeClass("ocultar");
        $(".cantidad-producto").removeClass("ocultar");
        $(".costounitario-producto").removeClass("ocultar");
        $(".preciounitario-producto").removeClass("ocultar");
        $(".afectoisc-producto").removeClass("ocultar");
        $(".iscporcentaje-producto").removeClass("ocultar");
        $(".descuentounitario-producto").removeClass("ocultar");
        $(".importe-producto").removeClass("ocultar");
        $(".valorventa-producto").addClass("ocultar");
        self.VistaPendienteNota(1);
        self.VistaAlmacen(1);
      }
      else if (data.TipoCompra() == TIPO_COMPRA.GASTOS) {
        $(".codigo-producto").addClass("ocultar");
        $(".unidad-producto").addClass("ocultar");
        $(".cantidad-producto").addClass("ocultar");
        $(".costounitario-producto").addClass("ocultar");
        $(".preciounitario-producto").addClass("ocultar");
        $(".afectoisc-producto").addClass("ocultar");
        $(".iscporcentaje-producto").addClass("ocultar");
        $(".descuentounitario-producto").addClass("ocultar");
        $(".importe-producto").addClass("ocultar");
        $(".valorventa-producto").removeClass("ocultar");
        self.VistaPendienteNota(0);
        self.VistaAlmacen(0);
      }
      else if (data.TipoCompra() == TIPO_COMPRA.COSTOSAGREGADO) {
        $(".codigo-producto").addClass("ocultar");
        $(".unidad-producto").addClass("ocultar");
        $(".cantidad-producto").addClass("ocultar");
        $(".costounitario-producto").addClass("ocultar");
        $(".preciounitario-producto").addClass("ocultar");
        $(".afectoisc-producto").addClass("ocultar");
        $(".iscporcentaje-producto").addClass("ocultar");
        $(".descuentounitario-producto").addClass("ocultar");
        $(".importe-producto").addClass("ocultar");
        $(".valorventa-producto").removeClass("ocultar");
        self.VistaPendienteNota(0);
        self.VistaAlmacen(0);
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

      if (self.DetallesNotaCreditoCompra().length > 0) {
        ko.utils.arrayForEach(self.DetallesNotaCreditoCompra(), function (el) {
          el.InicializarVistaModelo(event);
        });
      }

      var item = self.DetallesNotaCreditoCompra.AgregarDetalle(undefined, event);
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
        if ($form.find("#ProveedorNC").attr("data-validation-text-found") === $form.find("#ProveedorNC").val()) {
          var $evento = { target: "#ProveedorNC" };
          self.ValidarCliente(data, $evento);
        }
        else {
          $form.find("#ProveedorNC").attr("data-validation-text-found", "");
          var $evento = { target: "#ProveedorNC" };
          self.ValidarCliente(data, $evento);
        }
      }
      else {
        if ($form.find("#ProveedorNC").attr("data-validation-text-found") !== $form.find("#ProveedorNC").val()) {
          $form.find("#ProveedorNC").attr("data-validation-text-found", data.NumeroDocumentoIdentidad + " - " + data.RazonSocial);
        }

        var $evento = { target: "#ProveedorNC" };
        self.ValidarCliente(data, $evento);
        //var $data = { IdPersona : }
        data.IdProveedor = data.IdPersona;
        ko.mapping.fromJS(data, MappingCompra, self);
      }
      //LIMPIAMOS POR NUEVO PROVEEDOR
      self.CambiarMotivoNotaCreditoCompra(event);
      //PROXIMO focus
      var inputCliente = $form.find("#ProveedorNC");
      var form = inputCliente.closest('form');
      var items = $(form).find("input, select, button").not(':disabled, [readonly], .no-tab');
      var indice = items.index(inputCliente);
      items.eq(indice + 1).focus();

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

  self.OnChangeFormaPago = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-formapago option:selected").text();
      data.NombreFormaPago(texto);
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
        // var item = self.DetallesNotaCreditoCompra.ReemplazarDetalle(data,event);
        //item.InicializarVistaModelo(event,self.PostBusquedaProducto);
        // setTimeout( function()  {
        //     self.Seleccionar(data,event);
        // },250);

        // $form.find("#nletras").autoDenominacionMoneda(self.Total());
      }
      if ($callback) $callback(data, event);
    }
  }

  // self.Seleccionar = function (data,event) {
  //   if(event)
  //   {
  //     var id = "#"+ data.IdDetalleComprobanteCompra();
  //     $(id).addClass('active').siblings().removeClass('active');
  //     // self.SeleccionarDetalleComprobanteCompra(data,event);
  //   }
  // }

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
        $form.find("#ProveedorNC").focus();
      }
      else {
        $form.find("#combo-formapago").focus();
      }
    }
  }

  self.Deshacer = function (data, event) {
    if (event) {
      self.Editar(self.CopiaNotaCreditoCompra(), event, self.callback);
    }
  }

  self.Editar = function (data, event, callback, blocked) {
    if (event) {
      var objetoData = ko.mapping.toJS(data);
      if (self.IndicadorReseteoFormulario === true) $form.resetearValidaciones();//'#formComprobanteCompra'
      if (callback) self.callback = callback;
      // self.EditarNotaCreditoCompra(data,event);
      self.InicializarVistaModelo(undefined, event);
      self.CargarCajas(data, event);

      if (!blocked) {
        $(self.Options.IDPanelHeader).bloquearSelector(false);//'#panelheaderComprobanteCompra'
        $form.bloquearSelector(false);//'#formComprobanteCompra'
      }

      $('#loader').show();
      self.ConsultarDocumentosReferencia(data, event, function ($dataref, $eventref) {

        self.ConsultarDetalleNotaCreditoCompra(data, event, function ($data, $event) {
          // self.InicializarVistaModeloDetalle(undefined,event);

          if (self.DetallesNotaCreditoCompra().length > 0) {
            ko.utils.arrayForEach(self.DetallesNotaCreditoCompra(), function (item) {
              $(item.InputCodigoMercaderia()).attr("data-validation-found", "true");
              $(item.InputCodigoMercaderia()).attr("data-validation-text-found", $(item.InputProducto()).val());
              self.ListaIdsDetalle.push(item.IdProducto());
            })
          }

          $('#loader').hide();
          setTimeout(function () {
            $form.find('#combo-seriedocumento').focus();
          }, 350);


          // var idmotivo = $form.find("#combo-motivo").val();
          var idmotivo = objetoData.IdMotivoNotaCredito;
          self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
          self.EditarNotaCreditoCompra(data, event);

          $form.find("#ProveedorNC").attr("data-validation-text-found", self.NumeroDocumentoIdentidad() + " - " + self.RazonSocial());
          self.CopiaNotaCreditoCompra(data);
          if (blocked) {
            $(self.Options.IDPanelHeader).bloquearSelector(blocked);//'#panelheaderComprobanteCompra'
            $form.bloquearSelector(blocked);//'#formComprobanteCompra'
          }
          $form.find('#btn_Cerrar').removeAttr('disabled');
          $form.find("#combo-motivo").prop('disabled', true);
          self.SumarComprobantesElegidos(event);
          self.Importe(self.Total());
        });

      });

    }
  }

  self.Limpiar = function (data, event) {
    if (event) {
      self.DetallesNotaCreditoCompra([]);
      self.Nuevo(self.ComprobanteCompraInicial, event, self.callback);
      self.DocumentosReferencia([])
      var idmotivo = $form.find("#combo-motivo").val();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
      $form.find("#ProveedorNC").val("");
      // self.LimpiarPorConcepto(event);
      self.Total("0.00");
      self.ValorCompraGravado("0.00");
      self.EstadoPendienteNota("0");
      self.CheckPendiente(false);

      self.MotivosNotaCreditoCompra([]);
      ko.utils.arrayForEach(self.CopiaMotivos(), function (entry) {
        var ids = entry.AfectacionCompra();
        var res = ids.split(",");
        if (res.indexOf(self.TipoCompra()) >= 0) {
          self.MotivosNotaCreditoCompra.push(entry);
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

      var numero_facturas = self.MiniComprobantesCompraNC().length;
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
          var filtrado = ko.utils.arrayFilter(self.DetallesNotaCreditoCompra(), function (item) {
            return item.IdProducto() != null;
          });
          if (filtrado.length <= 0) {
            alertify.alert("Validación", "Debe ingresar por lo menos 1 ítem.");
            return false;
          }
        }

        alertify.confirm("Emisión de Nota Crédito Compra", "¿Desea guardar los cambios?", function () {
          $("#loader").show();

          if (window.Motivo.Reglas.BorrarDetalles == 1) {
            self.DetallesNotaCreditoCompra([]);
          }

          self.GuardarNotaCreditoCompra(event, self.PostGuardar);
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
      var itemsgte = self.DetallesNotaCreditoCompra.Obtener(IdDetalleComprobanteCompra, event);
      // setTimeout( function()  {
      // self.Seleccionar(itemsgte,event);
      // },250);
      self.DetallesNotaCreditoCompra.RemoverDetalle(data, event);
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
      var total = self.DetallesNotaCreditoCompra().length;
      var ultimoItem = self.DetallesNotaCreditoCompra()[total - 1];
      var resultado = "false";
      if ($form.find("#tablaDetalleComprobanteCompra").is(":visible")) {
        if (ultimoItem.CodigoMercaderia() == "" && ultimoItem.NombreProducto() == ""
          && (ultimoItem.Cantidad() == "" || ultimoItem.Cantidad() == "0")
          && (ultimoItem.CostoUnitario() == "" || ultimoItem.CostoUnitario() == "0")
          && (ultimoItem.PrecioUnitario() == "" || ultimoItem.PrecioUnitario() == "0")
          && (ultimoItem.DescuentoUnitario() == "" || ultimoItem.DescuentoUnitario() == "0")
        ) {
          resultado = "true";
        }

        $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputPrecioUnitario()).attr("data-validation-optional", resultado);
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
      self.SaldoNotaCredito("0.00");
    }
  }

  self.CalcularPorcentaje = function (data, event) {
    if (event) {

      var num_filas = self.MiniComprobantesCompraNC().length;
      if (num_filas > 0) {
        var primera_fila = self.MiniComprobantesCompraNC()[0];

        var totalsaldo = parseFloatAvanzado(data.TotalSaldo());
        var importe = parseFloatAvanzado(data.Importe());
        var tasaIGV = parseFloatAvanzado(self.TasaIGV());

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
          if (parseFloatAvanzado(primera_fila.ValorCompraGravado()) > 0) {
            porcentaje = (importe / totalsaldo) * 100;
            igv = (importe / (1 + tasaIGV)) * tasaIGV;
            gravado = importe - igv;
          }
          else {
            porcentaje = (importe / totalsaldo) * 100;
            nogravado = parseFloatAvanzado(data.Importe());
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
      var num_filas = self.MiniComprobantesCompraNC().length;

      if (num_filas > 0) {
        var porcentaje = parseFloatAvanzado(data.Porcentaje());
        var tasaIGV = parseFloatAvanzado(self.TasaIGV());
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
          var primera_fila = self.MiniComprobantesCompraNC()[0];
          var porcentaje_decimal = parseFloatAvanzado(data.Porcentaje()) / 100;
          importe = parseFloatAvanzado(data.TotalSaldo()) * porcentaje_decimal;
          
          if (parseFloatAvanzado(primera_fila.ValorCompraGravado()) > 0) {
            igv = (importe / (1 + tasaIGV)) * tasaIGV;
            gravado = importe - igv;
          }
          else {
            nogravado = importe;
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
        self.VistaAlmacen(0);
      }
      else {
        self.EstadoPendienteNota("0");
        self.VistaAlmacen(1);
      }
    }
  }

  self.AbrirConsultaComprobanteCompra = function (data, event) {
    if (event) {
      var _idproveedor = $form.find("#IdProveedor").val();
      if (_idproveedor != "") {
        var filas_mcv = self.MiniComprobantesCompraNC().length;
        var cant_items = window.Motivo.Reglas.CantidadFacturas;
        if (filas_mcv > 0 && cant_items == 0) {
          alertify.alert("VALIDACION!", "Usted tiene un Comprobante de Compra en Proceso. Elimine la factura y vuelva a buscar.");
          return false;
        }

        var tipo_documento = $form.find("#combo-tipodocumento").val();
        if (filas_mcv > 0) {
          if (self.MiniComprobantesCompraNC()[0].IdTipoDocumento() != tipo_documento) {
            alertify.alert("VALIDACION!", "Usted tiene añadido un Comprobante de Compra de un Tipo. Elimine y vuelva a consultar.");
            return false;
          }
        }

        ViewModels.data.FiltrosNC.IdPersona(self.IdPersona());
        ViewModels.data.FiltrosNC.IdTipoDocumento($form.find("#combo-tipodocumento").val());
        ViewModels.data.FiltrosNC.IdMoneda($header.find("#combo-moneda").val());
        ViewModels.data.FiltrosNC.FechaInicio(ViewModels.data.FiltrosNC.FechaHoy());
        ViewModels.data.FiltrosNC.FechaFin(ViewModels.data.FiltrosNC.FechaHoy());
        $(".fecha").inputmask({ "mask": "99/99/9999" });

        var _data = ko.mapping.toJS(ViewModels.data.FiltrosNC, mappingIgnore);
        ViewModels.data.FiltrosNC.ConsultarComprobantesCompraPorCliente(_data, event, ViewModels.data.FiltrosNC.PostConsultar);
        self.BusquedaComprobanteCompraNC([]);
        $buscador.find("#btn_AgregarComprobantesCompra").prop("disabled", true);
        $buscador.modal('show');
        setTimeout(function () {
          $buscador.find("#input-text-filtro-mercaderia").focus();
        }, 500);
      }
      else {
        alertify.alert("VALIDACION!", "Por favor, busque un proveedor.", function () {
          setTimeout(function () {
            $form.find("#ProveedorNC").focus();
          }, 500);
        });
      }
    }
  }

  self.AgregarComprobantesCompraPorCliente = function (data, event) {
    if (event) {
      var objeto = Knockout.CopiarObjeto(self.BusquedaComprobanteCompraNC);
      var i = 0;
      // self.DetallesNotaCreditoCompra([]);
      //ELMINAMOS DetalleComprobanteCompra
      self.DetallesNotaCreditoCompra([]);

      ko.utils.arrayFirst(objeto(), function (item) {
        var data_items = ko.mapping.toJS(item);
        self.MiniComprobantesCompraNC.push(new MiniComprobantesCompraNCModel(data_items));
        self.IdTipoCompra(item.IdTipoCompra());

        ko.utils.arrayFirst(item.DetallesComprobanteCompra(), function (item2) {
          // var data_item = ko.mapping.toJS(item2, {ignore : ["SaldoPendienteNotaCredito"]});
          var data_item = ko.mapping.toJS(item2);
          data_item.IdReferenciaDCV = data_item.IdDetalleComprobanteCompra;
          data_item.IdDetalleReferencia = data_item.IdDetalleComprobanteCompra;

          if (window.Motivo.Reglas.IniciarCampoDetalle.length > 0) {
            window.Motivo.Reglas.IniciarCampoDetalle.forEach(function (elemento) {
              var nombre_elemento = elemento.Id;
              delete data_item[nombre_elemento];
              data_item[nombre_elemento] = elemento.Value;
            });
          }

          console.log(data_item);

          var _objeto = new VistaModeloDetalleNotaCreditoCompra(data_item, self);
          self.DetallesNotaCreditoCompra.push(_objeto);
          _objeto.InicializarVistaModelo(event, self.PostBusquedaProducto);
          _objeto.OcultarCamposTipoCompra(self, event);
          self.ListaIdsDetalle.push(data_item.IdProducto);
          // self.DetallesNotaCreditoCompra.Agregar(data_item, event);
        });
        i++;
      });

      if (window.Motivo.Reglas.CantidadFacturas != 1 && window.Motivo.Reglas.MontoCero == 0) {
        var comprobante = ko.mapping.toJS(objeto()[0], ignoreNotaCreditoCompra);
        var includesList = Object.keys(mapeadoNotaCreditoCompra);
        var nuevadata = leaveJustIncludedProperties(comprobante, includesList);

        ko.mapping.fromJS(nuevadata, self);
        // console.log(comprobante);
        // self.CalcularTotales(event);
      }
      else {
        var comprobante = ko.mapping.toJS(self.MiniComprobantesCompraNC()[0], ignoreNotaCreditoCompra);
        var includesList = Object.keys(mapeoPropiedadesNotaCreditoCompra);
        var nuevadata = leaveJustIncludedProperties(comprobante, includesList);

        ko.mapping.fromJS(nuevadata, self);
      }

      self.SumarComprobantesElegidos(event);

      self.BusquedaComprobanteCompraNC([]);
      self.BusquedaComprobantesCompraNC([]);
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

      if (self.MiniComprobantesCompraNC().length > 0 && window.Motivo.Reglas.BorrarDetalles == 1) {
        self.CalcularPorcentaje(data, event);
      }

      if (window.Motivo.Reglas.ActualizarTotales == 1) {
        self.ActualizarTotales(data, event);
      }
    }
  }

  self.LimpiarPorConcepto = function (data, event) {
    if (event) {
      //LIMPIAMOS LA PARTE FOOTER DE LA NOTA NotaCreditoCompra
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
      var row_comprobantes = self.MiniComprobantesCompraNC().length;
      if (row_comprobantes > 0) {
        ko.utils.arrayFirst(self.MiniComprobantesCompraNC(), function (item2) {
          // var _total = parseFloatAvanzado(item2.SaldoNotaCredito());
          var _total = parseFloatAvanzado(item2.SaldoNotaCredito()) + parseFloatAvanzado(item2.DiferenciaSaldo());
          total += _total;
        });
      }

      self.TotalSaldo(total);
    }
  }

  self.CambiarMotivoNotaCreditoCompra = function (event) {
    if (event) {
      self.DetallesNotaCreditoCompra([]);
      self.InicializarVistaModeloDetalle(undefined, event);

      self.LimpiarDetalleConcepto(event);

      self.MiniComprobantesCompraNC([]);
      self.CalcularTotales(event);
      self.SumarComprobantesElegidos(event);

      var idmotivo = $form.find("#combo-motivo").val();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
      console.log(idmotivo);
    }
  }

  self.CambiarFiltro = function (item, callback, event) {
    var item = self.ObtenerDataFiltro(item, event);
    if (item != null) {
      window.Motivo = Object.assign(window.Motivo, item)
    }
    console.log(item);
    callback(window.Motivo, event);
  };

  self.CorrerReglas = function (motivo, event) {
    if (event) {
      var concepto_detalle = motivo.Reglas.MotivoDetalle;
      var mostrar_nota = motivo.Reglas.MostrarNota;
      var actualizarconceptos = motivo.Reglas.ActualizarConceptos;

      //OPCIONES DE VISTAS
      self.VistaConceptoODetalle(concepto_detalle);
      self.NotaUsuario(motivo.Reglas.MensajeNota);
      self.VistaNota(mostrar_nota);

      if (actualizarconceptos == 1) {
        var nueva_data = {};
        nueva_data.IdMotivoNotaCredito = motivo.Data.IdMotivoNotaCredito;
        self.ActualizarConceptos(nueva_data, event, self.PostActualizarConceptos);
      }
      //OPCIONES DE ACTUALIZACION
      self.ActualizarDetalle(motivo.Reglas.ActualizarDetalle);
      self.TotalProporcional(motivo.Reglas.TotalProporcional);

      //PARA EL AMACEN
      self.VistaAlmacen(motivo.Reglas.MostrarAlmacen);
      self.VistaPendienteNota(motivo.Reglas.MostrarAlmacen);
      self.EstadoPendienteNota("0");
      $form.find("#CheckPendiente").prop("checked", false);
      self.OnChangeCheckPendiente(motivo, event);

      //OCULTAMOS O HABILITAMOS CAMPOS
      self.HabilitarCampos(motivo, event);
    }
  }

  self.PostActualizarConceptos = function (data, event) {
    if (event) {
      self.ConceptosNotaCreditoCompra([]);
      data.forEach(function (item) {
        self.ConceptosNotaCreditoCompra.push(item);
      });
    }
  }

  self.HabilitarCampos = function (motivo, event) {
    if (event) {
      self.OcultarCampos(motivo, event);

      $form.find(".NotaCreditoCompra_Todos").prop("disabled", true);
      $form.find(".NotaCreditoCompra_Todos").closest("button").hide();
      //SE AGREGA CLASE no-tab
      $form.find(".NotaCreditoCompra_Todos").addClass("no-tab");
      if (motivo.Reglas.CamposEditables.length > 0) {
        motivo.Reglas.CamposEditables.forEach(function (item) {
          window.CamposNotaCreditoCompra.forEach(function (item2) {
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
        $form.find(".NotaCreditoCompra_Todos").show();
        motivo.Reglas.OcultarCampos.forEach(function (item) {
          window.CamposNotaCreditoCompra.forEach(function (item2) {
            var id = "." + item2.Clase;
            if (item == item2.IdCampo) {
              $(id).hide();
            }
          });
        });
      }
      else {
        $form.find(".NotaCreditoCompra_Todos").show();
      }

    }
  }

  self.ActualizarTotales = function (data, event) {
    if (event) {
      /*
      var detalle = ko.mapping.toJS(self.DetallesNotaCreditoCompra);
      var importe = 0;
      var igv = 0;
      var gravado = 0;
      var nogravado = 0;
      ko.utils.arrayFirst(detalle, function (item) {
        var importe_detalle = parseFloatAvanzado(item.CostoItem);     
        if (item.AfectoIGV != 0) {
          var igv_fila = importe_detalle * VALOR_IGV;
          importe += importe_detalle;
          igv += igv_fila;
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
      */
      var tasaigv = parseFloatAvanzado(VALOR_IGV);

      var valorventainfecto = self.CalcularTotalOperacionInafecto();
      self.ValorCompraNoGravado(valorventainfecto);

      var valorventanogravado = self.CalcularTotalOperacionNoGravada();
      self.ValorCompraNoGravado(valorventanogravado);

      var total = self.CalcularTotal();

      var totalmanual = total - (valorventanogravado + valorventainfecto);
      var valorventagravado = (totalmanual / (1 + tasaigv)).toFixed(NUMERO_DECIMALES_COMPRA);//self.CalcularTotalOperacionGravada();
      var igvmanual = (totalmanual - valorventagravado).toFixed(NUMERO_DECIMALES_COMPRA);//self.CalcularMontoIGV();

      self.Total(total);

      if (valorventagravado > 0) {
        self.IGV(igvmanual);
        self.ValorCompraGravado(valorventagravado);
      } else {
        self.IGV(0);
        self.ValorCompraGravado(0);
      }
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
      // $(options.IDModalNotaCreditoCompra).modal("hide");//"#modalComprobanteCompra"
      // if (self.callback) self.callback(self,event);
    }
  }

  self.Show = function (event) {
    if (event) {
      self.showNotaCreditoCompra(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showNotaCreditoCompra(false);
      self.callback = undefined;
      self.OnClickBtnCerrar(self, event);
    }
  }

  self.OnChangeNotaCreditoCompra = function (event) {
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

  self.OnChangePeriodo = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-periodo option:selected").text();
      //data.NombrePeriodo(texto);
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
