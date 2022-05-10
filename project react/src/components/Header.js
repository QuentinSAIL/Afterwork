import React, {useContext, useEffect, useState} from 'react';
import {Link} from "react-router-dom";
import Credentials from "../services/Credentials";
import AuthenticationContext from "../config/credentialsContext";
import AfterWorkAPI from "../services/AfterWorkAPI";

const Header = () => {

    const {isAuthenticated, setIsAuthenticated} = useContext(AuthenticationContext);
    const [rubriques, setRubriques] = useState([]);
    const [client, setClient] = useState([]);
    const [role, setRole] = useState([]);

    const fetchRubriques = async () => {
        try {
            const _rubriques = await AfterWorkAPI.getAllRubriques()
            //console.log(_rubriques)
            setRubriques(_rubriques)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchRubriques();
    }, [])

    const fetchClients = async () => {
        try {
            const token = Credentials.getPayload()
            const idClient = token["id_client"]
            //console.log(idClient)
            const _client = await Credentials.getInfoClient(idClient)
            //console.log(_client)
            setClient(_client)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchClients();
    }, [])

    const handleLogOut = () => {
        Credentials.logOut()
        setIsAuthenticated(false);    // setIsAuthenticated(false)

    }

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

    const UpdateNewsLetter = () => {
        const token = Credentials.getPayload()
        AfterWorkAPI.modifNewsLetter(token["id_client"])
        window.location.reload();
    }

    return (
        <>
            <ul className="nav nav-tabs">
                {(!isAuthenticated && (
                    <>
                        <li>
                            <Link className="btn btn-success me-2" to="/connexion">Connexion</Link>
                        </li>
                        <li>
                            <Link className="btn btn-warning me-2" to="/inscription">Inscription</Link>
                        </li>
                    </>
                )) || (
                    <li>
                        <button onClick={handleLogOut} className="btn btn-danger me-2">Deconnexion
                        </button>
                    </li>
                )}
                <div className="btn-group">
                    <Link type="button" className="btn" to="/rubriques">Rubriques</Link>
                    <button type="button" className="btn border dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown" aria-expanded="false">
                    </button>
                    <ul className="dropdown-menu">
                        {rubriques.map(rubrique => {
                            return <li key={rubrique.idRubrique}>
                                <a className="dropdown-item"
                                      href={`/articles/${rubrique.idRubrique}`}>{rubrique.titre}</a>
                            </li>
                        })}
                        {(((role === 4 || role === 1) && isAuthenticated) && (
                            <>
                                <li>
                                    <hr className="dropdown-divider"/>
                                </li>
                                <li>
                                    <Link className="dropdown-item" value="all" to={`/ajouter/rubrique`}>Ajouter une rubrique
                                    </Link>
                                </li>
                            </>
                        ))}
                    </ul>
                </div>
                {((role === 1) && isAuthenticated && (
                <div>
                    <Link type="button" className="btn" to="/administrer">Administrer</Link>
                </div>
                ))}
                {(((role === 3 && isAuthenticated) && client[0]["abonnementNewsletter"] === true) && (
                    <button className="btn btn-success" onClick={UpdateNewsLetter}>
                        Newsletter
                    </button>
                ))}
                {(((role === 3 && isAuthenticated) && client[0]["abonnementNewsletter"] === false) && (
                    <button className="btn btn-warning" onClick={UpdateNewsLetter}>
                        Newsletter
                    </button>
                ))}
                <ul className="navbar-nav mx-auto">
                    <li className="nav justify-content-center">
                        <Link to={"/"}><h2>Afterwork</h2></Link>
                    </li>
                </ul>
                <ul className="nav nav-tabs mt-1">
                    <li className="px-4">
                        <Link to="/panier"><a href="/panier"><img className="icone" src="https://cdn-icons-png.flaticon.com/512/118/118089.png" alt="panier"/></a></Link>
                    </li>
                </ul>
            </ul>
        </>
    )
}

export default Header;
