<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>
<DespatchAdvice xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2" 
                xmlns:ds="http://www.w3.org/2000/09/xmldsig#" 
                xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" 
                xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" 
                xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
>
<ext:UBLExtensions>  
</ext:UBLExtensions>
<cbc:UBLVersionID>${ublVersionIdSwf}</cbc:UBLVersionID>
<cbc:CustomizationID schemeAgencyName="PE:SUNAT">${CustomizationIdSwf}</cbc:CustomizationID>
<cbc:ID>${nroCdpSwf}</cbc:ID>
<cbc:IssueDate>${fechaEmision}</cbc:IssueDate>
<!-- Se condiciona la HoraEmision por si existe -->
<#if horaEmision??>
<cbc:IssueTime>${horaEmision}</cbc:IssueTime>
</#if>
<cbc:DespatchAdviceTypeCode>${tipCdpSwf}</cbc:DespatchAdviceTypeCode>
<cac:DespatchSupplierParty>
    <cbc:CustomerAssignedAccountID schemeID="${tipDocuEmisorSwf}">${nroRucEmisorSwf}</cbc:CustomerAssignedAccountID>
    <cac:Party>
        <cac:PartyIdentification>
            <cbc:ID schemeID="${tipDocuEmisorSwf}" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">${nroRucEmisorSwf}</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyLegalEntity>
            <cbc:RegistrationName><![CDATA[${razonSocialSwf}]]></cbc:RegistrationName>
        </cac:PartyLegalEntity>
    </cac:Party>
</cac:DespatchSupplierParty>
<#if !(CodigoMotivoTraslado='02' || CodigoMotivoTraslado='07' || CodigoMotivoTraslado='13') >
<cac:DeliveryCustomerParty>
    <cbc:CustomerAssignedAccountID schemeID="${CodigoTipoDocumentoDestinatario}">${NumeroDocumentoDestinatario}</cbc:CustomerAssignedAccountID>
    <cac:Party>
        <cac:PartyIdentification>
            <cbc:ID schemeID="${CodigoTipoDocumentoDestinatario}" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">${NumeroDocumentoDestinatario}</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyLegalEntity>
            <cbc:RegistrationName><![CDATA[${RazonSocialDestinatario}]]></cbc:RegistrationName>
        </cac:PartyLegalEntity>
    </cac:Party>
</cac:DeliveryCustomerParty>
</#if>
<#if (CodigoMotivoTraslado='02' || CodigoMotivoTraslado='07' || CodigoMotivoTraslado='13') >
<cac:SellerSupplierParty>
    <cbc:CustomerAssignedAccountID schemeID="${CodigoTipoDocumentoDestinatario}">${NumeroDocumentoDestinatario}</cbc:CustomerAssignedAccountID>
    <cac:Party>
        <cac:PartyIdentification>
            <cbc:ID schemeID="${CodigoTipoDocumentoDestinatario}" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">${NumeroDocumentoDestinatario}</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyLegalEntity>
            <cbc:RegistrationName><![CDATA[${RazonSocialDestinatario}]]></cbc:RegistrationName>
        </cac:PartyLegalEntity>
    </cac:Party>
