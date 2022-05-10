import axios from "../config/axios"

const logIn = (mail,mot_de_passe) => {
    return axios
        .post("/connexionEmp",{"mail":mail,"mot_de_passe":mot_de_passe})
        .then(response => response.data.token)
};

export default {logIn};