InventariosInicialModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

}

InventarioInicialModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.InicializarVistaModelo = function (event) {
    if (event) {
      var data = { id: "#NombreProducto", TipoVenta: TIPO_VENTA.MERCADERIAS };
      if (self.IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA) {
        $("#NombreProducto").autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.ZOFRA);
      }
      else if (self.IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA) {
        $("#NombreProducto").autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.DUA);
      }
      else {
        $("#NombreProducto").autoCompletadoProducto(data, event, self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.GENERAL);
      }
      // $("#NombreProducto").autoCompletadoProducto(data,event,self.ValidarAutocompletadoProducto);
      $("#FechaInventario").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $("#FechaVencimiento").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $("#FechaEmisionDocumentoSalidaZofra").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $("#FechaEmisionDua").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });

      self.InicializarValidator(event);

      $("#NombreProducto").on("focusout", function (event) {
        self.ValidarNombreProducto(undefined, event);
      });
    }
  }

  self.InicializarValidator = function (event) {
    if (event) {

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

  self.OnKeyEnterCodigoMercaderia = function (data, event) {
    if (event) {
      if (event.keyCode === TECLA_ENTER) {

        data.CodigoMercaderia($(event.target).val());

        self.ValidarProductoPorCodigo(data, event, function ($data, $event, $valid) {
          var $evento = { target: '#NombreProducto' };
          self.procesado = true;
          self.ValidarNombreProducto(data, $evento);
          self.procesado = false;
          self.FocusNextAutocompleteProducto(event);
        });
      }
      return true;
    }
  }

  self.ValidarNombreProducto = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.OnChangeIdOrigenMercaderia = function (data, event) {
    if (event) {
      self.InicializarVistaModelo(event);
      $("#formInventarioInicial").resetearValidaciones();

      self.FechaEmisionDua('');
      self.NumeroDua('');
      self.NumeroItemDua('');
      self.FechaEmisionDocumentoSalidaZofra('');
      self.NumeroDocumentoSalidaZofra('');
      self.NombreProducto('');
      self.CodigoMercaderia('');
      $("#CodigoMercaderiaInventario").attr("data-validation-found", "false");
      $("#CodigoMercaderiaInventario").attr("data-validation-text-found", "");

    }
  }

  self.ValidarAutocompletadoProducto = function (data, event) {
    if (event) {
      if (data === -1) {
        var $evento = { target: "#NombreProducto" };
        if ($("#CodigoMercaderiaInventario").attr("data-validation-text-found") === $("#NombreProducto").val()) {
          $("#CodigoMercaderiaInventario").attr("data-validation-found", "true");
          self.ValidarNombreProducto(data, $evento);
        }
        else {
          $("#CodigoMercaderiaInventario").attr("data-validation-text-found", "");
          $("#CodigoMercaderiaInventario").attr("data-validation-found", "false");
          self.ValidarNombreProducto(data, $evento);
        }
        self.FocusNextAutocompleteProducto($evento);
      }
      else {
        if ($("#CodigoMercaderiaInventario").attr("data-validation-text-found") === $("#NombreProducto").val()) {
          $("#CodigoMercaderiaInventario").attr("data-validation-found", "true");
          var $evento = { target: "#NombreProducto" };
          self.ValidarNombreProducto(data, $evento);
          self.FocusNextAutocompleteProducto($evento);
        }
        else {
          $("#CodigoMercaderiaInventario").attr("data-validation-text-found", data.NombreProducto);
          $("#CodigoMercaderiaInventario").attr("data-validation-found", "true");
          var $evento = { target: "#NombreProducto" };
          self.ValidarNombreProducto(data, $evento);
          var $evento2 = { target: "#CodigoMercaderiaInventario" };
          var $data = Knockout.CopiarObjeto(data);

          self.ValidarProductoPorCodigo($data, $evento2, function ($data3, $evento3) {
            self.FocusNextAutocompleteProducto($evento);
          });
        }
      }
    }
  }

  self.FocusNextAutocompleteProducto = function (event) {
    if (event) {
      var $inputProducto = $(event.target);
      var pos = $inputProducto.closest("form").find("input, select").not(".no-tab").index($inputProducto);
      $inputProducto.closest("form").find("input, select").not(".no-tab").eq(pos + 1).focus();
    }
  }

  self.ValidarProductoPorCodigo = function (data, event, $callback) {
    if (event) {
      // var datajs = "";
      var codigo = "";
      var url_json = "";
      var _busqueda = "IdProducto";

      // datajs = { CodigoMercaderia : data.CodigoMercaderia(), IdGrupoProducto : TipoVenta };
      codigo = data.CodigoMercaderia();
      url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      _busqueda = "CodigoMercaderia";

      var $input = $("#CodigoMercaderiaInventario");
      var json = ObtenerJSONCodificadoDesdeURL(url_json);

      var opcionExtra = "";
      if (self.IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA) {
        opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.ZOFRA + '"';
      }
      else if (self.IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA) {
        opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.DUA + '"';
      }
      else {
        opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.GENERAL + '"';
      }

      codigo = codigo.toUpperCase();

      var queryBusqueda = '//*[CodigoMercaderia="' + codigo + '" ' + opcionExtra + ']';

      var rpta = JSON.search(json, queryBusqueda);
      if (rpta.length > 0) {
        var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
        var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
        $input.attr("data-validation-found", "true");
        $input.attr("data-validation-text-found", producto[0].NombreProducto);
        // var item =self.Reemplazar(rpta[0]);
        ko.mapping.fromJS(producto[0], {}, self);
        // var $InputPrecioUnitario = $(self.InputPrecioUnitario());
      }
      else {
        var item = null;
        $input.attr("data-validation-found", "false");
        $input.attr("data-validation-text-found", "");
      }

      $(event.target).validate(function (valid, elem) {
        if ($callback) $callback(rpta[0], event, valid);
      });
    }
  }


  self.OnFocus = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      if (callback) callback(data, event);
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }

  self.OnNuevaInventarioInicial = function (data, event) {
    if (event) {
      ko.mapping.fromJS(data, Mapping, self);

      $("#CodigoInventarioInicial").attr('disabled', true);

      $('#FormularioInventarioInicial').modal('show');
    }
  }

  self.InsertarNuevaInventarioInicial = function (data, event) {
    if (event) {
      var objeto = self;
      var _data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({ "Data": data });
      $("#loader").show();

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/InventarioInicial/cInventarioInicial/InsertarInventarioInicial',
        success: function (data) {
          console.log(data);
          if (!data.error) {
            $("#loader").hide();
            alertify.confirm("REGISTRO DE INVENTARIO INICIALS", "Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function () {
              ko.mapping.fromJS(_data.InventarioInicialNueva, Mapping, objeto);
            }, function () {
              $('#FormularioInventarioInicial').modal('hide');

            });
          }
          else {
            $("#loader").hide();
            alertify.alert("ERROR EN REGISTRO DE INVENTARIO INICIALS", data.error.msg, function () { });
          }

        },
        error: function (jqXHR, textStatus, errorThrown) {
          var $data = { error: { msg: jqXHR.responseText } };
          $("#loader").hide();
          alertify.alert("Error en " + self.titulo, $data.error.msg, function () { });
        }
      });
    }
  }

  self.OnClickBtnCerrar = function (event) {
    if (event) {
      $('#FormularioInventarioInicial').modal('hide');
    }
  }
}

