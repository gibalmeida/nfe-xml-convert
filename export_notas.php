<?php

/**
 * Este script (simples mais útil) lê uma pasta contendo arquivos de NFes
 * no formato XML e gera em sua saída dados em formato CSV ou SQL (INSERTS).
 * (O CSV você poderá converter em uma planilha.)
 * 
 * Este script foi baseado no layout da NFe versão 4.00, conforme
 * documentação na pasta doc-nfe.
 * 
 * Nota(s): 
 * - foi desconsiderado notas com os campos de ICMS para o Simples
 * Nacional (eu não precisava para o trabalho que o script foi
 * criado, mas se você precisar disso, faça um fork deste projeto;
 * verifique o layout da NFe e inclua; depois faça um PR no meu
 * projeto... vamos ajudar outras pessoas ;) ).
 */

include_once("utils.php");
include_once("tipos.php");
include_once("csvPrinter.php");
include_once("sqlPrinter.php");

$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . "nfes/notas"; // informe a pasta onde se encontrar os arquivos das NFes em formato XML
$files = glob($dir . DIRECTORY_SEPARATOR . "*.xml");

// Formato de saída CSV ou SQL (INSERT QUERIES)
$printFormat = "SQL";
$fieldsList = explode(
	";",
	"nota;" .
		"chave;" .
		"emissao;" .
		"versao;" .
		"natOp;" .
		"tpNF;" .
		"idDest;" .
		"tpEmis;" .
		"finNFe;" .
		"indFinal;" .
		"emitente-nome;" .
		"destinatario-nome;" .
		"indIEDest;" .
		"status;" .
		"motivo-status;" .
		"nItem;" .
		"cProd;" .
		"cEAN;" .
		"xProd;" .
		"ncm;" .
		"nve;" .
		"cest;" .
		"indEscala;" .
		"CNPJFab;" .
		"cBenef;" .
		"extipi;" .
		"cfop;" .
		"uCom;" .
		"qCom;" .
		"vUnCom;" .
		"vProd;" .
		"cEANTrib;" .
		"uTrib;" .
		"qTrib;" .
		"vUnTrib;" .
		"vFrete;" .
		"vSeg;" .
		"vDesc;" .
		"vOutro;" .
		"indTot;" .
		"icms-elem;icms-origem;icms-CST;icms-modBC;icms-vBC;icms-pRedBC;icms-pICMS;icms-vICMS;icms-vBCFCP;icms-pFCP;icms-vFCP;icms-modBCST;icms-pMVAST;icms-pRedBCST;icms-vBCST;icms-pICMSST;icms-vICMSST;icms-vBCFCPST;icms-pFCPST;icms-vFCPST;icms-vICMSDeson;icms-motDesICMS;icms-vICMSOp;icms-pDif;icms-vICMSDif;icms-pBCOp;icms-UFST;icms-vBCSTRet;icms-pST;icms-vICMSSubstituto;icms-vICMSSTRet;icms-vBCFCPSTRet;icms-pFCPSTRet;icms-vFCPSTRet;icms-vBCSTDest;icms-vICMSSTDest;icms-pRedBCEfet;icms-vBCEfet;icms-pICMSEfet;icms-vICMSEfet;" .
		"ipi-elem;ipi-CST;ipi-vBC;ipi-pIPI;ipi-qUnid;ipi-vUnid;ipi-vIPI;" .
		"pis-elem;pis-CST;pis-vBC;pis-pPIS;pis-qBCProd;pis-vAliqProd;pis-vPIS;" .
		"pisSt-vBC;pisSt-pPIS;pisSt-qBCProd;pisSt-vAliqProd;pisSt-vPIS;" .
		"cofins-elem;cofins-CST;cofins-vBC;cofins-pCOFINS;cofins-qBCProd;cofins-vAliqProd;cofins-vCOFINS;" .
		"cofinsSt-vBC;cofinsSt-pCOFINS;cofinsSt-qBCProd;cofinsSt-vAliqProd;cofinsSt-vCOFINS;" .
		"LINK"
);

/**
 * @var Printer $printer
 */
$printer = ($printFormat == "CSV" ? new CSVPrinter($fieldsList) : new SQLPrinter($fieldsList));

$printer->printHeader();
foreach ($files as $file) {
	$xml = simplexml_load_file($file);
	// if ($xml->NFe->infNFe->ide->tpNF == 0) { // Tipo do Documento Fiscal (0 - entrada; 1 - saída)
	if (isset($xml->protNFe->infProt->cStat) && $xml->protNFe->infProt->cStat == 100) { // 100 = Autorizado o uso da NF-e
		processXml($xml, $file, $printer);
	}
}

$printer->printFooter();

