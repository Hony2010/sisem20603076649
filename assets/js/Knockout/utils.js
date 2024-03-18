var configuracionValidacion = {
    errorElementClass: 'error',
    messagesOnModified: false,
    decorateInputElement: true,
    insertMessages: false
}

var Knockout = Knockout || {};

Knockout.CopiarObjeto = function (objeto) {
    var json = Knockout.ConvertirJson(objeto);
    var objetoCopiado = ko.mapping.fromJSON(json);
    return objetoCopiado;
}

Knockout.ConvertirJson = function (aoObjeto) {
    return JSON.stringify(ko.mapping.toJS(aoObjeto), function (key, value) {
        if (key != 'parent') {
            return value;
        };
    });
}

Knockout.CopiarJS = function (objeto) {
    var json = Knockout.ConvertirJson(objeto);
    var objetoCopiado = ko.mapping.fromJSON(json);
    return ko.toJS(objetoCopiado);
}

ko.bindingHandlers.fadeVisible = {
    init: function (element, valueAccessor) {
        // Initially set the element to be instantly visible/hidden depending on the value
        var value = valueAccessor();
        $(element).toggle(ko.utils.unwrapObservable(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
    },
    update: function (element, valueAccessor) {
        // Whenever the value subsequently changes, slowly fade the element in or out
        var value = valueAccessor();

        //ko.utils.unwrapObservable(value) ? $(element).fadeIn("fast") : $(element).fadeOut("fast");
        ko.utils.unwrapObservable(value) ? $(element).fadeIn("fast") : $(element).hide();

    }
};

ko.validation.registerExtenders();

ko.validation.init(configuracionValidacion);

// override del init para todos los value

var originalKoValueInit = ko.bindingHandlers.value.init;
ko.bindingHandlers.value.init = function (element, valueAccessor, allBindingsAccessor, viewModel, bindingContext) {
    originalKoValueInit(element, valueAccessor, allBindingsAccessor, viewModel, bindingContext);
    var observable = valueAccessor();
    if (!ko.validation.utils.isValidatable(observable))
    { return; }
    else {
        observable.isModified(false);
    };

}


var mappingIgnore = {
    'ignore': '__ko_mapping__'
}

function leaveJustIncludedProperties(data, includesList) {
  var result = {};
  (includesList || []).forEach(function(propertyName) {
    if (data[propertyName] !=undefined)
      result[propertyName] = data[propertyName];
  });
  return result;
}


ko.bindingHandlers.bootstrapmodal = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel,bindingContext) {
        //console.log("bootstrapmodal");
        var $element = $(element);
        var value = valueAccessor(), allBindings = allBindingsAccessor();
        var valueUnwrapped = ko.utils.unwrapObservable(value);
        //console.log(value);
        //properties - bootstrap's
        var backdrop = allBindings.backdrop || true;
        var keyboard = allBindings.keyboard || true;
        var show = allBindings.show || false;
        var remote = allBindings.remote || false;
        //properties - custom

        //event bindings
        var onshow = allBindings.onshow || null;
        var onshown = allBindings.onshown || null;
        var onhide = allBindings.onhide || null;
        var onhiden = allBindings.onhiden || null;

        $element.modal({
            backdrop: backdrop,
            keyboard: keyboard,
            show: false,//show,
            remote: remote
        });

        //bind events
        $element.on('show.bs.modal', function () {
            if (onshow && typeof onshow == 'function') {
                onshow.call();
            }
        });

        $element.on('shown.bs.modal', function () {
            if (onshown && typeof onshown == 'function') {
                onshown.call();
            }
        });

        $element.on('hide.bs.modal', function () {
            if (onhide && typeof onhide == 'function') {
                onhide.call();
            }
        });

        $element.on('hidden.bs.modal', function () {
            if (onhiden && typeof onhiden == 'function') {
                if (ko.isObservable(value)) { value(false); }
                onhiden.call();
            }
        });
    },
    update: function (element, valueAccessor) {
      //console.log("bootstrapmodal.update");
      //console.log(valueAccessor);
      var value = valueAccessor();
      ko.unwrap(value) ? $(element).modal('show') : $(element).modal('hide');
    }
};

