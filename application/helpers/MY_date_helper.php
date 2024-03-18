<?php

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function validateDateDiff($fecha1,$fecha2,$tipo)
{
  $zfecha1=date_create($fecha1);
  $zfecha2=date_create($fecha2);
  $diferencia = date_diff($zfecha1,$zfecha2);

  if ($tipo =="d")
  {
    if($diferencia->invert==1 && $diferencia->days > 0)
      return  -$diferencia->days;
    else
      return  $diferencia->days;
  }
  else
  {
      return  $diferencia;
  }
}

function convertToDate($date,$format='Y-m-d')
{
  if (strlen($date) > 0)
  {
    $tmpdate=str_replace('/', '-', $date);
    $resultado=date($format, strtotime($tmpdate));
  }
  else {
    $resultado="";
  }

  return $resultado;
}

function convertirFechaES($date)
{
  $fecha = date("d/m/Y", strtotime($date));
  return $fecha;
}

function convertToDateTime($datetime,$format='Y-m-d H:i:s')
{
  if (strlen($datetime) > 0)
  {
    $tmpdatetime = str_replace('/', '-', $datetime);
    $resultado=date($format, strtotime($tmpdatetime));
  }
  else {
    $resultado="";
  }

  return $resultado;
}

function convertirFechaHoraES($datetime)
{
  $fecha = date("d/m/Y H:i:s", strtotime($datetime));
  return $fecha;
}

function hourIsBetween($from, $to, $input) {
  $dateFrom = DateTime::createFromFormat('!H:i:s', $from);
  $dateTo = DateTime::createFromFormat('!H:i:s', $to);
  $dateInput = DateTime::createFromFormat('!H:i:s', $input);
  if ($dateFrom > $dateTo) $dateTo->modify('+1 day');
  return ($dateFrom <= $dateInput && $dateInput <= $dateTo) || ($dateFrom <= $dateInput->modify('+1 day') && $dateInput <= $dateTo);
}

function convertirDateTimeToFechaSistema($fecha, $format='Y-m-d')
{
  $fecha= date($format, strtotime($fecha));
  return $fecha;
}

function convertToTime($time, $format='H:i:s')
{
  if (strlen($time) > 0)
  {
    $resultado=date($format, strtotime($time));
  }
  else {
    $resultado="";
  }

  return $resultado;
}
