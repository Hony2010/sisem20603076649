VistaModeloImportacionVenta = function (data,options) {
    var self = this;
    ko.mapping.fromJS(data, MappingVenta, self);
    ModeloImportacionVenta.call(this,self);
    self.dataJSON = ko.observable();
    self.TotalVentas = ko.observable();

    self.Inicializar = function (data,event, editar = true) {
      if (event)  {
        
      }
    }

    self.InicializarVistaModelo = function (data,event, editar = true) {
      if (event)  {
        
      }
    }

    self.OnFocus = function(data,event) {
      if(event)  {
          $(event.target).select();
      }
    }

    self.InsertarVentasJSON = function(data, event, callback)
    {
      if(event)
      {
        self.CargarComprobantesVenta(data, event, function($data, event){

          var dataVentas = ko.mapping.toJS(self.data.ComprobantesVenta);

          var total = dataVentas.length;
          self.TotalVentas(total);

          ko.utils.arrayForEach(dataVentas, function(item) {
            item.IdComprobante = item.IdComprobanteVenta;
            var datajs = {Data: JSON.stringify(item)};
            self.InsertarComprobanteVentaJSON(datajs, event, function($data, $event){
              if($data.error)
              {
                var oldItem = ko.utils.arrayFirst(self.data.ComprobantesVenta(), function(venta) {
                    return venta.IdComprobanteVenta == item.IdComprobante;
                });
                var itemData = ko.mapping.toJS(oldItem);
                itemData.Estado = $data.error.msg;
                itemData.CodigoEstado = 0;
                self.data.ComprobantesVenta.replace(oldItem, itemData);
              }
              else
              {
                var oldItem = ko.utils.arrayFirst(self.data.ComprobantesVenta(), function(venta) {
                    return venta.IdComprobanteVenta == $data.IdComprobante;
                });
                // console.log(oldItem);
                
                self.data.ComprobantesVenta.replace(oldItem, $data);
                // oldItem.Estado($data.Estado);
                // self.data.ComprobantesVenta.remove( function (item2) {
                //   return item2.IdComprobanteVenta == $data.IdComprobante; 
                // })
              }
              total = total - 1;
              self.TotalVentas(total);
            });
          });
          
        });
        
      }
    }

    self.ValidarListaClientes = function(data, event, callback)
    {
      if(event)
      {
        $("#loader").show();
        var objeto = ko.mapping.toJS(self.dataJSON(), mappingIgnore);
        var datajs = {Data: JSON.stringify(objeto.Clientes)};
        console.log(datajs);
        self.ValidarListaClienteJSON(datajs, event, function($data, $event){
          if($data.error)
          {

          }
          else
          {
            ko.utils.arrayForEach($data.ClientesBase, function(item) {
                self.data.ClientesBase.push(item);
            });
            ko.utils.arrayForEach($data.ClientesImportacion, function(item) {
                self.data.ClientesImportacion.push(item);
            });
          }
          callback($data, $event);
        });
        $("#loader").hide();
      }
    }

    self.ValidarListaProductos = function(data, event, callback)
    {
      if(event)
      {
        $("#loader").show();
        var objeto = ko.mapping.toJS(self.dataJSON(), mappingIgnore);
        var datajs = {Data: JSON.stringify(objeto.Productos)};
        console.log(datajs);
        self.ValidarListaProductoJSON(datajs, event, function($data, $event){
          if($data.error)
          {

          }
          else
          {
            ko.utils.arrayForEach($data.ProductosImportacion, function(item) {
                self.data.ProductosImportacion.push(item);
            });
            ko.utils.arrayForEach($data.ProductosBase, function(item) {
                self.data.ProductosBase.push(item);
            });
          }
          callback($data, $event);
        });
        $("#loader").hide();
      }
    }

    self.CargarComprobantesVenta = function(data, event, callback)
    {
      if(event)
      {
        // $("#loader").show();
        var objeto = ko.mapping.toJS(self.dataJSON(), mappingIgnore);
        
        self.data.ComprobantesVenta([]);
        ko.utils.arrayForEach(objeto.Ventas, function(item) {
            item.Estado = "";
            item.CodigoEstado = "0";
            self.data.ComprobantesVenta.push(item);
        });
        
        self.TotalVentas(objeto.Ventas.length);
        callback(data, event);
        // $("#loader").hide();
      }
    }

    self.LimpiarObjetos = function(data, event)
    {
      if(event)
      {
        self.data.ClientesBase([]);
        self.data.ClientesImportacion([]);
        self.data.ProductosBase([]);
        self.data.ProductosImportacion([]);
        self.data.ComprobantesVenta([]);
      }
    }

    self.CargarJSON = function(data, event)
    {
      if(event)
      {
        $('#loader').show();
        // debugger;
        // self.DetallesInventarioInicial([]);
        self.dataJSON("");

        var json; //VALOR PARAMETRICO DE DATA IMPORT
        var files = event.target.files,file;
        if (!files || files.length == 0) return;
        file = files[0];
        var fileReader = new FileReader();
        fileReader.onload = (function (theFile) {
          return function (e) {
            // console.log('e readAsText = ', e);
            // console.log('e readAsText target = ', e.target);
            try {
              json = JSON.parse(e.target.result);
              self.dataJSON(json);
              // alert('json global var has been set to parsed json of this file here it is unevaled = \n' + JSON.stringify(json));
            } catch (ex) {
              // alert('ex when trying to parse json = ' + ex);
            }
          }
        })(file);
        fileReader.readAsText(file);
        $('#loader').hide();
        self.ResetearPlugin(data, event);
        self.LimpiarObjetos(data, event);
        $("#CargarJSON").val("");
      }
    }

    self.ResetearPlugin = function(data, event)
    {
      if(event)
      {
        var plugin = $($('#form-importacion')[0]).data('plugin_accWizard');
        plugin.activateStep(0);
        // plugin.currentIndex = 0;
      }
    }

  }