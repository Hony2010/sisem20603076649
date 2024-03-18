$(document).ready(function() {

  $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
    var nombre_catalgo = $(this).prop('rel');
    e.preventDefault();
    $(this).siblings('a.active').removeClass("active");
    $(this).addClass("active");
    var index = $(this).index();

    $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
    $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");

    if (nombre_catalgo == "forma_pago") {
      if (vistaModeloGeneral.vmgFormaPago.dataFormaPago.FormasPago()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/FormaPago',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgFormaPago.dataFormaPago.FormasPago([]);
              ko.utils.arrayForEach(response.dataFormaPago.FormasPago, function(item) {
                vistaModeloGeneral.vmgFormaPago.dataFormaPago.FormasPago.push(new FormasPagoModel(item));
              });
              var primera_fila = vistaModeloGeneral.vmgFormaPago.dataFormaPago.FormasPago()[0];
              vistaModeloGeneral.vmgFormaPago.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }

    if (nombre_catalgo == "giro_del_negocio") {
      if (vistaModeloGeneral.vmgGiroNegocio.dataGiroNegocio.GirosNegocio()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/GiroNegocio',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgGiroNegocio.dataGiroNegocio.GirosNegocio([]);
              ko.utils.arrayForEach(response.dataGiroNegocio.GirosNegocio, function(item) {
                vistaModeloGeneral.vmgGiroNegocio.dataGiroNegocio.GirosNegocio.push(new GirosNegocioModel(item));
              });
              var primera_fila = vistaModeloGeneral.vmgGiroNegocio.dataGiroNegocio.GirosNegocio()[0];
              vistaModeloGeneral.vmgGiroNegocio.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }

    if (nombre_catalgo == "grupo_de_parametro") {
      if (vistaModeloGeneral.vmgGrupoParametro.dataGrupoParametro.GruposParametro()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/GrupoParametro',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgGrupoParametro.dataGrupoParametro.GruposParametro([]);
              ko.utils.arrayForEach(response.dataGrupoParametro.GruposParametro, function(item) {
                vistaModeloGeneral.vmgGrupoParametro.dataGrupoParametro.GruposParametro.push(new GruposParametroModel(item));
              });
              var primera_fila = vistaModeloGeneral.vmgGrupoParametro.dataGrupoParametro.GruposParametro()[0];
              vistaModeloGeneral.vmgGrupoParametro.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }

    if (nombre_catalgo == "medio_de_pago") {
      if (vistaModeloGeneral.vmgMedioPago.dataMedioPago.MediosPago()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/MedioPago',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgMedioPago.dataMedioPago.MediosPago([]);
              ko.utils.arrayForEach(response.dataMedioPago.MediosPago, function(item) {
                vistaModeloGeneral.vmgMedioPago.dataMedioPago.MediosPago.push(new MediosPagoModel(item));
              });
              var primera_fila = vistaModeloGeneral.vmgMedioPago.dataMedioPago.MediosPago()[0];
              vistaModeloGeneral.vmgMedioPago.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }

    if (nombre_catalgo == "moneda") {
      if (vistaModeloGeneral.vmgMoneda.dataMoneda.Monedas()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/Moneda',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgMoneda.dataMoneda.Monedas([]);
              ko.utils.arrayForEach(response.dataMoneda.Monedas, function(item) {
                vistaModeloGeneral.vmgMoneda.dataMoneda.Monedas.push(new MonedasModel(item));
              });
              var primera_fila = vistaModeloGeneral.vmgMoneda.dataMoneda.Monedas()[0];
              vistaModeloGeneral.vmgMoneda.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }

    if (nombre_catalgo == "regimen_tributario") {
      if (vistaModeloGeneral.vmgRegimenTributario.dataRegimenTributario.RegimenesTributario()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/RegimenTributario',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgRegimenTributario.dataRegimenTributario.RegimenesTributario([]);
              ko.utils.arrayForEach(response.dataRegimenTributario.RegimenesTributario, function(item) {
                vistaModeloGeneral.vmgRegimenTributario.dataRegimenTributario.RegimenesTributario.push(new RegimenesTributarioModel(item));
              });
              var primera_fila = vistaModeloGeneral.vmgRegimenTributario.dataRegimenTributario.RegimenesTributario()[0];
              vistaModeloGeneral.vmgRegimenTributario.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }

    if (nombre_catalgo == "sede") {
      if (vistaModeloGeneral.vmgSede.dataSede.Sedes()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/Sede',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgSede.dataSede.Sedes([]);
              ko.utils.arrayForEach(response.dataSede.Sedes, function(item) {
                vistaModeloGeneral.vmgSede.dataSede.Sedes.push(new SedesModel(item));
              });

              vistaModeloGeneral.vmgSede.dataSede.Sede.TiposSede([]);
              ko.utils.arrayForEach(response.dataSede.TiposSede, function(item) {
                vistaModeloGeneral.vmgSede.dataSede.Sede.TiposSede.push(new TiposSedeModel(item));
              });

              var primera_fila = vistaModeloGeneral.vmgSede.dataSede.Sedes()[0];
              vistaModeloGeneral.vmgSede.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }

    if (nombre_catalgo == "tipo_de_cambio") {
      if (vistaModeloGeneral.vmgTipoCambio.dataTipoCambio.TiposCambio()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/TipoCambio',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgTipoCambio.dataTipoCambio.TiposCambio([]);
              ko.utils.arrayForEach(response.dataTipoCambio.TiposCambio, function(item) {
                vistaModeloGeneral.vmgTipoCambio.dataTipoCambio.TiposCambio.push(new TiposCambioModel(item));
              });
              var primera_fila = vistaModeloGeneral.vmgTipoCambio.dataTipoCambio.TiposCambio()[0];
              vistaModeloGeneral.vmgTipoCambio.Seleccionar(primera_fila,null);

              var input = ko.toJS(vistaModeloGeneral.vmgTipoCambio.dataTipoCambio.Filtros);
              $("#PaginadorTipoCambio").paginador(input, vistaModeloGeneral.vmgTipoCambio.ConsultarPorPagina);
              $(".fecha-reporte").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});

            }
            $("#loader").hide();
          }
        });
      }
    }

    if (nombre_catalgo == "tipo_de_documento") {
      if (vistaModeloGeneral.vmgTipoDocumento.dataTipoDocumento.TiposDocumento()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/TipoDocumento',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgTipoDocumento.dataTipoDocumento.TiposDocumento([]);
              ko.utils.arrayForEach(response.dataTipoDocumento.TiposDocumento, function(item) {
                vistaModeloGeneral.vmgTipoDocumento.dataTipoDocumento.TiposDocumento.push(new TiposDocumentoModel(item));
              });

              vistaModeloGeneral.vmgTipoDocumento.dataTipoDocumento.TipoDocumento.ModulosSistema([]);
              ko.utils.arrayForEach(response.dataTipoDocumento.ModulosSistema, function(item) {
                vistaModeloGeneral.vmgTipoDocumento.dataTipoDocumento.TipoDocumento.ModulosSistema.push(new ModulosSistemaModel(item));
              });

              var primera_fila = vistaModeloGeneral.vmgTipoDocumento.dataTipoDocumento.TiposDocumento()[0];
              vistaModeloGeneral.vmgTipoDocumento.Seleccionar(primera_fila,event);
            }
            $("#loader").hide();
          }
        });
      }
    }

    if (nombre_catalgo == "tipo_de_sede") {
      if (vistaModeloGeneral.vmgTipoSede.dataTipoSede.TiposSede()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/TipoSede',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgTipoSede.dataTipoSede.TiposSede([]);
              ko.utils.arrayForEach(response.dataTipoSede.TiposSede, function(item) {
                vistaModeloGeneral.vmgTipoSede.dataTipoSede.TiposSede.push(new TiposSedeModel(item));
              });
              var primera_fila = vistaModeloGeneral.vmgTipoSede.dataTipoSede.TiposSede()[0];
              vistaModeloGeneral.vmgTipoSede.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();
          }
        });
      }
    }

    if (nombre_catalgo == "unidad_de_medida") {
      if (vistaModeloGeneral.vmgUnidadMedida.dataUnidadMedida.UnidadesMedida()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/UnidadMedida',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgUnidadMedida.dataUnidadMedida.UnidadesMedida([]);
              ko.utils.arrayForEach(response.dataUnidadMedida.UnidadesMedida, function(item) {
                vistaModeloGeneral.vmgUnidadMedida.dataUnidadMedida.UnidadesMedida.push(new UnidadesMedidaModel(item));
              });

              var primera_fila = vistaModeloGeneral.vmgUnidadMedida.dataUnidadMedida.UnidadesMedida()[0];
              vistaModeloGeneral.vmgUnidadMedida.Seleccionar(primera_fila,null);
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

    if (nombre_catalgo == "configuracion_de_impresion") {
      if (vistaModeloGeneral.vmgConfiguracionImpresion.dataConfiguracionImpresion.ConfiguracionImpresion()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/ConfiguracionImpresion',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgConfiguracionImpresion.dataConfiguracionImpresion.ConfiguracionImpresion([]);
              ko.utils.arrayForEach(response.dataConfiguracionImpresion.ConfiguracionImpresion, function(item) {
                vistaModeloGeneral.vmgConfiguracionImpresion.dataConfiguracionImpresion.ConfiguracionImpresion.push(new ConfiguracionImpresionModel(item));
              });
            }
            $("#loader").hide();
          }
        });
      }
    }

    if (nombre_catalgo == "correlativo_de_documento") {
      if (vistaModeloGeneral.vmgCorrelativoDocumento.dataCorrelativoDocumento.CorrelativosDocumento()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/CorrelativoDocumento',
          success: function (response) {
            if (response != null) {
              //antes de cargar los datos de la grilla , primero se carga los datos de cada combo que esta en cada fila.
              vistaModeloGeneral.vmgCorrelativoDocumento.dataCorrelativoDocumento.TiposDocumento([]);
              ko.utils.arrayForEach(response.dataCorrelativoDocumento.TiposDocumento, function(item) {
                vistaModeloGeneral.vmgCorrelativoDocumento.dataCorrelativoDocumento.TiposDocumento.push(item);
              });

              vistaModeloGeneral.vmgCorrelativoDocumento.dataCorrelativoDocumento.Sedes([]);
              ko.utils.arrayForEach(response.dataCorrelativoDocumento.Sedes, function(item) {
                vistaModeloGeneral.vmgCorrelativoDocumento.dataCorrelativoDocumento.Sedes.push(item);
              });

              vistaModeloGeneral.vmgCorrelativoDocumento.dataCorrelativoDocumento.CorrelativosDocumento([]);
              ko.utils.arrayForEach(response.dataCorrelativoDocumento.CorrelativosDocumento, function(item) {
                vistaModeloGeneral.vmgCorrelativoDocumento.dataCorrelativoDocumento.CorrelativosDocumento.push(new CorrelativosDocumentoModel(item));
              });

              var primera_fila = vistaModeloGeneral.vmgCorrelativoDocumento.dataCorrelativoDocumento.CorrelativosDocumento()[0];
              vistaModeloGeneral.vmgCorrelativoDocumento.Seleccionar(primera_fila,null);
            }
            $("#loader").hide();

          }
        });
      }
    }
    if (nombre_catalgo == "acceso_rol") {
      if (vistaModeloGeneral.vmgAccesoRol.dataAccesoRol.AccesosRol()==0) {
        $("#loader").show();
        $.ajax({
          type: 'GET',
          data : "",
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cVistaModeloGeneral/AccesoRol',
          success: function (response) {
            if (response != null) {
              vistaModeloGeneral.vmgAccesoRol.dataAccesoRol.AccesoRol([]);
              ko.utils.arrayForEach(response.dataAccesoRol.AccesosRol, function(item) {
                vistaModeloGeneral.vmgAccesoRol.dataAccesoRol.AccesosRol.push(new AccesosRolModel(item));
              });

              vistaModeloGeneral.vmgAccesoRol.dataAccesoRol.Roles([]);
              ko.utils.arrayForEach(response.dataAccesoRol.Roles, function(item) {
                vistaModeloGeneral.vmgAccesoRol.dataAccesoRol.Roles.push(item);
              });
          }
            $("#loader").hide();

          }
        });
      }
    }
  });
});
