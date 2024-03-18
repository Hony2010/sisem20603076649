ResumenesDiarioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
    self.EstadoSelector = ko.observable(false);

    self.FechaFormateada = ko.pureComputed(function(){
      return FechaFormato.FormatearFechaYYYYMMDD(self.FechaEmision());
    }, this);

    self.Icono = ko.pureComputed(function(){
      if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
        return "fa-minus";
      }
      else{
        return "fa-times";
      }
    }, this);

    self.Color = ko.pureComputed(function(){
      if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
        return "orange";
      }
      else{
        return "red";
      }
    }, this);

    self.VistaCheck = ko.pureComputed(function(){
      return self.IndicadorEstadoResumenDiario() == ESTADO_CPE.GENERADO ? "hidden" : "visible";
    }, this);


    self.EstadoCE = ko.computed(function(){
      if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
        return "Pend. Envío";
      }
      else {
        return "Pend.";
      }
    }, this);

    self.OpcionButton = function(data, event)
    {
      if(event)
      {
        if(data.IndicadorEstadoCPE() != ESTADO_CPE.ACEPTADO)
        {
          $("#GenerarResumen").prop('disabled', false);
        }
        else {
          $("#GenerarResumen").prop('disabled', true);
        }
      }
    }

    self.CambiarOpciones = function(data, event)
    {
      if(event)
      {
        if(filas_seleccionadas <= 0){
          $("#GenerarResumen").prop('disabled', true);
        }
        else{
          self.OpcionButton(data, event);
        }

        if(filas_seleccionadas == cantidad_filas)
        {
          Models.SelectorTodo(true);
        }
        else {
          Models.SelectorTodo(false);
        }
      }
    }

    self.CambiarEstadoCheck = function (data, event) {
      if(event){
        var id = "#"+ data.IdComprobanteVenta();
        var objeto = Knockout.CopiarObjeto(data);

        if (data.EstadoSelector() == true)
        {
          $(id).addClass('active');
          Models.data.ResumenDiario.push(new ResumenesDiarioModel(objeto));
          filas_seleccionadas++;
        }
        else
        {
          $(id).removeClass('active');
          Models.data.ResumenDiario.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } )
          filas_seleccionadas--;
        }

        self.CambiarOpciones(data, event);

      }

    }

    self.HabilitarFilaInputTipoCambio = function (data, event, option)  {
      if(event)
      {
        var id = data;

        if(option == false)
        {
          $(id).find('.class_InputTipoCambio').hide();
          $(id).find('.class_SpanTipoCambio').show();
        }
        else
        {
          $(id).find('.class_InputTipoCambio').show();
          $(id).find('.class_SpanTipoCambio').hide();
          $(id).find('.class_InputTipoCambio').focus();
        }
      }
    }

    self.OnBlurMotivoBaja = function(data, event)
    {
      if(event)
      {
        if (data.EstadoSelector() ==true) {
          var objeto1 = Knockout.CopiarObjeto(Models.data.ResumenDiario);
          if(objeto1().length > 0){
            ko.utils.arrayFirst(Models.data.ResumenDiario(), function(item) {
                if(item.IdComprobanteVenta() == data.IdComprobanteVenta()){
                  item.MotivoBaja(data.MotivoBaja());
                }
            });
          }
        }

        $('.class_InputTipoCambio').hide();
        $('.class_SpanTipoCambio').show();
      }
    }

    self.OnClickMotivoBaja = function(data, event)
    {
      if(event)
      {
        $('.class_InputTipoCambio').hide();
        $('.class_SpanTipoCambio').show();
        var id = "#" + data.IdComprobanteVenta() + "_td_MotivoBaja";
        self.HabilitarFilaInputTipoCambio(id, event, true);
      }
    }


}

ResumenesDiarioConsultaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    //self.EstadoFact = ko.observable(false);
    self.FechaFormateada = ko.pureComputed(function(){
      return FechaFormato.FormatearFechaYYYYMMDD(self.FechaEmisionDocumento());
    }, this);

    self.Icono = ko.pureComputed(function(){

      if(self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO){
        return "fa-check";
      }
      else if(self.IndicadorEstadoResumenDiario() == ESTADO_CPE.RECHAZADO){
        return "fa-times";
      }
      else{
        return "fa-minus";
      }
    }, this);

    self.Color = ko.pureComputed(function(){

      if(self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO){
        return "green";
      }
      else if(self.IndicadorEstadoResumenDiario() == ESTADO_CPE.RECHAZADO){
        return "red";
      }
      else{
        return "orange";
      }
    }, this);

    self.VistaCheck = ko.pureComputed(function(){
      return self.IndicadorEstado() == ESTADO.ACTIVO ? "hidden" : "visible";
    }, this);

    self.VistaCDR = ko.pureComputed(function(){
      if(self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO || self.IndicadorEstadoResumenDiario() == ESTADO_CPE.RECHAZADO)
      {
        return "visible";
      }
      else {
        return "hidden";
      }
    }, this);

    self.EstadoCE = ko.computed(function(){
      if(self.IndicadorEstadoResumenDiario() == ESTADO_CPE.ACEPTADO){
        return "Aceptado";
      }
      else if(self.IndicadorEstadoResumenDiario() == ESTADO_CPE.RECHAZADO){
        return "Rechazado";
      }
      // else if(self.IndicadorEstadoResumenDiario() == ESTADO_CPE.RECHAZADO){//ESTADO P
      //   return "En Proceso";
      // }
      else {
        return "Procesando";
      }
    }, this);

    self.VerFacturas = function(data, event)
    {
      if(event)
      {
        Models.data.DetalleResumenesDiario([]);

        ko.utils.arrayFirst(data.DetallesResumenDiario(), function(item) {
            Models.data.DetalleResumenesDiario.push(item);
          });

        $("#modalPreview").modal("show");
      }
    }

    self.Seleccionar = function(data, event)
    {
      if(event)
      {
        var id = "#"+data.IdResumenDiario();
        $(id).addClass('active').siblings().removeClass('active');
      }
    }


}


BuscadorModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.NumeroDocumento = ko.observable("");
    self.RazonSocial = ko.observable("");

    self.BusquedaTexto = function(data, event){
      if(data.NumeroDocumento() == ""){
        Models.data.Buscador.NumeroDocumento("%");
      }
    }

    self.LimpiarOpciones = function(data, event){
        if(event)
       {
         cantidad_filas = Models.data.ResumenesDiario().length;
         // filas_pendiente = 0;
         filas_seleccionadas = 0;
         $("#GenerarResumen").prop('disabled', true);
         Models.data.ResumenDiario([]);
         Models.SelectorTodo(false);
       }

    }

    self.BuscarFactura =function(data,event){
      if(event)
      {
        $("#loader").show();
        var accion = "";
        var objetoFiltro = ko.mapping.toJS(data, mappingIgnore)
        objetoFiltro.NumeroDocumento = data.NumeroDocumento() == "" ? "%" : data.NumeroDocumento();
        objetoFiltro.RazonSocial = data.RazonSocial() == "" ? "%" : self.RazonSocial();

        var datajs = { Data: objetoFiltro };
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/FacturacionElectronica/cResumenDiario/ConsultarComprobantesVenta',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                //Models.data.ResumenesDiario.removeAll();

                Models.data.ResumenesDiario([]);
                ko.utils.arrayForEach(data, function(item) {
                    Models.data.ResumenesDiario.push(new ResumenesDiarioModel(item));
                  });

                self.LimpiarOpciones(data, event);

              }
              else {
                Models.data.ResumenesDiario([]);
              }
              $("#loader").hide();

          },
          error : function (jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            $("#loader").hide();
          }
        });
      }

    }

}

BuscadorConsultaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.NumeroDocumento = ko.observable("");

    self.BusquedaTexto = function(data, event){
      if(data.NumeroDocumento() == ""){
        Models.data.BuscadorConsulta.NumeroDocumento("%");
      }
    }

    self.LimpiarOpciones = function(data, event){
        if(event)
       {
         filas_generada = 0;
         $("#EnviarSunat").prop('disabled', true);
         //Models.data.ResumenDiarioConsulta([]);
       }

    }

    self.BuscarFactura =function(data,event){
      if(event)
      {
        $("#loader").show();
        var accion = "";

        var objetoFiltro = ko.mapping.toJS(data, mappingIgnore)
        objetoFiltro.NumeroDocumento = data.NumeroDocumento() == "" ? "%" : data.NumeroDocumento();
        objetoFiltro.RazonSocial = data.RazonSocial() == "" ? "%" : data.RazonSocial();
        objetoFiltro.CodigoEstado = self.CodigoEstado() == undefined || self.CodigoEstado() == ""? "%" : self.CodigoEstado();

        var datajs = { Data: objetoFiltro };

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/FacturacionElectronica/cResumenDiario/ConsultarResumenesDiario',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                //Models.data.ResumenesDiarioConsulta.removeAll();
                Models.data.ResumenesDiarioConsulta([]);
                ko.utils.arrayForEach(data, function(item) {
                    Models.data.ResumenesDiarioConsulta.push(new ResumenesDiarioConsultaModel(item));
                  });

                filas_seleccionadas = 0;
                $("#GenerarResumen").prop('disabled', true);
                //self.LimpiarOpciones(data, event);

              }
              else {
                Models.data.ResumenesDiarioConsulta([]);
              }

              $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            $("#loader").hide();
          }
        });

        clearInterval(tiempo);
        tiempo = setInterval(self.MiTiempo, 25000);
      }

    }

    self.MiTiempo = function()
    {
      var pendiente = false;
      var objeto = ko.mapping.toJS(Models.data.ResumenesDiarioConsulta);
      objeto.forEach(function(entry, value){
        if(entry.IndicadorEstadoResumenDiario == ESTADO_CPE.EN_PROCESO)
        {
          pendiente = true;
        }
      });

      if(pendiente)
      {
        var datajs = {Data: JSON.stringify(objeto)};
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/FacturacionElectronica/cResumenDiario/ConsultarEstadoResumenDiario',
          success: function (data) {
            ko.utils.arrayForEach(Models.data.ResumenesDiarioConsulta(), function (observable) {
              data.forEach(function(entry, key){
                if(observable.IdResumenDiario() == entry.IdResumenDiario)
                {
                  // observable.IndicadorEstadoResumenDiario(entry.IndicadorEstadoResumenDiario);
                  // observable.IndicadorEstadoResumenDiario.valueHasMutated();
                  Models.data.ResumenesDiarioConsulta.replace(observable, new ResumenesDiarioConsultaModel(entry));
                }
              });
        		});
          },
          error : function (jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            // $("#loader").hide();
          }
        });
      }
    }

}

DetalleResumenesDiarioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.IdDetalleResumenDiario = ko.observable("");
    self.Numero = ko.observable("");
    self.RazonSocial = ko.observable("");
}

var MappingResumenDiario = {
    'ResumenesDiario': {
        create: function (options) {
            if (options)
              return new ResumenesDiarioModel(options.data);
            }
    },
    'ResumenDiario': {
        create: function (options) {
            if (options)
              return new ResumenesDiarioModel(options.data);
            }
    },
    'ResumenesDiarioConsulta': {
        create: function (options) {
            if (options)
              return new ResumenesDiarioConsultaModel(options.data);
            }
    },
    'ResumenDiarioConsulta': {
        create: function (options) {
            if (options)
              return new ResumenesDiarioConsultaModel(options.data);
            }
    },
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel(options.data);
            }
    },
    'BuscadorConsulta': {
        create: function (options) {
            if (options)
              return new BuscadorConsultaModel(options.data);
            }
    },
    'DetalleResumenesDiario': {
        create: function (options) {
            if (options)
              return new DetalleResumenesDiarioModel(options.data);
            }
    }
}
