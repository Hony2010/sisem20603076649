var ImageURL;

IndexEmpleado = function(data) {
    var _modo_nuevo = false;
    var self = this;
    var _opcion_guardar = 1;
    //var _objeto = Models.data.Empleado;

    ImageURL = data.data.ImageURLEmpleado;

    ko.mapping.fromJS(data, MappingEmpleado, self);
    
}
