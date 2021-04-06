<?php
/**
 * Este script gera em sua saída várias consultas SQL (UPDATES) para atualizar o campo "cancelada" da tabela "nota" com o valor igual a "S"
 */
$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . "nfes/cancelamento"; // informe aqui a pasta onde se encontra os arquivos XML das notas canceladas
$files = glob($dir . DIRECTORY_SEPARATOR . "*.xml");
$tableName = "notas";

foreach ($files as $file) {
    $xml = simplexml_load_file($file);

    $retEvento = &$xml->retEvento;  // Retorno do Evento
    $infEvento = &$retEvento->infEvento;

    if ($infEvento->xEvento == "Cancelamento") {
        if ($infEvento->cStat == 135) {
            echo "UPDATE `{$tableName}` SET cancelada = 'S' WHERE chave =  'NFe{$infEvento->chNFe}';\n";
        } elseif ($infEvento->cStat == 573) { // 573=Duplicidade de Evento.
            continue; 
        } else {
            echo "Error cStat desconhecido {$infEvento->cStat} - {$infEvento->xMotivo}.\n";
        }
    }
}
