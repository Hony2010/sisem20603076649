<!-- ko with : vmgReporteClientesPorZona.dataReporteClientesPorZona -->
    <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products">
        <div class="row">
          <div class="col-md-2 col-xs-12">

          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte de Clientes por Zona</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Buscador-->
                  <form class="form products-new" enctype="multipart/form-data" id="form" name="form" action="" method="post">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              
                              <fieldset>
                                <legend>Filtro</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon">Nombre de Zona</div>
                                          <input id="NombreZona_Z" name="NombreZona_Z" class="form-control formulario" type="text"data-bind="value:NombreZona_Z">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <div class="col-md-12">
                                <div class="row">
                                  <center>
                                    <!-- <hr> -->
                                    <button id="btnexcel_Z" type="button" name="excel" class="btn btn-default" data-bind="event:{click:DescargarReporte_Z}" > Excel </button> &nbsp;
                                    <button id="btnpdf_Z" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:DescargarReporte_Z}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_Z" type="button" class="btn btn-default" data-bind="event:{click:Pantalla_Z}"> Pantalla </button> &nbsp;
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
  </div>
  <!-- /ko -->
