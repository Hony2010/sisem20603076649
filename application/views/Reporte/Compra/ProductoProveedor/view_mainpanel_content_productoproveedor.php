<!-- ko with : vmgReporteProductoPorProveedor.dataReporteProductoPorProveedor -->
  <div class="main__cont">
    <div class="container-fluid half-padding">
        <div class="row">
          <div class="col-md-2 col-xs-12">
          </div>
          <div class="col-md-8 col-xs-12"> <!--col-lg-12 col-md-12 col-sm-12 col-xs-12-->
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Producto por Proveedor</h3>
              </div>
              <div class="panel-body">
                <div class="datalist__result">
                  <!-- ko with : Buscador -->
                  <form class="form products-new" enctype="multipart/form-data" id="form_ProductoProveedor" name="form" action="" method="post">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-12">
                              <fieldset>
                                <legend>Proveedor</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="IdProveedor_ProductoProveedor" value="0" data-bind="checked:IdProveedor_ProductoProveedor, event:{change:GrupoProveedor_ProductoProveedor}">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="IdProveedor_ProductoProveedor" value="1" data-bind="checked:IdProveedor_ProductoProveedor, event:{change:GrupoProveedor_ProductoProveedor}">
                                          </div>
                                          <div  id="DivBuscarProveedor_ProductoProveedor" class="form-group radiotxt">Buscar</div>
                                          <input id="TextoBuscarOcultoProveedor_ProductoProveedor" type="hidden" name="TextoProveedor_ProductoProveedor" data-bind="value: TextoProveedor_ProductoProveedor">
                                          <input id="TextoBuscarProveedor_ProductoProveedor" class="form-control formulario " name="" type="text" placeholder="escribir para buscar...." >
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                              <fieldset>
                                <legend>Mercadería</legend>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="IdProducto_ProductoProveedor" value="0" data-bind="checked:IdProducto_ProductoProveedor, event:{change:GrupoProducto_ProductoProveedor}">
                                          </div>
                                          <div class="form-group radiotxt">Todos</div>
                                        </label>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="form-group">
                                        <label class="input-group">
                                          <div class="input-group-addon addonrd">
                                            <input class="radiobtn" type="radio" name="IdProducto_ProductoProveedor" value="1" data-bind="checked:IdProducto_ProductoProveedor, event:{change:GrupoProducto_ProductoProveedor}">
                                          </div>
                                          <div  id="DivBuscarMercaderia_ProductoProveedor" class="form-group radiotxt">Buscar</div>
                                          <input id="TextoBuscarOcultoMercaderia_ProductoProveedor" type="hidden" name="TextoMercaderia_ProductoProveedor" data-bind="value: TextoMercaderia_ProductoProveedor">
                                          <input id="TextoBuscarMercaderia_ProductoProveedor" class="form-control formulario " name="" type="text" placeholder="escribir para buscar...." >
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
                                    <button id="btnexcel_ProductoProveedor" type="button" name="excel" class="btn btn-default" data-bind="event:{click:DescargarReporte_ProductoProveedor}" > Excel </button> &nbsp;
                                    <button id="btnpdf_ProductoProveedor" type="button" name="pdf" class="btn btn-default" data-bind="event:{click:DescargarReporte_ProductoProveedor}"> PDF </button> &nbsp;
                                    <button id="btnpantalla_ProductoProveedor" type="button" class="btn btn-default" data-bind="event:{click:Pantalla_ProductoProveedor}"> Pantalla </button> &nbsp;
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
