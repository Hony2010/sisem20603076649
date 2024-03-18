<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog"  id="modalServicio">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bind="event:{click : $root.Cerrar}"><span aria-hidden="true">&times;</span></button>
                <h4 class="panel-title"><span data-bind="text: $root.MostrarTitulo()"></span></h4>
            </div>
            <div class="modal-body">
              <!-- ko with : $root.data.Servicio  -->
              <form class="form products-new" enctype="multipart/form-data" id="form" name="form" action="" method="post">
                <div class="container-fluid">
                  <input type="hidden" name="IdProducto" id="IdProducto" data-bind="value : IdProducto">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">Código <strong class="alert-info">(*)</strong></div>
                            <input id="CodigoServicio" class="form-control formulario" type="text" data-bind="value: CodigoServicio, event{ focus : OnFocus, keydown : OnKeyEnter}">
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="checkbox">
                              <input name="CheckNumeroDocumento" id="CheckCodigoServicio" type="checkbox" class="form-control formulario" data-bind="checked : CodigoAutomatico, event: { change : OnChangeCheckCodigo , focus : OnFocus , keydown : OnKeyEnter}">
                              <label for="CheckNumeroDocumento">Editar</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon  formulario"> Tipo Servicio</div>
                            <select id="combo-tiposervicio" class="form-control  formulario" data-bind="
                                    value : IdTipoServicio,
                                    options : $root.data.TiposServicio,
                                    optionsValue : 'IdTipoServicio',
                                    optionsText : 'NombreTipoServicio',
                                    event{ focus : OnFocus, keydown : OnKeyEnter}">
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">Nombre <strong class="alert-info">(*)</strong></div>
                            <input id="NombreProducto" class="form-control formulario" type="text"  data-bind="value: NombreProducto, event{ focus : OnFocus, keydown : OnKeyEnter}">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">Familia</div>
                            <select id="combo-familia" class="form-control formulario" data-bind="value : IdFamiliaProducto, event: { change : $root.OnChangeFamilia, focus : OnFocus, keydown : OnKeyEnter}">
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">Sub Familia</div>
                            <select id="combo-subfamiliaproducto" class="form-control formulario" data-bind="event:{ change: $root.OnChangeSubFamilia, focus : OnFocus, keydown : OnKeyEnter}">
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">Línea Producto</div>
                            <select id="combo-lineaproducto" class="form-control  formulario" data-bind="
                                    value : IdLineaProducto,
                                    options : $root.data.LineasProducto,
                                    optionsValue : 'IdLineaProducto',
                                    optionsText : 'NombreLineaProducto',
                                    event{ focus : OnFocus, keydown : OnKeyEnter}">
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <div class="input-group">
                            <div class="input-group-addon formulario">Precio</div>
                            <input id="PrecioUnitario" class="form-control formulario" type="text"  data-bind="value: PrecioUnitario, event{ focus : OnFocus, keydown : OnKeyEnter}">
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
                          <label for="IndicadorEstadoProducto">Estado Servicio</label>
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
                  <div class="row">
                    <div class="col-md-12">
                      <center>
                        <br>
                        <div class="form-group">
                          <div class="">
                            <a data-toogle="modal" data-bind="click : AbrirPreview"><!--  data-bind="click : AbrirPreview"  -->
                              <img src="" width="110" height="110" class="img-rounded foto" id="img_FileFoto">
                            </a>
                            <input type="hidden" id="InputFileName" name="InputFileName" value="FileFoto">
                          </div>
                          <div tabindex="500" class="btn btn-default btn-file">
                            <span class="hidden-xs glyphicon glyphicon-folder-open"></span> &nbsp <label> Foto</label>
                            <input class="formulario" type="file" id="FileFoto" name="FileFoto" data-bind="event : { change : OnChangeInputFile }"
                            /><!-- data-bind="event : { change : OnChangeInputFile }" -->
                          </div>
                        </div>
                        </center>
                    </div>
                  </div>
                  <div class="row">
                    <center>
                      <button id="btn_Grabar" type="button" class="btn btn-success focus-control"  data-bind="click : $root.Guardar">Grabar</button> &nbsp;
                      <button id="btn_Limpiar" type="button" class="btn btn-default focus-control"  data-bind="click : $root.Deshacer">Deshacer</button> &nbsp;
                      <button id="btn_Cerrar" type="button" class="btn btn-default focus-control"  data-bind="event:{click : $root.Cerrar}">Cerrar</button>
                      
                    </center>
                    <div class="col-md-12">
                      <strong class="alert-info">* Grabar = ALT + G</strong>
                    </div>
                  </div>
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
