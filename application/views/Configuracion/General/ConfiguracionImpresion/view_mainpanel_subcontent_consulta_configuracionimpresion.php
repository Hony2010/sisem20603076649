<div class="scrollable scrollbar-macosx">

  <div class="container-fluid">

    <table class="datalist__table table display" width="100%" data-products="brand">
      <thead>
        <tr>
          <th class="products__id">Código</th>
          <th class="products__title">Descripción</th>
          <th  class="products__title">Tipo Documento</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <!-- ko foreach : ConfiguracionImpresion -->
        <tr class="clickable-row" data-bind="attr : { id: IdConfiguracionImpresion }">

          <td data-bind="text : IdConfiguracionImpresion"></td>

          <td data-bind="text : NombreConfiguracionImpresion"></td>
          <td data-bind="text : NombreTipoDocumento"></td>

          <td align="center" >
              <button data-bind="click : Editar , attr : { id : IdConfiguracionImpresion() + '_btnEditar' } "
                  class="btn btn-sm btn-warning btn-operaciones" data-toogle="tooltip" title="Editar Configuracion Impresion">
                  <span class="glyphicon glyphicon-pencil"></span>
              </button>
          </td>
        </tr>
        <!-- /ko -->
      </tbody>
    </table>
  </div>
</div>
