PublicacionesWebModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.FechaFormateada = ko.pureComputed(function(){
      return FechaFormato.FormatearFechaYYYYMMDD(self.FechaEmision());
    }, this);

    //self.EstadoFact = ko.observable(false);
    self.EstadoSelector = ko.observable(false);
    //self.FechaFila = ko.observable(DateFormat.format.date(self.FechaEmision(), "dd/MM/yyyy"));

    self.Icono = ko.pureComputed(function(){
      if(self.IndicadorEstadoPublicacionWeb() == ESTADO_PW.PENDIENTE){
        return "fa-minus";
      }
      else if(self.IndicadorEstadoPublicacionWeb() ==ESTADO_PW.ENVIADO){
        return "fa-check";
      }
      else {
        return "";
      }
    }, this);

    self.Color = ko.pureComputed(function(){
      //return self.IndicadorEstadoPublicacionWeb() == "P" ? "fa-minus" : "fa-check";
      if(self.IndicadorEstadoPublicacionWeb() == ESTADO_PW.PENDIENTE){
        return "orange";
      }
      else if(self.IndicadorEstadoPublicacionWeb() ==ESTADO_PW.ENVIADO){
        return "green";
      }
      else {
        return "";
      }
    }, this);

    self.VistaCheck = ko.pureComputed(function(){
      return self.IndicadorEstadoPublicacionWeb() == ESTADO_PW.PENDIENTE ? "visible" : "hidden";
    }, this);


    self.EstadoCE = ko.computed(function(){
      if(self.IndicadorEstadoPublicacionWeb() == ESTADO_PW.PENDIENTE){
        return "Pendiente";
      }
      else if(self.IndicadorEstadoPublicacionWeb() == ESTADO_PW.ENVIADO){
        return "Enviado";
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
          $("#EnviarFTP").prop('disabled', true)
        }
        else{
          $("#EnviarFTP").prop('disabled', false)
        }
      }
    }

    self.CambiarEstadoCheck = function (data, event) {
      if(event){
        var id = "#"+ data.IdComprobanteElectronico();

        var objeto = Knockout.CopiarObjeto(data);
        if (data.EstadoSelector() == true)
        {
          $(id).addClass('active');
          //Models.data.PublicacionesWeb.EstadoSelector(false);
          Models.data.PublicacionWeb.push(new PublicacionesWebModel(objeto));
          filas_seleccionadas++;
        }
        else
        {
          $(id).removeClass('active');
          //Models.data.PublicacionWeb.remove(new PublicacionesWebModel(data));
          Models.data.PublicacionWeb.remove( function (item) { return item.IdComprobanteElectronico() == objeto.IdComprobanteElectronico(); } )
          filas_seleccionadas--;
          //Models.data.PublicacionesWeb.EstadoSelector(true);
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
          url: SITE_URL+'/FacturacionElectronica/cPublicacionWeb/ExportarPDF',
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


BuscadorModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.NumeroDocumento = ko.observable("");

    self.BusquedaTexto = function(data, event){
      if(data.NumeroDocumento() == ""){
        Models.data.Buscador.NumeroDocumento("%");
      }
    }

    self.LimpiarOpciones = function(event){
        if(event)
       {
         filas_seleccionadas = 0;
         $("#SelectorTodo").prop('checked', false);
         $("#EnviarFTP").prop('disabled', true);
         Models.data.PublicacionWeb([]);
       }

    }

    self.BuscarFactura =function(data,event){
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
          url: SITE_URL+'/FacturacionElectronica/cPublicacionWeb/ConsultarComprobantesVentaElectronico',
          success: function (data) {
              console.log(data);
              if(data != "")
              {
                Models.data.PublicacionesWeb.removeAll();

                Models.data.PublicacionesWeb([]);
                cantidad_filas = 0;
                ko.utils.arrayFirst(data, function(item) {
                    if(item.IndicadorEstadoPublicacionWeb == ESTADO_PW.PENDIENTE){
                      cantidad_filas++;
                    }
                    Models.data.PublicacionesWeb.push(new PublicacionesWebModel(item));
                });
                self.LimpiarOpciones(event);

              }
              else {
                Models.data.PublicacionesWeb([]);
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

var MappingPublicacionWeb = {
    'PublicacionesWeb': {
        create: function (options) {
            if (options)
              return new PublicacionesWebModel(options.data);
            }
    },
    'PublicacionWeb': {
        create: function (options) {
            if (options)
              return new PublicacionesWebModel(options.data);
            }
    },
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel(options.data);
            }
    }
}
