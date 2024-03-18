var Mapping_GananciaPorPrecioBaseProducto = Object.assign(
  MappingGananciaPorPrecioBaseProducto
  );


IndexReporteGananciaPorPrecioBaseProducto = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_GananciaPorPrecioBaseProducto, self);

}
