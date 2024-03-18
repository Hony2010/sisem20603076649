
VistaModeloParametroSistema = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
  self.OnClickGuardarParametro = function (data, event) {
    if (event) {
      var parametros = ko.mapping.toJS(self, mappingIgnore);
      var datajs = { Data: [] }

      for (const key in parametros) {
        if (parametros.hasOwnProperty(key)) datajs.Data.push(parametros[key]);
      }

      $("#loader").show()
      self.ActualizarParametroSistemaPorGrupo(datajs, event, self.PostGuardarParametro)
    }
  }

  self.PostGuardarParametro = function (data, event) {
    if (event) {
      $("#loader").hide()
      if (!data.error) {
        alertify.alert("Configuración", "se actualizó correctamente.", function () {
          ko.mapping.fromJS(data, {}, self);
        })
      } else {
        alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function () { })
      }
    }
  }

  self.OnClickDeshacerCambios = function (data, event) {
    if (event) {
      $("#loader").show();
      var objeto = {"Data" : { "IdGrupoParametro": ViewModels.data.IdGrupoParametro()}};
      self.ConsultarParametroSistemaPorIdGrupo(objeto, event, function ($data) {
        $("#loader").hide()
        if (!$data.error) {
          ko.mapping.fromJS($data, {}, self);
        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { })
        }
      })
    }
  }


  self.ActualizarParametroSistemaPorGrupo = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + `/Configuracion/General/cParametroSistema/ActualizarParametroSistemaPorGrupo`,
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }


  self.ConsultarParametroSistemaPorIdGrupo = function (data, event, callback) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: SITE_URL + `/Configuracion/General/cParametroSistema/ConsultarParametroSistemaPorIdGrupo`,
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

var Mapping = {
  'ParametrosSistema': {
    create: function (options) {
      if (options)
        return new VistaModeloParametroSistema(options.data);
    }
  }
}

IndexParametroSistema = function (data) {
  var self = this;
  ko.mapping.fromJS(data, Mapping, self);
}