var Mapping_MovimientoDocumentoDua = Object.assign(
  MappingMovimientoDocumentoDua
  );


IndexReporteMovimientoDocumentoDua = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingMovimientoDocumentoDua, self);

}
