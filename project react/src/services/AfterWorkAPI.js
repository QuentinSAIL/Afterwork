import axios from "../config/axios"

const getAllRole = () => {
    return axios
        .get('roles')
        .then(response => response.data)
};

const getAllProduits = () => {
    return axios
        .get('produits')
        .then(response => response.data)
};

const getAllCategorie = () => {
    return axios
        .get('categorie')
        .then(response => response.data)
};

const getTva = () => {
    return axios
        .get('tva')
        .then(response => response.data)
};

const getDetailsProduit = (id) => {
    return axios
        .get(`produits/details/${id}`)
        .then(response => response.data)
};

const getCommentairesProduit = (idProduct) => {
    return axios
        .get(`commentaires/${idProduct}`)
        .then(response => response.data)
};

const InsertCommentairesProduit = (idProduct, idUser, titre, description, dateTime) => {
    return axios
        .post(`create/comment/${idProduct}`,{"id_client":idUser, "id_produit":idProduct, "titre":titre, "description":description, "date":dateTime})
        .then(response => response.data)
};

const getAllRubriques = () => {
    return axios
        .get('/rubriques')
        .then(response => response.data)
}

const getArticlesByRubrique = (idRubrique) => {
    return axios
        .get(`articles/${idRubrique}`)
        .then(response => response.data)
};

const getDetailsRubrique = (idRubrique) => {
    return axios
        .get(`rubrique/${idRubrique}`)
        .then(response => response.data)
};

const InsertRubrique = (titre, description) => {
    return axios
        .post('create/rubrique',{"titre":titre, "description":description})
        .then(response => response.data)
};

const InsertArticle = (titre, id_employe, contenu, date_creation_article, id_rubrique) => {
    return axios
        .post('create/article',{"titre":titre,"id_employe":id_employe ,"contenu":contenu,"date_creation_article":date_creation_article, "id_rubrique":id_rubrique})
        .then(response => response.data)
};

const DeleteRubrique = (idRubrique) => {
    return axios
        .get(`delete/rubrique/${idRubrique}`)
        .then(response => response.data)
}

const DeleteArticle = (idArticle) => {
    return axios
        .get(`delete/article/${idArticle}`)
        .then(response => response.data)
}

const getAllEmployes = () => {
    return axios
        .get('employes')
        .then(response => response.data)
}

const getAllClients = () => {
    return axios
        .get('clients')
        .then(response => response.data)
}

const modifNewsLetter = (idClient) => {
    return axios
        .post(`abonnementNewsletter/${idClient}`)
        .then(response => response.data)
}

const validateBasket = (noTable, produitetquantite) => {
    return axios
        .post(`create/commande`,{"no_table":noTable, "quantiteProd":produitetquantite})
        .then(response => response.data)
}


// eslint-disable-next-line import/no-anonymous-default-export
export default { validateBasket, getAllProduits, getDetailsProduit, getAllCategorie, getTva, getCommentairesProduit, InsertCommentairesProduit, getAllRubriques,
    getArticlesByRubrique, getDetailsRubrique, InsertRubrique, DeleteRubrique, getAllEmployes, getAllClients, modifNewsLetter, getAllRole , DeleteArticle, InsertArticle}