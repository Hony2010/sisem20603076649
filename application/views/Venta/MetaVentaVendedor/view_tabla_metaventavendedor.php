<fieldset>
  <table class="datalist__table table display table-border">
    <thead>
      <tr>
        <!-- <th>N°</th> -->
        <th>VENDEDOR</th>
        <th>USUARIO</th>
        <th>SUELDO</th>
        <th>META MENSUAL</th>
        <th>BONIFICACION AL 50%</th>
        <th>BONIFICACION AL 100%</th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : MetasVentaVendedor -->
      <tr class="clickable-row" data-bind="event: { click : $root.Seleccionar }">
        <!-- <td class="">
          <span data-bind=""></span>
        </td> -->
        <td class="">
          <span data-bind="text: RazonSocial"></span>
        </td>
        <td class="">
          <span data-bind="text: AliasUsuarioVenta"></span>
        </td>
        <td class="">
          <span data-bind="text: Sueldo"></span>
        </td>
        <td class=" ">
          <div class="form-grop">
            <input type="text" class="form-control formulario text-right" data-bind="
            value: MetaVentaMensual,
            numbertrim: MetaVentaMensual,
            event: {focus: $root.OnFocus, keydown: $root.OnKeyEnter}">
          </div>
        </td>
        <td class="">
          <div class="form-grop">
            <input type="text" class="form-control formulario text-right" data-bind="
            value: BonificacionMetaCincuenta,
            numbertrim: BonificacionMetaCincuenta,
            event: {focus: $root.OnFocus, keydown: $root.OnKeyEnter}">
          </div>
        </td>
        <td class="">
          <div class="form-grop">
            <input type="text" class="form-control formulario text-right" data-bind="
            value: BonificacionMetaCien,
            numbertrim: BonificacionMetaCien,
            event: {focus: $root.OnFocus, keydown: $root.OnKeyEnter}">
          </div>
        </td>
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</fieldset>