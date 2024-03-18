var Mapping_MAS = Object.assign(
  MappingProductoMasVendido
  );


IndexProductosMasVendidos = function (data) {

    var self = this;

    ko.mapping.fromJS(data, MappingProductoMasVendido, self);

}
