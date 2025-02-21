<?php

$config['const_sisem_configuracion_general']=array();

defined('ID_NUM_POR_PAGINA_TIPO_CAMBIO')       OR define('ID_NUM_POR_PAGINA_TIPO_CAMBIO',94);
defined('ID_NUM_ENTERO_TIPO_CAMBIO')       OR define('ID_NUM_ENTERO_TIPO_CAMBIO',20);
defined('ID_NUM_DECIMAL_TIPO_CAMBIO')       OR define('ID_NUM_DECIMAL_TIPO_CAMBIO',21);
defined('ID_EMPRESA')       OR define('ID_EMPRESA',1); //GRUPO
defined('ID_URL_CARPETA_IMAGENES_EMPRESA')       OR define('ID_URL_CARPETA_IMAGENES_EMPRESA',41); //ParametroSistema

defined('ID_TIPO_DOCUMENTO_FACTURA')       OR define('ID_TIPO_DOCUMENTO_FACTURA',2);
defined('ID_TIPO_DOCUMENTO_BOLETA')       OR define('ID_TIPO_DOCUMENTO_BOLETA',4);
defined('ID_TIPO_DOCUMENTO_ORDEN_PEDIDO')       OR define('ID_TIPO_DOCUMENTO_ORDEN_PEDIDO',78);
defined('ID_TIPO_DOCUMENTO_COMANDA')       OR define('ID_TIPO_DOCUMENTO_COMANDA',86);
defined('ID_TIPO_DOCUMENTO_TICKET')       OR define('ID_TIPO_DOCUMENTO_TICKET',87);
defined('ID_TIPO_DOCUMENTO_GUIA_REMISION_REMITENTE')       OR define('ID_TIPO_DOCUMENTO_GUIA_REMISION_REMITENTE',10);
defined('ID_TIPO_DOCUMENTO_PROFORMA')       OR define('ID_TIPO_DOCUMENTO_PROFORMA',90);
defined('ID_TIPO_DOCUMENTO_CODIGO_BARRAS')       OR define('ID_TIPO_DOCUMENTO_CODIGO_BARRAS',93);
defined('ID_TIPO_DOCUMENTO_TRANSFERENCIA_ALMACEN')       OR define('ID_TIPO_DOCUMENTO_TRANSFERENCIA_ALMACEN',95);


defined('ID_TIPO_PERSONA_JURIDICA')       OR define('ID_TIPO_PERSONA_JURIDICA',1);
defined('ID_TIPO_PERSONA_NATURAL')       OR define('ID_TIPO_PERSONA_NATURAL',2);
defined('ID_TIPO_PERSONA_NO_DOMICILIADO')       OR define('ID_TIPO_PERSONA_NO_DOMICILIADO',3);

defined('ID_PARAMETRO_CAMPO_A_CUENTA')       OR define('ID_PARAMETRO_CAMPO_A_CUENTA',184); //ParametroSistema
defined('ID_PARAMETRO_CAMPO_MONTO_PENDIENTE_VENTA')       OR define('ID_PARAMETRO_CAMPO_MONTO_PENDIENTE_VENTA',398); //ParametroSistema
defined('ID_PARAMETRO_CAMPOS_CON_ENVIO_Y_GESTION')       OR define('ID_PARAMETRO_CAMPOS_CON_ENVIO_Y_GESTION',185); //ParametroSistema

defined('ID_MONEDA_SOLES') OR define('ID_MONEDA_SOLES',1);
defined('ID_MONEDA_DOLARES') OR define('ID_MONEDA_DOLARES',2);
defined('ID_CORRELATIVO_DOCUMENTO_PRINCIPAL') OR define('ID_CORRELATIVO_DOCUMENTO_PRINCIPAL',1);
defined('ID_TIPODOCUMENTO_DUA') OR define('ID_TIPODOCUMENTO_DUA',48);
defined('ID_TIPODOCUMENTO_DOCUMENTOINGRESO') OR define('ID_TIPODOCUMENTO_DOCUMENTOINGRESO',79);
defined('ID_TIPODOCUMENTO_DOCUMENTOCONTROL') OR define('ID_TIPODOCUMENTO_DOCUMENTOCONTROL',80);
defined('ID_TIPODOCUMENTO_NOTACREDITO') OR define('ID_TIPODOCUMENTO_NOTACREDITO',8);
defined('ID_TIPODOCUMENTO_NOTADEBITO') OR define('ID_TIPODOCUMENTO_NOTADEBITO',9);
defined('ID_TIPODOCUMENTO_NOTASALIDA') OR define('ID_TIPODOCUMENTO_NOTASALIDA',75);
defined('ID_TIPODOCUMENTO_NOTAENTRADA') OR define('ID_TIPODOCUMENTO_NOTAENTRADA',76);
defined('ID_TIPODOCUMENTO_INVENTARIOINICIAL') OR define('ID_TIPODOCUMENTO_INVENTARIOINICIAL',77);
defined('ID_TIPODOCUMENTO_COMANDA') OR define('ID_TIPODOCUMENTO_COMANDA',86);
defined('ID_TIPODOCUMENTO_NOTADEVOLUCION') OR define('ID_TIPODOCUMENTO_NOTADEVOLUCION',89);