</cac:SellerSupplierParty>
</#if>
<cac:Shipment>
    <cbc:ID>${NrolineaEnvio}</cbc:ID>
    <cbc:HandlingCode>${CodigoMotivoTraslado}</cbc:HandlingCode>
    <#if CodigoMotivoTraslado == "08" || CodigoMotivoTraslado == "09">
    <cbc:Information>${NombreMotivoTraslado}</cbc:Information>
    </#if>
    <cbc:GrossWeightMeasure unitCode="${CodigoUnidadMedidaPesoBrutoTotal}">${PesoBrutoTotal}</cbc:GrossWeightMeasure>
    <#if IndicadorTransbordo != "0">
    <!-- <cbc:SplitConsignmentIndicator>${DenominacionEnvioTransbordo}</cbc:SplitConsignmentIndicator> -->
    </#if>    
   
    <cac:ShipmentStage>
        <cbc:TransportModeCode>${CodigoModalidadTraslado}</cbc:TransportModeCode>
        <cac:TransitPeriod>
            <cbc:StartDate>${FechaInicioTraslado}</cbc:StartDate>
        </cac:TransitPeriod>
        <!--POR CODIGO MODALIDAD 01 - TRASLADO PUBLICO-->
        <#if CodigoModalidadTraslado = "01" && IndicadorTransbordo != "0" >
        <cac:CarrierParty>
            <cac:PartyIdentification>
                <cbc:ID schemeID="${CodigoTipoDocumentoTransportista}">${NumeroDocumentoTransportista}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[${RazonSocialTransportista}]]></cbc:Name>
            </cac:PartyName>
            <cac:PartyLegalEntity>
              <cbc:RegistrationName><![CDATA[${RazonSocialTransportista}]]></cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:CarrierParty>
        </#if>
        <!--POR CODIGO MODALIDAD TRASLADO PRIVADO-->
        <#if CodigoModalidadTraslado = "02">
        <cac:TransportMeans>
            <cac:RoadTransport>
                <cbc:LicensePlateID><![CDATA[${NumeroPlaca}]]></cbc:LicensePlateID>
            </cac:RoadTransport>
        </cac:TransportMeans>      
        </#if>
         <!-- CONDUCTOR PRINCIPAL -->
        <#if ( !(CodigoModalidadTraslado = "01") && NumeroPlaca !='') >
        <cac:DriverPerson>
            <cbc:ID schemeID="${CodigoTipoDocumentoTransportista}">${NumeroDocumentoTransportista}</cbc:ID>
             <cbc:FirstName>${RazonSocialTransportista}</cbc:FirstName>                
             <cbc:FamilyName>${RazonSocialTransportista}</cbc:FamilyName>
             <cbc:JobTitle>Principal</cbc:JobTitle>            
            <cac:IdentityDocumentReference>
                <cbc:ID>${NumeroLicenciaConducir}</cbc:ID>
            </cac:IdentityDocumentReference>                                    
        </cac:DriverPerson>        
        </#if>
           
    </cac:ShipmentStage>
    <cac:Delivery>        
        <#if !(CodigoMotivoTraslado='18') >
        <cac:DeliveryAddress>            
            <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">${CodigoUbigeoPuntoLlegada}</cbc:ID>              <cac:AddressLine>
                <cbc:Line>${DireccionPuntoLlegada}</cbc:Line>
            </cac:AddressLine>
        </cac:DeliveryAddress>
        </#if>                              
        <cac:Despatch>
            <!-- DIRECCION DEL PUNTO DE PARTIDA -->
            <cac:DespatchAddress>
                <!-- UBIGEO DE PARTIDA -->
                <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">${CodigoUbigeoPuntoPartida}</cbc:ID>
                <!-- CODIGO DE ESTABLECIMIENTO ANEXO DE PARTIDA -->
                <cbc:AddressTypeCode listID="${nroRucEmisorSwf}" listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0000</cbc:AddressTypeCode>
                <!-- DIRECCION COMPLETA Y DETALLADA DE PARTIDA -->
                <cac:AddressLine>
                    <cbc:Line>${DireccionPuntoPartida}</cbc:Line>
                </cac:AddressLine>                                
            </cac:DespatchAddress>                
        </cac:Despatch>
    </cac:Delivery>
    <#if ( (CodigoModalidadTraslado = "01" || CodigoModalidadTraslado = "02") && NumeroPlaca !='' ) >
      <cac:TransportHandlingUnit>
            <cac:TransportEquipment>
                <cbc:ID>${NumeroPlaca}</cbc:ID>
            </cac:TransportEquipment>
        </cac:TransportHandlingUnit>
    </#if>
    <!--POR CODIGO MOTIVO TRASLADO - IMPORTACION-->
    <#if CodigoMotivoTraslado = "08">
    <cac:TransportHandlingUnit>
        <cac:TransportEquipment>
            <cbc:ID>${NumeroContenedor}</cbc:ID>
        </cac:TransportEquipment>
    </cac:TransportHandlingUnit>
    </#if>
<!--
    <cac:OriginAddress>
        <cbc:ID>${CodigoUbigeoPuntoPartida}</cbc:ID>
        <cbc:StreetName><![CDATA[${DireccionPuntoPartida}]]></cbc:StreetName>
    </cac:OriginAddress> -->

    <#if CodigoMotivoTraslado = "08" || CodigoMotivoTraslado = "09">
    <cac:FirstArrivalPortLocation>
        <cbc:ID>${CodigoPuerto}</cbc:ID>
    </cac:FirstArrivalPortLocation>
    </#if>

   
</cac:Shipment>
<#list listaDetalle as detalle>
<cac:DespatchLine>
    <cbc:ID>${detalle.lineaSwf}</cbc:ID>
    <cbc:DeliveredQuantity unitCode="${detalle.unidadMedida}">${detalle.cantItem}</cbc:DeliveredQuantity>
    <cac:OrderLineReference>
        <cbc:LineID>${detalle.lineaSwf}</cbc:LineID>
    </cac:OrderLineReference>
    <cac:Item>
        <cbc:Description>            
            <![CDATA[${detalle.NombreProducto}]]>            
        </cbc:Description>
        <cac:SellersItemIdentification>
            <cbc:ID>${detalle.CodigoItem}</cbc:ID>
        </cac:SellersItemIdentification>
    </cac:Item>
</cac:DespatchLine>
</#list> 
</DespatchAdvice>