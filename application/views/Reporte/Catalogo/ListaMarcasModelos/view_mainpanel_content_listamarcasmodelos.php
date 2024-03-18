<!-- ko with : vmgReporteListaMarcasModelos.dataReporteListaMarcasModelos -->
    <div class="main__cont">
    <div class="container-fluid half-padding">
      <div class="datalist page page_products products">
        <div class="row">
          <div class="col-md-2 col-xs-12">

          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Reporte de Listado de Marcas y Modelos</h3>
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
                                          <input id="PrimerDia_MarcasModelos" name="FechaInicio_MarcasModelos" class="form-control formulario fecha-reporte" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicio_MarcasModelos, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group ">
                                          <div class="input-group-addon">Al</div>
                                          <input id="UltimoDia_MarcasModelos" name="FechaFinal_MarcasModelos" class="form-control formulario fecha-reporte" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFinal_MarcasModelos, event:{focusout : ValidarFecha}">
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
                                            <input class="radiobtn" type="radio" name="FormaPago_MarcasModelos" value="0" data-bind="checked:FormaPago_MarcasModelos">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="FormaPago_MarcasModelos" value="1" data-bind="checked:FormaPago_MarcasModelos">
                                          </div>
                                          <div class="form-group radiotxt">Contado</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="FormaPago_MarcasModelos" value="2" data-bind="checked:FormaPago_MarcasModelos">
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
                                            <input class="radiobtn" type="radio" name="Orden_MarcasModelos" value="0" data-bind="checked:Orden_MarcasModelos">
                                          </div>
                                          <div class="form-group radiotxt">Fec.Emisión</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Orden_MarcasModelos" value="1" data-bind="checked:Orden_MarcasModelos">
                                          </div>
                                          <div class="form-group radiotxt">Cliente</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Orden_MarcasModelos" value="2" data-bind="checked:Orden_MarcasModelos">
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
                                            <input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_MarcasModelos" value="0" data-bind="checked:NumeroDocumentoIdentidad_MarcasModelos, event:{change:GrupoCliente_MarcasModelos}">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_MarcasModelos" value="1" data-bind="checked:NumeroDocumentoIdentidad_MarcasModelos, event:{change:GrupoCliente_MarcasModelos}">
                                          </div>
                                          <div  id="DivBuscar_MarcasModelos" class="form-group radiotxt">Buscar</div>
                                          <input id="TextoBuscarOculto_MarcasModelos" type="hidden" name="TextoCliente_MarcasModelos" data-bind="value: TextoCliente_MarcasModelos">
                                          <input id="TextoBuscar_MarcasModelos" class="form-control formulario " name="" type="text" placeholder="escribir para buscar...." >
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
                                    <button id="btnexcel_MarcasModelos" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                    <button id="btnpdf_MarcasModelos" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_MarcasModelos" type="button" name="pantalla"  class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