var Mapping = {
  'InventariosInicial': {
    create: function (options) {
      if (options)
        return new InventariosInicialModel(options.data);
    }
  },
  'InventarioInicial': {
    create: function (options) {
      if (options)
        return new InventarioInicialModel(options.data);
    }
  },
  'NuevaInventarioInicial': {
    create: function (options) {
      if (options)
        return new InventarioInicialModel(options.data);
    }
  },
  'Mercaderia': {
    create: function (options) {
      if (options)
        return new MercaderiaModel(options.data);
    }
  }
}

IndexInventarioInicial = function (data) {
  var _modo_nuevo = false;
  var _opcion_guardar = 1;
  var _objeto;
  var self = this;
  self.MostrarTitulo = ko.observable("");
  self.copiatextofiltro = ko.observable("");
  self.copiasedefiltro = ko.observable("");
  self.IndicadorAlmacenZofra = ko.observable(0);

  ko.mapping.fromJS(data, Mapping, self);

  self.InicializarVistaModelo = function (event) {
    if (event) {
      $("#FechaMovimiento").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      self.copiasedefiltro(ViewModels.data.Filtros.IdAsignacionSede());
      var detalles = ko.mapping.toJS(self.data.InventariosInicial);
      if (detalles.length > 0) {
        self.OnChangeAlmacen(self.data.Filtros, event);
        self.data.Filtros.FechaMovimiento(detalles[0].FechaInicial);
      }

    }
  }

  self.IndicadorAlmacenZofraVista = ko.computed(function () {
    var resultado = self.IndicadorAlmacenZofra();
    return resultado;
  }, this);

  self.OnChangeAlmacen = function (data, event) {
    if (event) {
      //debugger;
      var texto=$("#combo-almacen option:selected").text();
      //self.NombreAlmacen(texto);      
      ko.utils.arrayForEach(self.data.Sedes(), function (item) {
        if (item.NombreSede() == texto) {
          self.data.InventarioInicial.IdSede(item.IdSede());
        }          
      });

      self.copiatextofiltro(data.textofiltro());
      self.copiasedefiltro(data.IdAsignacionSede());
      if (ViewModels.data.InventarioInicial.ParametroDocumentoSalidaZofra() == 1) {
        var dataAlmacen = ko.mapping.toJS(ViewModels.data.Sedes);
        busqueda = JSPath.apply('.{.IdAsignacionSede == $Texto}', dataAlmacen, { Texto: data.IdAsignacionSede() });
        if (busqueda.length > 0) {
          if (busqueda[0].IndicadorAlmacenZofra == 1) {
            self.IndicadorAlmacenZofra(1);
          }
          else {
            self.IndicadorAlmacenZofra(0);
          }
        }
      }

      self.ConsultarInventariosInicial(data, event, self.PostConsultar);
    }
  }

  self.ValidarFechaMovimiento = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {

      });
    }
  }

  self.ConsultarPorPagina = function (data, event) {
    if (event) {
      self.ConsultarInventariosInicialPorPagina(data, event, self.PostConsultarPorPagina);
      $("#Paginador").pagination("drawPage", data.pagina);
    }
  }

  self.PostConsultarPorPagina = function (data, event) {
    if (event) {
      self.data.InventariosInicial([]);
      ko.utils.arrayForEach(data, function (item) {
        self.data.InventariosInicial.push(new InventariosInicialModel(item));
      });


      var detalles = ko.mapping.toJS(self.data.InventariosInicial);
      if (detalles.length > 0) {
        var objeto = ViewModels.data.InventariosInicial()[0];
        ViewModels.Seleccionar(objeto, event);
        self.data.Filtros.FechaMovimiento(detalles[0].FechaInicial);
      }

    }
  }

  self.ConsultarInventariosInicialPorPagina = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = ko.mapping.toJS({ "Data": data });
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: datajs,
        cache: false,
        url: SITE_URL + '/Inventario/InventarioInicial/cEdicionInventarioInicial/ConsultarInventariosInicialPorPagina',
        success: function (data) {
          $("#loader").hide();
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert("HA OCURRIDO UN ERROR", jqXHR.responseText);
        }
      });
    }
  }

  self.Consultar = function (data, event) {
    if (event) {
      var tecla = event.keyCode ? event.keyCode : event.which;
      if (tecla == TECLA_ENTER) {
        self.copiatextofiltro(data.textofiltro());
        self.copiasedefiltro(data.IdAsignacionSede());
        var inputs = $(event.target).closest('form').find(':input:visible');
        inputs.eq(inputs.index(event.target) + 1).focus();

        self.ConsultarInventariosInicial(data, event, self.PostConsultar);
      }
    }
  }

  self.ConsultarInventariosInicial = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = ko.mapping.toJS({ "Data": data });
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: datajs,
        url: SITE_URL + '/Inventario/InventarioInicial/cEdicionInventarioInicial/ConsultarInventariosInicial',
        success: function (data) {
          $("#loader").hide();
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert("HA OCURRIDO UN ERROR", jqXHR.responseText);
        }
      });
    }
  }

  self.PostConsultar = function (data, event) {
    if (event) {
      self.data.InventariosInicial([]);
      ko.utils.arrayForEach(data.resultado, function (item) {
        self.data.InventariosInicial.push(new InventariosInicialModel(item));
      });

      //ko.mapping.fromJS(data.Filtros,{},self.data.Filtros);
      $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);
      self.data.Filtros.totalfilas(data.Filtros.totalfilas);

      var detalles = ko.mapping.toJS(self.data.InventariosInicial);
      if (detalles.length > 0) {
        var objeto = ViewModels.data.InventariosInicial()[0];
        ViewModels.Seleccionar(objeto, event);
        self.data.Filtros.FechaMovimiento(detalles[0].FechaInicial);
      }
    }
  }

  self.CambiarFechaInventario = function (data, event) {
    if (event) {
      alertify.confirm("ADVERTENCIA!!", "¿Esta seguro de cambiar la fecha para su inventario?", function () {
        $("#loader").show();
        var datajs = ko.mapping.toJS({ "Data": data }, mappingIgnore);
        $.ajax({
          type: 'POST',
          data: datajs,
          dataType: "json",
          url: SITE_URL + '/Inventario/InventarioInicial/cEdicionInventarioInicial/ActualizarFechaInventariosInicial',
          success: function (data) {
            console.log(data);
            if (data) {
              alertify.alert("CORRECTO", "Se actualizo correctamente la fecha.");
              ko.utils.arrayForEach(ViewModels.data.InventariosInicial(), function (item) {
                item.FechaInicial(ViewModels.data.Filtros.FechaMovimiento());
              });
            }
            $("#loader").hide();
          },
          error: function (jqXHR, textStatus, errorThrown) {
            var $data = { error: { msg: jqXHR.responseText } };
            $("#loader").hide();
            alertify.alert("Error en proceso.", $data.error.msg, function () { });
          }
        });
      }, function () {
      });
    }
  }

  self.SeleccionarAnterior = function (data) {
    var id = "#" + data.IdInventarioInicial();
    var anteriorObjeto = $(id).prev();

    anteriorObjeto.addClass('active').siblings().removeClass('active');

    if (_modo_nuevo == false) {
      var match = ko.utils.arrayFirst(self.data.InventariosInicial(), function (item) {
        return anteriorObjeto.attr("id") == item.IdInventarioInicial();
      });
    }
  }

  self.SeleccionarSiguiente = function (data) {
    var id = "#" + data.IdInventarioInicial();
    var siguienteObjeto = $(id).next();

    if (siguienteObjeto.length > 0) {
      siguienteObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false) //revisar
      {
        var match = ko.utils.arrayFirst(self.data.InventariosInicial(), function (item) {
          return siguienteObjeto.attr("id") == item.IdInventarioInicial();
        });
      }
    }
    else {
      self.SeleccionarAnterior(data);
    }
  }

  self.Seleccionar = function (data, event) {
    if (data != undefined) {
      var id = "#" + data.IdInventarioInicial();
      $(id).addClass('active').siblings().removeClass('active');

      var objeto = Knockout.CopiarObjeto(data);
      _objeto = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(objeto, Mapping, ViewModels.data.InventarioInicial);
    }
  }

  self.GuardarActualizar = function (data, event) {
    if (event) {
      var fila_objeto = ko.utils.arrayFirst(ViewModels.data.InventariosInicial(), function (item) {
        return ViewModels.data.InventarioInicial.IdInventarioInicial() == item.IdInventarioInicial();
      });

      var objeto = Knockout.CopiarObjeto(ViewModels.data.InventarioInicial);
      var objeto2 = ko.mapping.fromJS(ViewModels.data.InventarioInicial);
      ViewModels.data.InventariosInicial.replace(fila_objeto, objeto);

      self.Seleccionar(ViewModels.data.InventarioInicial);
      $("#modalInventarioInicial").modal("hide");

      $("#loader").hide();
    }
  }

  self.GuardarInsertar = function (data, event) {
    if (event) {
      var data = ko.mapping.toJS(data);
      var busqueda = JSPath.apply('.{.IdUnidadMedida == $Texto}', data.objeto.UnidadesMedida, { Texto: data.objeto.IdUnidadMedida });
      data.objeto.NombreUnidadMedida = busqueda[0].NombreUnidadMedida;
      var objeto = new InventarioInicialModel(data.objeto);
      // self.SeleccionarSiguiente(objeto);

      var filas = ViewModels.data.InventariosInicial().length;
      self.data.Filtros.totalfilas(data.Filtros.totalfilas);

      var numeropagina = $("#Paginador").pagination('getCurrentPage');
      ko.mapping.toJS(data.Filtros, {}, ViewModels.data.Filtros);
      var objeto_js = ko.mapping.toJS(ViewModels.data.Filtros);
      $("#Paginador").paginador(objeto_js, self.ConsultarPorPagina);

      var objetoInventario = ko.mapping.toJS(ViewModels.data.InventariosInicial);
      var busqueda2 = [];
      if (data.objeto.IdOrigenMercaderia == ORIGEN_MERCADERIA.GENERAL) {
        busqueda2 = JSPath.apply('.{.IdProducto == $Texto}', objetoInventario, { Texto: data.objeto.IdProducto });
      }
      else if (data.objeto.IdOrigenMercaderia == ORIGEN_MERCADERIA.ZOFRA) {
        busqueda2 = JSPath.apply('.{.IdProducto == $Texto && .NumeroDocumentoSalidaZofra == $Origen}', objetoInventario, { Texto: data.objeto.IdProducto, Origen: data.objeto.NumeroDocumentoSalidaZofra });
      }
      else if (data.objeto.IdOrigenMercaderia == ORIGEN_MERCADERIA.DUA) {
        busqueda2 = JSPath.apply('.{.IdProducto == $Texto && .NumeroDua == $Origen}', objetoInventario, { Texto: data.objeto.IdProducto, Origen: data.objeto.NumeroDua });
      }

      if (filas >= 10) {
        if (busqueda2.length > 0) {
          var objetoOld = ko.utils.arrayFirst(ViewModels.data.InventariosInicial(), function (item) {
            return item.IdProducto() == data.objeto.IdProducto;
          });
          ViewModels.data.InventariosInicial.replace(objetoOld, objeto);
        }
        else {
          $("#Paginador").paginador(objeto_js, self.ConsultarPorPagina);
          var ultimo = $("#Paginador ul li:last").prev();
          ultimo.children("a").click();
        }
      }
      else {
        if (busqueda2.length > 0) {
          var objetoOld = ko.utils.arrayFirst(ViewModels.data.InventariosInicial(), function (item) {
            return item.IdProducto() == data.objeto.IdProducto;
          });
          ViewModels.data.InventariosInicial.replace(objetoOld, objeto);
        }
        else {
          $("#Paginador").pagination("drawPage", numeropagina);
          ViewModels.data.InventariosInicial.push(objeto);
          self.Seleccionar(objeto);
        }
      }
      self.Seleccionar(ViewModels.data.InventarioInicial);
      $("#modalInventarioInicial").modal("hide");
      $("#loader").hide();

    }
  }

  self.PreGuardar = function (data, event) {
    if (event) {
      if ($("#formInventarioInicial").isValid() === false) {//"#formComprobanteVenta"
        alertify.alert("Error en Validación", "Existe aun datos inválidos , por favor de corregirlo.", function () {
          setTimeout(function () {
            $("#formInventarioInicial").find('.has-error').find('input').first().focus();
          }, 300);
        });
      }
      else {
        self.Guardar(data, event);
      }
    }
  }

  self.Guardar = function (data, event) {
    if (event) {
      var accion = "";
      $("#loader").show();
      if (_opcion_guardar != 0) {
        accion = "ActualizarInventario";
      }
      else {
        accion = "InsertarMercaderiaInventarioInicial";
      }
      var _data = data;
      var copiaObjeto = ko.mapping.toJS(ViewModels.data.InventarioInicial, mappingIgnore);
      var datajs = { Data: JSON.stringify(copiaObjeto), "Filtro": self.copiatextofiltro(), 'Sede': self.copiasedefiltro() };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/InventarioInicial/cInventarioInicial/' + accion,
        success: function (data) {
          console.log(data);
          $("#loader").hide();
          if (_opcion_guardar != 0) {
            if (data == "") {
              ko.mapping.fromJS(_data, Mapping, ViewModels.data.InventarioInicial);
              self.GuardarActualizar(data, event);
            }
            else {
              alertify.alert("ERROR EN " + self.titulo, data.error.msg);
              $("#CodigoInventarioInicial").focus();
            }
          }
          else {
            if (!data.error) {
              ko.mapping.fromJS(data.objeto, {}, ViewModels.data.InventarioInicial);
              self.GuardarInsertar(data, event);
            }
            else {
              alertify.alert("Error en " + self.titulo, data.error.msg, function () { });
              $("#CodigoInventarioInicial").focus();
            }
          }

        },
        error: function (jqXHR, textStatus, errorThrown) {
          var $data = { error: { msg: jqXHR.responseText } };
          $("#loader").hide();
          alertify.alert("Error en " + self.titulo, $data.error.msg, function () { });
        }
      });
    }

  }

  self.OnChangeIdOrigenMercaderia = function (data, event) {
    if (event) {
      $("#formMercaderiaInventarioInicial").find("#FechaEmisionDocumentoSalidaZofra").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $("#formMercaderiaInventarioInicial").find("#FechaEmisionDua").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      data.FechaEmisionDua('');
      data.NumeroDua('');
      data.NumeroItemDua('');
      data.FechaEmisionDocumentoSalidaZofra('');
      data.NumeroDocumentoSalidaZofra('');

    }
  }

  self.NuevaMercaderia = function (data, event) {
    if (event) {
      if ($("#formMercaderiaInventarioInicial").isValid() === false) {
        alertify.alert("VALIDACION!", "Corrija los campos");
      }
      else {
        self.GuardarMercaderiaInventarioInicial(data, event);
      }
    }
  }

  self.GuardarMercaderiaInventarioInicial = function (data, event) {
    if (event) {
      var objeto = self;
      var _data = Knockout.CopiarObjeto(data);
      var copia_objeto = ko.mapping.toJS(data, mappingIgnore);
      var datajs = { Data: JSON.stringify(copia_objeto), "Filtro": ViewModels.data.Filtros.textofiltro(), 'Sede': ViewModels.data.Filtros.IdAsignacionSede() };
      $("#loader").show();

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Inventario/InventarioInicial/cInventarioInicial/InsertarMercaderiaInventarioInicial',
        success: function (data) {
          if (data != null) {
            if (data.msg != "") {
              alertify.alert("ERROR EN " + self.titulo, data.error.msg);
            }
            else {
              var busqueda = JSPath.apply('.{.IdUnidadMedida *= $Texto}', data.objeto.UnidadesMedida, { Texto: data.objeto.IdUnidadMedida });
              data.objeto.NombreUnidadMedida = busqueda[0].NombreUnidadMedida;
              var objeto = new InventariosInicialModel(data.objeto);
              // self.SeleccionarSiguiente(objeto);

              var filas = ViewModels.data.InventariosInicial().length;
              self.data.Filtros.totalfilas(data.Filtros.totalfilas);

              ko.mapping.toJS(data.Filtros, {}, ViewModels.data.Filtros);
              var objeto_js = ko.mapping.toJS(ViewModels.data.Filtros);
              $("#Paginador").paginador(objeto_js, self.ConsultarPorPagina);

              if (filas >= 10) {
                $("#Paginador").paginador(objeto_js, self.ConsultarPorPagina);
                var ultimo = $("#Paginador ul li:last").prev();
                ultimo.children("a").click();
              }
              else {
                ViewModels.data.InventariosInicial.push(objeto);
                self.Seleccionar(objeto);
              }

              // var filas = ViewModels.data.InventariosInicial().length;
              // self.data.Filtros.totalfilas(data.Filtros.totalfilas);
              // if(filas == 0)
              // {
              //   ko.mapping.toJS(data.Filtros, {}, ViewModels.data.Filtros);
              //   var objeto_js = ko.mapping.toJS(ViewModels.data.Filtros);
              //   $("#Paginador").paginador(objeto_js,self.ConsultarPorPagina);
              //
              //   var ultimo = $("#Paginador ul li:last").prev();
              //   ultimo.children("a").click();
              // }

              $('#FormularioMercaderia').modal("hide");
            }
          }
          $("#loader").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var $data = { error: { msg: jqXHR.responseText } };
          $("#loader").hide();
          alertify.alert("Error en " + self.titulo, $data.error.msg, function () { });
        }
      });
    }
  }

  self.OnClickBtnNuevaMercaderia = function (data, event) {
    if (event) {
      _opcion_guardar = 0;
      _modo_nuevo = true;
      // self.OnDisabledInput(event, false);
      var objetoNuevo = ko.mapping.toJS(ViewModels.data.NuevaInventarioInicial);
      self.OnDisabledInput(event, false);

      if (ViewModels.data.InventarioInicial.ParametroDocumentoSalidaZofra() == 1) {
        if (self.IndicadorAlmacenZofra() == 1) {
          $("#OrigenMercaderiaGeneral").prop('disabled', true);
          $("#OrigenMercaderiaDua").prop('disabled', true);
          objetoNuevo.IdOrigenMercaderia = ORIGEN_MERCADERIA.ZOFRA;
        }
        else {
          $("#OrigenMercaderiaZofra").prop('disabled', true);
        }
      }

      self.MostrarTitulo("INSERCIÓN DE INVENTARIO INICIAL");
      $('#btn_Limpiar').text("Limpiar");
      $('#formInventarioInicial').resetearValidaciones();
      objetoNuevo.NombreSede = $("#combo-almacen option:selected").text();
      objetoNuevo.IdAsignacionSede = ViewModels.data.Filtros.IdAsignacionSede();
      objetoNuevo.FechaInicial = ViewModels.data.Filtros.FechaMovimiento();
      objetoNuevo.FechaInventario = ViewModels.data.Filtros.FechaMovimiento();
      ko.mapping.fromJS(objetoNuevo, {}, ViewModels.data.InventarioInicial);
      ViewModels.data.InventarioInicial.InicializarVistaModelo(event);
      // $("#CodigoMercaderiaInventario").attr("data-validation-text-found",data.NombreProducto());
      // $("#CodigoMercaderiaInventario").attr("data-validation-found","true");
      $("#btn_Limpiar").hide();
      $("#modalInventarioInicial").modal("show");
      setTimeout(function () {
        $("#CodigoMercaderia").focus();
      }, 750);

      $("#CodigoInventarioInicial").attr('disabled', true);
    }
  }

  self.OnDisabledInput = function (event, option) {
    if (event) {
      if (option) {
        $("#CodigoMercaderiaInventario").prop('disabled', true);
        $("#NombreProducto").prop('disabled', true);
        $("#GrupoDocumentosInventario").find('input').prop('disabled', true);
      }
      else {
        $("#CodigoMercaderiaInventario").prop('disabled', false);
        $("#NombreProducto").prop('disabled', false);
        $("#GrupoDocumentosInventario").find('input').prop('disabled', false);
      }
    }
  }

  self.Editar = function (data, event) {
    if (event) {
      $("#CodigoInventarioInicial").attr('disabled', false);
      self.OnDisabledInput(event, true);
      _opcion_guardar = 1;
      _modo_nuevo = false;
      if (_modo_nuevo == true) {

      }
      else {
        self.MostrarTitulo("EDICIÓN DE INVENTARIO INICIAL");
        $('#btn_Limpiar').text("Deshacer");
        $('#formInventarioInicial').resetearValidaciones();
        ViewModels.data.InventarioInicial.InicializarVistaModelo(event);
        $("#CodigoMercaderiaInventario").attr("data-validation-text-found", data.NombreProducto());
        $("#CodigoMercaderiaInventario").attr("data-validation-found", "true");
        $("#btn_Limpiar").show();
        $("#modalInventarioInicial").modal("show");
        setTimeout(function () {
          $("#CantidadInicial").focus();
        }, 750);

        $("#CodigoInventarioInicial").attr('disabled', true);
      }
      _modo_nuevo = false;
      self.titulo = "Edición de Mercadería";
    }
  }

  self.PreEliminar = function (data) {
    self.titulo = "Eliminación de Mercadería";
    alertify.confirm(self.titulo, "¿Desea borrar realmente?", function () {

      if (data.IdInventarioInicial() != null)
        self.Eliminar(data);
    }, function () { });
  }

  self.PreImpresionCodigoBarras = function (data, event) {
    if (event) {
      self.titulo = "Impresion de Codigo de barras";
      
      $("#loader").show();
      ViewModels.data.Mercaderia.ImprimirCodigoBarras(data, event, function ($data) {
        $("#loader").hide();
        if (!$data.error) {
          if (ViewModels.data.Mercaderia.ParametroVistaPreviaImpresion() == 1) {
            printJS($data.APP_RUTA);
          }
          //alertify.alert(self.titulo, "Se imprimió correctamente", function () { });
        //} else {
          //alertify.alert(self.titulo, $data.error.msg, function () { });
        }
      });
    }
  }

  self.Eliminar = function (data) {
    var objeto = data;
    var _datajs = ko.mapping.toJS(data, mappingIgnore);
    var datajs = ko.toJS({ "Data": _datajs, "Filtro": self.copiatextofiltro(), 'Sede': self.copiasedefiltro() });

    $.ajax({
      type: 'POST',
      data: datajs,
      dataType: "json",
      url: SITE_URL + '/Inventario/InventarioInicial/cInventarioInicial/BorrarInventario',
      success: function (data) {
        if (data != null) {
          if (data.msg != "") {
            alertify.alert("ERROR EN " + self.titulo, data.error.msg);
          }
          else {
            self.SeleccionarSiguiente(objeto);
            ViewModels.data.InventariosInicial.remove(objeto);

            var filas = ViewModels.data.InventariosInicial().length;
            self.data.Filtros.totalfilas(data.Filtros.totalfilas);
            if (filas == 0) {
              ko.mapping.toJS(data.Filtros, {}, ViewModels.data.Filtros);
              var objeto_js = ko.mapping.toJS(ViewModels.data.Filtros);
              $("#Paginador").paginador(objeto_js, self.ConsultarPorPagina);

              var ultimo = $("#Paginador ul li:last").prev();
              ultimo.children("a").click();

            }
          }
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        //console.log(jqXHR.responseText);
        var $data = { error: { msg: jqXHR.responseText } };
        //     $("#loader").hide();
        alertify.alert("Error en " + self.titulo, $data.error.msg, function () { });
      }
    });


  }

  self.Deshacer = function (event) {
    if (event) {
      if ($('#btn_Limpiar').text() == "Deshacer") {
        self.Seleccionar(_objeto, null);
      }
      else if ($('#btn_Limpiar').text() == "Limpiar") {
        ko.mapping.fromJS(ViewModels.data.NuevaInventarioInicial, Mapping, ViewModels.data.InventarioInicial);
        document.getElementById("form").reset();

        setTimeout(function () {
          $("#CodigoInventarioInicial").focus();
        }, 500);
      }

    }
  }

  self.Cerrar = function (event) {
    if (event) {
      $("#modalInventarioInicial").modal("hide");

      if (_modo_nuevo == true) {
        _modo_nuevo = false;
      }
      self.Seleccionar(_objeto);
    }
  }

}
