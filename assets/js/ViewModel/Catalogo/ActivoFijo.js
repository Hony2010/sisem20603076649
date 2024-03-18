
ActivosFijoModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
}

ActivoFijoModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.CambiarEstadoProducto = ko.computed(function () {
    var estado = self.IndicadorEstadoProducto() ? '1' : '0';
    self.EstadoProducto(estado);
  }, this)

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

  self.OnChangeTipoAfectacionIGV = function (data, event) {
    if (event) {
      if (self.IdTipoAfectacionIGV() == TIPO_AFECTACION_IGV.GRAVADO) {
        data.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);
      }
      else {
        data.IdTipoPrecio(TIPO_PRECIO.VALOR_REFERENCIAL_OPERACION_GRATUITA);
      }
      Models.data.TiposAfectacionIGV().forEach(function (item) {
        if (item.IdTipoAfectacionIGV() == data.IdTipoAfectacionIGV()) {
          data.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
        }
      });
      Models.data.TiposPrecio().forEach(function (item) {
        if (item.IdTipoPrecio() == data.IdTipoPrecio()) {
          data.CodigoTipoPrecio(item.CodigoTipoPrecio())
        }
      });
    }
  }

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
  'ActivosFijo': {
    create: function (options) {
      if (options)
        return new ActivosFijoModel(options.data);
    }
  },
  'ActivoFijo': {
    create: function (options) {
      console.log('ActivoFijo');
      console.log(options);
      if (options)
        return new ActivoFijoModel(options.data);
    }
  },
  'NuevoActivoFijo': {
    create: function (options) {
      if (options)
        return new ActivoFijoModel(options.data);
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

Index = function (data) {
  //var _option = false;
  //var _input_habilitado = false;
  var _modo_nuevo = false;
  //var _codigo_evento_previo = 0;
  var _opcion_guardar = 1;
  var _objeto;
  var self = this;
  //var ModelsSubFamilia = new IndexSubFamilia(data);
  self.MostrarTitulo = ko.observable("");

  ko.mapping.fromJS(data, Mapping, self);

  self.CargarModelo = function (data, event) {
    if (event) {
      _combo_modelo.empty();
      var id_marca = data.IdMarca();
      var id_modelo = data.IdModelo();
      url_modelo = ko.mapping.toJS(Models.data.Modelos())
      $.each(url_modelo, function (key, entry) {
        if (id_marca == entry.IdMarca) {
          var sel = "";
          if (id_modelo != "" || id_modelo != null) {
            if (id_modelo == entry.IdModelo) {
              sel = 'selected="true"';
            }
          }
          _combo_modelo.append($('<option ' + sel + '></option>').attr('value', entry.IdModelo).text(entry.NombreModelo));
        }
      })
    }
  }

  self.OnChangeModelo = function (data, event) {
    if ($("#modalActivoFijo").is(":visible")) {
      if (event) {
        Models.data.ActivoFijo.IdModelo($("#combo-modelo").val());
        console.log("ID MODELO REMAPEADO: " + Models.data.ActivoFijo.IdModelo());
        //self.CargarModelo(data, event);
      }
    }

  }

  self.OnChangeMarca = function (data, event) {
    if ($("#modalActivoFijo").is(":visible")) {
      if (event) {
        self.CargarModelo(data, event);
        if (data.IdModelo() == null) {
          _combo_modelo.prop('selectedIndex', 0);
        }

        setTimeout(function () {
          self.OnChangeModelo(data, event);
        }, 500);

      }
    }
  }

  self.Consultar = function () {

  }

  self.SeleccionarAnterior = function (data) {
    var id = "#" + data.IdProducto();
    var anteriorObjeto = $(id).prev();

    anteriorObjeto.addClass('active').siblings().removeClass('active');

    if (_modo_nuevo == false) {
      var match = ko.utils.arrayFirst(self.data.ActivosFijo(), function (item) {
        return anteriorObjeto.attr("id") == item.IdProducto();
      });

      if (match) {
        self.data.Marca = match;
      }
    }
  }

  self.SeleccionarSiguiente = function (data) {
    var id = "#" + data.IdProducto();
    var siguienteObjeto = $(id).next();

    if (siguienteObjeto.length > 0) {
      siguienteObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false) //revisar
      {
        var match = ko.utils.arrayFirst(self.data.ActivosFijo(), function (item) {
          return siguienteObjeto.attr("id") == item.IdProducto();
        });

        if (match) {
          self.data.Marca = match;
        }
      }
    }
    else {
      self.SeleccionarAnterior(data);
    }
  }

  self.Seleccionar = function (data, event) {
    console.log("Seleccionar");

    var id = "#" + data.IdProducto();
    $(id).addClass('active').siblings().removeClass('active');
    //debugger;

    var objeto = Knockout.CopiarObjeto(data);
    _objeto = Knockout.CopiarObjeto(data);
    ko.mapping.fromJS(objeto, Mapping, Models.data.ActivoFijo);

  }

  self.Nuevo = function (data, event) {
    //console.log("AgregarFamiliaProducto");
    if (event) {
      self.MostrarTitulo("REGISTRO DE ACTIVO FIJO");

      _objeto = Knockout.CopiarObjeto(Models.data.ActivoFijo);
      ko.mapping.fromJS(Models.data.NuevoActivoFijo, Mapping, Models.data.ActivoFijo);

      console.log("MOSTRANDO DATA NUEVA MERCADERIA");
      console.log(Models.data.ActivoFijo);

      self.CargarModelo(Models.data.ActivoFijo, event);

      $('#btn_Limpiar').text("Limpiar");
      $("#modalActivoFijo").modal("show");

      setTimeout(function () {
        $("#CodigoActivoFijo").focus();
      }, 1000);

      _opcion_guardar = 0;
      _modo_nuevo = true;
      Models.data.ActivoFijo.IdTipoAfectacionIGV(TIPO_AFECTACION_IGV.GRAVADO);
      Models.data.ActivoFijo.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);

      Models.data.TiposAfectacionIGV().forEach(function (item) {
        if (item.IdTipoAfectacionIGV() == data.ActivoFijo.IdTipoAfectacionIGV()) {
          data.ActivoFijo.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
        }
      });
      Models.data.TiposPrecio().forEach(function (item) {
        if (item.IdTipoPrecio() == data.ActivoFijo.IdTipoPrecio()) {
          data.ActivoFijo.CodigoTipoPrecio(item.CodigoTipoPrecio())
        }
      });
    }
  }

  self.Subir = function () {

    var modal = document.getElementById("form");
    console.log($("#IdProducto").val());
    var data = new FormData(modal);

    $.ajax({
      type: 'POST',
      data: data,
      contentType: false,       // The content type used when sending data to the server.
      cache: false,             // To unable request pages to be cached
      processData: false,        // To send DOMDocument or non processed data file it is set to false
      mimeType: "multipart/form-data",
      success: function (data) {
        //console.log(data);
        //Models.Guardar();
        //ACTUALIZANDO LA FILA EN MERADERIAS

        if (_opcion_guardar != 0) {
          var fila_objeto = ko.utils.arrayFirst(Models.data.ActivosFijo(), function (item) {
            return Models.data.ActivoFijo.IdProducto() == item.IdProducto();

          });
          var objeto = Knockout.CopiarObjeto(Models.data.ActivoFijo);
          var objeto2 = ko.mapping.fromJS(Models.data.ActivoFijo);
          Models.data.ActivosFijo.replace(fila_objeto, objeto);

          self.Seleccionar(Models.data.ActivoFijo);

          $("#modalActivoFijo").modal("hide");
        }
        else {
          //debugger;
          var copia_objeto = Knockout.CopiarObjeto(Models.data.ActivoFijo);
          Models.data.ActivosFijo.push(new ActivosFijoModel(copia_objeto));

          self.Seleccionar(Models.data.ActivoFijo);
          alertify.confirm(self.MostrarTitulo(), "Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function () {
            ko.mapping.fromJS(Models.data.NuevoActivoFijo, Mapping, Models.data.ActivoFijo);

            Models.data.ActivoFijo.IdTipoAfectacionIGV(TIPO_AFECTACION_IGV.GRAVADO);
            Models.data.ActivoFijo.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);

            Models.data.TiposAfectacionIGV().forEach(function (item) {
              if (item.IdTipoAfectacionIGV() == Models.data.ActivoFijo.IdTipoAfectacionIGV()) {
                Models.data.ActivoFijo.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
              }
            });
            Models.data.TiposPrecio().forEach(function (item) {
              if (item.IdTipoPrecio() == Models.data.ActivoFijo.IdTipoPrecio()) {
                Models.data.ActivoFijo.CodigoTipoPrecio(item.CodigoTipoPrecio())
              }
            });
            setTimeout(function () {
              $("#CodigoActivoFijo").focus();
            }, 350);
          }, function () {
            _modo_nuevo == false;
            $("#modalActivoFijo").modal("hide");
          });

        }
      }
    });
  }

  self.Guardar = function (data, event) {
    if (event) {
      $("#loader").show();

      var accion = "";
      if (_opcion_guardar != 0) {
        accion = "ActualizarActivoFijo";
      }
      else {
        accion = "InsertarActivoFijo";
      }
      var _data = data

      var datajs = ko.mapping.toJS({ "Data": Models.data.ActivoFijo }, mappingIgnore);
      console.log(datajs);
      console.log(data);
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cActivoFijo/' + accion,
        success: function (data) {
          console.log(data);
          $("#loader").hide();
          if (_opcion_guardar != 0) {
            if (data == "") {

              ko.mapping.fromJS(_data, Mapping, Models.data.ActivoFijo);

              var nombremodelo = $("#combo-modelo option:selected").text();
              Models.data.ActivoFijo.NombreModelo(nombremodelo);

              var nombretipoactivo = $("#combo-tipoactivo option:selected").text();
              Models.data.ActivoFijo.NombreTipoActivo(nombretipoactivo);
              //console.log("ID PRODUCTO" + data.IdProducto);
              self.Subir();
            }
            else {
              alertify.alert("ERROR EN " + self.MostrarTitulo(), data.error.msg);
            }
          }
          else {
            if (!data.error) {
              ko.mapping.fromJS(data.resultado, Mapping, Models.data.ActivoFijo);

              var nombremodelo = $("#combo-modelo option:selected").text();
              Models.data.ActivoFijo.NombreModelo(nombremodelo);

              var nombretipoactivo = $("#combo-tipoactivo option:selected").text();
              Models.data.ActivoFijo.NombreTipoActivo(nombretipoactivo);
              //console.log("ID PRODUCTO" + data.IdProducto);
              self.Subir();
            }
            else {
              alertify.alert("ERROR EN " + self.MostrarTitulo(), data.error.msg);
              $("#CodigoActivoFijo").focus();
            }
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var $data = { error: { msg: jqXHR.responseText } };
          $("#loader").hide();
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg);
        }
      });
    }
  }

  self.Editar = function (data, event) {

    if (event) {
      _opcion_guardar = 1;
      _modo_nuevo = false;
      if (_modo_nuevo == true) {

      }
      else {
        self.MostrarTitulo("EDICIÓN DE ACTIVO FIJO");
        self.CargarModelo(data, event);
        $('#btn_Limpiar').text("Deshacer");
        $("#modalActivoFijo").modal("show");
        setTimeout(function () {
          $("#CodigoActivoFijo").focus();
        }, 1000);

      }
      _modo_nuevo = false;

    }
  }

  self.PreEliminar = function (data) {
    self.MostrarTitulo("ELIMINACION DE ACTIVO FIJO");
    setTimeout(function () {
      alertify.confirm(self.MostrarTitulo(), "¿Desea borrar realmente?", function () {
        console.log("PreEliminarActivoFijo");

        if (data.IdProducto() != null)
          self.Eliminar(data);
      }, function () { });
    }, 100);

  }

  self.Eliminar = function (data) {
    var objeto = data;
    var datajs = ko.toJS({ "Data": data });

    $.ajax({
      type: 'POST',
      data: datajs,
      dataType: "json",
      url: SITE_URL + '/Catalogo/cActivoFijo/BorrarActivoFijo',
      success: function (data) {
        if (data != null) {
          console.log("BorrarActivoFijo");
          //console.log(data);
          if (data.msg != "") {
            alertify.alert("ERROR EN " + self.MostrarTitulo(), data.error.msg);
          }
          else {
            self.SeleccionarSiguiente(objeto);
            Models.data.ActivosFijo.remove(objeto);
          }
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        var $data = { error: { msg: jqXHR.responseText } };
        alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg);
      }
    });

  }

  self.Deshacer = function (event) {
    if (event) {
      if ($('#btn_Limpiar').text() == "Deshacer") {
        self.Seleccionar(_objeto, null);

        self.CargarModelo(_objeto, event);
      }
      else if ($('#btn_Limpiar').text() == "Limpiar") {
        ko.mapping.fromJS(Models.data.NuevoActivoFijo, Mapping, Models.data.ActivoFijo);
        document.getElementById("form").reset();

        setTimeout(function () {
          $("#CodigoActivoFijo").focus();
        }, 500);
      }

    }
  }

  self.Cerrar = function (event) {
    if (event) {
      $("#modalActivoFijo").modal("hide");

      if (_modo_nuevo == true) {
        _modo_nuevo = false;
      }
      self.Seleccionar(_objeto);

    }
  }
}
