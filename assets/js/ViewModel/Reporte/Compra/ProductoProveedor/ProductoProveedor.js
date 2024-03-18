var Mapping_ProductoProveedor = Object.assign(
  MappingProductoProveedor
  );


IndexReporteProductoProveedor = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_ProductoProveedor, self);

}
