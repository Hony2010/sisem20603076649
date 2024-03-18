<!-- ko with : vmrReporteStockProductoMarca.dataReporteStockProductoMarca -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">
          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte de Stock de Producto por Marca</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Buscador-->
                  <form class="form products-new" enctype="multipart/form-data" id="form_StockProductoMarca" name="form" action="" method="post">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              <fieldset>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon formulario">Almacén</div>
                                          <select id="Almacen_StockProductoMarca" class="form-control formulario" data-bind="
                                                  options : Almacenes,
                                                  optionsValue :'IdAsignacionSede',
                                                  optionsText : 'NombreSede',
                                                  optionsCaption : 'Todos' ">
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon formulario">Marca</div>
                                          <select id="Marca_StockProductoMarca" class="form-control formulario" data-bind="
                                                  options : Marcas,
                                                  optionsValue :'IdMarca',
                                                  optionsText : 'NombreMarca',
                                                  optionsCaption : 'Todos' ">
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend>Fecha</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <label style="margin: 0px;">
                                              <input class="radiobtn input-checkbox" type="radio" name="Fecha_StockProductoMarca" value="0" data-bind="checked:Fecha_StockProductoMarca, event:{change:OnChangeFecha}">
                                              <div class="label-checkbox" style="display:contents;">A fecha actual </div>
                                            </label>
                                          </div>
                                          <input id="FechaHoy_StockProductoMarca"  disabled name="FechaHoy_StockProductoMarca" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaHoy_StockProductoMarca, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group ">
                                          <div class="input-group-addon">
                                            <label style="margin: 0px;">
                                              <input class="radiobtn input-checkbox" type="radio" name="Fecha_StockProductoMarca" value="1" data-bind="checked:Fecha_StockProductoMarca, event:{change:OnChangeFecha}">
                                              <div class="label-checkbox" style="display:contents;">A fecha determinada</div>
                                            </label>
                                          </div>
                                          <input disabled id="FechaDeterminada_StockProductoMarca" name="FechaDeterminada_StockProductoMarca" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaDeterminada_StockProductoMarca, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend>Ordenado por</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Orden_StockProductoMarca" value="0" data-bind="checked:Orden_StockProductoMarca">
                                          </div>
                                          <div class="form-group radiotxt">Stock mayor a menor</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Orden_StockProductoMarca" value="1" data-bind="checked:Orden_StockProductoMarca">
                                          </div>
                                          <div class="form-group radiotxt">Descripción Mecadería</div>
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend>Mercadería</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Producto_StockProductoMarca" value="0" data-bind="checked:Producto_StockProductoMarca, event:{change:GrupoProducto}">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Producto_StockProductoMarca" value="1" data-bind="checked:Producto_StockProductoMarca, event:{change:GrupoProducto}">
                                          </div>
                                          <div  id="DivBuscar_StockProductoMarca" class="form-group radiotxt">Buscar</div>
                                          <input id="TextoBuscar_StockProductoMarca" class="form-control formulario " name="TextoBuscar_StockProductoMarca" type="text" placeholder="escribir para buscar...." >
                                          <input id="IdProducto_StockProductoMarca" class="form-control formulario " type="hidden" >
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend>Tipos de Documento</legend>
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="input-group">
                                      <div class="multiselect-native-select formulario">
                                        <button type="button" class="multiselect dropdown-toggle btn btn-default btn-control" data-toggle="dropdown">
                                          <span class="multiselect-selected-text">DOCUMENTOS </span>
                                          <span class="badge" data-bind="text: NumeroDocumentosSeleccionados"></span>
                                          <b style="float: right;margin: 5px;" class="caret"></b>
                                        </button>
                                        <ul class="multiselect-container dropdown-menu">
                                          <li>
                                            <div class="checkbox">
                                              <input id="SelectorTipoDocumentos_StockProductoMarca" type="checkbox" data-bind="event: { change: SeleccionarTodosComprobantes }" />
                                              <label for="SelectorTipoDocumentos_StockProductoMarca" class="checkbox"> Seleccionar Todos</label>
                                            </div>
                                          </li>
                                          <!-- ko foreach: TiposDocumentoVenta -->
                                          <li>
                                            <div class="checkbox">
                                              <input type="checkbox" data-bind="attr : { id: IdTipoDocumento() +'_TipoDocumento_StockProductoMarca' }, event: {change: $parent.SeleccionarComprobante}" />
                                              <label class="checkbox" data-bind="text: NombreTipoDocumento, attr:{ for : IdTipoDocumento() +'_TipoDocumento_StockProductoMarca'}"></label>
                                            </div>
                                          </li>
                                          <!-- /ko -->
                                        </ul>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <div class="col-md-12">
                                <div class="row">
                                  <center>
                                    <hr>
                                    <!-- <br> -->
                                    <button id="btnexcel_StockProductoMarca" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                    <button id="btnpdf_StockProductoMarca" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_StockProductoMarca" type="button" name="pantalla" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
                                  </center>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                  <!-- /ko -->
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
<!-- /ko -->
