<!-- ko with : vmrReporteMovimientoAlmacenDocumentoIngreso.dataReporteMovimientoAlmacenDocumentoIngreso -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">

          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte de Movimiento Kardex Físico con Documento Ingreso / Control</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                      <!-- ko with : Buscador-->
                      <form class="form products-new" enctype="multipart/form-data" id="form_MovimientoAlmacenDocumentoIngreso" name="form" action="" method="post">
                        <div class="container-fluid">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="input-group">
                                      <div class="input-group-addon formulario">Almacén</div>
                                      <select id="Alamacen_MovimientoAlmacenDocumentoIngreso" name="Alamacen_MovimientoAlmacenDocumentoIngreso" class="form-control formulario" data-bind="
                                              value : IdSede_MovimientoAlmacenDocumentoIngreso,
                                              options : VistaModeloReporteInventario.vmrReporteMovimientoAlmacenDocumentoIngreso.dataReporteMovimientoAlmacenDocumentoIngreso.Almacenes,
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
                                              <input id="FechaInicio_MovimientoAlmacenDocumentoIngreso" name="FechaInicio_MovimientoAlmacenDocumentoIngreso" class="form-control formulario fecha-reporte" type="text"
                                              data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                              data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicio_MovimientoAlmacenDocumentoIngreso, event:{focusout : ValidarFecha}">
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <div class="input-group ">
                                              <div class="input-group-addon">Al</div>
                                              <input id="FechaFin_MovimientoAlmacenDocumentoIngreso" name="FechaFin_MovimientoAlmacenDocumentoIngreso" class="form-control formulario fecha-reporte" type="text"
                                              data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                              data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFin_MovimientoAlmacenDocumentoIngreso, event:{focusout : ValidarFecha}">
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
                                                <input class="radiobtn" type="radio" name="Producto_MovimientoAlmacenDocumentoIngreso" value="0" data-bind="checked:Producto_MovimientoAlmacenDocumentoIngreso, event:{change:GrupoProducto}">
                                              </div>
                                              <div class="form-group radiotxt">Todos</div>
                                            </label>
                                          </div>
                                        </div>
                                        <div class="col-md-8">
                                          <div class="form-group">
                                            <label class="input-group">
                                              <div class="input-group-addon addonrd">
                                                <input class="radiobtn" type="radio" name="Producto_MovimientoAlmacenDocumentoIngreso" value="1" data-bind="checked:Producto_MovimientoAlmacenDocumentoIngreso, event:{change:GrupoProducto}">
                                              </div>
                                              <div  id="DivBuscar_MovimientoAlmacenDocumentoIngreso" class="form-group radiotxt">Buscar</div>
                                              <input id="IdProducto_MovimientoAlmacenDocumentoIngreso" class="form-control formulario " name="IdProducto_MovimientoAlmacenDocumentoIngreso" type="hidden" placeholder="escribir para buscar...." >
                                              <input id="TextoBuscar_MovimientoAlmacenDocumentoIngreso" class="form-control formulario " name="TextoBuscar_MovimientoAlmacenDocumentoIngreso" type="text" placeholder="escribir para buscar...." >
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
                                        <button id="btnexcel_MovimientoAlmacenDocumentoIngreso" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                        <button id="btnpdf_MovimientoAlmacenDocumentoIngreso" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                        <button id="btnpantalla_MovimientoAlmacenDocumentoIngreso" type="button" name="pantalla" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
