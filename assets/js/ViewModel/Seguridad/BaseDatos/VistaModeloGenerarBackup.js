VistaModeloGenerarBackup = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  ModeloGenerarBackup.call(this, self);

  self.OnClickBtnGenerarBackup = function (data, event) {
    if (event) {

      $("#loader").show();
      var datajs = { Data: {} }
      self.GenerarBackupBaseDatos(datajs, event, function ($data) {
        $("#loader").hide();
        if (!$data.error) {
          alertify.alert("Base de datos", `Se ha generado el backup ${$data.nombre}`, function () { 
            self.BackupBaseDatos($data);
          });
        } else {
          alertify.alert("ha ocurrido un error", $data.error.msg, function () { })
        }
      })

    }
  }

  self.OnClickBtnDescargarBackup = function (data, event) {
    if (event) {

      if (data.BackupBaseDatos().resultado === undefined) {
        alertify.alert("Base de datos", "Primero debe generar el backup de la base de datos.", function () { });
        return false;
      }

      var datajs = JSON.stringify(ko.mapping.toJS(self.BackupBaseDatos()));

      window.location = `${SITE_URL}/Seguridad/BaseDatos/cBackupBaseDatos/DescargarBackupBaseDatos?Data=${datajs}` 
    }
  }

  self.OnClickBtnEnviarCorreo = function (data, event) {
    if (event) {
      if (self.BackupBaseDatos().resultado === undefined) {
        alertify.alert("Base de datos", "Primero debe generar el backup de la base de datos.", function () { });
        return false;
      }

      alertify.prompt("Base de datos", "Para enviar al correo ingrese el correo destinatario:", "", function (event, email) {

        var datajs = {
          Data: {
            ruta: self.BackupBaseDatos().ruta,
            Email: email
          }
        }

        $("#loader").show();
        self.EnviarCorreoBackupBaseDatos(datajs, event, function ($data) {
          $("#loader").hide();
          if (!$data.error) {
            alertify.alert("Base de datos", `El email fue enviado correctamente.`, function () { 
              self.BackupBaseDatos($data);
            });
          } else {
            alertify.alert("ha ocurrido un error", $data.error.msg, function () { })
          }
        })
      }, function () { }).set('labels', {ok:'Enviar', cancel:'Cancelar'});
    }
  }
}

var MappingGenerarBackup = {
  'data': {
    create: function (options) {
      if (options)
        return new VistaModeloGenerarBackup(options.data);
    }
  }
}

IndexGenerarBackup = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingGenerarBackup, self);
}
