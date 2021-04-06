<?php

/**
 * Tipo do Documento Fiscal (0 - entrada; 1 - saída)
 */
function tpNF($tpNF)
{
  switch ($tpNF) {
    case "0":
      return "0-entrada";
    case "1":
      return "1-saída";
  }
  return $tpNF;
}
/**
 * Identificador de Local de destino da operação
 */
function idDest($idDest)
{
  switch ($idDest) {
    case "1":
      return "1-Interna";
    case "2":
      return "2-Interestadual";
    case "3":
      return "3-Exterior";
  }
  return $idDest;
}

/**
 * Forma de emissão da NF-e 
 */
function tpEmis($tpEmis)
{
  switch ($tpEmis) {
    case "1":
      return "1-Normal";
    case "2":
      return "2-Contingência FS";
    case "3":
      return "3-Contingência SCAN";
    case "4":
      return "4-Contingência DPEC";
    case "5":
      return "5-Contingência FSDA";
    case "6":
      return "6-Contingência SVC - AN";
    case "7":
      return "7-Contingência SVC - RS";
    case "9":
      return "9-Contingência off-line NFC-e";
  }
  return $tpEmis;
}


/**
 * Finalidade da emissão da NF-e
 */
function finNFe($finNFe)
{
  switch ($finNFe) {
    case "1":
      return "1-NFe normal";
    case "2":
      return "2-NFe complementar";
    case "3":
      return "3-NFe de ajuste";
    case "4":
      return "4-Devolução/Retorno";
  }
  return $finNFe;
}

/**
 * Indica operação com consumidor final (0-Não;1-Consumidor Final)
 */
function indFinal($indFinal)
{
  switch ($indFinal) {
    case "0":
      return "0-Não";
    case "1":
      return "1-Consumidor Final";
  }
}


/**
 * Indicador da IE do destinatário
 */
function indIEDest($indIEDest)
{
  switch ($indIEDest) {
    case "1":
      return "1-Contribuinte ICMS";
    case "2":
      return "2-Contribuinte isento de inscrição";
    case "9":
      return "9-Não Contribuinte";
  }
  return $indIEDest;
}
/**
 * ICMS CST - Código da Situação Tributária
 */
function icmsCST($cst)
{
  switch ($cst) {
    case "00":
      return "00-Tributada integralmente";
    case "10":
      return "10-Tributada e com cobrança do ICMS por substituição tributária";
    case "20":
      return "20-Com redução de base de cálculo";
    case "30":
      return "30-Isenta ou não tributada e com cobrança do ICMS por substituição tributária";
    case "40":
      return "40-Isenta";
    case "41":
      return "41-Não tributada";
    case "50":
      return "50-Suspensão";
    case "51":
      return "51-Diferimento A exigência do preenchimento das informações do ICMS diferido fica à critério de cada UF.";
    case "60":
      return "60-ICMS cobrado anteriormente por substituição tributária";
    case "70":
      return "70-Com redução de base de cálculo e cobrança do ICMS por substituição tributária";
    case "90":
      return "90-Outras";
  }
  return $cst;
}

/**
 * origem da mercadoria
 */
function Torig($orig)
{
  switch ($orig) {
    case "0":
      return "0-Nacional";
    case "1":
      return "1-Estrangeira-Importação direta";
    case "2":
      return "2-Estrangeira-Adquirida no mercado interno";
  }
  return $orig;
}

/**
 * Modalidade de determinação da BC do ICMS:
 */
function modBC($modBC)
{
  switch ($modBC) {
    case "0":
      return "0-Margem Valor Agregado (%)";
    case "1":
      return "1-Pauta (valor)";
    case "2":
      return "2-Preço Tabelado Máximo (valor)";
    case "3":
      return "3-Valor da Operação";
  }
  return $modBC;
}

/**
 * Modalidade de determinação da BC do ICMS ST
 */
function modBCST($BCST)
{
  switch ($BCST) {
    case "0":
      return  "0-Preço tabelado ou máximo sugerido";
    case "1":
      return "1-Lista Negativa (valor)";
    case "2":
      return "2-Lista Positiva (valor)";
    case "3":
      return "3-Lista Neutra (valor)";
    case "4":
      return "4-Margem Valor Agregado (%)";
    case "5":
      return "5-Pauta (valor)";
    case "6":
      return "6-Valor da Operação";
  }
  return $BCST;
}

/**
 * Motivo da desoneração do ICMS
 */
