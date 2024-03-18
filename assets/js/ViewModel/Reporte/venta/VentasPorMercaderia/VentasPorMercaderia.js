var Mapping_Mercaderia = Object.assign(
  MappingVentasPorMercaderia
  );


IndexReporteVentasPorMercaderia = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_Mercaderia, self);

}
