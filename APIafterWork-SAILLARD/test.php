<?php
require "./src/Controller/ProduitController.php";
require "./src/Services/ModeleAfterwork.php";

$BDDAfter = new Modele_Afterworks();
$test = $BDDAfter->getCafes();

var_dump($test);