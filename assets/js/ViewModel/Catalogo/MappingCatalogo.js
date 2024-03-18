var MappingCatalogo = {
  'Proveedores': {
    create: function (options) {
      if (options)
        return new VistaModeloProveedor(options.data);
    }
  },
  'Proveedor': {
    create: function (options) {
      if (options)
        return new VistaModeloProveedor(options.data);
    }
  },
  'Alumnos': {
    create: function (options) {
      if (options)
        return new AlumnosModel(options.data);
    }
  },
  'Alumno': {
    create: function (options) {
      if (options)
        return new AlumnoModel(options.data);
    }
  },
  'Clientes': {
    create: function (options) {
      if (options)
        return new VistaModeloCliente(options.data);
    }
  },
  'Cliente': {
    create: function (options) {
      if (options)
        return new VistaModeloCliente(options.data);
    }
  },
  'Mercaderia': {
    create: function (options) {
      if (options)
        return new MercaderiaModel(options.data);
    }
  },
  'Producto': {
    create: function (options) {
      if (options)
        return new VistaModeloProducto(options.data);
    }
  },
  'ListaPrecioProducto': {
    create: function (options) {
      if (options)
        return new VistaModeloListaPrecioProducto(options.data);
    }
  },
  'ListaPrecio': {
    create: function (options) {
      if (options)
        return new VistaModeloListaPrecio(options.data);
    }
  },
  'ListaRaleo': {
    create: function (options) {
      if (options)
        return new VistaModeloListaRaleo(options.data);
    }
  },
  'Transportistas': {
    create: function (options) {
      if (options)
        return new VistaModeloTransportista(options.data);
    }
  },
  'Transportista': {
    create: function (options) {
      if (options)
        return new VistaModeloTransportista(options.data);
    }
  }
}
