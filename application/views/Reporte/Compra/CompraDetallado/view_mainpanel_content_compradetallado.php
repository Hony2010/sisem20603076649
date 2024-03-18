<!-- ko with : vmrReporteCompraDetallado.dataReporteCompraDetallado -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">

          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Compra Detallado</h3>
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
                                <legend>Por fecha de emisión</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group">
                                          <div class="input-group-addon">Del</div>
                                          <input id="FechaInicio_Detallado" name="FechaInicio_Detallado" class="form-control formulario fecha-reporte" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" data-bind="value:FechaInicio_Detallado, event:{focusout : ValidarFecha}">
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <div class="input-group ">
                                          <div class="input-group-addon">Al</div>
                                          <input id="FechaFinal_Detallado" name="FechaFinal_Detallado" class="form-control formulario fecha-reporte" type="text" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha es invalida" data-bind="value:FechaFinal_Detallado, event:{focusout : ValidarFecha}">
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
                                          <select id="combo-añoperiodo_Detallado" name="AñoPeriodo_Detallado" class="form-control formulario" data-bind="
                                            options : AñoPeriodo_Detallado,
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
                                          <select id="IdPeriodoInicial_Detallado" name="IdPeriodoInicial_Detallado" class="form-control formulario" data-bind="
                                            options : MesesPeriodo_Detallado,
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
                                          <select id="IdPeriodoFinal_Detallado" name="IdPeriodoFinal_Detallado" class="form-control formulario" data-bind="
                                            options : MesesPeriodo_Detallado,
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
                                <legend>Condición de compras</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="FormaPago_Detallado" value="0" data-bind="checked:FormaPago_Detallado">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="FormaPago_Detallado" value="1" data-bind="checked:FormaPago_Detallado">
                                          </div>
                                          <div class="form-group radiotxt">Contado</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="FormaPago_Detallado" value="2" data-bind="checked:FormaPago_Detallado">
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
                                            <input class="radiobtn" type="radio" name="Orden_Detallado" value="0" data-bind="checked:Orden_Detallado">
                                          </div>
                                          <div class="form-group radiotxt">Fec.Emisión</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Orden_Detallado" value="1" data-bind="checked:Orden_Detallado">
                                          </div>
                                          <div class="form-group radiotxt">Cliente</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="Orden_Detallado" value="2" data-bind="checked:Orden_Detallado">
                                          </div>
                                          <div class="form-group radiotxt">Documento</div>
                                        </label>
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
                                          <select id="combo-tipocompra" name="TiposCompra_Detallado" class="form-control formulario" data-bind="
                                          value : IdTipoCompra_Detallado,
                                          options : TiposCompra_Detallado,
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
                                            <input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_Detallado" value="0" data-bind="checked:NumeroDocumentoIdentidad_Detallado, event:{change:GrupoCliente_Detallado}">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="NumeroDocumentoIdentidad_Detallado" value="1" data-bind="checked:NumeroDocumentoIdentidad_Detallado, event:{change:GrupoCliente_Detallado}">
                                          </div>
                                          <div  id="DivBuscar_Detallado" class="form-group radiotxt">Buscar</div>
                                          <input id="TextoBuscarOculto_Detallado" type="hidden" name="TextoCliente_Detallado" data-bind="value: TextoCliente_Detallado">
                                          <input id="TextoBuscar_Detallado" class="form-control formulario " name="" type="text" placeholder="escribir para buscar...." >
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
                                    <button id="btnexcel_Detallado" type="button" name="excel" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}" > Excel </button> &nbsp;
                                    <button id="btnpdf_Detallado" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_Detallado" type="button" name="pantalla" class="btn btn-default" data-bind="event:{click:OnClickBtnReportes}"> Pantalla </button> &nbsp;
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
