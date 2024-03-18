<!-- ko with : vmrReporteDocumentoIngreso.dataReporteDocumentoIngreso -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">
          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte Saldos de Documento Ingreso / Control</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Buscador-->
                  <form class="form products-new" enctype="multipart/form-data" id="form_DocumentoIngreso" name="form" action="" method="post">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              <!-- <div class="form-group">
                                <div class="input-group">
                                  <div class="input-group-addon formulario">Almacén</div>
                                  <select id="Almacen_DocumentoIngreso" class="form-control formulario" data-bind="
                                  options : Almacenes,
                                  optionsValue :'IdAsignacionSede',
                                  optionsText : 'NombreSede',
                                  optionsCaption : 'Todos'">
                                </select>
                              </div>
                            </div> -->
                              <fieldset>
                                <legend>Seleccionar Documento Ingreso o Control</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon">Del</div>
                                          <input id="FechaInicio_DocumentoIngreso" name="FechaInicio_DocumentoIngreso" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicio_DocumentoIngreso, event:{focusout : ValidarFecha, change : OnChangeFiltroDocumentoIngreso}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group ">
                                          <div class="input-group-addon">Al</div>
                                          <input id="FechaFin_DocumentoIngreso" name="FechaFin_DocumentoIngreso" class="form-control formulario fecha-reporte" type="text"
                                          data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy"
                                          data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFin_DocumentoIngreso, event:{focusout : ValidarFecha, change : OnChangeFiltroDocumentoIngreso}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" value="0" data-bind="checked:DI_DocumentoIngreso, event:{change:GrupoDocumentoIngreso}">
                                          </div>
                                          <div class="form-group radiotxt">Todas los DIs / DCs</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" value="1" data-bind="checked:DI_DocumentoIngreso, event:{change:GrupoDocumentoIngreso}">
                                          </div>
                                          <div  id="DivDI_DocumentoIngreso" class="form-group radiotxt">Buscar DI / DC específico</div>
                                          <input id="Item_DocumentoIngreso" class="form-control formulario " name="TextoBuscar_DocumentoIngreso" type="text" placeholder="escribir para buscar...." >
                                          <input id="IdDI_DocumentoIngreso" class="form-control formulario " type="hidden" >
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
                                    <button id="btnexcel_DocumentoIngreso" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                    <button id="btnpdf_DocumentoIngreso" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_DocumentoIngreso" type="button" name="pantalla" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
