<!-- ko with : GuiaRemisionRemitente -->
<form id="formGuiaRemisionRemitente" name="formGuiaRemisionRemitente" role="form" autocomplete="off">
  <div class="datalist__result">
    <div class="tab-pane active" id="brand" role="tabpanel">
      <div class="container-fluid">
        <div class="row">
          <fieldset>
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Serie</div>
                      <select name="combo-seriedocumento" id="combo-seriedocumento" class="form-control formulario" data-bind="
                        value: IdCorrelativoDocumento,
                        options: SeriesDocumento,
                        optionsValue: 'IdCorrelativoDocumento' ,
                        optionsText: 'SerieDocumento' ,
                        event: { focus: OnFocus, change : OnChangeSerieDocumento ,keydown: OnKeyEnter } "> </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Número</div>
                      <input id="NumeroDocumento" type="text" disabled class="form-control formulario disabled" data-bind="
                        value: NumeroDocumento,
                        event: { focus: OnFocus, keydown: OnKeyEnter }">
                    </div>
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-5">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">F. emisión</div>
                            <input id="FechaEmision" type="text" class="form-control formulario fecha" data-bind="
                            value: FechaEmision,
                            event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeFechaEmision }" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="Ingrese una fecha válida">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-7">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">Motivo de Traslado</div>
                            <select id="MotivosTraslado" class="form-control formulario" data-bind="
                            value: IdMotivoTraslado,
                            options: MotivosTraslado,
                            optionsValue: 'IdMotivoTraslado' ,
                            optionsText: 'NombreMotivoTraslado' ,
                            event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeComboMotivoTraslado }"> </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-5">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">F. traslado</div>
                            <input id="FechaTraslado" type="text" class="form-control formulario fecha" data-bind="
                            value: FechaTraslado,
                            event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeFechaTraslado }" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="Ingrese una fecha válida">
                          </div>
                        </div>
                      </div>
                      <!-- ko if: IdMotivoTraslado() == ID_PARAMETRO_MOTIVO_TRASLADO_VENTA -->
                      <div class="col-md-7">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">Doc. Ref</div>
                            <input id="AutocompletadoReferencia" type="text" class="form-control formulario" data-bind="
                            value: NumeroDocumentoReferencia,
                            event: { focus: OnFocus, keydown: OnKeyEnter }" data-validation="autocompletado" data-validation-error-msg="" data-validation-text-found="">
                          </div>
                        </div>
                      </div>
                      <!-- /ko -->
                      <!-- ko ifnot: IdMotivoTraslado() == ID_PARAMETRO_MOTIVO_TRASLADO_VENTA -->
                      <div class="col-md-7">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">Doc. Ref</div>
                            <input id="NumeroDocumentoReferencia" type="text" class="form-control formulario" data-bind="
                            value: NumeroDocumentoReferencia,
                            event: { focus: OnFocus, keydown: OnKeyEnter }">
                          </div>
                        </div>
                      </div>
                      <!-- /ko -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Destinatario</div>
                      <input id="NombreDestinatario" type="text" class="form-control formulario" data-bind="
                      value: NombreDestinatario,
                      event: { focus: OnFocus, keydown: OnKeyEnter, change: ValidarDestinatario }" data-validation="autocompletado" data-validation-error-msg="" data-validation-text-found="">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Punto de Partida</legend>
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Dirección</div>
                      <input id="DireccionPuntoPartida" type="text" class="form-control formulario" data-bind="
                      value: DireccionPuntoPartida,
                      event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeDireccionPuntoPartida }" data-validation="required" data-validation-error-msg="Este es un campo obligatorio">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Departamento</div>
                      <select id="DepartamentosPuntoPartida" class="form-control formulario" data-bind="
                      value: IdDepartamentoPuntoPartida,
                      options: $parent.Departamentos,
                      optionsValue: 'IdDepartamento' ,
                      optionsText: 'NombreDepartamento',
                      optionsCaption : 'Seleccionar...',
                      event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeComboDepartamentoPuntoPartida }" data-validation="select" data-validation-error-msg="Selecciona un item"> </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Provincia</div>
                      <select id="ProvinciasPuntoPartida" class="form-control formulario" data-bind="
                      value: IdProvinciaPuntoPartida,
                      options: ProvinciasPuntoPartida,
                      optionsValue: 'IdProvincia' ,
                      optionsText: 'NombreProvincia' ,
                      optionsCaption : 'Seleccionar...',
                      event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeComboProvinciasPuntoPartida }" data-validation="select" data-validation-error-msg="Selecciona un item"> </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Distrito</div>
                      <select id="DistritosPuntoPartida" class="form-control formulario" data-bind="
                      value: IdDistritoPuntoPartida,
                      options: DistritosPuntoPartida,
                      optionsValue: 'IdDistrito' ,
                      optionsText: 'NombreDistrito' ,
                      optionsCaption : 'Seleccionar...',
                      event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeComboDistritoPuntoPartida }" data-validation="select" data-validation-error-msg="Selecciona un item"> </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Dirección Completa</div>
                      <input id="DireccionCompletaPuntoPartida" type="text" disabled class="form-control formulario disabled" data-bind="
                      value: DireccionCompletaPuntoPartida,
                      event: { focus: OnFocus, keydown: OnKeyEnter }">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend>Punto de Llegada</legend>
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Dirección</div>
                      <input id="DireccionPuntoLlegada" type="text" class="form-control formulario" data-bind="
                      value: DireccionPuntoLlegada,
                      event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeDireccionPuntoLlegada }" data-validation="required" data-validation-error-msg="Este es un campo obligatorio">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Departamento</div>
                      <select id="DepartamentosPuntoLlegada" class="form-control formulario" data-bind="
                      value: IdDepartamentoPuntoLlegada,
                      options: $parent.Departamentos,
                      optionsValue: 'IdDepartamento' ,
                      optionsText: 'NombreDepartamento' ,
                      optionsCaption : 'Seleccionar...',
                      event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeComboDepartamentoPuntoLlegada }" data-validation="select" data-validation-error-msg="Selecciona un item"> </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Provincia</div>
                      <select id="ProvinciasPuntoLlegada" class="form-control formulario" data-bind="
                          value: IdProvinciaPuntoLlegada,
                          options: ProvinciasPuntoLlegada,
                          optionsValue: 'IdProvincia' ,
                          optionsText: 'NombreProvincia' ,
                          optionsCaption : 'Seleccionar...',
                          event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeComboProvinciasPuntoLlegada }" data-validation="select" data-validation-error-msg="Selecciona un item"> </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Distrito</div>
                      <select id="DistritosPuntoLlegada" class="form-control formulario" data-bind="
                      value: IdDistritoPuntoLlegada,
                      options: DistritosPuntoLlegada,
                      optionsValue: 'IdDistrito' ,
                      optionsText: 'NombreDistrito' ,
                      optionsCaption : 'Seleccionar...',
                      event: { focus: OnFocus, keydown: OnKeyEnter, change: OnChangeComboDistritoPuntoLlegada }" data-validation="select" data-validation-error-msg="Selecciona un item"> </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Dirección Completa</div>
                      <input id="DireccionCompletaPuntoLlegada" type="text" disabled class="form-control formulario disabled" data-bind="
                      value: DireccionCompletaPuntoLlegada,
                      event: { focus: OnFocus, keydown: OnKeyEnter }">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <div class="form-group" style="margin-top: 10px">
            <b style="margin-right: 10px">MODALIDAD DE TRASLADO: </b>
            <div class="radio radio-inline">
              <input id="TransportePublico" type="radio" name="ModalidadTrasnporte" value="1" data-bind="checked: IdModalidadTraslado">
              <label for="TransportePublico">Público (Terceros)</label>
            </div>
            <div class="radio radio-inline">
              <input id="TransportePrivado" type="radio" name="ModalidadTrasnporte" value="2" data-bind="checked: IdModalidadTraslado">
              <label for="TransportePrivado">Privado (Propio de la Empresa)</label>
            </div>
          </div>
          <fieldset>
            <legend>Datos del Transportista</legend>
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Nombre / Razón S. <strong class="alert-info">(*)</strong></div>
                      <input id="NombreTransportista" type="text" class="form-control formulario" data-bind="
                      value: NombreTransportista,
                      event: { focus: OnFocus, keydown: OnKeyEnter }" data-validation="autocompletado" data-validation-error-msg="" data-validation-text-found="">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="col-md-9">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Dirección</div>
                      <input id="DireccionTransportista" type="text" class="form-control formulario" data-bind="
                      value: DireccionTransportista,
                      event: { focus: OnFocus, keydown: OnKeyEnter }">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Placa <strong class="alert-info" data-bind="visible: IdModalidadTraslado() == 2">(*)</strong> </div>
                      <input id="PlacaVehiculo" type="text" class="form-control formulario" data-bind="
                        value: PlacaVehiculo,
                        event: { focus: OnFocus, keydown: OnKeyEnter }" data-validation="placa" data-validation-error-msg="Campo obligatorio">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div id="NumeroBrevete" class="input-group-addon formulario">Brevete</div>
                      <input type="text" class="form-control formulario" data-bind="
                          value: NumeroLicenciaConducir,
                          event: { focus: OnFocus, keydown: OnKeyEnter }">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Marca Vehiculo</div>
                      <input id="MarcaVehiculo" type="text" class="form-control formulario" data-bind="
                      value: MarcaVehiculo,
                      event: { focus: OnFocus, keydown: OnKeyEnter }">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">C. Inscripción</div>
                      <input id="NumeroConstanciaInscripcion" type="text" class="form-control formulario" data-bind="
                      value: NumeroConstanciaInscripcion,
                      event: { focus: OnFocus, keydown: OnKeyEnter }">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <div class="input-group">
                      <div class="input-group-addon formulario">Guia Transporte</div>
                      <input id="NumeroGuiaTransportista" type="text" class="form-control formulario" data-bind="
                      value: NumeroGuiaTransportista,
                      event: { focus: OnFocus, keydown: OnKeyEnter }">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </fieldset>
          <br>
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario">Observación</div>
                    <input id="Observacion" type="text" class="form-control formulario" data-bind="
                      value: Observacion,
                      event: { focus: OnFocus, keydown: OnKeyEnter }">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <fieldset>
            <table id="DetallesGuiaRemisionRemitente" class="table grid-detail-body">
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Descripción</th>
                  <th class="col-sm-1 products__title">Unidad</th>
                  <!-- ko if: (ParametroLote() != 0) -->
                  <th class="col-sm-1 products__title">
                      <center>Lote</center>
                  </th>
                  <!-- /ko -->
                  <th>Cantidad</th>
                  <th>Peso</th>
                  <th data-bind="visible : IdMotivoTraslado() == ID_PARAMETRO_MOTIVO_TRASLADO_VENTA">Pendiente</th>
                  <th width="41"></th>
                </tr>
              </thead>
              <tbody>
                <!-- ko foreach : DetallesGuiaRemisionRemitente -->
                <tr>
                  <td>
                    <div class="form-group">
                      <input type="text" class="form-control formulario" data-bind="
                      value: CodigoMercaderia,
                      disable : $parent.IdMotivoTraslado() == ID_PARAMETRO_MOTIVO_TRASLADO_VENTA,
                      attr: { id: 'input_CodigoMercaderia_' + IdDetalleGuiaRemisionRemitente() },
                      event: { focus: OnFocusCodigoMercaderia, keydown: OnKeyEnterCodigoMercaderia}" data-validation="validacion_producto" data-validation-error-msg="Cod. Inválido" data-validation-text-found="">
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                      <input type="text" class="form-control formulario" data-bind="
                      value: NombreProducto,
                      attr: { id: 'input_NombreProducto_' + IdDetalleGuiaRemisionRemitente(), 'data-validation-reference': 'input_CodigoMercaderia_' + IdDetalleGuiaRemisionRemitente() },
                      event: { focus: OnFocusNombreProducto, change: OnChangeNombreProducto }" data-validation="autocompletado_producto" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto" style="width: 300px;">
                    </div>
                  </td>
                
                  <td>                    
                    <span data-bind="text: AbreviaturaUnidadMedida"></span>
                  </td>                  
                        
                  <!-- ko if: ($parent.ParametroLote() != 0 ) -->
                  <td class="col-sm-1">
                    <input name="NumeroLote" class="form-control formulario" data-bind="
                    value : NumeroLote , attr : { id : IdDetalleGuiaRemisionRemitente() + '_input_NumeroLote'},
                    disable : $parent.IdMotivoTraslado() == ID_PARAMETRO_MOTIVO_TRASLADO_VENTA && UltimoItem() == true,
                    event: {                    
                      focus : function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } , 
                      keydown : function(data,event) { return OnKeyEnter(data,event,$parent.OnKeyEnter); }, 
                      focusout : ValidarNumeroLote } , 
                      ko_autocomplete_lote: { lote: DataLotes($parent.IdAsignacionSede()),id: IdLoteProducto}" type="text" 
                     data-validation-found="false"> <!--  data-validation="required_lote" data-validation-error-msg="Requerido"  -->
                  </td>
                  <!-- /ko -->
                  <td>
                    <div class="form-group">
                      <input type="text" class="form-control formulario text-right" data-bind="
                      value: Cantidad,
                      disable : $parent.IdMotivoTraslado() == ID_PARAMETRO_MOTIVO_TRASLADO_VENTA && UltimoItem() == true,
                      attr: { id: 'input_Cantidad_' + IdDetalleGuiaRemisionRemitente(), 'data-cantidad-decimal': DecimalCantidad() },
                      event: { focus: OnFocusCantidad, keydown: $parent.OnKeyEnter, focusout: OnFocusOutCantidad, change: OnChangeCantidad },
                      numberdecimal: Cantidad " data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                    </div>
                  </td>
                  <td>
                    <div class="form-group"><!-- disable : $parent.IdMotivoTraslado() == ID_PARAMETRO_MOTIVO_TRASLADO_VENTA, -->
                      <input type="text" class="form-control formulario text-right" data-bind="
                      value: Peso,                      
                      disable : $parent.IdMotivoTraslado() == ID_PARAMETRO_MOTIVO_TRASLADO_VENTA && UltimoItem() == true,
                      attr: { id: 'input_Peso_' + IdDetalleGuiaRemisionRemitente(), 'data-cantidad-decimal': DecimalPeso() },
                      numberdecimal: Peso,
                      event: { focus: $parent.OnChangePesoBrutoTotal, keydown: $parent.OnKeyEnter, focusout: $parent.OnChangePesoBrutoTotal, change : $parent.OnChangePesoBrutoTotal }"
                      
                      data-validation="number_calc" data-validation-allowing="float,positive,range[0.001;9999999]" 
                      data-validation-decimal-separator="." data-validation-error-msg="De 0 a más">
                    </div>
                  </td>
                  <td data-bind="visible : $parent.IdMotivoTraslado() == ID_PARAMETRO_MOTIVO_TRASLADO_VENTA">
                    <div class="form-group">
                      <input type="text" class="form-control formulario text-right" data-bind="
                      value: SaldoPendienteGuiaRemision,
                      disable : $parent.IdMotivoTraslado() == ID_PARAMETRO_MOTIVO_TRASLADO_VENTA,
                      attr: { id: 'input_SaldoPendienteGuiaRemision_' + IdDetalleGuiaRemisionRemitente(), 'data-cantidad-decimal': DecimalPendiente() },
                      numberdecimal: SaldoPendienteGuiaRemision,
                      event: { focus: $parent.OnFocus, keydown: $parent.OnKeyEnter }">
                    </div>
                  </td>                  
                  <td>
                    <div class="input-group ajuste-opcion-plusminus">
                      <button type="button" class="btn btn-danger btn-consulta glyphicon glyphicon-minus no-tab" data-bind="
                      visible: !UltimoItem(),                      
                      click : function(data,event) {  return OnClickBtnOpcion(data,event,$parent.OnQuitarFila); },                      
                      event : {
                         focus: function(data,event) { return OnFocus(data,event,$parent.OnRefrescar); } ,
                         keydown : function(data,event) { return OnKeyEnterOpcion(data,event,$parent.OnKeyEnter); }  },
                      attr : { id : IdDetalleGuiaRemisionRemitente() + '_a_opcion' }"></button>
                    </div>
                  </td>                  
                </tr>
                <!-- /ko -->
              </tbody>
            </table>
          </fieldset>
        </div>
        <div class="row">
          <div class="cold-md-12 text-right">
            Peso Bruto Total <span data-bind="text: PesoBrutoTotal"></span> (KGM)
          </div>
        </div>
        <div class="row">
          <div class="col-md-2">
            <br>
            <strong class="alert-info">* Grabar = ALT + G</strong>
          </div>
          <div class="col-md-8 text-center">
            <br>
            <button type="button" id="Grabar" class="btn btn-success focus-control" data-bind="event: { click: Guardar }">Grabar</button> &nbsp;
            <button type="button" id="Limpiar" class="btn btn-default focus-control" data-bind="event: { click: Limpiar }, visible : opcionProceso() == 1">Limpiar</button> &nbsp;
            <button type="button" id="Deshacer" class="btn btn-default" data-bind="event: { click: Deshacer }, visible : opcionProceso() == 2">Deshacer</button> &nbsp;
            <button type="button" id="Cerrar" class="btn btn-default" data-bind="event: { click : OnClickBtnCerrar }, visible :  opcionProceso() == 2">Cerrar</button>
          </div>
          <div class="col-md-2">
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- /ko -->