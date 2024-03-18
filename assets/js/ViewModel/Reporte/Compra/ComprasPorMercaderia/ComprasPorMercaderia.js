var Mapping_Mercaderia = Object.assign(
  MappingComprasPorMercaderia
  );


IndexReporteComprasPorMercaderia = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_Mercaderia, self);

}
