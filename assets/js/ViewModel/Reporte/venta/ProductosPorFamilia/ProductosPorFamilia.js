var Mapping_PF = Object.assign(
  MappingProductosPorFamilia
  );


IndexProductosPorFamilia = function (data) {

    var self = this;

    ko.mapping.fromJS(data, MappingProductosPorFamilia, self);

}
