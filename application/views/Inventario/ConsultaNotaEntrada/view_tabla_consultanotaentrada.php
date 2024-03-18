<fieldset>
  <table class="datalist__table table display table-border" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="col-md-auto"><center>Documento</center></th>
        <th class="col-md-auto"><center>Documento Referencia</center></th>
        <th class="col-md-auto"><center>Fecha Emisión</center></th>
        <th class="col-md-1">RUC</th>
        <th class="col-md-4"><center>Proveedor</center></th>
        <th >&nbsp;</th>
        <th >&nbsp;</th>
        <th >&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : NotasEntrada -->
      <tr class="clickable-row" data-bind="click : $root.Seleccionar, attr : { id: IdNotaEntrada }">
        <td align="center" class="col-md-auto col-md-auto-height" data-bind="text : Numero()"></td>
        <td align="center" class="col-md-auto col-md-auto-height" data-bind="text : DocumentoReferencia"></td>
        <td class="col-md-auto col-md-auto-height"><center><span data-bind="text : FechaEmision"></span></center></td>
        <td  class="col-md-1 col-md-auto-height" data-bind="text : NumeroDocumentoIdentidad"></td>
        <td class="col-md-4 col-md-auto-height" data-bind="text : RazonSocial"></td>

        <td align="center" class="col-md-auto col-md-auto-height">
          <div data-bind="css: VistaReferencia">
            <button data-bind="click : $root.OnClickBtnVer , attr : { id : IdNotaEntrada() + '_btnVer' }  "
            class="btn btn-sm btn-info btn-operaciones" data-toogle="tooltip" title="Ver Comprobante Compra">
              <span class="fa fa-fw fa-eye"></span>
            </button>
          </div>
        </td>
        <td  align="center" class="col-md-auto col-md-auto-height">
          <div data-bind="css: VistaReferencia">
            <button data-bind="click : $root.OnClickBtnEditar , attr : { id : IdNotaEntrada() + '_btnEditar' },enable: OnEnableBtnEditar() "
            class="btn btn-sm btn-warning btn-operaciones" data-toogle="tooltip" title="Editar Comprobante Compra">
              <span class="glyphicon glyphicon-pencil"></span>
            </button>
          </div>
        </td>
        <td align="center" class="col-md-auto col-md-auto-height">
          <div data-bind="css: VistaReferencia">
            <button data-bind="click : $root.Eliminar,enable:  OnEnableBtnEliminar()"
            class="btn btn-sm btn-danger btn-operaciones" data-toogle="tooltip" title="Eliminar NotaEntrada">
              <span class="glyphicon glyphicon-trash"></span>
            </button>
          </div>
        </td>
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</fieldset>
