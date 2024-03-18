ModeloGenerarBackup = function () {
  var self = this;

  self.GenerarBackupBaseDatos = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Seguridad/BaseDatos/cBackupBaseDatos/GenerarBackupBaseDatos',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.DescargarBackupBaseDatos = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Seguridad/BaseDatos/cBackupBaseDatos/DescargarBackupBaseDatos',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.EnviarCorreoBackupBaseDatos = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + '/Seguridad/BaseDatos/cBackupBaseDatos/EnviarCorreoBackupBaseDatos',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }
}