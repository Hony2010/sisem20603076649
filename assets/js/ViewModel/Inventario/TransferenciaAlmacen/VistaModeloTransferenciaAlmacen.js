
VistaModeloTransferenciaAlmacen = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingTransferenciaAlmacen, self);

  self.IndicadorReseteoFormulario = true;

  self.idForm = "#formTransferenciaAlmacen";
  self.idModal = "#modalTransferenciaAlmacen";

  ModeloTransferenciaAlmacen.call(this, self);

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.InicializarModelo(event);
      AccesoKey.AgregarKeyOption(self.idForm, "#btn_Grabar", 71);
      $("#FechaTraslado").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });

      self.InicializarValidator(event);
      //self.OnChangeAlmacen(data, event);
    }
  }

  self.InicializarValidator = function (event) {
    if (event) {

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
      self.DetallesTransferenciaAlmacen([]);
      if (self.DetallesTransferenciaAlmacen().length > 0) {
        ko.utils.arrayForEach(self.DetallesTransferenciaAlmacen(), function (el) {
          el.InicializarVistaModelo(event, self.PostBusquedaProducto, self.CrearTransferenciaAlmacen);//if (indice == 0) item = Knockout.CopiarObjeto(el);
        });
      }

      var item = self.DetallesTransferenciaAlmacen.Agregar(undefined, event);
      item.InicializarVistaModelo(event, self.PostBusquedaProducto, self.CrearTransferenciaAlmacen);
      $(item.InputOpcion()).hide();
      //self.Seleccionar(item,event);
    }
  }

  self.OnChangeSerieTransferencia = function (data, event) {
    if (event) {
      var texto = $("#combo-seriedocumento option:selected").text();
      data.SerieTransferencia(texto);
    }
  }

  self.OnChangeSedeOrigen = function (data, event) {
    if (event) {
      var texto = $("#combo-motivoTransferenciaAlmacenOrigen option:selected").text();
      var index = $("#combo-motivoTransferenciaAlmacenOrigen")[0].selectedIndex;
      var codigo = self.Sedes()[index].CodigoSede();
      data.NombreSedeOrigen(texto);
      data.CodigoSedeOrigen(codigo);
    }
  }

  self.OnChangeSedeDestino = function (data, event) {
    if (event) {
      var texto = $("#combo-motivoTransferenciaAlmacenDestino option:selected").text();
      var index = $("#combo-motivoTransferenciaAlmacenDestino")[0].selectedIndex;
      var codigo = self.Sedes()[index].CodigoSede();
      data.NombreSedeDestino(texto);
      data.CodigoSedeDestino(codigo);
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

  self.CrearTransferenciaAlmacen = function (data, event) {
    if (event) {
      var $input = $(event.target);
      self.RefrescarBotonesDetalleTransferenciaAlmacen($input, event);
    }
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      var id = "#" + data.IdTransferenciaAlmacen();
      $(id).addClass('active').siblings().removeClass('active');
      self.SeleccionarDetalleTransferenciaAlmacen(data, event);
      self.DetallesTransferenciaAlmacen.Actualizar(undefined, event);
      // $("#nletras").autoDenominacionMoneda(self.Total());
    }
  }

  self.Deshacer = function (data, event) {
    if (event) {
      self.Editar(self.TransferenciaAlmacenInicial, event, self.callback);
    }
  }

  self.Limpiar = function (data, event) {
    if (event) {
      $("#ParseExcel").val("");
      self.Nuevo(self.TransferenciaAlmacenInicial, event, self.callback);
    }
  }

  self.Nuevo = function (data, event, callback) {
    if (event) {
      $(self.idForm).resetearValidaciones();
      if (callback) self.callback = callback;
      self.NuevoTransferenciaAlmacen(data, event);
      self.InicializarVistaModelo(undefined, event);
      self.InicializarVistaModeloDetalle(undefined, event);
      /*
      setTimeout( function()  {
          $('#combo-almacen').focus();
        },350);*/
    }
  }

  self.Ver = function (data, event, callback) {
    if (event) {
      $(self.idForm).disabledElments($(self.idForm), true);
      self.Editar(data, event, callback, true);
    }
  }

  self.Editar = function (data, event, callback, ver = false) {
    if (event) {
      if (self.IndicadorReseteoFormulario === true) $(self.idForm).resetearValidaciones();
      if (callback) self.callback = callback;
      self.EditarTransferenciaAlmacen(data, event);
      self.InicializarVistaModelo(undefined, event);

      self.ConsultarDetallesTransferencia(data, event, ver);
    }
  }

  self.ConsultarDetallesTransferencia = function (data, event, ver = false) {
    if (event) {
      $("#loader").show();
      self.ConsultarDetallesTransferenciaAlmacen(data, event, function ($data) {
        $("#loader").hide();

        self.DetallesTransferenciaAlmacen([]);
        ko.utils.arrayForEach($data, function (item) {
          var resultado = self.DetallesTransferenciaAlmacen.Agregar(item, event);
          resultado.InicializarVistaModelo(event)

          $(self.idForm).find(resultado.InputCodigoMercaderia()).attr("data-validation-text-found", item.NombreProducto);
          $(self.idForm).find(resultado.InputCodigoMercaderia()).attr("data-validation-found", "true");
        });

        self.DetallesTransferenciaAlmacen.Agregar(undefined, event);
        $(self.idForm).disabledElments($(self.idForm), !ver ? false : true);

        setTimeout(function () {
          $(self.idForm).find('#combo-seriedocumento').focus();
        }, 350);
      })
    }
  }

  self.Guardar = function (data, event) {
    if (event) {

      self.AplicarExcepcionValidaciones(data, event);

      var validar_duplicado = self.ValidarDuplicados(data, event);
      if (validar_duplicado == false) {
        alertify.alert("Por favor hay codigos duplicados en sus nuevos registro. Verifique y modifique.");
        return false;
      }

      if ($(self.idForm).isValid() === false) {
        alertify.alert("Error en Validación", "Existe aun datos inválidos , por favor de corregirlo.");
      }
      else {
        var filtrado = ko.utils.arrayFilter(self.DetallesTransferenciaAlmacen(), function (item) {
          return item.IdProducto() != null;
        });
        if (filtrado.length <= 0) {
          alertify.alert("VALIDACIÓN!", "Debe existir un registro completo para proceder.");
          return false;
        }

        alertify.confirm(self.titulo, "¿Desea guardar los cambios?", function () {
          $("#loader").show();
          self.GuardarTransferenciaAlmacen(event, self.PostGuardar);
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
        alertify.alert("Transferencia Almacen", "Se Guardaron Correctamente los Datos.", function () {
          //data_mercaderia = ObtenerJSONCodificadoDesdeURL(url_json);
          if (self.callback) self.callback(data, event);
          // data_mercaderia = self.DataMercaderiaPorOrigenMercaderia(event);
          alertify.alert().destroy();
        });

      }
    }
  }


  self.Anular = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.AnularTransferenciaAlmacen(data, event, self.PostAnular);
    }
  }

  self.Eliminar = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.EliminarTransferenciaAlmacen(data, event, self.PostEliminar);
    }
  }

  self.PostAnular = function (data, event) {
    if (event) {
      alertify.alert("Anulación de Transferencia Almacen", "Se anuló correctamente!", function () {
        if (self.callback != undefined) self.callback(data, event);
      });
    }
  }

  self.PostEliminar = function (data, event) {
    if (event) {
      if (self.callback != undefined) self.callback(data, event);
    }
  }

  self.TieneAccesoEditar = ko.computed(function () {
    if (self.IndicadorEstado() == "N") {
      return true;
    } else {
      return false;
    }
  }, this);

  self.TieneAccesoAnular = ko.computed(function () {
    if (self.IndicadorEstado() == "N") {
      return true;
    } else {
      return false;
    }
  }, this);

  self.TieneAccesoEliminar = ko.computed(function () {
    if (self.IndicadorEstado() == "N") {
      return true;
    } else {
      return false;
    }
  }, this);


  self.RefrescarBotonesDetalleTransferenciaAlmacen = function (data, event) {
    if (event) {
      var tamaño = self.DetallesTransferenciaAlmacen().length;
      var indice = data.closest("tr").index();
      if (indice === tamaño - 1) {
        self.RemoverExcepcionValidaciones(data, event);
        var InputOpcion = self.DetallesTransferenciaAlmacen()[indice].InputOpcion();
        $(InputOpcion).show();
        self.AgregarDetalleTransferenciaAlmacen(undefined, event);
      }
    }
  }

  self.RemoverExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesTransferenciaAlmacen().length;
      var ultimoItem = self.DetallesTransferenciaAlmacen()[total - 1];
      var resultado = "false";

      $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputValorUnitario()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputFechaVencimientoLote()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroLote()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroDocumentoSalidaZofra()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroDua()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroItemDua()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputFechaEmisionDocumentoSalidaZofra()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputFechaEmisionDua()).attr("data-validation-optional", resultado);
    }
  }

  self.AgregarDetalleTransferenciaAlmacen = function (data, event) {
    if (event) {
      var item = self.DetallesTransferenciaAlmacen.Agregar(undefined, event);
      item.InicializarVistaModelo(event, self.PostBusquedaProducto, self.CrearTransferenciaAlmacen);
      $(item.InputOpcion()).hide();
    }
  }

  self.QuitarDetalleTransferenciaAlmacen = function (data, event) {
    if (event) {
      var tr = $(data.InputProducto()).closest("tr");

      self.DetallesTransferenciaAlmacen.Remover(data, event);
    }
  }

  self.OnFocusOutNumeroTransferencia = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {

      });

      //data.NumeroDocumento($(event.target).zFill(data.NumeroDocumento(), 8));  
    }
  }

  self.OnFocusOutFechaTraslado = function (data, event) {
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
      self.DetallesTransferenciaAlmacen.Actualizar(undefined, event);
      // $("#nletras").autoDenominacionMoneda(self.Total());
      if (callback) callback(data, event);
    }
  }

  self.AplicarExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var total = self.DetallesTransferenciaAlmacen().length;
      var ultimoItem = self.DetallesTransferenciaAlmacen()[total - 1];
      var resultado = "false";
      if (ultimoItem.CodigoMercaderia() === "" && ultimoItem.NombreProducto() === ""
        && (ultimoItem.Cantidad() === "" || ultimoItem.Cantidad() === "0")
        && (ultimoItem.ValorUnitario() === "" || ultimoItem.ValorUnitario() === "0")
        && (ultimoItem.FechaVencimientoLote() === "")
        && (ultimoItem.NumeroLote() === "")
        && (ultimoItem.NumeroDocumentoSalidaZofra() === "")
        && (ultimoItem.NumeroDua() === "")
        && (ultimoItem.NumeroItemDua() === "" || ultimoItem.NumeroItemDua() === "0")
        && (ultimoItem.FechaEmisionDocumentoSalidaZofra() === "")
        && (ultimoItem.FechaEmisionDua() === "")
      ) {
        resultado = "true";
      }

      $(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputValorUnitario()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputFechaVencimientoLote()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroLote()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroDocumentoSalidaZofra()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroDua()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputNumeroItemDua()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputFechaEmisionDocumentoSalidaZofra()).attr("data-validation-optional", resultado);
      $(ultimoItem.InputFechaEmisionDua()).attr("data-validation-optional", resultado);
    }
  }

  self.ValidarDuplicados = function (data, event) {
    if (event) {
      var objeto = ko.mapping.toJS(self.DetallesTransferenciaAlmacen());
      var detalles_nuevos = objeto.filter(function (value) { return value.IdProducto == "-" });

      // var codigos = [];
      // detalles_nuevos.forEach(function(entry, key){
      //   codigos.push(parseInt(entry.CodigoMercaderia));
      // });
      // var filtro_codigo = codigos.filter(function(v,i,o){if(i>=0 && v!==o[i-1]) return v;});
      var filtro_codigo = removeDuplicates(detalles_nuevos, 'CodigoMercaderia');

      if (filtro_codigo.length != detalles_nuevos.length) {
        return false;
      }

      return true;
    }
  }

  self.Cerrar = function (data, event) {
    if (event) {

    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $("#modalCliente").modal("hide");
      if (self.callback) self.callback(self, event);
    }
  }

  self.Hide = function (data, event) {
    if (event) {
      self.OnClickBtnCerrar(data, event)
    }
  }
}
