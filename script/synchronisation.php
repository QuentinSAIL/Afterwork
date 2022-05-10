<?php
include_once "Modele_Afterworks.php";
include_once "Modele_Prestashop.php";

$BDDPresta = new Modele_Prestashop();
$BDDAfter = new Modele_Afterworks();

//Je récupère la liste des catégories dans prestashop
$listeCategoriePresta = $BDDPresta->getCategories();

//je parcours la liste des catégories
foreach ($listeCategoriePresta as $category) {

// Création des catégories

    if (!$BDDAfter->existCategory($category["id_category"])) {
        echo "> La catégorie " . $category["name"] . " n'existe pas. Voulez-vous la créer? (O/N) : ";
        $resp = readLine();

        if ($resp != "N") {
            $BDDAfter->insertCategory($category["id_category"], $category["name"]);
            echo " -- Catégorie " . $category["name"] . " ajoutée" . PHP_EOL;


        } else if ($BDDAfter->existCategory($category["id_category"]) && !$BDDPresta->existCategorie($category["id_category"])) {
            echo " La Catégorie " . $category["name"] . " n'est pas présente dans la bdd prestashop, voulez vous la supprimer ainsi que tous ses produits ? (O/N) :";
            $respCategExist = readLine();
            if ($respCategExist != "N") {
                $BDDAfter->deleteCategory($category["id_category"]);
                echo " Categorie " . $category["name"] . " supprimée" . PHP_EOL;
            }
        } else {
            echo PHP_EOL;
        }
    }

    echo "Voulez vous gerer les articles de la catégorie " . $category["name"] . " ? (O/N)";
    $resp = readLine();
    if ($resp != "N") {
        $listeProduitPresta = $BDDPresta->getProductsByCategory($category["id_category"]);
        foreach ($listeProduitPresta as $produit) {
            if (!$BDDAfter->existProduct($produit["id_product"])) {
                echo "> Le produit " . $produit["name"] . "de la catégorie " . $category["name"] . " n'existe pas. Voulez-vous le créer? (O/N) : ";
                $respProduit = readLine();

                if ($respProduit != "N") {
                    $declinaison = $BDDAfter->getProductAttributeByIdproduct($produit["id_product"]);
                    if (!$declinaison) {
                        echo "Quelle déclinaison voulez vous pour le produit ".$produit["name"]." (N) si vous voulez pas en mettre : ";
                        $decliProduit = readLine();
                    }elseif($declinaison){
                        echo "Pour le ".$category["name"]." ".$produit["name"]." nous avons trouvé la déclinaison suivantes : 
                        . A quel prix sera-t-il vendu ?";
                    }

                    if ($decliProduit != "N") {
                        $BDDAfter->insertAttribute($produit["id_product"], $decliProduit);
                        echo "La déclinaison $decliProduit à bien été ajouté au produit ".$produit["name"].".";
                    }
                    echo " Quel prix voulez vous pour " . $produit["name"] . " ?" . PHP_EOL;
                    $respProduitPrix = readLine();
                    $BDDAfter->insertProduct($produit["id_product"], $produit["name"], null, null, $produit["description"], $category["id_category"], null);
                    $BDDAfter->insertProductAttribute(null , $produit["id_product"], null, $respProduitPrix);
                    echo " -- Produit " . $produit["name"] . " ajoutée" . PHP_EOL;
                }
            }
        }
    }
}
