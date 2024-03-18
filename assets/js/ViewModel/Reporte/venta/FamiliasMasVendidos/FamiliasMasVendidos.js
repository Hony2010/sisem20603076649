var Mapping_Familia = Object.assign(
  MappingFamiliaMasVendido
  );


IndexFamiliasMasVendidos = function (data) {

    var self = this;

    ko.mapping.fromJS(data, MappingFamiliaMasVendido, self);

}
