
VistaModeloCompraGasto = function (data, options) {
  var self = this;
  ko.mapping.fromJS(data, MappingCompra, self);
  // self.CheckNumeroDocumento = ko.observable(true);
  self.CheckDetraccion = ko.observable(false);
  self.IndicadorReseteoFormulario = true;
  self.Options = options;
  ModeloCompraGasto.call(this, self);

  var $form = $(options.IDForm);

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.InicializarModelo(event);

      AccesoKey.AgregarKeyOption("#formCompraGasto", "#btn_Grabar", TECLA_G);

      var target = options.IDForm + " " + "#Proveedor";
      $form.find("#Proveedor").autoCompletadoProveedor(event, self.ValidarAutoCompletadoProveedor, target);
      $form.find("#FechaEmision").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $form.find("#FechaVencimiento").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $form.find("#FechaDetraccion").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      self.InicializarValidator(event);

      $form.find("#Proveedor").on("focusout", function (event) {
        self.ValidarProveedor(undefined, event);
      });

      $("body")
        //.off("keydown")
        .on("keydown", function (event) {
          self.OnKeyEnterForm(undefined, event);
          return true;
        });

      self.OnRefrescar(data, event, true);
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

  self.InicializarVistaModeloDetalle = function (data, event) {
    if (event) {
      var item;

      if (self.DetallesCompraGasto().length > 0) {
        ko.utils.arrayForEach(self.DetallesCompraGasto(), function (el) {
          el.InicializarVistaModelo(event);
        });
      }

      var item = self.DetallesCompraGasto.Agregar(undefined, event);
      item.InicializarVistaModelo(event);
      $form.find(item.InputOpcion()).hide();

    }
  }

  self.OnChangeDetraccion = function (data, event) {
    if (event) {
      if (self.CheckDetraccion() == true) {
        $form.find("#content_detracciones").find("input[type=text], input[type=radio]").removeClass("no-tab");
        $form.find("#content_detracciones").find("input[type=text], input[type=radio]").prop("disabled", false);
        $form.find("#content_detracciones").find("input[type=text],input[type=radio]").removeAttr("tabindex");
        self.PagadorDetraccion(PAGADOR_DETRACCION.CLIENTE);
      }
      else {
        self.PagadorDetraccion(null);
        self.FechaDetraccion("");
        self.MontoDetraccion('0.00');
        self.NumeroDocumentoDetraccion("");
        $form.find("#content_detracciones").resetearValidaciones();
        $form.find("#content_detracciones").find("input[type=text],input[type=radio]").addClass("no-tab");
        $form.find("#content_detracciones").find("input[type=text],input[type=radio]").attr("tabindex", "-1");
        $form.find("#content_detracciones").find("input[type=text], input[type=radio]").prop("disabled", true);
      }
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

  self.OnChangeSerieDocumento = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-seriedocumento option:selected").text();
      data.SerieDocumento(texto);
    }
  }

  self.OnChangeTipoDocumento = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-tipodocumento option:selected").text();
      //data.TipoDocumento(texto);
    }
  }

  self.OnChangePeriodo = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-periodo option:selected").text();
      //data.NombrePeriodo(texto);
    }
  }

  self.OnChangeMoneda = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-moneda option:selected").text();
      data.NombreMoneda(texto);
      self.CargarCajas(data, event);

    }
  }

  self.CrearDetalleCompraGasto = function (data, event) {
    if (event) {
      var $input = $(event.target);
      self.RefrescarBotonesDetalleCompraGasto($input, event);
    }
  }

  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }
  }

  self.OnRefrescar = function (data, event, esporeliminacion) {
    if (event) {
      if (!$form.hasClass("selector-blocked")) {//'#formCompraGasto'
        if (!esporeliminacion) self.CrearDetalleCompraGasto(data, event);
        self.CalcularTotales(event);
        self.CalcularMontosPercepcion(data, event)
      }
      //$form.find("#nletras").autoDenominacionMoneda(self.Total());
    }
  }

  self.OnClickBtnNuevoProveedor = function (data, event, dataProveedor) {
    if (event) {
      dataProveedor.OnNuevo(dataProveedor.ProveedorNuevo, event, self.PostCerrarProveedor);
      dataProveedor.Show(event);
      return true;
    }
  }

  self.PostCerrarProveedor = function (dataProveedor, event) {
    if (event) {
      $(self.Options.IDModalProveedor).modal("hide");//"#modalProveedor"
      if (dataProveedor.EstaProcesado() === true) {
        $form.find("#Proveedor").focus();
      }
      else {
        $form.find("#FechaEmision").focus();
      }
    }
  }

  self.Deshacer = function (data, event) {
    if (event) {
      self.Editar(self.CompraGastoInicial, event, self.callback);
    }
  }

  self.Limpiar = function (data, event) {
    if (event) {
      self.Nuevo(self.CompraGastoInicial, event, self.callback);
    }
  }

  self.OnVer = function (data, event, callback) {
    if (event) {
      self.Editar(data, event, callback, true);
    }
  }

  self.Nuevo = function (data, event, callback) {
    if (event) {
      $form.resetearValidaciones();//'#formCompraGasto'
      if (callback) self.callback = callback;
      self.NuevoCompraGasto(data, event);
      self.InicializarVistaModelo(undefined, event);
      self.InicializarVistaModeloDetalle(undefined, event);
      self.OnChangeDetraccion(data, event);
      self.CargarCajas(data,event);

      setTimeout(function () {
        $form.find('#Proveedor').focus();
      }, 350);
    }
  }

  self.Editar = function (data, event, callback, blocked) {
    if (event) {
      if (self.IndicadorReseteoFormulario === true) $form.resetearValidaciones();//'#formCompraGasto'
      if (callback) self.callback = callback;
      self.EditarCompraGasto(data, event);
      self.InicializarVistaModelo(undefined, event);
      self.CargarCajas(data, event);

      self.CheckDetraccion((self.IndicadorDetraccion() == DETRACCION.CON_DETACCION ? true : false));
      $form.find("#Proveedor").attr("data-validation-text-found", self.NumeroDocumentoIdentidad() + " - " + self.RazonSocial());

      $('#loader').show();
      self.ConsultarDetallesCompraGasto(data, event, function ($data, $event) {
        self.InicializarVistaModeloDetalle(undefined, event);

        if (self.DetallesCompraGasto().length > 0) {
          ko.utils.arrayForEach(self.DetallesCompraGasto(), function (item) {
            $(item.InputIdProducto()).attr("data-validation-found", "true");
            $(item.InputIdProducto()).attr("data-validation-text-found", item.NombreProducto());
          })
        }

        $('#loader').hide();
        setTimeout(function () {
          $form.find('#combo-seriedocumento').focus();
        }, 350);

        $(self.Options.IDPanelHeader).bloquearSelector(blocked);//'#panelheaderCompraGasto'
        $form.bloquearSelector(blocked);//'#formCompraGasto'
        $form.find('#btn_Cerrar').removeAttr('disabled');
        if (blocked == true) {
          self.OnChangeDetraccion(self, event);
          $form.bloquearSelector(blocked);//'#formCompraGasto'
        }
        else {
          self.OnChangeDetraccion(self, event);
        }
      });
    }
  }

  self.Guardar = function (data, event) {
    if (event) {
      if ($("#loader").is(":visible")) {
        return false;
      }

      self.AplicarExcepcionValidaciones(data, event);

      if ($form.isValid() === false) {//"#formCompraGasto"
        alertify.alert("Error en Validación", "Existe aun datos inválidos , por favor de corregirlo.");
      }
      else {
        var filtrado = ko.utils.arrayFilter(self.DetallesCompraGasto(), function (item) {
          return item.IdProducto() != null;
        });
        if (filtrado.length <= 0) {
          alertify.alert("VALIDACIÓN!", "Debe existir un detalle completo para proceder.");
          return false;
        }
        alertify.confirm(self.titulo, "¿Desea guardar los cambios?", function () {
          $("#loader").show();
          self.GuardarCompraGasto(event, self.PostGuardar);
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
      self.AnularCompraGasto(data, event, self.PostAnular);
    }
  }

  self.Eliminar = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.EliminarCompraGasto(data, event, self.PostEliminar);
    }
  }

  self.PostAnular = function (data, event) {
    if (event) {
      var titulo = "Anulación de Comprobante de Compra";
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

      if (resultado.error === "") {
        alert("Se eliminó correctamente!");

        if (self.callback != undefined)
          self.callback(resultado.data, event);
      }
      else {
        alert(resultado.error);
      }
    }
  }

  self.CalculoTotalCompraGravado = ko.computed(function () {
    var resultado = accounting.formatNumber(self.ValorCompraGravado(), NUMERO_DECIMALES_COMPRA);
    return resultado;
  }, this);

  self.CalculoTotalCompraNoGravado = ko.computed(function () {
    var resultado = accounting.formatNumber(self.ValorCompraNoGravado(), NUMERO_DECIMALES_COMPRA);
    return resultado;
  }, this);

  self.CalculoTotalVentaInafecto = ko.computed(function () {
    var resultado = accounting.formatNumber(self.ValorCompraInafecto(), NUMERO_DECIMALES_COMPRA);
    return resultado;
  }, this);

  self.CalculoTotalDescuentoGlobal = ko.computed(function () {
    var resultado = accounting.formatNumber(self.DescuentoGlobal(), NUMERO_DECIMALES_COMPRA);
    return resultado;
  }, this);

  self.CalculoTotalIGV = ko.computed(function () {
    var resultado = accounting.formatNumber(self.IGV(), NUMERO_DECIMALES_COMPRA);
    return resultado;
  }, this);

  self.CalculoTotalCompra = ko.computed(function () {
    var resultado = accounting.formatNumber(self.Total(), NUMERO_DECIMALES_COMPRA);
    return resultado;
  }, this);

  self.OnEnableBtnEditar = ko.computed(function () {
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

  self.TieneAccesoAnular = ko.observable(self.ValidarEstadoCompraGasto(self, window));

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
    }
  }

  self.RefrescarBotonesDetalleCompraGasto = function (data, event) {
    if (event) {
      var tamaño = self.DetallesCompraGasto().length;
      var indice = data.closest("tr").index();
      if (indice === tamaño - 1) {
        self.RemoverExcepcionValidaciones(data, event);
        var InputOpcion = self.DetallesCompraGasto()[indice].InputOpcion();
        $(InputOpcion).show();
        self.OnAgregarFila(undefined, event);
      }
    }
  }

  self.RemoverExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesCompraGasto().length;
      var ultimoItem = self.DetallesCompraGasto()[total - 1];
      var resultado = "false";

      $form.find(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputPrecioUnitario()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputCostoUnitario()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputAfectoIGV()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputPrecioCosto()).attr("data-validation-optional", resultado);
    }
  }

  self.OnAgregarFila = function (data, event) {
    if (event) {
      var item = self.DetallesCompraGasto.Agregar(undefined, event);
      item.InicializarVistaModelo(event);
      $(item.InputOpcion()).hide();
    }
  }

  self.OnQuitarFila = function (data, event) {
    if (event) {
      self.DetallesCompraGasto.Remover(data, event);
      var trfilas = $("#tablaDetalleCompraGasto").find("tr").find("button:visible");
      if (trfilas.length == 0) {
        setTimeout(function () {
          $form.find("#OrdenCompra").focus();
        }, 250);
      }
      self.OnRefrescar(data, event, true);
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

  self.ValidarDescuentoGlobal = function (data, event) {

  }

  self.ValidarFechaEmision = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (valid) self.CalcularTipoCambio(data, event);//self.ValorTipoCambio(self.CalcularTipoCambio(data,event));
      });
    }
  }

  self.ValidarFechaNotaSalida = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.CalcularTipoCambio = function (data, event) {
    if (event) {
      var resultado = 0.00;
      if (self.IdMoneda() != ID_MONEDA_SOLES)
        self.TipoCambio.ObtenerTipoCambio(data, function ($data) {
          if ($data) {
            resultado = $data.TipoCambioCompra;
            self.ValorTipoCambio(resultado);
          }
          else {
            alertify.alert("No se encontro un tipo de cambio para la fecha emision");
          }
        });
      return resultado;
    }
  }

  self.ValidarProveedor = function (data, event) {
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
        if ($form.find("#Proveedor").attr("data-validation-text-found") === $form.find("#Proveedor").val()) {
          var $evento = { target: self.Options.IDForm + " " + "#Proveedor" };
          self.ValidarProveedor(data, $evento);
        }
        else {
          $form.find("#Proveedor").attr("data-validation-text-found", "");
          var $evento = { target: self.Options.IDForm + " " + "#Proveedor" };
          self.ValidarProveedor(data, $evento);
        }

        $form.find("#FechaEmision").focus();
      }
      else {
        if ($form.find("#Proveedor").attr("data-validation-text-found") !== $form.find("#Proveedor").val()) {
          $form.find("#Proveedor").attr("data-validation-text-found", data.NumeroDocumentoIdentidad + " - " + data.RazonSocial);
        }

        var $evento = { target: self.Options.IDForm + " " + "#Proveedor" };
        self.ValidarProveedor(data, $evento);
        //var $data = { IdPersona : }
        data.IdProveedor = data.IdPersona;
        ko.mapping.fromJS(data, MappingCompra, self);
        $form.find("#FechaEmision").focus();
        // var dataProveedor = new ModeloProveedor();
        // dataProveedor.ObtenerProveedorPorIdPersona(data,event, function($data,$event) {
        //     if($event) {
        //       if($data != null)  {
        //         ko.mapping.fromJS($data,MappingCompra,self);
        //       }
        //
        //       $form.find("#FechaEmision").focus();
        //     }
        // });
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


  self.AplicarExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesCompraGasto().length;
      var ultimoItem = self.DetallesCompraGasto()[total - 1];
      var resultado = "false";
      if (ultimoItem.NombreProducto() === ""
        && (ultimoItem.CostoUnitario() === "" || ultimoItem.CostoUnitario() === "0")) {
        resultado = "true";
      }

      $form.find(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputPrecioUnitario()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputCostoUnitario()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputAfectoIGV()).attr("data-validation-optional", resultado);
      $form.find(ultimoItem.InputPrecioCosto()).attr("data-validation-optional", resultado);
    }
  }

  self.Cerrar = function (data, event) {
    if (event) {

    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $(self.Options.IDModalComprobanteCompra).modal("hide");//"#modalCompraGasto"
      // if (self.callback) self.callback(self,event);
    }
  }

  self.OnKeyEnterForm = function (data, event) {
    if (event) {
      var tecla = event.keyCode || event.which || 0;
      if (event.altKey) {
        if (tecla === 103 || tecla === 71) {//G o g
          self.Guardar(data, event);
        }
      }
    }
    return true;
  }

  self.Show = function (event) {
    if (event) {
      self.showCompraGasto(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showCompraGasto(false);
      self.callback = undefined;
      self.OnClickBtnCerrar(self, event);
    }
  }

  self.OnChangeEstadoPendienteNota = function (data, event) {
    if (event) {

      if (data.EstadoPendienteNota() == ESTADO_PENDIENTE_NOTA.PENDIENTE) {
        $form.find("#combo-almacen").removeAttr("disabled");
        $form.find("#combo-almacen").removeClass("no-tab");
        $form.find("#FechaNotaEntrada").removeAttr("disabled");
        $form.find("#FechaNotaEntrada").removeClass("no-tab");
      }
      else {
        $form.find("#combo-almacen").attr("disabled", "disabled");
        $form.find("#combo-almacen").addClass("no-tab");
        $form.find("#FechaNotaEntrada").attr("disabled", "disabled");
        $form.find("#FechaNotaEntrada").addClass("no-tab");
      }
    }
  }

  self.OnChangeComboAlmacen = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-almacen option:selected").text();
      data.NombreSedeAlmacen(texto);
    }
  }

  self.ValidarFechaNotaEntrada = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.ValidarFechaDetraccion = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.ValidarDocumentoDetraccion = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.CalcularMontosPercepcion = function (data, event) {
    if (event) {

      var total = parseFloatAvanzado(self.Total())
      var tasaPercepcion = parseFloatAvanzado(parseFloatAvanzado(self.TasaPercepcionPorcentaje()) / 100);
      var montoPercepcion = (tasaPercepcion * total).toFixed(2);
      var totalMasPercepcion = parseFloatAvanzado(total + parseFloatAvanzado(montoPercepcion)).toFixed(2)

      self.TasaPercepcion(tasaPercepcion);
      self.MontoPercepcion(montoPercepcion);
      self.TotalMasPercepcion(totalMasPercepcion);
    }
  }

  self.CargarCajas = function (data, event) {
    if (event) {
      $form.find("#combo-caja").empty()
      var cajas = ko.mapping.toJS(self.Cajas());
      var data = ko.mapping.toJS(data);
      var id_caja = data.IdCaja;
      $.each(cajas, function (key, entry) {
        if(self.IdMoneda() == entry.IdMoneda)
        {
          var sel = "";
          if(id_caja != "" || id_caja != null)
          {
            if(id_caja == entry.IdCaja)
            {
              sel = 'selected="true"';
            }
          }
          $form.find("#combo-caja").append($('<option '+sel+'></option>').attr('value', entry.IdCaja).text(entry.NombreCaja));
        }
      })
      self.OnChangeCajas(data,event);
    }
  }

  self.OnChangeCajas = function (data,event) {
    if (event) {
      if (self.IdFormaPago() == ID_FORMA_PAGO_CONTADO) {
        var id = $form.find("#combo-caja option:selected").val()
        self.IdCaja(id);
      }
    }
  }
  self.OnKeyEnterTotales = function(data,event) {
    var resultado = $(event.target).enterToTab(event);
    self.CalcularTotales(event);
    return resultado;
  }

  self.OnChangeIndicadorTipoCalculoIGV = function (data, event) {
    if (event) {
      self.CalcularTotales(event);
    }
  }
}
