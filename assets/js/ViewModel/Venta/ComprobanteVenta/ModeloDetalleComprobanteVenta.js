ModeloDetalleComprobanteVenta = function (data) {
  var self = this;
  var base = data;
  var temporaltipoventa = (ViewModels.data.ComprobanteVenta == undefined ? ViewModels.data.ComprobanteVentaNuevo.IdTipoVenta() : ViewModels.data.ComprobanteVenta.TipoVenta());
  self.TipoVenta = ko.observable(temporaltipoventa);// ko.observable(TIPO_VENTA.MERCADERIAS);
  self.IdSede = ko.observable("");
  self.CodigoServicio = ko.observable();
  self.CodigoActivoFijo = ko.observable();
  self.CodigoOtraVenta = ko.observable();
  self._DataLotes = ko.observable([]);
  self._DataListaZofra = ko.observable([]);
  self._DataListaDua = ko.observable([]);
  self.PrecioUnitarioEspecialCliente = ko.observable();
  self.ProductoAnterior = ko.observable({});

  self.DataPrecios = function () {
    if (self.hasOwnProperty("ListaPrecios")) {
      var listaprecio = ko.mapping.toJS(self.ListaPrecios);
      return listaprecio;
    }
    else {
      return [];
    }
  }


  self.DataRaleo = function () {
    if (self.hasOwnProperty("ListaRaleos")) {
      var listaraleo = ko.mapping.toJS(self.ListaRaleos);
      return listaraleo;
    }
    else {
      return [];
    }
  }

  self.DataLotes = function (asignacionsede) {
    if (self.hasOwnProperty("ListaLotes")) {
      var nuevalista = [];
      var listalotes = ko.mapping.toJS(self.ListaLotes);
      if (listalotes) {
        listalotes.forEach(function (entry, key) {
          if (entry.IdAsignacionSede == asignacionsede) {
            nuevalista.push(entry);
          }
        })
        self._DataLotes(nuevalista);
        return nuevalista;
      }
      else {
        return [];
      }
    }
    else {
      return [];
    }
  }

  self.DataListaZofra = function (asignacionsede) {
    if (self.hasOwnProperty("ListaZofra")) {
      var nuevalista = [];
      var listazofra = ko.mapping.toJS(self.ListaZofra);
      if (listazofra) {
        listazofra.forEach(function (entry, key) {
          if (entry.IdAsignacionSede == asignacionsede) {
            nuevalista.push(entry);
          }
        })
        self._DataListaZofra(nuevalista);
        return nuevalista;
      } else {
        return [];
      }

    }
    else {
      return [];
    }
  }

  self.DataListaDua = function (asignacionsede) {
    if (self.hasOwnProperty("ListaDua")) {
      var nuevalista = [];
      var listadua = ko.mapping.toJS(self.ListaDua);
      if (listadua) {
        listadua.forEach(function (entry, key) {
          if (entry.IdAsignacionSede == asignacionsede) {
            nuevalista.push(entry);
          }
        })
        self._DataListaDua(nuevalista);
        return nuevalista;
      }
      else {
        return [];
      }
    }
    else {
      return [];
    }
  }

  self.InicializarModelo = function (event) {
    if (event) {

    }
  }

  self.CalcularSubTotal = function (data, event) {
    if (event) {
      if (self.cabecera.ParametroCalculoIGVDesdeTotal() == 0) {
        self.CalcularSubTotalPorPrecioUnitario(data, event);
      } else {
        self.CalcularValorVentaItemPorValorUnitario(data, event);
      }
    }
    self.CalcularICBPER(data, event);
  }

  self.ObtenerPeriodoICBPER = function (event) {
    if (event) {

      var fecha = $("#FechaEmision").val();

      var periodos = self.cabecera.PeriodosTasaImpuestoBolsa()
      var año = fecha.split("/")[2];
      var periodo = [];
      if (año < periodos[1].Periodo()) {
        periodo = periodos[0];
      } else if (año >= periodos[periodos.length - 1].Periodo()) {
        periodo = periodos[periodos.length - 1];
      } else {
        ko.utils.arrayForEach(periodos, function (item) {
          if (item.Periodo() == año) {

            periodo = item
          }
        });
      }

      return periodo;
    }
  }

  self.CalcularICBPER = function (data, event) {
    if (event) {
      var cantidad = self.Cantidad() == "" ? 0 : parseFloatAvanzado(self.Cantidad());

      if (self.AfectoICBPER) {
        if (self.AfectoICBPER() == 1) {
          var periodo = self.ObtenerPeriodoICBPER(event);
          var dataPeriodo = {
            FactorICBP: periodo.Tasa(),
            IdPeriodoTasaImpuestoBolsa: periodo.IdPeriodoTasaImpuestoBolsa(),
            SumatoriaICBP: (cantidad * parseFloatAvanzado(periodo.Tasa()))
          }
        } else {
          var dataPeriodo = {
            FactorICBP: TASA_ICBP_POR_DEFECTO,
            IdPeriodoTasaImpuestoBolsa: ID_ICBP_POR_DEFECTO,
            SumatoriaICBP: (cantidad * TASA_ICBP_POR_DEFECTO)
          }
        }
      }
      else {
        var dataPeriodo = {
          FactorICBP: TASA_ICBP_POR_DEFECTO,
          IdPeriodoTasaImpuestoBolsa: ID_ICBP_POR_DEFECTO,
          SumatoriaICBP: (cantidad * TASA_ICBP_POR_DEFECTO)
        }
      }

      self.FactorICBP(dataPeriodo.FactorICBP);
      self.IdPeriodoTasaImpuestoBolsa(dataPeriodo.IdPeriodoTasaImpuestoBolsa);
      self.SumatoriaICBP(dataPeriodo.SumatoriaICBP);

    }
  }

  self.CalcularSubTotalPorPrecioUnitario = function (data, event) {
    if (event) {
      if (base) {
        var resultado = "";
        if (base.NombreProducto !== undefined) {
          if (base.Cantidad() === "" || base.PrecioUnitario() === "" || base.DescuentoUnitario() === "") {
            base.SubTotal(0);
            base.ValorVentaItem(0);
            resultado = "";
          } else {
            var cantidad = parseFloatAvanzado(base.Cantidad());
            var precioUnitario = parseFloatAvanzado(base.PrecioUnitario());
            var descuentoUnitario = parseFloatAvanzado(base.DescuentoUnitario());
            var tasaIGV = parseFloatAvanzado(VALOR_IGV);
            var subTotal = 0.00;

            if (base.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_GRAVADO) {
              var valorUnitario = (precioUnitario / (1 + tasaIGV));
              var descuentoValorUnitario = (descuentoUnitario / (1 + tasaIGV));
              var valorUnitarioNeto = (valorUnitario - descuentoValorUnitario);
              var valorVentaNetoItem = (valorUnitarioNeto * cantidad);
              var igvItem = (valorVentaNetoItem * tasaIGV);
            } else {
              var valorUnitario = precioUnitario;
              var descuentoValorUnitario = descuentoUnitario;
              var valorUnitarioNeto = (valorUnitario - descuentoValorUnitario);
              var valorVentaNetoItem = (valorUnitarioNeto * cantidad);
              var igvItem = 0.00;
            }

            var precioUnitarioNeto = (precioUnitario - descuentoUnitario);
            var descuentoValorItem = (descuentoValorUnitario * cantidad);
            var descuentoItem = (descuentoUnitario * cantidad);
            var valorVentaItem = (valorUnitario * cantidad);
            var tasaDescuentoItem = (descuentoValorItem / valorVentaItem);

            var subTotal = (precioUnitarioNeto * cantidad);

            subTotal = subTotal == 'Infinity' ? 0 : subTotal;

            base.PrecioUnitarioNeto(precioUnitarioNeto.toFixed(NUMERO_DECIMALES_VENTA));
            base.ValorUnitarioNeto(valorUnitarioNeto.toFixed(NUMERO_DECIMALES_VENTA));
            base.DescuentoValorItem(descuentoValorItem.toFixed(NUMERO_DECIMALES_VENTA));
            base.ValorVentaNetoItem(valorVentaNetoItem.toFixed(NUMERO_DECIMALES_VENTA));
            base.SubTotal(subTotal.toFixed(NUMERO_DECIMALES_VENTA));
            base.ValorUnitario(valorUnitario.toFixed(NUMERO_DECIMALES_VENTA));
            base.ValorVentaItem(valorVentaItem.toFixed(NUMERO_DECIMALES_VENTA));
            base.IGVItem(igvItem.toFixed(NUMERO_DECIMALES_VENTA));
            base.DescuentoItem(descuentoItem.toFixed(NUMERO_DECIMALES_VENTA));
            base.DescuentoValorUnitario(descuentoValorUnitario.toFixed(NUMERO_DECIMALES_VENTA));
            base.TasaDescuentoItem(tasaDescuentoItem.toFixed(5));

            if (base.IndicadorOperacionGratuita() == 1) {
              var valorreferencial = parseFloatAvanzado(base.ValorReferencial());
              var valorreferencialitem = valorreferencial * base.Cantidad();
              var igvreferencialitem = valorreferencialitem * parseFloatAvanzado(tasaIGV);
              base.ValorReferencialItem(valorreferencialitem);
              base.IGVReferencialItem(igvreferencialitem);
            }
            else {
              base.ValorReferencial(0);
              base.ValorReferencialItem(0);
              base.IGVReferencialItem(0);
            }
            
          }
          return resultado;
        } else {
          base.SubTotal(0);
          return resultado;
        }
      }
    }
  }

  self.CalcularValorVentaItemPorValorUnitario = function (data, event) {
    if (event) {
      if (base) {
        var resultado = "";
        if (base.NombreProducto !== undefined) {
          if (base.Cantidad() === "" || base.ValorUnitario() === "" || base.DescuentoUnitario() === "") {
            base.SubTotal(0);
            resultado = "";
          } else {
            var cantidad = parseFloatAvanzado(base.Cantidad());
            var valorUnitario = parseFloatAvanzado(base.ValorUnitario());
            var descuentoValorUnitario = parseFloatAvanzado(base.DescuentoValorUnitario());
            var tasaIGV = parseFloatAvanzado(VALOR_IGV);
            var subTotal = 0.00;

            var valorUnitarioNeto = (valorUnitario - descuentoValorUnitario);
            var valorVentaNetoItem = (valorUnitarioNeto * cantidad);
            
            if (base.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_GRAVADO) {
              var precioUnitario = (valorUnitario * (1 + tasaIGV));
              var descuentoUnitario = (descuentoValorUnitario * (1 + tasaIGV));
              var igvItem = (valorVentaNetoItem * tasaIGV);
            } else {
              var precioUnitario = valorUnitario;
              var descuentoUnitario = descuentoValorUnitario;
              var igvItem = 0;
            }
            
            var precioUnitarioNeto = (precioUnitario - descuentoUnitario);
            var descuentoValorItem = (descuentoValorUnitario * cantidad);
            var descuentoItem = (descuentoUnitario * cantidad);
            var valorVentaItem = (valorUnitario * cantidad);
            var subTotal = (precioUnitarioNeto * cantidad);
            var tasaDescuentoItem = (descuentoValorItem / valorVentaItem);

            subTotal = subTotal == 'Infinity' ? 0 : subTotal;

            base.PrecioUnitarioNeto(precioUnitarioNeto.toFixed(NUMERO_DECIMALES_VENTA));
            base.ValorUnitarioNeto(valorUnitarioNeto.toFixed(NUMERO_DECIMALES_VENTA));
            base.DescuentoValorItem(descuentoValorItem.toFixed(NUMERO_DECIMALES_VENTA));
            base.ValorVentaNetoItem(valorVentaNetoItem.toFixed(NUMERO_DECIMALES_VENTA));
            base.SubTotal(subTotal.toFixed(NUMERO_DECIMALES_VENTA));
            base.PrecioUnitario(precioUnitario.toFixed(NUMERO_DECIMALES_VENTA));
            base.ValorVentaItem(valorVentaItem.toFixed(NUMERO_DECIMALES_VENTA));
            base.IGVItem(igvItem.toFixed(NUMERO_DECIMALES_VENTA));
            base.DescuentoItem(descuentoItem.toFixed(NUMERO_DECIMALES_VENTA));
            base.DescuentoUnitario(descuentoUnitario.toFixed(NUMERO_DECIMALES_VENTA));
            base.TasaDescuentoItem(tasaDescuentoItem.toFixed(5));
          }
          return resultado;
        } else {
          base.SubTotal(0);
          return resultado;
        }
      }
    }
  }

  self.CalcularDescuentoUnitarioPorPorcentaje = function (data, event) {
    if (event) {
      var porcentajedescuento = parseFloatAvanzado(self.PorcentajeDescuento());
      var preciounitario = parseFloatAvanzado(self.PrecioUnitario());
      var descuentounitario = 0;
      if (porcentajedescuento > 0) {
        descuentounitario = (preciounitario * porcentajedescuento) / 100;
      }
      self.DescuentoUnitario(descuentounitario);
    }
  }

  self.CalcularCantidad = function (data, event) {
    if (event) {
      if (base) {
        var resultado = "";
        if (base.NombreProducto !== undefined) {
          if (base.SubTotal() === "" || base.PrecioUnitario() === "" || base.DescuentoUnitario() === "") {
            base.Cantidad(0);
            base.ValorVentaItem(0);
            resultado = "";
          } else {
            var precioUnitario = parseFloatAvanzado(base.PrecioUnitario());
            var descuentoUnitario = parseFloatAvanzado(base.DescuentoUnitario());
            var tasaIGV = parseFloatAvanzado(VALOR_IGV);
            var descuentoValorUnitario = 0.00
            var subTotal = parseFloatAvanzado(base.SubTotal());

            if (base.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_GRAVADO) {
              var valorUnitario = ((precioUnitario - descuentoUnitario) / (1 + tasaIGV)).toFixed(NUMERO_DECIMALES_VALOR_UNITARIO_VENTA);
              var cantidad = (subTotal / (precioUnitario - descuentoUnitario)).toFixed(CANTIDAD_DECIMALES_VENTA.CANTIDAD);
              var valorVentaItem = (parseFloatAvanzado(subTotal) / (1 + tasaIGV)).toFixed(NUMERO_DECIMALES_VENTA);
              var igvItem = (parseFloatAvanzado(subTotal) - parseFloatAvanzado(valorVentaItem)).toFixed(NUMERO_DECIMALES_VENTA);
            } else {
              var valorUnitario = (precioUnitario - descuentoUnitario).toFixed(NUMERO_DECIMALES_VALOR_UNITARIO_VENTA);
              var cantidad = (subTotal / (precioUnitario - descuentoUnitario)).toFixed(CANTIDAD_DECIMALES_VENTA.CANTIDAD);
              var valorVentaItem = (cantidad * parseFloatAvanzado(valorUnitario)).toFixed(NUMERO_DECIMALES_VENTA);
              var igvItem = 0.00;
            }

            subTotal = subTotal == 'Infinity' ? 0 : subTotal;

            base.Cantidad(cantidad);
            base.ValorUnitario(parseFloatAvanzado(valorUnitario).toFixed(NUMERO_DECIMALES_VENTA));
            base.ValorVentaItem(parseFloatAvanzado(valorVentaItem).toFixed(NUMERO_DECIMALES_VENTA));
            base.IGVItem(parseFloatAvanzado(igvItem).toFixed(NUMERO_DECIMALES_VENTA));
            base.DescuentoItem((descuentoUnitario * cantidad).toFixed(NUMERO_DECIMALES_VENTA));
            base.DescuentoValorUnitario(descuentoValorUnitario.toFixed(NUMERO_DECIMALES_VENTA));
          }
          return resultado;
        } else {
          base.SubTotal(0);
          return resultado;
        }
      }
    }
  }

  self.CalcularPrecioUnitario = function (data, event) {
    if (event) {
      if (base) {
        var resultado = "";
        if (base.NombreProducto !== undefined) {
          if (base.Cantidad() === "" || base.SubTotal() === "" || base.DescuentoUnitario() === "") {
            base.PrecioUnitario(0);
            base.ValorVentaItem(0);
            resultado = "";
          } else {
            var cantidad = parseFloatAvanzado(base.Cantidad());
            var descuentoUnitario = parseFloatAvanzado(base.DescuentoUnitario());
            var tasaIGV = parseFloatAvanzado(VALOR_IGV);
            var descuentoValorUnitario = 0.00
            var subTotal = parseFloatAvanzado(base.SubTotal());

            if (base.CodigoTipoAfectacionIGV() == CODIGO_AFECTACION_IGV_GRAVADO) {
              var precioUnitario = (subTotal / (cantidad - descuentoUnitario)).toFixed(CANTIDAD_DECIMALES_VENTA.CANTIDAD);
              var valorUnitario = ((precioUnitario - descuentoUnitario) / (1 + tasaIGV)).toFixed(NUMERO_DECIMALES_VALOR_UNITARIO_VENTA);
              var valorVentaItem = (parseFloatAvanzado(subTotal) / (1 + tasaIGV)).toFixed(NUMERO_DECIMALES_VENTA);
              var igvItem = (parseFloatAvanzado(subTotal) - parseFloatAvanzado(valorVentaItem)).toFixed(NUMERO_DECIMALES_VENTA);
              var preciounitarioneto = (parseFloatAvanzado(subTotal) / cantidad);
              var valorunitarioneto = (parseFloatAvanzado(valorUnitario) - descuentoValorUnitario);
              var valorventanetoitem = (parseFloatAvanzado(valorunitarioneto) * cantidad);

            } else {
              var precioUnitario = (subTotal / (cantidad - descuentoUnitario)).toFixed(CANTIDAD_DECIMALES_VENTA.CANTIDAD);
              var valorUnitario = (precioUnitario - descuentoUnitario).toFixed(NUMERO_DECIMALES_VALOR_UNITARIO_VENTA);
              var valorVentaItem = (cantidad * parseFloatAvanzado(valorUnitario)).toFixed(NUMERO_DECIMALES_VENTA);
              var igvItem = 0.00;
              var preciounitarioneto = (parseFloatAvanzado(subTotal) / cantidad);
              var valorunitarioneto = (parseFloatAvanzado(valorUnitario) - descuentoValorUnitario);
              var valorventanetoitem = (parseFloatAvanzado(valorunitarioneto) * cantidad);


            }

            subTotal = subTotal == 'Infinity' ? 0 : subTotal;

            base.PrecioUnitario(precioUnitario);
            base.ValorUnitario(parseFloatAvanzado(valorUnitario).toFixed(NUMERO_DECIMALES_VENTA));
            base.ValorVentaItem(parseFloatAvanzado(valorVentaItem).toFixed(NUMERO_DECIMALES_VENTA));
            base.IGVItem(parseFloatAvanzado(igvItem).toFixed(NUMERO_DECIMALES_VENTA));
            base.DescuentoItem((descuentoUnitario * cantidad).toFixed(NUMERO_DECIMALES_VENTA));
            base.DescuentoValorUnitario(descuentoValorUnitario.toFixed(NUMERO_DECIMALES_VENTA));
            base.PrecioUnitarioNeto(preciounitarioneto.toFixed(NUMERO_DECIMALES_VENTA));
            base.ValorUnitarioNeto(valorunitarioneto.toFixed(NUMERO_DECIMALES_VENTA));
            base.ValorVentaNetoItem(valorventanetoitem.toFixed(NUMERO_DECIMALES_VENTA));
          }
          return resultado;
        } else {
          base.SubTotal(0);
          return resultado;
        }
      }
    }
  }
  
  self.CalcularDescuentoItem = function () {
    if (base) {
      var resultado = "";
      if (base.DescuentoUnitario() !== "") {
        resultado = parseFloatAvanzado(base.DescuentoUnitario()) * parseFloatAvanzado(base.Cantidad());
        return resultado.toFixed(2);
      }
      else {
        return resultado;

      }
    }
  }

  self.CalcularPrecioUnitarioPorPrecioSolesDolares = function () {
    if (base) {
      var cambio = parseFloatAvanzado(self.cabecera.ValorTipoCambio());
      if (cambio > 0 ) {
        var preciosolesdolares = parseFloatAvanzado(self.PrecioUnitarioSolesDolares());
        var preciounitario = (preciosolesdolares / cambio).toFixed(self.DecimalDescuentoUnitario());
          
        self.PrecioUnitario(preciounitario);
      }
    }  
  }

  self.CalcularPrecioSolesDolaresPorPrecioUnitario = function () {
    if (base) {
      var cambio = parseFloatAvanzado(self.cabecera.ValorTipoCambio());
      if (cambio > 0 ) {
        var preciounitario = parseFloatAvanzado(self.PrecioUnitario());
        var preciosolesdolares = (preciounitario * cambio).toFixed(self.DecimalDescuentoUnitario());
        
        self.PrecioUnitarioSolesDolares(preciosolesdolares);
      }
    }  
  }

  self.Reemplazar = function (data) {
    if (data) {
      var codigo = "";
      if (data.CodigoMercaderia) codigo = data.CodigoMercaderia;
      else if (data.CodigoServicio) codigo = data.CodigoServicio;
      else if (data.CodigoActivoFijo) codigo = data.CodigoActivoFijo;
      else if (data.CodigoOtraVenta) codigo = data.CodigoOtraVenta;

      self.Cantidad((data.CodigoServicio) ? "1.00" : self.Cantidad());
      data.NombreMarca = data.NombreMarca == "NO ESPECIFICADO" ? "-" : data.NombreMarca;
      data.PrecioUnitario = data.PrecioUnitario === "" || data.PrecioUnitario === null ? "0.00" : data.PrecioUnitario;
      var nuevodetalle = self.NuevoDetalleComprobanteVenta;
      var includesList = Object.keys(ko.mapping.toJS(nuevodetalle, { ignore: ["Cantidad", "ListaPrecios"] }));
      var nuevadata = leaveJustIncludedProperties(data, includesList);
      var copia = Knockout.CopiarObjeto(nuevodetalle);
      var $cantidad = self.Cantidad() === "" ? "0.00" : self.Cantidad();
      var $preciounitario = self.PrecioUnitario() === "" ? "0.00" : self.PrecioUnitario();
      var $valorunitario = self.ValorUnitario() === "" ? "0.00" : self.ValorUnitario();
      var $descuentounitario = self.DescuentoUnitario() === "" ? "0.00" : self.DescuentoUnitario();
      var $descuentoValorUnitario = self.DescuentoValorUnitario() === "" ? "0.00" : self.DescuentoValorUnitario();
      var $listaprecio = [];
      var precioEspecialCliente = "";
      if (data.ListaPrecios) {

        var IndicadorVistaPrecioMinimo = self.NuevoDetalleComprobanteVenta.IndicadorVistaPrecioMinimo();
        if (IndicadorVistaPrecioMinimo == 0) {
          data.ListaPrecios.forEach(function (entry, key) {
            if (entry.IndicadorPrecioMinimo == 0) {
              if (entry.IdSede == self.cabecera.IdSede()) {

                if (entry.IdTipoListaPrecio == base.cabecera.IdTipoListaPrecioEspecial()) {
                  precioEspecialCliente = entry.Precio;
                }
                $listaprecio.push(entry);
              }
            }
          });
        }
        else {
          data.ListaPrecios.forEach(function (entry, key) {
            if (entry.IdSede == self.cabecera.IdSede()) {
              if (entry.IdTipoListaPrecio == base.cabecera.IdTipoListaPrecioEspecial()) {
                precioEspecialCliente = entry.Precio;
              }
              $listaprecio.push(entry);
            }
          });
        }
      }

      var $listaraleo = [];
      if (data.ListaRaleos) {
        $listaraleo = data.ListaRaleos;
      }

      var adicionaldata = {
        'Cantidad': $cantidad,
        'PrecioUnitario': $preciounitario,
        'ValorUnitario': $valorunitario,
        'DescuentoUnitario': $descuentounitario,
        'DescuentoValorUnitario': $descuentoValorUnitario,
        'ListaPrecios': $listaprecio,
        'ListaRaleos': $listaraleo,
        'IdDetalleComprobanteVenta': self.IdDetalleComprobanteVenta(),
        'CodigoMercaderia': codigo,
        'ProductoBonificado': self.ProductoBonificado(),
      };
      ko.mapping.fromJS(adicionaldata, {}, copia);
      ko.mapping.fromJS(nuevadata, MappingVenta.DetalleComprobanteVenta, copia);
      var copia_data = ko.mapping.toJS(copia);
      var resultado = new VistaModeloDetalleComprobanteVenta(copia_data);
      
  
      if (base.cabecera.ParametroAplicaPrecioEspecial() == 1 && precioEspecialCliente != "" ) {
        resultado.PrecioUnitarioEspecialCliente(precioEspecialCliente);
        resultado.PrecioUnitario(precioEspecialCliente);
      }

      base.__ko_mapping__ = undefined;
      var output = ko.mapping.toJS(resultado, mappingIgnore);
      ko.mapping.fromJS(output, {}, base);      

      return resultado;
    }
  }
  self.GuardarProducto = function (data, event) {
    if (event) {
      var producto = ko.mapping.toJS(ViewModels.data.Mercaderia);
      producto.NombreProducto = self.NombreProducto();
      producto.CodigoMercaderia = self.CodigoMercaderia();
      producto.CodigoAutomatico = producto.CodigoMercaderia == "" || producto.CodigoMercaderia == null ? 0 : 1;
      var datajs = { "Data": producto };

      $("#loader").show()
      self.InsertarProducto(datajs, event, function ($data, $event) {
        $("#loader").hide()
        if (!$data.error) {
          self.ValidarAutocompletadoProducto($data.resultado, event)
        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function functionName() {
          })
        }
      })
    }
  }

  self.GuardarProductoServicio = function (data, event) {
    if (event) {
      var producto = ko.mapping.toJS(ViewModels.data.Servicio);
      producto.NombreProducto = self.NombreProducto();
      producto.CodigoServicio = self.CodigoServicio();
      producto.CodigoAutomatico = producto.CodigoServicio == "" || producto.CodigoServicio == null ? 0 : 1;
      var datajs = { "Data": producto };

      $("#loader").show()
      self.InsertarServicio(datajs, event, function ($data, $event) {
        $("#loader").hide()
        if (!$data.error) {
          self.ValidarAutocompletadoProducto($data.resultado, event);
        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function functionName() {
          })
        }
      })
    }
  }


  self.InsertarProducto = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cMercaderia/InsertarMercaderia',
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

  self.InsertarServicio = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cServicio/InsertarServicio',
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
