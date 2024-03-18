var Mapping_Gananciaporvendedor = Object.assign(
  MappingGananciaporvendedor
  );


IndexReporteGananciaPorVendedor = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_Gananciaporvendedor, self);

}
