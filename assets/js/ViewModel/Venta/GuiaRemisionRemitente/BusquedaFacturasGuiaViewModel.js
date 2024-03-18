BusquedaFacturasGuiaViewModel = function (data) {
  var self = this;
  
  ko.mapping.fromJS(data, {}, self);

  BusquedaFacturasGuiaModel.call(this, self);

  self.Inicializar = function (data, event, callback) {
    self.callback=callback;
  }

  self.OnClickBtnAgregar = function (data, event) {
    if (event) {
      $(event.target).select();

      
    }
  }

  self.OnClickBtnBuscar =  function (data, event) {
    if (event) {
      
      var $d = {   
        FechaInicio : moment(data.FechaInicio(),"DD/MM/YYYY").format("YYYY-MM-DD"),
        FechaFin : moment(data.FechaFin(),"DD/MM/YYYY").format("YYYY-MM-DD"),
        Vendedores :"'"+data.VendedoresSeleccionados().join("','")+"'"
      }

      self.ConsultarComprobantesGuia($d,event,function($data,$event){
        self.ComprobantesVentaGuia([]);
        ko.mapping.fromJS($data,{},self.ComprobantesVentaGuia);
        $("#TodosComprobantes").prop("checked",false);
        $("#TodosComprobantes").click();
        //console.log($data);
      });
    }

  }
  
  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }
  

  self.BuscarProformaPorPagina = function (data, event) {
    if (event) {
      // var datajs = { Data: self.ObtenerFiltro(data, event) };
      // $("#loader").show();
      // self.ConsultarVentasProformasPorPagina(datajs, event, self.PostBuscarProformaPorPagina)
    }
  }

  
  self.ValidarFechaInicio = function(data, event){
    if(event) {
      $(event.target).validate(function(valid, elem) {
         //console.log('Element '+elem.name+' is '+( valid ? 'valid' : 'invalid'));
      });
    }
  }

  self.ValidarFechaFin = function(data, event){
    if(event) {
      $(event.target).validate(function(valid, elem) {
         //console.log('Element '+elem.name+' is '+( valid ? 'valid' : 'invalid'));
      });
    }
  }

  self.SeleccionarTodosVendedores = function (data, event) {
    if (event) {
      console.log("SeleccionarTodosVendedores");
      var estadoglobal = $(event.target).prop('checked');
      var vendedoresSeleccionados = [];
      
      ko.utils.arrayForEach(data.Vendedores(),function (item) {
        item.EstadoSeleccion(estadoglobal);
        vendedoresSeleccionados.push(item.AliasUsuarioVenta());
      });

      data.NumeroVendedoresSeleccionados(estadoglobal ? data.Vendedores().length : 0);
      data.VendedoresSeleccionados(estadoglobal ? vendedoresSeleccionados : []);
    }
  }

  self.SeleccionarVendedor = function (data, event,parent) {
    if (event) {
      var estado = $(event.target).prop('checked');
      var vendedoresSeleccionados = parent.VendedoresSeleccionados();
      parent.NumeroVendedoresSeleccionados(estado ? parent.NumeroVendedoresSeleccionados() + 1 : parent.NumeroVendedoresSeleccionados() - 1);
      $('#SelectorVendedores').prop('checked', parent.NumeroVendedoresSeleccionados() == parent.TotalVendedores() ? true : false);
      data.EstadoSeleccion(estado);

      if (estado) {
        vendedoresSeleccionados.push(data.AliasUsuarioVenta());
      } else {
        vendedoresSeleccionados = vendedoresSeleccionados.filter(function(value) {
          return value != data.AliasUsuarioVenta();
        });
      }

      parent.VendedoresSeleccionados(vendedoresSeleccionados);
    }
  }

  self.OnChangeEstadoSelector = function (data, event) {
    if (event) {
      var estado = $(event.target).prop('checked');
      var comprobantesSeleccionados = self.FiltrosGuia.ComprobantesSeleccionados();
      self.FiltrosGuia.NumeroVendedoresSeleccionados(estado ? self.FiltrosGuia.NumeroVendedoresSeleccionados() + 1 : self.FiltrosGuia.NumeroComprobantesSeleccionados() - 1);
      $('#TodosComprobantes').prop('checked', self.FiltrosGuia.NumeroVendedoresSeleccionados() == self.FiltrosGuia.TotalComprobantes() ? true : false);
      data.EstadoSelector(estado);

      if (estado) {
        comprobantesSeleccionados.push(data);
      } else {
        comprobantesSeleccionados = comprobantesSeleccionados.filter(function(value) {
          return value.Documento != data.Documento();
        });
      }

      self.ComprobantesSeleccionados(comprobantesSeleccionados);
    }
  }

  self.OnChangeTodosComprobantes = function (data, event) {
    if (event) {
      console.log("OnChangeTodosComprobantes");
      var estadoglobal = $(event.target).prop('checked');
      var comprobantesSeleccionados = [];
      
      ko.utils.arrayForEach(data.ComprobantesVentaGuia(),function (item) {
        item.EstadoSelector(estadoglobal);
      });

      data.FiltrosGuia.NumeroComprobantesSeleccionados(estadoglobal ? data.ComprobantesVentaGuia().length : 0);
      data.FiltrosGuia.ComprobantesSeleccionados(estadoglobal ? data.ComprobantesVentaGuia() : []);
    }
  }

  self.OnClickBtnCargarProductos = function(data,event) {
    if(event) {
      //debugger;
      self.callback(data.FiltrosGuia.ComprobantesSeleccionados(),event);
    }
  }

}
