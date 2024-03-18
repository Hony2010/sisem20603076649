var cantidad_filas = data.data.Numero_Filas;
var filas_seleccionadas = 0;
var option_button = 0;

var Mapping = Object.assign(
  MappingConsultaComprobanteElectronico
);

Index = function (data) {
  var self = this;
  ko.mapping.fromJS(data, Mapping, self);

  self.TipoEnvioComprobante = ko.observable('1');
  self.DirecionEnvioComprobante = ko.observable();
  self.ComprobanteSeleccionado = ko.observable();

  self.Inicializar = function () {
    $("#fecha-inicio").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
    $("#fecha-fin").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
  }

  self.ValidarXML = function (data, event) {
    if (event) {
      $("#loader").show();
      var old_data = data;

      $.ajax({
        type: 'POST',
        dataType: 'json',
        data: { "nombre": old_data.name },
        url: SITE_URL + '/FacturacionElectronica/cComprobanteElectronico/ValidarXML',
        success: function (data) {
          if (data == '1') {
            window.location = old_data.url;
            // window.open(old_data.url,'_blank');
            console.log("Descarga" + old_data.url);
          }
          else {
            alertify.alert("ADVERTENCIA!", "El archivo xml no se encuentra disponible. Consulte con el administrador.");
          }
          $("#loader").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          console.log(jqXHR.responseText);
        }
      });

    }
  }

  self.ValidarCDR = function (data, event) {
    if (event) {
      $("#loader").show();
      var old_data = data;

      $.ajax({
        type: 'POST',
        dataType: 'json',
        data: { "nombre": old_data.name },
        url: SITE_URL + '/FacturacionElectronica/cComprobanteElectronico/ValidarCDR',
        success: function (data) {
          if (data == '1') {
            window.location = old_data.url;
            // window.open(old_data.url,'_blank');
            console.log("Descarga" + old_data.url);
          }
          else {
            alertify.alert("ADVERTENCIA!", "El CDR no se encuentra disponible. Consulte con el administrador.");
          }
          $("#loader").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          console.log(jqXHR.responseText);
        }
      });

    }
  }

  self.AbrirModalDeEnvioDeComprobantes = function (data, event) {
    if (event) {
      $("#modalEnvioComprobanteElectronico").modal("show");

      var cliente = JSON.search(ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_JSON_CLIENTES), `//*[IdPersona="${data.IdCliente()}"]`);
      if (cliente.length > 0) {
        self.ComprobanteSeleccionado(data);
        var direccionEnvio = self.TipoEnvioComprobante() == 1 ? cliente[0].Email : cliente[0].Celular;
        self.DirecionEnvioComprobante(direccionEnvio);
      }
    }
  }

  self.EnviarComprobantesSegunTipo = function (data, event) {
    if (event) {
      var documento = ko.mapping.toJS(self.ComprobanteSeleccionado(), mappingIgnore)
      var resultado = JSON.search(ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_JSON_CLIENTES), `//*[IdPersona="${documento.IdCliente}"]`);

      if (resultado.length > 0) {
        var cliente = resultado[0];

        if (self.TipoEnvioComprobante() == 1) {
          cliente.Email = self.DirecionEnvioComprobante().toLowerCase();
          self.EnviarComprobantePorEmail(documento, event, cliente)
        } else {
          cliente.Celular = self.DirecionEnvioComprobante().toLowerCase();
          self.PublicarComprobanteElectronico(documento, event, cliente)
        }
      }
    }
  }

  self.PublicarComprobanteElectronico = function (data, event, cliente) {
    if (event) {
      
      if (cliente.Celular == '') {
        var val_data = {
          title: "<strong>Validación!</strong>",
          type: "danger",
          clase: "notify-danger",
          message: "El celular del cliente es invalido."
        };
        CargarNotificacionDetallada(val_data);
        return false;
      }

      var mensaje = `Estimado Cliente *${cliente.RazonSocial}*, le invitamos a descargar sus archivos del comprobante *${data.SerieDocumento}-${data.NumeroDocumento}* : PDF : ${data.URLComprobanteElectronicoPDF} y \r\n XML : ${data.URLComprobanteElectronicoXML}, de la empresa ${NOMBRE_EMPRESA} con ruc ${RUC_EMPRESA}. Atentamente SISEM PERU SAC`;

      if (data.IndicadorEstadoPublicacionWeb == ESTADO_PW.PENDIENTE) {
        $("#loader").show();
        
        self.EnviarXMLFTP(data, event, function ($data) {
          $("#loader").hide();
          if (!$data.error) {
            self.ComprobanteSeleccionado().IndicadorEstadoPublicacionWeb(data.estado);
            window.open(`https://api.whatsapp.com/send?phone=${cliente.Celular}&text=${mensaje}`, '_blank');
          } else {
            alertify.alert("ha ocurrido un error", $data.error.msg, function () { });
          }
        })
      } else {
        window.open(`https://api.whatsapp.com/send?phone=${cliente.Celular}&text=${mensaje}`, '_blank');
      }
    }
  }

  self.EnviarComprobantePorEmail = function (data, event, cliente) {
    if (event) {

      var data_notify = { title: "¿Desea Enviar el CPE por Email al Cliente?", message: "Se enviará el XML y PDF respectivo." };
      LoadNotificacionEmail(data_notify, function (res) {
        if (res) {
          if (validarEmail(cliente.Email) == false) {
            var val_data = {
              title: "<strong>Validación!</strong>",
              type: "danger",
              clase: "notify-danger",
              message: "El email del cliente es invalido."
            };
            CargarNotificacionDetallada(val_data);
            return false;
          }
         
          cliente.NombreArchivo = data.NombreArchivoComprobante;
          cliente.IdComprobanteVenta = data.IdComprobanteVenta;
          cliente.SerieDocumento = data.SerieDocumento;
          cliente.NumeroDocumento = data.NumeroDocumento;
          cliente.Total = data.Total;
          cliente.NombreAbreviado = data.NombreAbreviado;
          cliente.NombreTipoDocumento = data.NombreTipoDocumento;
          cliente.IdTipoDocumento = data.IdTipoDocumento;
          $("#loader").show();
          self.EnviarMail(cliente, event, function ($data, $event) {
            $("#loader").hide();
            if (!$data.error) {
              CargarNotificacionDetallada($data);
            } else {
              alertify.alert("ha ocurrido un error", $data.error.msg, function () { });
            }
          });
        }
      });
    }
  }

  self.OnChangeTipoEnvioComprobante = function (data, event) {
    if (event) {
      var documento = ko.mapping.toJS(self.ComprobanteSeleccionado(), mappingIgnore)
      var cliente = JSON.search(ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_JSON_CLIENTES), `//*[IdPersona="${documento.IdCliente}"]`);
      if (cliente.length > 0) {
        var direccionEnvio = self.TipoEnvioComprobante() == 1 ? cliente[0].Email : cliente[0].Celular;
        self.DirecionEnvioComprobante(direccionEnvio);
      }
    }
  }

  self.EnviarMail = function (data, event, callback) {
    if (event) {
      var objeto = Knockout.CopiarObjeto(data);
      var datajs = ko.mapping.toJS({"Data" : data}, mappingIgnore)
  
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Venta/cVenta/EnviarEmail',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.EnviarXMLFTP = function (data, event, callback) {
    var objeto = Knockout.CopiarObjeto(data);
    var datajs = ko.mapping.toJS({"Data" : data}, mappingIgnore)

    if (event) {
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/FacturacionElectronica/cPublicacionWeb/EnviarXMLFTP',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

}