ko.bindingHandlers.numbertrim = {
  init: function(element, valueAccessor, allBindings) {
       var value = valueAccessor();
      ko.bindingHandlers.value.init(element,valueAccessor, allBindings);
  },
  update: function(element, valueAccessor) {
     var value = valueAccessor();
     var valueElemento = value();
     if($.type(value()) === "string"){valueElemento = valueElemento.replace(/,/g, '')};     

     if ($.isNumeric(valueElemento)) {
       var valor = value();
       var resultado = accounting.formatNumber(valor, NUMERO_DECIMALES_VENTA);
       value(resultado);
       $(element).val(resultado);
     }
     else if(isNaN(value()))  {
       if (value() !== "" ) {
           var valor = "0.00";
           value(valor);
           $(element).val(valor);
       }
     }

     ko.bindingHandlers.value.update(element,valueAccessor);
  }
};

ko.bindingHandlers.number = {
  init: function(element, valueAccessor, allBindings) {
       var value = valueAccessor();
      ko.bindingHandlers.value.init(element,valueAccessor, allBindings);
  },
  update: function(element, valueAccessor) {
     var value = valueAccessor();

     if ($.isNumeric(value())) {
       var valor = value();
       value(valor);
       $(element).val(valor);
     }
     else if(isNaN(value()))  {
       if (value() !== "" ) {
           var valor = "0";
           value(valor);
           $(element).val(valor);
       }
     }

     ko.bindingHandlers.value.update(element,valueAccessor);
  }
};

ko.bindingHandlers.numberdecimal = {
  init: function(element, valueAccessor, allBindings) {
       var value = valueAccessor();
      ko.bindingHandlers.value.init(element,valueAccessor, allBindings);
  },
  update: function(element, valueAccessor) {
     var value = valueAccessor();
     var valueElemento = value();
     if($.type(value()) === "string"){valueElemento = valueElemento.replace(/,/g, '')};

     if ($.isNumeric(valueElemento)) {
       var valor = valueElemento;
       var resultado = accounting.formatNumber(valor, element.dataset.cantidadDecimal);
       value(resultado);
       $(element).val(resultado);
     }
     else if(isNaN(valueElemento))  {
       if (valueElemento !== "" ) {
           var valor = accounting.formatNumber(0, element.dataset.cantidadDecimal);
           value(valor);
           $(element).val(valor);
       }
     }

     ko.bindingHandlers.value.update(element,valueAccessor);
  }
};

ko.bindingHandlers.numberempty = {
  init: function(element, valueAccessor, allBindings) {
      ko.bindingHandlers.value.init(element,valueAccessor, allBindings);
  },
  update: function(element, valueAccessor) {
     var value = valueAccessor();
     if (value() == "" || isNaN(value())) {
       var valor = "0.00";
       value(valor);
       $(element).val(valor);
     }

     ko.bindingHandlers.value.update(element,valueAccessor);
  }
};

