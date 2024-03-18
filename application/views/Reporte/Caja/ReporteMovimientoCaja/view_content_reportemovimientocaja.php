<!-- ko with : ReporteMovimientoCaja -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">
          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte Movimiento Diario</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Filtro -->
                  <form class="form products-new" enctype="multipart/form-data" id="form_Vendedor" name="form" action="" method="post">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              <fieldset>
                                <legend>Rango de fecha</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon">Del</div>
                                          <input class="form-control formulario fecha" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" type="date" data-bind="value: FechaInicio, event:{ focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group ">
                                          <div class="input-group-addon">Al</div>
                                          <input class="form-control formulario fecha" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" data-bind="value: FechaFinal, event:{ focusout: ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <br>
                              <div class="col-md-12">
                                <div class="row">
                                  <center>
                                    <hr>
                                    <button type="button" class="btn btn-default excel" data-bind="event: { click: DescargarReporteExcel }"> E<b><u>x</u></b>cel </button> &nbsp;
                                    <button type="button" class="btn btn-default pdf" data-bind="event: { click: DescargarReportePdf }"> PDF </button> &nbsp;
                                    <button type="button" class="btn btn-default" data-bind="event: { click: MostrarReportePantalla }"> Pantalla </button> &nbsp;
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