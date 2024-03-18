ModeloListaPrecio = function (data) {
  var self = this;
  var base = data;

  self.iddetalleListaPrecio;
  self.DetalleListaPrecioInicial;
  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);

  self.InicializarModelo = function (event) {
    if(event) {
      // self.CalcularTotales(event);
    }
  }

  self.EditarListaPrecio = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesListaPrecio" });
      self.ListaPrecioInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo ="Edición de Lista de precios";
    }
  }

  self.SeleccionarDetalleListaPrecio = function (data,event) {
    if(event) {
      self.iddetalleListaPrecio = data.IdListaPrecio();
      self.DetalleListaPrecioInicial = ko.mapping.toJS(data,mappingIgnore);
    }
  }


  self.GuardarListaPrecio = function (event,callback) {
    if (event)  {

      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesListaPrecio" });
      var nueva_data = ko.mapping.toJS(base, {ignore: ["FamiliasProducto", "SubFamiliasProducto", "Modelos", "Marcas", "LineasProducto"]});
      var datajs =ko.mapping.toJS({"Data" : nueva_data },mappingfinal);

      datajs = {Data: JSON.stringify(datajs)};
      self.GuardarMercaderia(datajs,event,function($data,$event) {
          if($data.error)
            callback($data,$event);
          else {
            // ko.mapping.fromJS($data, MappingInventario, self);
            callback($data,$event);
          }
        });
    }
  }

  self.GuardarListaDescuento = function (event,callback) {
    if (event)  {

      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesListaPrecio" });
      var nueva_data = ko.mapping.toJS(base, {ignore: ["FamiliasProducto", "SubFamiliasProducto", "Modelos", "Marcas", "LineasProducto"]});
      var datajs =ko.mapping.toJS({"Data" : nueva_data },mappingfinal);

      datajs = {Data: JSON.stringify(datajs)};
      self.GuardarMercaderiaDescuento(datajs,event,function($data,$event) {
          if($data.error)
            callback($data,$event);
          else {
            // ko.mapping.fromJS($data, MappingInventario, self);
            callback($data,$event);
          }
        });
    }
  }

  self.GuardarMercaderiaDescuento = function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType : "json",
        url: SITE_URL+'/Catalogo/cListaDescuento/GuardarListaDescuento',
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

  self.GuardarMercaderia = function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType : "json",
        url: SITE_URL+'/Catalogo/cListaPrecios/GuardarListaPrecio',
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
        url: SITE_URL+'/Inventario/ListaPrecio/cListaPrecio/ActualizarListaPrecio',
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
  self.EliminarListaPrecio = function(data,event,callback) {
    if (event) {
      var resultado = { data : null , error : ""};
      var objeto = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({"Data":data},mappingIgnore);
      self.opcionProceso(opcionProceso.Eliminacion);

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Inventario/ListaPrecio/cListaPrecio/BorrarListaPrecio',
              success: function (data) {
                if (data.error == "")
                  resultado.data = data.resultado;
                else
                  resultado.error = data.error;

                callback(resultado,event);
            },
            error : function (jqXHR, textStatus, errorThrown) {
              resultado.error = jqXHR.responseText;
              callback(resultado,event);
          }
      });
    }
  }

  self.ConsultarMercaderias = function(data,event,callback) {
    if(event)
    {
      // var $data = Knockout.CopiarObjeto(data);
      var $data = ko.mapping.toJS(base, mappingIgnore);

      $data.IdFamiliaProducto = base.IdFamiliaProducto() == undefined ? "%" : base.IdFamiliaProducto();
      $data.IdMarca = base.IdMarca() == undefined ? "%" : base.IdMarca();
      $data.IdLineaProducto = base.IdLineaProducto() == undefined ? "%" : base.IdLineaProducto();
      $data.IdSubFamiliaProducto = base.IdSubFamiliaProducto() == "" ? "%" : base.IdSubFamiliaProducto();
      $data.IdModelo = base.IdModelo() == "" ? "%" : base.IdModelo();
      $data.Descripcion = base.Descripcion() == "" ? "%" : "%"+base.Descripcion()+"%";
      
      var nueva_data = ko.mapping.toJS($data, {ignore: ["DetallesListaPrecio", "FamiliasProducto", "SubFamiliasProducto", "Modelos", "Marcas", "LineasProducto"]});
      // var datajs = ko.mapping.toJS({"Data": nueva_data});
      var datajs = {Data: JSON.stringify(nueva_data)};

      $.ajax({
        type: 'GET',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Catalogo/cListaPrecios/ConsultarMercaderias',
        success: function (data) {
            self.DetallesListaPrecio([]);
            ko.utils.arrayForEach(data, function (item) {
              self.DetallesListaPrecio.push(new VistaModeloDetalleListaPrecio(item, self ));
            });

            callback(self,event);
          }
      });
    }
  }

  self.ConsultarMercaderiasParaPrecioBase = function(data,event,callback) {
    if(event)
    {
      // var $data = Knockout.CopiarObjeto(data);
      var $data = ko.mapping.toJS(base, mappingIgnore);
      $data.IdFamiliaProducto = base.IdFamiliaProducto() == undefined ? "%" : base.IdFamiliaProducto();
      $data.IdMarca = base.IdMarca() == undefined ? "%" : base.IdMarca();
      $data.IdLineaProducto = base.IdLineaProducto() == undefined ? "%" : base.IdLineaProducto();
      $data.IdSubFamiliaProducto = base.IdSubFamiliaProducto() == "" ? "%" : base.IdSubFamiliaProducto();
      $data.IdModelo = base.IdModelo() == "" ? "%" : base.IdModelo();
      $data.Descripcion = base.Descripcion() == "" ? "%" : "%"+base.Descripcion()+"%";
      var nueva_data = ko.mapping.toJS($data, {ignore: ["DetallesListaPrecio", "FamiliasProducto", "SubFamiliasProducto", "Modelos", "Marcas", "LineasProducto"]});
      // var datajs = ko.mapping.toJS({"Data": nueva_data});
      var datajs = {Data: JSON.stringify(nueva_data)};

      $.ajax({
        type: 'GET',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Catalogo/cListaPrecios/ConsultarMercaderiasParaPrecioBase',
        success: function (data) {
            self.DetallesListaPrecio([]);
            ko.utils.arrayForEach(data, function (item) {
              self.DetallesListaPrecio.push(new VistaModeloDetalleListaPrecio(item, self ));
            });

            callback(self,event);
          }
      });
    }
  }

  self.ActualizarCostosPromedio = function(data,event,callback) {
    if(event)
    {
      // var $data = Knockout.CopiarObjeto(data);
      var $data = ko.mapping.toJS(base, mappingIgnore);

      var nueva_data = ko.mapping.toJS($data["DetallesListaPrecio"]);
      var datajs = {Data: JSON.stringify(nueva_data), Filtro: $data["FiltroCosto"]};

      $.ajax({
        type: 'POST',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Catalogo/cListaPrecios/ObtenerCostosPromedios',
        success: function (data) {
            self.DetallesListaPrecio([]);
            ko.utils.arrayForEach(data, function (item) {
              var objeto = new VistaModeloDetalleListaPrecio(item, self);
              self.DetallesListaPrecio.push(objeto);
              objeto.CalcularPrecioVenta(null, event);
              objeto.OnChangePrecio(objeto, event);
            });

            callback(self,event);
          }
      });
    }
  }

  self.ActualizarPreciosPromedio = function(data,event,callback) {
    if(event)
    {
      // var $data = Knockout.CopiarObjeto(data);
      var $data = ko.mapping.toJS(base, mappingIgnore);

      var nueva_data = ko.mapping.toJS($data["DetallesListaPrecio"]);
      var datajs = {Data: JSON.stringify(nueva_data), Filtro: $data["FiltroPrecio"]};

      $.ajax({
        type: 'POST',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Catalogo/cListaPrecios/ObtenerPreciosPromedios',
        success: function (data) {
            self.DetallesListaPrecio([]);
            ko.utils.arrayForEach(data, function (item) {
              var objeto = new VistaModeloDetalleListaPrecio(item, self);
              self.DetallesListaPrecio.push(objeto);
              objeto.CalcularPrecioVenta(null, event);
              objeto.OnChangePrecio(objeto, event);
            });

            callback(self,event);
          }
      });
    }
  }

}
