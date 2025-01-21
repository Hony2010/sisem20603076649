VistaModeloGuiaRemisionRemitente = function (data, options) {
  var self = this;
  ko.mapping.fromJS(data, MappingGuiaRemisionRemitente, self);
  ModeloGuiaRemisionRemitente.call(this, self);

  self.Options = options;
  self.$form = $(self.Options.IdForm);
  self.DireccionSedeSerie = ko.observable("");
  self.IdMotivoTrasladoAnterior=ko.observable("");
  // funciones
  self.InicializarVistaModelo = function (event) {
    if (event) {
      self.IdMotivoTrasladoAnterior(self.IdMotivoTraslado());
      var target = `${self.Options.IdForm}  #NombreDestinatario`;
      $(target).autoCompletadoCliente(event, self.ValidarAutoCompletadoDestinatario, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, target);

      var target = `${self.Options.IdForm}  #NombreTransportista`;
      $(target).autoCompletadoTransportista(target, event, self.ValidarAutoCompletadoTransportista);

      var target = `${self.Options.IdForm}  #AutocompletadoReferencia`;
      $(target).autoCompletadoComprobantesVenta(target, event, self.ValidarAutoCompletadoDocumentoReferencia);

      self.$form.find(".fecha").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      AccesoKey.AgregarKeyOption(self.Options.IdForm, "#Grabar", TECLA_G);
      self.InicializarValidator(event);

      $("#fecha-inicio").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      $("#fecha-fin").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });

      self.OnChangeSerieDocumento(undefined,event);
      $("#SelectorVendedores").click();
    }
  }

  self.InicializarValidator = function (event) {
    if (event) {
      $.formUtils.addValidator({
        name: 'autocompletado',
        validatorFunction: function (value, $el) {
          var texto = $el.attr("data-validation-text-found");
          var texto2 =  texto == undefined ? "" :texto;
          return (value.toUpperCase() === texto2.toUpperCase() || texto2.toUpperCase() == "") ? true : false;
        },
        errorMessageKey: 'autocompletado'
      });

      $.formUtils.addValidator({
        name: "select",
        validatorFunction: function (value) {
          return (value == "" || value == null || value == 0 ? false : true)
        },
        errorMessageKey: "select"
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

      $.formUtils.addValidator({
        name: "placa",
        validatorFunction: function (value) {
          return (value == "" && self.IdModalidadTraslado() != ID_PARAMETRO_MODALIDAD_TRASLADO_PUBLICO ? false : true)
        },
        errorMessageKey: "placa"
      });

      $.formUtils.addValidator({
        name: 'required_lote',
        validatorFunction: function (value, $el, config, language, $form) {
          var texto = $el.attr("data-validation-found");
          var resultado = ("true" === texto) ? true : false;
          return resultado;
        },
        errorMessageKey: 'badvalidacion_lote'
      });

    }
  }

  self.OnChangeSerieDocumento = function (data, event) {
    if (event) {
      
      var texto = self.$form.find("#combo-seriedocumento option:selected").text();
      var indice = self.$form.find("#combo-seriedocumento")[0].selectedIndex;
      self.IdSede(self.SeriesDocumento()[indice].IdSede());
      self.SerieDocumento(texto);
      self.Sedes().forEach(item => {
        if ( item.IdSede() == self.IdSede()) {
          self.DireccionSedeSerie(item.Direccion());

          self.InterCambiarDirecciones(data,event);          
          //self.DireccionPuntoPartida(self.obtenerDireccionPuntoPartida(item.Direccion(),event));  
          //self.DireccionPuntoLlegada(self.obtenerDireccionPuntoLlegada(item.Direccion(),event));  

          self.IdAsignacionSede(item.IdAsignacionSede());
        }        
      });


    }
  }

  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }
  }

  self.OnKeyEnter = function (data, event) {
    var resultado = $(event.target).enterToTab(event);
    return resultado;
  }

  self.ValidarFormulario = function (event) {
    if (event) {
      return self.$form.isValid();
    }
  }

  self.ValidarInput = function (event) {
    if (event) {
      $(event.target).validate(function (valid, elem) { });
    }
  }

  self.NombreDestinatario = ko.computed(function () {
    var nombre = self.NumeroDocumentoIdentidadDestinatario() == '' ? self.RazonSocialDestinatario() : `${self.NumeroDocumentoIdentidadDestinatario()} - ${self.RazonSocialDestinatario()}`;
    return nombre;
  }, this)

  self.NombreTransportista = ko.computed(function () {
    var nombre = self.NumeroDocumentoIdentidadTransportista() == '' ? self.RazonSocialTransportista() : `${self.NumeroDocumentoIdentidadTransportista()} - ${self.RazonSocialTransportista()}`;
    return nombre;
  }, this)

  self.Nuevo = function (data, event, callback) {
    if (event) {
      if (callback) self.callback = callback;
      self.NuevaGuiaRemisionRemitente(data, event);
      self.InicializarVistaModelo(event);
      self.OnNuevoDetalleGuiaRemisionRemitente(event);
      self.$form.resetearValidaciones();
    }
  }

  self.OnChangeComboMotivoTraslado = function (data, event) {
    if (event) {
      self.NumeroDocumentoReferencia("");
      self.IdComprobanteVenta("");
      self.DetallesGuiaRemisionRemitente([]);
      
      self.InterCambiarDirecciones2(data,event);
      //self.DireccionPuntoLlegada(self.obtenerDireccionPuntoLlegada(self.DireccionSedeSerie(),event));
      //self.DireccionPuntoPartida(self.obtenerDireccionPuntoPartida(self.DireccionSedeSerie(),event));
      self.OnNuevoDetalleGuiaRemisionRemitente(event);
      var target = `${self.Options.IdForm}  #AutocompletadoReferencia`;
      $(target).autoCompletadoComprobantesVenta(target, event, self.ValidarAutoCompletadoDocumentoReferencia);
    }    
  }  

  self.OnChangeFechaEmision = function (data, event) {
    self.ValidarInput(event);
  }

  self.OnChangeFechaTraslado = function (data, event) {
    self.ValidarInput(event);
  }

  self.OnChangeDireccionPuntoPartida = function (data, event) {
    if (event) {
      self.ValidarInput(event);
      self.ObtenerDireccionCompletaPuntoPartida(event);
    }
  }

  self.OnChangeComboDepartamentoPuntoPartida = function (data, event) {
    if (event) {
      self.ObtenerProvinciasPuntoPartida(data, event);
      self.ValidarInput(event);
      self.ObtenerDireccionCompletaPuntoPartida(event);
    }
  }

  self.OnChangeComboProvinciasPuntoPartida = function (data, event) {
    if (event) {
      self.ObtenerDistritosPuntoPartida(data, event);
      self.ValidarInput(event);
      self.ObtenerDireccionCompletaPuntoPartida(event);
    }
  }

  self.OnChangeComboDistritoPuntoPartida = function (data, event) {
    if (event) {
      self.ValidarInput(event);
      self.ObtenerDireccionCompletaPuntoPartida(event);
    }
  }

  self.ObtenerProvinciasPuntoPartida = function (data, event) {
    if (event) {
      var IdDepartamento = data.IdDepartamentoPuntoPartida()
      var provincias = self.ObtenerProvinciasPorDepartamento({ IdDepartamento }, event);
      self.ProvinciasPuntoPartida(provincias());
      self.IdProvinciaPuntoPartida(data.IdProvinciaPuntoPartida());
    }
  }

  self.ObtenerDistritosPuntoPartida = function (data, event) {
    if (event) {
      var IdProvincia = data.IdProvinciaPuntoPartida()
      var distritos = self.ObtenerDistritosPorProvincia({ IdProvincia }, event);
      self.DistritosPuntoPartida(distritos());
      self.IdDistritoPuntoPartida(data.IdDistritoPuntoPartida());
    }
  }

  self.ObtenerDireccionCompletaPuntoPartida = function (event) {
    if (event) {
      var condicion = isNaN(self.IdDepartamentoPuntoPartida()) || isNaN(self.IdProvinciaPuntoPartida()) || isNaN(self.IdDistritoPuntoPartida());
      var nombre = condicion ? "" : `${self.DireccionPuntoPartida()} ${$("#DepartamentosPuntoPartida :selected").text()}  -  ${$("#ProvinciasPuntoPartida :selected").text()}  -  ${$("#DistritosPuntoPartida :selected").text()}`;

      self.DireccionCompletaPuntoPartida(nombre);
    }
  }

  self.OnChangeDireccionPuntoLlegada = function (data, event) {
    if (event) {
      self.ValidarInput(event);
      self.ObtenerDireccionCompletaPuntoLlegada(event);
    }
  }

  self.OnChangeComboDepartamentoPuntoLlegada = function (data, event) {
    if (event) {
      self.ObtenerProvinciasPuntoLlegada(data, event);
      self.ValidarInput(event);
      self.ObtenerDireccionCompletaPuntoLlegada(event);

    }
  }

  self.OnChangeComboProvinciasPuntoLlegada = function (data, event) {
    if (event) {
      self.ObtenerDistritosPuntoLlegada(data, event);
      self.ValidarInput(event);
      self.ObtenerDireccionCompletaPuntoLlegada(event);

    }
  }

  self.OnChangeComboDistritoPuntoLlegada = function (data, event) {
    if (event) {
      self.ValidarInput(event);
      self.ObtenerDireccionCompletaPuntoLlegada(event);
    }
  }

  self.ObtenerProvinciasPuntoLlegada = function (data, event) {
    if (event) {
      var IdDepartamento = data.IdDepartamentoPuntoLlegada()
      var provincias = self.ObtenerProvinciasPorDepartamento({ IdDepartamento }, event);
      self.ProvinciasPuntoLlegada(provincias());
      self.IdProvinciaPuntoLlegada(data.IdProvinciaPuntoLlegada());

    }
  }

  self.ObtenerDistritosPuntoLlegada = function (data, event) {
    if (event) {
      var IdProvincia = data.IdProvinciaPuntoLlegada()
      var distritos = self.ObtenerDistritosPorProvincia({ IdProvincia }, event);
      self.DistritosPuntoLlegada(distritos());
      self.IdDistritoPuntoLlegada(data.IdDistritoPuntoLlegada());
    }
  }

  self.ObtenerDireccionCompletaPuntoLlegada = function (event) {
    if (event) {
      var condicion = isNaN(self.IdDepartamentoPuntoLlegada()) || isNaN(self.IdProvinciaPuntoLlegada()) || isNaN(self.IdDistritoPuntoLlegada());
      var nombre = condicion ? "" : `${self.DireccionPuntoLlegada()} ${$("#DepartamentosPuntoLlegada :selected").text()}  -  ${$("#ProvinciasPuntoLlegada :selected").text()}  -  ${$("#DistritosPuntoLlegada :selected").text()}`;
      self.DireccionCompletaPuntoLlegada(nombre);
    }
  }

  self.ObtenerProvinciasPorDepartamento = function (data, event) {
    if (event) {
      var provincias = ko.mapping.toJS(ViewModels.data.Provincias(), mappingIgnore);
      var resultado = provincias.filter(item => item.IdDepartamento == data.IdDepartamento);

      return Knockout.CopiarObjeto(resultado);
    }
  }

  self.ObtenerDistritosPorProvincia = function (data, event) {
    if (event) {
      var distritos = ko.mapping.toJS(ViewModels.data.Distritos(), mappingIgnore);
      var resultado = distritos.filter(item => item.IdProvincia == data.IdProvincia);

      return Knockout.CopiarObjeto(resultado);
    }
  }

  self.ValidarDestinatario = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) { self.IdDestinatario(''); }
      });
    }
  }

  self.ValidarTransportista = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) { self.IdTransportista(''); }
      });
    }
  }

  self.ValidarDocumentoReferencia = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) { });
    }
  }

  self.ValidarAutoCompletadoDestinatario = function (data, event) {
    if (event) {
      var $inputDestinatario = self.$form.find("#NombreDestinatario");
      var $evento = { target: `${self.Options.IdForm} #NombreDestinatario` };
      
      if (data === -1) {
        var memsajeError = "No se han encontrado resultados para tu búsqueda";
        var razonSocialDestinatario = "";
        var $data = { IdDestinatario: '' };
      } else {
        var memsajeError = "";
        var razonSocialDestinatario = data.NumeroDocumentoIdentidad == "" ? data.RazonSocial : `${data.NumeroDocumentoIdentidad} - ${data.RazonSocial}`;
        //self.DireccionSedeSerie(data.Direccion);
        var $data = {
          RazonSocialDestinatario: data.RazonSocial,
          NumeroDocumentoIdentidadDestinatario: data.NumeroDocumentoIdentidad,
          IdDestinatario: data.IdPersona
          //DireccionPuntoLlegada: self.obtenerDireccionPuntoLlegada(data.Direccion,event),
          //DireccionPuntoPartida: self.obtenerDireccionPuntoPartida(self.DireccionSedeSerie(),event)
        };
      }
      

      $inputDestinatario.attr("data-validation-error-msg", memsajeError);
      $inputDestinatario.attr("data-validation-text-found", razonSocialDestinatario);
      
      ko.mapping.fromJS($data, {}, self);
      self.InterCambiarDirecciones3(data.Direccion,event);
      self.ValidarDestinatario(data, $evento);
      self.FocusNextAutocomplete($evento);
    }
  }

  self.ValidarAutoCompletadoTransportista = function (data, event) {
    if (event) {
      var $inputTransportista = self.$form.find("#NombreTransportista");
      var $evento = { target: `${self.Options.IdForm} #NombreTransportista` };

      if (data === -1) {
        var memsajeError = "No se han encontrado resultados para tu búsqueda";
        var razonSocialTransportista = "";
        var $data = { IdDestinatario: '' };
      } else {
        var memsajeError = "";
        var razonSocialTransportista = data.NumeroDocumentoIdentidad == "" ? data.RazonSocial : `${data.NumeroDocumentoIdentidad} - ${data.RazonSocial}`;
        var $data = {
          RazonSocialTransportista: data.RazonSocial,
          NumeroDocumentoIdentidadTransportista: data.NumeroDocumentoIdentidad,
          IdTransportista: data.IdPersona,
          DireccionTransportista: data.Direccion,
          NumeroConstanciaInscripcion: data.NumeroConstanciaInscripcion,
          NumeroLicenciaConducir : data.NumeroLicenciaConducir
        };
      }

      $inputTransportista.attr("data-validation-error-msg", memsajeError);
      $inputTransportista.attr("data-validation-text-found", razonSocialTransportista);
      
      ko.mapping.fromJS($data, {}, self);
      self.ValidarTransportista(data, $evento);
      self.FocusNextAutocomplete($evento);

    }
  }

  self.ValidarAutoCompletadoDocumentoReferencia = function (data, event) {
    if (event) {
      var $inputDocReferencia = self.$form.find("#AutocompletadoReferencia");
      var $evento = { target: `${self.Options.IdForm} #AutocompletadoReferencia` };
      
      if (data === -1) {
        var memsajeError = "No se han encontrado resultados para tu búsqueda";
        var nombreDocumentoReferencia = "";
      } else {
        var memsajeError = "";
        var nombreDocumentoReferencia = data.Documento;

        var obj = {
          IdComprobanteVenta: data.IdComprobanteVenta,
          IdTipoVenta: TIPO_VENTA.MERCADERIAS,
          IdAsignacionSede: self.IdAsignacionSede(),
          IdCorrelativoDocumento: data.IdCorrelativoDocumento
        }
        self.NumeroDocumentoReferencia(data.Documento);
        self.IdComprobanteVenta(data.IdComprobanteVenta);

        $("#loader").show();
        self.ConsultarComprobanteVenta(obj, event, function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            if (self.ParametroObservacionGuia() == 1) { self.Observacion($data.Observacion) }
            
            //validar destinatario segun el comprobante referencia
            var destinatario = self.ObtenerFilaClienteJSON($data, event);
            destinatario.Direccion = $data.Direccion;
            //self.DireccionSedeSerie();
            //self.DireccionPuntoPartida(self.obtenerDireccionPuntoPartida(self.DireccionSedeSerie(),event));  
            //self.DireccionPuntoLlegada(self.obtenerDireccionPuntoLlegada(destinatario.Direccion,event));  
          
            self.ValidarAutoCompletadoDestinatario(destinatario, event)

            self.AgregarDetallesComprobanteVentaEnGuiaRemicion($data, $event);

            $inputDocReferencia.attr("data-validation-error-msg", memsajeError);
            $inputDocReferencia.attr("data-validation-text-found", nombreDocumentoReferencia);
            self.ValidarDocumentoReferencia(data, $evento);
            self.FocusNextAutocomplete($evento);
          } else {
            alertify.alert(self.titulo, $data.error.msg, function () { });
          }
        })
      }
    }
  }

  self.AgregarDetallesComprobanteVentaEnGuiaRemicion = function (data, event) {
    if (event) {
      var detalleGuia = ko.mapping.toJS(self.NuevoDetalleGuiaRemisionRemitente);
      self.DetallesGuiaRemisionRemitente([])
      data.DetallesComprobanteVenta.forEach(item => {
        item.Cantidad = item.SaldoPendienteGuiaRemision;
        item.CantidadPrevia = item.SaldoPendienteGuiaRemision;

        var objeto = self.AgregarDetalleGuiaRemisionRemitente(Object.assign(detalleGuia, item), self);

        var dataItem = self.ObtenerFilaMercaderiaJSON(objeto, event);
        if (dataItem) {
          if (self.ParametroLote() == 1) {
            objeto.ListaLotes(dataItem.ListaLotes);
          }          
        }

        self.$form.find(objeto.InputNumeroLote()).attr("data-validation-found", "false");
        self.$form.find(objeto.InputCodigoMercaderia()).attr("data-validation-found", "true");
        self.$form.find(objeto.InputCodigoMercaderia()).attr("data-validation-text-found", objeto.NombreProducto());
      });
      self.OnNuevoDetalleGuiaRemisionRemitente(data, event);
    }
  }

  // FOCUS DESPUES DEL AUTOCOMPLETADO
  self.FocusNextAutocomplete = function (event) {
    if (event) {

      var $input = $(event.target);
      var pos = $input.closest("Form").find("input, select").not(':disabled').index($input);
      $input.closest("Form").find("input, select").not(':disabled').eq(pos + 1).focus();
    }
  }

  //CREA DATA PARA NUEVO DETALLE
  self.OnNuevoDetalleGuiaRemisionRemitente = function (event) {
    if (event) {
      ko.utils.arrayForEach(self.DetallesGuiaRemisionRemitente(), function (item) { item.UltimoItem(false); });
      var nuevo = ko.mapping.toJS(self.NuevoDetalleGuiaRemisionRemitente);
      var response = self.AgregarDetalleGuiaRemisionRemitente(nuevo, event);
      response.UltimoItem(true);
    }
  }

  // PUSHEA NUEVO DETALLE
  self.AgregarDetalleGuiaRemisionRemitente = function (data, event) {
    if (event) {
      var resultado = new VistaModeloDetalleGuiaRemisionRemitente(data, self);
      var idMaximo = Math.max.apply(null, ko.utils.arrayMap(self.DetallesGuiaRemisionRemitente(), function (e) { return e.IdDetalleGuiaRemisionRemitente(); }));
      self.DetallesGuiaRemisionRemitente.push(resultado);
      resultado.IdDetalleGuiaRemisionRemitente(idMaximo == '-Infinity' ? 1 : idMaximo + 1);
      resultado.InicializarVistaModelo(event);
      return resultado;
    }
  }

  self.OnQuitarFila = function (data, event) {
    if (event) {
      self.DetallesGuiaRemisionRemitente.remove(data);
      var trfilas = $("#DetallesGuiaRemisionRemitente").find("tr").find("button:visible");
      if (trfilas.length == 0) {
        setTimeout(function () { self.$form.find("#Observacion").focus(); }, 250);
      }
      //self.OnRefrescar(data, event, true);
    }
  }

  self.AplicarExcepcionValidaciones = function (data, event) {
    if (event) {
      //Si es la ultima fila y esta vacia sin datos entonces no aplicar validacion.
      var ultimoItem = self.DetallesGuiaRemisionRemitente()[self.DetallesGuiaRemisionRemitente().length - 1];
      var resultado = "true";

      self.$form.find(ultimoItem.InputCodigoMercaderia()).attr("data-validation-optional", resultado);
      self.$form.find(ultimoItem.InputProducto()).attr("data-validation-optional", resultado);
      self.$form.find(ultimoItem.InputCantidad()).attr("data-validation-optional", resultado);
      self.$form.find(ultimoItem.InputNumeroLote()).attr("data-validation-optional", resultado);
    }
  }

  self.ValidarGuiaParaGuardar = function (data, event) {
    if (event) {
      self.AplicarExcepcionValidaciones(data, event)
      var filtrado = ko.utils.arrayFilter(self.DetallesGuiaRemisionRemitente(), function (item) { return item.IdProducto() != null; });
      if (!self.$form.isValid()) {
        return "Existe aun datos inválidos , por favor de corregirlo.";
      }
      if (filtrado.length <= 0) {
        return "Debe ingresar por lo menos 1 ítem.";
      }
      return "";
    }
  }

  self.Guardar = function (data, event) {
    if (event) {
      if ($("#loader").is(":visible")) { return false; } //PETICIONES MULTIPLES 

      var validacion = self.ValidarGuiaParaGuardar(data, event); //VALIDACION DE LA GUIA ANTES DE SER GUARDADO 
      if (validacion != "") {
        alertify.alert(self.titulo, validacion, function () { });
        return false
      }
      self.GuardarGuiaRemisionRemitente(event, self.PostGuardar);
    }
  }
  self.PostGuardar = function (data, event) {
    if (event) {
      if (data.error) {
        $("#loader").hide();
        alertify.alert("Error en " + self.titulo, data.error.msg, function () { });
      }
      else {
        /*alertify.alert(self.titulo, self.mensaje, function () {
          if (self.callback) self.callback(data, event);
        });*/
        $("#loader").hide();
        alertify.confirm(self.titulo, self.mensaje, function () {
          $("#loader").show();
          self.Imprimir(data, event, function ($data, $evento) {
            $("#loader").hide();
            /*if (self.ParametroEnvioEmail() == 1) {
              self.EnviarEmailCliente(data, event);
            }*/
            if (self.callback) self.callback(data, $evento);
          });
        }, function () {
          $("#loader").hide();
          /*if (self.ParametroEnvioEmail() == 1) {
            self.EnviarEmailCliente(data, event);
          }*/
          if (self.callback) self.callback(data, event);
        });
      }
    }
  }

  self.Limpiar = function (data, event) {
    if (event) {
      self.Nuevo(self.GuiaRemisionRemitenteInicial, event, self.callback);
    }
  }
  self.Deshacer = function (data, event) {
    if (event) {
      self.Editar(self.GuiaRemisionRemitenteInicial, event, self.callback);
    }
  }

  self.OnVer = function (data, event, callback) {
    if (event) {
      self.$form.disabledElments(self.$form, true);
      self.Editar(data, event, callback, true);
    }
  }

  self.OnEditar = function (data, event, callback) {
    if (event) {
      self.Editar(data, event, callback, false);
    }
  }

  self.Editar = function (data, event, callback, ver = false) {
    if (event) {
      self.$form.resetearValidaciones();
      if (!ver) { self.$form.disabledElments(self.$form, false); }
      self.opcionProceso(opcionProceso.Edicion);
      if (callback) self.callback = callback;

      var CopiaData = Knockout.CopiarObjeto(data);
      self.ObtenerProvinciasPuntoPartida(CopiaData, event);
      self.ObtenerDistritosPuntoPartida(CopiaData, event);
      self.ObtenerProvinciasPuntoLlegada(CopiaData, event);
      self.ObtenerDistritosPuntoLlegada(CopiaData, event);
      self.ObtenerDireccionCompletaPuntoPartida(event);
      self.ObtenerDireccionCompletaPuntoLlegada(event);

      self.EditarGuiaRemisionRemitente(data, event);
      self.InicializarVistaModelo(event);

      var razonSocialDestinatario = self.NumeroDocumentoIdentidadDestinatario() == "" ? self.RazonSocialDestinatario() : self.NumeroDocumentoIdentidadDestinatario() + " - " + self.RazonSocialDestinatario()
      self.$form.find("#NombreDestinatario").attr("data-validation-text-found", razonSocialDestinatario);
      self.$form.find("#NombreDestinatario").val(razonSocialDestinatario);

      var razonSocialTransportista = self.NumeroDocumentoIdentidadTransportista() == "" ? self.RazonSocialTransportista() : self.NumeroDocumentoIdentidadTransportista() + " - " + self.RazonSocialTransportista()
      self.$form.find("#NombreTransportista").attr("data-validation-text-found", razonSocialTransportista);
      self.$form.find("#NombreTransportista").val(razonSocialTransportista);

      self.$form.find("#AutocompletadoReferencia").attr("data-validation-text-found", self.NumeroDocumentoReferencia());
      self.$form.find("#AutocompletadoReferencia").val(self.NumeroDocumentoReferencia());

      $('#loader').show();
      self.ConsultarDetallesGuiaRemision(data, event, function ($data, $event) {
        $('#loader').hide();
        self.OnNuevoDetalleGuiaRemisionRemitente($data, $event);
        if (self.DetallesGuiaRemisionRemitente().length > 0) {
          ko.utils.arrayForEach(self.DetallesGuiaRemisionRemitente(), function (item) {

            var dataItem = self.ObtenerFilaMercaderiaJSON(item, event);
            
            if (dataItem) {
              if (self.ParametroLote() == 1) {
                item.ListaLotes(dataItem.ListaLotes);
              }              
            }

            self.$form.find(item.InputNumeroLote()).attr("data-validation-found", "true");
            self.$form.find(item.InputCodigoMercaderia()).attr("data-validation-found", "true");
            self.$form.find(item.InputCodigoMercaderia()).attr("data-validation-text-found", item.NombreProducto());
          })
        }
        if (ver) {
          self.$form.disabledElments(self.$form, !ver ? false : true);
        }
      });
    }
  }

  self.OnEnableBtnEditar = ko.computed(function () {
    if (self.IndicadorEstado() == ESTADO.ACTIVO) {
      return true;
    }
    else if (self.IndicadorEstado() == ESTADO.ANULADO) {
      return false;
    }
    else {
      return false;
    }
  }, this);

  self.OnEnableBtnAnular = ko.computed(function () {
    if (self.IndicadorEstado() == ESTADO.ACTIVO) {
      return true;
    }
    else if (self.IndicadorEstado() == ESTADO.ANULADO) {
      return false;
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

  self.Show = function (event) {
    if (event) {
      self.showGuiaRemisionRemitente(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showGuiaRemisionRemitente(false);
      self.OnClickBtnCerrar(self, event);
    }
  }
  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $("#modalGuiaRemisionRemitente").modal("hide");//"#modalComprobanteVenta"
      $("#modalGuiaRemisionRemitenteBuscadorFacturas").modal("hide");//"#modalComprobanteVenta"
      //debugger;
      var cpe =data;
      self.DetallesGuiaRemisionRemitente([]);
      ko.utils.arrayForEach(cpe,function(item){
        var d = item.DetalleComprobanteVenta();
        ko.utils.arrayForEach(d,function(el) {
          var existe = false;
          ko.utils.arrayForEach(self.DetallesGuiaRemisionRemitente(),function(dgr){
            if(dgr.IdProducto() ==el.IdProducto()){              
              existe=true;
              var c = parseFloatAvanzado(dgr.Cantidad()) + parseFloatAvanzado(el.Cantidad());
              dgr.Cantidad(c); 
              dgr.SaldoPendienteGuiaRemision(c);
            }
          });

          if(!existe) {
            var detalleGuia = ko.mapping.toJS(self.NuevoDetalleGuiaRemisionRemitente);
            //item.Cantidad = item.SaldoPendienteGuiaRemision;
            //item.CantidadPrevia = item.SaldoPendienteGuiaRemision;  
            var objeto = self.AgregarDetalleGuiaRemisionRemitente(Object.assign(detalleGuia, el), self);
    
            var dataItem = self.ObtenerFilaMercaderiaJSON(objeto, event);
            if (dataItem) {
              if (self.ParametroLote() == 1) {
                objeto.ListaLotes(dataItem.ListaLotes);
              }          
            }
    
            self.$form.find(objeto.InputNumeroLote()).attr("data-validation-found", "false");
            self.$form.find(objeto.InputCodigoMercaderia()).attr("data-validation-found", "true");
            self.$form.find(objeto.InputCodigoMercaderia()).attr("data-validation-text-found", objeto.NombreProducto());
            
          }

        });
      });

      self.OnNuevoDetalleGuiaRemisionRemitente(data, event);            
    }
  }

  self.Eliminar = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.EliminarGuiaRemisionRemitente(data, event, self.PostEliminar);
    }
  }

  self.PostEliminar = function (data, event) {
    if (event) {
      if (self.callback) { self.callback(data, event); }
    }
  }

  self.Anular = function (data, event, callback) {
    if (event) {
      if (callback != undefined) self.callback = callback;
      self.AnularGuiaRemisionRemitente(data, event, self.PostAnular);
    }
  }

  self.PostAnular = function (data, event) {
    if (event) {
      if (self.callback) { self.callback(data, event); }
    }
  }

  self.ObtenerFilaClienteJSON = function (data, event) {
    if (event) {
      var json = ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_JSON_CLIENTES);
      var rpta = JSON.search(json, '//*[IdPersona ="' + data.IdCliente + '"]');

      return rpta.length > 0 ? rpta[0] : [];
    }
  }

  self.OnChangePesoBrutoTotal = function (data, event) {
    if (event) {
      var pesoBrutoTotal=0;
      ko.utils.arrayForEach(self.DetallesGuiaRemisionRemitente(), function (item) {
        if(item.Peso() != null) {
          pesoBrutoTotal=  pesoBrutoTotal + parseFloatAvanzado(item.Peso());;
        }             
      })

      self.PesoBrutoTotal(pesoBrutoTotal);      
    }
  }

  self.OnRefrescar = function (data, event, esporeliminacion) {
    if (event) {
 
    }
  }

  self.obtenerDireccionPuntoPartida = function(data,event) {
    if(event) {
      if(self.IdMotivoTraslado() == 3 || self.IdMotivoTraslado() == 9) {
        return "";
      }
      else {
        return data;
      }          
    }
  }

  self.obtenerDireccionPuntoLlegada = function(data,event) {
    if(event) {
      if(self.IdMotivoTraslado() == 3 || self.IdMotivoTraslado() == 9) {
        return data;  
      }
      else {
        return "";
      }          
    }
  }

  self.InterCambiarDirecciones = function(data,event) {
    if(event) {
      if(self.IdMotivoTraslado() == 3 || self.IdMotivoTraslado() == 9) {//COMPRA
        self.DireccionPuntoLlegada(self.DireccionSedeSerie());      
      }
      else {
        self.DireccionPuntoPartida(self.DireccionSedeSerie());
      }
    }
  }

  self.InterCambiarDirecciones2 = function(data,event) {
    if(event) {
      if(
        ((self.IdMotivoTrasladoAnterior() == 3 || self.IdMotivoTrasladoAnterior() == 9) &&
        !(self.IdMotivoTraslado() == 3 || self.IdMotivoTraslado() == 9))
        || (!(self.IdMotivoTrasladoAnterior() == 3 || self.IdMotivoTrasladoAnterior() == 9) &&
        (self.IdMotivoTraslado() == 3 || self.IdMotivoTraslado() == 9))
       ) {//COMPRA
        direccionPLL = self.DireccionPuntoLlegada();
        direccionPP = self.DireccionPuntoPartida();
        self.DireccionPuntoLlegada(direccionPP);      
        self.DireccionPuntoPartida(direccionPLL);
        }

      self.IdMotivoTrasladoAnterior(self.IdMotivoTraslado());
      }
  }

  self.InterCambiarDirecciones3 = function(data,event) {
    if(event) {
      if(self.IdMotivoTraslado() == 3 || self.IdMotivoTraslado() == 9) {//COMPRA
        //direccionPLL = self.DireccionPuntoLlegada();
        //direccionPP = self.DireccionPuntoPartida();
        self.DireccionPuntoPartida(data);
      }
      else {
        self.DireccionPuntoLlegada(data);      
        
      }
    }
  }

  self.OnClickBtnBuscadorFacturasGuia = function (data, event, parent) {
		if (event) {
      parent.BuscadorFacturasGuia.Inicializar(data,event,self.OnClickBtnCerrar);
			//ViewModels.data.BusquedaProformaVenta.Inicializar(data, event, $parent);
			$("#modalGuiaRemisionRemitenteBuscadorFacturas").modal("show");
		}
	};


}
