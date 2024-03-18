<!-- ko with : vmrReporteProductoMasComprado.dataReporteProductoMasComprado -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">
          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Productos más Comprados</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Buscador -->
                  <form class="form products-new" enctype="multipart/form-data" id="form_ProductoMasComprado" name="form" action="" method="post">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              <fieldset>
                                <legend>Por fecha de emisión</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div  class="input-group">
                                          <div class="input-group-addon">Del</div>
                                          <input id="FechaInicio_ProductoMasComprado" name="FechaInicio_ProductoMasComprado" class="form-control formulario fecha-reporte" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicio_ProductoMasComprado, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon">Al</div>
                                          <input id="FechaFinal_ProductoMasComprado" name="FechaFinal_ProductoMasComprado" class="form-control formulario fecha-reporte" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFinal_ProductoMasComprado, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend>Cantidad de Productos a Mostrar</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="CantidadFilas_ProductoMasComprado" value="0" data-bind="checked:CantidadFilas_ProductoMasComprado">
                                          </div>
                                          <div class="form-group radiotxt">Sólo los 10 primeros</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="CantidadFilas_ProductoMasComprado" value="1" data-bind="checked:CantidadFilas_ProductoMasComprado">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
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
                                    <button id="btnexcel_ProductoMasComprado" type="button" name="excel" class="btn btn-default" data-bind="event:{click:DescargarReporte_ProductoMasComprado}" > Excel </button> &nbsp;
                                    <button id="btnpdf_ProductoMasComprado" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:DescargarReporte_ProductoMasComprado}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_ProductoMasComprado" type="button" class="btn btn-default" data-bind="event:{click:Pantalla_ProductoMasComprado}"> Pantalla </button> &nbsp;
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
