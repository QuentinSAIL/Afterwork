import axios from "../config/axios"
import jwtDecode from "jwt-decode";

const logIn = (mail,mot_de_passe) => {
    return axios
        .post("connexion",{"mail":mail,"mot_de_passe":mot_de_passe})
        .then(response => response.data.token)
};

const logOut = () => {
    localStorage.removeItem('token')
}

const getPayload = () => {
    if (isAuthenticated()) {
        const token = localStorage.getItem("token")
        return jwtDecode(token)
    } else {
        return "l'utilisateur n'est pas identifié"
    }
}

const getIdRoleEmploye = (UserId) => {
    return axios
        .get(`employe/${UserId}`)
        .then(response => response.data[0]["idRole"]["idRole"])
}

const getIdRoleClient = (UserId) => {
    return axios
        .get(`client/${UserId}`)
        .then(response => response.data[0]["roles"][0])
}

const isAuthenticated = () => {
    const token = localStorage.getItem('token')
    if (token) {
        const payload = jwtDecode(token)
        return payload.exp*1000 > new Date().getTime()
    }
    return false
}

const newClient = (nom,prenom,password,email,tel) => {
    return axios
        .post("inscription",{"nom":nom,"prenom":prenom,"password":password,"email":email,"tel":tel})
        .then(response => response.data)
}

const newEmploye = (idRole,nom,prenom,password,email,libRue,CPVille,Ville,tel,dateEmbauche) => {
    return axios
        .post("inscriptionEmp",{"idRole":idRole,"nom":nom,"prenom":prenom,"password":password,"email":email,
            "libRue":libRue,"CPVille":CPVille,"ville":Ville,"tel":tel,"dateEmbauche":dateEmbauche})
        .then(response => response.data)
}

const resetMDP = (UserId) => {
    return axios
        .get(`reset/mdp/${UserId}`)
        .then(response => response.data)
}

const getInfoClient = (UserId) => {
    return axios
        .get(`client/${UserId}`)
        .then(response => response.data)
}


// eslint-disable-next-line import/no-anonymous-default-export
export default { logIn, logOut, isAuthenticated, newClient, newEmploye, getIdRoleEmploye, getIdRoleClient, getPayload, resetMDP, getInfoClient }