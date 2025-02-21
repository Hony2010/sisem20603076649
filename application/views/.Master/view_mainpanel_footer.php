<div id="loader" style="display:none;">
  <img src="<?php echo base_url()?>assets/img/loading.gif" />
</div>
<script type="text/javascript">
  <?php
  $menu___tabs = $this->session->userdata('tab_'.LICENCIA_EMPRESA_RUC);
  $menu___item = $this->session->userdata('item_'.LICENCIA_EMPRESA_RUC);
  $menu___op_left = $this->session->userdata('op_left_'.LICENCIA_EMPRESA_RUC);
  $theme___pagination = $this->session->userdata('Usuario_'.LICENCIA_EMPRESA_RUC)["TemaSistema"];
  ?>
  var menu___tabs = '<?php echo $menu___tabs !="" ? $menu___tabs : "catalogos";?>';
  var menu___item = "<?php echo $menu___item !="" ? $menu___item : "0";?>";
  var menu___op_left = "<?php echo $menu___op_left !="" ? $menu___op_left : "0";?>";
  var theme___pagination = '<?php echo $theme___pagination == "right.light.css" ? "light-theme" : "dark-theme";?>';

  var ID_MONEDA_SOLES = 1;
  var ID_MONEDA_DOLARES = 2;
  var ID_FORMA_PAGO_CREDITO=2;
  var ID_FORMA_PAGO_CONTADO=1;
  var ESTADO_DIRECCION_CLIENTE = { SIN_CAMBIOS : "0" , INSERTADO : "1" , ACTUALIZADO : "2" , BORRADO : "3"  };

  var DETRACCION = { SIN_DETRACCION : '0', CON_DETACCION : '1'};
  var PAGADOR_DETRACCION = { CLIENTE : '1', PROVEEDOR : '2'};
  var TIPO_VENTA = { MERCADERIAS : '1', SERVICIOS : '2', ACTIVOS : '3', OTRASVENTAS : '4'};
  var TIPO_COMPRA = { MERCADERIAS : 1, GASTOS : 2, COSTOSAGREGADO : 3};
  var TECLA_ENTER = 13;
  var TECLA_TAB = 9;
  var TECLA_BLUR = "blur";

  var BASE_URL = "<?php echo base_url();?>";
  var SITE_URL_BASE = "<?php echo site_url(); ?>";
  var MAX_NRO_ELEMENTOS_AUTOCOMPLETADO = "<?php echo MAX_NRO_ELEMENTOS_AUTOCOMPLETADO?>";
  var SITE_URL = SITE_URL_BASE;
  <?php if(PARAMETRO_SERVIDOR_CLIENTE == 1) { ?>
    /*PARA EL SERVIDOR*/
    var SERVER_URL = BASE_URL; //Para el servidor se debe igualar a base_url
    //var SITE_URL = "<?php //echo site_url(); ?>";
  <?php } else { ?>
    /*PARA EL CLIENTE*/
    var SERVER_URL = "<?php echo APP_PATH_SERVER_URL;?>"; //Para el servidor se debe igualar a base_url
    //var SITE_URL = "<?php //echo APP_SITE_URL_SERVER; ?>";
  <?php }?>


  var CARPETA_IMAGENES = "assets/img/";
  var CARPETA_CLIENTE = "Cliente/";
  var CARPETA_PROVEEDOR = "Proveedor/";
  var CARPETA_TRANSPORTISTA = "Transportista/";
  var CARPETA_MERCADERIA = "Mercaderia/";
  var CARPETA_EMPRESA = "Empresa/";
  var CARPETA_EMPLEADO = "Empleado/";

  var SUB_CARPETA_CODIGO_BARRAS = "/CodigoBarra/";
  var CARPETA_SERVICIO = "Servicio/";
  var URL_JSON_MERCADERIAS = 'assets/data/mercaderia/mercaderias.json';
  var URL_RUTA_PRODUCTOS = 'assets/data/productos/';
  var URL_JSON_SERVICIOS = 'assets/data/servicio/servicios.json';
  var URL_JSON_ACTIVOSFIJOS = 'assets/data/activofijo/activosfijos.json';
  var URL_JSON_OTRASVENTAS = 'assets/data/otraventa/otrasventas.json';

  var URL_JSON_COSTOSAGREGADOS = 'assets/data/costoagregado/costosagregados.json';
  var URL_JSON_GASTOS = 'assets/data/gasto/gastos.json';
  
  var URL_JSON_EMPLEADOS = 'assets/data/empleado/empleados.json';
  var URL_JSON_CLIENTES = 'assets/data/cliente/clientes.json';
  var URL_JSON_PROVEEDORES = 'assets/data/proveedor/proveedores.json';
  var URL_JSON_TRANSPORTISTAS = 'assets/data/transportista/transportistas.json';
  var URL_JSON_RADIO_TAXI = 'assets/data/radiotaxi/radiotaxi.json';
  var URL_JSON_VEHICULOS = 'assets/data/vehiculos/vehiculos.json';
  var URL_JSON_USUARIOS = 'assets/data/usuario/usuarios.json';
  
  var URL_JSON_PENDIENTE_COBRANZA_CLIENTE = 'assets/data/pendientecobranzacliente/pendientescobranzacliente.json';
  var URL_JSON_COMPROBANTE_VENTA = 'assets/data/comprobanteventa/comprobantesventa.json';
  var URL_JSON_PROFORMA = 'assets/data/proforma/proformas.json';
  
  var SEPARADOR_CARPETA = "/";

  var opcionProceso  = { Nuevo : 1, Edicion : 2 , Anulacion :3 , Eliminacion : 4};
  var opcionNombreBtnAlerta  = { Aceptar : "Aceptar", Si : "Si", No :'No', Cancelar : "Cancelar", Cerrar: "Cerrar"};
  var NUMERO_ITEMS_BUSQUEDA_JSON_IMAGENES = 12;
  var TAMANO_SERIE_COMPRA = 4;
  var ID_TIPO_DOCUMENTO_IDENTIDAD_DNI = 2;
  var ID_TIPO_DOCUMENTO_IDENTIDAD_RUC = 4;
  var ID_TIPO_DOCUMENTO_IDENTIDAD_SIN_DOC = 7;
  var ID_TIPO_DOCUMENTO_IDENTIDAD_OTROS = 1;
  var VALOR_IGV = 0.18;
  var MAXIMO_DIGITOS_DNI = 8;
  var MAXIMO_DIGITOS_RUC = 11;
  var ID_TIPO_PERSONA_NATURAL = 2;
  var ID_TIPO_PERSONA_JURIDICA = 1;
  var ID_TIPO_PERSONA_NO_DOMICILIADO = 3;
  var NUMERO_DECIMALES_VENTA = 4;
  var NUMERO_DECIMALES_VALOR_UNITARIO_VENTA = 10;
  var NUMERO_DECIMALES_COMPRA = 2;
  var NUMERO_DECIMALES_COSTO_UNITARIO = 4;
  var CANTIDAD_DECIMALES_VENTA = {CANTIDAD : "4", PRECIO_UNITARIO : "4", DESCUENTO_UNITARIO : "2", SUB_TOTAL : "2", DESCUENTO_VALOR_UNITARIO: "2"}
  var CANTIDAD_DECIMALES_COMPRA = {CANTIDAD : "3", COSTO_UNITARIO : "6", PRECIO_UNITARIO : "6", SUB_TOTAL : "2", DESCUENTO_UNITARIO : "4", COSTO_UNITARIO_CALCULADO : "6"}
  var CANTIDAD_DECIMALES_GASTO = {COSTO_UNITARIO : "4",PRECIO_UNITARIO : "4", CANTIDAD : "4"}
  var CANTIDAD_DECIMALES_COSTO_AGREGADO = {PRECIO_UNITARIO :"4",COSTO_UNITARIO : "4", CANTIDAD: "2", COSTO_UNITARIO_CALCULADO: '4'}
  var CANTIDAD_DECIMALES_NOTA_CREDITO_COMPRA = {DESCUENTO_UNITARIO : "6"}
  var CANTIDAD_DECIMALES_GUIA_REMISION_REMITENTE = {CANTIDAD : "2", PESO: "2", PENDIENTE: "2"};
  var CANTIDAD_DECIMALES_INVENTARIO = {VALORUNITARIO : "4"};
  var CODIGO_AFECTACION_IGV_GRAVADO = '10';
  var CODIGO_AFECTACION_IGV_EXONERADO = '20';
  var CODIGO_AFECTACION_IGV_INAFECTO = '30';
  var ID_TIPO_DOCUMENTO_FACTURA = "2";
  var ID_TIPO_DOCUMENTO_BOLETA ="4";
  var ID_TIPO_DOCUMENTO_COMANDA ="86";
  var ID_TIPO_DOCUMENTO_PROFORMA ="90";
  var ID_TIPO_DOCUMENTO_TICKET ="87";
  var ID_TIPO_DOCUMENTO_ORDEN_PEDIDO ="78";
  var ID_TIPO_DOCUMENTO_GUIA ="10";
  var ID_TIPO_DOCUMENTO_NOTA_CREDITO = "8";
  var ID_TIPO_DOCUMENTO_NOTA_DEBITO = "9";
  var ID_TIPO_DOCUMENTO_NOTADEVOLUCION = <?php echo ID_TIPODOCUMENTO_NOTADEVOLUCION?>;
  var ID_SUBTIPO_DOCUMENTO_BOLETA_T = "1";
  var ID_SUBTIPO_DOCUMENTO_BOLETA_Z = "2";
  var ID_TIPO_DOCUMENTO_DUA = 48;
  var ID_TIPO_DOCUMENTO_INGRESO = 79;
  var ID_TIPO_DOCUMENTO_CONTROL = 80;
  var ID_TIPO_DOCUMENTO_RECIBO_INGRESO = "81";
  var ID_TIPO_DOCUMENTO_RECIBO_EGRESO = "82";
  var ID_TIPO_DOCUMENTO_VOUCHER_INGRESO = "83";
  var ID_TIPO_DOCUMENTO_VOUCHER_EGRESO = "84";
  var ID_TIPO_EXISTENCIA_MERCADERIA = 1;
  var ID_UNIDAD_MEDIDA_UND = 58;
  var ID_CLIENTES_VARIOS = 1;
  var RUC_CLIENTES_VARIOS = "00000000";
  var RZ_CLIENTES_VARIOS = "CLIENTES VARIOS";
  var TEXTO_CLIENTES_VARIOS = RUC_CLIENTES_VARIOS + " - " + RZ_CLIENTES_VARIOS;
  var ESTADO_PENDIENTE_NOTA = { PENDIENTE : "0" , PENDIENTE_ENTREGA_NOTA_SALIDA : "1", PENDIENTE_ENTREGA_NOTA_ENTRADA : "2"};
  var COMPROBANTE_CON_NOTA_SALIDA = 3;
  var ESTADO_CPE = {GENERADO : 'G',ACEPTADO: 'C',RECHAZADO : 'R', EN_PROCESO : 'P',NINGUNO:''};
  var ESTADO_PW = {PENDIENTE : 'D',ENVIADO: 'V',NINGUNO:''};
  var ESTADO = {ACTIVO:"A",ELIMINADO:"E",ANULADO:"N"};
  var CODIGO_ESTADO = {EMITIDO:1,MODIFICADO:2,ANULADO:3};
  var CODIGO_SERIE_BOLETA="B";
  var CODIGO_SERIE_FACTURA="F";
  var CODIGO_SERIE_GUIA="T";
  var CODIGO_TIPO_DOCUMENTO_BOLETA="03";
  var TIPO_PRECIO= { PRECIO_UNITARIO_INCLUIDO_IGV :'1', VALOR_REFERENCIAL_OPERACION_GRATUITA :'2'};
  var TIPO_AFECTACION_IGV = { GRAVADO :'1', EXONERADO :'2', INAFECTO :'3', EXPORTACION : '4' };
  var TIPO_SISTEMA_ISC = { NO_AFECTO : '0', SISTEMA_VALOR : '1' , APLICACION_MONTO_FIJO : '2' , SISTEMA_PRECIOS_VENTA_PUBLICO : '3' };
  var ORIGEN_MERCADERIA = { GENERAL : '1', DUA : '2' , ZOFRA : '3', TODOS:'0', GENERALVENTA:'4', DUAZOFRA:'5'}; //'TODOS'&'GENERALVENTA' = condicional para autoCompletadoProducto
  var TAMANO_MAXIMO_IMAGEN_KB = 1500;
  var TAMANO_MAXIMO_IMAGEN_MB = 1.5;
  var MONTO_MAXIMO_BOLETA = 700.00;
  var PENDIENTE_NOTA_ENTRADA = 2;
  var PENDIENTE_NOTA_SALIDA = 1;
  var PENDIENTE_NOTA_NINGUNA = 0;
  var CANTIDAD_DIGITOS_BARCODE = 13;
  var CODIGO_TIPO_DOCUMENTO_IDENTIDAD = { RUC : 6 , DNI : 1, TODOS : 0, SINDOCUMENTO : 0};
  var INDICADOR_APERTURA_CIERRE_CAJA = { APERTURA : "A" , CIERRE : "C"};
  var ESTADO_INDICADOR_IMPRESION = { PENDIENTE : 0 , ENVIADO : 1 };
  var INDICADOR_TIPO_IMPRESION = { CONSUMO : 0 , DETALLADO : 1 };
  var ESTADO_INDICADOR_PREVENTA = { COMANDA : '0', PRECUENTA : '1', POSTPRECUENTA: '2' };
  var INDICADOR_PAGADO = { PENDIENTE : '0', PARCIAL : '1', PAGADO: '2' };
  var INDICADOR_CANJEADO = { PENDIENTE : '0', PARCIAL : '1', CANJEADO: '2' };
  var INDICADOR_PRECUENTA = { NO : '0', SI : '1' };
  var TIPO_DESCUENTO = { PORCENTUAL : '0', MONTO : '1' };
  var SITUACION_MESA = { DISPONIBLE : 'D', OCUPADO : 'O' };
  var MEDIO_PAGO = { CHEQUE : '7'};
  var ESTADO_PRODUCTO = { VISIBLE : '1' };
  var ESTADO_CLIENTE = { VISIBLE : '1' };
  var ESTADO_PROVEEDOR = { VISIBLE : '1' };
  var ESTADO_EMPLEADO = { VISIBLE : '1' };
  var ESTADO_TRANSPORTISTA = { VISIBLE : '1' };

  var TASA_ICBP_POR_DEFECTO = 0;
  var ID_ICBP_POR_DEFECTO = 0;

  var FILTRO_CLIENTE_SIN_RUC = 'FILTRO_CLIENTE_SIN_RUC';
  var ID_ROL_GERENTE = 1;
  var ID_ROL_VENDEDOR = 4;
  var ID_ROL_CAJERO = 24;
  //TECLAS DE ACCESO RAPIDO
  var TECLA_REPAG = 33;
  var TECLA_ENTER = 13;
  var TECLA_G = 71; //GRABAR
  var TECLA_N = 78; //NUEVO
  var TECLA_B = 66; //BUSCAR
  var TECLA_AVPAG = 34;
  var TECLA_INICIO = 36;
  var TECLA_FIN = 35;
  var TECLA_ARROW_UP = 38;
  var TECLA_ARROW_DOWN = 40;
  var TECLA_ARROW_LEFT = 37;
  var TECLA_ARROW_RIGHT = 39;
  var TECLA_ESC = 27;
  var TECLA_ALT = 18;
  var TECLA_NUEVO = 78;
  var TECLA_X = 88; //Editar
  var TECLA_Y = 89; //Eliminar
  var TIPO_CONDICIONAL_CLIENTE = { TODOS: 1,  RUC : 1 };
  var INDICADOR_TIPO_COMPROBANTE = { INGRESO: 'I', SALIDA: 'S', TRANFERENCIA: 'T'}
  var ID_TIPO_OPERACION_SALDO_INICIAL = '1';
  var ID_TIPO_OPERACION_COBRANZA_CLIENTE = '3';
  var ID_TIPO_OPERACION_VENTA_CONTADO = '2';
  var ID_TIPO_OPERACION_COMPRA_CONTADO = '15';
  var ID_PARAMETRO_MODALIDAD_TRASLADO_PUBLICO = <?php echo ID_PARAMETRO_MODALIDAD_TRASLADO_PUBLICO;?>;
  var ID_PARAMETRO_MOTIVO_TRASLADO_VENTA = <?php echo ID_PARAMETRO_MOTIVO_TRASLADO_VENTA;?>;
  var ID_PARAMETRO_TIPO_CAMBIO_BUSQUEDA_AVANZADA_PRODUCTO = <?php echo ID_PARAMETRO_TIPO_CAMBIO_BUSQUEDA_AVANZADA_PRODUCTO;?>;
  var ID_PARAMETRO_MARGEN_UTILIDAD_BUSQUEDA_AVANZADA_PRODUCTO = <?php echo ID_PARAMETRO_MARGEN_UTILIDAD_BUSQUEDA_AVANZADA_PRODUCTO;?>;
  var ID_GENERO_MASCULINO = <?php echo "1";?>;
  var ID_GENERO_FEMENINO = <?php echo "2";?>;
 
  var BLOQUEO_DETALLE_TRANSPORTE = "0";

  var NOMBRE_EMPRESA = "<?php echo $this->session->userdata("Empresa_".LICENCIA_EMPRESA_RUC)["RazonSocial"]; ?>";
  var RUC_EMPRESA = "<?php echo $this->session->userdata("Empresa_".LICENCIA_EMPRESA_RUC)["CodigoEmpresa"]; ?>";

  var ID_TIPO_OPERACION_VENTA_INTERNA = <?php echo ID_TIPO_OPERACION_VENTA_INTERNA;?>;
  var ID_TIPO_OPERACION_DETRACCION = <?php echo ID_TIPO_OPERACION_DETRACCION;?>;
  var CODIGO_MEDIO_PAGO_DETRACCION_DEPOSITO = "<?php echo CODIGO_MEDIO_PAGO_DETRACCION_DEPOSITO;?>";