defined('ID_PARAMETRO_CANTIDAD_CAJA')       OR define('ID_PARAMETRO_CANTIDAD_CAJA',211);
defined('ID_PARAMETRO_TRANSPORTES')       OR define('ID_PARAMETRO_TRANSPORTES',224);
defined('ID_PARAMETRO_ALUMNO')       OR define('ID_PARAMETRO_ALUMNO',225);
defined('ID_PARAMETRO_LOTE')       OR define('ID_PARAMETRO_LOTE',162);
defined('ID_ATRIBUTOS_MENSAJE_DEMO')       OR define('ID_ATRIBUTOS_MENSAJE_DEMO',13);

defined('ESTADO_ACTIVO')       OR define('ESTADO_ACTIVO',"A");
defined('ESTADO_ELIMINADO')       OR define('ESTADO_ELIMINADO',"E");
defined('ESTADO_INACTIVO')       OR define('ESTADO_INACTIVO',"I");
defined('ESTADO_BLOQUEADO')       OR define('ESTADO_BLOQUEADO',"B");
defined('ESTADO_ANULADO')       OR define('ESTADO_ANULADO','N');


defined('ESTADO_DOCUMENTO_ACTIVO')       OR define('ESTADO_DOCUMENTO_ACTIVO','A');
defined('ESTADO_DOCUMENTO_ELIMINADO')       OR define('ESTADO_DOCUMENTO_ELIMINADO','E');
defined('ESTADO_DOCUMENTO_ANULADO')       OR define('ESTADO_DOCUMENTO_ANULADO','N');

defined('ID_FORMA_PAGO_CONTADO') OR define('ID_FORMA_PAGO_CONTADO','1');
defined('ID_FORMA_PAGO_CREDITO') OR define('ID_FORMA_PAGO_CREDITO','2');
defined('ID_FORMATO_IMPRESION') OR define('ID_FORMATO_IMPRESION',151);
defined('ID_PARAMETRO_FECHA_EMISION_MINIMO') OR define('ID_PARAMETRO_FECHA_EMISION_MINIMO',71);

defined('ID_MODULO_COMPRA') OR define('ID_MODULO_COMPRA',5);
defined('ID_MODULO_VENTA') OR define('ID_MODULO_VENTA',2);
defined('ID_MODULO_CAJA') OR define('ID_MODULO_CAJA',8);
defined('ID_MODULO_CUENTA_POR_COBRAR') OR define('ID_MODULO_CUENTA_POR_COBRAR',9);
defined('ID_MODULO_CUENTA_POR_PAGAR') OR define('ID_MODULO_CUENTA_POR_PAGAR',10);

defined('CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE') OR define('CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE',0);
defined('CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE_ENTREGA_NOTA_SALIDA') OR define('CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE_ENTREGA_NOTA_SALIDA',1);
defined('CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE_ENTREGA_NOTA_ENTRADA') OR define('CODIGO_ESTADO_PENDIENTE_NOTA_PENDIENTE_ENTREGA_NOTA_ENTRADA',2);
defined('CODIGO_ESTADO_COMPROBANTE_CON_NOTA_SALIDA') OR define('CODIGO_ESTADO_COMPROBANTE_CON_NOTA_SALIDA',3);

defined('ID_TIPO_SEDE_AGENCIA') OR define('ID_TIPO_SEDE_AGENCIA',1);
defined('ID_TIPO_SEDE_ALMACEN') OR define('ID_TIPO_SEDE_ALMACEN',2);
defined('ID_TIPO_EXISTENCIA_MERCADERIA') OR define('ID_TIPO_EXISTENCIA_MERCADERIA',1);
defined('ID_AFECTACION_IGV_GRAVADO') or define('ID_AFECTACION_IGV_GRAVADO',1);
defined('CODIGO_AFECTACION_IGV_GRAVADO') or define('CODIGO_AFECTACION_IGV_GRAVADO',10);
defined('CODIGO_AFECTACION_IGV_NO_GRAVADO') or define('CODIGO_AFECTACION_IGV_NO_GRAVADO',20);
defined('CODIGO_AFECTACION_IGV_INAFECTO') or define('CODIGO_AFECTACION_IGV_INAFECTO',30);

