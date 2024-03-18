var Mapping_MovimientoAlmacenDocumentoIngreso = Object.assign(
  MappingMovimientoAlmacenDocumentoIngreso
  );


IndexReporteMovimientoAlmacenDocumentoIngreso = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingMovimientoAlmacenDocumentoIngreso, self);

}