var url_menu = SERVER_URL +"assets/data/menu/menu-<?php echo $this->session->userdata('Usuario_'.LICENCIA_EMPRESA_RUC)['NombreUsuario']; ?>.json";//'assets/data/menu/__menu.json';
</script>

<link href="<?php echo base_url()?>assets/css/styles.css" rel="stylesheet">
<script src="<?php echo base_url()?>assets/libs/jquery/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/jquery-form-validator/jquery.form-validator.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/jquery.scrollbar/jquery.scrollbar.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/bootstrap-tabdrop/bootstrap-tabdrop.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/ionrangeslider/js/ion.rangeSlider.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/inputNumber/js/inputNumber.js"></script>
<script src="<?php echo base_url()?>assets/libs/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/datatables/js/jquery.dataTables.js"></script>
<script src="<?php echo base_url()?>assets/libs/datatables/js/dataTables.select.js"></script>
<script src="<?php echo base_url()?>assets/libs/datatables/js/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url()?>assets/libs/selectize/js/standalone/selectize.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="<?php echo base_url()?>assets/js/main.js"></script>
<script src="<?php echo base_url()?>assets/js/demo.js"></script>
<script src="<?php echo base_url()?>assets/js/iconos/all.js"></script>
<script src="<?php echo base_url()?>assets/libs/Accounting/accounting.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/Moment/moment.js"></script>
<script src="<?php echo base_url()?>assets/libs/dateformats/dateformats.js"></script>
<script src="<?php echo base_url()?>assets/libs/openfiledialog/openfiledialog.js"></script>

