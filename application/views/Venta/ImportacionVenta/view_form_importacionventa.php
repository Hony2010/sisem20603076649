<div class="" style="padding: 0px 20px;" data-bind="visible: $root.dataJSON() != undefined">

  <form id="form-importacion">
    <div class="group-1" data-acc-step>
      <h5 class="mb-5" ata-acc-title>
        <span class="acc-step-number badge badge-pill badge-info mr-1">1</span>
        Clientes
      </h5>
      <div data-acc-content>
        <div class="" data-bind="visible: ClientesImportacion().length == 0">
          <h3 class="text-center">Presione el boton "Siguiente" para validar los datos.</h3>
        </div>
        <div class="row" data-bind="visible: ClientesImportacion().length > 0">
          <div class="col-md-5">
            <h4 align="center">BASE DE DATOS PRINCIPAL</h4>
            <table class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaDetalleComprobanteVenta">
              <thead>
                <tr>
                  <th class="col-md-auto"><center>RUC</center></th>
                  <th class="col-md-12"><center>Razon Social</center></th>
                </tr>
              </thead>
              <tbody>
                <!-- ko foreach : ClientesBase -->
                <tr name="Fila" class="clickable-row" style="min-height: 80px;" data-bind="attr : { id: IdPersona +'_tr_clientebase'}">
                  <td class="col-md-auto text-center" data-bind="style: { color: CodigoEstado == 1 ? 'red' : '' }">
                    <span class="" data-bind="text: NumeroDocumentoIdentidad"></span>
                  </td>
                  <td class="text-center" data-bind="style: { color: CodigoEstado == 2 ? 'blue' : '' }">
                    <span class="" data-bind="text: RazonSocial"></span>
                  </td>
                </tr>
                <!-- /ko -->
              </tbody>
            </table>
          </div>
          <div class="col-md-7">
            <h4 align="center">BASE DE DATOS IMPORTADA</h4>
            <table class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaDetalleComprobanteVenta">
            <thead>
                <tr>
                  <th class="col-md-auto"><center>RUC</center></th>
                  <th class="col-md-10"><center>Razon Social</center></th>
                  <th class="col-md-2"><center>Estado</center></th>
                </tr>
              </thead>
              <tbody>
                <!-- ko foreach : ClientesImportacion -->
                <tr name="Fila" class="clickable-row" style="min-height: 80px;" data-bind="attr : { id: IdPersona +'_tr_clienteimportacion'}, style: { color: CodigoEstado == 0 ? 'green' : '' }">
                  <td class="col-md-auto text-center" data-bind="style: { color: CodigoEstado == 1 ? 'red' : '' }">
                    <span class="" data-bind="text: NumeroDocumentoIdentidad"></span>
                  </td>
                  <td class="col-md-10 text-center" data-bind="style: { color: CodigoEstado == 2 ? 'blue' : '' }">
                    <span class="" data-bind="text: RazonSocial"></span>
                  </td>
                  <td class="text-center">
                    <span class="" data-bind="text: Estado"></span>
                  </td>
                </tr>
                <!-- /ko -->
              </tbody>
            </table>
          </div>
        </div>
        <br />
      </div>
    </div>

    <div class="group-2" data-acc-step>
      <h5 class="mb-5" ata-acc-title>
        <span class="acc-step-number badge badge-pill badge-info mr-1">2</span>
        Productos
      </h5>
      <div data-acc-content>
        <div class="" data-bind="visible: ProductosImportacion().length == 0">
          <h3 class="text-center">Presione el boton "Siguiente" para validar los datos.</h3>
        </div>
        <div class="row" data-bind="visible: ProductosImportacion().length > 0">
          <div class="col-md-5">
            <h4 align="center">BASE DE DATOS PRINCIPAL</h4>
            <table class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaDetalleComprobanteVenta">
              <thead>
                <tr>
                  <!-- <th class="col-md-auto"><center>Codigo</center></th> -->
                  <th class="col-md-12"><center>Producto</center></th>
                </tr>
              </thead>
              <tbody>
                <!-- ko foreach : ProductosBase -->
                <tr name="Fila" class="clickable-row" style="min-height: 80px;" data-bind="attr : { id: IdProducto+'_tr_productobase'}">
                  <!-- <td class="col-md-auto text-center" data-bind="style: { color: CodigoEstado == 1 ? 'red' : '' }">
                    <span class="" data-bind="text: CodigoMercaderia"></span>
                  </td> -->
                  <td class="col-md-12 text-center" data-bind="style: { color: CodigoEstado == 2 ? 'blue' : '' }">
                    <span class="" data-bind="text: NombreProducto"></span>
                  </td>
                </tr>
                <!-- /ko -->
              </tbody>
            </table>
          </div>
          <div class="col-md-7">
            <h4 align="center">BASE DE DATOS IMPORTADA</h4>
            <table class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaDetalleComprobanteVenta">
            <thead>
                <tr>
                  <!-- <th class="col-md-auto"><center>Codigo</center></th> -->
                  <th class="col-md-10"><center>Producto</center></th>
                  <th class="col-md-2"><center>Estado</center></th>
                </tr>
              </thead>
              <tbody>
                <!-- ko foreach : ProductosImportacion -->
                <tr name="Fila" class="clickable-row" style="min-height: 80px;" data-bind="attr : { id: IdProducto +'_tr_productoimportacion'}, style: { color: CodigoEstado == 0 ? 'green' : '' }">
                  <!-- <td class="col-md-auto text-center" data-bind="style: { color: CodigoEstado == 1 ? 'red' : '' }">
                    <span class="" data-bind="text: CodigoMercaderia"></span>
                  </td> -->
                  <td class="col-md-10 text-center" data-bind="style: { color: CodigoEstado == 2 ? 'blue' : '' }">
                    <span class="" data-bind="text: NombreProducto"></span>
                  </td>
                  <td class="col-md-2 text-center">
                    <span class="" data-bind="text: Estado"></span>
                  </td>
                </tr>
                <!-- /ko -->
              </tbody>
            </table>
          </div>
        </div>

        <br />
      </div>
    </div>

    <div class="group-3" data-acc-step>
      <h5 class="mb-5" ata-acc-title>
        <span class="acc-step-number badge badge-pill badge-info mr-1">3</span>
        Ventas
      </h5>
      <div data-acc-content>
        <div class="row">
          <div class="col-md-12">
            <h4 align="center">Ventas a Importar</h4>
            <div align="center">
              <h3 data-bind="text: $root.TotalVentas"></h3>
            </div>
            <table class="datalist__table table display grid-detail-body table-border" width="100%" id="tablaDetalleComprobanteVenta">
              <thead>
                  <tr>
                    <th class="col-md-2 products__id"><center>Documento</center></th>
                    <th class="col-md-2 products__id"><center>Fecha Emision</center></th>
                    <th class="col-md-2 products__id"><center>RUC/DNI</center></th>
                    <th class="col-md-2 products__id"><center>Cliente</center></th>
                    <th class="col-md-2 products__id"><center>Total</center></th>
                    <th class="col-md-2 products__id"><center>Estado</center></th>
                  </tr>
                </thead>
                <tbody>
                  <!-- ko foreach : ComprobantesVenta -->
                  <tr name="Fila" class="clickable-row" style="min-height: 80px;" data-bind="attr : { id: IdComprobanteVenta +'_tr_productoimportacion'}">
                    <td class="text-center">
                      <span class="" data-bind="text: Numero"></span>
                    </td>
                    <td class="text-center">
                      <span class="" data-bind="text: FechaFormateada"></span>
                    </td>
                    <td class="text-center">
                      <span class="" data-bind="text: NumeroDocumentoIdentidad"></span>
                    </td>
                    <td class="text-center">
                      <span class="" data-bind="text: RazonSocial"></span>
                    </td>
                    <td class="text-center">
                      <span class="" data-bind="text: Total"></span>
                    </td>
                    <td class="text-center" data-bind="style: { color: CodigoEstado == 1 ? 'green' : 'red' }">
                      <span class="" data-bind="text: Estado"></span>
                    </td>
                  </tr>
                  <!-- /ko -->
                </tbody>
              </table>
          </div>
        </div>
        <br />
      </div>
    </div>
  </form>

</div>
