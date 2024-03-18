var Mapping_Mensual = Object.assign(
  MappingVentasMensuales
  );


IndexVentasMensuales = function (data) {

    var self = this;

    ko.mapping.fromJS(data, MappingVentasMensuales, self);

}
