<fieldset>
  <table class="datalist__table table display table-border" width="100%" data-products="brand">
    <thead>
      <tr>
        <th class="col-md-auto"><center>Documento</center></th>
        <th class="col-md-auto"><center>Fecha Emisión</center></th>
        <th class="col-md-1">RUC</th>
        <th class="col-md-3"><center>Proveedor</center></th>
        <th class="col-md-1"><center>Total</center></th>
        <th class="col-md-auto"><center>Forma Pago</center></th>
        <th class="">Tipo Compra</th>
        <!-- <th class="col-md-1">Situacion CPE</th> -->
        <th >&nbsp;</th>
        <th >&nbsp;</th>
        <th >&nbsp;</th>
        <!-- <th >&nbsp;</th> -->
      </tr>
    </thead>
    <tbody>
      <!-- ko foreach : ComprobantesCompra -->
      <tr class="clickable-row" data-bind="click : $root.Seleccionar, attr : { id: IdComprobanteCompra }">
        <td class="col-md-auto col-md-auto-height text-center" data-bind="text : Numero()"></td>
        <td class="col-md-auto col-md-auto-height"><center><span data-bind="text : FechaEmision"></span></center></td>
        <td class="col-md-1 col-md-auto-height" data-bind="text : NumeroDocumentoIdentidad"></td>
        <td class="col-md-3 col-md-auto-height" data-bind="text : RazonSocial"></td>
        <td class="col-md-1 text-right col-md-auto-height" data-bind="text : TotalComprobante()"></td>
        <td class="col-md-auto col-md-auto-height"><center><span data-bind="text : NombreFormaPago"></span></center></td>
        <td class="" data-bind="text : NombreTipoCompra"></td>
        <!-- <td align="center" class="col-md-1 col-md-auto-font col-md-auto-height" data-bind="text: SituacionCPE()"></td> -->
        <td align="center" class="col-md-auto col-md-auto-height">
          <button data-bind="click : $root.OnClickBtnVer , attr : { id : IdComprobanteCompra() + '_btnVer' }  "
          class="btn btn-sm btn-info btn-operaciones" data-toogle="tooltip" title="Ver Comprobante Compra">
            <span class="fa fa-fw fa-eye"></span>
          </button>
        </td>
        <td  align="center" class="col-md-auto col-md-auto-height">
          <button data-bind="click : $root.OnClickBtnEditar , attr : { id : IdComprobanteCompra() + '_btnEditar' },enable: OnEnableBtnEditar() "
          class="btn btn-sm btn-warning btn-operaciones" data-toogle="tooltip" title="Editar Comprobante Compra">
            <span class="glyphicon glyphicon-pencil"></span>
          </button>
        </td>
        <td align="center" class="col-md-auto col-md-auto-height">
          <button data-bind="click : $root.Eliminar,enable:  OnEnableBtnEliminar()"
          class="btn btn-sm btn-danger btn-operaciones" data-toogle="tooltip" title="Eliminar ComprobanteCompra">
            <span class="glyphicon glyphicon-trash"></span>
          </button>
        </td>
        <td align="center" class="col-md-auto col-md-auto-height">
          <button data-bind="click : $root.OnClickBtnEditarAlternativo,visible:  OnEnableBtnEditarAlternativo()"
          class="btn btn-sm btn-default btn-operaciones" data-toogle="tooltip" title="Editar ComprobanteCompra">
            <span class="glyphicon glyphicon-refresh"></span>
          </button>
        </td>
      </tr>
      <!-- /ko -->
    </tbody>
  </table>
</fieldset>
