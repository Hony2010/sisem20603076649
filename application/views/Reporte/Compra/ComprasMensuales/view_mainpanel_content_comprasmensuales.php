<!-- ko with : vmrReporteComprasMensuales.dataReporteComprasMensuales -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">
          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Compras Mensuales</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Buscador -->
                  <form class="form products-new" enctype="multipart/form-data" id="form_ComprasMensuales" name="form" action="" method="post">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              <fieldset>
                                <legend>Por Periodo de emisión</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <span class="input-group-addon">Año</span>
                                          <select id="combo-añoperiodo" name="AñoPeriodo_ComprasMensuales" class="form-control formulario" data-bind="
                                            options : AñoPeriodo_ComprasMensuales,
                                            optionsValue : 'Año' ,
                                            optionsText : 'Año',
                                            event : { change : OnChangeAñoPeriodo }">
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-1">
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <span class="input-group-addon">Mes Inical</span>
                                          <select id="IdPeriodoInicial_ComprasMensuales" name="IdPeriodoInicial_ComprasMensuales" class="form-control formulario" data-bind="
                                            options : MesesPeriodo_ComprasMensuales,
                                            optionsValue : 'IdPeriodo',
                                            optionsText : 'Mes'">
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <span class="input-group-addon">Mes Final</span>
                                          <select id="IdPeriodoFinal_ComprasMensuales" name="IdPeriodoFinal_ComprasMensuales" class="form-control formulario" data-bind="
                                            options : MesesPeriodo_ComprasMensuales,
                                            optionsValue : 'IdPeriodo',
                                            optionsText : 'Mes'">
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <div class="col-md-12">
                                <div class="row">
                                  <center>
                                    <hr>
                                    <button id="btnexcel_ComprasMensuales" type="button" name="excel" class="btn btn-default" data-bind="event:{click:DescargarReporte_ComprasMensuales}" > Excel </button> &nbsp;
                                    <button id="btnpdf_ComprasMensuales" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:DescargarReporte_ComprasMensuales}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_ComprasMensuales" type="button" class="btn btn-default" data-bind="event:{click:Pantalla_ComprasMensuales}"> Pantalla </button> &nbsp;
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
