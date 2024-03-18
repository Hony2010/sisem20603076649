VistaModeloRegistroSaldoInicialCuentaCobranza = function (data) {
    var self = this;
    ko.mapping.fromJS(data, MappingCuentaCobranza, self);
    ModeloRegistroSaldoInicialCuentaCobranza.call(this,self);
    self.UltimoItemSeleccionado = [];
    self.TituloAlerta = ko.observable("SALDO INICIAL CUENTA COBRANZA");
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
          var detallesValidos = ko.utils.arrayFilter(self.data.DetallesSaldoInicialCuentaCobranza(), function (item) {
            return item.IdProducto() != null && item.IdProducto() != '';
          });

          var saldoSeleccionado = ko.utils.arrayFilter(self.data.SaldosInicialesCuentaCobranza(), function (item) {
            return item.IdSaldoInicialCuentaCobranza() == self.IdSaldoInicialSeleccionado();
          });

          saldoSeleccionado[0].DetallesSaldoInicialCuentaCobranza([]);
          saldoSeleccionado[0].DetallesSaldoInicialCuentaCobranza(detallesValidos);
          self.data.DetallesSaldoInicialCuentaCobranza([]);
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
        var objeto = ko.mapping.toJS(self.data.NuevoSaldoInicialCuentaCobranza)
        var nuevoSaldoInicial = new VistaModeloSaldoInicialCuentaCobranza(objeto, self);
        var idMaximo = Math.max.apply(null,ko.utils.arrayMap(this.SaldosInicialesCuentaCobranza(),function(e){return e.IdSaldoInicialCuentaCobranza(); }));

        nuevoSaldoInicial.IdSaldoInicialCuentaCobranza(idMaximo == '-Infinity' ? 1 : idMaximo + 1);
        nuevoSaldoInicial.OpcionProceso(opcionProceso.Nuevo);

        self.data.SaldosInicialesCuentaCobranza.push(nuevoSaldoInicial);
        nuevoSaldoInicial.Inicializar()

        self.OnClickFilaSaldoInicial(nuevoSaldoInicial, event);
        self.OnFilaNueva(true);
      }
    }

    self.OnClickFilaSaldoInicial = function (data, event) {
      if(event) {
        if (self.OnFilaNueva()) { return false; }

        var ultimoItem = self.UltimoItemSeleccionado;
        if (ultimoItem.IdSaldoInicialCuentaCobranza) {
          if (ultimoItem.IdSaldoInicialCuentaCobranza() != data.IdSaldoInicialCuentaCobranza()) {
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
          self.data.SaldosInicialesCuentaCobranza.remove(data);
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
        var nuevo = ko.utils.arrayFilter(data.data.SaldosInicialesCuentaCobranza(), function (item) {
          return item.OpcionProceso == opcionProceso.Nuevo;
        });
        return nuevo;
      }
    }

    self.Seleccionar = function (data, event) {
      if (event) {
        if (data != undefined) {
          var id = "#"+ data.IdSaldoInicialCuentaCobranza();
          $(id).addClass('active').siblings().removeClass('active');
        }
      }
    }

    self.SeleccionarAnterior = function (data, event)  {
      if (event) {
        var id = "#" + data.IdSaldoInicialCuentaCobranza();
        var seleccionar = $(id).prev() ? $(id).prev() : $(id).next();
        seleccionar.addClass('active').siblings().removeClass('active');
      }
    }

    self.SeleccionarSiguiente = function (data, event)  {
      if (event) {
        var id = "#" + data.IdSaldoInicialCuentaCobranza();
        var seleccionar = $(id).next() ? $(id).next() : $(id).prev();
        seleccionar.addClass('active').siblings().removeClass('active');
      }
    }

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        self.ListarSaldosInicialesCuentaCobranzaPorPagina(data,event,self.PostConsultarPorPagina);
      }
    }

    self.PostConsultarPorPagina =  function(data,event) {
      if(event) {
        var objeto = self.data.SaldosInicialesCuentaCobranza()[0];
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
          self.ListarSaldosInicialesCuentaCobranza(data,event,self.PostConsultar);
        }
      }
    }

    self.PostConsultar = function (data,event) {
      if(event) {
        var objeto = self.data.SaldosInicialesCuentaCobranza()[0];
        self.Seleccionar(objeto,event);
        $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
        self.data.Filtros.totalfilas(data.Filtros.totalfilas);
      }
    }


}
