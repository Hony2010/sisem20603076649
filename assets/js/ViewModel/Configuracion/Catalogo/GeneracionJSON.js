IndexGeneracionJSON = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GenerarJSONMercaderia = function(data, event) {
      if(event)
      {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONMercaderia',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                  $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONServicio = function(data, event) {
      if(event)
      {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONServicio',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                  $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONActivoFijo = function(data, event) {
      if(event)
      {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONActivoFijo',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONOtraVenta = function(data, event) {
      if(event)
      {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONOtraVenta',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONCostoAgregado = function(data, event) {
      if(event)
      {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONCostoAgregado',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONGasto = function(data, event) {
      if(event)
      {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONGasto',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONCliente = function(data, event) {
      if(event)
      {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONCliente',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONProveedor = function(data, event) {
      if(event)
      {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONProveedor',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONEmpleado = function(data, event) {
      if(event)
      {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONEmpleado',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONTodos = function(data, event) {
      if(event)
      {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONTodos',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONUsuario = function(data, event) {
      if(event) {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONUsuario',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONComprobantesVenta = function(data, event) {
      if(event) {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONComprobantesVenta',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    self.GenerarJSONProformas = function(data, event) {
      if(event) {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONProformas',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }

    
    self.GenerarJSONPendientesCobranzaCliente = function(data, event) {
      if(event) {
        $("#loader").show();
        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cGeneracionJSON/GenerarJSONPendientesCobranzaCliente',
                success: function (data) {
                  if(data.error)
                  {
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                  }
                  else {
                    alertify.alert("CORRECTO!", "Se generó correctamente el archivo.")
                  }
                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR.responseText);
                    $("#loader").hide();
                    alertify.alert("ADVERTENCIA!", "Ha ocurrido un error con la generación del archivo.");
                }
            });
      }
    }
}
