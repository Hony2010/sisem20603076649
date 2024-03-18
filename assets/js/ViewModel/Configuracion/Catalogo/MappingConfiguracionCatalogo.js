var MappingConfiguracionCatalogo = {
    'TiposDocumentoIdentidad': {
        create: function (options) {
            if (options)
              return new VistaModeloTipoDocumentoIdentidad(options.data);
            //  if (options.skip != undefined)
        }
    },
    'TipoDocumentoIdentidad': {
        create: function (options) {
            if (options)
            return new VistaModeloTipoDocumentoIdentidad(options.data);
            //  if (options.skip != undefined)
        }
    }
}
