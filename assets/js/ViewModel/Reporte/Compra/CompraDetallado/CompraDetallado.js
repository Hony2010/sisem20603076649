var Mapping_Detallado = Object.assign(
  MappingCompraDetallado
  );


IndexReporteCompraDetallado = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_Detallado, self);

}
