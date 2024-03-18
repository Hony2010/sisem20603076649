<?xml version="1.0" encoding="windows-1250"?>
<SummaryDocuments
  xmlns:ns12="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2" xmlns:ns8="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:ns6="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"  xmlns:ns11="urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1" xmlns:ns14="urn:sunat:names:specification:ubl:peru:schema:xsd:Perception-1" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:ns13="urn:sunat:names:specification:ubl:peru:schema:xsd:Retention-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ns7="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1">
<ext:UBLExtensions>
<ext:UBLExtension>
<ext:ExtensionContent></ext:ExtensionContent>
</ext:UBLExtension>
</ext:UBLExtensions>
 <cbc:UBLVersionID>${ublVersionIdSwf}</cbc:UBLVersionID>
 <cbc:CustomizationID>${CustomizationIdSwf}</cbc:CustomizationID>
 <cbc:ID>${idResumenDiario}</cbc:ID>
 <cbc:ReferenceDate>${fechaEmision}</cbc:ReferenceDate>
 <cbc:IssueDate>${fechaGeneracion}</cbc:IssueDate>
 <cac:Signature>
    <cbc:ID>${identificadorFirmaSwf}</cbc:ID>
    <cac:SignatoryParty>
        <cac:PartyIdentification>
          <cbc:ID>${nroRucEmisorSwf}</cbc:ID>
        </cac:PartyIdentification>
        <cac:PartyName>
          <cbc:Name><![CDATA[${nombreComercialSwf}]]></cbc:Name>
        </cac:PartyName>
    </cac:SignatoryParty>
    <cac:DigitalSignatureAttachment>
        <cac:ExternalReference>
          <cbc:URI>${URIidentificadorFirmaSwf}</cbc:URI>
        </cac:ExternalReference>
    </cac:DigitalSignatureAttachment>
 </cac:Signature>

 <cac:AccountingSupplierParty>
  <cbc:CustomerAssignedAccountID>${nroRucEmisorSwf}</cbc:CustomerAssignedAccountID>
  <cbc:AdditionalAccountID>${tipDocuEmisorSwf}</cbc:AdditionalAccountID>
  <cac:Party>
    <cac:PartyLegalEntity>
      <cbc:RegistrationName><![CDATA[${razonSocialSwf}]]></cbc:RegistrationName>
    </cac:PartyLegalEntity>
  </cac:Party>
  </cac:AccountingSupplierParty>
  <#list listaResumen as resumen>
  <sac:SummaryDocumentsLine>
    <cbc:LineID>${resumen.linea}</cbc:LineID>
    <cbc:DocumentTypeCode>${resumen.tipoDocumento}</cbc:DocumentTypeCode>
    <cbc:ID>${resumen.serieNumeroDocumento}</cbc:ID>
    <#assign importeTotal = resumen.montoTotal?number>
    <#if (importeTotal > 700.00) >
    <cac:AccountingCustomerParty>
      <cbc:CustomerAssignedAccountID>${resumen.numeroDocumentoCliente}</cbc:CustomerAssignedAccountID>
      <cbc:AdditionalAccountID>${resumen.codigoTipoIdentidadCliente}</cbc:AdditionalAccountID>
    </cac:AccountingCustomerParty>
    </#if>
    <#if resumen.tipoDocumento = '07' || resumen.tipoDocumento ='08' >
    <cac:BillingReference>
      <cac:InvoiceDocumentReference>
        <cbc:ID>${resumen.serieNumeroDocumentoReferencia}</cbc:ID>
        <cbc:DocumentTypeCode>${resumen.tipoDocumentoReferencia}</cbc:DocumentTypeCode>
      </cac:InvoiceDocumentReference>
    </cac:BillingReference>
    </#if>

    <#if resumen.indicadorPercepcion = "S" && resumen.montoPercepcion > 0 >
    <sac:SUNATPerceptionSummaryDocumentReference>
      <sac:SUNATPerceptionSystemCode>
          ${resumen.codigoPercepcion}
      </sac:SUNATPerceptionSystemCode>
      <sac:SUNATPerceptionPercent>
          ${resumen.porcentajePercepcion}
      </sac:SUNATPerceptionPercent>
      <cbc:TotalInvoiceAmount currencyID="${resumen.moneda}">
          ${resumen.montoPercepcion}
      </cbc:TotalInvoiceAmount>
      <sac:SUNATTotalCashed currencyID="${resumen.moneda}">
        ${resumen.montoTotalCobrado}
      </sac:SUNATTotalCashed>
      <cbc:TaxableAmount>
        ${resumen.montoBaseImponiblePercepcion}
      </cbc:TaxableAmount>
    </sac:SUNATPerceptionSummaryDocumentReference>
    </#if>
    <cac:Status>
      <cbc:ConditionCode>${resumen.codigoEstado}</cbc:ConditionCode>
    </cac:Status>
    <sac:TotalAmount currencyID="${resumen.moneda}">${resumen.montoTotal}</sac:TotalAmount>

    <#assign totalOpGravada = resumen.montoPagadoOpGravada?number>
    <#if (totalOpGravada > 0) >
    <sac:BillingPayment>
      <cbc:PaidAmount  currencyID="${resumen.moneda}">${resumen.montoPagadoOpGravada}</cbc:PaidAmount>
      <cbc:InstructionID>${resumen.instructionIDOpGravada}</cbc:InstructionID>
    </sac:BillingPayment>
    </#if>
    <#assign totalOpExonerada= resumen.montoPagadoOpExonerada?number>
    <#if (totalOpExonerada > 0)>
    <sac:BillingPayment>
      <cbc:PaidAmount currencyID="${resumen.moneda}">${resumen.montoPagadoOpExonerada}</cbc:PaidAmount>
      <cbc:InstructionID>${resumen.instructionIDOpExonerada}</cbc:InstructionID>
    </sac:BillingPayment>
    </#if>
    <#assign totalOpInafecto= resumen.montoPagadoOpInafecto?number>
    <#if (totalOpInafecto > 0)>
    <sac:BillingPayment>
      <cbc:PaidAmount currencyID="${resumen.moneda}">${resumen.montoPagadoOpInafecto}</cbc:PaidAmount>
      <cbc:InstructionID>${resumen.instructionIDOpInafecto}</cbc:InstructionID>
    </sac:BillingPayment>
    </#if>
    <#assign totalOpGratuita= resumen.montoPagadoOpGratuita?number>
    <#if (totalOpGratuita > 0)>
    <sac:BillingPayment>
      <cbc:PaidAmount  currencyID="${resumen.moneda}">${resumen.montoPagadoOpGratuita}</cbc:PaidAmount>
      <cbc:InstructionID>${resumen.instructionIDOpGratuita}</cbc:InstructionID>
    </sac:BillingPayment>
    </#if>

    <#assign indicadorcargo = resumen.indicadorCargo?string>
    <#if (indicadorcargo = 'true')>
    <cac:AllowanceCharge>
      <cbc:ChargeIndicator>${resumen.indicadorCargo}</cbc:ChargeIndicator>
      <cbc:Amount currencyID="${resumen.moneda}">${resumen.montoCargo}</cbc:Amount>
    </cac:AllowanceCharge>
    </#if>

    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="${resumen.moneda}">${resumen.montoTasaIGV}</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxAmount currencyID="${resumen.moneda}">${resumen.montoTasaIGV}</cbc:TaxAmount>
        <cac:TaxCategory>
          <cac:TaxScheme>
            <cbc:ID>${resumen.idTasaIGV}</cbc:ID>
            <cbc:Name><![CDATA[${resumen.nombreTasaIGV}]]></cbc:Name>
            <cbc:TaxTypeCode>${resumen.codigoTasaIGV}</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>
    </cac:TaxTotal>

    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="${resumen.moneda}">${resumen.montoTasaISC}</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxAmount currencyID="${resumen.moneda}">${resumen.montoTasaISC}</cbc:TaxAmount>
        <cac:TaxCategory>
          <cac:TaxScheme>
            <cbc:ID>${resumen.idTasaISC}</cbc:ID>
            <cbc:Name><![CDATA[${resumen.nombreTasaISC}]]></cbc:Name>
            <cbc:TaxTypeCode>${resumen.codigoTasaISC}</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>
    </cac:TaxTotal>

    <cac:TaxTotal>
      <cbc:TaxAmount currencyID="${resumen.moneda}">${resumen.montoTasaOTROS}</cbc:TaxAmount>
      <cac:TaxSubtotal>
        <cbc:TaxAmount currencyID="${resumen.moneda}">${resumen.montoTasaOTROS}</cbc:TaxAmount>
        <cac:TaxCategory>
          <cac:TaxScheme>
            <cbc:ID>${resumen.idTasaOTROS}</cbc:ID>
            <cbc:Name><![CDATA[${resumen.nombreTasaOTROS}]]></cbc:Name>
            <cbc:TaxTypeCode>${resumen.codigoTasaOTROS}</cbc:TaxTypeCode>
          </cac:TaxScheme>
        </cac:TaxCategory>
      </cac:TaxSubtotal>
    </cac:TaxTotal>

  </sac:SummaryDocumentsLine>
  </#list>
</SummaryDocuments>
