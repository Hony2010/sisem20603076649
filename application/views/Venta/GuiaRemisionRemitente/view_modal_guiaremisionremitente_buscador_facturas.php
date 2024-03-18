<div class="modal fade" data-backdrop-limit="1" tabindex="-1" role="dialog" id="modalGuiaRemisionRemitenteBuscadorFacturas" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="panel-title">Buscador de Comprobantes Venta por Vendedor</h3>
            </div>
            <div class="modal-body">
                <br>
                <!-- ko with : BuscadorFacturasGuia -->
                <div class="row">
                    <div class="col-md-12">
                        <form action="">
                            <!-- ko with : FiltrosGuia -->
                            <fieldset>
                                <legend>Vendedores</legend>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="multiselect-native-select formulario">
                                                <button type="button" class="multiselect dropdown-toggle btn btn-default btn-control" data-toggle="dropdown">
                                                    <span class="multiselect-selected-text">VENDEDORES </span>
                                                    <span class="badge" data-bind="text: NumeroVendedoresSeleccionados"></span>
                                                    <b style="float: right;margin: 5px;" class="caret"></b>
                                                </button>
                                                <ul class="multiselect-container dropdown-menu" style="width: 100%;">
                                                    <li>
                                                        <div class="checkbox">
                                                            <input id="SelectorVendedores" type="checkbox" data-bind="event: { change:  $parent.SeleccionarTodosVendedores }" />
                                                            <label for="SelectorVendedores" class="checkbox"> Seleccionar Todos</label>
                                                        </div>
                                                    </li>
                                                    <!-- ko foreach: Vendedores -->
                                                    <li>
                                                        <div class="checkbox">
                                                            <input type="checkbox" data-bind="attr : { id: IdUsuario() +'_Usuario' }, event: {change: (data,event) => $parentContext.$parent.SeleccionarVendedor(data,event,$parent)}, checked : EstadoSeleccion" />
                                                            <label class="checkbox" data-bind="text: AliasUsuarioVenta() + ' - ' + RazonSocial(), attr:{ for : IdUsuario() +'_Usuario'}"></label>
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
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input id="input-text-filtro-mercaderia" class="form-control formulario" type="text" placeholder="Buscar por Nro Documento" data-bind="value : TextoFiltro">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon">Del</div>
                                                    <input id="fecha-inicio" class="form-control formulario" data-bind="value: FechaInicio, event:{blur:$parent.ValidarFechaInicio}" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de inicio en invalida" data-validation-has-keyup-event="true" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-addon">Al</div>
                                                    <input id="fecha-fin" class="form-control formulario" data-bind="value: FechaFin, event:{blur:$parent.ValidarFechaFin}" data-inputmask-clearmaskonlostfocus="false" data-validation="date" data-validation-format="dd/mm/yyyy" data-validation-error-msg="La fecha de fin en invalida" data-validation-has-keyup-event="true" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button id="BuscadorEnvio" class="btn btn-primary btn-control" data-bind="event:{click: $parent.OnClickBtnBuscar}" style="width: 100%;">Buscar</button>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <!-- /ko -->
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" style="max-height: 300px;overflow: auto">
                        <fieldset>
                            <table class="datalist__table table display" width="100%" data-products="brand">
                                <thead>
                                    <tr>
                                        <th class="products__id">Documento</th>
                                        <th class="products__title">Fecha Emision</th>
                                        <th class="products__title">Total</th>
                                        <th class="products__title">Vendedor</th>                                        
                                        <th class="col-md-1"> 
                                            <input id="TodosComprobantes" class="input-checkbox" name="TodosComprobantes" 
                                            type="checkbox" data-bind="checked: TodosComprobantes, event: {change: OnChangeTodosComprobantes}">
                                            <label class="label-checkbox">Todo</label>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- ko foreach : ComprobantesVentaGuia -->
                                    <tr class="clickable-row text-uppercase">
                                        <td data-bind="text: Documento"></td>
                                        <td data-bind="text: FechaEmision"></td>
                                        <td data-bind="text: Total"></td>
                                        <td data-bind="text: AliasUsuarioVenta"></td>
                                        <td class="col-md-1 col-md-auto-height">
                                            <input type="checkbox" data-bind="checked: EstadoSelector, event: {change: $parent.OnChangeEstadoSelector}">
                                        </td>
                                        <td></td>
                                    </tr>
                                    <!-- /ko -->
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-success" data-bind="event: {click: OnClickBtnCargarProductos}">Cargar</button>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <table>
                            <tr>
                                <td>
                                    <div id="Paginador">
                                    </div>
                                </td>
                                <td style="padding-bottom:5px;">
                                    <h5>Se encontraron <span data-bind="text: ComprobantesVentaGuia().length"></span> registros</h5>
                                <td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /ko -->
            </div>
        </div>
    </div>
</div>