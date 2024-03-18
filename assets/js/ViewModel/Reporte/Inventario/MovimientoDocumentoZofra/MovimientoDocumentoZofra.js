var Mapping_MovimientoDocumentoZofra = Object.assign(
  MappingMovimientoDocumentoZofra
  );


IndexReporteMovimientoDocumentoZofra = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingMovimientoDocumentoZofra, self);

}
