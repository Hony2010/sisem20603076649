<!-- ko with : vmrReporteMovimientoMercaderia.dataReporteMovimientoMercaderia -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">

          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte de Movimiento Kardex Físico</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                      <!-- ko with : Buscador-->
                      <form class="form products-new" enctype="multipart/form-data" id="form_MovimientoMercaderia" name="form" action="" method="post">
                        <div class="container-fluid">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="input-group">
                                      <div class="input-group-addon formulario">Almacén</div>
                                      <select id="Alamacen_MovimientoMercaderia" name="Alamacen_MovimientoMercaderia" class="form-control formulario" data-bind="
                                              value : IdSede_MovimientoMercaderia,
                                              options : VistaModeloReporteInventario.vmrReporteMovimientoMercaderia.dataReporteMovimientoMercaderia.Almacenes,
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
                                              <input id="FechaInicio_MovimientoMercaderia" name="FechaInicio_MovimientoMercaderia" class="form-control formulario fecha-reporte" type="text"
                                              data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                              data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicio_MovimientoMercaderia, event:{focusout : ValidarFecha}">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <div class="input-group ">
                                              <div class="input-group-addon">Al</div>
                                              <input id="FechaFin_MovimientoMercaderia" name="FechaFin_MovimientoMercaderia" class="form-control formulario fecha-reporte" type="text"
                                              data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                              data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFin_MovimientoMercaderia, event:{focusout : ValidarFecha}">
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
                                                <input class="radiobtn" type="radio" name="Producto_MovimientoMercaderia" value="0" data-bind="checked:Producto_MovimientoMercaderia, event:{change:GrupoProducto}">
                                              </div>
                                              <div class="form-group radiotxt">Todos</div>
                                            </label>
                                          </div>
                                        </div>
                                        <div class="col-md-8">
                                          <div class="form-group">
                                            <label class="input-group">
                                              <div class="input-group-addon addonrd">
                                                <input class="radiobtn" type="radio" name="Producto_MovimientoMercaderia" value="1" data-bind="checked:Producto_MovimientoMercaderia, event:{change:GrupoProducto}">
                                              </div>
                                              <div  id="DivBuscar_MovimientoMercaderia" class="form-group radiotxt">Buscar</div>
                                              <input id="IdProducto_MovimientoMercaderia" class="form-control formulario " name="IdProducto_MovimientoMercaderia" type="hidden" placeholder="escribir para buscar...." >
                                              <input id="TextoBuscar_MovimientoMercaderia" class="form-control formulario " name="TextoBuscar_MovimientoMercaderia" type="text" placeholder="escribir para buscar...." >
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
                                                  <input id="SelectorTipoDocumentos_MovimientoMercaderia" type="checkbox" data-bind="event: { change: SeleccionarTodosComprobantes }" />
                                                  <label for="SelectorTipoDocumentos_MovimientoMercaderia" class="checkbox"> Seleccionar Todos</label>
                                                </div>
                                              </li>
                                              <!-- ko foreach: TiposDocumentoVenta -->
                                              <li>
                                                <div class="checkbox">
                                                  <input type="checkbox" data-bind="attr : { id: CodigoTipoDocumento() +'_TipoDocumento_MovimientoMercaderia' }, event: {change: $parent.SeleccionarComprobante}" />
                                                  <label class="checkbox" data-bind="text: NombreTipoDocumento, attr:{ for : CodigoTipoDocumento() +'_TipoDocumento_MovimientoMercaderia'}"></label>
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
                                        <button id="btnexcel_MovimientoMercaderia" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                        <button id="btnpdf_MovimientoMercaderia" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                        <button id="btnpantalla_MovimientoMercaderia" type="button" name="pantalla" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
