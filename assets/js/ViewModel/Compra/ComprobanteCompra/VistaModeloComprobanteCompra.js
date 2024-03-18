
VistaModeloComprobanteCompra = function (data, options) {
  var self = this;
  ko.mapping.fromJS(data, MappingCompra, self);
  // self.parent = ko.observable(self).syncWith("BusquedaDocumentoIngreso");
  self.CheckNumeroDocumento = ko.observable(true);
  self.CalcularMontoTotalACuenta = ko.observable(true);
  self.IndicadorReseteoFormulario = true;
  self.Options = options;
  ModeloComprobanteCompra.call(this, self);
  self.TotalCantidades = ko.observable("0.00");

  var $form = $(options.IDForm);

  self.DivFooterVenta = ko.pureComputed(function () {
    // if (self.ParametroCampoACuenta() == '1') {
    //   return "col-md-2";
    // }
    // else {
    //   return "col-md-4";
    // }
    return "col-md-2";
  });

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.InicializarModelo(event);
      self.FiltrosIngreso.InicializarVistaModelo(data, event, self);
      self.OnChangeTipoDocumento(data, event, false);

      var target = options.IDForm + " " + "#Proveedor";
      $form.find("#Proveedor").autoCompletadoProveedor(event, self.ValidarAutoCompletadoProveedor, target);
      $form.find("#FechaEmision").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $form.find("#FechaVencimiento").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $form.find("#FechaMovimientoAlmacen").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $form.find("#FechaDetraccion").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });

      self.InicializarValidator(event);

      $form.find("#Proveedor").on("focusout", function (event) {
        self.ValidarProveedor(undefined, event);
      });

      $("body")
        //.off("keydown")
        .on("keydown", function (event) {
          return true;
        });

      self.OnRefrescar(data, event, true);
      self.CambiarCampoACuenta(data, event);
      AccesoKey.AgregarKeyOption(options.IDForm, "#btn_Grabar", TECLA_G);

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

      if (self.DetallesComprobanteCompra().length > 0) {
        ko.utils.arrayForEach(self.DetallesComprobanteCompra(), function (el) {
          el.InicializarVistaModelo(event);
        });
      }

      var item = self.DetallesComprobanteCompra.Agregar(undefined, event);
      item.InicializarVistaModelo(event);
      $(item.InputOpcion()).hide();
      $(item.OpcionMercaderia()).hide();
    }
  }

  self.OnChangeCheckDocumentoSalidaZofra = function (data, event) {
    if (event) {
      $form.find("#PanelDocumentoSalidaZofra").resetearValidaciones();
      if (data.CheckDocumentoSalidaZofra() == true || data.CheckDocumentoSalidaZofra() == 'true') {
        // $form.find("#NumeroDocumentoSalidaZofra").prop('disabled', false);
        // $form.find("#NumeroDocumentoSalidaZofra").removeClass('no-tab');
        // $form.find("#NumeroDocumentoSalidaZofra").focus();
        $form.find("#NumeroDocumentoSalidaZofra").attr('data-validation', 'required');
        $form.find("#BtnDocumentoIngreso").attr('disabled', false);
        $form.find("#BtnDocumentoIngreso").removeClass('no-tab');
      }
      else {
        // $form.find("#NumeroDocumentoSalidaZofra").prop('disabled', true);
        // $form.find("#NumeroDocumentoSalidaZofra").addClass('no-tab');
        $form.find("#BtnDocumentoIngreso").attr('disabled', true);
        $form.find("#BtnDocumentoIngreso").addClass('no-tab');
        $form.find("#NumeroDocumentoSalidaZofra").removeAttr('data-validation');
        data.NumeroDocumentoSalidaZofra("");
        data.IdDocumentoIngresoZofra("");
        data.DocumentoIngreso("");
        data.FechaEmisionDocumentoIngreso("");
      }

      if (self.CheckDocumentoSalidaZofra() == true && self.IdTipoDocumento() == self.ParametroTipoDocumentoSalidaZofra() && self.ParametroDocumentoSalidaZofra() == 1) {
        $form.find("#tablaDetalleComprobanteCompra tr:last").find('input, select, button').prop('disabled', true);
        $form.find("#tablaDetalleComprobanteCompra tr:last").find('input, select, button').addClass('no-tab');
      }
      else if (self.CheckDocumentoSalidaZofra() == true && (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_DUA || self.IdTipoDocumento() == self.ParametroTipoDocumentoDuaAlternativo())) {
        $form.find("#tablaDetalleComprobanteCompra tr:last").find('input, select, button').prop('disabled', true);
        $form.find("#tablaDetalleComprobanteCompra tr:last").find('input, select, button').addClass('no-tab');
      }
      else {
        // self.DetallesComprobanteCompra.pop();
        // var ultimaFila = self.DetallesComprobanteCompra.Agregar(undefined, event);
        // ultimaFila.InicializarVistaModelo(event);
        // $(ultimaFila.InputOpcion()).hide();
        // $(ultimaFila.OpcionMercaderia()).hide();
        $form.find("#tablaDetalleComprobanteCompra tr:last").find('input, select, button').prop('disabled', false);
        $form.find("#tablaDetalleComprobanteCompra tr:last").find('input:not([readonly]), select').removeClass('no-tab');
      }
    }
  }

  self.OnChangeFormaPago = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-formapago option:selected").text();
      data.NombreFormaPago(texto);
      self.CambiarCampoACuenta(data, event);
      if (self.IdFormaPago() == ID_FORMA_PAGO_CREDITO) {
        self.IdCaja(null);
      } else {
        self.CargarCajas(data, event);
      }
    }
  }

  self.CambiarCampoACuenta = function (data, event) {
    if (event) {
      var id = $form.find("#combo-formapago").val();
      if (self.ParametroCampoACuenta() == 1) {
        if (id == ID_FORMA_PAGO_CONTADO) {
          $form.find("#ACuenta").prop('readonly', true);
          $form.find("#ACuenta").addClass('no-tab');
          self.MontoACuenta(self.CalcularTotal());
        }
        else {
          $form.find("#ACuenta").prop('readonly', false);
          $form.find("#ACuenta").removeClass('no-tab');
          self.MontoACuenta("0.00");
        }
      }
    }
  }

  self.OnChangeSerieDocumento = function (data, event) {
    if (event) {
      var texto = $form.find("#combo-seriedocumento option:selected").text();
      data.SerieDocumento(texto);
    }
  }

  self.OnChangeTipoDocumento = function (data, event, eventoActivo = true) {
    if (event) {
      //LIMPIAMOS
      var texto = $form.find("#combo-tipodocumento option:selected").text();
      //data.TipoDocumento(texto);
      if (eventoActivo) {
        self.CheckDocumentoSalidaZofra(false);
      }
      self.OnChangeCheckDocumentoSalidaZofra(self, event);

      if (self.ParametroDocumentoSalidaZofra() == 1) {
        var objetoSedes = Knockout.CopiarObjeto(self.CopiaSedes());
        self.Sedes(objetoSedes());
        if (self.IdTipoDocumento() == self.ParametroTipoDocumentoSalidaZofra()) {
          self.Sedes.remove(function (item) { return item.IndicadorAlmacenZofra() == 0; })
        }
        else if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_INGRESO || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_CONTROL) { }//Se dejan todos los almacenes
        else {
          self.Sedes.remove(function (item) { return item.IndicadorAlmacenZofra() == 1; })
        }
      }
      // var copia = ko.mapping.toJS(self.Sedes);
      // console.log(copia);

      if (eventoActivo) {
        if (self.IdTipoDocumento() == self.ParametroTipoDocumentoSalidaZofra()) {
          self.RefrescarDetalleComprobanteCompra(data, event);
        }
        else if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_DUA || self.IdTipoDocumento() == self.ParametroTipoDocumentoDuaAlternativo()) {
          self.RefrescarDetalleComprobanteCompra(data, event);
        }
        else {
          self.RefrescarDetalleComprobanteCompra(data, event);
        }
      }

    }
  }

  self.RefrescarDetalleComprobanteCompra = function (data, event) {
    if (event) {
      self.DetallesComprobanteCompra([]);
      var item = self.DetallesComprobanteCompra.Agregar(undefined, event);
      item.InicializarVistaModelo(event);
      $(item.InputOpcion()).hide();
      $(item.OpcionMercaderia()).hide();
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
      var valorTipoCambio = parseFloatAvanzado(self.ValorTipoCambio());
      if (self.IdMoneda() == ID_MONEDA_DOLARES && valorTipoCambio <= 0) {
        $("#loader").show();
        self.TipoCambio.ObtenerTipoCambio(data, function ($data) {
          $("#loader").hide();
          if ($data) {
            resultado = $data.TipoCambioCompra;
            self.ValorTipoCambio(resultado);
          }
          else {
            alertify.alert(self.titulo, "No se encontro un tipo de cambio para la fecha emision");
          }
        });
        // self.ObtenerTipoCambioActual(data, event, function ($data, $event) {
        //   if ($data.TipoCambio != "") {
        //     self.ValorTipoCambio($data.TipoCambio);
        //   }
        // });
      }
      self.CargarCajas(data, event);
    }
  }

  self.CrearDetalleComprobanteCompra = function (data, event) {
    if (event) {
      if (self.CheckDocumentoSalidaZofra() == true && self.IdTipoDocumento() == self.ParametroTipoDocumentoSalidaZofra() && self.ParametroDocumentoSalidaZofra() == 1) {
        $form.find("#tablaDetalleComprobanteCompra tr:last").find('input, select, button').prop('disabled', true);
        $form.find("#tablaDetalleComprobanteCompra tr:last").find('input, select, button').addClass('no-tab');
        return false;
      }
      if (self.CheckDocumentoSalidaZofra() == true && (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_DUA || self.IdTipoDocumento() == self.ParametroTipoDocumentoDuaAlternativo())) {
        $form.find("#tablaDetalleComprobanteCompra tr:last").find('input, select, button').prop('disabled', true);
        $form.find("#tablaDetalleComprobanteCompra tr:last").find('input, select, button').addClass('no-tab');
        return false;
      }
      if (self.IndicadorReferenciaCostoAgregado().length != '0') {
        return false;
      }

      var $input = $(event.target);
      self.RefrescarBotonesDetalleComprobanteCompra($input, event);
    }
  }

  self.BloquearUltimaFila = function (data, event) {
    if (event) {

    }
  }

  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }
  }

  self.OnRefrescar = function (data, event, esporeliminacion) {
    if (event) {
      if (!$form.hasClass("selector-blocked")) {//'#formComprobanteCompra'
        if (!esporeliminacion) self.CrearDetalleComprobanteCompra(data, event);
        self.CalcularTotales(event);
        self.CalcularTotalACuenta(event);
        self.CalcularMontosPercepcion(data, event);
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
      self.ValidarAutoCompletadoProveedor(ko.mapping.toJS(dataProveedor), event);
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
      self.Editar(self.ComprobanteCompraInicial, event, self.callback);
    }
  }

  self.Limpiar = function (data, event) {
    if (event) {
      self.Nuevo(self.ComprobanteCompraInicial, event, self.callback);
    }
  }

  self.OnVer = function (data, event, callback) {
    if (event) {
      self.Editar(data, event, callback, true);
    }
  }

  self.Nuevo = function (data, event, callback) {
    if (event) {
      $form.resetearValidaciones();//'#formComprobanteCompra'
      if (callback) self.callback = callback;
      self.NuevoComprobanteCompra(data, event);
      self.InicializarVistaModelo(undefined, event);
      self.InicializarVistaModeloDetalle(undefined, event);
      self.OnChangeDetraccion(undefined, event);
      self.CargarCajas(data, event);
      // self.OnChangeCheckDocumentoSalidaZofra(data,event);

      setTimeout(function () {
        $form.find('#Proveedor').focus();
      }, 350);
    }
  }

  self.Editar = function (data, event, callback, blocked) {
    if (event) {
      if (self.IndicadorReseteoFormulario === true) $form.resetearValidaciones();//'#formComprobanteCompra'
      if (callback) self.callback = callback;
      self.EditarComprobanteCompra(data, event);
      self.CalcularMontoTotalACuenta(false);
      self.InicializarVistaModelo(undefined, event);
      self.CargarCajas(data, event);
      self.CheckDetraccion((self.IndicadorDetraccion() == DETRACCION.CON_DETACCION ? true : false));
      $form.find("#Proveedor").attr("data-validation-text-found", self.NumeroDocumentoIdentidad() + " - " + self.RazonSocial());
      self.CopiaIdProductosDetalle([]);
      //self.CalcularMontosPercepcion(data, event);

      $('#loader').show();
      self.ConsultarDetallesComprobanteCompra(data, event, function ($data, $event) {
        self.InicializarVistaModeloDetalle(undefined, event);

        if (self.DetallesComprobanteCompra().length > 0) {
          ko.utils.arrayForEach(self.DetallesComprobanteCompra(), function (item) {
            if (item.IdProducto() != null) self.CopiaIdProductosDetalle.push(item.IdProducto())

            $(item.InputCodigoMercaderia()).attr("data-validation-found", "true");
            $(item.InputCodigoProductoProveedor()).attr("data-validation-found", "true");//prueba
            $(item.InputCodigoMercaderia()).attr("data-validation-text-found", $(item.InputProducto()).val());

          })
        }

        $('#loader').hide();
        setTimeout(function () {
          $form.find('#combo-seriedocumento').focus();
        }, 350);

        $(self.Options.IDPanelHeader).bloquearSelector(blocked);//'#panelheaderComprobanteCompra'
        $form.bloquearSelector(blocked);//'#formComprobanteCompra'

        // self.OnChangeCheckDocumentoSalidaZofra(self, event);

        if (blocked == true) {
          self.OnChangeDetraccion(data, event);
          $(self.Options.IDPanelHeader).bloquearSelector(blocked);
          $form.bloquearSelector(blocked);//'#formCompraGasto'
        }
        else {
          self.OnChangeDetraccion(data, event);
        }
        if (self.IndicadorReferenciaCostoAgregado().length != '0') {
          $form.find("#tablaDetalleComprobanteCompra tr:last").find('input, select, button').prop('disabled', true);
          $form.find("#tablaDetalleComprobanteCompra tr:last").find('input, select, button').addClass('no-tab');
        }
        $form.find('#btn_Cerrar').removeAttr('disabled');
      });
    }
  }

  self.EditarAlternativo = function (data, event, callback, blocked) {
    if (event) {
      $('#loader').show();
      var $formAlternativo = $(options.IDFormAlternativo);
      if (self.IndicadorReseteoFormulario === true) $formAlternativo.resetearValidaciones();
      if (callback) self.callback = callback;
      self.EditarComprobanteCompraAlternativo(data, event);
      // self.InicializarVistaModelo(undefined,event);
      $formAlternativo.find("#Proveedor").attr("data-validation-text-found", self.NumeroDocumentoIdentidad() + "  -  " + self.RazonSocial());
      self.CopiaIdProductosDetalle([]);

      setTimeout(function () {
        $formAlternativo.find('#combo-seriedocumento').focus();
      }, 350);
      $formAlternativo.bloquearSelector(true);

      $formAlternativo.find('#btn_Cerrar').removeAttr('disabled');
      $formAlternativo.find('#btn_Grabar').removeAttr('disabled');
      $formAlternativo.find('#SerieDocumento').removeAttr('disabled');
      $formAlternativo.find('#NumeroDocumento').removeAttr('disabled');
      $formAlternativo.find('#Observacion').removeAttr('disabled');
      $('#loader').hide();
    }
  }

  self.Guardar = function (data, event) {
    if (event) {
      if ($("#loader").is(":visible")) {
        // console.log("PETICIONES MULTIPLES PARADAS");
        return false;
      }

      self.AplicarExcepcionValidaciones(data, event);

      if ($form.isValid() === false) {//"#formComprobanteCompra"
        alertify.alert("Error en Validación", "Existe aun datos inválidos , por favor de corregirlo.", function () {
          setTimeout(function () {
            $form.find('.has-error').find('input').first().focus();
          }, 300);
        });
      }
      else {
        var filtrado = ko.utils.arrayFilter(self.DetallesComprobanteCompra(), function (item) {
          return item.IdProducto() != null;
        });
        if (filtrado.length <= 0) {
          alertify.alert("Validación", "Debe ingresar por lo menos 1 ítem.");
          return false;
        }
        $("#loader").show();
        self.GuardarComprobanteCompra(event, self.PostGuardar);
      }
    }
  }

  self.GuardarAlternativo = function (data, event) {
    if (event) {

      if ($form.isValid() === false) {//"#formComprobanteCompra"
        alertify.alert("Error en Validación", "Existe aun datos inválidos , por favor de corregirlo.", function () {
          setTimeout(function () {
            $form.find('.has-error').find('input').first().focus();
          }, 300);
        });
      }
      else {
        $("#loader").show();
        self.GuardarComprobanteCompraAlternativo(event, self.PostGuardar);
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
      self.AnularComprobanteCompra(data, event, self.PostAnular);
    }
  }

  self.Eliminar = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.EliminarComprobanteCompra(data, event, self.PostEliminar);
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
        // alertify.alert("Eliminacion Comprobante Compra","Se eliminó correctamente");
        if (self.callback != undefined)
          self.callback(resultado.data, event);
      }
      else {
        alertify.alert("Error en Eliminacion de Comprobante Compra", resultado.error);
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
    if (self.BloquearDocumentoZofra() == true) {
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

  self.OnEnableBtnEditarAlternativo = ko.computed(function () {
    if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_INGRESO) {
      return true;
    }
    else if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_CONTROL) {
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

  self.TieneAccesoAnular = ko.observable(self.ValidarEstadoComprobanteCompra(self, window));

  // self.OnChangeCheckNumeroDocumento = function(data,event)  {
  //   if (event)  {
  //     if($form.find("#CheckNumeroDocumento").prop("checked"))  {
  //       $form.find("#NumeroDocumento").attr("readonly", false);
  //       $form.find("#NumeroDocumento").removeClass("no-tab");
  //       $form.find("#NumeroDocumento").attr("data-validation-optional","false");
  //       $form.find("#NumeroDocumento").focus();
  //     }
  //     else {
  //       self.NumeroDocumento("");
  //       $form.find("#NumeroDocumento").attr("data-validation-optional","true");
  //       $form.find("#NumeroDocumento").attr("readonly", true);
  //       $form.find("#NumeroDocumento").addClass("no-tab");
  //       $form.find("#NumeroDocumento").focus();
  //       $form.find("#CheckNumeroDocumento").focus();
  //     }
  //   }
  // }

  self.RefrescarBotonesDetalleComprobanteCompra = function (data, event) {
    if (event) {
      var tamaño = self.DetallesComprobanteCompra().length;
      var indice = data.closest("tr").index();
      if (indice === tamaño - 1) {
        self.RemoverExcepcionValidaciones(data, event);
        var InputOpcion = self.DetallesComprobanteCompra()[indice].InputOpcion();
        $(InputOpcion).show();
        var OpcionMercaderia = self.DetallesComprobanteCompra()[indice].OpcionMercaderia();
        $(OpcionMercaderia).show();
        self.OnAgregarFila(undefined, event);
      }
    }
  }

  self.RemoverExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesComprobanteCompra().length;
      var ultimoItem = self.DetallesComprobanteCompra()[total - 1];
      var resultado = "false";
      $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputCodigoProductoProveedor()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputFechaVencimiento()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroLote()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputCostoUnitario()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputPrecioCosto()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroItemDua()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputDescuentoUnitario()).attr("data-validation-optional", resultado);
    }
  }

  self.OnAgregarFila = function (data, event) {
    if (event) {
      var item = self.DetallesComprobanteCompra.Agregar(undefined, event);
      item.InicializarVistaModelo(event);
      $(item.InputOpcion()).hide();
      $(item.OpcionMercaderia()).hide();

    }
  }

  self.OnQuitarFila = function (data, event) {
    if (event) {
      self.DetallesComprobanteCompra.Remover(data, event);
      var trfilas = $("#tablaDetalleComprobanteCompra").find("tr").find("button:visible");
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
      data.SerieDocumento($(event.target).zFill(data.SerieDocumento(), self.TamanoSerieCompra()));
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
    if (event) {
      self.OnRefrescar(data, event, false);
    }
  }

  self.ValidarFechaEmision = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (valid) self.CalcularTipoCambio(data, event);//self.ValorTipoCambio(self.CalcularTipoCambio(data,event));
        self.FechaMovimientoAlmacen($(event.target).val());

        if (self.IdFormaPago() != ID_FORMA_PAGO_CONTADO) {
          self.FechaVencimiento($(event.target).val());
        }
      });
    }
  }

  self.ValidarFechaMovimientoAlmacen = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.ValidarDocumentoSalida = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.CalcularTipoCambio = function (data, event) {
    if (event) {
      var resultado = 0.00;
      if (self.IdMoneda() != ID_MONEDA_SOLES)
        if (data.ValorTipoCambio() != "0.00" && data.ValorTipoCambio() != 0 && jQuery.isNumeric(data.ValorTipoCambio())) {
          resultado = data.ValorTipoCambio();
        }
        else {
          self.TipoCambio.ObtenerTipoCambio(data, function ($data) {
            if ($data) {
              resultado = $data.TipoCambioCompra;
              self.ValorTipoCambio(resultado);
            }
            else {
              alertify.alert(self.titulo, "No se encontro un tipo de cambio para la fecha emision");
            }
          });
        }
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

  self.OnKeyEnterTotales = function (data, event) {
    var resultado = $(event.target).enterToTab(event);
    self.CalcularTotales(event);
    return resultado;
  }

  self.AplicarExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesComprobanteCompra().length;
      var ultimoItem = self.DetallesComprobanteCompra()[total - 1];
      var resultado = "false";
      if (ultimoItem.CodigoMercaderia() === "" && ultimoItem.NombreProducto() === ""
        && (ultimoItem.Cantidad() === "" || ultimoItem.Cantidad() === "0")
        && (ultimoItem.CostoUnitario() === "" || ultimoItem.CostoUnitario() === "0")
        && (ultimoItem.PrecioUnitario() === "" || ultimoItem.PrecioUnitario() === "0")
        && (ultimoItem.DescuentoUnitario() === "" || ultimoItem.DescuentoUnitario() === "0")
        && (ultimoItem.FechaVencimiento() === "")
        && (ultimoItem.NumeroLote() === "")
        && (ultimoItem.NumeroItemDua() === "")
        && (ultimoItem.CostoItem() === "" || ultimoItem.CostoItem() === "0")
        && (ultimoItem.PrecioItem() === "" || ultimoItem.PrecioItem() === "0")
      ) {
        resultado = "true";
      }

      $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputCodigoProductoProveedor()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputFechaVencimiento()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroLote()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputCostoUnitario()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputPrecioUnitario()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputPrecioCosto()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroItemDua()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputDescuentoUnitario()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputTasaDescuentoUnitario()).attr("data-validation-optional", resultado);
    }
  }

  self.Cerrar = function (data, event) {
    if (event) {

    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $(self.Options.IDModalComprobanteCompra).modal("hide");//"#modalComprobanteCompra"
      // if (self.callback) self.callback(self,event);
    }
  }

  self.OnClickBtnCerrarAlternativo = function (data, event) {
    if (event) {
      $(self.Options.IDModalComprobanteCompraAlternativo).modal("hide");
    }
  }

  self.Show = function (event) {
    if (event) {
      self.showComprobanteCompra(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showComprobanteCompra(false);
      self.callback = undefined;
      self.OnClickBtnCerrar(self, event);
    }
  }

  self.OnChangeEstadoPendienteNota = function (data, event) {
    if (event) {

      if (data.EstadoPendienteNota() == ESTADO_PENDIENTE_NOTA.PENDIENTE) {
        $form.find("#combo-almacen").removeAttr("disabled");
        $form.find("#combo-almacen").removeClass("no-tab");
        $form.find("#FechaMovimientoAlmacen").removeAttr("disabled");
        $form.find("#FechaMovimientoAlmacen").removeClass("no-tab");
      }
      else {
        $form.find("#combo-almacen").attr("disabled", "disabled");
        $form.find("#combo-almacen").addClass("no-tab");
        $form.find("#FechaMovimientoAlmacen").attr("disabled", "disabled");
        $form.find("#FechaMovimientoAlmacen").addClass("no-tab");
      }
    }
  }

  self.OnChangeComboAlmacen = function (data, event, parent) {
    if (event) {

      var texto = $form.find("#combo-almacen option:selected").text();
      data.NombreSedeAlmacen(texto);
      ko.utils.arrayForEach(data.Sedes(), function (item) {
        if (item.NombreSede() == texto) {
          data.IdSede(item.IdSede());
        }
      });

      parent.BusquedaAvanzadaProducto.OnLimpiar(undefined, event);
    }
  }

  self.ValidarFechaMovimientoAlmacen = function (data, event) {
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

  self.OnChangeDetraccion = function (data, event) {
    if (event) {
      if (self.CheckDetraccion() == true) {
        $form.find("#content_detracciones").find("input[type=text], input[type=radio]").removeClass("no-tab");
        $form.find("#content_detracciones").find("input[type=text], input[type=radio]").prop("disabled", false);
        $form.find("#content_detracciones").find("input[type=text],input[type=radio]").removeAttr("tabindex");

        if (data == undefined) {
          self.PagadorDetraccion(PAGADOR_DETRACCION.CLIENTE);
        }
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

  self.ValidarDocumentoDetraccion = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.OnClickBtnNuevaMercaderia = function (data, event, dataMercaderia) {
    if (event) {
      dataMercaderia.OnNuevaMercaderia(dataMercaderia.MercaderiaNueva, event);
      return true;
    }
  }

  self.BuscarDocumentoIngreso = function (data, event) {
    if (event) {
      if ($("#CheckDocumentoSalidaZofra").prop('checked') == true) {
        $(options.IDModalBusquedaDocumentoIngreso).modal('show');

        setTimeout(function () {
          $(options.IDModalBusquedaDocumentoIngreso).find("input").first().focus();
        }, 500);
      }
    }
  }

  self.AgregarDocumentoIngreso = function (data, event) {
    if (event) {
      self.BorrarDetallesComprobanteCompra(data, event);

      var objeto = ko.mapping.toJS(data);
      if (objeto.DetallesComprobanteCompra.length > 0) {
        self.NumeroDocumentoSalidaZofra(objeto.SerieDocumento + " - " + objeto.NumeroDocumento);
        self.DocumentoIngreso(objeto.SerieDocumento + " - " + objeto.NumeroDocumento);
        self.FechaEmisionDocumentoIngreso(objeto.FechaEmisionCopia);
        self.IdDocumentoIngresoZofra(objeto.IdComprobanteCompra);
        self.IdMoneda(objeto.IdMoneda);
        self.ValorTipoCambio(objeto.ValorTipoCambio);

        objeto.DetallesComprobanteCompra.reverse().forEach(function (entry, key) {
          entry.Cantidad = entry.SaldoDocumentoIngreso;
          if (self.IdTipoDocumento() == self.ParametroTipoDocumentoSalidaZofra()) {
            if (entry.Cantidad > 0 && entry.IdOrigenMercaderia == ORIGEN_MERCADERIA.ZOFRA) {
              var detalle = self.DetallesComprobanteCompra.AgregarInverso(entry, event);
              detalle.InicializarVistaModelo(event);
              $(detalle.InputCodigoMercaderia()).attr("data-validation-text-found", entry.NombreProducto);
              $(detalle.InputCodigoMercaderia()).attr("data-validation-found", "true");
            }
          }
          else if (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_DUA || self.IdTipoDocumento() == self.ParametroTipoDocumentoDuaAlternativo()) {
            if (entry.Cantidad > 0 && entry.IdOrigenMercaderia == ORIGEN_MERCADERIA.DUA) {
              var detalle = self.DetallesComprobanteCompra.AgregarInverso(entry, event);
              detalle.InicializarVistaModelo(event);
              $(detalle.InputCodigoMercaderia()).attr("data-validation-text-found", entry.NombreProducto);
              $(detalle.InputCodigoMercaderia()).attr("data-validation-found", "true");
            }
          }
        });

        self.CalcularTotales(event);
        self.CalcularTotalACuenta(event);
      }

      $(options.IDModalBusquedaDocumentoIngreso).modal('hide');

      setTimeout(function () {
        $form.find("#btn_Grabar").focus();
      }, 300);
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

  self.OnClickBtnBuscadorMercaderiaListaSimple = function (data, event, dataBase) {
    if (event) {
      $("#BuscadorMercaderiaListaSimple").modal('show');
      $("#BuscadorMercaderiaListaSimple #spanNombreSede").text(self.NombreSedeAlmacen());
      //dataBase.BusquedaAvanzadaProducto.Mercaderias([]);
      dataBase.BusquedaAvanzadaProducto.InicializarVistaModelo(data, event, self, self.OnClickAgregarMercaderiaImagen);

      setTimeout(function () {
        $("#BuscadorMercaderiaListaSimple").find('input').first().focus();
      }, 500);
    }
  }

  self.OnClickAgregarMercaderiaImagen = function (data, event) {
    if (event) {

      var objeto = ko.mapping.toJS(data);

      var detalleFiltrado = ko.utils.arrayFilter(self.DetallesComprobanteCompra(), function (item) {
        return item.IdProducto() == objeto.IdProducto;
      });

      self.DetallesComprobanteCompra.remove(function (item) { return item.IdProducto() == null; })

      objeto.CostoUnitario = objeto.CostoUnitario == "" || objeto.CostoUnitario == null ? '0' : objeto.CostoUnitario
      objeto.DescuentoItem = objeto.DescuentoItem == "" || objeto.DescuentoItem == null ? '0' : objeto.DescuentoItem
      objeto.DescuentoUnitario = objeto.DescuentoUnitario == "" || objeto.DescuentoUnitario == null ? '0' : objeto.DescuentoUnitario

      if (detalleFiltrado.length > 0) {
        var producto = detalleFiltrado[0]
        var cantidadaDetalleFiltrado = parseFloatAvanzado(producto.Cantidad()) + parseFloatAvanzado(objeto.Cantidad);
        var precioUnitarioDetalleFiltrado = parseFloatAvanzado(producto.PrecioUnitario());
        producto.Cantidad(parseFloatAvanzado(cantidadaDetalleFiltrado));
        producto.SubTotal(parseFloatAvanzado(cantidadaDetalleFiltrado * precioUnitarioDetalleFiltrado));

      } else {
        var detalle = ko.mapping.toJS(self.NuevoDetalleComprobanteCompra);
        objeto = Object.assign(detalle, objeto);
        objeto.Cantidad = objeto.Cantidad == '0' ? '1' : objeto.Cantidad;
        objeto.SubTotal = objeto.Cantidad * objeto.PrecioUnitario;
        var producto = self.DetallesComprobanteCompra.Agregar(objeto, event);
        producto.InicializarVistaModelo(event);

      }

      producto.CalculoSubTotal(data, event);
      $form.find(producto.InputCodigoMercaderia()).attr("data-validation-found", "true");
      $form.find(producto.InputCodigoMercaderia()).attr("data-validation-text-found", $form.find(producto.InputProducto()).val());
      $form.find(producto.InputNumeroLote()).attr("data-validation-found", "false");

      var item = self.DetallesComprobanteCompra.Agregar(undefined, event);
      item.InicializarVistaModelo(event);
      $(item.InputOpcion()).hide();
      $(item.OpcionMercaderia()).hide();
      self.CalcularTotales(event);

    }
  }

  self.OnEnableDescuentoGlobal = ko.computed(function () {
    var cantidaditems = 0, afectoIGVSi = 0, afectoIGVNo = 0;

    if (self.DetallesComprobanteCompra) {
      ko.utils.arrayForEach(self.DetallesComprobanteCompra(), function (item) {
        if (item.IdProducto() != "" && item.IdProducto() != null) {
          cantidaditems++;
          switch (item.AfectoIGV()) {
            case "1":
              afectoIGVSi++;
              break;
            case "0":
              afectoIGVNo++;
              break;
            default:
          }
        }
      });
    }

    if ((afectoIGVSi == cantidaditems) || (afectoIGVNo == cantidaditems)) {
      return true;
    } else {
      // self.DescuentoGlobal(0);
      return false;
    }

  }, this);

  self.CambiosEnElDetale = ko.computed(function () {

    if (self.DetallesComprobanteCompra != undefined) {
      var detalles = self.DetallesComprobanteCompra();
    }
    else {
      var detalles = [];
    }

    var totalCantidad = 0
    ko.utils.arrayForEach(detalles, function (item, key) {
      item.NumeroItem(key + 1);

      if (item.IdProducto() !== null && item.IdProducto() !== "") {
        totalCantidad = parseFloatAvanzado(totalCantidad) + parseFloatAvanzado(item.Cantidad());
      }
    });
    self.TotalCantidades(totalCantidad.toFixed(NUMERO_DECIMALES_VENTA))
  }, this)

  self.OnChangeIndicadorTipoCalculoIGV = function (data, event) {
    if (event) {
      self.CalcularTotales(event);
    }
  }

}
