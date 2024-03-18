var MappingConfiguracionGeneral = {
    'TipoCambio': {
        create: function (options) {
            if (options)
              return new VistaModeloTipoCambio(options.data);
            //  if (options.skip != undefined)
        }
    },
    'TiposCambio': {
        create: function (options) {
            if (options)
            return new VistaModeloTipoCambio(options.data);
            //  if (options.skip != undefined)
        }
    }
}
