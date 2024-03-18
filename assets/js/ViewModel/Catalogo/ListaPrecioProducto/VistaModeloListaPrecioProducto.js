VistaModeloListaPrecioProducto = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingCatalogo, self);
  
  ModeloListaPrecioProducto.call(this, self);
  self.titulo = "Gestión de Precios";

  self.InicializarVistaModelo = function (event) {
    if (event) {
      self.InicializarValidator(event);

      self.CargarSubFamilia(self, event);
      self.CargarModelo(self, event);
      self.OnChangeSubFamilia(self, event);
      self.OnChangeModelo(self, event);

      AccesoKey.AgregarKeyOption("#formListaPrecioProducto", "#btn_Grabar", TECLA_G);
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
    }
  }

  self.InicializarVistaModeloDetalle = function (data, event) {
    if (event) {
      var item;
      self.DetallesListaPrecio([]);
    }
  }


  self.OnChangeSubFamilia = function (data, event) {

    if (event) {
      self.IdSubFamiliaProducto($("#combo-subfamiliaproducto").val());
      //self.CargarModelo(data, event);
    }
  }

  self.OnChangeFamilia = function (data, event) {
    if (event) {
      self.CargarSubFamilia(data, event);

      if (data.IdSubFamiliaProducto() == null) {
        $('#combo-subfamiliaproducto').prop('selectedIndex', 0);
      }

      // if(data.IdFamiliaProducto() == undefined)
      // {
      //   $('#combo-subfamiliaproducto').append($('<option selected="true"></option>').attr('value', '').text("TODOS"));
      // }

      setTimeout(function () {
        self.OnChangeSubFamilia(data, event);
      }, 500);

    }
  }

  self.CargarSubFamilia = function (data, event) {
    if (event) {

      $('#combo-subfamiliaproducto').empty();
      var id_familia = data.IdFamiliaProducto();
      var id_subfamilia = data.IdSubFamiliaProducto();
      $('#combo-subfamiliaproducto').append($('<option selected="true"></option>').attr('value', '').text("TODOS"));
      url_subfamilia = ko.mapping.toJS(self.SubFamiliasProducto());
      $.each(url_subfamilia, function (key, entry) {
        if (id_familia == entry.IdFamiliaProducto) {
          var sel = "";
          $('#combo-subfamiliaproducto').append($('<option ' + sel + '></option>').attr('value', entry.IdSubFamiliaProducto).text(entry.NombreSubFamiliaProducto));

        }
      })
    }
  }

  self.OnChangeModelo = function (data, event) {
    if (event) {
      self.IdModelo($("#combo-modelo").val());
    }
  }

  self.OnChangeMarca = function (data, event) {
    if (event) {
      self.CargarModelo(data, event);

      if (data.IdModelo() == null) {
        $('#combo-modelo').prop('selectedIndex', 0);
      }

      setTimeout(function () {
        self.OnChangeModelo(data, event);
      }, 500);

    }
  }

  self.CargarModelo = function (data, event) {
    if (event) {

      $('#combo-modelo').empty();
      var id_marca = data.IdMarca();
      var id_modelo = data.IdModelo();
      $('#combo-modelo').append($('<option selected="true"></option>').attr('value', '').text("TODOS"));
      url_modelo = ko.mapping.toJS(self.Modelos());
      $.each(url_modelo, function (key, entry) {
        if (id_marca == entry.IdMarca) {
          var sel = "";

          $('#combo-modelo').append($('<option ' + sel + '></option>').attr('value', entry.IdModelo).text(entry.NombreModelo));

        }
      })
    }
  }

  self.OnClickConsultarMercaderias = function (data, event) {
    if (event) {
      $('#loader').show();

      self.CopiaIdProductosDetalle([]);
      self.ConsultarMercaderias(data, event, function ($data, $event) {
        $('#loader').hide();
      });
    }
  }

  self.CrearListaPrecio = function (data, event) {
    if (event) {
      var $input = $(event.target);
      self.RefrescarBotonesDetalleListaPrecio($input, event);
    }
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      var id = "#" + data.IdListaPrecio();
      $(id).addClass('active').siblings().removeClass('active');
      self.SeleccionarDetalleListaPrecio(data, event);
      self.DetallesListaPrecio.Actualizar(undefined, event);
      // $("#nletras").autoDenominacionMoneda(self.Total());
    }
  }

  self.Limpiar = function (data, event) {
    if (event) {
      self.Nuevo(self.ListaPrecioInicial, event, self.callback);
    }
  }

  self.Nuevo = function (data, event, callback) {
    if (event) {
      $('#formListaPrecio').resetearValidaciones();
      if (callback) self.callback = callback;
      self.InicializarVistaModelo(undefined, event);
      self.InicializarVistaModeloDetalle(undefined, event);
    }
  }

  self.Editar = function (data, event, callback) {
    if (event) {
      if (self.IndicadorReseteoFormulario === true) $('#formListaPrecio').resetearValidaciones();
      if (callback) self.callback = callback;
      self.EditarListaPrecio(data, event);
      self.InicializarVistaModelo(undefined, event);
      self.ConsultarDetallesListaPrecio(data, event, function ($data, $event) {
        self.InicializarVistaModeloDetalle(undefined, event);
        setTimeout(function () {
          $('#combo-seriedocumento').focus();
        }, 350);
      });
    }
  }

  self.OnClickConsultarMercaderias = function (data, event) {
    if (event) {
      var obj = ko.mapping.toJS(data, mappingIgnore);
      var datajs = {
        Data: JSON.stringify({
          IdSede : obj.IdSede,
          IdFamiliaProducto: obj.IdFamiliaProducto == undefined ? "%" : obj.IdFamiliaProducto,
          IdMarca: obj.IdMarca == undefined ? "%" : obj.IdMarca,
          IdLineaProducto: obj.IdLineaProducto == undefined ? "%" : obj.IdLineaProducto,
          IdSubFamiliaProducto: obj.IdSubFamiliaProducto == "" ? "%" : obj.IdSubFamiliaProducto,
          IdModelo: obj.IdModelo == "" ? "%" : obj.IdModelo,
          Descripcion: obj.Descripcion == "" ? "%" : "%" + obj.Descripcion + "%",
        })
      };
      
      $("#loader").show();
      self.CopiaIdProductosDetalle([]);
      self.ConsultarPreciosMercaderia(datajs, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          self.DetallesListaPrecio([]);
          ko.utils.arrayForEach($data, function (item) {
            self.DetallesListaPrecio.push(new VistaModeloDetalleListaPrecio(item, self));
          });
        }
      })
    }
  }

  self.Guardar = function (data, event) {
    if (event) {
      var pregunta = self.CheckAplicaMismoPrecio() == true ? "¿Se aplicará los mismos precios para las otras sedes, desea guardar los cambios de todas formas?" : "¿Desea guardar los cambios?";      
      alertify.confirm(self.titulo, pregunta, function () {
        $("#loader").show();
        self.GuardarListaPrecio(event, self.PostGuardar);
      }, function () { });
    }
  }

  self.PostGuardar = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (data.error) {
        alertify.alert("Error en " + self.titulo, data.error.msg, function () {
        });
      }
      else {
        alertify.alert("Lista de Precios", "Se Guardaron Correctamente los Datos.", function () {
          if (self.callback) self.callback(data, event);
          if (self.IdMarca() == undefined) {
            $('#combo-modelo').append($('<option selected="true"></option>').attr('value', '').text("TODOS"));
            self.IdModelo($("#combo-modelo").val());
          }

          if (self.IdFamiliaProducto() == undefined) {
            $('#combo-subfamiliaproducto').append($('<option selected="true"></option>').attr('value', '').text("TODOS"));
            self.IdSubFamiliaProducto($("#combo-subfamiliaproducto").val());
          }
          self.CopiaIdProductosDetalle([]);
        });
      }
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }

  self.OnFocus = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      if (callback) callback(data, event);
    }
  }
}
