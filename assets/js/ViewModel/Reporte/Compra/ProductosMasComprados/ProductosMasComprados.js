var Mapping_MAS = Object.assign(
  Mapping_ProductoMasComprado
  );


IndexReporteProductoMasComprado = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_ProductoMasComprado, self);

}
