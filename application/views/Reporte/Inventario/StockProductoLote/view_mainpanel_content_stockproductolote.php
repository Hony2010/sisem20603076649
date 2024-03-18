<!-- ko with : vmrReporteStockProductoLote.dataReporteStockProductoLote -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">
          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte de Stock de Producto Lote</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Buscador-->
                  <form class="form products-new" enctype="multipart/form-data" id="form_StockProductoLote" name="form" action="" method="post">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon formulario">Almacén</div>
                                  <select id="combo-almacen" class="form-control formulario" data-bind="
                                          value : IdSede_StockProductoLote,
                                          options : VistaModeloReporteInventario.vmrReporteStockProductoLote.dataReporteStockProductoLote.Almacenes,
                                          optionsValue :'IdAsignacionSede',
                                          optionsText : 'NombreSede',
                                          optionsCaption : 'Todos' ">
                                  </select>
                                </div>
                              </div>
                              <fieldset>
                                <legend>Fecha</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <label style="margin: 0px;">
                                              <input class="radiobtn input-checkbox" type="radio" name="Fecha_StockProductoLote" value="0" data-bind="checked:Fecha_StockProductoLote, event:{change:OnChangeFecha}">
                                              <div class="label-checkbox" style="display:contents;">A fecha actual </div>
                                            </label>
                                          </div>
                                          <input id="FechaHoy_StockProductoLote"  disabled name="FechaHoy_StockProductoLote" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaHoy_StockProductoLote, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group ">
                                          <div class="input-group-addon">
                                            <label style="margin: 0px;">
                                              <input class="radiobtn input-checkbox" type="radio" name="Fecha_StockProductoLote" value="1" data-bind="checked:Fecha_StockProductoLote, event:{change:OnChangeFecha}">
                                              <div class="label-checkbox" style="display:contents;">A fecha determinada</div>
                                            </label>
                                          </div>
                                          <input disabled id="FechaDeterminada_StockProductoLote" name="FechaDeterminada_StockProductoLote" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaDeterminada_StockProductoLote, event:{focusout : ValidarFecha}">
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
                                            <input class="radiobtn" type="radio" name="Orden_StockProductoLote" value="0" data-bind="checked:Orden_StockProductoLote">
                                          </div>
                                          <div class="form-group radiotxt">Stock mayor a menor</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Orden_StockProductoLote" value="1" data-bind="checked:Orden_StockProductoLote">
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
                                            <input class="radiobtn" type="radio" name="Producto_StockProductoLote" value="0" data-bind="checked:Producto_StockProductoLote, event:{change:GrupoProducto}">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Producto_StockProductoLote" value="1" data-bind="checked:Producto_StockProductoLote, event:{change:GrupoProducto}">
                                          </div>
                                          <div  id="DivBuscar_StockProductoLote" class="form-group radiotxt">Buscar</div>
                                          <input id="TextoBuscar_StockProductoLote" class="form-control formulario " name="TextoBuscar_StockProductoLote" type="text" placeholder="escribir para buscar...." >
                                          <input id="IdProducto_StockProductoLote" class="form-control formulario " type="hidden" >
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
                                              <input id="SelectorTipoDocumentos_StockProductoLote" type="checkbox" data-bind="event: { change: SeleccionarTodosComprobantes }" />
                                              <label for="SelectorTipoDocumentos_StockProductoLote" class="checkbox"> Seleccionar Todos</label>
                                            </div>
                                          </li>
                                          <!-- ko foreach: TiposDocumentoVenta -->
                                          <li>
                                            <div class="checkbox">
                                              <input type="checkbox" data-bind="attr : { id: IdTipoDocumento() +'_TipoDocumento_StockProductoLote' }, event: {change: $parent.SeleccionarComprobante}" />
                                              <label class="checkbox" data-bind="text: NombreTipoDocumento, attr:{ for : IdTipoDocumento() +'_TipoDocumento_StockProductoLote'}"></label>
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
                                    <button id="btnexcel_StockProductoLote" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                    <button id="btnpdf_StockProductoLote" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_StockProductoLote" type="button" name="pantalla" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
