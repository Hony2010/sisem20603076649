var MappingVehiculo = {
  'Vehiculos': {
    create: function (options) {
      if (options)
        return new VistaModeloVehiculo(options.data);
    }
  },
  'Vehiculo': {
    create: function (options) {
      if (options)
        return new VistaModeloVehiculo(options.data);
    }
  }
}