defined('ID_UNIDAD_MEDIDA_UNIDAD') OR define('ID_UNIDAD_MEDIDA_UNIDAD',58);
defined('ID_UNIDAD_MEDIDA_ZZ') OR define('ID_UNIDAD_MEDIDA_ZZ',66);

defined('ID_TIPO_SISTEMA_ISC_NO_AFECTO') OR define('ID_TIPO_SISTEMA_ISC_NO_AFECTO',0);
defined('CODIGO_TIPO_SISTEMA_ISC_NO_AFECTO') OR define('CODIGO_TIPO_SISTEMA_ISC_NO_AFECTO',"00");
defined('ID_FABRICANTE_NO_ESPECIFICADO') OR define('ID_FABRICANTE_NO_ESPECIFICADO','0');

defined('ID_TIPO_PRECIO_UNITARIO') OR define('ID_TIPO_PRECIO_UNITARIO',1);
defined('ID_TIPO_OPERACION_VENTA_INTERNA') OR define('ID_TIPO_OPERACION_VENTA_INTERNA',1);
defined('ID_TIPO_OPERACION_DETRACCION') OR define('ID_TIPO_OPERACION_DETRACCION',5);
defined('CODIGO_MEDIO_PAGO_DETRACCION_DEPOSITO') OR define('CODIGO_MEDIO_PAGO_DETRACCION_DEPOSITO', "001");

defined('CODIGO_TIPO_DOCUMENTO_NOTA_CREDITO') OR define('CODIGO_TIPO_DOCUMENTO_NOTA_CREDITO','07');
defined('CODIGO_TIPO_DOCUMENTO_NOTA_DEBITO') OR define('CODIGO_TIPO_DOCUMENTO_NOTA_DEBITO','08');
defined('CODIGO_TIPO_DOCUMENTO_GUIA_REMISION_REMITENTE') OR define('CODIGO_TIPO_DOCUMENTO_GUIA_REMISION_REMITENTE','09');

defined('CANTIDAD_LETRA_NUMERO_DOCUMENTO') OR define('CANTIDAD_LETRA_NUMERO_DOCUMENTO',8);
defined('NOMBRE_CONTROLADOR_DASHBOARD') OR define('NOMBRE_CONTROLADOR_DASHBOARD','cDashBoard');

defined('ID_SUB_TIPO_DOCUMENTO_BOLETA_TIPO_T') OR define('ID_SUB_TIPO_DOCUMENTO_BOLETA_TIPO_T', 1);
defined('ID_SUB_TIPO_DOCUMENTO_BOLETA_TIPO_Z') OR define('ID_SUB_TIPO_DOCUMENTO_BOLETA_TIPO_Z', 2);

defined('ID_TIPO_TRIBUTO_IGV') OR define('ID_TIPO_TRIBUTO_IGV',1);
defined('ID_TIPO_TRIBUTO_EXONERADO') OR define('ID_TIPO_TRIBUTO_EXONERADO',6);
defined('ID_TIPO_TRIBUTO_INAFECTO') OR define('ID_TIPO_TRIBUTO_INAFECTO',7);
defined('ID_TIPO_TRIBUTO_EXPORTACION') OR define('ID_TIPO_TRIBUTO_EXPORTACION',4);

defined('ID_PARAMETRO_RESTRICCION_CANTIDAD') OR define('ID_PARAMETRO_RESTRICCION_CANTIDAD',163);
defined('ID_PARAMETRO_DOCUMENTO_SALIDA_ZOFRA') OR define('ID_PARAMETRO_DOCUMENTO_SALIDA_ZOFRA',168);
defined('ID_PARAMETRO_TIPO_DOCUMENTO_SALIDA_ZOFRA') OR define('ID_PARAMETRO_TIPO_DOCUMENTO_SALIDA_ZOFRA',191);
defined('ID_PARAMETRO_DOCUMENTO_SALIDA_DUA') OR define('ID_PARAMETRO_DOCUMENTO_SALIDA_DUA',171);
defined('ID_PARAMETRO_DOCUMENTO_INGRESO') OR define('ID_PARAMETRO_DOCUMENTO_INGRESO',215);
defined('ID_PARAMETRO_DESCUENTO_UNITARIO_VENTA') OR define('ID_PARAMETRO_DESCUENTO_UNITARIO_VENTA',192);
defined('ID_PARAMETRO_STOCK_PRODUCTO_VENTA') OR define('ID_PARAMETRO_STOCK_PRODUCTO_VENTA',193);
defined('ID_PARAMETRO_DESCUENTO_ITEM_VENTA') OR define('ID_PARAMETRO_DESCUENTO_ITEM_VENTA',194);
defined('ID_PARAMETRO_TIPO_CAMBIO_ACTUAL') OR define('ID_PARAMETRO_TIPO_CAMBIO_ACTUAL',204);
defined('ID_PARAMETRO_BANNER_TIPO_VENTA') OR define('ID_PARAMETRO_BANNER_TIPO_VENTA',229);
defined('ID_PARAMETRO_TIPO_VENTA_DEFECTO') OR define('ID_PARAMETRO_TIPO_VENTA_DEFECTO',231);
defined('ID_PARAMETRO_OBSERVACION_DETALLE') OR define('ID_PARAMETRO_OBSERVACION_DETALLE',232);
defined('ID_PARAMETRO_MARCA_VENTA') OR define('ID_PARAMETRO_MARCA_VENTA',260);
defined('ID_PARAMETRO_VENTA_STOCK_NEGATIVO') OR define('ID_PARAMETRO_VENTA_STOCK_NEGATIVO',261);

