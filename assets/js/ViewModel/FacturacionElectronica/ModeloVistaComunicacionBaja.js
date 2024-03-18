ComunicacionesBajaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.FechaFormateada = ko.pureComputed(function(){
      return FechaFormato.FormatearFechaYYYYMMDD(self.FechaEmision());
    }, this);
    //self.EstadoFact = ko.observable(false);
    self.EstadoSelector = ko.observable(false);

    self.Icono = ko.pureComputed(function(){

      if(self.IndicadorEstado() == ESTADO.ANULADO && self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
        return "fa-minus";
      }
      else{
        return "fa-times";
      }
    }, this);

    self.Color = ko.pureComputed(function(){

      if(self.IndicadorEstado() == ESTADO.ANULADO && self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
        return "orange";
      }
      else{
        return "red";
      }
    }, this);

    self.VistaCheck = ko.pureComputed(function(){
      return self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO ? "hidden" : "visible";
    }, this);


    self.EstadoCE = ko.computed(function(){
      if(self.IndicadorEstado() == ESTADO.ANULADO && self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
        return "Pend. Baja";
      }
      else {
        return "Baja generada(Pend. de envío)";
      }
    }, this);

    self.OpcionButton = function(data, event)
    {
      if(event)
      {
        if(self.IndicadorEstado() == ESTADO.ANULADO && self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
          $("#GenerarBaja").prop('disabled', false);
        }
        else {
          $("#GenerarBaja").prop('disabled', true);
        }
      }
    }

    self.CambiarOpciones = function(data, event)
    {
      if(event)
      {
        //PARA HABILITAR Y DESHABILITAR EL BUTTON
        if(filas_pendiente <= 0){
          //$("#EnviarSunat").prop('disabled', true)
          $("#GenerarBaja").prop('disabled', true);
        }
        else{
          self.OpcionButton(data, event);
        }
      }
    }

    self.CambiarEstadoCheck = function (data, event) {
      if(event){
        var id = "#"+ data.IdComprobanteVenta();

        //$(id).addClass('active');
        //$(id).addClass('active').siblings().removeClass('active');
        var objeto = Knockout.CopiarObjeto(data);

        if (data.EstadoSelector() == true)
        {
          $(id).addClass('active');
          //Models.data.ComunicacionesBaja.EstadoSelector(false);
          Models.data.ComunicacionBaja.push(new ComunicacionesBajaModel(objeto));
          filas_pendiente++;
        }
        else
        {
          $(id).removeClass('active');
          //Models.data.ComunicacionBaja.remove(new ComunicacionesBajaModel(data));
          Models.data.ComunicacionBaja.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } )
          filas_pendiente--;
          //Models.data.ComunicacionesBaja.EstadoSelector(true);
        }

        self.CambiarOpciones(data, event);

      }

    }

    self.HabilitarFilaInputTipoCambio = function (data, event, option)  {
      //var id = "#"+data.IdTipoCambio();
      if(event)
      {
        var id = data;
        //$(id).addClass('active').siblings().removeClass('active');

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
          var objeto1 = Knockout.CopiarObjeto(Models.data.ComunicacionBaja);
          if(objeto1().length > 0){
            ko.utils.arrayFirst(Models.data.ComunicacionBaja(), function(item) {
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

ComunicacionesBajaConsultaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    //self.EstadoFact = ko.observable(false);

    self.FechaFormateada = ko.pureComputed(function(){
      return FechaFormato.FormatearFechaYYYYMMDD(self.FechaEmisionDocumento());
    }, this);

    self.Icono = ko.pureComputed(function(){

      if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.ACEPTADO){
        return "fa-check";
      }
      else if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.RECHAZADO){
        return "fa-times";
      }
      else{
        return "fa-minus";
      }
    }, this);

    self.VistaCDR = ko.pureComputed(function(){
      if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.ACEPTADO || self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.RECHAZADO)
      {
        return "visible";
      }
      else {
        return "hidden";
      }
    }, this);

    self.Color = ko.pureComputed(function(){

      if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.ACEPTADO){
        return "green";
      }
      else if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.RECHAZADO){
        return "red";
      }
      else{
        return "orange";
      }
    }, this);

    self.VistaCheck = ko.pureComputed(function(){
      return self.IndicadorEstado() == "A" ? "hidden" : "visible";
    }, this);

    self.EstadoCE = ko.computed(function(){
      if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.ACEPTADO){
        return "Aceptado";
      }
      else if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.RECHAZADO){
        return "Rechazado";
      }
      else if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.EN_PROCESO){
        return "En Proceso";
      }
      else if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.GENERADO){
          return "Pend. Envío";
      }
    }, this);

    self.OnVisibleBtnVer = ko.computed(function(){

        if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.ACEPTADO){
          return true;
        }
        else if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.RECHAZADO){
          return true;
        }
        else if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.EN_PROCESO){
          return false;
        }
        else if(self.IndicadorEstadoComunicacionBaja() == ESTADO_CPE.GENERADO){
            return false;
        }
        else {
            return false;
        }
    }, this);

    self.VerFacturas = function(data, event)
    {
      if(event)
      {
        Models.data.DetalleComunicacionesBaja([]);

        ko.utils.arrayFirst(data.DetallesComunicacionBaja(), function(item) {
            Models.data.DetalleComunicacionesBaja.push(item);
          });

        $("#modalPreview").modal("show");

      }
    }

    self.Seleccionar = function(data, event)
    {
      if(event)
      {
        var id = "#"+data.IdComunicacionBaja();
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
         //cantidad_filas = data.length;
         filas_pendiente = 0;
         $("#GenerarBaja").prop('disabled', true);
         Models.data.ComunicacionBaja([]);
       }

    }

    self.BuscarFactura =function(data,event){
      if(event)
      {
        $("#loader").show();
        var accion = "";
        var _data = Knockout.CopiarObjeto(data);

        if(data.NumeroDocumento() == "")
        {
          _data.NumeroDocumento("%");
        }
        if(data.RazonSocial() == "")
        {
          _data.RazonSocial("%");
        }

        var datajs = ko.mapping.toJS({"Data" : _data}, mappingIgnore);

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/FacturacionElectronica/cComunicacionBaja/ConsultarFacturasElectronicasConComunicacionBaja',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                Models.data.ComunicacionesBaja.removeAll();

                Models.data.ComunicacionesBaja([]);
                ko.utils.arrayFirst(data, function(item) {
                    Models.data.ComunicacionesBaja.push(new ComunicacionesBajaModel(item));
                  });

                self.LimpiarOpciones(data, event);

              }
              else {
                Models.data.ComunicacionesBaja([]);
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
         //cantidad_filas = data.length;
         filas_generada = 0;
         $("#EnviarSunat").prop('disabled', true);
         Models.data.ComunicacionBajaConsulta([]);
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
          url: SITE_URL+'/FacturacionElectronica/cComunicacionBaja/ConsultarComunicacionesBaja',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                Models.data.ComunicacionesBajaConsulta.removeAll();

                Models.data.ComunicacionesBajaConsulta([]);
                ko.utils.arrayFirst(data, function(item) {
                    Models.data.ComunicacionesBajaConsulta.push(new ComunicacionesBajaConsultaModel(item));
                  });

                self.LimpiarOpciones(data, event);

              }
              else {
                Models.data.ComunicacionesBajaConsulta([]);
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
      var objeto = ko.mapping.toJS(Models.data.ComunicacionesBajaConsulta);
      objeto.forEach(function(entry, value){
        if(entry.IndicadorEstadoComunicacionBaja == ESTADO_CPE.EN_PROCESO)
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
          url: SITE_URL+'/FacturacionElectronica/cComunicacionBaja/ConsultarEstadoComunicacionBaja',
          success: function (data) {
            ko.utils.arrayForEach(Models.data.ComunicacionesBajaConsulta(), function (observable) {
              data.forEach(function(entry, key){
                if(observable.IdComunicacionBaja() == entry.IdComunicacionBaja)
                {
                  // observable.IndicadorEstadoComunicacionBaja(entry.IndicadorEstadoComunicacionBaja);
                  // observable.IndicadorEstadoComunicacionBaja.valueHasMutated();
                  Models.data.ComunicacionesBajaConsulta.replace(observable, new ComunicacionesBajaConsultaModel(entry));
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

DetalleComunicacionesBajaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.IdDetalleComunicacionBaja = ko.observable("");
    self.Numero = ko.observable("");
    self.RazonSocial = ko.observable("");
    self.MotivoBaja = ko.observable("");
}

var MappingComunicacionBaja = {
    'ComunicacionesBaja': {
        create: function (options) {
            if (options)
              return new ComunicacionesBajaModel(options.data);
            }
    },
    'ComunicacionBaja': {
        create: function (options) {
            if (options)
              return new ComunicacionesBajaModel(options.data);
            }
    },
    'ComunicacionesBajaConsulta': {
        create: function (options) {
            if (options)
              return new ComunicacionesBajaConsultaModel(options.data);
            }
    },
    'ComunicacionBajaConsulta': {
        create: function (options) {
            if (options)
              return new ComunicacionesBajaConsultaModel(options.data);
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
    'DetalleComunicacionesBaja': {
        create: function (options) {
            if (options)
              return new DetalleComunicacionesBajaModel(options.data);
            }
    }
}