ko.bindingHandlers.ko_autocomplete = {
    init: function (element, params, valueAccessor) {
      var updateElementValueWithLabel = function (event, ui) {
            // Stop the default behavior
            event.preventDefault();

            // Update our SelectedOption observable
            if(ui.item) {
                // Update the value of the html element with the label
                // of the activated option in the list (ui.item)
                // ui.item - label|value|...
                $(element).val(ui.item.value);
                $(element).change();
                // valueAccessor(ui.item.label);
            }else{
                // valueAccessor($(element).val());
                $(element).change();
            }
        };

        $(element).autocomplete({
          source: function (request, response) {
            response($.map(params().source, function (value, key) {
                return {
                    label: value.NombreTipoListaPrecio + ' - S/ ' + value.Precio,
                    value: value.Precio,
                    // This way we still have acess to the original object
                    object: value
                }
            }));
              // response(data_listaprecios.Nombre);
          },
          minLength: 0,
          select: function (event, ui) {
              updateElementValueWithLabel(event, ui);
          },
          change: function (event, ui) {
              updateElementValueWithLabel(event, ui);
              // alert(ui.item.value);
          }
        }).focus(function(){
            $(this).data("uiAutocomplete").search('');
        });
    },
    update: function (element, params, valueAccessor) {
      var item = valueAccessor();

      var updateElementValueWithLabel = function (event, ui) {
            // Stop the default behavior
            event.preventDefault();
            // item.value(10);
            // console.log(item.value());
            // Update our SelectedOption observable
            if(ui.item) {
                // Update the value of the html element with the label
                // of the activated option in the list (ui.item)
                // ui.item - label|value|...
                $(element).val(ui.item.value);
                $(element).change();
                // valueAccessor(ui.item.label);
            }else{
                // valueAccessor($(element).val());
                $(element).change();
            }
        };

      $(element).autocomplete({
        source: function (request, response) {
          var raleo = params().raleo;
          if(raleo.length > 0)
          {
            var fila = null;
            raleo.forEach(function(entry, key){
              if(parseFloat(entry.NombreTipoListaRaleo) == parseFloat(params().cantidad()))
              {
                fila = entry;
              }
            });
            if(fila != null)
            {
              response(null);
            }
            else {
              response($.map(params().source, function (value, key) {
                  return {
                      label: value.NombreTipoListaPrecio + ' - S/ ' + value.Precio,
                      value: value.Precio,
                      // This way we still have acess to the original object
                      object: value
                  }
              }));
            }
          }
          else {
            response($.map(params().source, function (value, key) {
                return {
                    label: value.NombreTipoListaPrecio + ' - S/ ' + value.Precio,
                    value: value.Precio,
                    // This way we still have acess to the original object
                    object: value
                }
            }));
          }
            // response(data_listaprecios.Nombre);
        },
        minLength: 0,
        select: function (event, ui) {
            updateElementValueWithLabel(event, ui);
        },
        change: function (event, ui) {
            updateElementValueWithLabel(event, ui);
            // alert(ui.item.value);
        }
      }).focus(function(){
          $(this).data("uiAutocomplete").search('');
      });

    }
};

ko.bindingHandlers.ko_autocomplete_cantidad = {
    init: function (element, params, valueAccessor) {
      var item = valueAccessor();
      var updateElementValueWithLabel = function (event, ui) {
            // Stop the default behavior
            event.preventDefault();

            // Update our SelectedOption observable
            if(ui.item) {
                // Update the value of the html element with the label
                // of the activated option in the list (ui.item)
                // ui.item - label|value|...
                $(element).val(ui.item.value);
                $(element).change();
                // updatePrecioValue();
                // valueAccessor(ui.item.label);
            }else{
                // valueAccessor($(element).val());
                $(element).change();
                // updatePrecioValue();
            }
        };

        $(element).autocomplete({
          source: function (request, response) {
            response($.map(params().raleo, function (value, key) {
                return {
                    label: value.NombreTipoListaRaleo,
                    value: value.NombreTipoListaRaleo, //value.Precio,
                    // This way we still have acess to the original object
                    object: value
                }
            }));
              // response(data_listaprecios.Nombre);
          },
          minLength: 0,
          select: function (event, ui) {
              updateElementValueWithLabel(event, ui);
          },
          change: function (event, ui) {
              updateElementValueWithLabel(event, ui);
              // alert(ui.item.value);
          }
        }).focus(function(){
            $(this).data("uiAutocomplete").search('');
        });
    },
    update: function (element, params, valueAccessor) {
      var item = valueAccessor();
      var updateElementValueWithLabel = function (event, ui) {
            // Stop the default behavior
            event.preventDefault();

            // Update our SelectedOption observable
            if(ui.item) {
                // Update the value of the html element with the label
                // of the activated option in the list (ui.item)
                // ui.item - label|value|...
                $(element).val(ui.item.value);
                $(element).change();
                // updatePrecioValue();
                // valueAccessor(ui.item.label);
            }else{
                // valueAccessor($(element).val());
                $(element).change();
                // updatePrecioValue();
            }
        };

        $(element).autocomplete({
          source: function (request, response) {
            response($.map(params().raleo, function (value, key) {
                return {
                    label: value.NombreTipoListaRaleo,
                    value: value.NombreTipoListaRaleo, //value.Precio,
                    // This way we still have acess to the original object
                    object: value
                }
            }));
              // response(data_listaprecios.Nombre);
          },
          minLength: 0,
          select: function (event, ui) {
              updateElementValueWithLabel(event, ui);
          },
          change: function (event, ui) {
              updateElementValueWithLabel(event, ui);
              // alert(ui.item.value);
          }
        }).focus(function(){
            $(this).data("uiAutocomplete").search('');
        });

    }
};