function motDesICMS($motDes)
{
  switch ($motDes) {
    case "1":
      return "1-Táxi";
    case "3":
      return "3-Uso na agropecuária/Produtor Agropecuário";
    case "4":
      return "4-Frotista/Locadora";
    case "5":
      return "5-Diplomático/Consular";
    case "6":
      return "6-Utilitários e Motocicletas da Amazônia Ocidental e Áreas de Livre Comércio (Resolução 714/88 e 790/94 – CONTRAN e suas alterações)";
    case "7":
      return ";7-SUFRAMA";
    case "8":
      return "8-Venda a órgão Público";
    case "9":
      return "9-Outros";
    case "10":
      return "10-Deficiente Condutor";
    case "11":
      return "11-Deficiente não condutor";
    case "12":
      return "12-Fomento agropecuário";
    case "16":
      return "16-Olimpíadas Rio 2016";
    case "90":
      return "90-Solicitado pelo Fisco";
  }
  return $motDes;
}


/**
 * Código da Situação Tributária do IPI
 */
function ipiCST($cst)
{
  switch ($cst) {
    case "00":
      return "00-Entrada com recuperação de crédito";
    case "01":
      return "01-Entrada tributada com alíquota zero";
    case "02":
      return "02-Entrada isenta";
    case "03":
      return "03-Entrada não-tributada";
    case "04":
      return "04-Entrada imune";
    case "05":
      return "05-Entrada com suspensão";
    case "49":
      return "49-Outras entradas";
    case "50":
      return "50-Saída tributada";
    case "51":
      return "51-Saída tributada com alíquota zero";
    case "52":
      return "52-Saída isenta";
    case "53":
      return "53-Saída não-tributada";
    case "54":
      return "54-Saída imune";
    case "55":
      return "55-Saída com suspensão";
    case "99":
      return "99-Outras saídas";
  }

  return $cst;
}

/**
 * Código de Situação Tributária do PIS.
 */

function pisCST($cst)
{
  switch ($cst) {
    case "01":
      return "01-Op.Tributável-BC=Vlr.da Op.Alíq Normal(Cumulativo/Não Cumulativo)";
    case "02":
      return "02-Op.Tributável-BC=Vlr da Op.(Alíq.Diferenciada)";
    case "03":
      return "03-Op.Tributável-BC=Qtd.Vendida x Alíq.p/Un.Produto";
    case "04":
      return "04-Op.Tributável-Tributação Monofásica-(Alíq.Zero)";
    case "05":
      return "05-Op.Tributável (ST)";
    case "06":
      return "06-Op.Tributável-Alíq.Zero";
    case "07":
      return "07-Op.Isenta da contribuição";
    case "08":
      return "08-Op.Sem Incidência da contribuição";
    case "09":
      return "09-Op.Com Suspensão da contribuição";
      return "09-Op.Com Suspensão da contribuição";
    case "49":
      return "49-Outras Op.de Saída";
    case "50":
      return "50-Op.com Direito a Crédito-Vinculada Exclusivamente a Receita Tributada no Mercado Interno";
    case "51":
      return "51-Op.com Direito a Crédito-Vinculada Exclusivamente a Receita Não Tributada no Mercado Interno";
    case "52":
      return "52-Op.com Direito a Crédito-Vinculada Exclusivamente a Receita de Exportação";
    case "53":
      return "53-Op.com Direito a Crédito-Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno";
    case "54":
      return "54-Op.com Direito a Crédito-Vinculada a Receitas Tributadas no Mercado Interno e de Exportação";
    case "55":
      return "55-Op.com Direito a Crédito-Vinculada a Receitas Não-Tributadas no Mercado Interno e de Exportação";
    case "56":
      return "56-Op.com Direito a Crédito-Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno, e de Exportação";
    case "60":
      return "60-Crédito Presumido-Operação de Aquisição Vinculada Exclusivamente a Receita Tributada no Mercado Interno";
    case "61":
      return "61-Crédito Presumido-Operação de Aquisição Vinculada Exclusivamente a Receita Não-Tributada no Mercado Interno";
    case "62":
      return "62-Crédito Presumido-Operação de Aquisição Vinculada Exclusivamente a Receita de Exportação";
    case "63":
      return "63-Crédito Presumido-Operação de Aquisição Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno";
    case "64":
      return "64-Crédito Presumido-Operação de Aquisição Vinculada a Receitas Tributadas no Mercado Interno e de Exportação";
    case "65":
      return "65-Crédito Presumido-Operação de Aquisição Vinculada a Receitas Não-Tributadas no Mercado Interno e de Exportação";
    case "66":
      return "66-Crédito Presumido-Operação de Aquisição Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno, e de Exportação";
    case "67":
      return "67-Crédito Presumido-Outras Operações";
    case "70":
      return "70-Op.de Aquisição sem Direito a Crédito";
    case "71":
      return "71-Op.de Aquisição com Isenção";
    case "72":
      return "72-Op.de Aquisição com Suspensão";
    case "73":
      return "73-Op.de Aquisição a Alíquota Zero";
    case "74":
      return "74-Op.de Aquisição sem Incidência da Contribuição";
    case "75":
      return "75-Op.de Aquisição por Substituição Tributária";
    case "98":
      return "98-Outras Operações de Entrada";

    case "99":
      return "99-Outras Operações";
  }

  return $cst;
}

