<!-- ko with : vmrReporteMovimientoDocumentoDua.dataReporteMovimientoDocumentoDua -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">
          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte de Movimiento Documento por DUA</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Buscador-->
                  <form class="form products-new" enctype="multipart/form-data" id="form_MovimientoDocumentoDua" name="form" action="" method="post">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon formulario">Almacén</div>
                                  <select id="Almacen_MovimientoDocumentoDua" class="form-control formulario" data-bind="
                                  options : Almacenes,
                                  optionsValue :'IdAsignacionSede',
                                  optionsText : 'NombreSede',
                                  optionsCaption : 'Todos'">
                                </select>
                              </div>
                            </div>
                              <fieldset>
                                <legend>Seleccionar DUA</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon">Del</div>
                                          <input id="FechaInicioDua_MovimientoDocumentoDua" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicioDua_MovimientoDocumentoDua, event:{focusout : ValidarFecha, change : OnChangeFiltroDua}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group ">
                                          <div class="input-group-addon">Al</div>
                                          <input id="FechaFinDua_MovimientoDocumentoDua" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFinDua_MovimientoDocumentoDua, event:{focusout : ValidarFecha, change : OnChangeFiltroDua}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" value="0" data-bind="checked:Dua_MovimientoDocumentoDua, event:{change:GrupoDua}">
                                          </div>
                                          <div class="form-group radiotxt">Todas las DUAs</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" value="1" data-bind="checked:Dua_MovimientoDocumentoDua, event:{change:GrupoDua}">
                                          </div>
                                          <div  id="DivDua_MovimientoDocumentoDua" class="form-group radiotxt">Buscar DUA específica</div>
                                          <input id="Item_MovimientoDocumentoDua" class="form-control formulario " name="TextoBuscar_MovimientoDocumentoDua" type="text" placeholder="escribir para buscar...." >
                                          <input id="IdDua_MovimientoDocumentoDua" class="form-control formulario " type="hidden" >
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
                                          <input id="FechaHoy_MovimientoDocumentoDua" name="FechaHoy_MovimientoDocumentoDua" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicio_MovimientoDocumentoDua, event:{focusout : ValidarFecha}">
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
                                          <input id="FechaDeterminada_MovimientoDocumentoDua" name="FechaDeterminada_MovimientoDocumentoDua" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFin_MovimientoDocumentoDua, event:{focusout : ValidarFecha}">
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
                                            <input class="radiobtn" type="radio" name="Producto_MovimientoDocumentoDua" value="0" data-bind="checked:Producto_MovimientoDocumentoDua, event:{change:GrupoProducto}">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Producto_MovimientoDocumentoDua" value="1" data-bind="checked:Producto_MovimientoDocumentoDua, event:{change:GrupoProducto}">
                                          </div>
                                          <div  id="DivBuscar_MovimientoDocumentoDua" class="form-group radiotxt">Buscar</div>
                                          <input id="TextoBuscar_MovimientoDocumentoDua" class="form-control formulario " name="TextoBuscar_MovimientoDocumentoDua" type="text" placeholder="escribir para buscar...." >
                                          <input id="IdProducto_MovimientoDocumentoDua" class="form-control formulario " type="hidden" >
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
                                    <button id="btnexcel_MovimientoDocumentoDua" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                    <button id="btnpdf_MovimientoDocumentoDua" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_MovimientoDocumentoDua" type="button" name="pantalla" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