ko.bindingHandlers.ko_autocomplete_lote = {
    init: function (element, params, valueAccessor) {
      var item = valueAccessor();
      var id = params().id;
      var updateElementValueWithLabel = function (event, ui) {
            event.preventDefault();
            if(ui.item) {
                $(element).val(ui.item.value);
                $(element).change();
                id(ui.item.object.IdLoteProducto);
            }else{
                $(element).change();
            }
        };
        $(element).autocomplete({
          source: function (request, response) {
            response($.map(params().lote, function (value, key) {
                return {
                  label: value.NumeroLote+' - '+value.FechaVencimiento+' - '+'STOCK: '+value.StockProductoLote,
                  value: value.NumeroLote,
                  object: value
                }
            }));
          },
          minLength: 0,
          select: function (event, ui) {
              updateElementValueWithLabel(event, ui);
          },
    			focus: function( event, ui ) {
    				updateElementValueWithLabel(event, ui);
    			}
          // ,
          // change: function (event, ui) {
          //     updateElementValueWithLabel(event, ui);
          // }
        }).focus(function(){
            $(this).data("uiAutocomplete").search('');
        });
    },
    update: function (element, params, valueAccessor) {
      var item = valueAccessor();
      var id = params().id;
      var updateElementValueWithLabel = function (event, ui) {
            event.preventDefault();
            if(ui.item) {
                $(element).val(ui.item.value);
                $(element).change();
                id(ui.item.object.IdLoteProducto);
            }else{
                $(element).change();
            }
        };

        $(element).autocomplete({
          source: function (request, response) {
            response($.map(params().lote, function (value, key) {
                return {
                  label: value.NumeroLote+' - '+value.FechaVencimiento+' - '+'STOCK: '+value.StockProductoLote,
                  value: value.NumeroLote,
                  object: value
                }
            }));
          },
          minLength: 0,
          select: function (event, ui) {
              updateElementValueWithLabel(event, ui);
          },
    			focus: function( event, ui ) {
    				updateElementValueWithLabel(event, ui);
    			}
          // ,
          // change: function (event, ui) {
          //   $(this).autocomplete('search', $(this).val());
          //     updateElementValueWithLabel(event, ui);
          // }
        }).focus(function(){
            $(this).data("uiAutocomplete").search('');
        });

    }
};

