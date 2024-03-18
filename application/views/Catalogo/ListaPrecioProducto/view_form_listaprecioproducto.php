<!-- ko with : ListaPrecioProducto -->
<form id="formListaPrecioProducto" autocomplete="off">
  <div class="datalist__result">
    <div class="container-fluid">
      <div class="row">
        <fieldset>
          <legend>Filtros</legend>
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario-venta">Sede</div>
                    <select name="combo-sede" id="combo-sede" class="form-control formulario" data-bind="
                          value : IdSede,
                          options : Sedes,
                          optionsValue : 'IdSede' ,
                          optionsText : 'NombreSede',                          
                          event: { keydown: OnKeyEnter}">
                    </select>                  
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <div class="input-group">                    
                      <div class="checkbox">                        
                        <input name="CheckAplicaMismoPrecio" id="CheckAplicaMismoPrecio" 
                        type="checkbox" class="form-control formulario" 
                        data-bind="checked : CheckAplicaMismoPrecio">
                        <label for="CheckAplicaMismoPrecio">Aplicar mismos precios en otra sedes&nbsp;</label>
                      </div>
                      
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario-venta">Familia Producto</div>
                    <select name="combo-familia" id="combo-familia" class="form-control formulario" data-bind="
                          value : IdFamiliaProducto,
                          options : FamiliasProducto,
                          optionsValue : 'IdFamiliaProducto' ,
                          optionsText : 'NombreFamiliaProducto',
                          optionsCaption: 'Todos',
                          event: { change : OnChangeFamilia, keydown: OnKeyEnter}">
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario-venta">Sub Familia</div>
                    <select id="combo-subfamiliaproducto" class="form-control formulario" data-bind="value: IdSubFamiliaProducto,event:{ change: OnChangeSubFamilia, keydown: OnKeyEnter}">
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario-venta">Marca</div>
                    <select name="combo-marca" id="combo-marca" class="form-control formulario" data-bind="
                          value : IdMarca,
                          options : Marcas,
                          optionsValue : 'IdMarca' ,
                          optionsText : 'NombreMarca',
                          optionsCaption: 'Todos',
                          event: { change : OnChangeMarca, keydown: OnKeyEnter}">
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario-venta">Modelo</div>
                    <select id="combo-modelo" class="form-control formulario" data-bind="value:IdModelo, event:{ change: OnChangeModelo, keydown: OnKeyEnter}">
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon formulario-venta">Linea</div>
                    <select name="combo-motivoinventarioinicial" id="combo-motivoinventarioinicial" class="form-control formulario" data-bind="
                          value : IdLineaProducto,
                          options : LineasProducto,
                          optionsCaption: 'Todos',
                          optionsValue : 'IdLineaProducto' ,
                          optionsText : 'NombreLineaProducto',
                          event: {keydown: OnKeyEnter}">
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input class="form-control formulario" type="text" placeholder="Buscar Mercadería por Descripción" data-bind='value : Descripcion, event:{keydown: OnKeyEnter}'>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <button class="btn btn-primary btn-control" type="button" data-bind="event:{click: OnClickConsultarMercaderias}">Buscar</button>
                </div>
              </div>
            </div>
          </div>
        </fieldset>
      </div>
      <br>
      <div class="row">
        <div class="col-md-12">
          <div class="row detalle-comprobante">
            <div class="col-md-12">
              <fieldset>
                <table class="datalist__table table display table-border" width="100%" id="tablaListaPrecioMercaderia">
                  <thead>
                    <tr>
                      <th class="text-center">CÓDIGO</th>
                      <th class="">DESCRIPCIÓN PRODUCTO</th>
                      <th class="text-center">UNIDAD</th>
                      <th class="text-center">PRECIO BASE</th>
                      <!-- ko foreach : TiposListaPrecio -->
                      <th class="text-center" data-bind="text:NombreTipoListaPrecio"></th>
                      <!-- /ko -->
                    </tr>
                  </thead>
                  <tbody>
                    <!-- ko foreach : DetallesListaPrecio -->
                    <tr>
                      <td class="text-center">
                        <span data-bind="text: CodigoMercaderia"></span>
                      </td>
                      <td class="">
                        <span data-bind="text: NombreProducto"></span>
                      </td>
                      <td class="text-center">
                        <span data-bind="text: AbreviaturaUnidadMedida"></span>
                      </td>
                      <td class="text-center">
                        <input type="text" class="form-control formulario  text-right" data-bind="
                        attr: {'data-cantidad-decimal' : DecimalPrecio()},
                        value: PrecioUnitario,
                        numberdecimal: PrecioUnitario">
                      </td>
                      <!-- ko foreach : ListaDePrecios -->
                      <td class="text-center">
                        <input type="text" class="form-control formulario text-right" data-bind="
                        attr: {'data-cantidad-decimal' : $parent.DecimalPrecio()},
                        value: Precio,
                        numberdecimal: Precio,
                        event: {focus: $parent.OnFocus, keydown: $parent.OnKeyEnter, change: $parent.OnChangePrecio}">
                      </td>
                      <!-- /ko -->
                    </tr>
                    <!-- /ko -->
                  </tbody>
                </table>
              </fieldset>
            </div>
          </div>
        </div>
      </div>

      <div class="row text-center">
        <br>
        <button type="button" id="btn_Grabar" class="btn btn-success focus-control" data-bind="click : Guardar">Grabar</button> &nbsp;
        <button type="button" id="btn_Limpiar" class="btn btn-default focus-control" data-bind="click : Limpiar">Limpiar</button> &nbsp;
        <br>
      </div>
    </div>

  </div>
</form>
<!-- /ko -->