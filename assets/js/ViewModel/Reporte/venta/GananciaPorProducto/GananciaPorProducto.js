var Mapping_Gananciaporproducto = Object.assign(
  MappingGananciaporproducto
  );


IndexReporteGananciaPorProducto = function (data) {

    var self = this;

    ko.mapping.fromJS(data, Mapping_Gananciaporproducto, self);

}