ko.bindingHandlers.ko_autocomplete_zofra = {
    init: function (element, params, valueAccessor) {
      var item = valueAccessor();
      var id = params().id;
      var updateElementValueWithLabel = function (event, ui) {
            event.preventDefault();
            if(ui.item) {
                $(element).val(ui.item.value);
                $(element).change();
                id(ui.item.object.IdDocumentoSalidaZofraProducto);
            }else{
                $(element).change();
            }
        };
        $(element).autocomplete({
          source: function (request, response) {
            response($.map(params().zofra, function (value, key) {
                return {
                  label: value.NumeroDocumentoSalidaZofra+' - '+ 'STOCK: '+value.StockDocumentoSalidaZofra,
                  value: value.NumeroDocumentoSalidaZofra,
                  object: value
                }
            }));
          },
          minLength: 0,
          select: function (event, ui) {
              updateElementValueWithLabel(event, ui);
          },
    			focus: function( event, ui ) {
    				updateElementValueWithLabel(event, ui);
    			}
          // ,
          // change: function (event, ui) {
          //     updateElementValueWithLabel(event, ui);
          // }
        }).focus(function(){
            $(this).data("uiAutocomplete").search('');
        });
    },
    update: function (element, params, valueAccessor) {
      var item = valueAccessor();
      var id = params().id;
      var updateElementValueWithLabel = function (event, ui) {
            event.preventDefault();
            if(ui.item) {
                $(element).val(ui.item.value);
                $(element).change();
                id(ui.item.object.IdDocumentoSalidaZofraProducto);
            }else{
                $(element).change();
            }
        };

        $(element).autocomplete({
          source: function (request, response) {
            response($.map(params().zofra, function (value, key) {
                return {
                  label: value.NumeroDocumentoSalidaZofra+' - '+ 'STOCK: '+value.StockDocumentoSalidaZofra,
                  value: value.NumeroDocumentoSalidaZofra,
                  object: value
                }
            }));
          },
          minLength: 0,
          select: function (event, ui) {
              updateElementValueWithLabel(event, ui);
          },
    			focus: function( event, ui ) {
    				updateElementValueWithLabel(event, ui);
    			}
          // ,
          // change: function (event, ui) {
          //   $(this).autocomplete('search', $(this).val());
          //     updateElementValueWithLabel(event, ui);
          // }
        }).focus(function(){
            $(this).data("uiAutocomplete").search('');
        });

    }
};

ko.bindingHandlers.ko_autocomplete_dua = {
    init: function (element, params, valueAccessor) {
      var item = valueAccessor();
      var id = params().id;
      var updateElementValueWithLabel = function (event, ui) {
            event.preventDefault();
            if(ui.item) {
                $(element).val(ui.item.value);
                $(element).change();
                id(ui.item.object.IdDuaProducto);
            }else{
                $(element).change();
            }
        };
        $(element).autocomplete({
          source: function (request, response) {
            response($.map(params().dua, function (value, key) {
                return {
                  label: value.NumeroItemDua+' - '+value.NumeroDua+' - '+'STOCK: '+value.StockDua,
                  value: value.NumeroDua,
                  object: value
                }
            }));
          },
          minLength: 0,
          select: function (event, ui) {
              updateElementValueWithLabel(event, ui);
          },
    			focus: function( event, ui ) {
    				updateElementValueWithLabel(event, ui);
    			}
          // ,
          // change: function (event, ui) {
          //     updateElementValueWithLabel(event, ui);
          // }
        }).focus(function(){
            $(this).data("uiAutocomplete").search('');
        });
    },
    update: function (element, params, valueAccessor) {
      var item = valueAccessor();
      var id = params().id;
      var updateElementValueWithLabel = function (event, ui) {
            event.preventDefault();
            if(ui.item) {
                $(element).val(ui.item.value);
                $(element).change();
                id(ui.item.object.IdDuaProducto);
            }else{
                $(element).change();
            }
        };

        $(element).autocomplete({
          source: function (request, response) {
            response($.map(params().dua, function (value, key) {
                return {
                  label: value.NumeroItemDua+' - '+value.NumeroDua+' - '+'STOCK: '+value.StockDua,
                  value: value.NumeroDua,
                  object: value
                }
            }));
          },
          minLength: 0,
          select: function (event, ui) {
              updateElementValueWithLabel(event, ui);
          },
    			focus: function( event, ui ) {
    				updateElementValueWithLabel(event, ui);
    			}
          // ,
          // change: function (event, ui) {
          //   $(this).autocomplete('search', $(this).val());
          //     updateElementValueWithLabel(event, ui);
          // }
        }).focus(function(){
            $(this).data("uiAutocomplete").search('');
        });

    }
};

ko.onError = function(error) {
  $("#loader").hide();
  alert("knockout error",error);
    //myLogger("knockout error", error);
};
