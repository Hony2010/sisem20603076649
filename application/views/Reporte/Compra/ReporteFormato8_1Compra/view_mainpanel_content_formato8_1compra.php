<!-- ko with : vmgReporteFormato8_1Compra.dataReporteFormato8_1Compra -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">
          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte formato 8.1</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Buscador -->
                  <form class="form products-new" enctype="multipart/form-data" id="form_Formato8" name="form" action="" method="post">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              <fieldset>
                                <legend>Por Periodo</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <span class="input-group-addon">Año</span>
                                          <select id="combo-añoperiodo" name="AñoPeriodo_Formato8" class="form-control formulario" data-bind="
                                            value : Año,
                                            options : AñoPeriodo_Formato8,
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
                                          <select id="IdPeriodoInicial_Formato8" name="IdPeriodoInicial_Formato8" class="form-control formulario" data-bind="
                                            value : IdPeriodoInicio,
                                            options : MesesPeriodo_Formato8,
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
                                          <select id="IdPeriodoFinal_Formato8" name="IdPeriodoFinal_Formato8" class="form-control formulario" data-bind="
                                            value : IdPeriodoFin,
                                            options : MesesPeriodo_Formato8,
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
                                    <button id="btnexcel_Formato8" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                    <button id="btnpdf_Formato8" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_Formato8" type="button" name="pantalla" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
