<!-- ko with : vmgReporteListaFamiliasSubFamilias.dataReporteListaFamiliasSubFamilias -->
    <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products">
        <div class="row">
          <div class="col-md-2 col-xs-12">

          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte de Listado de Familias SubFamilias</h3>
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
                              <!--
                              <fieldset>
                                <legend>Rango de fecha</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon">Del</div>
                                          <input id="PrimerDia_FamiliasSubFamilias" name="FechaInicio_FamiliasSubFamilias" class="form-control formulario fecha-reporte" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicio_FamiliasSubFamilias, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group ">
                                          <div class="input-group-addon">Al</div>
                                          <input id="UltimoDia_FamiliasSubFamilias" name="FechaFinal_FamiliasSubFamilias" class="form-control formulario fecha-reporte" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFinal_FamiliasSubFamilias, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend>Condición de venta</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="FormaPago_FamiliasSubFamilias" value="0" data-bind="checked:FormaPago_FamiliasSubFamilias">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="FormaPago_FamiliasSubFamilias" value="1" data-bind="checked:FormaPago_FamiliasSubFamilias">
                                          </div>
                                          <div class="form-group radiotxt">Contado</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="FormaPago_FamiliasSubFamilias" value="2" data-bind="checked:FormaPago_FamiliasSubFamilias">
                                          </div>
                                          <div class="form-group radiotxt">Crédito</div>
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend>Ordenado por</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Orden_FamiliasSubFamilias" value="0" data-bind="checked:Orden_FamiliasSubFamilias">
                                          </div>
                                          <div class="form-group radiotxt">Fec.Emisión</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Orden_FamiliasSubFamilias" value="1" data-bind="checked:Orden_FamiliasSubFamilias">
                                          </div>
                                          <div class="form-group radiotxt">Cliente</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Orden_FamiliasSubFamilias" value="2" data-bind="checked:Orden_FamiliasSubFamilias">
                                          </div>
                                          <div class="form-group radiotxt">Documento</div>
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend>Cliente</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_FamiliasSubFamilias" value="0" data-bind="checked:NumeroDocumentoIdentidad_FamiliasSubFamilias, event:{change:GrupoCliente_FamiliasSubFamilias}">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_FamiliasSubFamilias" value="1" data-bind="checked:NumeroDocumentoIdentidad_FamiliasSubFamilias, event:{change:GrupoCliente_FamiliasSubFamilias}">
                                          </div>
                                          <div  id="DivBuscar_FamiliasSubFamilias" class="form-group radiotxt">Buscar</div>
                                          <input id="TextoBuscarOculto_FamiliasSubFamilias" type="hidden" name="TextoCliente_FamiliasSubFamilias" data-bind="value: TextoCliente_FamiliasSubFamilias">
                                          <input id="TextoBuscar_FamiliasSubFamilias" class="form-control formulario " name="" type="text" placeholder="escribir para buscar...." >
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                            -->
                              <div class="col-md-12">
                                <div class="row">
                                  <center>
                                    <!-- <hr> -->
                                    <button id="btnexcel_FamiliasSubFamilias" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                    <button id="btnpdf_FamiliasSubFamilias" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_FamiliasSubFamilias" type="button" name="pantalla"  class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
