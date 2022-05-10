import React, {useContext, useEffect, useState} from 'react';
import AfterWorkAPI from "../services/AfterWorkAPI";
import {Link} from "react-router-dom";
import AuthenticationContext from "../config/credentialsContext";
import Credentials from "../services/Credentials";

const Accueilcategorie = () => {
    const [categories, setCategories] = useState([]);
    const [role, setRole] = useState([]);
    const isAuthenticated = useContext(AuthenticationContext);

    const fetchCategories = async () => {
        try {
            const _categories = await AfterWorkAPI.getAllCategorie()
            //console.log(_categories)
            setCategories(_categories)
        } catch (error) {
            //console.log(error)
        }
    }

    useEffect(() => {
        fetchCategories();
    }, [])


    const fetchRole = async () => {
        let _role = ''
        const token = await Credentials.getPayload()
        if (token["client"] === false) {
            _role = await Credentials.getIdRoleEmploye(token["id_employe"])
        }
        if (token["client"] === true) {
            _role = await Credentials.getIdRoleClient(token["id_client"])
        }
        setRole(_role)
    }
    useEffect(() => {
        fetchRole();
    }, [])


    return (
        <>
            <h1 className="text-center mt-3">Page d'accueil des categories</h1>
            {((role === 1 || (role === 2)) && isAuthenticated && (
                <div className="text-center">
                    <Link className="mt-3 btn btn-light border bg-success" to={`ajouter/categorie`}>Ajouter une
                        categorie</Link>
                </div>
            ))}

            {categories.map(categorie => {
                return <div key={categorie.idCategorie} className="card">
                    <div className="card-body">
                        {(categorie.activation === 0 && (<h5>inactif</h5>))}
                        <h5 className="card-title">Libellé : {categorie.libelleCategorie}</h5>
                        {(categorie.descriptionCategorie && (
                            <p className="card-text"><b className="fs-4">Description
                                : </b>{categorie.descriptionCategorie}</p>
                        ))}
                    </div>
                </div>
            })}
        </>
    )
}

export default Accueilcategorie;