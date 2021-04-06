<?php

/**
 * Retorna um campo texto formatado (com aspas e/ou ponto-vírgula) ou NULL se for vazio
 */
function textField($fieldValue, $withSemicolon = true) {
  $value = "NULL";
  if (!empty($fieldValue)) {
    $value = '"'.$fieldValue.'"';
  }
  return $value . ($withSemicolon ? ';' : '');
}

/**
 * Retorna um campo númerico formatado (com ponto-vírgula) ou NULL se for vazio
 */
function numField($fieldValue, $withSemicolon = true) {
  $value = "NULL";
  if (!empty($fieldValue)) {
    $value = $fieldValue;
  }
  return $value . ($withSemicolon ? ';' : '');
}

/**
 * Retorna um campo NULL formatado (com ponto-vírgula)
 */
function nullField($withSemicolon = true) {
  return "NULL" . ($withSemicolon ? ';' : '');
}