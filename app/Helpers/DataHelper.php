<?php
/**
 * Функция вычисляет разницу двух дат и форматирует ее в формате дд мм гг
 * @param $dateFirst
 * @param $dateSecond
 * @return string
 */

function dateDiff($dateFirst, $dateSecond){
    $interval = date_diff($dateFirst, $dateSecond);
    $formatStr = '%hч %iмин';
    if ($interval->d > 0) $formatStr = '%dд ' . $formatStr;
    if ($interval->m > 0) $formatStr = '%mм ' . $formatStr;
    if ($interval->y > 0) $formatStr = '%yг ' . $formatStr;
    return $interval->format($formatStr);
}
