<?php


class Modele_Prestashop{

    private $connexion = null;

    public function __construct()
    {
        $this->connexion = new PDO('mysql:host=127.0.0.1;dbname=prestashop;charset=LATIN1',  "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));

    }

    public function getCategories()
    {
        $requetePreparée = $this->connexion->prepare('select * from `ps_category_lang` order by id_category ') ;
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        return $tableauReponse;
    }


    public function getProductsByCategory($idCategorie)
    {
        $requetePreparée = $this->connexion->prepare('
            select * 
            from `ps_product_lang` inner join `ps_product`
                on ps_product_lang.id_product = ps_product.id_product
            where id_category_default  = :paramIdCategorie') ;

        $requetePreparée->bindParam('paramIdCategorie',$idCategorie);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        return $tableauReponse;
    }

    // ????
    public function getAttributeByProduct($idProduct)
    {
        $requetePreparée = $this->connexion->prepare('SELECT * FROM ps_product_lang p INNER JOIN 
                                ps_product_attribute pa ON p.id_product = pa.id_product INNER JOIN 
                                ps_product_attribute_combination pac ON pa.id_product_attribute = pac.id_product_attribute
                                INNER JOIN ps_attribute_lang pal ON pal.id_attribute = pac.id_attribute
                                where p.id_product = :paramidProduct AND p.id_lang = 1') ;
        $requetePreparée->bindParam('paramidProduct',$idProduct);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        return $tableauReponse;
    }

    public function existCategorie($idCategorie){
        $requetePreparée = $this->connexion->prepare('select * from `ps_category_lang` where `id_category` =  :paramIdCategorie') ;
        $requetePreparée->bindParam('paramIdCategorie',$idCategorie);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        if ($tableauReponse){
            return true;
        }else{
            return false;
        }
    }

    public function existProduct($idProduit){
        $requetePreparée = $this->connexion->prepare('
            select * 
            from `ps_product_lang` inner join `ps_product`
                on ps_product_lang.id_product = ps_product.id_product
            where ps_product_lang.id_product  = :paramIdProduct') ;
        $requetePreparée->bindParam('paramIdProduct',$idProduit);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        if ($tableauReponse){
            return true;
        }else{
            return false;
        }
    }


    public function existAttribute($idAttribute){
        $requetePreparée = $this->connexion->prepare('SELECT * FROM ps_product_lang p INNER JOIN 
                                ps_product_attribute pa ON p.id_product = pa.id_product INNER JOIN 
                                ps_product_attribute_combination pac ON pa.id_product_attribute = pac.id_product_attribute
                                INNER JOIN ps_attribute_lang pal ON pal.id_attribute = pac.id_attribute
                                where pal.id_attribute = :paramidAttribute AND pal.id_lang = 1') ;
        $requetePreparée->bindParam('paramidAttribute',$idAttribute);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        if ($tableauReponse){
            return true;
        }else{
            return false;
        }
    }

    public function existProductAttribute($idProductAttribute){
        $requetePreparée = $this->connexion->prepare('SELECT * FROM ps_product_lang p INNER JOIN 
                                ps_product_attribute pa ON p.id_product = pa.id_product INNER JOIN 
                                ps_product_attribute_combination pac ON pa.id_product_attribute = pac.id_product_attribute
                                INNER JOIN ps_attribute_lang pal ON pal.id_attribute = pac.id_attribute
                                where pac.id_product_attribute = :paramidProductAttribute AND pal.id_lang = 1') ;
        $requetePreparée->bindParam('paramidProductAttribute',$idProductAttribute);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        if ($tableauReponse){
            return true;
        }else{
            return false;
        }
    }
}