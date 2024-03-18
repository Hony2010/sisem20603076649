
ServiciosModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

}

ServicioModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  
  self.InicializarVistaModeloServicio = function (event) {
    if (event) {
      $("#modalServicio").resetearValidaciones();
    }
  }

  self.CambiarEstadoProducto = ko.computed(function () {
    var estado = self.IndicadorEstadoProducto() ? '1' : '0';
    self.EstadoProducto(estado);
  }, this)


  self.OnChangeInputFile = function (data, event) {
    if (event) {
      $("#FileFoto").readAsImage(event, function ($data) {
        if ($data) {
          $('#img_FileFoto').attr('src', $data.source);
          $('#foto_previa').attr('src', $data.source);
          data.Foto($data.filename);
        }
      });
    }
  }

  self.OnChangeCheckCodigo = function (data, event) {
    if (event) {
      if (event) {
        if ($("#CheckCodigoServicio").prop("checked")) {
          $("#CodigoServicio").attr("disabled", false);
          $("#CodigoServicio").removeClass("no-tab");
          $("#CodigoServicio").focus();
          data.CodigoAutomatico(1);
        }
        else {
          data.CodigoServicio("");
          $("#CodigoServicio").attr("disabled", true);
          $("#CodigoServicio").addClass("no-tab");
          data.CodigoAutomatico(0);
        }
      }
    }
  }



  self.OnFocus = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      if (callback) callback(data, event);
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }

  self.AbrirPreview = function (data, event) {

    if (event) {
      var img = event.target;
      var dataURL = img.src;

      if (dataURL != '') {
        $("#foto_previa").attr('src', dataURL);
        $("#modalPreview").modal("show");
      }
    }
  }

  self.OnChangeTipoAfectacionIGV = function (data, event) {
    if (event) {
      if (self.IdTipoAfectacionIGV() == TIPO_AFECTACION_IGV.GRAVADO) {
        data.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);
      }
      else {
        data.IdTipoPrecio(TIPO_PRECIO.VALOR_REFERENCIAL_OPERACION_GRATUITA);
      }

      Models.data.TiposAfectacionIGV().forEach(function (item) {
        if (item.IdTipoAfectacionIGV() == data.IdTipoAfectacionIGV()) {
          data.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
        }
      });
      Models.data.TiposPrecio().forEach(function (item) {
        if (item.IdTipoPrecio() == data.IdTipoPrecio()) {
          data.CodigoTipoPrecio(item.CodigoTipoPrecio())
        }
      });

    }
  }

  self.OnClickBtnCerrar = function (event) {
    if (event) {
      //$('#FormularioMercaderia').modal('hide');
    }
  }
  
}

SubFamiliaProductoModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
}

SubFamiliasProductoModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
}

var mappingIgnore = {
  'ignore': '__ko_mapping__'
}

var Mapping = {
  'Servicios': {
    create: function (options) {
      if (options)
        return new ServiciosModel(options.data);
    }
  },
  'Servicio': {
    create: function (options) {
      console.log('Servicio');
      console.log(options);
      if (options)
        return new ServicioModel(options.data);
    }
  },
  'NuevoServicio': {
    create: function (options) {
      if (options)
        return new ServicioModel(options.data);
    }
  },
  'SubFamiliaProducto': {
    create: function (options) {
      if (options)
        return new SubFamiliaProductoModel(options.data);
    }
  },
  'SubFamiliasProducto': {
    create: function (options) {
      if (options)
        return new SubFamiliasProductoModel(options.data);
    }
  },
}

jQuery.isSubstring = function (haystack, needle) {
  return haystack.indexOf(needle) !== -1;

};

var ImageURL;

