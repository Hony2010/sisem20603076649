ModeloInventarioInicial = function (data) {
  var self = this;
  var base = data;

  self.iddetalleInventarioInicial;
  self.DetalleInventarioInicialInicial;
  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);

  self.InicializarModelo = function (event) {
    if(event) {
      // self.CalcularTotales(event);
    }
  }

  self.NuevoInventarioInicial = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesInventarioInicial" });
      self.InventarioInicialInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo ="Registro de Inventario Inicial";
    }
  }

  self.EditarInventarioInicial = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesInventarioInicial" });
      self.InventarioInicialInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Edicion);
      self.titulo ="Edición de Inventario Inicial";
    }
  }

  self.SeleccionarDetalleInventarioInicial = function (data,event) {
    if(event) {
      self.iddetalleInventarioInicial = data.IdInventarioInicial();
      self.DetalleInventarioInicialInicial = ko.mapping.toJS(data,mappingIgnore);
    }
  }


  self.GuardarInventarioInicial = function (event,callback) {
    if (event)  {

      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesInventarioInicial" });
      var datajs =ko.mapping.toJS({"Data" : base },mappingfinal);
      datajs = {Data: JSON.stringify(datajs)};
      // self.CalcularTotales(event);

      if (self.opcionProceso() == opcionProceso.Nuevo)  {
        
        self.Insertar(datajs,event,function($data,$event) {
          
          if($data.error)
            callback($data,$event);
          else {
            
            if (self.ParametroCodigoBarrasAutomatico() == 1) {
              $("#loader").hide();
              alertify.confirm(self.titulo, "¿Desea imprimir el(los) codigo(s) de barras?", function () {
                  $("#loader").show();
                  
                  ko.utils.arrayForEach($data.DetallesInventarioInicial, function (item) { 

                    self.ImprimirCodigoBarras(item, event, function ($data2, $evento2) {                      
  
                      if (self.ParametroVistaPreviaImpresion() == 1) {
                        printJS($data2.APP_RUTA);
                      }

                      if (item.IdProducto == null) {
                        $("#loader").hide();
                        ko.mapping.fromJS($data, MappingInventario, self);
                        callback($data,$event);
                      } 
                    });

                  });
                  
               },function(){
                ko.mapping.fromJS($data, MappingInventario, self);
                callback($data,$event); 
               });
            }
            else {
              ko.mapping.fromJS($data, MappingInventario, self);
              callback($data,$event);
            }       
          }
        });
      }
      else {
        self.Actualizar(datajs,event,function($data,$event) {
          if($data.error)
            callback($data,$event);
          else {
            self.mensaje = "Se actualizó el comprobante " + self.SerieInventarioInicial() + " - " + self.NumeroInventarioInicial()+" correctamente.\n";

            self.GenerarXML(data,event,function($data,$evento) {
              //self.mensaje = self.mensaje+"y también se generó el XML N° "+$data.NombreArchivoComprobante+".";
              self.mensaje = self.mensaje + " ¿Desea imprimir el documento?\n";
              self.mensaje = self.mensaje.replace(/\n/g, "<br />");
              callback($data,$event);
            });
          }
        });
      }
    }
  }

  self.Insertar = function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType : "json",
        url: SITE_URL+'/Inventario/InventarioInicial/cInventarioInicial/InsertarInventarioInicial',
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
        url: SITE_URL+'/Inventario/InventarioInicial/cInventarioInicial/ActualizarInventarioInicial',
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

  self.AnularInventarioInicial = function(data,event,callback) {
    if (event) {
      self.opcionProceso(opcionProceso.Anulacion);
      var _mappingIgnore = ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesInventarioInicial" });
      var datajs =ko.mapping.toJS({"Data": data },mappingfinal);

      $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Inventario/InventarioInicial/cInventarioInicial/AnularInventarioInicial',
          success: function (data) {
            if (data.error)
            {
              $("#loader").hide();
              alertify.alert(data.error.msg);
            }
            else
              callback(data,event);
        },
        error : function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert(jqXHR.responseText);
        }
      });
    }
  }

  self.EliminarInventarioInicial = function(data,event,callback) {
    if (event) {
      var resultado = { data : null , error : ""};
      var objeto = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({"Data":data},mappingIgnore);
      self.opcionProceso(opcionProceso.Eliminacion);

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Inventario/InventarioInicial/cInventarioInicial/BorrarInventarioInicial',
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

  self.ValidarUnidadMedida = function(data,event,callback) {
    if (event) {
      var resultado = { data : null , error : ""};
      var objeto = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({"Data":data},mappingIgnore);
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Inventario/InventarioInicial/cInventarioInicial/ValidarUnidadMedida',
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

  self.ValidarEstadoInventarioInicial = function(data,event) {
    if(event) {
      if (data.IndicadorEstado != undefined)  {
        if(data.IndicadorEstado() == "E" ) return false;
        if((data.IndicadorEstado() == "A") && (data.IndicadorEstadoCPE() == "C" || data.IndicadorEstadoCPE() == "R")) return false;
        if(data.IndicadorEstado() == "N") {
           if(self.opcionProceso() == opcionProceso.Anulacion) return false;
           if((self.opcionProceso() != opcionProceso.Anulacion) &&  (data.IndicadorEstadoCPE()=="C" || data.IndicadorEstadoCPE()=="R")) return false;
        }
      }

      return true;
    }
  }

  if (self.DetallesInventarioInicial !=undefined)
  {
    self.DetallesInventarioInicial.Remover = function(data,event)  {
      if(event) {
        this.remove(data);
        // self.CalcularTotales(event);
      }
    }

    self.DetallesInventarioInicial.Agregar = function(data,event) {
      if(event) {
        var objeto = null;
        if(data == undefined) {
          objeto = ko.mapping.toJS(base.NuevoDetalleInventarioInicial);// Knockout.CopiarObjeto(base.NuevoDetalleInventarioInicial);
        }
        else {
          objeto = ko.mapping.toJS(data);//Knockout.CopiarObjeto(data);
        }

        var resultado = new VistaModeloDetalleInventarioInicial(objeto, self);

        var idMaximo = 0;

        if (this().length > 0) idMaximo = Math.max.apply(null,ko.utils.arrayMap(this(),function(e){ return e.IdInventarioInicial(); }));

        resultado.IdInventarioInicial(idMaximo+1);
        this.push(resultado);

        // self.CalcularTotales(event);
        return resultado;
      }
    }

    self.DetallesInventarioInicial.Obtener = function(data,event) {
        if(event) {
          var objeto = ko.utils.arrayFirst(this(), function(item) {
              return data == item.IdInventarioInicial();
          });

          //if(objeto != null)
            objeto.__ko_mapping__ = undefined;
          return objeto;
        }
    }

    self.DetallesInventarioInicial.ExisteProductoVacio = function()  {
       var objeto = ko.utils.arrayFirst(this(), function (item) {
          return item.NombreProducto() == null || item.NombreProducto() == ""; }
        );

        return objeto;
    }

    self.DetallesInventarioInicial.Actualizar = function(data,event) {
      if(event) {
          // self.CalcularTotales(event);
      }
    }
  }


  self.ConsultarDetallesInventarioInicial = function(data,event,callback) {
    if(event)
    {
      var $data = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({"Data": { "IdInventarioInicial" : $data.IdInventarioInicial()}});

      $.ajax({
        type: 'GET',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Inventario/InventarioInicial/cDetalleInventarioInicial/ConsultarDetallesInventarioInicial',
        success: function (data) {
            self.DetallesInventarioInicial([]);
            ko.utils.arrayForEach(data, function (item) {
              self.DetallesInventarioInicial.Agregar(new VistaModeloDetalleInventarioInicial(item, self),event);
            });

            callback(self,event);
          }
      });
    }
  }

  self.ValidarProductoPorRegimen = function(data, event)
  {
    if(event)
    {
      var json = data_mercaderia;
      var opcionExtra = "";
      if (self.IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA) {
        opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.ZOFRA + '"';
      }
      else if (self.IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA){
        opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.DUA + '"';
      }
      else {
        opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.GENERAL + '"';
      }

      var codigo = String(data.CodigoMercaderia).toUpperCase();

      var queryCodigo = '//*[CodigoMercaderia="'+codigo+'"]';

      var queryBusqueda = '//*[CodigoMercaderia="'+codigo+'" '+ opcionExtra +']';

      var rpta_codigo = JSON.search(json, queryCodigo);
      var rpta = JSON.search(json, queryBusqueda);

      if(rpta_codigo.length > 0)
      {
        if (rpta.length > 0)  {
          return true;
        }
        else {
          return false;
        }
      }
      else {
        return true;
      }

    }
  }

  self.ImprimirCodigoBarras = function (data, event, callback) {
    if (event) {
      var datajs = ko.mapping.toJS({ "Data": data });

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL_BASE + '/Catalogo/cMercaderia/ImprimirCodigoBarras',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

}
