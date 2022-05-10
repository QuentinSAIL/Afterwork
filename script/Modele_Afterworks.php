<?php
class Modele_Afterworks{


    private $connexion = null;

    public function __construct()
    {
        $this->connexion = new PDO('mysql:host=127.0.0.1;dbname=afterwork;charset=LATIN1',  "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));
    }

    //// CATEGORIE

    public function getCategories()
    {
        $requetePreparée = $this->connexion->prepare('select * from `categorie` order by id ') ;
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        return $tableauReponse;
    }

    public function existCategory($id)
    {
        $requetePreparée = $this->connexion->prepare('select * from `categorie` where id=:paramId ') ;
        $requetePreparée->bindParam('paramId', $id);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        if($tableauReponse) {
            return true;
        }else {
            return false;
        }
    }

    public function insertCategory($idCat, $libelleCat)
    {
        $requetePreparée =  $this->connexion->prepare(
            'INSERT INTO `categorie` (`id`,`libelle`)
         VALUES (:paramId, :paramLibelle);');
        $requetePreparée->bindParam('paramId',$idCat);
        $requetePreparée->bindParam('paramLibelle',$libelleCat);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function deleteCategory($id){

        $requetePreparée = $this->connexion->prepare('DELETE from `categorie` where id=:paramId ') ;
        $requetePreparée->bindParam('paramId', $id);
        $requetePreparée2 = $this->connexion->prepare('UPDATE `produit` SET idCategorie = NULL WHERE idCategorie=:paramId ') ;
        $requetePreparée2->bindParam('paramId', $id);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $reponse = $requetePreparée2->execute(); //$reponse boolean sur l'état de la requête
    }


    //// PRODUIT

    public function existProduct($id)
    {
        $requetePreparée = $this->connexion->prepare('select * from `produit` where id=:paramId ') ;
        $requetePreparée->bindParam('paramId', $id);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        if($tableauReponse) {
            return true;
        }else {
            return false;
        }
    }

    public function insertProduct($idProd, $nomProd, $marqueProd, $imageProd, $descriptionProd, $idCategorieProd, $dispoProd )
    {
        $requetePreparée =  $this->connexion->prepare(
            'INSERT INTO `produit` (`id`, `nom` ,`marque`, `image`, `description`, `idCategorie`, `disponibilite`) 
         VALUES (:paramId, :paramNom, :paramMarque, :paramImage, :paramDescription, :paramIdCategorie, :paramDispo);');
        $requetePreparée->bindParam('paramId',$idProd);
        $requetePreparée->bindParam('paramNom',$nomProd);
        $requetePreparée->bindParam('paramMarque',$marqueProd);
        $requetePreparée->bindParam('paramImage',$imageProd);
        $requetePreparée->bindParam('paramDescription',$descriptionProd);
        $requetePreparée->bindParam('paramIdCategorie',$idCategorieProd);
        $requetePreparée->bindParam('paramDispo',$dispoProd);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function deleteProduct($id){

        $requetePreparée = $this->connexion->prepare('DELETE from `produit` where id=:paramId ') ;
        $requetePreparée->bindParam('paramId', $id);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
    }


    //DECLINAISON

    public function existAttribute($idAttribute)
    {
        $requetePreparée = $this->connexion->prepare('SELECT * FROM declinaison where id = :paramidAttribute') ;
        $requetePreparée->bindParam('paramidAttribute',$idAttribute);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        if ($tableauReponse){
            return true;
        }else{
            return false;
        }
    }

    public function insertAttribute($id,$libelle)
    {
        $requetePreparée = $this->connexion->prepare('INSERT INTO `declinaison` (`id`, `libelle`) VALUES (:paramId,:paramLibelle);') ;
        $requetePreparée->bindParam('paramId', $id);
        $requetePreparée->bindParam('paramLibelle', $libelle);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
    }


    public function deleteAttribute($id){

        $requetePreparée = $this->connexion->prepare('DELETE from `declinaison` where id=:paramId ') ;
        $requetePreparée->bindParam('paramId', $id);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
    }


    public function deleteProductAttribut($id)
    {
        $requetePreparée = $this->connexion->prepare('DELETE from `produit_declinaison` where id=:paramId ');
        $requetePreparée->bindParam('paramId', $id);
        $requetePreparée2 = $this->connexion->prepare('UPDATE `commande_en_ligne` SET idPoduitDeclinaison = NULL WHERE idPoduitDeclinaison=:paramId ');
        $requetePreparée2->bindParam('paramId', $id);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $reponse = $requetePreparée2->execute(); //$reponse boolean sur l'état de la requête
    }

    public function insertProductAttribute($id, $idProduit, $idDeclinaison, $prix)
    {
        $requetePreparée = $this->connexion->prepare(
            'INSERT INTO `produit_declinaison` (`id`, `idProduit`, `idDeclinaison`, `prix`) 
                    VALUES (:paramId, :paramIdProduit, :paramIdDeclinaison, :paramPrix);');
        $requetePreparée->bindParam('paramId', $id);
        $requetePreparée->bindParam('paramIdProduit', $idProduit);
        $requetePreparée->bindParam('paramIdDeclinaison', $idDeclinaison);
        $requetePreparée->bindParam('paramPrix', $prix);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
    }

    public function getProductAttributeByIdproduct($id)
    {
        $requetePreparée = $this->connexion->prepare('select * from `produit_declinaison` where idProduit=:paramId ');
        $requetePreparée->bindParam('paramId', $id);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparée->fetchAll(PDO::FETCH_ASSOC);
        return $tableauReponse;
    }
}



