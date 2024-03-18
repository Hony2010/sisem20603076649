<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalActivoFijo">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="event:{click:$root.Cerrar}"><span aria-hidden="true">&times;</span></button>
                <h4 class="panel-title"><span data-bind="text: $root.MostrarTitulo()">REGISTRO DE ACTIVO FIJO</span></h4>
            </div>
            <div class="modal-body">
              <!-- ko with : $root.data.ActivoFijo  -->
              <form class="form products-new" enctype="multipart/form-data" id="form" name="form" action="" method="post">
                <div class="container-fluid">
                  <input type="hidden" name="IdProducto" id="IdProducto" data-bind="value : IdProducto">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Código <strong class="alert-info">(*)</strong></div>
                              <input id="CodigoActivoFijo" class="form-control formulario" type="text" data-bind="value: CodigoActivoFijo, event : { focus : OnFocus, keydown : OnKeyEnter}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Nº Serie</div>
                              <input id="NumeroSerie" class="form-control formulario" type="text" data-bind="value: NumeroSerie, event : { focus : OnFocus, keydown : OnKeyEnter}">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Nombre <strong class="alert-info">(*)</strong> </div>
                              <input id="NombreProducto" class="form-control formulario" type="text" data-bind="value: NombreProducto, event : { focus : OnFocus, keydown : OnKeyEnter}">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Placa</div>
                              <input id="Placa" class="form-control formulario" type="text" data-bind="value: Placa, event : { focus : OnFocus, keydown : OnKeyEnter}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Año</div>
                              <input id="Ano" class="form-control formulario" type="text"data-bind="value: Ano, event : { focus : OnFocus, keydown : OnKeyEnter}">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Color</div>
                              <input id="Color" class="form-control formulario" type="text" data-bind="value: Color, event : { focus : OnFocus, keydown : OnKeyEnter}">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon formulario">Tipo Activo</div>

                              <select id="combo-tipoactivo" class="form-control formulario" data-bind="
                                      value : IdTipoActivo,
                                      options : $root.data.TiposActivo,
                                      optionsValue : 'IdTipoActivo',
                                      optionsText : 'NombreTipoActivo',
                                      event : { focus : OnFocus, keydown : OnKeyEnter} ">
                              </select>
                            </div>
                          </div>
                        </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <div class="input-group">
                                <div class="input-group-addon formulario">Marca</div>
                                <select id="combo-marca" class="form-control formulario" data-bind="value : IdMarca, event: { change : $root.OnChangeMarca, focus : OnFocus, keydown : OnKeyEnter}">
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <div class="input-group">
                                <div class="input-group-addon formulario">Modelo</div>
                                <select id="combo-modelo" class="form-control formulario " data-bind="event:{change: $root.OnChangeModelo, focus : OnFocus, keydown : OnKeyEnter}">
                                </select>
                              </div>
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <div class="radio radio-inline">
                              <input id="radioAfectoIGV" type="radio" name="radio" class="no-tab" value="1" data-bind="checked : IdTipoAfectacionIGV, event : { change : OnChangeTipoAfectacionIGV }">
                              <label for="radioAfectoIGV">Afecto IGV</label>
                            </div>
                            <div class="radio radio-inline">
                              <input id="radioExoneradoIGV" type="radio" name="radio"  class="no-tab" value="2" data-bind="checked : IdTipoAfectacionIGV, event : { change : OnChangeTipoAfectacionIGV }">
                              <label for="radioExoneradoIGV">Exonerado IGV</label>
                            </div>
                            <div class="radio radio-inline">
                              <input id="radioInafectoIGV" type="radio" name="radio"  class="no-tab" value="3" data-bind="checked : IdTipoAfectacionIGV, event : { change : OnChangeTipoAfectacionIGV }">
                              <label for="radioInafectoIGV">Inafecto IGV</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="checkbox checkbox-inline">
                            <input id="IndicadorEstadoProducto" type="checkbox" class="no-tab" data-bind="checked : IndicadorEstadoProducto">
                            <label for="IndicadorEstadoProducto">Estado Activo Fijo</label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <strong class="alert-info">(*) Campos Obligatorios</strong>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <center>
                      <br>
                      <button id="btn_Grabar" type="button" class="btn btn-success focus-control" data-bind="click : $root.Guardar">Grabar</button> &nbsp;
                      <button id="btn_Limpiar" type="button" class="btn btn-default focus-control" data-bind="click : $root.Deshacer">Deshacer</button> &nbsp;
                      <button id="btn_Cerrar" type="button" class="btn btn-default focus-control" data-bind="event:{click : $root.Cerrar}">Cerrar</button>
                    </center>
                    <div class="col-md-12">
                      <strong class="alert-info">* Grabar = ALT + G</strong>
                    </div>
                  </div>
                </div>
              </form>
              <!-- /ko -->
      </div>
    </div>
  </div>
</div>