Index = function (data) {

  var _modo_nuevo = false;
  var _opcion_guardar = 1;
  var _objeto;
  var self = this;
  self.MostrarTitulo = ko.observable("");
  self.copiatextofiltro = ko.observable("");
  self.nombrefamilia = ko.observable("");
  self.nombresubfamilia = ko.observable("");

  ImageURL = data.data.ImageURL;

  ko.mapping.fromJS(data, Mapping, self);
 
  self.ConsultarPorPagina = function (data, event) {
    if (event) {
      self.ConsultarServiciosPorPagina(data, event, self.PostConsultarPorPagina);
      $("#Paginador").pagination("drawPage", data.pagina);
    }
  }

  self.PostConsultarPorPagina = function (data, event) {
    if (event) {
      self.data.Servicios([]);
      ko.utils.arrayForEach(data, function (item) {
        self.data.Servicios.push(new ServiciosModel(item));
      });

      var objeto = Models.data.Servicios()[0];
      Models.Seleccionar(objeto, event);
    }
  }

  self.ConsultarServiciosPorPagina = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = ko.mapping.toJS({ "Data": data });
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: datajs,
        cache: false,
        url: SITE_URL + '/Catalogo/cServicio/ConsultarServiciosPorPagina',
        success: function (data) {
          $("#loader").hide();
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert("HA OCURRIDO UN ERROR", jqXHR.responseText);
        }
      });
    }
  }

  self.Consultar = function (data, event) {
    if (event) {
      var tecla = event.keyCode ? event.keyCode : event.which;
      if (tecla == TECLA_ENTER) {
        self.copiatextofiltro(data.textofiltro())
        var inputs = $(event.target).closest('form').find(':input:visible');
        inputs.eq(inputs.index(event.target) + 1).focus();

        self.ConsultarServicios(data, event, self.PostConsultar);
      }
    }
  }

  self.ConsultarServicios = function (data, event, callback) {
    if (event) {
      $("#loader").show();
      var datajs = ko.mapping.toJS({ "Data": data });
      $.ajax({
        type: 'GET',
        dataType: 'json',
        data: datajs,
        url: SITE_URL + '/Catalogo/cServicio/ConsultarServicios',
        success: function (data) {
          $("#loader").hide();
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert("HA OCURRIDO UN ERROR", jqXHR.responseText);
        }
      });
    }
  }

  self.PostConsultar = function (data, event) {
    if (event) {
      self.data.Servicios([]);
      ko.utils.arrayForEach(data.resultado, function (item) {
        self.data.Servicios.push(new ServiciosModel(item));
      });

      var objeto = Models.data.Servicios()[0];
      Models.Seleccionar(objeto, event);
      //ko.mapping.fromJS(data.Filtros,{},self.data.Filtros);
      $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);
      self.data.Filtros.totalfilas(data.Filtros.totalfilas);
    }
  }

  self.Sugerencias = function (event) {
    $("#input-text-filtro").autocomplete({
      delay: 100,
      source: function (request, response) {    
        $.ajax({
          method: 'POST',
          dataType: 'jsonp',
          url: SITE_URL + '/Configuracion/Catalogo/cModelo/DataServer',
          success: function (data) {
            response(data[0]);
          }
        });
      }
    });
  }

  /*self.CargarSubFamilia = function (data, event) {
    if (event) {

      _combo_subfamilia.empty();
      var id_familia = data.IdFamiliaProducto();
      var id_subfamilia = data.IdSubFamiliaProducto();
      url_subfamilia = ko.mapping.toJS(Models.data.SubFamiliasProducto());
      $.each(url_subfamilia, function (key, entry) {
        if (id_familia == entry.IdFamiliaProducto) {
          var sel = "";
          if (id_subfamilia != "") {
            if (id_subfamilia == entry.IdSubFamiliaProducto) {
              sel = 'selected="true"';
            }
          }

          _combo_subfamilia.append($('<option ' + sel + '></option>').attr('value', entry.IdSubFamiliaProducto).text(entry.NombreSubFamiliaProducto));

        }
      })
    }
  }*/

  self.OnChangeSubFamilia = function (data, event) {
    if ($("#modalServicio").is(":visible")) {
      if (event) {
        Models.data.Servicio.IdSubFamiliaProducto($("#combo-subfamiliaproducto").val());
        //console.log("ID SUBFAMILIA REMAPEADO: " + Models.data.Servicio.IdSubFamiliaProducto());
        //self.CargarModelo(data, event);
        if (_opcion_guardar == 0) {
          var nombresubfamilia = $("#combo-subfamiliaproducto option:selected").text() != "NO ESPECIFICADO" ? $("#combo-subfamiliaproducto option:selected").text() : "";
          self.nombresubfamilia(nombresubfamilia);
          //data.NombreProducto(self.nombrefamilia() + " " + self.nombresubfamilia() + " " + self.nombremarca() + " " + self.nombremodelo());
        }

        if (_opcion_guardar == 0) {
          var nombrefamilia = $("#combo-familia").val() != 0 ? $("#combo-familia option:selected").text() : "";
          self.nombrefamilia(nombrefamilia);
          //data.NombreProducto(self.nombrefamilia() + " " + self.nombresubfamilia() + " " + self.nombremarca() + " " + self.nombremodelo());
        }

      }
    }
  }

  self.OnChangeFamilia = function (data, event) {
    if ($("#modalServicio").is(":visible")) {
      var nombrefamilia="";
      if (event) {
        //debugger;
        ListadoSubFamilia.CargarSubFamilia(data,url_subfamilia,_combo_subfamilia);
        //self.CargarSubFamilia(data, event);

        if (data.IdSubFamiliaProducto() == null) {          
          _combo_subfamilia.prop('selectedIndex', 0);
        }
        
        if (_opcion_guardar == 0) {
          var nombrefamilia = $("#combo-familia").val() != 0 ? $("#combo-familia option:selected").text() : "";
          self.nombrefamilia(nombrefamilia);
          //data.NombreProducto(self.nombrefamilia() + " " + self.nombresubfamilia() + " ");
        }

        //setTimeout(function () {
        self.OnChangeSubFamilia(data, event);
        //}, 500);

      }
    }
  }

  self.CargarFoto = function (data) {
    var src = "";
    if (data != null) {
      console.log(data.IdProducto());

      //console.log("ID PRODUCTO: " + data.IdProducto() + "  ., FOTO NOMBRE: " + data.Foto())
      if (data.IdProducto() == "" || data.IdProducto() == null || data.Foto() == null || data.Foto() == "")
        src = BASE_URL + CARPETA_IMAGENES + "nocover.png";
      else
        src = SERVER_URL + CARPETA_IMAGENES + CARPETA_SERVICIO + data.IdProducto() + SEPARADOR_CARPETA + data.Foto();

      return src;
    }
  }

 

  self.SeleccionarAnterior = function (data) {
    var id = "#" + data.IdProducto();
    var anteriorObjeto = $(id).prev();

    anteriorObjeto.addClass('active').siblings().removeClass('active');

    if (_modo_nuevo == false) {
      var match = ko.utils.arrayFirst(self.data.Servicios(), function (item) {
        return anteriorObjeto.attr("id") == item.IdProducto();
      });

      if (match) {
        self.data.FamiliaProducto = match;
      }
      $("#img_FileFoto").attr("src", self.CargarFoto(match));//OJO AQUI
      $("#img_FileFotoPreview").attr("src", self.CargarFoto(match));
    }
  }

  self.SeleccionarSiguiente = function (data) {
    var id = "#" + data.IdProducto();
    var siguienteObjeto = $(id).next();

    if (siguienteObjeto.length > 0) {
      siguienteObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false) //revisar
      {
        var match = ko.utils.arrayFirst(self.data.Servicios(), function (item) {
          return siguienteObjeto.attr("id") == item.IdProducto();
        });

        if (match) {
          self.data.FamiliaProducto = match;
        }
        $("#img_FileFoto").attr("src", self.CargarFoto(match));//OJO AQUI
        $("#img_FileFotoPreview").attr("src", self.CargarFoto(match));
      }
    }
    else {
      self.SeleccionarAnterior(data);
    }
  }

  self.Seleccionar = function (data, event) {
    if (data != undefined) {
      var id = "#" + data.IdProducto();
      $(id).addClass('active').siblings().removeClass('active');
      //debugger;

      var objeto = Knockout.CopiarObjeto(data);
      _objeto = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(objeto, Mapping, Models.data.Servicio);


      $("#img_FileFoto").attr("src", self.CargarFoto(objeto));//OJO AQUI
      $("#img_FileFotoPreview").attr("src", self.CargarFoto(objeto));
    }
  }

  self.LimpiarImagen = function () {
    var src = BASE_URL + CARPETA_IMAGENES + "nocover.png";
    $('#img_FileFoto').attr('src', src);
    $('#img_FileFotoPreview').attr('src', src);
  }

  self.Nuevo = function (data, event) {
    // console.log("AgregarFamiliaProducto");
    if (event) {
      self.MostrarTitulo("REGISTRO DE SERVICIO");

      _objeto = Knockout.CopiarObjeto(Models.data.Servicio);
      ko.mapping.fromJS(Models.data.NuevoServicio, Mapping, Models.data.Servicio);

      //console.log("MOSTRANDO DATA NUEVA SERVICIO");
      //console.log(Models.data.Servicio);

      //self.CargarSubFamilia(Models.data.Servicio, event);
      ListadoSubFamilia.CargarSubFamilia(Models.data.Servicio, url_subfamilia, _combo_subfamilia);
      //LIMPIADOR DE IMAGENES A BLANCO
      self.LimpiarImagen();

      Models.data.Servicio.IdTipoAfectacionIGV(TIPO_AFECTACION_IGV.GRAVADO);
      Models.data.Servicio.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);

      data.TiposAfectacionIGV().forEach(function (item) {
        if (item.IdTipoAfectacionIGV() == Models.data.Servicio.IdTipoAfectacionIGV()) {
          Models.data.Servicio.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
        }
      });
      data.TiposPrecio().forEach(function (item) {
        if (item.IdTipoPrecio() == Models.data.Servicio.IdTipoPrecio()) {
          Models.data.Servicio.CodigoTipoPrecio(item.CodigoTipoPrecio())
        }
      });
    }

    $('#btn_Limpiar').text("Limpiar");

    $("#modalServicio").modal("show");

    setTimeout(function () {
      $("#CheckCodigoServicio").focus();
    }, 1000);
    $("#CodigoServicio").attr('disabled', true);
    Models.data.Servicio.CodigoAutomatico(0);
    self.nombrefamilia("");
    self.nombresubfamilia("");
    _opcion_guardar = 0;
    _modo_nuevo = true;

    self.titulo = "Registro de Servicio";

    //self.InicializarVistaModeloServicio(event)
    $("#modalServicio").resetearValidaciones();
  }

  self.Subir = function ($data) {

    var modal = document.getElementById("form");
    var IdProducto = $("#IdProducto").val();
    //console.log($("#IdProducto").val());
    var data = new FormData(modal);

    $.ajax({
      type: 'POST',
      data: data,
      contentType: false,       // The content type used when sending data to the server.
      cache: false,             // To unable request pages to be cached
      processData: false,        // To send DOMDocument or non processed data file it is set to false
      mimeType: "multipart/form-data",
      url: SITE_URL + '/Catalogo/cServicio/SubirFoto',
      success: function (data) {
        //ACTUALIZANDO LA FILA EN SERVICIOS
        if (_opcion_guardar != 0) {
          self.GuardarActualizar(data, event);
          /*
          var fila_objeto = ko.utils.arrayFirst(Models.data.Servicios(), function (item) {
            return Models.data.Servicio.IdProducto() == item.IdProducto();

          });
          var objeto = Knockout.CopiarObjeto(Models.data.Servicio);
          var objeto2 = ko.mapping.fromJS(Models.data.Servicio);
          Models.data.Servicios.replace(fila_objeto, objeto);

          self.Seleccionar(Models.data.Servicio);

          $("#modalServicio").modal("hide");
          */
        }
        else {
          self.GuardarInsertar($data, event);
          /*
          var copia_objeto = Knockout.CopiarObjeto(Models.data.Servicio);
          Models.data.Servicios.push(new ServiciosModel(copia_objeto));

          self.Seleccionar(Models.data.Servicio);
          alertify.confirm(self.MostrarTitulo(), "Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function () {
            ko.mapping.fromJS(Models.data.NuevoServicio, Mapping, Models.data.Servicio);

            Models.data.Servicio.IdTipoAfectacionIGV(TIPO_AFECTACION_IGV.GRAVADO);
            Models.data.Servicio.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);

            Models.data.TiposAfectacionIGV().forEach(function (item) {
              if (item.IdTipoAfectacionIGV() == Models.data.Servicio.IdTipoAfectacionIGV()) {
                Models.data.Servicio.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
              }
            });
            Models.data.TiposPrecio().forEach(function (item) {
              if (item.IdTipoPrecio() == Models.data.Servicio.IdTipoPrecio()) {
                Models.data.Servicio.CodigoTipoPrecio(item.CodigoTipoPrecio())
              }
            });

            setTimeout(function () {
              $("#CodigoServicio").focus();
            }, 200);
          }, function () {
            _modo_nuevo == false;
            $("#modalServicio").modal("hide");
          });
          */
        }
        $("#loader").hide();
      },      
      error: function (jqXHR, textStatus, errorThrown) {
        var $data = { error: { msg: jqXHR.responseText } };
        $("#loader").hide();
        alertify.alert("Error en " + self.titulo, $data.error.msg, function () { });
      }
    });
  }

  self.DatosDelCombo = function (event) {
    if(event) {
      var nombresubfamilia = $("#combo-subfamiliaproducto option:selected").text();
      Models.data.Servicio.NombreSubFamiliaProducto(nombresubfamilia);
      var nombrefamilia = $("#combo-familia option:selected").text();
      Models.data.Servicio.NombreFamiliaProducto(nombrefamilia);

      var nombretiposervicio = $("#combo-tiposervicio option:selected").text();
      Models.data.Servicio.NombreTipoServicio(nombretiposervicio);
    }
  }

  self.GuardarActualizar = function (data, event) {
    if (event) {
      var fila_objeto = ko.utils.arrayFirst(Models.data.Servicios(), function (item) {
        return Models.data.Servicio.IdProducto() == item.IdProducto();
      });

      var objeto = Knockout.CopiarObjeto(Models.data.Servicio);
      var objeto2 = ko.mapping.fromJS(Models.data.Servicio);
      Models.data.Servicios.replace(fila_objeto, objeto);

      self.Seleccionar(Models.data.Servicio);
      $("#modalServicio").modal("hide");
      
      $("#loader").hide();
    }
  }

  self.GuardarInsertar = function (data, event) {
    if (event) {
      $("#loader").hide();

      alertify.confirm(self.titulo, "Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function () {
        ko.mapping.fromJS(Models.data.NuevServicio, Mapping, Models.data.Servicio);
        document.getElementById("form").reset();
        Models.data.Servicio.IdTipoAfectacionIGV(TIPO_AFECTACION_IGV.GRAVADO);
        Models.data.Servicio.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);
        //Models.data.Mercaderia.IdTipoSistemaISC(TIPO_SISTEMA_ISC.NO_AFECTO);
        //self.OnChangeTipoSistemaISC(event);
        self.LimpiarImagen();
        setTimeout(function () {
          $("#CheckCodigoServicio").focus();
        }, 500);

      }, function () {

        _modo_nuevo == false;
        $("#modalServicio").modal("hide");      

        var objeto_filtro = ko.mapping.toJS(data.Filtros);

        var filas = Models.data.Servicios().length;
        Models.data.Filtros.totalfilas(objeto_filtro.totalfilas);
        if (filas >= 10) {
          $("#Paginador").paginador(objeto_filtro, Models.ConsultarPorPagina);
          var ultimo = $("#Paginador ul li:last").prev();
          ultimo.children("a").click();
        }
        else {
          var copia_nuevo = ko.mapping.toJS(Models.data.Servicio);
          Models.data.Servicios.push(new ServiciosModel(copia_nuevo));
          self.Seleccionar(Models.data.Servicio);
        }

      });

      $("#loader").hide();

    }
  }

  var ignore_array_data = {
    "ignore": [
      "__ko_mapping__",
      "UnidadesMedida",
      "TiposSistemaISC",    
      "OrigenMercaderia",
      "TiposPrecio",
      "TiposAfectacionIGV"
    ]};

  self.Guardar = function (data, event) {
    if (event) {
      if ($("#modalServicio").isValid() === false) {
        alertify.alert(self.titulo, "Existe aun datos inválidos , por favor de corregirlo.", function () { });
        return false;
      }
      var accion = "";
      $("#loader").show();
      if (_opcion_guardar != 0) {
        accion = "ActualizarServicio";
      }
      else {
        accion = "InsertarServicio";
      }
      var _data = data;
      var _mappingIgnore = ko.toJS(ignore_array_data);
      //var datajs = ko.mapping.toJS({ "Data": Models.data.Servicio }, mappingIgnore);
      var datajs = ko.mapping.toJS({ "Data": Models.data.Servicio, "Filtro": self.copiatextofiltro() }, _mappingIgnore);
      console.log(datajs);
      console.log(data);
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cServicio/' + accion,
        success: function (data) {
          console.log(data);
          if (_opcion_guardar != 0) {
            if (!data.error) {
              ko.mapping.fromJS(_data, Mapping, Models.data.Servicio);
              self.DatosDelCombo(event);
              //console.log("ID PRODUCTO" + data.IdProducto);
              //self.Subir();
              var _foto = self.data.Servicio.Foto();
              if (_foto != null) {
                self.Subir(data);
              }
              else{
                self.GuardarActualizar(data, event);
              }
            }
            else {
              alertify.alert("ERROR EN " + self.titulo, data.error.msg);
              $("#CodigoServicio").focus();
            }
          }
          else {
            if (!data.error) {
              ko.mapping.fromJS(data.resultado, Mapping, Models.data.Servicio);
              self.DatosDelCombo(event);
              //console.log("ID PRODUCTO" + data.IdProducto);
              var _foto = self.data.Servicio.Foto();
              if (_foto != "") {
                self.Subir(data);
              }
              else{
                //$("#loader").hide();
                self.GuardarInsertar(data, event);
              }
            }
            else {
              alertify.alert("ERROR EN " + self.titulo, data.error.msg,function () { });
              $("#CodigoServicio").focus();
            }
          }
         // $("#loader").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var $data = { error: { msg: jqXHR.responseText } };
          $("#loader").hide();
          alertify.alert("Error en " + self.titulo, $data.error.msg, function () { });
        }
      });
    }
  }

  self.Editar = function (data, event) {

    if (event) {
      $("#CodigoServicio").attr('disabled', false);
      _opcion_guardar = 1;
      _modo_nuevo = false;
      if (_modo_nuevo == true) {

      }
      else {
        self.MostrarTitulo("EDICIÓN DE SERVICIO");

        //self.CargarSubFamilia(data, event);
        ListadoSubFamilia.CargarSubFamilia(data, url_subfamilia, _combo_subfamilia);

        $('#btn_Limpiar').text("Deshacer");

        $("#modalServicio").modal("show");
        $("#CodigoServicio").focus();

        setTimeout(function () {
          $("#CheckCodigoServicio").focus();
        }, 500);
        $("#CodigoServicio").attr('disabled', true);

        Models.data.Servicio.CodigoAutomatico(0);
      }            
      _modo_nuevo = false;
      self.titulo = "Edición de Servicio";
      //self.InicializarVistaModeloServicio(event);
      $("#modalServicio").resetearValidaciones();
    }
  }

  self.PreEliminar = function (data) {
    self.titulo = "Eliminación de Servicio";
    //setTimeout(function () {
      //self.MostrarTitulo("ELIMINACION DE SERVICIO");
      alertify.confirm(self.MostrarTitulo(), "¿Desea borrar realmente?", function () {
        console.log("PreEliminarServicio");

        if (data.IdProducto() != null)
          self.Eliminar(data);
      }, function () { });
    //}, 100);

  }

  self.Eliminar = function (data) {
    var objeto = data;
    var _datajs = ko.mapping.toJS(data, mappingIgnore);
    //var datajs = ko.toJS({ "Data": data });
    var datajs = ko.toJS({ "Data": _datajs, "Filtro": self.copiatextofiltro() });

    $.ajax({
      type: 'POST',
      data: datajs,
      dataType: "json",
      url: SITE_URL + '/Catalogo/cServicio/BorrarServicio',
      success: function (data) {
        if (data != null) {
          console.log("BorrarServicio");
          //console.log(data);
          if (data.msg != "") {
            alertify.alert("ERROR EN " + self.titulo, data.error.msg);
          }
          else {
            self.SeleccionarSiguiente(objeto);
            Models.data.Servicios.remove(objeto);            
            var filas = Models.data.Servicios().length;

            self.data.Filtros.totalfilas(data.Filtros.totalfilas);
            if (filas == 0) {
              $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);

              var ultimo = $("#Paginador ul li:last").prev();
              ultimo.children("a").click();

            }
          }
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        var $data = { error: { msg: jqXHR.responseText } };
        //alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg);
        alertify.alert("Error en " + self.titulo, $data.error.msg, function () { });
      }
    });


  }

  self.Deshacer = function (event) {
    if (event) {
      if ($('#btn_Limpiar').text() == "Deshacer") {
        self.Seleccionar(_objeto, null);
        //self.CargarSubFamilia(_objeto, event);
        ListadoSubFamilia.CargarSubFamilia(_objeto, url_subfamilia, _combo_subfamilia);
      }
      else if ($('#btn_Limpiar').text() == "Limpiar") {
        ko.mapping.fromJS(Models.data.NuevoServicio, Mapping, Models.data.Servicio);
        document.getElementById("form").reset();
        
        //LIMPIADOR DE IMAGENES A BLANCO      
         self.LimpiarImagen();

        setTimeout(function () {
          $("#CodigoServicio").focus();
        }, 500);
      }

    }
  }

  self.Cerrar = function (event) {
    if (event) {
      $("#modalServicio").modal("hide");
      if (_modo_nuevo == true) {
        _modo_nuevo = false;
      }
      self.Seleccionar(_objeto);

    }
  }
}
