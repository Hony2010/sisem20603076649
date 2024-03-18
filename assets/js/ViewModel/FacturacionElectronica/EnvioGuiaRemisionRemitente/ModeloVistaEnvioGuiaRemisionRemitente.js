EnviosGuiaRemisionRemitenteModel = function (data) {
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
          //Models.data.EnviosGuiaRemisionRemitente.EstadoSelector(false);
          Models.data.EnvioGuiaRemisionRemitente.push(new EnviosGuiaRemisionRemitenteModel(objeto));
          filas_seleccionadas++;
        }
        else
        {
          $(id).removeClass('active');
          //Models.data.EnvioGuiaRemisionRemitente.remove(new EnviosGuiaRemisionRemitenteModel(data));
          Models.data.EnvioGuiaRemisionRemitente.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } )
          filas_seleccionadas--;
          //Models.data.EnviosGuiaRemisionRemitente.EstadoSelector(true);
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
          url: SITE_URL+'/FacturacionElectronica/cEnvioGuiaRemisionRemitente/ExportarPDF',
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

EnviosGuiaRemisionRemitenteConsultaModel = function (data) {
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
        //Models.data.EnviosGuiaRemisionRemitenteConsulta.EstadoSelector(false);
        Models.data.EnvioGuiaRemisionRemitenteConsulta.push(new EnviosGuiaRemisionRemitenteConsultaModel(objeto));
        filas_seleccionadas_consultas++;
      }
      else
      {
        $(id).removeClass('active');
        //Models.data.EnvioGuiaRemisionRemitente.remove(new EnviosGuiaRemisionRemitenteModel(data));
        Models.data.EnvioGuiaRemisionRemitenteConsulta.remove( function (item) { return item.IdComprobanteVenta() == objeto.IdComprobanteVenta(); } )
        filas_seleccionadas_consultas--;
        //Models.data.EnviosGuiaRemisionRemitenteConsulta.EstadoSelector(true);
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
         Models.data.EnvioGuiaRemisionRemitente([]);
       }

    }

    self.BuscarGuiaRemision =function(data,event){
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
          url: SITE_URL+'/FacturacionElectronica/cEnvioGuiaRemisionRemitente/ConsultarGuiasRemisionRemitenteEnvio',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                Models.data.EnviosGuiaRemisionRemitente.removeAll();

                Models.data.EnviosGuiaRemisionRemitente([]);
                cantidad_filas = 0;
                ko.utils.arrayFirst(data, function(item) {
                    if(item.IndicadorEstadoCPE == ESTADO_CPE.GENERADO){
                      cantidad_filas++;
                    }
                    Models.data.EnviosGuiaRemisionRemitente.push(new EnviosGuiaRemisionRemitenteModel(item));
                });
                self.LimpiarOpciones(event);

              }
              else {
                cantidad_filas = 0;
                Models.data.EnviosGuiaRemisionRemitente([]);
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
       Models.data.EnvioGuiaRemisionRemitenteConsulta([]);
     }

  }

  self.BuscarGuiaRemision =function(data,event){
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
        url: SITE_URL+'/FacturacionElectronica/cEnvioGuiaRemisionRemitente/ConsultarGuiasRemisionRemitenteEnvioPendiente',
        success: function (data) {
            console.log(data);
            if(data != "")
            {
              Models.data.EnviosGuiaRemisionRemitenteConsulta.removeAll();

              Models.data.EnviosGuiaRemisionRemitenteConsulta([]);
              cantidad_filas_consultas = 0;
              
              ko.utils.arrayFirst(data, function(item) {
                  if(item.IndicadorEstadoCPE == ESTADO_CPE.EN_PROCESO){
                    cantidad_filas_consultas++;
                  }
                  Models.data.EnviosGuiaRemisionRemitenteConsulta.push(new EnviosGuiaRemisionRemitenteConsultaModel(item));
              });
              
              self.LimpiarOpciones(event);              
            }
            else {
              cantidad_filas_consultas = 0;
              self.LimpiarOpciones(event);
              Models.data.EnviosGuiaRemisionRemitenteConsulta([]);
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

var MappingEnvioGuiaRemisionRemitente = {
    'EnviosGuiaRemisionRemitente': {
        create: function (options) {
            if (options)
              return new EnviosGuiaRemisionRemitenteModel(options.data);
            }
    },
    'EnvioGuiaRemisionRemitente': {
        create: function (options) {
            if (options)
              return new EnviosGuiaRemisionRemitenteModel(options.data);
            }
    },
    'EnviosGuiaRemisionRemitenteConsulta': {
        create: function (options) {
            if (options)
              return new EnviosGuiaRemisionRemitenteConsultaModel(options.data);
            }
    },
    'EnvioGuiaRemisionRemitenteConsulta': {
        create: function (options) {
            if (options)
              return new EnviosGuiaRemisionRemitenteConsultaModel(options.data);
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
