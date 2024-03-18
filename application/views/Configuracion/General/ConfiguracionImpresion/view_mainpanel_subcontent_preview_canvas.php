<!-- ko with :$root.data.Canvas  -->
<div id="preview_canvas" class="products-preview datalist-preview">
  <button id="btn_cargar" class="btn btn-warning" type="button" name="button" data-bind="event:{click: CargarJSON}">Cargar JSON</button>
  <button id="btn_parsear" class="btn btn-primary" type="button" name="button" data-bind="event:{click: RenderizarJSON}">Parsear JSON</button>
  <button id="btn_imprimir" class="btn btn-success" type="button" name="button" data-bind="event:{click: ImprimirArchivoJSON}">Imprimir JSON</button>

  <div class="products-preview__cont">

    <!--<div class="products-preview__name" title="Nombre Producto" data-bind="text: NombreProducto">Nombre</div>-->
    <div class="products-preview__data" width="100%">
      <div class="products-preview__photo" style="width:100%; height:100%; max-width: 100%; padding-right:0">
        <div id="preview-images">

        </div>
      </div>
    </div>

    <div class="products-preview__props"> <!-- Vista Previa de Familia -->
        <fieldset>
          <legend>Propiedades Documento</legend>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">Width</div>
                  <input id="canvas-width" class="form-control formulario" name="canvas-width" type="text" value="600" data-bind="value: AnchoHoja"/>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">Height</div>
                  <input id="canvas-height" class="form-control formulario" name="canvas-height" type="text" value="500" data-bind="value: AltoHoja"/>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">Background</div>
                  <img id="img_background" class="hidden"/>
                  <input id="image" class="form-control formulario" name="image" type="file" data-bind="event:{change: CambiarBackGround}"/>
                </div>
              </div>
            </div>
          </div>

          <button class="btn btn-primary" title="Ajustar" data-bind="event:{click: AjustarPlantilla}">Ajustar Lienzo</button>
          <button class="btn btn-warning" title="Back Image" data-bind="event:{click: AgregarFondoImagen}">ImageBack</button>
          <button class="btn btn-danger" title="No Image" data-bind="event:{click: QuitarFondoImagen}">No ImageBack</button>
        </fieldset>

        <fieldset>
            <legend>Datos de Comprobante</legend>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon">Tipo</div>
                    <select class="form-control formulario" id="tipo">
                      <option value="0" selected>Etiqueta</option>
                      <option value="1">Cabecera</option>
                      <option value="2">Detalle</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon">Valor</div>
                    <input id="text_texto" class="form-control formulario" name="text_texto" type="text"/>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon">Id</div>
                    <input id="text_id" class="form-control formulario" name="text_id" type="text"/>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-addon">Campo Tabla</div>
                    <input id="text_name" class="form-control formulario" name="text_value" type="text"/>
                  </div>
                </div>
              </div>
            </div>
        </fieldset>
        <fieldset>
          <legend>Alineamiento</legend>
            <button id="objAlignTop" class="btn btn-primary" type="button" name="button">Top</button>
            <button id="objAlignBottom" class="btn btn-primary" type="button" name="button">Bottom</button>
            <button id="objAlignCenter" class="btn btn-primary" type="button" name="button">Center</button>
            <button id="objAlignRight" class="btn btn-primary" type="button" name="button">Right</button>
            <button id="objAlignLeft" class="btn btn-primary" type="button" name="button">Left</button>
        </fieldset>
        <fieldset>
          <legend>Propiedades del Texto</legend>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">Fuente</div>
                  <select class="form-control formulario" id="font-family">
                    <option value="arial">Arial</option>
                    <option value="helvetica" selected>Helvetica</option>
                    <option value="myriad pro">Myriad Pro</option>
                    <option value="delicious">Delicious</option>
                    <option value="verdana">Verdana</option>
                    <option value="georgia">Georgia</option>
                    <option value="courier">Courier</option>
                    <option value="comic sans ms">Comic Sans MS</option>
                    <option value="impact">Impact</option>
                    <option value="monaco">Monaco</option>
                    <option value="optima">Optima</option>
                    <option value="hoefler text">Hoefler Text</option>
                    <option value="plaster">Plaster</option>
                    <option value="engagement">Engagement</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">Color</div>
                  <input type="color" class="form-control formulario" id="text-font-color" value="#000000">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">Font size</div>
                  <input type="range" class="form-control formulario" value="20" min="1" max="120" step="1" id="text-font-size">
                  <div id="font_value_text" class="input-group-addon">13</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div id="text-controls-additional">
                <input type='checkbox' name='fonttype' id="text-cmd-bold">Bold
                <input type='checkbox' name='fonttype' id="text-cmd-italic">Italic
                <!--<input type='checkbox' name='fonttype' id="text-cmd-underline" >Underline
                <input type='checkbox' name='fonttype'  id="text-cmd-linethrough">Linethrough
                <input type='checkbox' name='fonttype'  id="text-cmd-overline" >Overline-->
              </div>
            </div>
          </div>

          <div id="text-decoration">
            <label class="radio-inline"><input type="radio" name="text-decoration-group" id="text_none" value="none" checked>None</label>
            <label class="radio-inline"><input type="radio" name="text-decoration-group" id="text_underline" value="underline">Underline</label>
            <label class="radio-inline"><input type="radio" name="text-decoration-group" id="text_linethrough" value="linethrough">Linethrough</label>
            <label class="radio-inline"><input type="radio" name="text-decoration-group" id="text_overline" value="overline">Overline</label>
          </div>
          <div class="" style="text-align: center;">
            <button class="btn btn-primary" data-bind="event:{click: AgregarTexto}">Agregar Texto</button>
            <button class="btn btn-danger" data-bind="event:{click: BorrarObjeto}">Borrar Objeto</button>
          </div>
        </fieldset>
        <fieldset>
          <legend>Propiedades de Imagen</legend>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">Width</div>
                  <input id="image-width" class="form-control formulario" name="image-width" type="text" value="100"/>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">Height</div>
                  <input id="image-height" class="form-control formulario" name="image-height" type="text" value="100"/>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">Background</div>
                  <img id="img_image" class="hidden"/>
                  <input id="img_insert" class="form-control formulario" name="img_insert" type="file" data-bind="event:{change: CambiarImagen}"/>
                </div>
              </div>
            </div>
          </div>
          <div class="" style="text-align: center;">
            <button class="btn btn-primary" title="Imagen" data-bind="event:{click: AgregarImagen}">Insertar Imagen</button>
          </div>
        </fieldset>
        <div class="" style="text-align: center;">
          <button class="btn btn-warning" data-bind="event:{click: ConsolaJSON}">JSON</button>
          <button class="btn btn-primary" data-bind="event:{click: GuardarArchivoJSON}">Guardar</button>
        </div>

      <!--<div class="products-preview__prop"><i class="fa fa-heartbeat"></i><span class="products-preview__status" data-bind="text: PrecioUnitario">Stock Actual</span></div>-->
    </div>

  </div>
</div>
<!-- /ko -->