defined('ID_PARAMETRO_CODIGO_BARRAS') OR define('ID_PARAMETRO_CODIGO_BARRAS',172);
defined('ID_PARAMETRO_LISTA_VENDEDOR') OR define('ID_PARAMETRO_LISTA_VENDEDOR',175);
defined('ID_PARAMETRO_ENVIO_EMAIL') OR define('ID_PARAMETRO_ENVIO_EMAIL',176);
defined('ID_TASA_IGV')       OR define('ID_TASA_IGV',42); //ParametroSistema

defined('NUM_FILAS_POR_PAGINA')       OR define('NUM_FILAS_POR_PAGINA',6);

defined('NOMBRE_ARCHIVO_REPORTE_PRODUCTOS_POR_FAMILIA')       OR define('NOMBRE_ARCHIVO_REPORTE_PRODUCTOS_POR_FAMILIA',89); //ParametroSistema
defined('NOMBRE_ARCHIVO_JASPER_PRODUCTOS_POR_FAMILIA')       OR define('NOMBRE_ARCHIVO_JASPER_PRODUCTOS_POR_FAMILIA',90); //ParametroSistema

defined('PARAMETRO_ID_CANTIDAD_IMPRESION') OR define('PARAMETRO_ID_CANTIDAD_IMPRESION', 256);
defined('MAX_NRO_ELEMENTOS_AUTOCOMPLETADO') OR define('MAX_NRO_ELEMENTOS_AUTOCOMPLETADO',10);

defined('ID_PARAMETRO_PESO_CHILENO') OR define('ID_PARAMETRO_PESO_CHILENO',292);

defined('NOMBRE_ARCHIVO_REPORTE_PRODUCTOS_POR_FAMILIA_CONSOLIDADO')       OR define('NOMBRE_ARCHIVO_REPORTE_PRODUCTOS_POR_FAMILIA_CONSOLIDADO',350); //ParametroSistema
defined('NOMBRE_ARCHIVO_JASPER_PRODUCTOS_POR_FAMILIA_CONSOLIDADO')       OR define('NOMBRE_ARCHIVO_JASPER_PRODUCTOS_POR_FAMILIA_CONSOLIDADO',351); //ParametroSistema


defined('RUTA_TAREA_GENERA_BACKUP_BASE_BATOS') OR define('RUTA_TAREA_GENERA_BACKUP_BASE_BATOS',APP_PATH.'application/libraries/etc/TareaGenerarBackupBaseDatos.bat');
defined('RUTA_CARPETA_ASSETS_BASE_DATOS') OR define('RUTA_CARPETA_ASSETS_BASE_DATOS',APP_PATH.'assets/data/basedatos');
defined('EXTENSION_SQL') OR define('EXTENSION_SQL',".sql");
defined('EXTENSION_7Z') OR define('EXTENSION_7Z',".7z");
defined('EXTENSION_ZIP') OR define('EXTENSION_ZIP',".zip");
defined('ID_PARAMETRO_SAUNA') OR define('ID_PARAMETRO_SAUNA',358);
defined('RUTA_TAREA_REACTIVA_TOMCAT') OR define('RUTA_TAREA_REACTIVA_TOMCAT',APP_PATH.'application/libraries/etc/TareaReactivaTomcat.bat');
defined('ID_PARAMETRO_MOSTRAR_CAMPO_MONTO_RECIBIDO') OR define('ID_PARAMETRO_MOSTRAR_CAMPO_MONTO_RECIBIDO',359);
defined('ID_PARAMETRO_TRANSPORTE_MERCANCIA') OR define('ID_PARAMETRO_TRANSPORTE_MERCANCIA',360);
defined('NOMBRE_NO_ESPECIFICADO') OR define('NOMBRE_NO_ESPECIFICADO',"NO ESPECIFICADO");
defined('ID_PARAMETRO_HORA_CONSULTA_VENTA') OR define('ID_PARAMETRO_HORA_CONSULTA_VENTA',397);
