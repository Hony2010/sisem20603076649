<div class="modal fade bd-example-modal-lg" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalInventarioInicial">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="event:{click: $root.Cerrar}"><span aria-hidden="true">&times;</span></button>
                <h4 class="panel-title"><span data-bind="text: $root.MostrarTitulo()"></span></h4>
            </div>
            <div class="modal-body">
              <!-- ko with : InventarioInicial  -->
              <form class="form products-new" enctype="multipart/form-data" id="formInventarioInicial" name="formInventarioInicial" action="" method="post" role="form" autocomplete="off">
                <div class="container-fluid">
                  <input type="hidden" class="no-tab" name="IdProducto" id="IdProducto" data-bind="value : IdProducto">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Almacen</div>
                              <input disabled class="form-control formulario no-tab" type="text" data-bind="value: NombreSede, event : {focus : OnFocus, keydown : OnKeyEnter}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Tipo Existencia</div>
                              <select disabled id="combo-unidadmedida" class="form-control formulario no-tab" data-bind="
                                      value : IdTipoExistencia,
                                      options : $root.data.TiposExistencia,
                                      optionsValue : 'IdTipoExistencia' ,
                                      optionsText : 'NombreTipoExistencia',
                                      event : {focus : OnFocus, keydown : OnKeyEnter} " >
                              </select>
                              <!-- <input disabled class="form-control formulario no-tab" type="text" data-bind="value: NombreTipoExistencia, event : {focus : OnFocus, keydown : OnKeyEnter}"> -->
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Fecha Inventario</div>
                              <input disabled id="FechaInventario" class="form-control formulario no-tab" type="text" data-bind="value: FechaInicial, event : {focus : OnFocus, keydown : OnKeyEnter}"
                              data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Motivo Inventario</div>
                              <select id="combo-motivoinventario" class="form-control formulario" data-bind="
                                      value : IdMotivoInventarioInicial,
                                      options : $root.data.MotivosInventario,
                                      optionsValue : 'IdMotivoInventarioInicial' ,
                                      optionsText : 'NombreMotivoInventarioInicial',
                                      event : {focus : OnFocus, keydown : OnKeyEnter}">
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                          <div id="GrupoDocumentosInventario" class="col-md-12">
                            <div class="form-group">
                              <div class="radio radio-inline">
                                <input disabled id="OrigenMercaderiaGeneral" class="no-tab" type="radio" name="radio2" value="1" data-bind="checked : IdOrigenMercaderia,event : { change : OnChangeIdOrigenMercaderia }">
                                <label for="OrigenMercaderiaGeneral">General</label>
                              </div>
                              <!-- ko if: $root.data.ParametroDua() != 0 -->
                              <div class="radio radio-inline">
                                <input disabled id="OrigenMercaderiaDua" class="no-tab" type="radio" name="radio2" value="2" data-bind="checked : IdOrigenMercaderia,event : { change : OnChangeIdOrigenMercaderia }">
                                <label for="OrigenMercaderiaDua">DUA</label>
                              </div>
                              <!-- /ko -->
                              <!-- ko if: $root.data.ParametroDocumentoSalidaZofra() != 0 -->
                              <div class="radio radio-inline">
                                <input disabled id="OrigenMercaderiaZofra" class="no-tab" type="radio" name="radio2" value="3" data-bind="checked : IdOrigenMercaderia,event : { change : OnChangeIdOrigenMercaderia }">
                                <label for="OrigenMercaderiaZofra">Documento Salida Zofra</label>
                              </div>
                              <!-- /ko -->
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Codigo</div>
                              <input disabled id="CodigoMercaderiaInventario" class="form-control formulario" type="text" data-bind="value: CodigoMercaderia, event : {focus : OnFocus, keydown : OnKeyEnter, keydown : function(data,event) { return OnKeyEnterCodigoMercaderia(data,event); }}"
                              data-validation="validacion_producto" data-validation-error-msg="Cod. Inválido"
                              data-validation-found="false"  data-validation-text-found="">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Unidad de Medida</div>
                              <select disabled id="combo-unidadmedida" class="form-control formulario no-tab" data-bind="
                              value : IdUnidadMedida,
                              options : $root.data.UnidadesMedida,
                              optionsValue : 'IdUnidadMedida' ,
                              optionsText : 'NombreUnidadMedida',
                              event : {focus : OnFocus, keydown : OnKeyEnter} " >
                            </select>
                          </div>
                        </div>
                      </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Descrip. Producto</div>
                              <input disabled id="NombreProducto" class="form-control formulario" type="text" data-bind="value: NombreProducto, event : {focus : OnFocus, keydown : OnKeyEnter}"
                              data-validation-reference="CodigoMercaderiaInventario" data-validation="autocompletado_producto" data-validation-error-msg="No se han encontrado resultados para tu búsqueda de producto">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Cantidad</div>
                              <input id="CantidadInicial" class="form-control formulario" type="text" data-bind="value: CantidadInicial, event : {focus : OnFocus, keydown : OnKeyEnter}, numbertrim : CantidadInicial"
                              data-validation="number_desc" data-validation-error-msg="Ingrese un valor">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Valor Unitario</div>
                              <input id="Descripcion" class="form-control formulario" type="text" data-bind="value: ValorUnitario, event : {focus : OnFocus, keydown : OnKeyEnter}, numbertrim : ValorUnitario"
                              data-validation="number_desc" data-validation-error-msg="Ingrese un valor">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <!-- ko if: $root.data.ParametroLote() != 0 -->
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Numero Lote</div>
                              <input id="NumeroLote" class="form-control formulario" type="text" data-bind="value: NumeroLote, event : {focus : OnFocus, keydown : OnKeyEnter}"
                              data-validation="required" data-validation-error-msg="Este campo es requerido">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Fecha Vencimiento</div>
                              <input id="FechaVencimiento" class="form-control formulario" type="text" data-bind="value: FechaVencimiento, event : {focus : OnFocus, keydown : OnKeyEnter}"
                              data-inputmask-clearmaskonlostfocus="false" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida">
                            </div>
                          </div>
                        </div>
                        <!-- /ko -->
                        <!-- ko if: $root.data.ParametroDocumentoSalidaZofra() == 1 && IdOrigenMercaderia() == ORIGEN_MERCADERIA.ZOFRA -->
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Documento Zofra</div>
                              <input id="NumeroDocumentoSalidaZofra" class="form-control formulario" type="text" data-bind="value: NumeroDocumentoSalidaZofra, event : {focus : OnFocus, keydown : OnKeyEnter}"
                              data-validation="required" data-validation-error-msg="Este campo es requerido">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Fecha Documento Zofra</div>
                              <input id="FechaEmisionDocumentoSalidaZofra" class="form-control formulario" type="text" data-bind="value: FechaEmisionDocumentoSalidaZofra, event : {focus : OnFocus, keydown : OnKeyEnter}"
                              data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida">
                            </div>
                          </div>
                        </div>
                        <!-- /ko -->
                        <!-- ko if: $root.data.ParametroDua() == 1 && IdOrigenMercaderia() == ORIGEN_MERCADERIA.DUA -->
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Numero Dua</div>
                              <input id="NumeroDua" class="form-control formulario" type="text" data-bind="value: NumeroDua, event : {focus : OnFocus, keydown : OnKeyEnter}"
                              data-validation="required" data-validation-error-msg="Este campo es requerido">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Fecha Dua</div>
                              <input id="FechaEmisionDua" class="form-control formulario" type="text" data-bind="value: FechaEmisionDua, event : {focus : OnFocus, keydown : OnKeyEnter}"
                              data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Numero Item</div>
                              <input id="NumeroItemDua" class="form-control formulario" type="text" data-bind="value: NumeroItemDua, event : {focus : OnFocus, keydown : OnKeyEnter}"
                              data-validation="number" data-validation-error-msg="Este campo es numerico">
                            </div>
                          </div>
                        </div>
                        <!-- /ko -->
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Observacion</div>
                              <input id="Observacion" class="form-control formulario" type="text" data-bind="value: Observacion, event : {focus : OnFocus, keydown : OnKeyEnter}">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <center>
                      <button id="btn_Grabar" type="button" class="btn btn-success focus-control" tabindex="-1" data-bind="click : $root.PreGuardar">Grabar</button> &nbsp;
                      <button id="btn_Limpiar" type="button" class="btn btn-default focus-control no-tab" tabindex="-1" data-bind="click : $root.Deshacer">Deshacer</button> &nbsp;
                      <button id="btn_Cerrar" type="button" class="btn btn-default focus-control no-tab" tabindex="-1" data-bind="click : $root.Cerrar">Cerrar</button>
                    </center>
                  </div>
                  <!-- <div class="row">
                    <div class="col-md-12">
                      <strong class="alert-info">* Grabar = ALT + G</strong>
                    </div>
                  </div> -->
                </div>

              </form>
              <!-- /ko -->
        <!--<center>
            <img src="" width="60%" height="60%" id="foto_previa" name="foto_previa">
        </center>-->
      </div>
    </div>
  </div>
</div>
