<!-- ko with : vmrReporteCompraGeneral.dataReporteCompraGeneral -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">
          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Compra General</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Buscador -->
                  <form class="form products-new" enctype="multipart/form-data" id="form_General" name="form" action="" method="post">
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
                                        <div class="input-group">
                                          <div class="input-group-addon">Del</div>
                                          <input id="FechaInicio_General" name="FechaInicio_General" class="form-control formulario fecha-reporte" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicio_General, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group ">
                                          <div class="input-group-addon">Al</div>
                                          <input id="FechaFinal_General" name="FechaFinal_General" class="form-control formulario fecha-reporte" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFinal_General, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend>Por periodo de emisión</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-3">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <span class="input-group-addon">Año</span>
                                          <select id="combo-añoperiodo_General" name="AñoPeriodo_General" class="form-control formulario" data-bind="
                                            options : AñoPeriodo_General,
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
                                          <select id="IdPeriodoInicial_General" name="IdPeriodoInicial_General" class="form-control formulario" data-bind="
                                            options : MesesPeriodo_General,
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
                                          <select id="IdPeriodoFinal_General" name="IdPeriodoFinal_General" class="form-control formulario" data-bind="
                                            options : MesesPeriodo_General,
                                            optionsValue : 'IdPeriodo',
                                            optionsText : 'Mes'">
                                          </select>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend></legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md--12">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <span class="input-group-addon">Tipo de Compra</span>
                                          <select id="combo-tipocompra" name="TiposCompra_General" class="form-control formulario" data-bind="
                                          value : IdTipoCompra_General,
                                          options : TiposCompra_General,
                                          optionsValue : 'IdTipoCompra' ,
                                          optionsText : 'NombreTipoCompra',
                                          optionsCaption: 'Todos'">
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              </fieldset>
                              <fieldset>
                                <legend>Proveedor</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_General" value="0" data-bind="checked:NumeroDocumentoIdentidad_General, event:{change:GrupoCliente_General}">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_General" value="1" data-bind="checked:NumeroDocumentoIdentidad_General, event:{change:GrupoCliente_General}">
                                          </div>
                                          <div  id="DivBuscar_General" class="form-group radiotxt">Buscar</div>
                                          <input id="TextoBuscarOculto_General" type="hidden" name="TextoCliente_General" data-bind="value: TextoCliente_General">
                                          <input id="TextoBuscar_General" class="form-control formulario " name="" type="text" placeholder="escribir para buscar...." >
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
                                    <button id="btnexcel_General" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                    <button id="btnpdf_General" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_General" type="button" name="pantalla" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
