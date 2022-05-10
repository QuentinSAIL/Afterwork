import axios from "../config/axios"

const getAllProducts = () => {
    return axios
        .get('produits')
        .then(response => response.data)
};

const getDetailsProduct = (id) => {
    return axios
        .get(`produits/details/${id}`)
        .then(response => response.data)
};

export default {getAllProducts,getDetailsProduct}