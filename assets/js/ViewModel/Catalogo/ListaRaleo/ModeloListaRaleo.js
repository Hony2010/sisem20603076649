ModeloListaRaleo = function (data) {
  var self = this;
  var base = data;

  self.iddetalleListaRaleo;
  self.DetalleListaRaleoInicial;
  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);

  self.InicializarModelo = function (event) {
    if(event) {
      // self.CalcularTotales(event);
    }
  }

  self.EditarListaRaleo = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesListaRaleo" });
      self.ListaRaleoInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo ="Edición de Lista de Raleo";
    }
  }

  self.SeleccionarDetalleListaRaleo = function (data,event) {
    if(event) {
      self.iddetalleListaRaleo = data.IdListaRaleo();
      self.DetalleListaRaleoInicial = ko.mapping.toJS(data,mappingIgnore);
    }
  }


  self.GuardarListaRaleo = function (event,callback) {
    if (event)  {

      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesListaRaleo" });
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

  self.GuardarMercaderia = function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType : "json",
        url: SITE_URL+'/Catalogo/cListaRaleo/GuardarListaRaleo',
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
      var nueva_data = ko.mapping.toJS($data, {ignore: ["DetallesListaRaleo", "FamiliasProducto", "SubFamiliasProducto", "Modelos", "Marcas", "LineasProducto"]});
      // var datajs = ko.mapping.toJS({"Data": nueva_data});
      var datajs = {Data: JSON.stringify(nueva_data)};

      $.ajax({
        type: 'GET',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Catalogo/cListaRaleo/ConsultarMercaderias',
        success: function (data) {
            self.DetallesListaRaleo([]);
            ko.utils.arrayForEach(data, function (item) {
              self.DetallesListaRaleo.push(new VistaModeloDetalleListaRaleo(item, self));
            });

            callback(self,event);
          }
      });
    }
  }
}
