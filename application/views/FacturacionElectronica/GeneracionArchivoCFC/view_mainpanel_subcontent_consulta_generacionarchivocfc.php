    <!-- ko with : $root.data.Buscador  -->
    <?php echo $view_subcontent_buscador_generacionarchivocfc; ?>
    <!-- /ko -->
    <br class="espacio_ocultar" /><br class="espacio_ocultar" /><br class="espacio_ocultar" />
    <div class="contenerdor_table_consulta">
      <table class="datalist__table table" width="100%" data-products="brand">
        <thead>
          <tr>
            <th class="col-md-4" ><center>Motivo Contingencia</center></th>
            <th class="col-md-auto"><center>T. Ope.</center></th>
            <th class="col-md-auto"><center>Fecha Emision</center></th>
            <th class="col-md-1"><center>T. Comp.</center></th>
            <th class="col-md-1">Serie</th>
            <th class="col-md-1"><center>Numero</center></th>
            <th class="col-md-1"><center>T. Doc. Cl.</center></th>
            <th class="col-md-1"><center>Doc. Identidad</center></th>
            <th class="col-md-4"><center>Razon Social</center></th>
            <th class="col-md-1"><center>Moneda</center></th>
            <th class="col-md-1"><center>V. V. Grav.</center></th>
            <th class="col-md-1"><center>V. V. No Grav.</center></th>
            <th class="col-md-1"><center>V. V. Ina.</center></th>
            <th class="col-md-1"><center>Total Expo.</center></th>
            <th class="col-md-1"><center>ISC</center></th>
            <th class="col-md-1"><center>IGV</center></th>
            <th class="col-md-1"><center>Otros Trib.</center></th>
            <th class="col-md-1"><center>Total</center></th>

            <th class="col-md-1"><center>T. Comp. Mod.</center></th>
            <th class="col-md-1"><center>Serie Comp. Mod.</center></th>
            <th class="col-md-1"><center>Numero Comp. Mod.</center></th>
            <th class="col-md-1"><center>Reg. Perc.</center></th>
            <th class="col-md-1"><center>Base Impo.</center></th>
            <th class="col-md-1"><center>M. Perc.</center></th>
            <th class="col-md-1"><center>M. Tot. Perc.</center></th>

          </tr>
        </thead>
        <tbody>
          <!-- ko foreach : ComprobantesVenta -->
          <tr class="clickable-row" data-bind="attr : { id: IdComprobanteVenta }" style="text-transform: UpperCase;">
            <td class="col-md-4 text-right col-md-auto-height">
              <select id="combo-tipodocumento" class="form-control formulario" data-bind="
              value: IdMotivoComprobanteFisicoContingencia,
              options : $parent.Motivos,
              optionsValue : 'IdMotivoComprobanteFisicoContingencia',
              optionsText : 'NombreMotivoComprobanteFisicoContingencia',
              event : { change : BuscarCodigoMotivo}">
              </select>
            </td>
            <td align="center" class="col-md-auto col-md-auto-height" data-bind="">-</td>
            <td align="center" class="col-md-auto col-md-auto-height" data-bind="text: FechaEmision"></td>
            <td align="center" class="col-md-auto col-md-auto-height" data-bind="text: CodigoTipoDocumento"></td>
            <td class="col-md-1 col-md-auto-height" data-bind="text: SerieDocumento"></td>
            <td class="col-md-1 col-md-auto-height" data-bind="text: NumeroDocumento"></td>
            <td class="col-md-1 col-md-auto-height" data-bind="text: NombreAbreviado"></td>
            <td class="col-md-1 col-md-auto-height" data-bind="text: NumeroDocumentoIdentidad"></td>
            <td class="col-md-4 text-left col-md-auto-height" style="min-width: 250px;" data-bind="text: RazonSocial"></td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="text: NombreMoneda"></td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="text: ValorVentaGravado"></td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="text: ValorVentaNoGravado"></td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="text: ValorVentaInafecto"></td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="">-</td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="text: ISC"></td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="text: IGV"></td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="text: OtroTributo"></td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="text: Total"></td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="">-</td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="">-</td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="">-</td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="">-</td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="">-</td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="">-</td>
            <td class="col-md-1 text-right col-md-auto-height" data-bind="">-</td>
          </tr>
          <!-- /ko -->
        </tbody>
      </table>
    </div>
    <br />
    <button id="EnviarFTP" class="btn btn-primary" data-bind="event:{click: $root.GenerarCFC}">Generar Archivo</button>
  <br>
