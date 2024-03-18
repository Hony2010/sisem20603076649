<!-- ko foreach : AccesosRol -->
<div class="row">
  <div class="col-md-12">
    <ul style="list-style:none;">
      <li>
          <h3>
            <label>
              <input id="mercaderia" name="mercaderia" type="checkbox" data-bind="checked: EstadoGeneral, event:{change: CambiarEstadoCheck}" />
              <span data-bind="text: NombreModuloSistema">Catálogo</span>
            </label>
          </h3>
          <ul dir=""style="list-style:none;">
          <!-- ko foreach : OpcionesSistema -->
            <li>
              <label >
                <input id="accesorol" name="accesorol" type="checkbox" data-bind="checked: Estado"/>
                <span data-bind="text: NombreOpcionSistema"></span>
              </label>
            </li>
          <!-- /ko -->
         </ul>
      </li>
    </ul>

  </div>
</div>
<!-- /ko -->