<script src="<?php echo base_url()?>assets/libs/jquery-ui/jquery-ui.js"></script>
<script src="<?php echo base_url()?>assets/libs/easyautocomplete/jquery.easy-autocomplete.js"></script>
<script src="<?php echo base_url()?>assets/libs/numeroaletras/NumeroALetras.js"></script>
<script src="<?php echo base_url()?>assets/libs/jquery-linked/jquery.linkedSelect.js"></script>
<script src="<?php echo base_url()?>assets/libs/jquery-issubstring/jquery-issubstring.js"></script>
<script src="<?php echo base_url()?>assets/libs/truncate-decimals/truncate-decimals.js"></script>
<script src="<?php echo base_url()?>assets/libs/load-json/jquery.loadJSON.js"></script>
<script src="<?php echo base_url()?>assets/libs/redondeo-decimal/redondeo-decimal.js"></script>
<script src="<?php echo base_url()?>assets/libs/jquery-barcode/jquery-barcode.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/generar-codigobarras/generar-codigobarras.js"></script>
<script src="<?php echo base_url()?>assets/libs/dom-to-image/dom-to-image.js"></script>
<script src="<?php echo base_url()?>assets/libs/html2canvas/html2canvas.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/jquery-dateFormat/dateFormat.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/bootstrap-notify/bootstrap-notify.js"></script>
<script src="<?php echo base_url()?>assets/libs/fabricjs/fabric.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/jquery-inputmask/jquery.inputmask.bundle.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/jquery-wizard/jquery.accordion-wizard.js"></script>
<!--FUNCIONES PARA LA NOTIFICACION-->
<script src="<?php echo base_url()?>assets/js/Clases.js"></script>
<script src="<?php echo base_url()?>assets/libs/inputAlert/inputAlert.js"></script>
<script src="<?php echo base_url()?>assets/libs/alertifyjs/alertify.js"></script>
<script src="<?php echo base_url()?>assets/libs/jnumeric/jnumeric.js"></script>
<script src="<?php echo base_url()?>assets/js/Funciones.js"></script>
<script src="<?php echo base_url()?>assets/libs/simplePagination/jquery.simplePagination.js"></script>
<script src="<?php echo base_url()?>assets/libs/codemirror/lib/codemirror.js"></script>
<script src="<?php echo base_url()?>assets/libs/codemirror/mode/xml/xml.js"></script>
<script src="<?php echo base_url()?>assets/libs/numeraljs/numeral.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/key-shorts/key-shorts.js"></script>
<script src="<?php echo base_url()?>assets/libs/js-xlsx/xlsx.full.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/defiant/defiant.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/jsonpath/jspath.min.js"></script>
<script src="<?php echo base_url()?>assets/libs/jsonh/jsonh.js"></script>
<script src="<?php echo base_url()?>assets/js/__menu.js"></script>
<script src="<?php echo base_url()?>assets/libs/printjs/print.min.js"></script>

<?php echo $view_footer_extension; ?>
