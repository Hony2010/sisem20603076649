ModeloProducto = function (data) {
  var self = this;
  var base = data;

  self.InicializarModelo = function (data,event) {
    if(event) {
    }
  }

self.ObtenerProductoPorIdProducto = function (data,event,callback) {
    if (event)  {
      if(callback) {

        var datajs =ko.toJS({"Data": data });

        if (data.IdGrupoProducto == TIPO_VENTA.MERCADERIAS)
          urljs = SITE_URL+'/Catalogo/cMercaderia/ObtenerMercaderiaPorIdProducto';
        else if (data.IdGrupoProducto == TIPO_VENTA.SERVICIOS)
          urljs= SITE_URL+'/Catalogo/cServicio/ObtenerServicioPorIdProducto';
        else if (data.IdGrupoProducto == TIPO_VENTA.ACTIVOS)
          urljs= SITE_URL+'/Catalogo/cActivoFijo/ObtenerActivoFijoIdProducto';
        else if (data.IdGrupoProducto == TIPO_VENTA.OTRASVENTAS)
          urljs= SITE_URL+'/Catalogo/cOtraVenta/ObtenerOtraVentaPorIdProducto';
        else
          urljs = SITE_URL+'/Catalogo/cMercaderia/ObtenerMercaderiaPorIdProducto';

        $.ajax({
          method: 'GET',
          data : datajs,
          dataType: 'json',
          url: urljs ,
          success: function(data) {
            callback(data,event);
          }
        });
      }
    }
  }

  self.ObtenerProductoPorCodigo = function (data,event,callback) {
			//console.log("ModeloProducto.ObtenerProductoPorCodigo");
      if (event)  {
        if(callback) {
          var datajs =ko.toJS({"Data": data });

          if (data.IdGrupoProducto == TIPO_VENTA.MERCADERIAS)
            urljs = SITE_URL+'/Catalogo/cMercaderia/ObtenerMercaderiaPorCodigoMercaderia';
          else if (data.IdGrupoProducto == TIPO_VENTA.SERVICIOS)
            urljs= SITE_URL+'/Catalogo/cServicio/ObtenerServicioPorCodigoServicio';
          else if (data.IdGrupoProducto == TIPO_VENTA.ACTIVOS)
              urljs= SITE_URL+'/Catalogo/cActivoFijo/ObtenerActivoFijoPorCodigoActivoFijo';
          else if (data.IdGrupoProducto == TIPO_VENTA.OTRASVENTAS)
              urljs= SITE_URL+'/Catalogo/cOtraVenta/ObtenerOtraVentaPorIdProducto';

          $.ajax({
            method: 'GET',
            data : datajs,
            dataType: 'json',
            url: urljs ,
            success: function(data) {
              //console.log(callback);
              //console.log("ModeloProducto.ObtenerProductoPorCodigo.success");
              callback(data,event);
            }
          });
      }
    }
  }


}
