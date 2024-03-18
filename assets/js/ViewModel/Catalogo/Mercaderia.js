MercaderiasModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

}

MercaderiaModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.InicializarVistaModeloMercaderia = function (event) {
    if (event) {

      $("#modalMercaderia").resetearValidaciones()
      $("#NombreProveedor").val("");
      $("#NombreProveedor").autoCompletadoProveedor(event, self.ValidarAutoCompletadoProveedor, "#NombreProveedor");
    }
  }

  self.NombreProveedor = ko.computed(function () {
    var nombre = self.IdProveedor() == '' || self.IdProveedor() == null ? '' : `${self.NumeroDocumentoIdentidad()} - ${self.RazonSocial()}`;
    return nombre;
  }, this)

  self.ValidarAutoCompletadoProveedor = function (data, event) {
    if (event) {
      var $inputProveedor = $("#NombreProveedor");
      var $evento = { target: `#NombreProveedor` };

      if (data === -1) {
        var memsajeError = "No se han encontrado resultados para tu búsqueda de cliente";
        var nombreProveedor = "";
        var $data = { IdProveedor: '' };
      } else {
        var memsajeError = "";
        var nombreProveedor = `${data.NumeroDocumentoIdentidad}  -  ${data.RazonSocial}`;
        var $data = { RazonSocial: data.RazonSocial, NumeroDocumentoIdentidad: data.NumeroDocumentoIdentidad, IdCliente: data.IdPersona };
      }

      $inputProveedor.attr("data-validation-error-msg", memsajeError);
      $inputProveedor.attr("data-validation-text-found", nombreProveedor);

      ko.mapping.fromJS($data, {}, self);
      ko.mapping.fromJS($data, {}, Models.data.Mercaderia);
      self.ValidarProveedor(data, $evento);

      Models.CrearNombreLargoProducto(Models.data.Mercaderia, event)
    }
  }

  self.ValidarProveedor = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdProveedor('');
        }
      });
    }
  }

  $.formUtils.addValidator({
    name: 'autocompletado_proveedor',
    validatorFunction: function (value, $el) {
      var texto = $el.attr("data-validation-text-found");
      var resultado = (value.toUpperCase() === texto.toUpperCase()) ? true : false;
      return resultado;
    },
    errorMessageKey: 'autocompletado_proveedor'
  });


  $.formUtils.addValidator({
    name: 'autocompletado_producto',
    validatorFunction: function (value, $el, config, language, $form) {
      var texto = $el.attr("data-validation-text-found");
      var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
      return resultado;
    },
    errorMessageKey: 'badautocompletado_producto'
  });

  self.OnClickBtnNuevaBonificacionMercaderia = function (data, event) {
    if (event) {
      var nuevaBonificacion = new VistaModeloBonificacionMercaderia(ko.mapping.toJS(self.Bonificacion), data);
      data.Bonificaciones.push(nuevaBonificacion);
      var idMaximo = Math.max.apply(null, ko.utils.arrayMap(this.Bonificaciones(), function (e) { return e.IdBonificacion(); }));
      nuevaBonificacion.IdBonificacion(idMaximo == '-Infinity' ? 1 : idMaximo + 1);
      nuevaBonificacion.InicializarVistaModelo();
      $("#modalDireccionesCliente").find("input").last().focus();
    }
  }

  self.OnClickBtnBonificaciones = function (data, event) {
    if (event) {
      if ($(".dropdown-menu-bonificaciones").is(":visible") || data.Bonificaciones().length > 0) {
        return false;
      }
      $("#loader").show();
      var datajs = { Data: JSON.stringify({ IdProducto: data.IdProducto() }) };
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cMercaderia/ListarBonificacionesPorIdProducto',
        success: function ($data) {
          $("#loader").hide();
          if (!$data.error) {
            data.Bonificaciones([]);
            $data.forEach(function (item) {
              var nuevaBonificacion = new VistaModeloBonificacionMercaderia(item, data);
              data.Bonificaciones.push(nuevaBonificacion);
              nuevaBonificacion.InicializarVistaModelo();
            })
          } else {
            alertify.alert("ERROR EN REGISTRO DE MERCADERIAS", $data.error.msg, function () { });
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert("Error en " + self.titulo, jqXHR.responseText, function () { });
        }
      });
    }
  }


  self.ChangeTodosAnotacionesPlato = function (data, event) {
    if (event) {
      var sel = $("#selector_anotacionesplato_todos").prop("checked");

      for (var i = 0; i < self.AnotacionesPlatoProducto().length; i++) {
        var idanotacion = '#' + self.AnotacionesPlatoProducto()[i].IdAnotacionPlato() + '_anotacionesplato';
        $(idanotacion).prop("checked", sel);
        self.AnotacionesPlatoProducto()[i].Seleccionado($(idanotacion).prop("checked"));
      }
      if (sel == true) {
        self.TotalAnotacionesPlatoSeleccionados(self.AnotacionesPlatoProducto().length);
      } else {
        self.TotalAnotacionesPlatoSeleccionados('0');
      }
      return true;
    }
  }

  self.CambioAnotacionesPlato = function (data, event) {
    if (event) {
      self.ContadorAnotaciones(event);
    }
  }

  self.CambiarAfectoICBPER = ko.computed(function () {
    if (self.IndicadorAfectoICBPER) {
      var afecto = self.IndicadorAfectoICBPER() ? '1' : '0';
      self.AfectoICBPER(afecto);
    }
  }, this)

  self.CambiarAfectoBonificacion = ko.computed(function () {
    if (self.IndicadorAfectoBonificacion) {
      var afecto = self.IndicadorAfectoBonificacion() ? '1' : '0';
      self.AfectoBonificacion(afecto);
    }
  }, this)


  self.CambiarEstadoProducto = ko.computed(function () {
    var estado = self.IndicadorEstadoProducto() ? '1' : '0';
    self.EstadoProducto(estado);
  }, this)

  self.ContadorAnotaciones = function (event) {
    if (event) {
      var NumeroItemsSeleccionadas = 0;

      for (var i = 0; i < self.AnotacionesPlatoProducto().length; i++) {
        var id_anotacionesplato = '#' + self.AnotacionesPlatoProducto()[i].IdAnotacionPlato() + '_anotacionesplato';
        if ($(id_anotacionesplato).prop("checked")) {
          NumeroItemsSeleccionadas++;
        }
      }

      if (self.AnotacionesPlatoProducto().length == NumeroItemsSeleccionadas) {
        $("#selector_anotacionesplato_todos").prop("checked", true);
      }
      else {
        $("#selector_anotacionesplato_todos").prop("checked", false);
      }
      self.TotalAnotacionesPlatoSeleccionados(NumeroItemsSeleccionadas);
    }
  }

  self.ContadorAnotaciones(window);


  self.OnChangeInputFile = function (data, event) {
    if (event) {
      $("#FileFoto").readAsImage(event, function ($data) {
        if ($data) {
          $('#img_FileFoto').attr('src', $data.source);
          $('#foto_previa').attr('src', $data.source);
          data.Foto($data.filename);
        }
      });
    }
  }

  self.OnChangeCheckNumeroDocumento = function (data, event) {
    if (event) {
      if (event) {
        if ($("#CheckCodigoMercaderia").prop("checked")) {
          $("#CodigoMercaderia").attr("disabled", false);
          $("#CodigoMercaderia").removeClass("no-tab");
          $("#CodigoMercaderia").focus();
          data.CodigoAutomatico(1);
        }
        else {
          data.CodigoMercaderia("");
          $("#CodigoMercaderia").attr("disabled", true);
          $("#CodigoMercaderia").addClass("no-tab");
          data.CodigoAutomatico(0);
        }
      }
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

  self.BuscarGoogle = function (data, event) {
    if (event) {
      var mercaderia = Models.data.Mercaderia.NombreProducto();
      var url = LinkInicio + mercaderia + LinkFin;

      if ($("#modalMercaderia").is(":visible") && mercaderia == null) {
        alertify.alert("Error al Buscar Imagen", "Debe ingresar el nombre del producto", function () {
          setTimeout(function () {
            $("#Descripcion").focus();
          }, 500);
        });
      }
      else if (mercaderia == null) {
        alertify.alert("Error al Buscar Imagen", "Debe seleccionar un producto");
      }
      else {
        var a = document.createElement("a");
        a.target = "_blank";
        a.href = url;
        a.click();
      }
    }
  }

  self.AbrirPreview = function (data, event) {
    if (event) {
      var img = event.target;
      var dataURL = img.src;

      if (dataURL != '') {
        $("#foto_previa").attr('src', dataURL);
        $("#modalPreview").modal("show");
      }
    }
  }
  //mercaderia
  self.OnNuevaMercaderia = function (data, event, callback, opcion = true) {
    if (event) {
      ko.mapping.fromJS(data, Mapping, self);
      setTimeout(function () {
        $("#CheckCodigoMercaderia").focus();
      }, 500);

      if (opcion) {
        $("#CodigoMercaderia").attr('disabled', true);
      }

      self.CodigoAutomatico(0);

      self.IdTipoAfectacionIGV(TIPO_AFECTACION_IGV.GRAVADO);
      self.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);
      self.IdTipoSistemaISC(TIPO_SISTEMA_ISC.NO_AFECTO);

      self.TiposAfectacionIGV().forEach(function (item) {
        if (item.IdTipoAfectacionIGV() == self.IdTipoAfectacionIGV()) {
          self.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
        }
      });
      self.TiposPrecio().forEach(function (item) {
        if (item.IdTipoPrecio() == self.IdTipoPrecio()) {
          self.CodigoTipoPrecio(item.CodigoTipoPrecio())
        }
      });
      self.OnChangeTipoSistemaISC(event);
      $('#FormularioMercaderia').modal('show');
      if (callback) self.callback = callback;
    }
  }

  self.OnChangeTipoSistemaISC = function (event) {
    if (event) {
      self.TiposSistemaISC().forEach(function (item) {
        if (item.IdTipoSistemaISC() == self.IdTipoSistemaISC()) {
          self.CodigoTipoSistemaISC(item.CodigoTipoSistemaISC())
        }
      });
    }
  }


  var ignore_array_data = {
    "ignore": [
      "__ko_mapping__",
      "UnidadesMedida",
      "TiposSistemaISC",
      "OrigenMercaderia",
      "TiposPrecio",
      "TiposAfectacionIGV"
    ]
  };

  self.InsertarNuevaMercaderia = function (data, event) {
    if (event) {
      var objeto = self;
      var _data = Knockout.CopiarObjeto(data);
      var _mappingIgnore = ko.toJS(ignore_array_data);
      var datajs = ko.mapping.toJS({ "Data": data }, _mappingIgnore);
      $("#loader").show();

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cMercaderia/InsertarMercaderia',
        success: function (data) {
          console.log(data);
          if (!data.error) {
            $("#loader").hide();
            alertify.confirm("REGISTRO DE MERCADERIAS", "Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function () {
              ko.mapping.fromJS(_data.MercaderiaNueva, Mapping, objeto);
              objeto.IdTipoAfectacionIGV(TIPO_AFECTACION_IGV.GRAVADO);
              objeto.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);
              objeto.IdTipoSistemaISC(TIPO_SISTEMA_ISC.NO_AFECTO);

              self.TiposAfectacionIGV().forEach(function (item) {
                if (item.IdTipoAfectacionIGV() == self.IdTipoAfectacionIGV()) {
                  self.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
                }
              });
              self.TiposPrecio().forEach(function (item) {
                if (item.IdTipoPrecio() == self.IdTipoPrecio()) {
                  self.CodigoTipoPrecio(item.CodigoTipoPrecio())
                }
              });
              self.OnChangeTipoSistemaISC(event);

              setTimeout(function () {
                $("#CheckCodigoMercaderia").focus();
              }, 850);
            }, function () {
              $('#FormularioMercaderia').modal('hide');
              if (self.callback) self.callback(data.resultado, event)
            });
          }
          else {
            $("#loader").hide();
            alertify.alert("ERROR EN REGISTRO DE MERCADERIAS", data.error.msg, function () { });
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

  self.OnChangeTipoAfectacionIGV = function (data, event) {
    if (event) {
      if (self.IdTipoAfectacionIGV() == TIPO_AFECTACION_IGV.GRAVADO) {
        self.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);
      }
      else {
        self.IdTipoPrecio(TIPO_PRECIO.VALOR_REFERENCIAL_OPERACION_GRATUITA);
      }

      self.TiposAfectacionIGV().forEach(function (item) {
        if (item.IdTipoAfectacionIGV() == self.IdTipoAfectacionIGV()) {
          self.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
        }
      });
      self.TiposPrecio().forEach(function (item) {
        if (item.IdTipoPrecio() == self.IdTipoPrecio()) {
          self.CodigoTipoPrecio(item.CodigoTipoPrecio())
        }
      });
    }
  }

  self.OnClickBtnCerrar = function (event) {
    if (event) {
      $('#FormularioMercaderia').modal('hide');
    }
  }

  self.ImprimirCodigoBarras = function (data, event, callback) {
    if (event) {
      var datajs = ko.mapping.toJS({ "Data": data });

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL_BASE + '/Catalogo/cMercaderia/ImprimirCodigoBarras',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

}


SubFamiliaProductoModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
}

SubFamiliasProductoModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
}

ModeloModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
}

ModelosModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
}

var mappingIgnore = {
  'ignore': '__ko_mapping__'
}

var Mapping = {
  'Mercaderias': {
    create: function (options) {
      if (options)
        return new MercaderiasModel(options.data);
    }
  },
  'Mercaderia': {
    create: function (options) {
      console.log('Mercaderia');
      console.log(options);
      if (options)
        return new MercaderiaModel(options.data);
    }
  },
  'NuevaMercaderia': {
    create: function (options) {
      if (options)
        return new MercaderiaModel(options.data);
    }
  },
  'SubFamiliaProducto': {
    create: function (options) {
      if (options)
        return new SubFamiliaProductoModel(options.data);
    }
  },
  'SubFamiliasProducto': {
    create: function (options) {
      if (options)
        return new SubFamiliasProductoModel(options.data);
    }
  },
  'Modelo': {
    create: function (options) {
      if (options)
        return new ModeloModel(options.data);
    }
  },
  'Modelos': {
    create: function (options) {
      if (options)
        return new ModelosModel(options.data);
    }
  }
}

jQuery.isSubstring = function (haystack, needle) {
  return haystack.indexOf(needle) !== -1;

};

var ImageURL, LinkInicio, LinkFin;

Index = function (data) {
  var _modo_nuevo = false;
  var _opcion_guardar = 1;
  var _objeto;
  var self = this;
  self.MostrarTitulo = ko.observable("");
  self.copiatextofiltro = ko.observable("");

  self.nombrefamilia = ko.observable("");
  self.nombresubfamilia = ko.observable("");
  self.nombremarca = ko.observable("");
  self.nombremodelo = ko.observable("");
  self.nombrelinea = ko.observable("");

  ImageURL = data.data.ImageURL;
  LinkInicio = data.data.LinkInicio;
  LinkFin = data.data.LinkFin;

  ko.mapping.fromJS(data, Mapping, self);


  self.ConsultarPorPagina = function (data, event) {
    if (event) {
      self.ConsultarMercaderiasPorPagina(data, event, self.PostConsultarPorPagina);
      $("#Paginador").pagination("drawPage", data.pagina);
    }
  }

  self.PostConsultarPorPagina = function (data, event) {
    if (event) {
      self.data.Mercaderias([]);
      ko.utils.arrayForEach(data, function (item) {
        self.data.Mercaderias.push(new MercaderiasModel(item));
      });

      var objeto = Models.data.Mercaderias()[0];
      Models.Seleccionar(objeto, event);
    }
  }

  self.ConsultarMercaderiasPorPagina = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = ko.mapping.toJS({ "Data": data });
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: datajs,
        cache: false,
        url: SITE_URL + '/Catalogo/cMercaderia/ConsultarMercaderiasPorPagina',
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
        self.copiatextofiltro(data.textofiltro())
        var inputs = $(event.target).closest('form').find(':input:visible');
        inputs.eq(inputs.index(event.target) + 1).focus();

        self.ConsultarMercaderias(data, event, self.PostConsultar);
      }
    }
  }

  self.ConsultarMercaderias = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = ko.mapping.toJS({ "Data": data });
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: datajs,
        url: SITE_URL + '/Catalogo/cMercaderia/ConsultarMercaderias',
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
      self.data.Mercaderias([]);
      ko.utils.arrayForEach(data.resultado, function (item) {
        self.data.Mercaderias.push(new MercaderiasModel(item));
      });

      var objeto = Models.data.Mercaderias()[0];
      Models.Seleccionar(objeto, event);
      //ko.mapping.fromJS(data.Filtros,{},self.data.Filtros);
      $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);
      self.data.Filtros.totalfilas(data.Filtros.totalfilas);
    }
  }

  self.Sugerencias = function (event) {
    $("#input-text-filtro").autocomplete({
      delay: 100,
      source: function (request, response) {
        $.ajax({
          method: 'POST',
          dataType: 'jsonp',
          url: SITE_URL + '/Configuracion/Catalogo/cModelo/DataServer',
          success: function (data) {
            response(data[0]);
          }
        });
      }
    });
  }

  self.GenerarCodigoBarra = function (data, event) {
    data.CodigoMercaderia(data.CodigoMercaderia() == null ? '' : data.CodigoMercaderia())
    var codigo = data.CodigoMercaderia().length == CANTIDAD_DIGITOS_BARCODE ? data.CodigoMercaderia() :  GenerarCodigoAleatorio();
    console.log("Codigo Generado: " + codigo);

    $("#img_BFileFoto").attr("src", "");

    var settings = {
      output: "css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: "1",
      barHeight: "50"
    };

    $("#barcode").barcode(
      codigo, // Valor del codigo de barras
      "ean13",
      settings // tipo (cadena)
    );
    $("#img_BFileFoto").hide();
    var node = document.getElementById('barcode');
    //var node = $("#preview-images canvas");
    domtoimage.toJpeg(node, { quality: 0.95 })
      .then(function (dataUrl) {
        var img = new Image();
        img.id = "CodigoBarrasImg";
        img.src = dataUrl;

        document.getElementById("div-codigobarras").innerHTML = "";
        var division = document.getElementById("div-codigobarras");
        division.appendChild(img);
      })
      .catch(function (error) {
        console.error('oops, something went wrong!', error);
      })

    var codigo_texto = codigo + '.jpeg';
    $('#InputFileCode').val(codigo_texto);
    Models.data.Mercaderia.CodigoBarras(codigo_texto);
  }

  self.OnChangeSubFamilia = function (data, event) {
    if ($("#modalMercaderia").is(":visible")) {
      if (event) {
        Models.data.Mercaderia.IdSubFamiliaProducto($("#combo-subfamiliaproducto").val());
        if (_opcion_guardar == 0) {
          var nombresubfamilia = $("#combo-subfamiliaproducto option:selected").text() != "NO ESPECIFICADO" ? $("#combo-subfamiliaproducto option:selected").text() : "";
          self.nombresubfamilia(nombresubfamilia);
          data.NombreProducto(self.nombrefamilia() + " " + self.nombresubfamilia() + " " + self.nombremarca() + " " + self.nombremodelo());
        }

        if (_opcion_guardar == 0) {
          var nombrefamilia = $("#combo-familia").val() != 0 ? $("#combo-familia option:selected").text() : "";
          self.nombrefamilia(nombrefamilia);
          data.NombreProducto(self.nombrefamilia() + " " + self.nombresubfamilia() + " " + self.nombremarca() + " " + self.nombremodelo());
        }
      }
    }
  }

  self.OnChangeFamilia = function (data, event) {
    if ($("#modalMercaderia").is(":visible")) {
      var nombrefamilia = "";
      if (event) {
        ListadoSubFamilia.CargarSubFamilia(data, url_subfamilia, _combo_subfamilia);

        if (data.IdSubFamiliaProducto() == null) {
          _combo_subfamilia.prop('selectedIndex', 0);
        }

        if (_opcion_guardar == 0) {
          var nombrefamilia = $("#combo-familia").val() != 0 ? $("#combo-familia option:selected").text() : "";
          self.nombrefamilia(nombrefamilia);
          data.NombreProducto(self.nombrefamilia() + " " + self.nombresubfamilia() + " " + self.nombremarca() + " " + self.nombremodelo() + " " + data.Color());
        }
        self.OnChangeSubFamilia(data, event);
        self.CrearNombreLargoProducto(data, event);

      }
    }
  }

  self.OnChangeModelo = function (data, event) {
    if ($("#modalMercaderia").is(":visible")) {
      if (event) {
        Models.data.Mercaderia.IdModelo($("#combo-modelo").val());
        console.log("ID MODELO REMAPEADO: " + Models.data.Mercaderia.IdModelo());
        if (_opcion_guardar == 0) {
          var nombremodelo = $("#combo-modelo option:selected").text() != "NO ESPECIFICADO" ? $("#combo-modelo option:selected").text() : "";
          self.nombremodelo(nombremodelo);
          data.NombreProducto(self.nombrefamilia() + " " + self.nombresubfamilia() + " " + self.nombremarca() + " " + self.nombremodelo() + " " + data.Color());
        }
      }
    }
  }

  self.OnChangeMarca = function (data, event) {
    if ($("#modalMercaderia").is(":visible")) {
      if (event) {
        //self.CargarModelo(data, event);
        ListadoModelo.CargarModelo(data, url_modelo, _combo_modelo);
        if (data.IdModelo() == null) {
          _combo_modelo.prop('selectedIndex', 0);
        }
        if (_opcion_guardar == 0) {
          var nombremarca = $("#combo-marca").val() != 0 ? $("#combo-marca option:selected").text() : "";
          self.nombremarca(nombremarca);
          data.NombreProducto(self.nombrefamilia() + " " + self.nombresubfamilia() + " " + self.nombremarca() + " " + self.nombremodelo() + " " + data.Color());
        }
        self.OnChangeModelo(data, event);
        self.CrearNombreLargoProducto(data, event);
      }
    }
  }

  self.OnChangeInputColor = function (data, event) {
    if (event) {
      if (_opcion_guardar == 0) {
        data.NombreProducto(self.nombrefamilia() + " " + self.nombresubfamilia() + " " + self.nombremarca() + " " + self.nombremodelo() + " " + data.Color());
      }
    }
  }

  self.OnChangeLineaProducto = function (data, event) {
    if (event) {
      if ($("#modalMercaderia").is(":visible")) {
        var nombrelinea = $("#combo-lineaproducto").val() != 0 ? $("#combo-lineaproducto option:selected").text() : "";
        self.nombrelinea(nombrelinea);
        self.CrearNombreLargoProducto(data, event);
      }
    }
  }

  self.CrearNombreLargoProducto = function (data, event) {
    if (event) {
      if (_opcion_guardar == 0) {
        var resultado = data.CodigoMercaderia2() + ' ' + data.CodigoAlterno() + ' ' + self.nombrefamilia() + ' ' + self.nombrelinea() + ' ' + data.NumeroMotor() + ' ' + data.Aplicacion() + ' ' + self.nombremarca() + ' ' + data.Referencia() + ' ' + data.ReferenciaProveedor();
        data.NombreLargoProducto(resultado);
      }
    }
  }

  self.CargarFoto = function (data) {
    var src = "";
    if (data != null) {
      console.log(data.IdProducto());
      //console.log("ID PRODUCTO: " + data.IdProducto() + "  ., FOTO NOMBRE: " + data.Foto())
      if (data.IdProducto() == "" || data.IdProducto() == null || data.Foto() == null || data.Foto() == "") {
        src = BASE_URL + CARPETA_IMAGENES + "nocover.png";
      }
      else {
        src = SERVER_URL + CARPETA_IMAGENES + CARPETA_MERCADERIA + data.IdProducto() + SEPARADOR_CARPETA + data.Foto();
      }

    }
    return src;
  }

  self.CargarCodigoBarras = function (data) {
    var src = "";
    if (data != null) {
      console.log(data.IdProducto());

      //console.log("ID PRODUCTO: " + data.IdProducto() + "  ., FOTO NOMBRE: " + data.Foto())
      if (data.IdProducto() == "" || data.IdProducto() == null || data.CodigoBarras() == null || data.CodigoBarras() == "") {
        src = BASE_URL + CARPETA_IMAGENES + "nocover.png";
      }
      else {
        src = SERVER_URL + CARPETA_IMAGENES + CARPETA_MERCADERIA + data.IdProducto() + SUB_CARPETA_CODIGO_BARRAS + data.CodigoBarras();
      }
    }
    return src;
  }

  self.SeleccionarAnterior = function (data) {
    var id = "#" + data.IdProducto();
    var anteriorObjeto = $(id).prev();

    anteriorObjeto.addClass('active').siblings().removeClass('active');

    if (_modo_nuevo == false) {
      var match = ko.utils.arrayFirst(self.data.Mercaderias(), function (item) {
        return anteriorObjeto.attr("id") == item.IdProducto();
      });

      $("#img_FileFoto").attr("src", self.CargarFoto(match));//OJO AQUI
      $("#img_FileFotoPreview").attr("src", self.CargarFoto(match));
      $("#img_BFileFoto").attr("src", self.CargarCodigoBarras(match));
    }
  }

  self.SeleccionarSiguiente = function (data) {
    var id = "#" + data.IdProducto();
    var siguienteObjeto = $(id).next();

    if (siguienteObjeto.length > 0) {
      siguienteObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false) //revisar
      {
        var match = ko.utils.arrayFirst(self.data.Mercaderias(), function (item) {
          return siguienteObjeto.attr("id") == item.IdProducto();
        });

        $("#img_FileFoto").attr("src", self.CargarFoto(match));//OJO AQUI
        $("#img_FileFotoPreview").attr("src", self.CargarFoto(match));
        $("#img_BFileFoto").attr("src", self.CargarCodigoBarras(match));
      }
    }
    else {
      self.SeleccionarAnterior(data);
    }
  }

  self.Seleccionar = function (data, event) {

    if (data != undefined) {
      var id = "#" + data.IdProducto();
      $(id).addClass('active').siblings().removeClass('active');

      var objeto = Knockout.CopiarObjeto(data);
      _objeto = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(objeto, Mapping, Models.data.Mercaderia);

      $("#img_FileFoto").attr("src", self.CargarFoto(objeto));//OJO AQUI
      $("#img_FileFotoPreview").attr("src", self.CargarFoto(objeto));

      if (Models.data.Mercaderia.ParametroCodigoBarras() == 1) {
        document.getElementById("barcode").innerHTML = "";
        $("#img_BFileFoto").attr("src", self.CargarCodigoBarras(objeto));
      }

      if (Models.data.Mercaderia.BloquearTipoMercaderia() == 1) {
        $("#combo-origenmercaderia").prop('disabled', true);
        $("#combo-origenmercaderia").addClass('no-tab');
      }
      else {
        $("#combo-origenmercaderia").prop('disabled', false);
        $("#combo-origenmercaderia").removeClass('no-tab');
      }

      $("#AfectoISC").prop("checked", self.checkEstadoISC());
      $("#CheckSPV").prop("checked", self.checkEstadoSPV());
    }
  }

  self.LimpiarImagen = function () {
    var src = BASE_URL + CARPETA_IMAGENES + "nocover.png";
    $('#img_FileFoto').attr('src', src);
    $('#img_FileFotoPreview').attr('src', src);

    if (Models.data.Mercaderia.ParametroCodigoBarras() == 1) {
      $('#img_BFileFoto').attr('src', src);
      document.getElementById("barcode").innerHTML = "";
    }

  }

  self.Nuevo = function (data, event) {
    if (event) {
      self.MostrarTitulo("REGISTRO DE MERCADERIA");

      if (Models.data.Mercaderia.ParametroCodigoBarras() == 1) {
        $("#img_BFileFoto").show();
      }

      _objeto = Knockout.CopiarObjeto(Models.data.Mercaderia);
      ko.mapping.fromJS(Models.data.NuevaMercaderia, Mapping, Models.data.Mercaderia);
      //self.CargarModelo(Models.data.Mercaderia, event);
      //self.CargarSubFamilia(Models.data.Mercaderia, event);
      ListadoModelo.CargarModelo(Models.data.Mercaderia, url_modelo, _combo_modelo);
      ListadoSubFamilia.CargarSubFamilia(Models.data.Mercaderia, url_subfamilia, _combo_subfamilia);
      //LIMPIADOR DE IMAGENES A BLANCO
      self.LimpiarImagen();
      Models.data.Mercaderia.IdTipoAfectacionIGV(TIPO_AFECTACION_IGV.GRAVADO);
      Models.data.Mercaderia.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);
      Models.data.Mercaderia.IdTipoSistemaISC(TIPO_SISTEMA_ISC.NO_AFECTO);

      Models.data.TiposAfectacionIGV().forEach(function (item) {
        if (item.IdTipoAfectacionIGV() == Models.data.Mercaderia.IdTipoAfectacionIGV()) {
          Models.data.Mercaderia.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
        }
      });
      Models.data.TiposPrecio().forEach(function (item) {
        if (item.IdTipoPrecio() == Models.data.Mercaderia.IdTipoPrecio()) {
          Models.data.Mercaderia.CodigoTipoPrecio(item.CodigoTipoPrecio())
        }
      });
      self.OnChangeTipoSistemaISC(event);

      $("#AfectoISC").prop("checked", self.checkEstadoISC());
      $("#CheckSPV").prop("checked", self.checkEstadoSPV());
      $("#combo-origenmercaderia").prop('disabled', false);
      $("#combo-origenmercaderia").removeClass('no-tab');

      $('#btn_Limpiar').text("Limpiar");

      $("#modalMercaderia").modal("show");

      setTimeout(function () {
        $("#CheckCodigoMercaderia").focus();
      }, 500);
      $("#CodigoMercaderia").attr('disabled', true);
      Models.data.Mercaderia.CodigoAutomatico(0);

      self.nombrefamilia("");
      self.nombresubfamilia("");
      self.nombremarca("");
      self.nombremodelo("");
      _opcion_guardar = 0;
      _modo_nuevo = true;

      self.titulo = "Registro de Mercadería";

      Models.data.Mercaderia.InicializarVistaModeloMercaderia(event)

    }
  }

  self.SubirFoto = function ($data) {
    var modal = document.getElementById("form");
    var IdProducto = $("#IdProducto").val();
    console.log($("#IdProducto").val());

    var data = new FormData(modal);

    $.ajax({
      type: 'POST',
      data: data,
      contentType: false,       // The content type used when sending data to the server.
      cache: false,             // To unable request pages to be cached
      processData: false,        // To send DOMDocument or non processed data file it is set to false
      mimeType: "multipart/form-data",
      url: SITE_URL + '/Catalogo/cMercaderia/SubirFoto',
      success: function (data) {
        //ACTUALIZANDO LA FILA EN MERADERIAS
        if (_opcion_guardar != 0) {

          if (Models.data.Mercaderia.ParametroCodigoBarrasAutomatico() == 1) {
            alertify.confirm(self.titulo, "¿Desea imprimir el codigo de barras?", function () {
              $("#loader").show();
              //debugger;
              Models.data.Mercaderia.ImprimirCodigoBarras($data, event, function ($data2, $evento) {
                $("#loader").hide();

                if (Models.data.Mercaderia.ParametroVistaPreviaImpresion() == 1) {
                  printJS($data2.APP_RUTA);
                }

                self.GuardarActualizar(data, event)
              });
            }, function () {
              self.GuardarActualizar(data, event)
            });
          }
          else {
            self.GuardarActualizar(data, event)
          }


        }
        else {

          if (Models.data.Mercaderia.ParametroCodigoBarrasAutomatico() == 1) {
            alertify.confirm(self.titulo, "¿Desea imprimir el codigo de barras?", function () {
              $("#loader").show();

              self.ImprimirCodigoBarras($data.resultado, event, function ($data2, $evento) {

                $("#loader").hide();

                if (Models.data.Mercaderia.ParametroVistaPreviaImpresion() == 1) {
                  printJS($data2.APP_RUTA);
                }

                self.GuardarInsertar($data, event);
              });
            }, function () {
              self.GuardarInsertar($data, event);
            });
          }
          else {
            self.GuardarInsertar($data, event);
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

  self.DatosDelCombo = function (event) {
    if (event) {
      var nombremarca = $("#combo-marca option:selected").text();
      Models.data.Mercaderia.NombreMarca(nombremarca);
      var nombrefamilia = $("#combo-familia option:selected").text();
      Models.data.Mercaderia.NombreFamiliaProducto(nombrefamilia);
      var nombrefabricante = $("#combo-fabricante option:selected").text();
      Models.data.Mercaderia.NombreFabricante(nombrefabricante);

      var nombreunidad = $("#combo-unidadmedida").val();
      Models.data.UnidadesMedida().forEach(function (item) {
        if (item.IdUnidadMedida() == nombreunidad) {
          nombreunidad = item.AbreviaturaUnidadMedida();
        }
      });

      Models.data.Mercaderia.AbreviaturaUnidadMedida(nombreunidad);
    }
  }

  self.GuardarActualizar = function (data, event) {
    if (event) {
      var fila_objeto = ko.utils.arrayFirst(Models.data.Mercaderias(), function (item) {
        return Models.data.Mercaderia.IdProducto() == item.IdProducto();
      });

      var objeto = Knockout.CopiarObjeto(Models.data.Mercaderia);
      var objeto2 = ko.mapping.fromJS(Models.data.Mercaderia);
      Models.data.Mercaderias.replace(fila_objeto, objeto);

      self.Seleccionar(Models.data.Mercaderia);
      $("#modalMercaderia").modal("hide");
      if (Models.data.Mercaderia.ParametroCodigoBarras() == 1) {
        document.getElementById("barcode").innerHTML = "";
      }
      $("#loader").hide();
    }
  }

  self.GuardarInsertar = function (data, event) {
    if (event) {
      $("#loader").hide();

      alertify.confirm(self.titulo, "Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function () {
        ko.mapping.fromJS(Models.data.NuevaMercaderia, Mapping, Models.data.Mercaderia);
        document.getElementById("form").reset();
        Models.data.Mercaderia.IdTipoAfectacionIGV(TIPO_AFECTACION_IGV.GRAVADO);
        Models.data.Mercaderia.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);
        Models.data.Mercaderia.IdTipoSistemaISC(TIPO_SISTEMA_ISC.NO_AFECTO);
        self.OnChangeTipoSistemaISC(event);
        self.LimpiarImagen();
        setTimeout(function () {
          $("#CheckCodigoMercaderia").focus();
        }, 500);

      }, function () {

        _modo_nuevo == false;
        $("#modalMercaderia").modal("hide");
        if (Models.data.Mercaderia.ParametroCodigoBarras() == 1) {
          document.getElementById("barcode").innerHTML = "";
        }

        var objeto_filtro = ko.mapping.toJS(data.Filtros);

        var filas = Models.data.Mercaderias().length;
        Models.data.Filtros.totalfilas(objeto_filtro.totalfilas);
        if (filas >= 10) {
          $("#Paginador").paginador(objeto_filtro, Models.ConsultarPorPagina);
          var ultimo = $("#Paginador ul li:last").prev();
          ultimo.children("a").click();
        }
        else {
          var copia_nuevo = ko.mapping.toJS(Models.data.Mercaderia);
          Models.data.Mercaderias.push(new MercaderiasModel(copia_nuevo));
          self.Seleccionar(Models.data.Mercaderia);
        }

      });


      $("#loader").hide();

    }
  }

  var ignore_array_data = {
    "ignore": [
      "__ko_mapping__",
      "UnidadesMedida",
      "TiposSistemaISC",
      "OrigenMercaderia",
      "TiposPrecio",
      "TiposAfectacionIGV"
    ]
  };

  self.Guardar = function (data, event) {
    if (event) {
      if ($("#modalMercaderia").isValid() === false) {
        alertify.alert(self.titulo, "Existe aun datos inválidos , por favor de corregirlo.", function () { });
        return false;
      }
      var accion = "";
      $("#loader").show();
      console.log("GUARDAR PRODUCTO");
      if (_opcion_guardar != 0) {
        accion = "ActualizarMercaderia";
      }
      else {
        accion = "InsertarMercaderia";
      }
      var _data = data;
      var _mappingIgnore = ko.toJS(ignore_array_data);
      var datajs = ko.mapping.toJS({ "Data": Models.data.Mercaderia, "Filtro": self.copiatextofiltro() }, _mappingIgnore);
      //var code_bar = document.getElementById("CodigoBarrasImg");
      $('#CodigoBarrasImage').val($('#CodigoBarrasImg').attr('src'));
      console.log(datajs);
      console.log(data);
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cMercaderia/' + accion,
        success: function (data) {
          console.log(data);
          $("#loader").hide();
          if (_opcion_guardar != 0) {
            if (!data.error) {
              _data.Bonificaciones([]);

              ko.mapping.fromJS(_data, Mapping, Models.data.Mercaderia);
              self.DatosDelCombo(event);
              var _foto = self.data.Mercaderia.Foto();
              if (_foto != null) {
                self.SubirFoto(data);
              }
              else {

                if (Models.data.Mercaderia.ParametroCodigoBarrasAutomatico() == 1) {
                  alertify.confirm(self.titulo, "¿Desea imprimir el codigo de barras?", function () {
                    $("#loader").show();

                    Models.data.Mercaderia.ImprimirCodigoBarras(data, event, function ($data, $evento) {
                      $("#loader").hide();

                      if (Models.data.Mercaderia.ParametroVistaPreviaImpresion() == 1) {
                        printJS($data.APP_RUTA);
                      }

                      self.GuardarActualizar(data, event)
                    });
                  }, function () {
                    self.GuardarActualizar(data, event)
                  });
                }
                else {
                  self.GuardarActualizar(data, event)
                }

              }
            }
            else {
              alertify.alert("ERROR EN " + self.titulo, data.error.msg);
              $("#CodigoMercaderia").focus();
            }
          }
          else {
            if (!data.error) {
              data.resultado.Bonificaciones = new Array();
              ko.mapping.fromJS(data.resultado, Mapping, Models.data.Mercaderia);
              self.DatosDelCombo(event);
              var _foto = self.data.Mercaderia.Foto();
              if (_foto != "") {
                self.SubirFoto(data);
              }
              else {

                if (Models.data.Mercaderia.ParametroCodigoBarrasAutomatico() == 1) {
                  alertify.confirm(self.titulo, "¿Desea imprimir el codigo de barras?", function () {
                    $("#loader").show();

                    self.ImprimirCodigoBarras(data.resultado, event, function ($data, $evento) {
                      $("#loader").hide();

                      if (Models.data.Mercaderia.ParametroVistaPreviaImpresion() == 1) {
                        printJS($data.APP_RUTA);
                      }

                      self.GuardarInsertar(data, event);
                    });
                  }, function () {
                    self.GuardarInsertar(data, event);
                  });
                }
                else {
                  self.GuardarInsertar(data, event);
                }

              }
            }
            else {
              alertify.alert("Error en " + self.titulo, data.error.msg, function () { });
              $("#CodigoMercaderia").focus();
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

  self.Editar = function (data, event) {
    //console.log("Editar");
    if (event) {
      $("#CodigoMercaderia").attr('disabled', false);
      _opcion_guardar = 1;
      _modo_nuevo = false;
      if (_modo_nuevo == true) {

      }
      else {
        var totalanotaciones = 0
        self.MostrarTitulo("EDICIÓN DE MERCADERIA");
        if (data.AnotacionesPlatoProducto) {
          data.AnotacionesPlatoProducto().forEach(function (item) {
            if (item.Seleccionado() == true) { totalanotaciones++; }
          });
        }

        ListadoModelo.CargarModelo(data, url_modelo, _combo_modelo);
        ListadoSubFamilia.CargarSubFamilia(data, url_subfamilia, _combo_subfamilia);

        $('#btn_Limpiar').text("Deshacer");

        if (Models.data.Mercaderia.ParametroCodigoBarras() == 1) {
          document.getElementById("barcode").innerHTML = "";
          $("#img_BFileFoto").show();
        }

        $("#modalMercaderia").modal("show");
        $("#CodigoMercaderia").focus();

        setTimeout(function () {
          $("#CheckCodigoMercaderia").focus();
        }, 500);
        $("#CodigoMercaderia").attr('disabled', true);

        Models.data.Mercaderia.TotalAnotacionesPlatoSeleccionados(totalanotaciones);
        Models.data.Mercaderia.CodigoAutomatico(0);
      }
      _modo_nuevo = false;
      self.titulo = "Edición de Mercadería";
      Models.data.Mercaderia.InicializarVistaModeloMercaderia(event)
    }
  }

  self.PreEliminar = function (data) {
    self.titulo = "Eliminación de Mercadería";
    //setTimeout(function(){
    alertify.confirm(self.titulo, "¿Desea borrar realmente?", function () {
      console.log("PreEliminarMercaderia");

      if (data.IdProducto() != null)
        self.Eliminar(data);
    }, function () { });

    //}, 100);

  }

  self.Eliminar = function (data) {
    var objeto = data;
    var _datajs = ko.mapping.toJS(data, mappingIgnore);
    var datajs = ko.toJS({ "Data": _datajs, "Filtro": self.copiatextofiltro() });

    $.ajax({
      type: 'POST',
      data: datajs,
      dataType: "json",
      url: SITE_URL + '/Catalogo/cMercaderia/BorrarMercaderia',
      success: function (data) {
        if (data != null) {
          console.log("BorrarMercaderia");
          //console.log(data);
          if (data.msg != "") {
            alertify.alert("ERROR EN " + self.titulo, data.error.msg);
          }
          else {
            self.SeleccionarSiguiente(objeto);
            Models.data.Mercaderias.remove(objeto);
            var filas = Models.data.Mercaderias().length;

            self.data.Filtros.totalfilas(data.Filtros.totalfilas);
            if (filas == 0) {
              $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);

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

        //self.CargarModelo(_objeto, event);
        //self.CargarSubFamilia(_objeto, event);
        ListadoModelo.CargarModelo(_objeto, url_modelo, _combo_modelo);
        ListadoSubFamilia.CargarSubFamilia(_objeto, url_subfamilia, _combo_subfamilia);
      }
      else if ($('#btn_Limpiar').text() == "Limpiar") {
        ko.mapping.fromJS(Models.data.NuevaMercaderia, Mapping, Models.data.Mercaderia);
        document.getElementById("form").reset();

        //LIMPIADOR DE IMAGENES A BLANCO
        self.LimpiarImagen();

        setTimeout(function () {
          $("#CodigoMercaderia").focus();
        }, 500);
      }

    }
  }

  self.Cerrar = function (event) {
    if (event) {
      $("#modalMercaderia").modal("hide");

      if (_modo_nuevo == true) {
        _modo_nuevo = false;
      }
      self.Seleccionar(_objeto);


    }
  }

  self.OnChangeTipoAfectacionIGV = function () {

    if (Models.data.Mercaderia.IdTipoAfectacionIGV() == TIPO_AFECTACION_IGV.GRAVADO) {
      Models.data.Mercaderia.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);
    }
    else {
      Models.data.Mercaderia.IdTipoPrecio(TIPO_PRECIO.VALOR_REFERENCIAL_OPERACION_GRATUITA);
    }

    Models.data.TiposAfectacionIGV().forEach(function (item) {
      if (item.IdTipoAfectacionIGV() == Models.data.Mercaderia.IdTipoAfectacionIGV()) {
        Models.data.Mercaderia.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
      }
    });
    Models.data.TiposPrecio().forEach(function (item) {
      if (item.IdTipoPrecio() == Models.data.Mercaderia.IdTipoPrecio()) {
        Models.data.Mercaderia.CodigoTipoPrecio(item.CodigoTipoPrecio())
      }
    });

  }

  self.OnChangeTipoSistemaISC = function (event) {
    if (event) {
      Models.data.TiposSistemaISC().forEach(function (item) {
        if (item.IdTipoSistemaISC() == Models.data.Mercaderia.IdTipoSistemaISC()) {
          Models.data.Mercaderia.CodigoTipoSistemaISC(item.CodigoTipoSistemaISC())
        }
      });
    }
  }

  self.OnChangeEstadoSPV = function () {

    if ($("#CheckSPV").prop("checked"))
      Models.data.Mercaderia.SujetoPercepcionVenta("1");
    else
      Models.data.Mercaderia.SujetoPercepcionVenta("0");

  }

  self.checkEstadoISC = function () {
    return Models.data.Mercaderia.AfectoISC() == "1" ? true : false;
  }

  self.checkEstadoSPV = function () {
    return Models.data.Mercaderia.SujetoPercepcionVenta() == "1" ? true : false;
  }

  self.OnClickBtnImprimir = function (data, event) {
    if (event) {

      $("#loader").show();
      Models.data.Mercaderia.ImprimirCodigoBarras(data, event, function ($data2, $evento) {
        $("#loader").hide();
        if (Models.data.Mercaderia.ParametroVistaPreviaImpresion() == 1) {
          printJS($data2.APP_RUTA);
        }
      });
    }
  }
}
