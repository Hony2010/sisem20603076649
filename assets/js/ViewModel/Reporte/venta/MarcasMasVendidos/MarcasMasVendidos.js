var Mapping_Marca = Object.assign(
  MappingMarcaMasVendido
  );


IndexMarcasMasVendidos = function (data) {

    var self = this;

    ko.mapping.fromJS(data, MappingMarcaMasVendido, self);

}
