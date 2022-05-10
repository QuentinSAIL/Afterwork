<?php

class Modele_Afterworks
{

    private $connexion = null;

    public function __construct()
    {
        $this->connexion = new PDO('mysql:host=127.0.0.1;dbname=afterwork', "root", "", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    public function inscriptionClient($user)
    {
        //`lib_rue_client`, `CP_client`, `ville_client`, `abonnement_newsletter`,
        $requetePreparée =  $this->connexion->prepare(
            'INSERT INTO `client` (`nom_client`, `prenom_client`, `mot_de_passe`, `mail_client`,
                       `tel_client`, `id_role`) 
         VALUES (:paramNom, :paramPrenom, :paramPassword, :paramMail, :paramTel, 3)');
        $requetePreparée->bindParam('paramNom',$user['nom']);
        $requetePreparée->bindParam('paramPrenom',$user['prenom']);
        $requetePreparée->bindParam('paramPassword',$user['password']);
        $requetePreparée->bindParam('paramMail',$user['email']);
        $requetePreparée->bindParam('paramTel',$user['tel']);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function inscriptionEmploye($user)
    {
        //`lib_rue_client`, `CP_client`, `ville_client`, `abonnement_newsletter`,
        $requetePreparée =  $this->connexion->prepare(
            'INSERT INTO `employe` (`id_role`, `nom`, `prenom`, `mot_de_passe`, `mail`, `lib_rue`, `CP_ville`, `ville`, `tel`, `date_embauche`) 
         VALUES (:paramIdRole, :paramNom, :paramPrenom, :paramPassword, :paramMail, :paramLibRue, :paramCPVille, :paramVille, :paramTel, :paramDateEmbauche)');
        $requetePreparée->bindParam('paramIdRole',$user['idRole']);
        $requetePreparée->bindParam('paramNom',$user['nom']);
        $requetePreparée->bindParam('paramPrenom',$user['prenom']);
        $requetePreparée->bindParam('paramPassword',$user['password']);
        $requetePreparée->bindParam('paramMail',$user['email']);
        $requetePreparée->bindParam('paramLibRue',$user['libRue']);
        $requetePreparée->bindParam('paramCPVille',$user['CPVille']);
        $requetePreparée->bindParam('paramVille',$user['ville']);
        $requetePreparée->bindParam('paramTel',$user['tel']);
        $requetePreparée->bindParam('paramDateEmbauche',$user['dateEmbauche']);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function insertProduct($produit )
    {
        $requetePreparée =  $this->connexion->prepare(
            'INSERT INTO `produit` (`id_produit`, `libelle_produit` ,`id_tva`, `id_categorie`, `description_produit`,
                       `prix_unitaire_HT`, `image`, `activation`, `import`) 
         VALUES (:paramId, :paramLibelle, :paramIdTva, :paramIdCategorie, :paramDescription, :paramPrix, :paramImage , :paramActivation, :paramImport);');
        $requetePreparée->bindParam('paramId',$produit['id_produit']);
        $requetePreparée->bindParam('paramLibelle',$produit['libelle_produit']);
        $requetePreparée->bindParam('paramIdTva',$produit['id_tva']);
        $requetePreparée->bindParam('paramIdCategorie',$produit['id_categorie']);
        $requetePreparée->bindParam('paramDescription',$produit['description_produit']);
        $requetePreparée->bindParam('paramPrix',$produit['prix_unitaire_HT']);
        $requetePreparée->bindParam('paramImage',$produit['image']);
        $requetePreparée->bindParam('paramActivation',$produit['activation']);
        $requetePreparée->bindParam('paramImport',$produit['import']);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function insertRubrique($produit )
    {
        $requetePreparée =  $this->connexion->prepare(
            'INSERT INTO `rubrique` (`id_rubrique`, `titre` ,`description`) 
         VALUES (:paramId, :paramTitre, :paramDescription);');
        $requetePreparée->bindParam('paramId',$produit['id_rubrique']);
        $requetePreparée->bindParam('paramTitre',$produit['titre']);
        $requetePreparée->bindParam('paramDescription',$produit['description']);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function insertArticle($produit )
    {
        $requetePreparée =  $this->connexion->prepare(
            'INSERT INTO `article` (`id_article`, `titre` ,`id_employe`, `contenu`, `date_création_article`, `id_rubrique`) 
         VALUES (:paramId, :paramTitre, :paramId_employe, :paramContenu, :paramDate_creation_article, :paramId_rubrique);');
        $requetePreparée->bindParam('paramId',$produit['id_article']);
        $requetePreparée->bindParam('paramTitre',$produit['titre']);
        $requetePreparée->bindParam('paramId_employe',$produit['id_employe']);
        $requetePreparée->bindParam('paramContenu',$produit['contenu']);
        $requetePreparée->bindParam('paramDate_creation_article',$produit['date_creation_article']);
        $requetePreparée->bindParam('paramId_rubrique',$produit['id_rubrique']);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function insertComment($commentaire)
    {
        $requetePreparée =  $this->connexion->prepare(
            'INSERT INTO `commentaire` (`id_commentaire`, `id_client` ,`id_produit`, `titre`, `description`, `date`) 
         VALUES (:paramIdCommentaire, :paramIdClient, :paramIdProduit, :paramTitre, :paramDescription, :paramDate);')
        ;
        $requetePreparée->bindParam('paramIdCommentaire',$commentaire['id_commentaire']);
        $requetePreparée->bindParam('paramIdClient',$commentaire['id_client']);
        $requetePreparée->bindParam('paramIdProduit',$commentaire['id_produit']);
        $requetePreparée->bindParam('paramTitre',$commentaire['titre']);
        $requetePreparée->bindParam('paramDescription',$commentaire['description']);
        $requetePreparée->bindParam('paramDate',$commentaire['date']);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function insertCommand($command)
    {
        $date = date('Y-m-d H:i:s');
        $requetePreparée =  $this->connexion->prepare(
            'INSERT INTO `commande` (`id_statut` ,`id_employe`, `date_commande`, `no_table`) 
         VALUES (1, 0, :paramDate_commande, :paramNo_table);');
        $requetePreparée->bindParam('paramDate_commande',$date);
        $requetePreparée->bindParam('paramNo_table',$command['no_table']);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function getLastCommand() {
        $requetePreparee =  $this->connexion->prepare(
            'SELECT `id_commande`
                   FROM commande
                   WHERE `date_commande` = (SELECT max(`date_commande`)FROM commande)
                    ');
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparee->fetch(PDO::FETCH_ASSOC);
        return $tableauReponse;
    }

    public function insertProductInCommand($idCommand,$idProd,$quantiteProd,$prix,$montantTVA)
    {
        $requetePreparée =  $this->connexion->prepare(
            'INSERT INTO `produit_commande` (`id_commande` ,`id_produit`, `quantité_produit`, `prix_HT`, `montant_TVA`) 
         VALUES (:paramId_commande, :paramId_produit, :paramQuantiteProd, :paramPrix, :paramMontantTVA);');
        $requetePreparée->bindParam('paramId_commande',$idCommand);
        $requetePreparée->bindParam('paramId_produit',$idProd);
        $requetePreparée->bindParam('paramQuantiteProd',$quantiteProd);
        $requetePreparée->bindParam('paramPrix',$prix);
        $requetePreparée->bindParam('paramMontantTVA',$montantTVA);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }


    public function updateProduct($produit, $idProduit)
    {
        $requetePreparee =  $this->connexion->prepare(
            'UPDATE `produit` 
                   SET libelle_produit=:paramLibelle, id_tva=:paramIdTva, id_categorie=:paramIdCategorie, description_produit=:paramDescription, 
                       prix_unitaire_HT=:paramPrix, image=:paramImage , activation=:paramActivation, import=:paramImport
                   WHERE id_produit =:paramId');
        $requetePreparee->bindParam('paramId',$idProduit);
        $requetePreparee->bindParam('paramLibelle',$produit['libelle_produit']);
        $requetePreparee->bindParam('paramIdTva',$produit['id_tva']);
        $requetePreparee->bindParam('paramIdCategorie',$produit['id_categorie']);
        $requetePreparee->bindParam('paramDescription',$produit['description_produit']);
        $requetePreparee->bindParam('paramPrix',$produit['prix_unitaire_HT']);
        $requetePreparee->bindParam('paramImage',$produit['image']);
        $requetePreparee->bindParam('paramActivation',$produit['activation']);
        $requetePreparee->bindParam('paramImport',$produit['import']);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
    }

    public function insertCategory($category)
    {
        $requetePreparée =  $this->connexion->prepare(
            'INSERT INTO `categorie` (`id_categorie`, `libelle_categorie`, `description_categorie`, `activation`, `import`) 
         VALUES (:paramId, :paramLibelle, :paramDescription, :paramActivation, :paramImport);');
        $requetePreparée->bindParam('paramId',$category['id_categorie']);
        $requetePreparée->bindParam('paramLibelle',$category['libelle_categorie']);
        $requetePreparée->bindParam('paramDescription',$category['description_categorie']);
        $requetePreparée->bindParam('paramActivation',$category['activation']);
        $requetePreparée->bindParam('paramImport',$category['import']);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function deleteArticle($idArticle)
    {
        $requetePreparée =  $this->connexion->prepare(
            'DELETE FROM `article` WHERE id_article = :paramIdArticle');
        $requetePreparée->bindParam('paramIdArticle',$idArticle);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function deleteRubrique($idRubrique)
    {
        $requetePreparée =  $this->connexion->prepare(
            'DELETE FROM `rubrique` WHERE id_rubrique = :paramIdRubrique');
        $requetePreparée->bindParam('paramIdRubrique',$idRubrique);
        $reponse = $requetePreparée->execute(); //$reponse boolean sur l'état de la requête

    }

    public function resetMDP($idUser)
    {
        $requetePreparee =  $this->connexion->prepare(
            'UPDATE `employe` 
                   SET mot_de_passe=:paramMDP
                   WHERE id_employe =:paramId');
        $NouveauMotDePasse = '$argon2i$v=19$m=16,t=2,p=1$d3pFUVliV1hZdHZmeTZjNnRCR3pLU1ZQWG9KRjBhSUk$reYlkBDkMwd0aCZ9nqRKdQ';
        $requetePreparee->bindParam('paramMDP',$NouveauMotDePasse);
        $requetePreparee->bindParam('paramId',$idUser);

        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
    }

    public function updateNewsLetter($value, $idClient)
    {
        $requetePreparee =  $this->connexion->prepare(
            'UPDATE `client` 
                   SET abonnement_newsletter=:paramNews
                   WHERE id_client =:paramId');
        $requetePreparee->bindParam('paramNews',$value);
        $requetePreparee->bindParam('paramId',$idClient);

        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
    }
    public function getCommandProducts($idCommand)
    {
        $requetePreparee =  $this->connexion->prepare(
            'SELECT * 
                   FROM produit_commande, produit
                   WHERE produit.id_produit = produit_commande.id_produit
                   AND id_commande =:paramIdCommand');
        $requetePreparee->bindParam('paramIdCommand',$idCommand);
        $reponse = $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
        $tableauReponse = $requetePreparee->fetchAll(PDO::FETCH_ASSOC);
        return $tableauReponse;
    }

    public function CommandPlusOneIdStatut($idCommand, $idEmploye)
    {
        $requetePreparee =  $this->connexion->prepare(
            'UPDATE `COMMANDE` 
                   SET id_statut=id_statut+1, id_employe =:paramIdEmploye
                   WHERE id_commande =:paramIdCommand');
        $requetePreparee->bindParam('paramIdCommand',$idCommand);
        $requetePreparee->bindParam('paramIdEmploye',$idEmploye);
        return $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
    }

    public function CommandSetStatut($idCommand, $idStatut, $idEmploye)
    {
        $requetePreparee =  $this->connexion->prepare(
            'UPDATE `COMMANDE` 
                   SET id_statut = :paramIdStatut, id_employe =:paramIdEmploye
                   WHERE id_commande = :paramIdCommand');
        $requetePreparee->bindParam('paramIdCommand',$idCommand);
        $requetePreparee->bindParam('paramIdStatut',$idStatut);
        $requetePreparee->bindParam('paramIdEmploye',$idEmploye);
        return $requetePreparee->execute(); //$reponse boolean sur l'état de la requête
    }

    public function CommandDelete($idCommand)
    {
        $requetePreparee1 =  $this->connexion->prepare(
            'DELETE 
                   FROM produit_commande
                   WHERE id_commande = :paramIdCommand');

        $requetePreparee2 =  $this->connexion->prepare(
            'DELETE 
                   FROM commande
                   WHERE id_commande = :paramIdCommand');

        $requetePreparee1->bindParam('paramIdCommand',$idCommand);
        $requetePreparee2->bindParam('paramIdCommand',$idCommand);
        $requetePreparee1->execute(); //$reponse boolean sur l'état de la requête
        $requetePreparee2->execute(); //$reponse boolean sur l'état de la requête

        if ($requetePreparee1 == true && $requetePreparee2 == true) {
            return true;
        }else{
            return false;
        }
    }

}