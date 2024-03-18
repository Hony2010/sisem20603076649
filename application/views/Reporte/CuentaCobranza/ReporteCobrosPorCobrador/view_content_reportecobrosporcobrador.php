<!-- ko with : ReporteCobrosPorCobrador -->
<div class="main__cont">
  <div class="container-fluid half-padding">
    <div class="row">
      <div class="col-md-2 col-xs-12">
      </div>
      <div class="col-md-8 col-xs-12">
        <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title">Reporte de Cobros por Cobrador</h3>
          </div>
          <div class="panel-body">
            <div class="datalist__result">
              <!-- ko with : Filtro -->
              <form class="form products-new" enctype="multipart/form-data" id="FormReporteCobrosPorCobrador" name="form" action="" method="post">
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
                          <fieldset>
                            <legend>Cliente</legend>
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label class="input-group">
                                      <div class="input-group-addon addonrd">
                                        <input class="radiobtn" type="radio" name="RadioBtnCliente" value="0" data-bind="checked: IndicadorRadioBtnCliente">
                                      </div>
                                      <div class="form-group radiotxt">Todos</div>
                                    </label>
                                  </div>
                                </div>
                                <div class="col-md-8">
                                  <div class="form-group">
                                    <label class="input-group">
                                      <div class="input-group-addon addonrd">
                                        <input class="radiobtn" type="radio" name="RadioBtnCliente" value="1" data-bind="checked: IndicadorRadioBtnCliente">
                                      </div>
                                      <div class="form-group radiotxt" data-bind="visible: IndicadorRadioBtnCliente() == 0">Buscar</div>
                                      <input id="RazonSocialCliente_6" type="text" class="form-control formulario" placeholder="escribir para buscar...." data-bind="
                                      value: RazonSocialCliente(), 
                                      visible: IndicadorRadioBtnCliente() == 1,
                                      event: { change: ValidarCliente() }" data-validation="autocompletado_cliente" data-validation-error-msg="" data-validation-text-found="">
                                    </label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </fieldset>
                          <fieldset>
                            <legend>Usuarios</legend>
                            <div class="col-md-12">
                              <div class="form-group">
                                <div class="input-group">
                                  <div class="multiselect-native-select formulario">
                                    <button type="button" class="multiselect dropdown-toggle btn btn-default btn-control" data-toggle="dropdown">
                                      <span class="multiselect-selected-text">USUARIOS </span>
                                      <span class="badge" data-bind="text: NumeroUsuariosSeleccionados"></span>
                                      <b style="float: right;margin: 5px;" class="caret"></b>
                                    </button>
                                    <ul class="multiselect-container dropdown-menu" style="width: 100%;">
                                      <li>
                                        <div class="checkbox">
                                          <input id="SelectorUsuarios_CPC" type="checkbox" data-bind="event: { change: SeleccionarTodosUsuarios }" />
                                          <label for="SelectorUsuarios_CPC" class="checkbox"> Seleccionar Todos</label>
                                        </div>
                                      </li>
                                      <!-- ko foreach: Usuarios -->
                                      <li>
                                        <div class="checkbox">
                                          <input type="checkbox" data-bind="attr : { id: IdUsuario() +'_Usuario_CPC' }, event: {change: $parent.SeleccionarUsuario}" />
                                          <label class="checkbox" data-bind="text: AliasUsuarioVenta() + ' - ' + RazonSocial(), attr:{ for : IdUsuario() +'_Usuario_CPC'}"></label>
                                        </div>
                                      </li>
                                      <!-- /ko -->
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </fieldset>
                          <fieldset>
                            <legend>Zona</legend>
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <div class="input-group">
                                      <div class="input-group-addon">Nombre de Zona</div>
                                      <input class="form-control formulario" type="text" data-inputmask-clearmaskonlostfocus="false" data-bind="value: NombreZona">
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