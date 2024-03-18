<!-- ko with : vmrReporteMovimientoDocumentoZofra.dataReporteMovimientoDocumentoZofra -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">
          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte de Movimiento Documento por Zofra</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Buscador-->
                  <form class="form products-new" enctype="multipart/form-data" id="form_MovimientoDocumentoZofra" name="form" action="" method="post">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon formulario">Almacén</div>
                                  <select id="Almacen_MovimientoDocumentoZofra" class="form-control formulario" data-bind="
                                  options : Almacenes,
                                  optionsValue :'IdAsignacionSede',
                                  optionsText : 'NombreSede',
                                  optionsCaption : 'Todos'">
                                </select>
                              </div>
                            </div>
                              <fieldset>
                                <legend>Seleccionar Zofra</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon">Del</div>
                                          <input id="FechaInicioDocumentoSalidaZofra_MovimientoDocumentoZofra" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicioDocumentoSalidaZofra_MovimientoDocumentoZofra, event:{focusout : ValidarFecha, change : OnChangeFiltroDocumentoSalidaZofra}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group ">
                                          <div class="input-group-addon">Al</div>
                                          <input id="FechaFinDocumentoSalidaZofra_MovimientoDocumentoZofra" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFinDocumentoSalidaZofra_MovimientoDocumentoZofra, event:{focusout : ValidarFecha, change : OnChangeFiltroDocumentoSalidaZofra}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" value="0" data-bind="checked:DocumentoSalidaZofra_MovimientoDocumentoZofra, event:{change:GrupoDocumentoSalidaZofra}">
                                          </div>
                                          <div class="form-group radiotxt">Todas las Zofras</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" value="1" data-bind="checked:DocumentoSalidaZofra_MovimientoDocumentoZofra, event:{change:GrupoDocumentoSalidaZofra}">
                                          </div>
                                          <div  id="DivDocumentoSalidaZofra_MovimientoDocumentoZofra" class="form-group radiotxt">Buscar Zofra específica</div>
                                          <input id="Item_MovimientoDocumentoZofra" class="form-control formulario " name="TextoBuscar_MovimientoDocumentoZofra" type="text" placeholder="escribir para buscar...." >
                                          <input id="IdDocumentoSalidaZofra_MovimientoDocumentoZofra" class="form-control formulario " type="hidden" >
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend>Fecha de Movimiento</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <label style="margin: 0px;">
                                              <div class="label-checkbox" style="display:contents;">Del</div>
                                            </label>
                                          </div>
                                          <input id="FechaHoy_MovimientoDocumentoZofra" name="FechaHoy_MovimientoDocumentoZofra" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicio_MovimientoDocumentoZofra, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group ">
                                          <div class="input-group-addon">
                                            <label style="margin: 0px;">
                                              <div class="label-checkbox" style="display:contents;">Al</div>
                                            </label>
                                          </div>
                                          <input id="FechaDeterminada_MovimientoDocumentoZofra" name="FechaDeterminada_MovimientoDocumentoZofra" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFin_MovimientoDocumentoZofra, event:{focusout : ValidarFecha}">
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
                                            <input class="radiobtn" type="radio" name="Producto_MovimientoDocumentoZofra" value="0" data-bind="checked:Producto_MovimientoDocumentoZofra, event:{change:GrupoProducto}">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Producto_MovimientoDocumentoZofra" value="1" data-bind="checked:Producto_MovimientoDocumentoZofra, event:{change:GrupoProducto}">
                                          </div>
                                          <div  id="DivBuscar_MovimientoDocumentoZofra" class="form-group radiotxt">Buscar</div>
                                          <input id="TextoBuscar_MovimientoDocumentoZofra" class="form-control formulario " name="TextoBuscar_MovimientoDocumentoZofra" type="text" placeholder="escribir para buscar...." >
                                          <input id="IdProducto_MovimientoDocumentoZofra" class="form-control formulario " type="hidden" >
                                        </label>
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
                                    <button id="btnexcel_MovimientoDocumentoZofra" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                    <button id="btnpdf_MovimientoDocumentoZofra" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_MovimientoDocumentoZofra" type="button" name="pantalla" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
