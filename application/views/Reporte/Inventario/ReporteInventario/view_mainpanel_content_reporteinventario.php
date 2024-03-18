<!-- ko with : vmrReporteInventario.dataReporteInventario -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">

          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte de Inventario Inicial</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                      <!-- ko with : Buscador-->
                      <form class="form products-new" enctype="multipart/form-data" id="form_Inventario" name="form" action="" method="post">
                        <div class="container-fluid">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="form-group">
                                    <div class="input-group">
                                      <div class="input-group-addon formulario">Almacén</div>
                                      <select id="Alamacen_Inventario" name="Alamacen_Inventario" class="form-control formulario" data-bind="
                                              options : Almacenes,
                                              optionsValue :'IdAsignacionSede',
                                              optionsText : 'NombreSede',
                                              optionsCaption : 'Todos' ">
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="row">
                                      <center>
                                        <hr>
                                        <!-- <br> -->
                                        <button id="btnexcel_Inventario" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                        <button id="btnpdf_Inventario" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                        <button id="btnpantalla_Inventario" type="button" name="pantalla" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
