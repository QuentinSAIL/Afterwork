<?php
$pdo_presta = new PDO("mysql:host=localhost;dbname=prestashop", "root", "");
$pdo_after = new PDO("mysql:host=localhost;dbname=afterWork", "root", "");

// call the declinaison function to insert declinaison in the local DB
get_and_insert_all_declinaisons($pdo_presta, $pdo_after);

// call the tva function to insert pourcentageTVA in the local DB
get_and_insert_tva($pdo_after);

// put category import to 0 at the beginning of the script
$put_false_import_category = $pdo_after->prepare('update categorie set import = :paramimport');
$put_false_import_category->execute([
    'paramimport' => 0
]);

// put product import to 0 at the beginning of the script
$put_false_import_product = $pdo_after->prepare('update produit set import = :paramimport');
$put_false_import_product->execute([
    'paramimport' => 0
]);

// Get all Prestashop categories
$get_categories_prestashop = $pdo_presta->prepare('select id_category, `name`, description from ps_category_lang where id_lang = :param');
$get_categories_prestashop->execute([
    'param' => 1 // fr
]);
$presta_catagories = $get_categories_prestashop->fetchAll();

foreach ($presta_catagories as $category) {
    // get in the local BD the prestashop category
    $query = $pdo_after->prepare('select * from categorie where libelle_categorie = :param');
    $query->execute([
        'param' => $category["name"]
    ]);
    $after_category = $query->fetchAll();

    // if the category doesn't exist in the local DB, ask user
    if (sizeof($after_category) == 0) {
        echo 'Voulez-vous ajouter cette catégorie : ' . $category["name"] . ' ? O/N ';
        $response = readline();

        if ($response != "N") {
            // clean description (i hate html tags grrr)
            $category["description"] = str_replace("'", '"', $category["description"]);
            $category["description"] = strip_tags($category["description"]);

            // insert in categorie the prestashop category with import as true (1)
            $query = $pdo_after->prepare('insert into categorie VALUES (null, :paramname, :paramdesc, :paramactivation, :paramimport)');
            $query->execute([
                'paramname' =>  $category["name"],
                'paramdesc' => $category["description"],
                'paramactivation' => 1,
                'paramimport' => 1
            ]);
            $id_categ = $pdo_after->lastInsertId();

            // get all products of the prestashop category
            $get_all_products_query = $pdo_presta->prepare('select p.id_product, `name`, description 
                from ps_product_lang p 
                inner join ps_category_product c on  p.id_product = c.id_product
                where id_category = :paramid and id_lang = :paramlang
                ');
            $get_all_products_query->execute([
                'paramid' => $category["id_category"],
                'paramlang' => 1 //fr
            ]);
            $products = $get_all_products_query->fetchAll(PDO::FETCH_ASSOC);

            // If there is products, go for it
            if (sizeof($products) !== 0) {
                foreach ($products as $product) {
                    get_and_insert_product($pdo_presta, $pdo_after, $product, $id_categ, $category);
                }
            }
        }
    }
    else
    {
        // put category import to 1
        $put_true_import_category = $pdo_after->prepare('update categorie set import = :paramimport where libelle_categorie = :paramname');
        $put_true_import_category->execute([
            'paramimport' => 1,
            'paramname' => $category["name"]
        ]);

        // get all products of the prestashop category
        $get_all_products_query = $pdo_presta->prepare('select p.id_product, `name`, description 
                from ps_product_lang p 
                inner join ps_category_product c on  p.id_product = c.id_product
                where id_category = :paramid and id_lang = :paramlang
                ');
        $get_all_products_query->execute([
            'paramid' => $category["id_category"],
            'paramlang' => 1 //fr
        ]);
        $products = $get_all_products_query->fetchAll(PDO::FETCH_ASSOC);

        // If there is products, go for it
        if (sizeof($products) !== 0) {
            foreach ($products as $product) {
                get_and_insert_product($pdo_presta, $pdo_after, $product, $after_category[0]["id_categorie"], $category);
            }
        }
    }
}

// On va chercher toutes les catégories avec le champ import à 0 car cela signifie qu'elles n'existent plus dans prestashop
$get_category_import_null = $pdo_after->prepare('select * from categorie where import = :paramimport and activation = :paramactivation');
$get_category_import_null->execute([
    'paramactivation' => 1,
    'paramimport' => 0
]);
$categories_to_delete = $get_category_import_null->fetchAll();

foreach ($categories_to_delete as $category)
{
    echo 'Voulez-vous supprimer la catégorie ' . $category["libelle_categorie"] . ' ? O/N (Si O, tous les produits appartenant à la catégorie seront également supprimés !) ';
    $response = readline();

    if ($response === 'O')
    {
        $get_all_products = $pdo_after->prepare('select * from produit where id_categorie = :paramid');
        $get_all_products->execute([
            'paramid' => $category["id_categorie"]
        ]);
        $products = $get_all_products->fetchAll();

        $delete_products = $pdo_after->prepare('update produit set activation = :paramactivation where id_categorie = :paramid');
        $delete_products->execute([
            'paramactivation' => 0,
            'paramid' => $category["id_categorie"]
        ]);

        $delete_category = $pdo_after->prepare('update categorie set activation = :paramactivation where id_categorie = :paramid');
        $delete_category->execute([
            'paramactivation' => 0,
            'paramid' => $category["id_categorie"]
        ]);

        if ($delete_category->rowCount() > 0)
        {
            echo 'La catégorie ' . $category["libelle_categorie"] . ' a bien été supprimée, ainsi que les produits lui appartenant' . PHP_EOL;
        }
    }
}


// On va chercher tous les produits avec le champ import à 0 car cela signifie qu'ils n'existent plus dans prestashop
$get_product_import_null = $pdo_after->prepare('select * from produit where import = :paramimport and activation = :paramactivation');
$get_product_import_null->execute([
    'paramactivation' => 1,
    'paramimport' => 0
]);
$products_to_delete = $get_product_import_null->fetchAll();

foreach ($products_to_delete as $product)
{
    echo 'Voulez-vous supprimer le produit ' . $product["libelle_produit"] . ' ? O/N ';
    $response = readline();

    if ($response === 'O')
    {
        $delete_product = $pdo_after->prepare('update produit set activation = :paramactivation where id_produit = :paramid');
        $delete_product->execute([
            'paramactivation' => 0,
            'paramid' => $product["id_produit"]
        ]);

        if ($delete_product->rowCount() > 0)
        {
            echo 'Le produit ' . $product["libelle_produit"] . ' a bien été supprimé' . PHP_EOL;
        }
    }
}


function get_and_insert_all_declinaisons ($pdo_presta, $pdo_after)
{
    // J'insère toutes les déclinaisons de prestashop dans notre BDD after_works
    $declinaisons_query = $pdo_presta->prepare('select distinct name from ps_attribute_lang where id_lang = :param');
    $declinaisons_query->execute([
        'param' => 1
    ]);
    $declinaisons = $declinaisons_query->fetchAll();

    foreach ($declinaisons as $declinaison)
    {
        // get in the local DB the prestashop declinaison
        $look_if_declinaison_exists = $pdo_after->prepare('select * from declinaison where libelle_declinaison = :paramdeclinaison');
        $look_if_declinaison_exists->execute([
            'paramdeclinaison' => $declinaison["name"]
        ]);
        $declinaison_exists = $look_if_declinaison_exists->fetch(PDO::FETCH_ASSOC);

        // if the declinaison doesn't exist in the local DB, insert her
        if (!$declinaison_exists)
        {
            $declinaison_insert = $pdo_after->prepare('insert into declinaison VALUES (null, :paramname, null)');
            $declinaison_insert->execute([
                'paramname' =>  $declinaison["name"]
            ]);
        }
    }
}

function get_and_insert_tva ($pdo_after)
{
    $all_tva = [2.1, 5.5, 10, 20];
    foreach ($all_tva as $tva)
    {
        // get in the local DB if the tva exist yet
        $look_if_tva_exists = $pdo_after->prepare('select * from tva where pourcentageTVA = :parampourcentage');
        $look_if_tva_exists->execute([
            'parampourcentage' => $tva
        ]);
        $tva_exists = $look_if_tva_exists->fetch(PDO::FETCH_ASSOC);

        // if the tva doesn't exist in the local DB, insert it
        if (!$tva_exists)
        {
            $tva_insert = $pdo_after->prepare('insert into tva VALUES (null, :parampourcentage)');
            $tva_insert->execute([
                'parampourcentage' =>  $tva
            ]);
        }
    }
}

function get_and_insert_product ($pdo_presta, $pdo_after, $product, $id_categ, $category)
{
    // get in the local BD the prestashop product
    $look_if_products_exists = $pdo_after->prepare('select * from produit where libelle_produit = :paramproduct');
    $look_if_products_exists->execute([
        'paramproduct' => $product["name"]
    ]);
    $product_exists = $look_if_products_exists->fetch(PDO::FETCH_ASSOC);

    if ($product_exists)
    {
        // put product import to 1
        $put_false_import_product = $pdo_after->prepare('update produit set import = :paramimport where id_produit = :paramid');
        $put_false_import_product->execute([
            'paramimport' => 1,
            'paramid' => $product_exists["id_produit"]
        ]);
    }

    // if the product doesn't exist, get all product's declinaisons
    if (! $product_exists)
    {
        $query = $pdo_presta->prepare('SELECT DISTINCT pal.*
                            FROM ps_product_lang p
                            INNER JOIN ps_product_attribute pa ON p.id_product = pa.id_product
                            INNER JOIN ps_product_attribute_combination pac ON pa.id_product_attribute = pac.id_product_attribute
                            INNER JOIN ps_attribute_lang pal ON pal.id_attribute = pac.id_attribute
                            WHERE pa.id_product = :paramidProduct and pal.id_lang = :paramlang and pal.id_attribute NOT IN (1,6,7)');
        $query->execute([
            'paramidProduct' => $product["id_product"],
            'paramlang' => 1 //fr
        ]);
        $declinaisons = $query->fetchAll(PDO::FETCH_ASSOC);

        echo 'Pour la catégorie ' . $category["name"] . ', ' . $product['name'] .' sera vendu à quel prix : ';


        // if the product have declinaisons, ask for user his price
        if (sizeof($declinaisons) !== 0)
        {
            echo 'Ce produit a toutes ces déclinaisons : (le prix sera commun à toutes) ';
            foreach ($declinaisons as $declinaison)
            {
                echo  PHP_EOL . $declinaison["name"];
            }
        }
        $prix = readline();

        // clean product description : no more html tags
        $product["description"] = str_replace("'", '"', $product["description"]);
        $product["description"] = strip_tags($product["description"]);

        // insert the product in our local DB and get his id
        $query = $pdo_after->prepare('insert into produit VALUES (null, :paramlibelle, :paramtva, :paramcategorie, :paramdesc, :paramprix, null, :paramactivation, :paramimport)');
        $query->execute([
            'paramlibelle' =>  $product["name"],
            'paramtva' => 3,
            'paramcategorie' => $id_categ,
            'paramdesc' => $product["description"],
            'paramprix' => $prix,
            'paramactivation' => 1, // true
            'paramimport' => 1 // true
        ]);
        $id_product = $pdo_after->lastInsertId();

        if (sizeof($declinaisons) !== 0)
        {
            foreach ($declinaisons as $declinaison)
            {

                // insert into declinaison_produit, the product with his own declinaison
                $query = $pdo_after->prepare('insert into declinaison_produit VALUES (null, (
                            select id_declinaison from declinaison where libelle_declinaison = :paramdeclinaison), 
                                    :paramproduct)');
                $query->execute([
                    'paramproduct' =>  $id_product,
                    'paramdeclinaison' => $declinaison["name"]
                ]);
            }
        }
    }
}