<?php
include_once "Modele_Afterworks.php";
include_once "Modele_Prestashop.php";

$BDDAfter = new Modele_Afterworks();
$BDDPs = new Modele_Prestashop();

$test = $BDDAfter->getCategories();

var_dump($test);