VistaModeloDetalleInventarioInicial = function (data, base) {
    var self = this;
    var cabecera = base;
    ko.mapping.fromJS(data, MappingCatalogo , self);
    
    ModeloDetalleInventarioInicial.call(this,self);
    
    self.DecimalValorUnitario = ko.observable(CANTIDAD_DECIMALES_INVENTARIO.VALORUNITARIO)

    self.InicializarVistaModelo =  function (event,callback,callback2) {
      if(event) {
          self.Producto.InicializarVistaModelo(data,event);
          // var input = {id : self.InputProducto() };
          var input = {id : self.InputProducto(), TipoVenta : TIPO_VENTA.MERCADERIAS };
          if (cabecera.IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA) {
            $(self.InputProducto()).autoCompletadoProducto(input,event,self.ValidarAutocompletadoProducto,ORIGEN_MERCADERIA.ZOFRA);
          }
          else if (cabecera.IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA){
            $(self.InputProducto()).autoCompletadoProducto(input,event,self.ValidarAutocompletadoProducto,ORIGEN_MERCADERIA.DUA);
          }
          else {
            $(self.InputProducto()).autoCompletadoProducto(input,event,self.ValidarAutocompletadoProducto,ORIGEN_MERCADERIA.GENERAL);
          }
          // $(self.InputProducto()).autoCompletadoProducto(input,event,self.ValidarAutocompletadoProducto, ORIGEN_MERCADERIA.TODOS);
          $(self.InputFechaVencimiento()).inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
          $(self.InputFechaEmisionDocumentoSalidaZofra()).inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
          $(self.InputFechaEmisionDua()).inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
          self.InicializarModelo(event,callback,callback2);
          $(self.ComboUnidadMedida()).attr('disabled', true);
          $(self.ComboUnidadMedida()).addClass('no-tab');

          $(self.InputProducto()).on("focusout",function(event){
            self.ValidarNombreProducto(undefined,event);
          });
      }
    }

    self.InputCodigoMercaderia = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_CodigoMercaderia";
      }
      else {
        return "";
      }
    },this);

    self.InputProducto = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_NombreProducto";
      }
      else {
        return "";
      }
    },this);

    self.InputCantidadInicial = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_CantidadInicial";
      }
      else {
        return "";
      }
    },this);

    self.InputValorUnitario = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_ValorUnitario";
      }
      else {
        return "";
      }
    },this);

    self.ComboUnidadMedida = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_combo_UnidadMedida";
      }
      else {
        return "";
      }
    },this);

    self.InputDescuentoItem = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_DescuentoItem";
      }
      else {
        return "";
      }
    },this);

    self.InputOpcion = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_a_opcion";
      }
      else {
        return "";
      }
    },this);

    self.InputFechaVencimiento = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_FechaVencimientoLote";
      }
      else {
        return "";
      }
    },this);

    self.InputNumeroLote = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_NumeroLote";
      }
      else {
        return "";
      }
    },this);

    self.InputNumeroDocumentoSalidaZofra = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_NumeroDocumentoSalidaZofra";
      }
      else {
        return "";
      }
    },this);

    self.InputFechaEmisionDocumentoSalidaZofra = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_FechaEmisionDocumentoSalidaZofra";
      }
      else {
        return "";
      }
    },this);

    self.InputNumeroDua = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_NumeroDua";
      }
      else {
        return "";
      }
    },this);

    self.InputFechaEmisionDua = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_FechaEmisionDua";
      }
      else {
        return "";
      }
    },this);

    self.InputNumeroItemDua = ko.computed( function() {
      if(self.IdInventarioInicial != undefined) {
        return "#"+self.IdInventarioInicial()+"_input_NumeroItemDua";
      }
      else {
        return "";
      }
    },this);

    self.OnFocus = function(data,event,$callbackParent,$callback) {
      if(event)  {
        $callbackParent(data,event);
        $callback(data,event);
      }

      return true;
    }

    self.OnKeyEnter = function(data,event,$callbackParent) {
      if(event) {
        $callbackParent(data,event);
      }
      return true;
    }

    self.procesado = false;

    self.OnKeyEnterCodigoMercaderia = function(data,event,$callbackParent) {
        if(event) {
          if(event.keyCode === TECLA_ENTER) {

            data.CodigoMercaderia($(event.target).val());
            self.ValidarProductoPorCodigo(data,event,function($data,$event,$valid){
                var $evento = { target : self.InputProducto() };
                self.procesado = true;
                self.ValidarNombreProducto(data,$evento);
                $callbackParent($data,$event);
                self.procesado = false;
            });
          }
          return true;
        }
    }

    self.ValidarCodigoMercaderia = function(data,event) {
      if(event) {//focusout
        if(!self.procesado) {

            self.ValidarProductoPorCodigo(data,event,function($data,$event,$valid){
              var $evento = { target : self.InputProducto() };
              self.ValidarNombreProducto(data,$evento);
              $(event.relatedTarget).select();
          });
        }
      }
    }

    self.ValidarProductoPorCodigo = function(data,event,$callback) {
        if(event) {

          var $input = $(self.InputCodigoMercaderia());
          var datajs = { CodigoMercaderia : data.CodigoMercaderia() ,IdGrupoProducto : TIPO_VENTA.MERCADERIAS };

          var codigo = data.CodigoMercaderia();
          var url_json = SERVER_URL + URL_JSON_MERCADERIAS;

          var json = ObtenerJSONCodificadoDesdeURL(url_json);

          var opcionExtra = "";
          if (cabecera.IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA) {
            opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.ZOFRA + '"';
          }
          else if (cabecera.IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA){
            opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.DUA + '"';
          }
          else {
            opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.GENERAL + '"';
          }

          codigo = codigo.toUpperCase();

          var queryBusqueda = '//*[CodigoMercaderia="'+codigo+'" '+ opcionExtra +']';

          var rpta = JSON.search(json, queryBusqueda);

          if (rpta.length > 0)  {
            var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
            var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);

            $input.attr("data-validation-found","true");
            $input.attr("data-validation-text-found",producto[0].NombreProducto);
            $(self.ComboUnidadMedida()).closest("tr").removeClass('has-new');
            $(self.ComboUnidadMedida()).closest("td").removeClass('has-warning');
            $(self.ComboUnidadMedida()).prop('disabled', true);
            $(self.ComboUnidadMedida()).addClass('no-tab');

            producto[0].IdAsignacionSede=ViewModels.data.InventarioInicial.IdAsignacionSede();
            
            
            self.ConsultarInventarioInicialPorIdProductoSede(producto[0],event,function($resultado,$evento){
              
              if ($resultado.length > 0) {
                producto[0].CantidadInicial=$resultado[0].CantidadInicial;
                producto[0].ValorUnitario=$resultado[0].ValorUnitario; 
              }
              else {
                producto[0].CantidadInicial="0";
                producto[0].ValorUnitario="0.00";              
              }

              var item =self.Reemplazar(producto[0]);

              $(event.target).validate(function(valid, elem) {
                if ($callback) $callback(rpta[0],event,valid);
              });

            });

            
          }
          else {
            var item = null;
            self.IdProducto("-");
            // $(self.ComboUnidadMedida()).closest("tr").addClass('has-new');
            $(self.ComboUnidadMedida()).prop('disabled', false);
            $(self.ComboUnidadMedida()).removeClass('no-tab');
            $input.attr("data-validation-found","false");
            $input.attr("data-validation-text-found","");

            $(event.target).validate(function(valid, elem) {
              if ($callback) $callback(rpta[0],event,valid);
            });
          }                  

        }
    }

    self.ValidarProductoPorCodigoExcel = function(data,event,$callback) {
        if(event) {
          var $input = $(self.InputCodigoMercaderia());
          var json = data_mercaderia;
          // console.log(json);
          var opcionExtra = "";
          // if (cabecera.IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA) {
          //   opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.ZOFRA + '"';
          // }
          // else if (cabecera.IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA){
          //   opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.DUA + '"';
          // }
          // else {
          //   opcionExtra = ' and IdOrigenMercaderia = "' + ORIGEN_MERCADERIA.GENERAL + '"';
          // }
          var codigo = String(data.CodigoMercaderia());
          var queryBusqueda = '//*[CodigoMercaderia="'+ codigo.toUpperCase()+'" '+ opcionExtra +']';

          var rpta = JSON.search(json, queryBusqueda);

          var valido = null;
          if (rpta.length > 0)  {
            var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
            var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
            $input.attr("data-validation-found","true");
            $input.attr("data-validation-text-found",producto[0].NombreProducto);
            var item =self.ReemplazarFilaExcel(producto[0]);
            valido = true;
          }
          else {
            var item = null;
            $input.attr("data-validation-found","false");
            $input.attr("data-validation-text-found","");
            valido = false;
          }

          $callback(rpta[0],event,valido);
        }
    }

    self.ValidarNombreProducto = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ValidarAutocompletadoProducto = function(data,event) {
      if(event) {

        if (data === -1) {

          if($(self.InputCodigoMercaderia()).attr("data-validation-text-found") ===  $(self.InputProducto()).val())  {
              $(self.InputCodigoMercaderia()).attr("data-validation-found","true");
              var $evento = { target : self.InputProducto() };
              self.ValidarNombreProducto(data,$evento);
          }
          else {
            $(self.InputCodigoMercaderia()).attr("data-validation-text-found","");
            $(self.InputCodigoMercaderia()).attr("data-validation-found","false");
            var $evento = { target : self.InputProducto() };
            self.ValidarNombreProducto(data,$evento);
          }

          var $inputProducto =  $(self.InputProducto());
          var pos = $inputProducto.closest("tr").find("input").index($inputProducto);
          $inputProducto.closest("tr").find("input").eq(pos+1).focus();
        }
        else {
          if($(self.InputCodigoMercaderia()).attr("data-validation-text-found") === $(self.InputProducto()).val())  {
              $(self.InputCodigoMercaderia()).attr("data-validation-found","true");
              var $evento = { target : self.InputProducto() };
              self.ValidarNombreProducto(data,$evento);

              var $inputProducto =  $(self.InputProducto());
              var pos = $inputProducto.closest("tr").find("input").index($inputProducto);
              $inputProducto.closest("tr").find("input").eq(pos+1).focus();
          }
          else {

            $(self.InputCodigoMercaderia()).attr("data-validation-text-found",data.NombreProducto);
            $(self.InputCodigoMercaderia()).attr("data-validation-found","true");
            var $evento = { target : self.InputProducto() };
            self.ValidarNombreProducto(data,$evento);
            var $evento2 = { target : self.InputCodigoMercaderia() };
            var $data = Knockout.CopiarObjeto(data);

            self.ValidarProductoPorCodigo($data,$evento2,function($data3,$evento3){
              var $inputProducto =  $(self.InputProducto());
              var pos = $inputProducto.closest("tr").find("input").index($inputProducto);
              $inputProducto.closest("tr").find("input").eq(pos+1).focus();
            });
          }
        }
      }
    }

    self.ValidarCantidad = function(data,event) {
      if(event) {
        if(data.CantidadInicial() === "") data.CantidadInicial("0.00");

        $(event.target).validate(function(valid, elem) {
        });

      }
    }

    self.ValidarValorUnitario = function(data,event) {
      if(event) {
        if(data.ValorUnitario() === "") data.ValorUnitario("0.00");
        $(event.target).validate(function(valid, elem) {

        });
      }
    }

    self.ValidarDescuentoItem = function(data,event) {
      if(event) {
        if(data.DescuentoItem() ==="") data.DescuentoItem("0.00");

        $(event.target).validate(function(valid, elem) {
        });

      }
    }

    self.OnKeyEnterOpcion = function(data,event,$callbackParent) {
      if(event) {
        if(event.keyCode === TECLA_ENTER) {
          $callbackParent(data,event);
        }
        else if (event.keyCode === TECLA_TAB) {

          var ultimafila =$("tr:last").index();
          var fila = $(event.target).closest("tr").index();

          if (fila === ultimafila -1 ) {
              $("#DescuentoGlobal").focus();
              event.preventDefault();
              event.stopPropagation();
          }
        }

        return true;
    }
  }

  self.ValidarFechaVencimiento = function(data,event) {
    if(event) {
      $(event.target).validate(function(valid, elem) {

      });
    }
  }

  self.ValidarFechaEmisionDocumentoSalidaZofra = function(data,event) {
    if(event) {
      $(event.target).validate(function(valid, elem) {

      });
    }
  }

  self.ValidarFechaEmisionDua = function(data,event) {
    if(event) {
      $(event.target).validate(function(valid, elem) {

      });
    }
  }

  self.ValidarLote = function(data,event) {
    if(event) {
      $(event.target).validate(function(valid, elem) {

      });
      if(self.hasOwnProperty("ListaLotes"))
      {
        var objeto_lote = ko.mapping.toJS(self.ListaLotes);
        var opcion_lote = false;
        objeto_lote.forEach(function(entry, key){
          if(entry.NumeroLote == data.NumeroLote()){
            opcion_lote = true;
          }
        });

        if(opcion_lote)
        {
          $(self.InputNumeroLote()).closest('div').removeClass('has-new');
        }
        else {
          $(self.InputNumeroLote()).closest('div').addClass('has-new');
        }
      }

    }
  }

  self.ValidarNumeroDocumentoSalidaZofra = function(data,event) {
    if(event) {
      $(event.target).validate(function(valid, elem) {
      });
    }
  }

  self.ValidarNumeroDua = function(data,event) {
    if(event) {
      $(event.target).validate(function(valid, elem) {
      });
    }
  }

  self.ValidarNumeroItemDua = function(data,event) {
    if(event) {
      $(event.target).validate(function(valid, elem) {
      });
    }
  }

}
