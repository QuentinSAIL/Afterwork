import axios from "../config/axios";

const newProduct = (libelle_produit, id_tva, id_categorie, description_produit, prix_unitaire_HT, image, activation, Import) => {
    return axios
        .post("/create/product",{"libelle_produit":libelle_produit, "id_tva":id_tva, "id_categorie":id_categorie, "description_produit":description_produit,
            "prix_unitaire_HT":prix_unitaire_HT, "image":image, "activation":activation, "import":Import})
        .then(response => response.data)
}

const newCategory = (libelle_categorie, description_categorie, activation, Import) => {
    return axios
        .post("/create/category",{"libelle_categorie":libelle_categorie, "description_categorie":description_categorie,
            "activation":activation, "import":Import})
        .then(response => response.data)
}



const updateProduct = (id, libelle_produit, id_tva, id_categorie, description_produit, prix_unitaire_HT, image, activation, Import) => {
    return axios
        .post(`modifier/produit/${id}`,{"id_produit":id, "libelle_produit":libelle_produit,
            "id_tva":id_tva, "id_categorie":id_categorie, "description_produit":description_produit,
            "prix_unitaire_HT":prix_unitaire_HT, "image":image, "activation":activation, "import":Import})
        .then(response => response.data)
}

// eslint-disable-next-line import/no-anonymous-default-export
export default { newProduct, updateProduct, newCategory }