function processXml(&$xml, &$file, &$printer)
{

	$ide = &$xml->NFe->infNFe->ide; // identificação da NF-e
	$emit = &$xml->NFe->infNFe->emit; // Identificação do emitente
	$dest = &$xml->NFe->infNFe->dest; // Identificação do Destinatário
	$det = &$xml->NFe->infNFe->det; // Dados dos detalhes da NF-e

	foreach ($det as $item) {
		$prod = &$item->prod; // Dados dos produtos e serviços da NF-e
		$imposto = &$item->imposto; // Tributos incidentes nos produtos ou serviços da NF-e
		// $vTotTrib = &$imposto->vTotTrib; // Valor estimado total de impostos federais, estaduais e municipais
		$icms00 = &$imposto->ICMS->ICMS00; // Tributação pelo ICMS 00 - Tributada integralmente
		$icms10 = &$imposto->ICMS->ICMS10; // Tributação pelo ICMS 10 - Tributada e com cobrança do ICMS por substituição tributária
		$icms20 = &$imposto->ICMS->ICMS20; // Tributção pelo ICMS 20 - Com redução de base de cálculo
		$icms30 = &$imposto->ICMS->ICMS30; // Tributação pelo ICMS 30 - Isenta ou não tributada e com cobrança do ICMS por substituição tributária
		$icms40 = &$imposto->ICMS->ICMS40; // Tributação pelo ICMS 40 - Isenta 41 - Não tributada 50 - Suspensão
		$icms51 = &$imposto->ICMS->ICMS51; // Tributção pelo ICMS 51 - Diferimento A exigência do preenchimento das informações do ICMS diferido fica à critério de cada UF.
		$icms60 = &$imposto->ICMS->ICMS60; // Tributação pelo ICMS 60 - ICMS cobrado anteriormente por substituição tributária
		$icms70 = &$imposto->ICMS->ICMS70; // Tributação pelo ICMS 70 - Com redução de base de cálculo e cobrança do ICMS por substituição tributária
		$icms90 = &$imposto->ICMS->ICMS90; // Tributação pelo ICMS 90 - Outras
		$icmsPart = &$imposto->ICMS->ICMSPart; // Partilha do ICMS entre a UF de origem e UF de destino ou a UF definida na legislação Operação interestadual para consumidor final com partilha do ICMS devido na operação entre a UF de origem e a UF do destinatário ou ou a UF definida na legislação. (Ex. UF da concessionária de entrega do veículos
		$icmsST = &$imposto->ICMS->ICMSST; // Grupo de informação do ICMSST devido para a UF de destino, nas operações interestaduais de produtos que tiveram retenção antecipada de ICMS por ST na UF do remetente. Repasse via Substituto Tributário.

		$ipiTrib = &$imposto->IPI->IPITrib;
		$ipiNT = &$imposto->IPI->IPINT; // Por dedução, suponho que NT seja Não Tributável, já que IPITrib é tributável

		$pisAliq = &$imposto->PIS->PISAliq;
		$pisQtde = &$imposto->PIS->PISQtde;
		$pisNT = &$imposto->PIS->PISNT;
		$pisOutr = &$imposto->PIS->PISOutr;
		$pisST = &$imposto->PISST; // Dados do PIS Substituição Tributária

		$cofinsAliq = &$imposto->COFINS->COFINSAliq;
		$cofinsQtde = &$imposto->COFINS->COFINSQtde;
		$cofinsNT = &$imposto->COFINS->COFINSNT;
		$cofinsOutr = &$imposto->COFINS->COFINSOutr;
		$cofinsST = &$imposto->COFINSST; // Dados do COFINS da Substituição Tributaria

		$dtEmissao = isset($ide->dEmi) ? // Na versão 2.00 da NFe a data de emissão está no elemento dEmi
			$ide->dEmi :
			substr($ide->dhEmi, 0, 10); // Data e Hora de emissão do Documento Fiscal (AAAA-MM-DDThh:mm:ssTZD) ex.: 2012-09-01T13:00:00-03:00

		if (!empty($icms00)) {
			// Tributação pelo ICMS 00 - Tributada integralmente
			$icmsCols = textField("ICMS00") .
				textField(Torig($icms00->orig)) . // origem da mercadoria: 0 - Nacional 1 - Estrangeira - Importação direta 2 - Estrangeira - Adquirida no mercado interno
				textField(icmsCST($icms00->CST)) . // Tributção pelo ICMS 00 - Tributada integralmente
				textField(modBC($icms00->modBC)) . // Modalidade de determinação da BC do ICMS: 0 - Margem Valor Agregado (%); 1 - Pauta (valor); 2 - Preço Tabelado Máximo (valor); 3 - Valor da Operação.
				numField($icms00->vBC) . // Valor da BC do ICMS
				nullField() .  // pRedBC;
				numField($icms00->pICMS) . // Alíquota do ICMS
				numField($icms00->vICMS) . // Valor do ICMS
				nullField() .  // vBCFCP;
				numField($icms00->pFCP) . // Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).
				numField($icms00->vFCP) . // Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP)
				nullField() .  // modBCST;
				nullField() .  // pMVAST;
				nullField() .  // pRedBCST;
				nullField() .  // vBCST;
				nullField() .  // pICMSST;
				nullField() .  // vICMSST;
				nullField() .  // vBCFCPST;
				nullField() .  // pFCPST;
				nullField() .  // vFCPST;
				nullField() .  // vICMSDeson;
				nullField() .  // motDesICMS;
				nullField() .  // vICMSOp;
				nullField() .  // pDif;
				nullField() .  // vICMSDif;
				nullField() .  // pBCOp;
				nullField() .  // UFST;
				nullField() .  // vBCSTRet;
				nullField() .  // pST;
				nullField() .  // vICMSSubstituto;
				nullField() .  // vICMSSTRet;
				nullField() .  // vBCFCPSTRet;
				nullField() .  // pFCPSTRet;
				nullField() .  // vFCPSTRet;
				nullField() .  // vBCSTDest;
				nullField() .  // vICMSSTDest;
				nullField() .  // pRedBCEfet;
				nullField() .  // vBCEfet;
				nullField() .  // pICMSEfet;
				nullField(); // vICMSEfet 			
		} elseif (!empty($icms10)) {
			// Tributação pelo ICMS 10 - Tributada e com cobrança do ICMS por substituição tributária
			$icmsCols = textField("ICMS10") .
				textField(Torig($icms10->orig)) . // origem da mercadoria: 0 - Nacional 1 - Estrangeira - Importação direta 2 - Estrangeira - Adquirida no mercado interno
				textField(icmsCST($icms10->CST)) . // 10 - Tributada e com cobrança do ICMS por substituição tributária
				textField(modBC($icms10->modBC)) . // Modalidade de determinação da BC do ICMS: 0 - Margem Valor Agregado (%); 1 - Pauta (valor); 2 - Preço Tabelado Máximo (valor); 3 - Valor da Operação.
				numField($icms10->vBC) . // Valor da BC do ICMS<
				nullField() .  // pRedBC;
				numField($icms10->pICMS) . // Alíquota do ICMS
				numField($icms10->vICMS) . // Valor do ICMS
				numField($icms10->vBCFCP) . // Valor da Base de cálculo do FCP.
				numField($icms10->pFCP) . // Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).
				numField($icms10->vFCP) . // Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP).
				textField(modBCST($icms10->modBCST)) . // Modalidade de determinação da BC do ICMS ST: 0 – Preço tabelado ou máximo sugerido; 1 - Lista Negativa (valor); 2 - Lista Positiva (valor); 3 - Lista Neutra (valor); 4 - Margem Valor Agregado (%); 5 - Pauta (valor) 6-Valor da Operação;
				numField($icms10->pMVAST) . // Percentual da Margem de Valor Adicionado ICMS ST
				numField($icms10->pRedBCST) . // Percentual de redução da BC ICMS ST
				numField($icms10->vBCST) . // Valor da BC do ICMS ST
				numField($icms10->pICMSST) . // Alíquota do ICMS ST
				numField($icms10->vICMSST) . // Valor do ICMS ST
				numField($icms10->vBCFCPST) . // Valor da Base de cálculo do FCP retido por substituicao tributaria
				numField($icms10->pFCPST) . //	Percentual de FCP retido por substituição tributária.
				numField($icms10->vFCPST) . //	Valor do FCP retido por substituição tributária.
				nullField() .  // vICMSDeson;
				nullField() .  // motDesICMS;
				nullField() .  // vICMSOp;
				nullField() .  // pDif;
				nullField() .  // vICMSDif;
				nullField() .  // pBCOp;
				nullField() .  // UFST;
				nullField() .  // vBCSTRet;
				nullField() .  // pST;
				nullField() .  // vICMSSubstituto;
				nullField() .  // vICMSSTRet;
				nullField() .  // vBCFCPSTRet;
				nullField() .  // pFCPSTRet;
				nullField() .  // vFCPSTRet;
				nullField() .  // vBCSTDest;
				nullField() .  // vICMSSTDest;
				nullField() .  // pRedBCEfet;
				nullField() .  // vBCEfet;
				nullField() .  // pICMSEfet;
				nullField(); // vICMSEfet 
		} elseif (!empty($icms20)) {
			// Tributção pelo ICMS 20 - Com redução de base de cálculo				
			$icmsCols = textField("ICMS20") .
				textField(icmsCST($icms20->CST)) . // Tributção pelo ICMS 20 - Com redução de base de cálculo
				textField(Torig($icms20->orig)) . // origem da mercadoria: 0 - Nacional 1 - Estrangeira - Importação direta 2 - Estrangeira - Adquirida no mercado interno
				textField(modBC($icms20->modBC)) . // Modalidade de determinação da BC do ICMS: 0 - Margem Valor Agregado (%); 1 - Pauta (valor); 2 - Preço Tabelado Máximo (valor); 3 - Valor da Operação.
				numField($icms20->vBC) . // Valor da BC do ICMS
				numField($icms20->pRedBC) . // Percentual de redução da BC
				numField($icms20->pICMS) . // Alíquota do ICMS
				numField($icms20->vICMS) . // Valor do ICMS
				numField($icms20->vBCFCP) . // Valor da Base de cálculo do FCP.
				numField($icms20->pFCP) . // Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).
				numField($icms20->vFCP) . // Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP).
				nullField() .  // modBCST;
				nullField() .  // pMVAST;
				nullField() .  // pRedBCST;
				nullField() .  // vBCST;
				nullField() .  // pICMSST;
				nullField() .  // vICMSST;
				nullField() .  // vBCFCPST;
				nullField() .  // pFCPST;
				nullField() .  // vFCPST;
				numField($icms20->vICMSDeson) . // Valor do ICMS de desoneração
				textField(motDesICMS($icms20->motDesICMS)) . // Motivo da desoneração do ICMS:3-Uso na agropecuária;9-Outros;12-Fomento agropecuário
				nullField() .  // vICMSOp;
				nullField() .  // pDif;
				nullField() .  // vICMSDif;
				nullField() .  // pBCOp;
				nullField() .  // UFST;
				nullField() .  // vBCSTRet;
				nullField() .  // pST;
				nullField() .  // vICMSSubstituto;
				nullField() .  // vICMSSTRet;
				nullField() .  // vBCFCPSTRet;
				nullField() .  // pFCPSTRet;
				nullField() .  // vFCPSTRet;
				nullField() .  // vBCSTDest;
				nullField() .  // vICMSSTDest;
				nullField() .  // pRedBCEfet;
				nullField() .  // vBCEfet;
				nullField() .  // pICMSEfet;
				nullField(); // vICMSEfet 			
		} elseif (!empty($icms30)) {
			// Tributação pelo ICMS 30 - Isenta ou não tributada e com cobrança do ICMS por substituição tributária
			$icmsCols = textField("ICMS30") .
				textField(Torig($icms30->orig)) . // origem da mercadoria: 0 - Nacional 1 - Estrangeira - Importação direta 2 - Estrangeira - Adquirida no mercado interno
				textField(icmsCST($icms30->CST)) . // 30 - Isenta ou não tributada e com cobrança do ICMS por substituição tributária
				nullField() .  // origem;
				nullField() .  // CST;
				nullField() .  // modBC;
				nullField() .  // vBC;
				nullField() .  // pRedBC;
				nullField() .  // pICMS;
				nullField() .  // vICMS;
				nullField() .  // vBCFCP;
				nullField() .  // pFCP;
				nullField() .  // vFCP;
				textField(modBCST($icms30->modBCST)) . // Modalidade de determinação da BC do ICMS ST: 0 – Preço tabelado ou máximo sugerido; 1 - Lista Negativa (valor); 2 - Lista Positiva (valor); 3 - Lista Neutra (valor); 4 - Margem Valor Agregado (%); 5 - Pauta (valor). 6 - Valor da Operação
				numField($icms30->pMVAST) . // Percentual da Margem de Valor Adicionado ICMS ST
				numField($icms30->pRedBCST) . // Percentual de redução da BC ICMS ST
				numField($icms30->vBCST) . // Valor da BC do ICMS ST
				numField($icms30->pICMSST) . // Alíquota do ICMS ST
				numField($icms30->vICMSST) . // Valor do ICMS ST
				numField($icms30->vBCFCPST) . // Valor da Base de cálculo do FCP.
				numField($icms30->pFCPST) . // Percentual de FCP retido por substituição tributária.
				numField($icms30->vFCPST) . // Valor do FCP retido por substituição tributária.
				numField($icms30->vICMSDeson) . // Valor do ICMS de desoneração
				textField(motDesICMS($icms30->motDesICMS)) . // Motivo da desoneração do ICMS:6-Utilitários Motocicleta AÁrea Livre;7-SUFRAMA;9-Outros
				nullField() .  // vICMSOp;
				nullField() .  // pDif;
				nullField() .  // vICMSDif;
				nullField() .  // pBCOp;
				nullField() .  // UFST;
				nullField() .  // vBCSTRet;
				nullField() .  // pST;
				nullField() .  // vICMSSubstituto;
				nullField() .  // vICMSSTRet;
				nullField() .  // vBCFCPSTRet;
				nullField() .  // pFCPSTRet;
				nullField() .  // vFCPSTRet;
				nullField() .  // vBCSTDest;
				nullField() .  // vICMSSTDest;
				nullField() .  // pRedBCEfet;
				nullField() .  // vBCEfet;
				nullField() .  // pICMSEfet;
				nullField(); // vICMSEfet 			
		} elseif (!empty($icms40)) {
			// Tributação pelo ICMS 40 - Isenta 41 - Não tributada 50 - Suspensão
			$icmsCols = textField("ICMS40") .
				textField(Torig($icms40->orig)) . // origem da mercadoria: 0 - Nacional 1 - Estrangeira - Importação direta 2 - Estrangeira - Adquirida no mercado interno
				textField(icmsCST($icms40->CST)) . // Tributação pelo ICMS 40 - Isenta 41 - Não tributada 50 - Suspensão
				nullField() .  // modBC;
				nullField() .  // vBC;
				nullField() .  // pRedBC;
				nullField() .  // pICMS;
				nullField() .  // vICMS;
				nullField() .  // vBCFCP;
				nullField() .  // pFCP;
				nullField() .  // vFCP;
				nullField() .  // modBCST;
				nullField() .  // pMVAST;
				nullField() .  // pRedBCST;
				nullField() .  // vBCST;
				nullField() .  // pICMSST;
				nullField() .  // vICMSST;
				nullField() .  // vBCFCPST;
				nullField() .  // pFCPST;
				nullField() .  // vFCPST;
				numField($icms40->vICMSDeson) . // O valor do ICMS será informado apenas nas operações com veículos beneficiados com a desoneração condicional do ICMS.
				textField(motDesICMS($icms40->motDesICMS)) . // Este campo será preenchido quando o campo anterior estiver preenchido. Informar o motivo da desoneração: 1 – Táxi; 3 – Produtor Agropecuário; 4 – Frotista/Locadora; 5 – Diplomático/Consular; 6 – Utilitários e Motocicletas da Amazônia Ocidental e Áreas de Livre Comércio (Resolução 714/88 e 790/94 – CONTRAN e suas alterações); 7 – SUFRAMA; 8 - Venda a órgão Público; 9 – Outros 10- Deficiente Condutor 11- Deficiente não condutor 16 - Olimpíadas Rio 2016 90 - Solicitado pelo Fisco
				nullField() .  // vICMSOp;
				nullField() .  // pDif;
				nullField() .  // vICMSDif;
				nullField() .  // pBCOp;
				nullField() .  // UFST;
				nullField() .  // vBCSTRet;
				nullField() .  // pST;
				nullField() .  // vICMSSubstituto;
				nullField() .  // vICMSSTRet;
				nullField() .  // vBCFCPSTRet;
				nullField() .  // pFCPSTRet;
				nullField() .  // vFCPSTRet;
				nullField() .  // vBCSTDest;
				nullField() .  // vICMSSTDest;
				nullField() .  // pRedBCEfet;
				nullField() .  // vBCEfet;
				nullField() .  // pICMSEfet;
				nullField(); // vICMSEfet 

		} elseif (!empty($icms51)) {
			// Tributção pelo ICMS 51 - Diferimento A exigência do preenchimento das informações do ICMS diferido fica à critério de cada UF.
			$icmsCols = textField("ICMS51") .
				textField(Torig($icms51->orig)) . // origem da mercadoria: 0 - Nacional 1 - Estrangeira - Importação direta 2 - Estrangeira - Adquirida no mercado interno
				textField(icmsCST($icms51->CST)) . // Tributção pelo ICMS 51 - Diferimento A exigência do preenchimento das informações do ICMS diferido fica à critério de cada UF.
				textField(modBC($icms51->modBC)) . // Modalidade de determinação da BC do ICMS: 0 - Margem Valor Agregado (%); 1 - Pauta (valor); 2 - Preço Tabelado Máximo (valor); 3 - Valor da Operação.
				numField($icms51->vBC) . // Valor da BC do ICMS
				numField($icms51->pRedBC) . // Percentual de redução da BC
				numField($icms51->pICMS) . // Alíquota do imposto
				numField($icms51->vICMS) . // Valor do ICMS
				numField($icms51->vBCFCP) . // Valor da Base de cálculo do FCP.
				numField($icms51->pFCP) . // Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).
				numField($icms51->vFCP) . // Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP).
				nullField() .  // modBCST;
				nullField() .  // pMVAST;
				nullField() .  // pRedBCST;
				nullField() .  // vBCST;
				nullField() .  // pICMSST;
				nullField() .  // vICMSST;
				nullField() .  // vBCFCPST;
				nullField() .  // pFCPST;
				nullField() .  // vFCPST;
				nullField() .  // vICMSDeson;
				nullField() .  // motDesICMS;
				numField($icms51->vICMSOp) . // Valor do ICMS da Operação
				numField($icms51->pDif) . // Percentual do diferemento
				numField($icms51->vICMSDif) . // Valor do ICMS da diferido
				nullField() .  // pBCOp;
				nullField() .  // UFST;
				nullField() .  // vBCSTRet;
				nullField() .  // pST;
				nullField() .  // vICMSSubstituto;
				nullField() .  // vICMSSTRet;
				nullField() .  // vBCFCPSTRet;
				nullField() .  // pFCPSTRet;
				nullField() .  // vFCPSTRet;
				nullField() .  // vBCSTDest;
				nullField() .  // vICMSSTDest;
				nullField() .  // pRedBCEfet;
				nullField() .  // vBCEfet;
				nullField() .  // pICMSEfet;
				nullField(); // vICMSEfet 			
		} elseif (!empty($icms60)) {
			// Tributação pelo ICMS 60 - ICMS cobrado anteriormente por substituição tributária
			$icmsCols = textField("ICMS60") .
				textField(Torig($icms60->orig)) . // origem da mercadoria: 0 - Nacional 1 - Estrangeira - Importação direta 2 - Estrangeira - Adquirida no mercado interno
				textField(icmsCST($icms60->CST)) . // Tributação pelo ICMS 60 - ICMS cobrado anteriormente por substituição tributária
				nullField() .  // modBC;
				nullField() .  // vBC;
				nullField() .  // pRedBC;
				nullField() .  // pICMS;
				nullField() .  // vICMS;
				nullField() .  // vBCFCP;
				nullField() .  // pFCP;
				nullField() .  // vFCP;
				nullField() .  // modBCST;
				nullField() .  // pMVAST;
				nullField() .  // pRedBCST;
				nullField() .  // vBCST;
				nullField() .  // pICMSST;
				nullField() .  // vICMSST;
				nullField() .  // vBCFCPST;
				nullField() .  // pFCPST;
				nullField() .  // vFCPST;
				nullField() .  // vICMSDeson;
				nullField() .  // motDesICMS;
				nullField() .  // vICMSOp;
				nullField() .  // pDif;
				nullField() .  // vICMSDif;
				nullField() .  // pBCOp;
				nullField() .  // UFST;
				numField($icms60->vBCSTRet) . // Valor da BC do ICMS ST retido anteriormente
				numField($icms60->pST) . // Aliquota suportada pelo consumidor final.
				numField($icms60->vICMSSubstituto) . // Valor do ICMS Próprio do Substituto cobrado em operação anterior
				numField($icms60->vICMSSTRet) . // Valor do ICMS ST retido anteriormente
				numField($icms60->vBCFCPSTRet) . // Valor da Base de cálculo do FCP retido anteriormente por ST.
				numField($icms60->pFCPSTRet) . // Percentual de FCP retido anteriormente por substituição tributária.
				numField($icms60->vFCPSTRet) . // Valor do FCP retido por substituição tributária.
				nullField() .  // vBCSTDest;
				nullField() .  // vICMSSTDest;
				numField($icms60->pRedBCEfet) . // Percentual de redução da base de cálculo efetiva.
				numField($icms60->vBCEfet) . // Valor da base de cálculo efetiva.
				numField($icms60->pICMSEfet) . // Alíquota do ICMS efetiva.
				numField($icms60->vICMSEfet); // Valor do ICMS efetivo.			

		} elseif (!empty($icms70)) {
			// Tributação pelo ICMS 70 - Com redução de base de cálculo e cobrança do ICMS por substituição tributária
			$icmsCols = textField("ICMS70") .
				textField(Torig($icms70->orig)) . // origem da mercadoria: 0 - Nacional 1 - Estrangeira - Importação direta 2 - Estrangeira - Adquirida no mercado interno
				textField(icmsCST($icms70->CST)) . // 70 - Com redução de base de cálculo e cobrança do ICMS por substituição tributária
				textField(modBC($icms70->modBC)) . // Modalidade de determinação da BC do ICMS: 0 - Margem Valor Agregado (%); 1 - Pauta (valor); 2 - Preço Tabelado Máximo (valor); 3 - Valor da Operação.
				numField($icms70->vBC) . // Valor da BC do ICMS
				numField($icms70->pRedBC) . // Percentual de redução da BC
				numField($icms70->pICMS) . // Alíquota do ICMS
				numField($icms70->vICMS) . // Valor do ICMS
				numField($icms70->vBCFCP) . // Valor da Base de cálculo do FCP.
				numField($icms70->pFCP) . // Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).
				numField($icms70->vFCP) . // Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP).
				textField(modBCST($icms70->modBCST)) . // Modalidade de determinação da BC do ICMS ST: 0 – Preço tabelado ou máximo sugerido; 1 - Lista Negativa (valor); 2 - Lista Positiva (valor); 3 - Lista Neutra (valor); 4 - Margem Valor Agregado (%); 5 - Pauta (valor). 6 - Valor da Operaçã
				numField($icms70->pMVAST) . // Percentual da Margem de Valor Adicionado ICMS ST
				numField($icms70->pRedBCST) . // Percentual de redução da BC ICMS ST
				numField($icms70->vBCST) . // Valor da BC do ICMS ST
				numField($icms70->pICMSST) . // Alíquota do ICMS ST
				numField($icms70->vICMSST) . // Valor do ICMS ST
				numField($icms70->vBCFCPST) . // Valor da Base de cálculo do FCP retido por substituição tributária.
				numField($icms70->pFCPST) . // Percentual de FCP retido por substituição tributária.
				numField($icms70->vFCPST) . // Valor do FCP retido por substituição tributária.
				numField($icms70->vICMSDeson) . // Valor do ICMS de desoneração
				textField(motDesICMS($icms70->motDesICMS)) . // Motivo da desoneração do ICMS:3-Uso na agropecuária;9-Outros;12-Fomento agropecuári
				nullField() .  // vICMSOp;
				nullField() .  // pDif;
				nullField() .  // vICMSDif;
				nullField() .  // pBCOp;
				nullField() .  // UFST;
				nullField() .  // vBCSTRet;
				nullField() .  // pST;
				nullField() .  // vICMSSubstituto;
				nullField() .  // vICMSSTRet;
				nullField() .  // vBCFCPSTRet;
				nullField() .  // pFCPSTRet;
				nullField() .  // vFCPSTRet;
				nullField() .  // vBCSTDest;
				nullField() .  // vICMSSTDest;
				nullField() .  // pRedBCEfet;
				nullField() .  // vBCEfet;
				nullField() .  // pICMSEfet;
				nullField(); // vICMSEfet 			

		} elseif (!empty($icms90)) {
			// Tributação pelo ICMS 90 - Outras
			$icmsCols = textField("ICMS90") .
				textField(Torig($icms90->orig)) . // origem da mercadoria: 0 - Nacional 1 - Estrangeira - Importação direta 2 - Estrangeira - Adquirida no mercado interno
				textField(icmsCST($icms90->CST)) . // Tributação pelo ICMS 90 - Outras
				textField(modBC($icms90->modBC)) . // Modalidade de determinação da BC do ICMS: 0 - Margem Valor Agregado (%); 1 - Pauta (valor); 2 - Preço Tabelado Máximo (valor); 3 - Valor da Operação.
				numField($icms90->vBC) . // Valor da BC do ICMS
				numField($icms90->pRedBC) . // Percentual de redução da BC
				numField($icms90->pICMS) . // Alíquota do ICMS
				numField($icms90->vICMS) . // Valor do ICMS
				numField($icms90->vBCFCP) . // Valor da Base de cálculo do FCP.
				numField($icms90->pFCP) . // Percentual de ICMS relativo ao Fundo de Combate à Pobreza (FCP).
				numField($icms90->vFCP) . // Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP).
				textField(modBCST($icms90->modBCST)) . // Modalidade de determinação da BC do ICMS ST: 0 – Preço tabelado ou máximo sugerido; 1 - Lista Negativa (valor); 2 - Lista Positiva (valor); 3 - Lista Neutra (valor); 4 - Margem Valor Agregado (%); 5 - Pauta (valor) 6 - Valor da Operação.
				numField($icms90->pMVAST) . // Percentual da Margem de Valor Adicionado ICMS ST
				numField($icms90->pRedBCST) . // Percentual de redução da BC ICMS ST<
				numField($icms90->vBCST) . // Valor da BC do ICMS ST
				numField($icms90->pICMSST) . // Alíquota do ICMS ST
				numField($icms90->vICMSST) . // Valor do ICMS ST
				numField($icms90->vBCFCPST) . // Valor da Base de cálculo do FCP.
				numField($icms90->pFCPST) . // Percentual de FCP retido por substituição tributária.
				numField($icms90->vFCPST) . // Valor do FCP retido por substituição tributária.
				numField($icms90->vICMSDeson) . // Valor do ICMS de desoneração
				textField(motDesICMS($icms90->motDesICMS)) . // Motivo da desoneração do ICMS:3-Uso na agropecuária;9-Outros;12-Fomento agropecuário
				nullField() .  // vICMSOp;
				nullField() .  // pDif;
				nullField() .  // vICMSDif;
				nullField() .  // pBCOp;
				nullField() .  // UFST;
				nullField() .  // vBCSTRet;
				nullField() .  // pST;
				nullField() .  // vICMSSubstituto;
				nullField() .  // vICMSSTRet;
				nullField() .  // vBCFCPSTRet;
				nullField() .  // pFCPSTRet;
				nullField() .  // vFCPSTRet;
				nullField() .  // vBCSTDest;
				nullField() .  // vICMSSTDest;
				nullField() .  // pRedBCEfet;
				nullField() .  // vBCEfet;
				nullField() .  // pICMSEfet;
				nullField(); // vICMSEfet 		

		} elseif (!empty($icmsPart)) {
			// Partilha do ICMS entre a UF de origem e UF de destino ou a UF definida na legislação Operação interestadual para consumidor final com partilha do ICMS devido na operação entre a UF de origem e a UF do destinatário ou ou a UF definida na legislação. (Ex. UF da concessionária de entrega do veículos)
			$icmsCols = textField("ICMSPart") .
				textField(Torig($icmsPart->orig)) . // origem da mercadoria: 0 - Nacional 1 - Estrangeira - Importação direta 2 - Estrangeira - Adquirida no mercado interno
				textField(icmsCST($icmsPart->CST)) . // Tributação pelo ICMS 10 - Tributada e com cobrança do ICMS por substituição tributária; 90 – Outros.
				textField(modBC($icmsPart->modBC)) . // Modalidade de determinação da BC do ICMS: 0 - Margem Valor Agregado (%); 1 - Pauta (valor); 2 - Preço Tabelado Máximo (valor); 3 - Valor da Operação.
				numField($icmsPart->vBC) . // Valor da BC do ICMS
				numField($icmsPart->pRedBC) . // Percentual de redução da BC
				numField($icmsPart->pICMS) . // Alíquota do ICMS
				numField($icmsPart->vICMS) . // Valor do ICMS
				nullField() .  // vBCFCP;
				nullField() .  // pFCP;
				nullField() .  // vFCP;
				textField(modBCST($icmsPart->modBCST)) . // Modalidade de determinação da BC do ICMS ST: 0 – Preço tabelado ou máximo sugerido; 1 - Lista Negativa (valor); 2 - Lista Positiva (valor); 3 - Lista Neutra (valor); 4 - Margem Valor Agregado (%); 5 - Pauta (valor). 6 - Valor da Operação
				numField($icmsPart->pMVAST) . // Percentual da Margem de Valor Adicionado ICMS ST
				numField($icmsPart->pRedBCST) . // Percentual de redução da BC ICMS ST
				numField($icmsPart->vBCST) . // Valor da BC do ICMS ST
				numField($icmsPart->pICMSST) . // Alíquota do ICMS ST
				numField($icmsPart->vICMSST) . // Valor do ICMS ST
				nullField() .  // vBCFCPST;
				nullField() .  // pFCPST;
				nullField() .  // vFCPST;
				nullField() .  // vICMSDeson;
				nullField() .  // motDesICMS;
				nullField() .  // vICMSOp;
				nullField() .  // pDif;
				nullField() .  // vICMSDif;
				numField($icmsPart->pBCOp) . // Percentual para determinação do valor da Base de Cálculo da operação própria.
				numField($icmsPart->UFST) . // Sigla da UF para qual é devido o ICMS ST da operação.
				nullField() .  // vBCSTRet;
				nullField() .  // pST;
				nullField() .  // vICMSSubstituto;
				nullField() .  // vICMSSTRet;
				nullField() .  // vBCFCPSTRet;
				nullField() .  // pFCPSTRet;
				nullField() .  // vFCPSTRet;
				nullField() .  // vBCSTDest;
				nullField() .  // vICMSSTDest;
				nullField() .  // pRedBCEfet;
				nullField() .  // vBCEfet;
				nullField() .  // pICMSEfet;
				nullField(); // vICMSEfet 
		} elseif (!empty($icmsST)) {
			// Grupo de informação do ICMSST devido para a UF de destino, nas operações interestaduais de produtos que tiveram retenção antecipada de ICMS por ST na UF do remetente. Repasse via Substituto Tributário.
			$icmsCols = textField("ICMSST") .
				textField(Torig($icmsST->orig)) . // origem da mercadoria: 0 - Nacional 1 - Estrangeira - Importação direta 2 - Estrangeira - Adquirida no mercado interno
				textField(icmsCST($icmsST->CST)) . // Tributção pelo ICMS 41-Não Tributado. 60-Cobrado anteriormente por substituição tributária.
				nullField() .  // origem;
				nullField() .  // CST;
				nullField() .  // modBC;
				nullField() .  // vBC;
				nullField() .  // pRedBC;
				nullField() .  // pICMS;
				nullField() .  // vICMS;
				nullField() .  // vBCFCP;
				nullField() .  // pFCP;
				nullField() .  // vFCP;
				nullField() .  // modBCST;
				nullField() .  // pMVAST;
				nullField() .  // pRedBCST;
				nullField() .  // vBCST;
				nullField() .  // pICMSST;
				nullField() .  // vICMSST;
				nullField() .  // vBCFCPST;
				nullField() .  // pFCPST;
				nullField() .  // vFCPST;
				nullField() .  // vICMSDeson;
				nullField() .  // motDesICMS;
				nullField() .  // vICMSOp;
				nullField() .  // pDif;
				nullField() .  // vICMSDif;
				nullField() .  // pBCOp;
				nullField() .  // UFST;
				numField($icmsST->vBCSTRet) . // Informar o valor da BC do ICMS ST retido na UF remetente
				numField($icmsST->pST) . // Aliquota suportada pelo consumidor final.
				numField($icmsST->vICMSSubstituto) . // Valor do ICMS Próprio do Substituto cobrado em operação anterior
				numField($icmsST->vICMSSTRet) . //  Informar o valor do ICMS ST retido na UF remetente (iv2.0))
				numField($icmsST->vBCFCPSTRet) . // Informar o valor da Base de Cálculo do FCP retido anteriormente por ST.
				numField($icmsST->pFCPSTRet) . // Percentual relativo ao Fundo de Combate à Pobreza (FCP) retido por substituição tributária.
				numField($icmsST->vFCPSTRet) . // Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP) retido por substituição tributária.
				numField($icmsST->vBCSTDest) . // Informar o valor da BC do ICMS ST da UF destino
				numField($icmsST->vICMSSTDest) . // Informar o valor da BC do ICMS ST da UF destino (v2.0)
				numField($icmsST->pRedBCEfet) . // Percentual de redução da base de cálculo efetiva.
				numField($icmsST->vBCEfet) . // Valor da base de cálculo efetiva.
				numField($icmsST->pICMSEfet) . // Alíquota do ICMS efetivo.
				numField($icmsST->vICMSEfet); // Valor do ICMS efetivo.
		} else {
			echo ("Nenhuma coluna de ICMS encontrada no arquivo . $file");
			exit(1);
		}

		if (!empty($ipiTrib)) {
			$ipiCols = textField("IPITrib") .
				textField(ipiCST($ipiTrib->CST)) . // Código da Situação Tributária do IPI: 00-Entrada com recuperação de crédito 49 - Outras entradas 50-Saída tributada 99-Outras saídas
				numField($ipiTrib->vBC) . // Valor da BC do IPI
				numField($ipiTrib->pIPI) . // Alíquota do IPI
				numField($ipiTrib->qUnid) . // Quantidade total na unidade padrão para tributação
				numField($ipiTrib->vUnid) . // Valor por Unidade Tributável. Informar o valor do imposto Pauta por unidade de medida.
				numField($ipiTrib->vIPI); // Valor do IPI
		} else {
			$ipiCols = textField("IPINT") .
				textField(ipiCST($ipiNT->CST)) . // Código da Situação Tributária do IPI: 01-Entrada tributada com alíquota zero 02-Entrada isenta 03-Entrada não-tributada 04-Entrada imune 05-Entrada com suspensão 51-Saída tributada com alíquota zero 52-Saída isenta 53-Saída não-tributada 54-Saída imune 55-Saída com suspensão
				nullField() . // Valor da BC do IPI
				nullField() . // Alíquota do IPI
				nullField() . // Quantidade total na unidade padrão para tributação
				nullField() . // Valor por Unidade Tributável. Informar o valor do imposto Pauta por unidade de medida.
				nullField(); // Valor do IPI

		}

		if (!empty($pisAliq)) {
			$pisCols = textField("PISAliq") .
				textField(pisCST($pisAliq->CST)) .	// Código de Situação Tributária do PIS. 01 – Operação Tributável - Base de Cálculo = Valor da Operação Alíquota Normal (Cumulativo/Não Cumulativo); 02 - Operação Tributável - Base de Calculo = Valor da Operação (Alíquota Diferenciada);		
				numField($pisAliq->vBC) . // Valor da BC do PIS
				numField($pisAliq->pPIS) . // Alíquota do PIS (em percentual)
				nullField() .  // qBCProd
				nullField() .  // vAliqProd
				numField($pisAliq->vPIS);  // Valor do PIS
		} elseif (!empty($pisQtde)) {
			$pisCols = textField("PISQtde") .
				textField(pisCST($pisQtde->CST)) . // Código de Situação Tributária do PIS. 03 - Operação Tributável - Base de Calculo = Quantidade Vendida x Alíquota por Unidade de Produto;			
				nullField() .  // vBC
				nullField() .  // pPIS
				numField($pisQtde->qBCProd) . // Quantidade Vendida (NT2011/004)
				numField($pisQtde->vAliqProd) . // Alíquota do PIS (em reais) (NT2011/004)
				numField($pisQtde->vPIS); // Valor do PIS
		} elseif (!empty($pisNT)) {
			$pisCols = textField("PISNT") .
				textField(pisCST($pisNT->CST)) . // Código de Situação Tributária do PIS. 04 - Operação Tributável - Tributação Monofásica - (Alíquota Zero); 05 - Operação Tributável (ST); 06 - Operação Tributável - Alíquota Zero; 07 - Operação Isenta da contribuição; 08 - Operação Sem Incidência da contribuição; 09 - Operação com suspensão da contribuição
				nullField() .  // vBC
				nullField() .  // pPIS
				nullField() .  // qBCProd
				nullField() .  // vAliqProd
				nullField(); // vPIS
		} else {
			$pisCols = textField("PISOutr") .
				textField(pisCST($pisOutr->CST)) . // Código de Situação Tributária do PIS. 99 - Outras Operações.			
				numField($pisOutr->vBC) . // Valor da BC do PIS
				numField($pisOutr->pPIS) . // Alíquota do PIS (em percentual)
				numField($pisOutr->qBCProd) . // Quantidade Vendida (NT2011/004)
				numField($pisOutr->vAliqProd) . // Alíquota do PIS (em reais) (NT2011/004)
				numField($pisOutr->vPIS); // Valor do PIS

		}

		if (!empty($cofinsAliq)) {
			$cofinsCols = textField("COFINSAliq") .
				textField(cofinsCST($cofinsAliq->CST)) .	// Código de Situação Tributária do COFINS. 01 – Operação Tributável - Base de Cálculo = Valor da Operação Alíquota Normal (Cumulativo/Não Cumulativo); 02 - Operação Tributável - Base de Calculo = Valor da Operação (Alíquota Diferenciada);		
				numField($cofinsAliq->vBC) . // Valor da BC do COFINS
				numField($cofinsAliq->pCOFINS) . // Alíquota do COFINS (em percentual)
				nullField() .  // qBCProd
				nullField() .  // vAliqProd
				numField($cofinsAliq->vCOFINS); // Valor do COFINS
		} elseif (!empty($cofinsQtde)) {
			$cofinsCols = textField("COFINSQtde") .
				textField(cofinsCST($cofinsQtde->CST)) . 	// Código de Situação Tributária do COFINS. 03 - Operação Tributável - Base de Calculo = Quantidade Vendida x Alíquota por Unidade de Produto		
				nullField() .  // vBC
				nullField() .  // pCOFINS
				numField($cofinsQtde->qBCProd) . // Quantidade Vendida (NT2011/004)
				numField($cofinsQtde->vAliqProd) . // Alíquota do COFINS (em reais) (NT2011/004)
				numField($cofinsQtde->vCOFINS); // Valor do COFINS
		} elseif (!empty($cofinsNT)) {
			$cofinsCols = textField("COFINSNT") .
				textField(cofinsCST($cofinsNT->CST)) . // Código de Situação Tributária do COFINS: 04 - Operação Tributável - Tributação Monofásica - (Alíquota Zero); 05 - Operação Tributável (ST); 06 - Operação Tributável - Alíquota Zero; 07 - Operação Isenta da contribuição; 08 - Operação Sem Incidência da contribuição; 09 - Operação com suspensão da contribuição
				nullField() .  // vBC
				nullField() .  // pCOFINS
				nullField() .  // qBCProd
				nullField() .  // vAliqProd
				nullField(); // vCOFINS
		} else {
			$cofinsCols = textField("COFINSOutr") .
				textField(cofinsCST($cofinsOutr->CST)) .	// Código de Situação Tributária do COFINS: 49 - Outras Operações de Saída 50 - Operação com Direito a Crédito - Vinculada Exclusivamente a Receita Tributada no Mercado Interno 51 - Operação com Direito a Crédito – Vinculada Exclusivamente a Receita Não Tributada no Mercado Interno 52 - Operação com Direito a Crédito - Vinculada Exclusivamente a Receita de Exportação 53 - Operação com Direito a Crédito - Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno 54 - Operação com Direito a Crédito - Vinculada a Receitas Tributadas no Mercado Interno e de Exportação 55 - Operação com Direito a Crédito - Vinculada a Receitas Não-Tributadas no Mercado Interno e de Exportação 56 - Operação com Direito a Crédito - Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno, e de Exportação 60 - Crédito Presumido - Operação de Aquisição Vinculada Exclusivamente a Receita Tributada no Mercado Interno 61 - Crédito Presumido - Operação de Aquisição Vinculada Exclusivamente a Receita Não-Tributada no Mercado Interno 62 - Crédito Presumido - Operação de Aquisição Vinculada Exclusivamente a Receita de Exportação 63 - Crédito Presumido - Operação de Aquisição Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno 64 - Crédito Presumido - Operação de Aquisição Vinculada a Receitas Tributadas no Mercado Interno e de Exportação 65 - Crédito Presumido - Operação de Aquisição Vinculada a Receitas Não-Tributadas no Mercado Interno e de Exportação 66 - Crédito Presumido - Operação de Aquisição Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno, e de Exportação 67 - Crédito Presumido - Outras Operações 70 - Operação de Aquisição sem Direito a Crédito 71 - Operação de Aquisição com Isenção 72 - Operação de Aquisição com Suspensão 73 - Operação de Aquisição a Alíquota Zero 74 - Operação de Aquisição sem Incidência da Contribuição 75 - Operação de Aquisição por Substituição Tributária 98 - Outras Operações de Entrada 99 - Outras Operações.
				numField($cofinsOutr->vBC) . // Valor da BC do COFINS
				numField($cofinsOutr->pCOFINS) . // Alíquota do COFINS (em percentual)
				numField($cofinsOutr->qBCProd) . // Quantidade Vendida (NT2011/004)
				numField($cofinsOutr->vAliqProd) . // Alíquota do COFINS (em reais) (NT2011/004)
				numField($cofinsOutr->vCOFINS); // Valor do COFINS
		}

		$row =
			textField($ide->nNF) . // Número do Documento Fiscal
			textField($xml->NFe->infNFe['Id']) . // Chave da NFe
			textField($dtEmissao) .
			textField($xml->NFe->infNFe['versao']) .
			textField($ide->natOp) . // Descrição da Natureza da Operação
			textField(tpNF($ide->tpNF)) . // Tipo do Documento Fiscal (0 - entrada; 1 - saída)
			textField(idDest($ide->idDest)) . // Identificador de Local de destino da operação (1-Interna;2-Interestadual;3-Exterior)
			textField(tpEmis($ide->tpEmis)) . // Forma de emissão da NF-e 1 - Normal; 2 - Contingência FS 3 - Contingência SCAN 4 - Contingência DPEC 5 - Contingência FSDA 6 - Contingência SVC - AN 7 - Contingência SVC - RS 9 - Contingência off-line NFC-e
			textField(finNFe($ide->finNFe)) . // Finalidade da emissão da NF-e: 1 - NFe normal 2 - NFe complementar 3 - NFe de ajuste 4 - Devolução/Retorno
			textField(indFinal($ide->indFinal)) . // Indica operação com consumidor final (0-Não;1-Consumidor Final)
			textField($emit->xNome) . // Razão Social ou Nome do emitente
			textField($dest->xNome) . // Razão Social ou nome do destinatário
			textField(indIEDest($dest->indIEDest)) . // Indicador da IE do destinatário: 1 – Contribuinte ICMSpagamento à vista; 2 – Contribuinte isento de inscrição; 9 – Não Contribuinte
			numField($xml->protNFe->infProt->cStat) .
			textField($xml->protNFe->infProt->xMotivo) .
			numField($item['nItem']) . // Número do Item
			textField($prod->cProd) . // Código do produto ou serviço. Preencher com CFOP caso se trate de itens não relacionados com mercadorias/produto e que o contribuinte não possua codificação própria Formato ”CFOP9999”
			textField($prod->cEAN) . // GTIN (Global Trade Item Number) do produto, antigo código EAN ou código de barras
			textField($prod->xProd) . // Descrição do produto ou serviço
			textField($prod->NCM) . // Código NCM (8 posições), será permitida a informação do gênero (posição do capítulo do NCM) quando a operação não for de comércio exterior (importação/exportação) ou o produto não seja tributado pelo IPI. Em caso de item de serviço ou item que não tenham produto (Ex. transferência de crédito, crédito do ativo imobilizado, etc.), informar o código 00 (zeros) (v2.0)
			textField($prod->NVE) . // Nomenclatura de Valor aduaneio e Estatístico
			textField($prod->CEST) . // Codigo especificador da Substuicao Tributaria - CEST, que identifica a mercadoria sujeita aos regimes de substituicao tributária e de antecipação do recolhimento do imposto
			textField($prod->indEscala) . // S ou N
			textField($prod->CNPJFab) . // CNPJ do Fabricante da Mercadoria, obrigatório para produto em escala NÃO relevante.
			textField($prod->cBenef) . // ??? <xs:pattern value="([!-ÿ]{8}|[!-ÿ]{10}|SEM CBENEF)?"/>
			textField($prod->EXTIPI) . // Código EX TIPI (3 posições)
			textField($prod->CFOP) . // Cfop
			textField($prod->uCom) . // Unidade comercial
			numField($prod->qCom) . // Quantidade Comercial do produto, alterado para aceitar de 0 a 4 casas decimais e 11 inteiros.
			numField($prod->vUnCom) . // Valor unitário de comercialização - alterado para aceitar 0 a 10 casas decimais e 11 inteiros
			textField($prod->vProd) .	// Valor bruto do produto ou serviço.
			textField($prod->cEANTrib) . // GTIN (Global Trade Item Number) da unidade tributável, antigo código EAN ou código de barras
			textField($prod->uTrib) . // Unidade Tributável
			numField($prod->qTrib) . // Quantidade Tributável - alterado para aceitar de 0 a 4 casas decimais e 11 inteiros
			numField($prod->vUnTrib) . // Valor unitário de tributação - - alterado para aceitar 0 a 10 casas decimais e 11 inteiros
			numField($prod->vFrete) . // Valor Total do Frete
			numField($prod->vSeg) . // Valor Total do Seguro
			numField($prod->vDesc) . // Valor do Desconto
			numField($prod->vOutro) . // Outras despesas acessórias
			numField($prod->indTot) . // Este campo deverá ser preenchido com: 0 – o valor do item (vProd) não compõe o valor total da NF-e (vProd) 1 – o valor do item (vProd) compõe o valor total da NF-e (vProd)

			$icmsCols . // Colunas do ICMS mescladas 

			$ipiCols . // Colunas do IPITrib e IPINT mescladas

			$pisCols . // Colunas PISAliq, PISQtde, PISNT e PISOutr mescladas

			numField($pisST->vBC) . // Valor da BC do PIS ST
			numField($pisST->pPIS) . // Alíquota do PIS ST (em percentual)
			numField($pisST->qBCProd) . // Quantidade Vendida
			numField($pisST->vAliqProd) . // Alíquota do PIS ST (em reais)
			numField($pisST->vPIS) .	// Valor do PIS ST		

			$cofinsCols . // Colunas COFINSAliq, COFINSQtde. COFINSNT e COFINSOutr mescladas

			numField($cofinsST->vBC) . // Valor da BC do COFINS ST
			numField($cofinsST->pCOFINS) . // Alíquota do COFINS ST(em percentual)
			numField($cofinsST->qBCProd) . // Quantidade Vendida
			numField($cofinsST->vAliqProd) . // Alíquota do COFINS ST(em reais)
			numField($cofinsST->vCOFINS) . // Valor do COFINS ST

			// numField($imposto->vTotTrib) .
			textField("file:///$file", false);

		$printer->printRow($row);
	}
}
