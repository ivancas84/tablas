<?
function formatDate(DateTime $value = null, $format = "d/m/Y"){
  return ($value) ? $value->format($format) : null;
}
