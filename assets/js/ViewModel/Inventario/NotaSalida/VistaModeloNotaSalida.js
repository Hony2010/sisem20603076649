
VistaModeloNotaSalida = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingInventario, self);
  self.CheckNumeroNotaSalida = ko.observable(true);
  self.CheckPendiente = ko.observable(false);
  self.IndicadorReseteoFormulario = true;
  self.TotalCantidades = ko.observable("0.00");
  self.IdMotivoSeleccionado = ko.observable("");

  ModeloNotaSalida.call(this, self);

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.InicializarModelo(event);
      $("#Cliente").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS);
      $("#Proveedor").autoCompletadoProveedor(event, self.ValidarAutoCompletadoProveedor);
      // $("#nletras").autoDenominacionMoneda(self.Total());
      $("#FechaEmision").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $("#FechaVencimiento").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      AccesoKey.AgregarKeyOption("#formNotaSalida", "#btn_Grabar", TECLA_G);
      $("#fecha-inicio").inputmask({ "mask": "99/99/9999" });
      $("#fecha-fin").inputmask({ "mask": "99/99/9999" });
      self.InicializarValidator(event);

      $("#Cliente").on("focusout", function (event) {
        self.ValidarCliente(undefined, event);
      });

      $("#Proveedor").on("focusout", function (event) {
        self.ValidarProveedor(undefined, event);
      });

      var idmotivo = self.IdMotivoNotaSalida();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
      self.IdMotivoSeleccionado(idmotivo);
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

  self.VistaReferencia = ko.pureComputed(function () {
    return self.Referencia() != 1 ? "visible" : "hidden";
  }, this);

  self.InicializarVistaModeloDetalle = function (data, event) {
    if (event) {
      var item;

      if (self.DetallesNotaSalida().length > 0) {
        ko.utils.arrayForEach(self.DetallesNotaSalida(), function (el) {
          el.EstadoInputOpcion(true)
          el.InicializarVistaModelo(event, self.PostBusquedaProducto, self.CrearDetalleNotaSalida);//if (indice == 0) item = Knockout.CopiarObjeto(el);
        });
      }

      var item = self.DetallesNotaSalida.Agregar(undefined, event);
      item.InicializarVistaModelo(event, self.PostBusquedaProducto, self.CrearDetalleNotaSalida);
      item.EstadoInputOpcion(false);
      // $(item.InputOpcion()).hide();

      //self.Seleccionar(item,event);
    }
  }

  self.OnChangeFormaPago = function (data, event) {
    if (event) {
      var texto = $("#combo-formapago option:selected").text();
      data.NombreFormaPago(texto);
    }
  }

  self.OnChangeSerieNotaSalida = function (data, event) {
    if (event) {
      var texto = $("#combo-seriedocumento option:selected").text();
      data.SerieNotaSalida(texto);
    }
  }

  self.OnChangeAlmacen = function (data, event, parent) {
    if (event) {
      var comboAlmacen = $("#combo-sede option:selected");

      self.NombreAlmacen(comboAlmacen.text());
      self.IdAsignacionSede(comboAlmacen.val());

      ko.utils.arrayForEach(data.Sedes(), function (item) {
        if (item.NombreSede() == comboAlmacen.text()) {
          self.IdSede(item.IdSede());
        }
      });

      parent.BusquedaAvanzadaProducto.OnLimpiar(undefined, event);
    }
  }

  self.OnChangeMoneda = function (data, event) {
    if (event) {
      var texto = $("#combo-moneda option:selected").text();
      data.NombreMoneda(texto);
    }
  }

  self.PostBusquedaProducto = function (data, event, $callback) {
    if (event) {
      if (data != null) {
        setTimeout(function () {
          self.Seleccionar(data, event);
        }, 250);
      }

      if ($callback) $callback(data, event);
    }
  }

  self.CrearDetalleNotaSalida = function (data, event) {
    if (event) {
      var $input = $(event.target);
      var regla_comprobantes = window.Motivo.Reglas.DocumentoReferencia;
      if (regla_comprobantes == 0 || regla_comprobantes == 2) {
        self.RefrescarBotonesDetalleNotaSalida($input, event);
      }

    }
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      var id = "#" + data.IdDetalleNotaSalida();
      $(id).addClass('active').siblings().removeClass('active');
      self.SeleccionarDetalleNotaSalida(data, event);
      self.DetallesNotaSalida.Actualizar(undefined, event);
      // $("#nletras").autoDenominacionMoneda(self.Total());
    }
  }

  self.Deshacer = function (data, event) {
    if (event) {
      self.Editar(self.NotaSalidaInicial, event, self.callback);
    }
  }

  self.Limpiar = function (data, event) {
    if (event) {
      self.Nuevo(self.NotaSalidaInicial, event, self.callback);

      var idmotivo = self.IdMotivoNotaSalida();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
    }
  }

  self.Nuevo = function (data, event, callback) {
    if (event) {
      $('#formNotaSalida').resetearValidaciones();
      if (callback) self.callback = callback;
      self.NuevoNotaSalida(data, event);
      self.InicializarVistaModelo(undefined, event);
      self.InicializarVistaModeloDetalle(undefined, event);

      var idmotivo = self.IdMotivoNotaSalida();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
      self.CheckPendiente(false);
      self.EstadoPendienteComprobante("0");
      setTimeout(function () {
        $('#combo-seriedocumento').focus();
      }, 350);
    }
  }

  self.Editar = function (data, event, callback, blocked = false) {
    if (event) {
      if (self.IndicadorReseteoFormulario === true) $('#formNotaSalida').resetearValidaciones();
      if (callback) self.callback = callback;
      $("#panelNotaSalida").bloquearSelector(blocked);//'#panelheaderComprobanteVenta'
      $("#formNotaSalida").bloquearSelector(blocked);
      self.EditarNotaSalida(data, event);

      self.InicializarVistaModelo(undefined, event);
      var razonSocial = data.NumeroDocumentoIdentidad() == "" ? data.RazonSocial() : data.NumeroDocumentoIdentidad() + " - " + data.RazonSocial();

      self.RazonSocial(data.RazonSocial())

      $("#Proveedor").attr("data-validation-text-found", razonSocial);
      $("#Proveedor").val(razonSocial);
      $("#Cliente").attr("data-validation-text-found", razonSocial);
      $("#Cliente").val(razonSocial);

      self.ConsultarDetallesNotaSalida(data, event, function ($data, $event) {
        self.InicializarVistaModeloDetalle(undefined, event);

        ko.utils.arrayForEach(self.DetallesNotaSalida(), function (item) {
          $(item.InputCodigoMercaderia()).attr("data-validation-found", "true");
          $(item.InputCodigoMercaderia()).attr("data-validation-text-found", $(item.InputProducto()).val());
        })

        setTimeout(function () {
          $('#combo-seriedocumento').focus();
        }, 350);
        if (blocked) {
          $("#panelNotaSalida").bloquearSelector(blocked);//'#panelheaderComprobanteVenta'
          $("#formNotaSalida").bloquearSelector(blocked);//'#formComprobanteVenta'
        }
        $("#formNotaSalida").find('#btn_Cerrar').removeAttr('disabled');
      });
    }
  }

  self.Guardar = function (data, event) {
    if (event) {

      self.AplicarExcepcionValidaciones(data, event);

      if ($("#formNotaSalida").isValid() === false) {
        alertify.alert("Error en Validación", "Existe aun datos inválidos , por favor de corregirlo.");
      }
      else {
        var filtrado = ko.utils.arrayFilter(self.DetallesNotaSalida(), function (item) {
          return item.IdProducto() != null;
        });
        if (filtrado.length <= 0) {
          alertify.alert("VALIDACIÓN!", "Debe existir un detalle completo para proceder.");
          return false;
        }

        alertify.confirm(self.titulo, "¿Desea guardar los cambios?", function () {
          $("#loader").show();
          self.GuardarNotaSalida(event, self.PostGuardar);
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
          alertify.alert().destroy();
        });
      }
      else {
        $("#loader").hide();
        self.HabilitarCampos(window.Motivo, event);
        alertify.alert(self.titulo, self.mensaje, function () {
          if (self.callback) self.callback(data, event);
          alertify.alert().destroy();
        });
      }
    }
  }

  self.Anular = function (data, event, callback) {
    if (event) {
      //$("#loader").show();
      if (callback != undefined) self.callback = callback;
      self.AnularNotaSalida(data, event, self.PostAnular);
    }
  }

  self.Eliminar = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.EliminarNotaSalida(data, event, self.PostEliminar);
    }
  }

  self.PostAnular = function (data, event) {
    if (event) {
      var titulo = "Anulación de Nota de Salida";
      var mensaje = "Se anuló correctamente!";

      $("#loader").hide();

      alertify.alert(titulo, mensaje, function () {
        if (self.callback != undefined) self.callback(data, event);
      });
    }
  }

  self.PostEliminar = function (data, event) {
    if (event) {
      var resultado = data;

      if (resultado.error === undefined) {
        // alertify.alert("Se eliminó correctamente!");

        if (self.callback != undefined)
          self.callback(resultado, event);
      }
      else {
        alertify.alert(resultado.error);
      }
    }
  }

  // self.TieneAccesoEditar =  ko.observable(self.ValidarEstadoNotaSalida(self,window));
  self.TieneAccesoEditar = ko.observable(true);

  // self.TieneAccesoAnular =  ko.observable(self.ValidarEstadoNotaSalida(self,window));
  self.TieneAccesoAnular = ko.observable(true);

  self.OnEnableBtnEditar = ko.pureComputed(function () {

    if (self.Referencia() == 1) {
      return false;
    }

    if (self.IndicadorEstado() == ESTADO.ACTIVO) {
      return true;
    }
    else if (self.IndicadorEstado() == ESTADO.ANULADO) {
      return true;
    }
    else {
      return false;
    }
  }, this);

  self.OnEnableBtnEliminar = ko.computed(function () {
    if (self.IndicadorEstado() == ESTADO.ACTIVO) {
      return true;
    }
    else if (self.IndicadorEstado() == ESTADO.ANULADO) {
      return true;
    }
    else {
      return false;
    }
  }, this);

  self.OnVer = function (data, event, callback) {
    if (event) {
      self.Editar(data, event, callback, true);
    }
  }

  self.OnEditar = function (data, event, callback) {
    if (event) {
      self.Editar(data, event, callback, false);
    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $("#modalNotaSalida").modal("hide");//"#modalComprobanteCompra"
    }
  }

  self.OnChangeCheckNumeroNotaSalida = function (data, event) {
    if (event) {
      if ($("#CheckNumeroNotaSalida").prop("checked")) {
        $("#NumeroNotaSalida").attr("readonly", false);
        $("#NumeroNotaSalida").removeClass("no-tab");
        $("#NumeroNotaSalida").attr("data-validation-optional", "false");
        $("#NumeroNotaSalida").focus();
      }
      else {
        self.NumeroNotaSalida("");
        $("#NumeroNotaSalida").attr("data-validation-optional", "true");
        $("#NumeroNotaSalida").attr("readonly", true);
        $("#NumeroNotaSalida").addClass("no-tab");
        $("#NumeroNotaSalida").focus();
        $("#CheckNumeroNotaSalida").focus();
      }
    }
  }

  self.RefrescarBotonesDetalleNotaSalida = function (data, event) {
    if (event) {
      var tamaño = self.DetallesNotaSalida().length;
      var indice = data.closest("tr").index();
      if (indice === tamaño - 1) {
        self.RemoverExcepcionValidaciones(data, event);
        var InputOpcion = self.DetallesNotaSalida()[indice].InputOpcion();
        // $(InputOpcion).show();
        self.AgregarDetalleNotaSalida(undefined, event);
      }
    }
  }

  self.RemoverExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesNotaSalida().length;
      if (total > 0) {
        var ultimoItem = self.DetallesNotaSalida()[total - 1];
        var resultado = "false";

        $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputValorUnitario()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputDescuentoItem()).attr("data-validation-optional", resultado);

      }
    }
  }

  self.AgregarDetalleNotaSalida = function (data, event) {
    if (event) {
      self.InicializarVistaModeloDetalle(undefined, event);
      // var item = self.DetallesNotaSalida.Agregar(undefined, event);
      // item.InicializarVistaModelo(event, self.PostBusquedaProducto, self.CrearDetalleNotaSalida);
      // $(item.InputOpcion()).hide();
    }
  }

  self.QuitarDetalleNotaSalida = function (data, event) {
    if (event) {
      var tr = $(data.InputProducto()).closest("tr");
      // var IdDetalleNotaSalida = tr.next().attr("id");
      var IdDetalleNotaSalida = tr.attr("id");
      var itemsgte = self.DetallesNotaSalida.Obtener(IdDetalleNotaSalida, event);
      setTimeout(function () {
        self.Seleccionar(itemsgte, event);
      }, 250);
      self.DetallesNotaSalida.Remover(data, event);
    }
  }

  self.ValidarNumeroNotaSalida = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ValidarFechaEmision = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (valid) self.ValorTipoCambio(self.CalcularTipoCambio(data, event));
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

  self.ValidarCliente = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdPersona(self.NoCliente());
          self.Direccion("");
          self.RazonSocial("");
        }
      });
    }
  }

  self.ValidarAutoCompletadoCliente = function (data, event) {
    if (event) {

      if (data === -1) {
        if ($("#Cliente").attr("data-validation-text-found") === $("#Cliente").val()) {
          var $evento = { target: "#Cliente" };
          self.ValidarCliente(data, $evento);
        }
        else {
          $("#Cliente").attr("data-validation-text-found", "");
          var $evento = { target: "#Cliente" };
          self.ValidarCliente(data, $evento);
        }
        $("#combo-tipodocumento").focus();
      }
      else {
        var razonSocial = data.NumeroDocumentoIdentidad == "" ? data.RazonSocial : data.NumeroDocumentoIdentidad + " - " + data.RazonSocial;

        if ($("#Cliente").attr("data-validation-text-found") !== $("#Cliente").val()) {
          $("#Cliente").attr("data-validation-text-found", razonSocial);
        }

        var $evento = { target: "#Cliente" };
        self.ValidarCliente(data, $evento);
        //var $data = { IdPersona : }
        data.IdCliente = data.IdPersona;
        ko.mapping.fromJS(data, MappingInventario, self);
        $("#combo-tipodocumento").focus();

      }
    }
  }

  self.ValidarProveedor = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdPersona(self.NoCliente());
          self.Direccion("");
          self.RazonSocial("");
        }
      });
    }
  }

  self.ValidarAutoCompletadoProveedor = function (data, event) {
    if (event) {

      if (data === -1) {
        if ($("#Proveedor").attr("data-validation-text-found") === $("#Proveedor").val()) {
          var $evento = { target: "#Proveedor" };
          self.ValidarProveedor(data, $evento);
        }
        else {
          $("#Proveedor").attr("data-validation-text-found", "");
          var $evento = { target: "#Proveedor" };
          self.ValidarProveedor(data, $evento);
        }
        $("#combo-tipodocumento").focus();
      }
      else {
        var razonSocial = data.NumeroDocumentoIdentidad == "" ? data.RazonSocial : data.NumeroDocumentoIdentidad + " - " + data.RazonSocial;

        if ($("#Proveedor").attr("data-validation-text-found") !== $("#Proveedor").val()) {
          $("#Proveedor").attr("data-validation-text-found", razonSocial);
        }

        var $evento = { target: "#Proveedor" };
        self.ValidarProveedor(data, $evento);
        //var $data = { IdPersona : }
        data.IdProveedor = data.IdPersona;
        ko.mapping.fromJS(data, MappingInventario, self);
        $("#combo-tipodocumento").focus();

      }
    }
  }

  self.ValidarFechaVencimiento = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.OnKeyEnter = function (data, event) {
    var resultado = $(event.target).enterToTab(event);
    return resultado;
  }

  self.OnFocus = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      self.DetallesNotaSalida.Actualizar(undefined, event);
      // $("#nletras").autoDenominacionMoneda(self.Total());
      if (callback) callback(data, event);
    }
  }

  self.AplicarExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesNotaSalida().length;
      if (total > 0) {
        var ultimoItem = self.DetallesNotaSalida()[total - 1];
        var resultado = "false";
        if (ultimoItem.CodigoMercaderia() === "" && ultimoItem.NombreProducto() === ""
          && (ultimoItem.Cantidad() === "" || ultimoItem.Cantidad() === "0")
          && (ultimoItem.ValorUnitario() === "" || ultimoItem.ValorUnitario() === "0")
          && (ultimoItem.DescuentoItem() === "" || ultimoItem.DescuentoItem() === "0")
        ) {
          resultado = "true";
        }

        $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputValorUnitario()).attr("data-validation-optional", resultado);
        $(ultimoItem.InputDescuentoItem()).attr("data-validation-optional", resultado);

      }
    }
  }

  self.Cerrar = function (data, event) {
    if (event) {

    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {

    }
  }

  //NUEVAS FUNCIONES
  self.AbrirConsultaComprobanteVenta = function (data, event) {
    if (event) {
      var _idcliente = self.IdPersona();
      if (_idcliente != self.NoCliente()) {

        self.Filtros.IdPersona(self.IdPersona());
        self.Filtros.IdTipoDocumento($("#combo-tipodocumento").val());
        self.Filtros.IdMoneda($("#combo-moneda").val());
        self.Filtros.FechaInicio(self.Filtros.FechaHoy());
        self.Filtros.FechaFin(self.Filtros.FechaHoy());
        var _data = ko.mapping.toJS(ViewModels.data.NotaSalida.Filtros, mappingIgnore);
        if (self.Filtros.TipoPersona() == 1) {
          self.Filtros.ConsultarComprobantesVentaPorPersona(_data, event, self.Filtros.PostConsultar);
        }
        else {
          self.Filtros.ConsultarComprobantesCompraPorPersona(_data, event, self.Filtros.PostConsultar);
        }

        self.BusquedaComprobanteVenta([]);
        $("#btn_AgregarComprobantesVenta").prop("disabled", true);
        $("#BusquedaComprobantesVentaModel").modal('show');
        setTimeout(function () {
          $("#input-text-filtro").focus();
        }, 500);
      }
      else {
        if ($("#Cliente").is(":visible")) {
          alertify.alert("Por favor, busque un cliente.", function () {
            setTimeout(function () {
              $("#Cliente").focus();
            }, 400);
          });
        }
        else {
          alertify.alert("Por favor, busque un proveedor.", function () {
            setTimeout(function () {
              $("#Proveedor").focus();
            }, 400);
          });
        }
      }
    }
  }

  self.AgregarComprobantesVentaPorCliente = function (data, event) {
    if (event) {
      var objeto = Knockout.CopiarObjeto(self.BusquedaComprobanteVenta);
      var i = 0;
      // ViewModels.data.NotaSalida.DetallesNotaSalida([]);
      //ELMINAMOS DetalleComprobanteVenta
      self.DetallesNotaSalida([]);

      ko.utils.arrayFirst(objeto(), function (item) {
        self.MiniComprobantesVenta.push(new MiniComprobantesVentaModel(item));
        var j = 0;
        ko.utils.arrayFirst(item.DetallesNotaSalida(), function (item2) {
          var data_item = ko.mapping.toJS(item2);

          data_item.IdDetalleNotaSalida = 0;
          var resultado = self.DetallesNotaSalida.Agregar(data_item, event);
          // var _objeto = new VistaModeloDetalleNotaSalida(data_item);
          // ViewModels.data.NotaSalida.DetallesNotaSalida.push(_objeto);
          resultado.InicializarVistaModelo(event, self.PostBusquedaProducto);

          // self.DetallesNotaSalida.Agregar(item2, event);
          j++;
        });
        i++;
      });

      self.BusquedaComprobanteVenta([]);
      self.BusquedaComprobantesVenta([]);
      $("#BusquedaComprobantesVentaModel").resetearValidaciones();
      $("#BusquedaComprobantesVentaModel").modal("hide");

      //HABILITAMOS Y DESHABILITAMOS SEGUN MOTIVO
      self.HabilitarCampos(window.Motivo, event);

    }
  }

  self.CambiarMotivoNotaSalida = function (data, event) {
    if (event) {
      var texto = $("#combo-motivo option:selected").text();
      data.MotivoMovimiento(texto);
      $("#formNotaSalida").resetearValidaciones();
      var motivoAnterior = self.ObtenerMotivoPorId(self.IdMotivoSeleccionado());
      var motivoNuevo = self.ObtenerMotivoPorId(self.IdMotivoNotaSalida());

      if (motivoAnterior.Data.GrupoMotivoNotaEntrada == 'B' && motivoNuevo.Data.GrupoMotivoNotaEntrada == 'A') {
        self.DetallesNotaSalida([]);
        self.InicializarVistaModeloDetalle(undefined, event);
        self.MiniComprobantesVenta([]);
      }

      var idmotivo = self.IdMotivoNotaSalida();
      self.CambiarFiltro(idmotivo, self.CorrerReglas, event);

      console.log(idmotivo);
    }
  }

  self.ObtenerMotivoPorId = function (data) {
    if (data) {
      var motivo = window.DataMotivos.filter(item => item.Data.IdMotivoNotaSalida == data);
      return motivo.length > 0 ? motivo[0] : {};
    }
  }

  self.CambiarFiltro = function (item, callback, event) {
    window.DataMotivos.forEach(function (elemento) {
      if (item == elemento.Data.IdMotivoNotaSalida) {
        window.Motivo.Data = elemento.Data;
        window.Motivo.Reglas = elemento.Reglas;
      }
    });
    console.log(window.Motivo);
    callback(window.Motivo, event);
  };

  self.CorrerReglas = function (motivo, event) {
    if (event) {
      var documento_referencia = motivo.Reglas.DocumentoReferencia;
      var busqueda_persona = motivo.Reglas.BusquedaPersona;

      $("#combo-tipodocumento").html("");
      $("#Proveedor").val("");
      $("#Cliente").val("");
      self.IdPersona(self.NoCliente());
      self.RazonSocial("");
      self.Direccion("");

      if (documento_referencia == 1) {
        // $("#VistaCheckPendiente").show();
        $("#VistaBusquedaComprobanteReferencia").show();

        $("#TableDetalleNotaSalida").removeClass("grid-detail-body");
        $("#TableDetalleNotaSalida").find("input").prop("disabled", true);

        $("#VistaBusquedaComprobanteReferencia").find('input, select, button').not("input[readonly]").removeClass("no-tab");

        if (busqueda_persona == 1) {
          self.Filtros.TipoPersona(ID_TIPO_PERSONA_JURIDICA);
          $(".busqueda_cliente").show();
          $(".busqueda_proveedor").hide();
          $("#Proveedor").addClass("no-tab");
          $("#Cliente").removeClass("no-tab");
          window.TiposDocumento.TiposDocumentoVenta.forEach(function (item) {
            $('#combo-tipodocumento').append($("<option/>", {
              value: item.id,
              text: item.value
            }));
          });
        }
        else {
          self.Filtros.TipoPersona(ID_TIPO_PERSONA_NATURAL);
          $(".busqueda_proveedor").show();
          $(".busqueda_cliente").hide();
          $("#Cliente").addClass("no-tab");
          $("#Proveedor").removeClass("no-tab");
          // $("#combo-tipodocumento").html("");
          window.TiposDocumento.TiposDocumentoCompra.forEach(function (item) {
            $('#combo-tipodocumento').append($("<option/>", {
              value: item.id,
              text: item.value
            }));
          });
        }

      }
      else if (documento_referencia == 0) {
        // $("#VistaCheckPendiente").hide();
        $("#VistaBusquedaComprobanteReferencia").hide();

        $("#TableDetalleNotaSalida").addClass("grid-detail-body");
        $("#VistaBusquedaComprobanteReferencia").find('input, select, button').not("input[readonly]").addClass("no-tab");
        // self.IdPersona(self.NoCliente());
        // self.RazonSocial("");
      }
      else {
        $("#VistaBusquedaComprobanteReferencia").hide();

        $("#TableDetalleNotaSalida").addClass("grid-detail-body");
        $("#VistaBusquedaComprobanteReferencia").find('input, select, button').not("input[readonly]").addClass("no-tab");
        // self.RazonSocial("");
      }

      self.HabilitarCampos(motivo, event);

    }
  }

  self.HabilitarCampos = function (motivo, event) {
    if (event) {
      $(".NotaSalida_Todos").prop("disabled", true);
      // $(".NotaSalida_Todos").closest("button").hide();
      //SE AGREGA CLASE no-tab
      $(".NotaSalida_Todos").addClass("no-tab");
      if (motivo.Reglas.CamposEditables.length > 0) {
        motivo.Reglas.CamposEditables.forEach(function (item) {
          window.CamposNotaSalida.forEach(function (item2) {
            var id = "." + item2.Clase;
            if (item == item2.IdCampo) {
              if (item2.Tipo == 0) {
                $(id).prop("disabled", false);
                $(id).removeClass("no-tab");
              }
              else {
                // $(id).show();
                $(id).prop("disabled", false);
              }
            }
          });
        });
      }

      self.OcultarCampos(motivo, event);
    }
  }

  self.OcultarCampos = function (motivo, event) {
    if (event) {
      if (motivo.Reglas.OcultarCampos.length > 0) {
        $(".NotaSalida_Todos").show();
        motivo.Reglas.OcultarCampos.forEach(function (item) {
          window.CamposNotaSalida.forEach(function (item2) {
            var id = "." + item2.Clase;
            if (item == item2.IdCampo) {
              // $(id).hide();
            }
          });
        });
      }
      else {
        $(".NotaSalida_Todos").show();
      }

      //Deshabilitamos todos los campos
      //$(".NotaCredito_Todos").prop("disabled", true);
    }
  }

  self.OnChangeCheckPendiente = function (data, event) {
    if (event) {
      self.RefrescarDetalle(data, event);
      if (self.CheckPendiente() == true) {
        $("#VistaBusquedaComprobanteReferencia").hide();
        self.IdPersona(self.NoCliente());
        self.RazonSocial("");
        self.EstadoPendienteComprobante("1");
        $("#Cliente").val("");
        $("#Proveedor").val("");
        $("#Direccion").val("");
        $("#VistaBusquedaComprobanteReferencia").resetearValidaciones();

        $("#TableDetalleNotaSalida").addClass("grid-detail-body");
        var idmotivo = 0;
        self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
      }
      else {
        self.EstadoPendienteComprobante("0");
        $("#VistaBusquedaComprobanteReferencia").show();

        $("#TableDetalleNotaSalida").removeClass("grid-detail-body");
        var idmotivo = self.IdMotivoNotaSalida();
        self.CambiarFiltro(idmotivo, self.CorrerReglas, event);
      }
    }
  }

  self.RefrescarDetalle = function (data, event) {
    if (event) {
      self.MiniComprobantesVenta([]);
      self.DetallesNotaSalida([]);
      self.InicializarVistaModeloDetalle(undefined, event);
    }
  }

  self.OnClickBtnBuscadorMercaderiaListaSimple = function (data, event, dataBase) {
    if (event) {
      $("#BuscadorMercaderiaListaSimple").modal('show');
      $("#BuscadorMercaderiaListaSimple #spanNombreSede").text(self.NombreAlmacen());

      dataBase.BusquedaAvanzadaProducto.InicializarVistaModelo(data, event, self, self.OnClickAgregarMercaderiaImagen);

      setTimeout(function () {
        $("#BuscadorMercaderiaListaSimple").find('input').first().focus();
      }, 500);
    }
  }

  self.OnClickAgregarMercaderiaImagen = function (data, event) {
    if (event) {

      var objeto = ko.mapping.toJS(data);

      var detalleFiltrado = ko.utils.arrayFilter(self.DetallesNotaSalida(), function (item) {
        return item.IdProducto() == objeto.IdProducto;
      });

      self.DetallesNotaSalida.remove(function (item) { return item.IdProducto() == null; })

      if (detalleFiltrado.length > 0) {
        var producto = detalleFiltrado[0]
        var cantidadaDetalleFiltrado = parseFloatAvanzado(producto.Cantidad()) + parseFloatAvanzado(objeto.Cantidad);
        producto.Cantidad(parseFloatAvanzado(cantidadaDetalleFiltrado));
        producto.ValorUnitario(parseFloatAvanzado(producto.PrecioUnitario()));

      } else {
        var detalle = ko.mapping.toJS(self.NuevoDetalleNotaSalida);
        objeto = Object.assign(detalle, objeto);
        objeto.Cantidad = objeto.Cantidad == '0' ? '1' : objeto.Cantidad;
        objeto.ValorUnitario = parseFloatAvanzado(objeto.PrecioUnitario);
        var producto = self.DetallesNotaSalida.Agregar(objeto, event);
        producto.InicializarVistaModelo(event, self.PostBusquedaProducto, self.CrearDetalleNotaSalida);

      }

      $(producto.InputCodigoMercaderia()).attr("data-validation-found", "true");
      $(producto.InputCodigoMercaderia()).attr("data-validation-text-found", $(producto.InputProducto()).val());
      // $(producto.InputOpcion()).show();

      self.AgregarDetalleNotaSalida(undefined, event);

      if (self.IdMotivoNotaSalida() == 1 || self.IdMotivoNotaSalida() == 17 || self.IdMotivoNotaSalida() == 25) {
        //HABILITAMOS Y DESHABILITAMOS SEGUN MOTIVO
        self.HabilitarCampos(window.Motivo, event);
      }
    }
  }


  self.CambiosEnElDetale = ko.computed(function () {

    var detalles = self.DetallesNotaSalida != undefined ? self.DetallesNotaSalida() : [];

    var totalCantidad = 0;
    ko.utils.arrayForEach(detalles, function (item, key) {
      item.NumeroItem(key + 1);

      if (item.IdProducto() !== null && item.IdProducto() !== "") {
        totalCantidad = parseFloatAvanzado(totalCantidad) + parseFloatAvanzado(item.Cantidad());
      }
    });
    self.TotalCantidades(totalCantidad.toFixed(NUMERO_DECIMALES_VENTA))
  }, this)

}