/**
 * Código de Situação Tributária do COFINS.
 */
function cofinsCST($cst)
{
  switch ($cst) {
    case "01":
      return "01-Op.Tributável-BC=Vlr.da Op.Alíq Normal(Cumulativo/Não Cumulativo)";
    case "02":
      return "02-Op.Tributável-BC=Vlr da Op.(Alíq.Diferenciada)";
    case "03":
      return "03-Op.Tributável-BC=Qtd.Vendida x Alíq.p/Un.Produto";
    case "04":
      return "04-Op.Tributável-Tributação Monofásica-(Alíq.Zero)";
    case "05":
      return "05-Op.Tributável (ST)";
    case "06":
      return "06-Op.Tributável-Alíq.Zero";
    case "07":
      return "07-Op.Isenta da contribuição";
    case "08":
      return "08-Op.Sem Incidência da contribuição";
    case "09":
      return "09-Op.Com Suspensão da contribuição";
    case "49":
      return "49-Outras Op.de Saída";
    case "50":
      return "50-Op.com Direito a Crédito-Vinculada Exclusivamente a Receita Tributada no Mercado Interno";
    case "51":
      return "51-Op.com Direito a Crédito-Vinculada Exclusivamente a Receita Não Tributada no Mercado Interno";
    case "52":
      return "52-Op.com Direito a Crédito-Vinculada Exclusivamente a Receita de Exportação";
    case "53":
      return "53-Op.com Direito a Crédito-Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno";
    case "54":
      return "54-Op.com Direito a Crédito-Vinculada a Receitas Tributadas no Mercado Interno e de Exportação";
    case "55":
      return "55-Op.com Direito a Crédito-Vinculada a Receitas Não-Tributadas no Mercado Interno e de Exportação";
    case "56":
      return "56-Op.com Direito a Crédito-Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno, e de Exportação";
    case "60":
      return "60-Crédito Presumido-Operação de Aquisição Vinculada Exclusivamente a Receita Tributada no Mercado Interno";
    case "61":
      return "61-Crédito Presumido-Operação de Aquisição Vinculada Exclusivamente a Receita Não-Tributada no Mercado Interno";
    case "62":
      return "62-Crédito Presumido-Operação de Aquisição Vinculada Exclusivamente a Receita de Exportação";
    case "63":
      return "63-Crédito Presumido-Operação de Aquisição Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno";
    case "64":
      return "64-Crédito Presumido-Operação de Aquisição Vinculada a Receitas Tributadas no Mercado Interno e de Exportação";
    case "65":
      return "65-Crédito Presumido-Operação de Aquisição Vinculada a Receitas Não-Tributadas no Mercado Interno e de Exportação";
    case "66":
      return "66-Crédito Presumido-Operação de Aquisição Vinculada a Receitas Tributadas e Não-Tributadas no Mercado Interno, e de Exportação";
    case "67":
      return "67-Crédito Presumido-Outras Operações";
    case "70":
      return "70-Op.de Aquisição sem Direito a Crédito";
    case "71":
      return "71-Op.de Aquisição com Isenção";
    case "72":
      return "72-Op.de Aquisição com Suspensão";
    case "73":
      return "73-Op.de Aquisição a Alíquota Zero";
    case "74":
      return "74-Op.de Aquisição sem Incidência da Contribuição";
    case "75":
      return "75-Op.de Aquisição por Substituição Tributária";
    case "98":
      return "98-Outras Operações de Entrada";
    case "99":
      return "99-Outras Operações";
  }

  return $cst;
}
