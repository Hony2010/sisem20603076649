ModeloTransportista = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showTransportista = ko.observable(false);
  self.EstadoCondicion = ko.observable();

  self.InicializarModelo = function () {

  }

  self.ValidarNumeroDocumentoIdentidad = function(value,event) {
    if(event) {
      var idtipo = base.IdTipoDocumentoIdentidad();

      if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI) { //dni
        if($.isNumeric(value) === true && value.length === MAXIMO_DIGITOS_DNI)
          return true;
        else
          return false;
      }
      else if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_RUC) {//ruc
        if ($.isNumeric(value) === true && value.length === MAXIMO_DIGITOS_RUC)
          return true;
        else
          return false;
      }
      else {
        if(value !=="") {
          return true;
        }
        else {
          return false;
        }
      }
    }
  }

  self.ObtenerTransportistaPorIdPersona = function(data,event,callback) {
    if(event) {
      if(callback)  {
        var datajs = ko.toJS({"Data": {"IdPersona" : data.IdPersona} });
        $.ajax({
            method: 'GET',
            data : datajs,
            dataType: 'json',
            url: SITE_URL+'/Catalogo/cTransportista/ObtenerTransportistaPorIdPersona',
            success: function(data) {
                callback(data,event);
            },
            error :  function (jqXHR, textStatus, errorThrown) {
              var data = {error:{msg:jqXHR.responseText}};
              callback(data,event);
            }
          });
        }
    }
  }

  self.ObtenerRutaFoto = function() {
       var src = "";
       if (self.IdPersona()=="" || self.IdPersona() == null || self.Foto() == null || self.Foto() == "")
           src=  BASE_URL + CARPETA_IMAGENES + "nocover.png";
       else
           src = SERVER_URL + CARPETA_IMAGENES + CARPETA_TRANSPORTISTA +self.IdPersona()+SEPARADOR_CARPETA+self.Foto();

       return src;
  }

  self.NuevoTransportista = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.TransportistaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
    }
  }

  self.EditarTransportista = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      self.TransportistaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
    }
  }

  self.GuardarTransportista = function (event,callback) {
      if(event) {
        var _mappingIgnore  =ko.toJS(mappingIgnore);
        var mappingfinal = Object.assign(_mappingIgnore);
        var datajs =ko.mapping.toJS({"Data" : base },mappingfinal);

        if (self.opcionProceso() == opcionProceso.Nuevo)  {
          datajs = {"Data" : datajs.Data, "Filtro" : self.copiatextofiltroguardar()};
          self.Insertar(datajs,event,function($data,$event) {
            if($data.error)
              callback($data,$event);
            else {
              ko.mapping.fromJS($data.resultado, MappingCatalogo, self);
              self.mensaje ="Se registró el transportista  " +self.NumeroDocumentoIdentidad() + " - " + self.NombreCompleto()+ " correctamente.";
              self.SubirFoto($data,$event,callback);
            }
          });
        }
        else {
          self.Actualizar(datajs,event,function($data,$event) {
            if($data.error)
              callback($data,$event);
            else {
              self.mensaje = "Se actualizó el transportista " + self.NumeroDocumentoIdentidad() + " - " + self.RazonSocial()+" correctamente.";
              self.SubirFoto($data,$event,callback);
            }
          });
        }
      }
  }

  self.EliminarTransportista = function (data,event,callback) {
    if(event) {
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore);
      var datajs = ko.mapping.toJS({"Data": base},mappingfinal);
      datajs = {"Data" : datajs.Data, "Filtro" : data.filtro};
      self.Eliminar(datajs,event,function($data,$event){
        callback($data,$event);
      });
    }
  }

  self.Insertar = function(data,event,callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Catalogo/cTransportista/InsertarTransportista',
        success: function (data) {
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.Actualizar = function(data,event,callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Catalogo/cTransportista/ActualizarTransportista',
        success: function (data) {
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.SubirFoto = function(data,event,callback) {
      var modal = document.getElementById("formtransportista");
      var dataform = new FormData(modal);

      $.ajax({
          type: 'POST',
          data : dataform,
          contentType: false,       // The content type used when sending data to the server.
          cache: false,             // To unable request pages to be cached
          processData: false,        // To send DOMDocument or non processed data file it is set to false
          mimeType: "multipart/form-data",
          url: SITE_URL+'/Catalogo/cTransportista/SubirFoto',
          success: function (result) {
              self.EstaProcesado(true);
              callback(data,event);
          },
          error :  function (jqXHR, textStatus, errorThrown) {
            var data = {error:{msg:jqXHR.responseText}};
            callback(data,event);
          }
      });
  }

  self.Eliminar = function (data,event,callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Catalogo/cTransportista/BorrarTransportista',
        success: function (data) {
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.ConsultarReniec = function(data, event,callback) {
    if (event) {
      var datajs = ko.toJS({"Data":{'NumeroDocumentoIdentidad':data.NumeroDocumentoIdentidad()}});
      $.ajax({
        type: 'POST',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Catalogo/cTransportista/ConsultarReniec',
        success: function (data) {
          callback(data,event);
        },
        error : function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.ConsultarSunat = function(data, event, callback) {
    if (event) {
      var datajs = ko.toJS({"Data":{'NumeroDocumentoIdentidad':data.NumeroDocumentoIdentidad()}});
      $.ajax({
        type: 'POST',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Catalogo/cTransportista/ConsultarSunat',
        success: function (data) {
          callback(data,event);
        },
        error : function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }

  self.ObtenerTransportistaPorServicioExterno = function(data,event,callback) {
    if(event) {
      var idtipo= data.IdTipoDocumentoIdentidad();
      var incluye = {
        'include': ["RazonSocial","NombreCompleto","ApellidoCompleto","Direccion","NombreComercial","RepresentanteLegal","Email","Celular","TelefonoFijo"]
      };
      if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI) {
        self.ConsultarReniec(data, event,function($data,$event) {
          if($data.success == false) {
            callback($data,event);
          }
          else {
            if ($data.success == true) {
              var objetoJS =ko.mapping.toJS(self.TransportistaNuevo);
              var extra = leaveJustIncludedProperties(objetoJS,incluye.include);
              ko.mapping.fromJS(extra,{}, self);
              ko.mapping.fromJS($data.result, {}, self);
              self.IdTipoPersona(2);
              callback($data.result,event);
            }
            else {
              var $datajs = {error:{msg:$data.message}};
              callback($datajs,event);
            }
          }
        });
      }
      else if(idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_RUC) {
        self.ConsultarSunat(data, event,function($data,$event) {
          if($data.success == false) {
            callback($data,event);
          }
          else {
            if ($data.success == true) {
              var objetoJS =ko.mapping.toJS(self.TransportistaNuevo);
              var extra = leaveJustIncludedProperties(objetoJS,incluye.include);
              ko.mapping.fromJS(extra,{}, self);
              ko.mapping.fromJS($data.result, {}, self);
              if($data.result.TipoPersona == 1) self.IdTipoPersona(1);
              if($data.result.TipoPersona == 2) self.IdTipoPersona(2);
              callback($data.result,event);
            }
            else {
              var $datajs = {error:{msg:$data.message}};
              callback($datajs,event);
            }
          }
        });
      }
      else {
          callback(data,event);
      }
    }
  }

  
  self.CambiarEstadoTransportista = ko.computed(function () {
    var estado = self.IndicadorEstadoTransportista() == true ? '1' : '0';
    self.EstadoTransportista(estado);
  }, this)


}
