EnviosFacturaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.FechaFormateada = ko.pureComputed(function(){
      return FechaFormato.FormatearFechaYYYYMMDD(self.FechaEmision());
    }, this);

    //self.EstadoFact = ko.observable(false);
    self.EstadoSelector = ko.observable(false);
    //self.FechaFila = ko.observable(DateFormat.format.date(self.FechaEmision(), "dd/MM/yyyy"));

    self.Icono = ko.pureComputed(function(){
      if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
        return "fa-minus";
      }
      else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.RECHAZADO){
        return "fa-times";
      }
      else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.ACEPTADO){
        return "fa-check";
      }
      else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.EN_PROCESO){
        return "fa-hourglass-end";
      }
      else {
        return "";
      }
    }, this);

    self.Color = ko.pureComputed(function(){
      //return self.IndicadorEstadoCPE() == "P" ? "fa-minus" : "fa-check";
      if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
        return "orange";
      }
      else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.RECHAZADO){
        return "red";
      }
      else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.ACEPTADO){
        return "green";
      }
      else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.EN_PROCESO){
        return "gray";
      }
      else {
        return "";
      }
    }, this);

    self.VistaCheck = ko.pureComputed(function(){
      return self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO ? "visible" : "hidden";
    }, this);


    self.EstadoCE = ko.computed(function(){
      if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
        return "Pendiente";
      }
      else if(self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO){
        return "Rechazado";
      }
      else if(self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
        return "Aceptado";
      }
      else if(self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO){
          return "En Proceso";
      }
    }, this);

    self.CambiarOpcionesCheck = function(event)
    {
      if(event)
      {
        if(cantidad_filas == filas_seleccionadas){
          $("#SelectorTodo").prop('checked', true);
        }
        else {
          $("#SelectorTodo").prop('checked', false);
        }

      }
    }

    self.CambiarOpcionesButton = function(event)
    {
      if(event)
      {

        //PARA HABILITAR Y DESHABILITAR EL BUTTON
        if(filas_seleccionadas <= 0){
          $("#EnviarSunat").prop('disabled', true)
        }
        else{
          $("#EnviarSunat").prop('disabled', false)
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
          //Models.data.EnviosFactura.EstadoSelector(false);
          Models.data.EnvioFactura.push(new EnviosFacturaModel(objeto));
          filas_seleccionadas++;
        }
        else
        {
          $(id).removeClass('active');
          //Models.data.EnvioFactura.remove(new EnviosFacturaModel(data));
          Models.data.EnvioFactura.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } )
          filas_seleccionadas--;
          //Models.data.EnviosFactura.EstadoSelector(true);
        }

        self.CambiarOpcionesCheck(event);
        self.CambiarOpcionesButton(event);

      }

    }

    self.GenerarPDF = function(data, event){
      if(event)
      {
        $("#loader").show();
        var accion = "";
        var _data = Knockout.CopiarObjeto(data);
        var datajs = ko.mapping.toJS({"Data" : _data}, mappingIgnore);

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          context: document.body,
          url: SITE_URL+'/FacturacionElectronica/cEnvioFactura/ExportarPDF',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                $('#DescargarPDF_iframe').prop('src', "");
                $('#DescargarPDF_iframe').prop('src', data);
                $("#modalPDFGenerado").modal("show");
              }
              else {

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

EnviosFacturaConsultaModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.FechaFormateada = ko.pureComputed(function(){
    return FechaFormato.FormatearFechaYYYYMMDD(self.FechaEmision());
  }, this);

  //self.EstadoFact = ko.observable(false);
  self.EstadoSelector = ko.observable(false);
  //self.FechaFila = ko.observable(DateFormat.format.date(self.FechaEmision(), "dd/MM/yyyy"));

  self.Icono = ko.pureComputed(function(){
    if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
      return "fa-minus";
    }
    else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.RECHAZADO){
      return "fa-times";
    }
    else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.ACEPTADO){
      return "fa-check";
    }
    else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.EN_PROCESO){
      return "fa-hourglass-end";
    }
    else {
      return "";
    }
  }, this);

  self.Color = ko.pureComputed(function(){
    //return self.IndicadorEstadoCPE() == "P" ? "fa-minus" : "fa-check";
    if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
      return "orange";
    }
    else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.RECHAZADO){
      return "red";
    }
    else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.ACEPTADO){
      return "green";
    }
    else if(self.IndicadorEstadoCPE() ==ESTADO_CPE.EN_PROCESO){
      return "gray";
    }
    else {
      return "";
    }
  }, this);

  self.VistaCheck = ko.pureComputed(function(){
    return self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO ? "visible" : "hidden";
  }, this);


  self.EstadoCE = ko.computed(function(){
    if(self.IndicadorEstadoCPE() == ESTADO_CPE.GENERADO){
      return "Pendiente";
    }
    else if(self.IndicadorEstadoCPE() == ESTADO_CPE.RECHAZADO){
      return "Rechazado";
    }
    else if(self.IndicadorEstadoCPE() == ESTADO_CPE.ACEPTADO){
      return "Aceptado";
    }
    else if(self.IndicadorEstadoCPE() == ESTADO_CPE.EN_PROCESO){
        return "En Proceso";
    }
  }, this);

  self.CambiarOpcionesCheck = function(event)
  {
    if(event)
    {
      if(cantidad_filas_consultas == filas_seleccionadas_consultas){
        $("#SelectorTodoConsulta").prop('checked', true);
      }
      else {
        $("#SelectorTodoConsulta").prop('checked', false);
      }

    }
  }

  self.CambiarOpcionesButton = function(event)
  {
    if(event)
    {

      //PARA HABILITAR Y DESHABILITAR EL BUTTON
      if(filas_seleccionadas_consultas <= 0){
        $("#EnviarSunatConsulta").prop('disabled', true)
      }
      else{
        $("#EnviarSunatConsulta").prop('disabled', false)
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
        //Models.data.EnviosFacturaConsulta.EstadoSelector(false);
        Models.data.EnvioFacturaConsulta.push(new EnviosFacturaConsultaModel(objeto));
        filas_seleccionadas_consultas++;
      }
      else
      {
        $(id).removeClass('active');
        //Models.data.EnvioFactura.remove(new EnviosFacturaModel(data));
        Models.data.EnvioFacturaConsulta.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } )
        filas_seleccionadas_consultas--;
        //Models.data.EnviosFacturaConsulta.EstadoSelector(true);
      }

      self.CambiarOpcionesCheck(event);
      self.CambiarOpcionesButton(event);

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
        Models.data.Consulta.NumeroDocumento("%");
      }
    }

    self.LimpiarOpciones = function(event){
        if(event)
       {
         filas_seleccionadas = 0;
         $("#SelectorTodo").prop('checked', false);
         $("#EnviarSunat").prop('disabled', true);
         Models.data.EnvioFactura([]);
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
          url: SITE_URL+'/FacturacionElectronica/cEnvioFactura/ConsultarComprobantesVentaEnvio',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                Models.data.EnviosFactura.removeAll();

                Models.data.EnviosFactura([]);
                cantidad_filas = 0;
                ko.utils.arrayFirst(data, function(item) {
                    if(item.IndicadorEstadoCPE == ESTADO_CPE.GENERADO){
                      cantidad_filas++;
                    }
                    Models.data.EnviosFactura.push(new EnviosFacturaModel(item));
                });
                self.LimpiarOpciones(event);

              }
              else {
                cantidad_filas = 0;
                Models.data.EnviosFactura([]);
                self.LimpiarOpciones(event);
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
  self.RazonSocial = ko.observable("");

  self.BusquedaTexto = function(data, event){
    if(data.NumeroDocumento() == ""){
      Models.data.Buscador.NumeroDocumento("%");
    }
  }

  self.LimpiarOpciones = function(event){
      if(event)
     {
       filas_seleccionadas_consultas = 0;
       $("#SelectorTodoConsulta").prop('checked', false);
       $("#EnviarSunatConsulta").prop('disabled', true);
       Models.data.EnvioFacturaConsulta([]);
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
        url: SITE_URL+'/FacturacionElectronica/cEnvioFactura/ConsultarComprobantesVentaEnvioPendiente',
        success: function (data) {
            console.log(data);
            if(data != "")
            {
              Models.data.EnviosFacturaConsulta.removeAll();

              Models.data.EnviosFacturaConsulta([]);
              cantidad_filas_consultas = 0;
              ko.utils.arrayFirst(data, function(item) {
                  if(item.IndicadorEstadoCPE == ESTADO_CPE.EN_PROCESO){
                    cantidad_filas_consultas++;
                  }
                  Models.data.EnviosFacturaConsulta.push(new EnviosFacturaConsultaModel(item));
              });
              self.LimpiarOpciones(event);

            }
            else {
              cantidad_filas_consultas = 0;
              self.LimpiarOpciones(event);
              Models.data.EnviosFacturaConsulta([]);
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

var MappingEnvioFactura = {
    'EnviosFactura': {
        create: function (options) {
            if (options)
              return new EnviosFacturaModel(options.data);
            }
    },
    'EnvioFactura': {
        create: function (options) {
            if (options)
              return new EnviosFacturaModel(options.data);
            }
    },
    'EnviosFacturaConsulta': {
        create: function (options) {
            if (options)
              return new EnviosFacturaConsultaModel(options.data);
            }
    },
    'EnvioFacturaConsulta': {
        create: function (options) {
            if (options)
              return new EnviosFacturaConsultaModel(options.data);
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
    }
}
