ModeloCompraMasiva = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showCompraMasiva = ko.observable(false);

  self.InicializarModelo = function (event) {
    if(event) {

    }
  }

  self.NuevoCompraMasiva = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesCompraMasiva" });
      self.CompraMasivaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo ="Registro de Comprobante de Compra";
    }
  }

  self.GuardarCompraMasiva = function (data, event,callback) {
    if (event)  {

      // var _mappingIgnore  =ko.toJS(mappingIgnore);
      // var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesBoletaMasiva" });
      // var datajs =ko.mapping.toJS({"Data" : data });
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
        url: SITE_URL+'/Compra/cCompra/InsertarCompraMasiva',
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

  if (self.DetallesCompraMasiva !=undefined)
  {
    self.DetallesCompraMasiva.Remover = function(data,event)  {
      if(event) {
        this.remove(data);
        self.CalcularTotales(event);
      }
    }

    self.DetallesCompraMasiva.Agregar = function(data,event) {
      if(event) {
        var objeto = null;
        if(data == undefined) {
          objeto = Knockout.CopiarObjeto(base.NuevoDetalleCompraMasiva);
        }
        else {
          objeto = Knockout.CopiarObjeto(data);
        }

        var resultado = new VistaModeloDetalleCompraMasiva(objeto);

        var idMaximo = 0;

        if (this().length > 0) idMaximo = Math.max.apply(null,ko.utils.arrayMap(this(),function(e){ return e.IdDetalleCompraMasiva(); }));

        resultado.IdDetalleCompraMasiva(idMaximo+1);
        this.push(resultado);

        self.CalcularTotales(event);
        return resultado;
      }
    }

    self.DetallesCompraMasiva.Obtener = function(data,event) {
        if(event) {
          var objeto = ko.utils.arrayFirst(this(), function(item) {
              return data == item.IdDetalleCompraMasiva();
          });

          //if(objeto != null)
            objeto.__ko_mapping__ = undefined;
          return objeto;
        }
    }

  }

  self.RUCDNIProveedor = ko.computed(function(){
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
