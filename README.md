# nfe-xml-convert

Este script (simples mais útil) lê uma pasta contendo arquivos de NFes no formato XML e gera em sua saída dados em formato CSV ou SQL (INSERTS). (O CSV você poderá converter em uma planilha.)

Este script foi baseado no layout da NFe versão 4.00, conforme documentação na pasta doc-nfe.

Nota(s): 
- foi desconsiderado notas com os campos de ICMS para o Simples Nacional (eu não precisava para o trabalho que o script foi criado, mas se você precisar disso, faça um fork deste projeto; verifique o layout da NFe e inclua; depois faça um PR no meu projeto... vamos ajudar outras pessoas ;) ).

# Como executar

Crie a pasta nfes/notas e coloque os arquivos XMLs das NFes dentro desta pasta. Edite o arquivo export_notas.php e altere o formato desejado de saída na varíavel $printFormat (linha 28). Depois execute o seguinte comando:

```
php export_notas.php > nome_do_arquivo.FORMATO
```
