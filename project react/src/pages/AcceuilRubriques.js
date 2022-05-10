import React, {useContext, useEffect, useState} from 'react';
import AfterWorkAPI from "../services/AfterWorkAPI";
import {Link} from "react-router-dom";
import AuthenticationContext from "../config/credentialsContext";
import Credentials from "../services/Credentials";

const AccueilRubriques = () => {
    const [rubriques, setRubriques] = useState([]);
    const [role, setRole] = useState([]);
    const isAuthenticated = useContext(AuthenticationContext);

    const fetchRubriques = async () => {
        try {
            const _rubriques = await AfterWorkAPI.getAllRubriques()
            //console.log(_rubriques)
            setRubriques(_rubriques)
        } catch (error) {
            //console.log(error)
        }
    }

    useEffect(() => {
        fetchRubriques();
    }, [])


    const fetchRole = async () => {
        try {
            const token = Credentials.getPayload()
            //console.log(token)
            const _role = await Credentials.getIdRoleEmploye(token["id_employe"])
            setRole(_role)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchRole();
    }, [])

    const SupprimerRubrique = (e) => {
        var IdRubrique = e.target.value
        AfterWorkAPI.DeleteRubrique(IdRubrique)
        window.location.reload();
    }

    return (
        <>
            <h1 className="text-center mt-3">Page d'accueil des rubriques</h1>
            {((role === 1 || (role === 4)) && isAuthenticated && (
                <div className="text-center">
                    <Link className="mt-3 btn btn-light border bg-success" to={`/ajouter/rubrique`}>Ajouter une
                        rubrique</Link>
                </div>
            ))}

            {rubriques.map(rubrique => {
                return <div key={rubrique.titre} className="card">
                    <div className="card-body">
                        <h5 className="card-title">Libellé : {rubrique.titre}</h5>
                        {((role === 1 || (role === 4)) && isAuthenticated && (
                            <button className="btn btn-warning position-absolute top-0 end-0"
                                    value={rubrique.idRubrique}
                                    onClick={SupprimerRubrique}>Supprimer
                            </button>
                        ))}
                        {(rubrique.description && (
                            <p className="card-text"><b className="fs-4">Description
                                : </b>{rubrique.description}</p>
                        ))}
                    </div>
                </div>
            })}
        </>
    )
}

export default AccueilRubriques;