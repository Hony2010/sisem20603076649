var options_CV = {
  IDForm : "#formImportacionMasiva",
  IDModalCliente :"#modalCliente" ,
  IDPanelHeader : "#panelHeaderImportacionMasiva",
  IDModalImportacionMasiva : "#modalImportacionMasiva"
};

var _MappingMasivo = {
    'ImportacionMasiva': {
        create: function (options) {
            if (options)
              return new VistaModeloImportacionMasiva(options.data, options_CV);
            }
    }
}

var MappingMasivo = Object.assign(_MappingMasivo);
