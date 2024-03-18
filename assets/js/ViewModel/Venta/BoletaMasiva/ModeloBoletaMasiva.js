ModeloBoletaMasiva = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showBoletaMasiva = ko.observable(false);
  self.TipoVenta = ko.observable(TIPO_VENTA.MERCADERIAS);

  self.InicializarModelo = function (event) {
    if(event) {

    }
  }

  self.NuevoBoletaMasiva = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesBoletaMasiva" });
      self.BoletaMasivaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo ="Emisión de Boleta Masiva";

    }
  }

  self.EditarBoletaMasiva = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesBoletaMasiva" });
      self.BoletaMasivaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo ="Edición de Boleta Masiva";
    }
  }

  self.GuardarBoletaMasiva = function (data, event,callback) {
    if (event)  {

      var datajs =ko.mapping.toJS(data);
      datajs = {Data: JSON.stringify(datajs)};

      self.Insertar(datajs, event, function($data, $event){
        if($data.error)
        {
          callback($data,$event);
        }
        else {
          callback($data,$event);
        }
      });

    }
  }

  self.Insertar = function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType : "json",
        url: SITE_URL+'/Venta/cVenta/InsertarBoletaMasiva',
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

  self.Actualizar =function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType: "json",
        url: SITE_URL+'/Venta/BoletaMasiva/cBoletaMasiva/ActualizarBoletaMasiva',
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

  if (self.DetallesBoletaMasiva !=undefined)
  {
    self.DetallesBoletaMasiva.Remover = function(data,event)  {
      if(event) {
        this.remove(data);
      }
    }

    self.DetallesBoletaMasiva.Agregar = function(data,event) {
      if(event) {
        var resultado = new VistaModeloDetalleBoletaMasiva(data);

        this.push(resultado);

        return resultado;
      }
    }

    self.DetallesBoletaMasiva.Obtener = function(data,event) {
        if(event) {
          var objeto = ko.utils.arrayFirst(this(), function(item) {
              return data == item.IdDetalleBoletaMasiva();
          });

          //if(objeto != null)
            objeto.__ko_mapping__ = undefined;
          return objeto;
        }
    }

  }


  self.ConsultarDetallesBoletaMasiva = function(data,event,callback) {
    if(event)
    {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({"Data": { "IdBoletaMasiva" : $data.IdBoletaMasiva(), "IdTipoVenta": $data.IdTipoVenta()}});

      $.ajax({
        type: 'GET',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Venta/BoletaMasiva/cDetalleBoletaMasiva/ConsultarDetallesBoletaMasiva',
        success: function (data) {
            self.DetallesBoletaMasiva([]);
            ko.utils.arrayForEach(data, function (item) {
              self.DetallesBoletaMasiva.Agregar(new VistaModeloDetalleBoletaMasiva(item),event);
            });

            callback(self,event);
          }
      });
    }
  }

  self.RUCDNICliente = ko.computed(function(){
      var resultado ="";
      if(self.NumeroDocumentoIdentidad()=="" || self.RazonSocial()=="")
      {
          resultado = "";
      }
      else {
        resultado = self.NumeroDocumentoIdentidad()+'  -  '+self.RazonSocial();
      }
      return resultado;
  }, this);

  self.Numero = ko.computed(function(){
    var resultado = self.NombreAbreviado() + ' ' +self.SerieDocumento()+'-'+self.NumeroDocumento();
    return resultado;
  },this);



}
