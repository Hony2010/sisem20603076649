VistaModeloRegistroSaldoInicialCuentaPago = function (data) {
    var self = this;
    ko.mapping.fromJS(data, MappingCuentaPago, self);
    ModeloRegistroSaldoInicialCuentaPago.call(this,self);
    self.UltimoItemSeleccionado = [];
    self.TituloAlerta = ko.observable("SALDO INICIAL CUENTA PAGO");
    self.OnFilaNueva = ko.observable(false);
    self.showDetalleComprobante = ko.observable(false);
    self.IdSaldoInicialSeleccionado = ko.observable('');

    self.Inicializar = function ()  {
      $(".fecha").inputmask({"mask": "99/99/9999"});
    }

    self.OnHideModalDetalle = function (data, event) {
      if (event) {
        self.showDetalleComprobante(false);


        if (self.IdSaldoInicialSeleccionado() != '') {
          var detallesValidos = ko.utils.arrayFilter(self.data.DetallesSaldoInicialCuentaPago(), function (item) {
            return item.IdProducto() != null && item.IdProducto() != '';
          });

          var saldoSeleccionado = ko.utils.arrayFilter(self.data.SaldosInicialesCuentaPago(), function (item) {
            return item.IdSaldoInicialCuentaPago() == self.IdSaldoInicialSeleccionado();
          });

          saldoSeleccionado[0].DetallesSaldoInicialCuentaPago([]);
          saldoSeleccionado[0].DetallesSaldoInicialCuentaPago(detallesValidos);
          self.data.DetallesSaldoInicialCuentaPago([]);
          self.IdSaldoInicialSeleccionado('');
        }
      }
    }

    self.OnFocus = function(data,event,callback) {
      if(event)  {
          $(event.target).select();
      }
    }

    self.OnKeyEnter = function(data,event) {
      if(event) {
        var resultado = $(event.target).enterToTab(event);
        return resultado;
      }
    }

    self.OnClickBtnNuevo = function (data, event) {
      if(event) {
        var objeto = ko.mapping.toJS(self.data.NuevoSaldoInicialCuentaPago)
        var nuevoSaldoInicial = new VistaModeloSaldoInicialCuentaPago(objeto, self);
        var idMaximo = Math.max.apply(null,ko.utils.arrayMap(this.SaldosInicialesCuentaPago(),function(e){return e.IdSaldoInicialCuentaPago(); }));

        nuevoSaldoInicial.IdSaldoInicialCuentaPago(idMaximo == '-Infinity' ? 1 : idMaximo + 1);
        nuevoSaldoInicial.OpcionProceso(opcionProceso.Nuevo);

        self.data.SaldosInicialesCuentaPago.push(nuevoSaldoInicial);
        nuevoSaldoInicial.Inicializar()

        self.OnClickFilaSaldoInicial(nuevoSaldoInicial, event);
        self.OnFilaNueva(true);
      }
    }

    self.OnClickFilaSaldoInicial = function (data, event) {
      if(event) {
        if (self.OnFilaNueva()) { return false; }

        var ultimoItem = self.UltimoItemSeleccionado;
        if (ultimoItem.IdSaldoInicialCuentaPago) {
          if (ultimoItem.IdSaldoInicialCuentaPago() != data.IdSaldoInicialCuentaPago()) {
            ultimoItem.OcultarInput(data, event);
            ko.mapping.fromJS(ultimoItem.CopiaData, {}, ultimoItem);
          } else {
            return false;
          }
        }

        data.CopiaData = ko.mapping.toJS(data);
        data.MostrarInput(data, event);
        self.UltimoItemSeleccionado = data;
        self.Seleccionar(data, event);
        $(event.target).closest('td').find('input').eq(0).focus();
      }
    }

    self.RemoverFilaNuevaSaldoInicial = function (data, event) {
      if(event) {
        alertify.confirm(self.TituloAlerta(), "¿Desea perder el nuevo registro?", function () {
          self.SeleccionarAnterior(data, event);
          self.data.SaldosInicialesCuentaPago.remove(data);
          self.OnFilaNueva(false);
        },function () {});
      }
    }

    self.OnEscFilaSaldoInicial = function (data, event) {
      if (event) {
        var tecla = event.keyCode ? event.keyCode : event.which;
        if (tecla != TECLA_ESC) { return false; }
        if (data.OpcionProceso() == opcionProceso.Nuevo) {
          self.RemoverFilaNuevaSaldoInicial(data, event);
        } else {
          data.OcultarInput(data, event);
        }
      }
    }

    self.ObtenerItemNuevoSaldoInicial = function (data, event) {
      if (event) {
        var nuevo = ko.utils.arrayFilter(data.data.SaldosInicialesCuentaPago(), function (item) {
          return item.OpcionProceso == opcionProceso.Nuevo;
        });
        return nuevo;
      }
    }

    self.Seleccionar = function (data, event) {
      if (event) {
        if (data != undefined) {
          var id = "#"+ data.IdSaldoInicialCuentaPago();
          $(id).addClass('active').siblings().removeClass('active');
        }
      }
    }

    self.SeleccionarAnterior = function (data, event)  {
      if (event) {
        var id = "#" + data.IdSaldoInicialCuentaPago();
        var seleccionar = $(id).prev() ? $(id).prev() : $(id).next();
        seleccionar.addClass('active').siblings().removeClass('active');
      }
    }

    self.SeleccionarSiguiente = function (data, event)  {
      if (event) {
        var id = "#" + data.IdSaldoInicialCuentaPago();
        var seleccionar = $(id).next() ? $(id).next() : $(id).prev();
        seleccionar.addClass('active').siblings().removeClass('active');
      }
    }

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        self.ListarSaldosInicialesCuentaPagoPorPagina(data,event,self.PostConsultarPorPagina);
      }
    }

    self.PostConsultarPorPagina =  function(data,event) {
      if(event) {
        var objeto = self.data.SaldosInicialesCuentaPago()[0];
        self.Seleccionar(objeto, event);
        $("#Paginador").pagination("drawPage", data.pagina);
      }
    }

    self.Consultar = function (data,event) {
      if(event) {
        var tecla = event.keyCode ? event.keyCode : event.which;
        if(tecla == TECLA_ENTER)
        {
          var inputs = $(event.target).closest('form').find(':input:visible');
          inputs.eq(inputs.index(event.target)+ 1).focus();
          self.ListarSaldosInicialesCuentaPago(data,event,self.PostConsultar);
        }
      }
    }

    self.PostConsultar = function (data,event) {
      if(event) {
        var objeto = self.data.SaldosInicialesCuentaPago()[0];
        self.Seleccionar(objeto,event);
        $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
        self.data.Filtros.totalfilas(data.Filtros.totalfilas);
      }
    }


}
