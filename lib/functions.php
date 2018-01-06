<?php

define("BASEPATH", str_replace("lib", "", dirname(__FILE__)));
/**
 * @param $classe
 * @return mixed
 */
function load($classe)
{
    return include_once (BASEPATH."lib/class/".$classe.".class.php"); // On inclut la classe correspondante au paramètre passé.
}