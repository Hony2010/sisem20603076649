<!-- ko with : vmrReporteMovimientoAlmacenValorado.dataReporteMovimientoAlmacenValorado -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">

          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte de Movimiento Kardex Físico - Valorado</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                      <!-- ko with : Buscador-->
                      <form class="form products-new" enctype="multipart/form-data" id="form_MovimientoAlmacenValorado" name="form" action="" method="post">
                        <div class="container-fluid">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="input-group">
                                      <div class="input-group-addon formulario">Almacén</div>
                                      <select id="Alamacen_MovimientoAlmacenValorado" name="Alamacen_MovimientoAlmacenValorado" class="form-control formulario" data-bind="
                                              value : IdSede_MovimientoAlmacenValorado,
                                              options : VistaModeloReporteInventario.vmrReporteMovimientoAlmacenValorado.dataReporteMovimientoAlmacenValorado.Almacenes,
                                              optionsValue :'IdAsignacionSede',
                                              optionsText : 'NombreSede',
                                              optionsCaption : 'Todos' ">
                                      </select>
                                    </div>
                                  </div>
                                  <fieldset>
                                    <legend>Rango de Fecha</legend>
                                    <div class="col-md-12">
                                      <div class="row">
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <div class="input-group">
                                              <div class="input-group-addon">Del</div>
                                              <input id="FechaInicio_MovimientoAlmacenValorado" name="FechaInicio_MovimientoAlmacenValorado" class="form-control formulario fecha-reporte" type="text"
                                              data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                              data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicio_MovimientoAlmacenValorado, event:{focusout : ValidarFecha}">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <div class="input-group ">
                                              <div class="input-group-addon">Al</div>
                                              <input id="FechaFin_MovimientoAlmacenValorado" name="FechaFin_MovimientoAlmacenValorado" class="form-control formulario fecha-reporte" type="text"
                                              data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                              data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFin_MovimientoAlmacenValorado, event:{focusout : ValidarFecha}">
                                            </div>
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
                                                <input class="radiobtn" type="radio" name="Producto_MovimientoAlmacenValorado" value="0" data-bind="checked:Producto_MovimientoAlmacenValorado, event:{change:GrupoProducto}">
                                              </div>
                                              <div class="form-group radiotxt">Todos</div>
                                            </label>
                                          </div>
                                        </div>
                                        <div class="col-md-8">
                                          <div class="form-group">
                                            <label class="input-group">
                                              <div class="input-group-addon addonrd">
                                                <input class="radiobtn" type="radio" name="Producto_MovimientoAlmacenValorado" value="1" data-bind="checked:Producto_MovimientoAlmacenValorado, event:{change:GrupoProducto}">
                                              </div>
                                              <div  id="DivBuscar_MovimientoAlmacenValorado" class="form-group radiotxt">Buscar</div>
                                              <input id="IdProducto_MovimientoAlmacenValorado" class="form-control formulario " name="IdProducto_MovimientoAlmacenValorado" type="hidden" placeholder="escribir para buscar...." >
                                              <input id="TextoBuscar_MovimientoAlmacenValorado" class="form-control formulario " name="TextoBuscar_MovimientoAlmacenValorado" type="text" placeholder="escribir para buscar...." >
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
                                                  <input id="SelectorTipoDocumentos_MovimientoAlmacenValorado" type="checkbox" data-bind="event: { change: SeleccionarTodosComprobantes }" />
                                                  <label for="SelectorTipoDocumentos_MovimientoAlmacenValorado" class="checkbox"> Seleccionar Todos</label>
                                                </div>
                                              </li>
                                              <!-- ko foreach: TiposDocumentoVenta -->
                                              <li>
                                                <div class="checkbox">
                                                  <input type="checkbox" data-bind="attr : { id: CodigoTipoDocumento() +'_TipoDocumento_MovimientoAlmacenValorado' }, event: {change: $parent.SeleccionarComprobante}" />
                                                  <label class="checkbox" data-bind="text: NombreTipoDocumento, attr:{ for : CodigoTipoDocumento() +'_TipoDocumento_MovimientoAlmacenValorado'}"></label>
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
                                        <button id="btnexcel_MovimientoAlmacenValorado" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                        <button id="btnpdf_MovimientoAlmacenValorado" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                        <button id="btnpantalla_MovimientoAlmacenValorado" name="pantalla"type="button" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
