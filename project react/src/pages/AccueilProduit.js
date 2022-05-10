import React, {useContext, useEffect, useState} from 'react';
import AfterWorkAPI from "../services/AfterWorkAPI";
import {Link} from "react-router-dom";
import AuthenticationContext from "../config/credentialsContext";
import Credentials from "../services/Credentials";

const AccueilProduit = () => {
    const [produits, setProduits] = useState([]);
    const [role, setRole] = useState([]);

    const fetchProduits = async () => {
        try {
            const _produits = await AfterWorkAPI.getAllProduits()
            //console.log(_produits)
            setProduits(_produits)
        } catch (error) {
            //console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchProduits();
    }, [])

    const [categories, setCategories] = useState([]);
    const [produitCateg, setProduitCateg] = useState([]);
    const [currentCateg, setCurrentCateg] = useState("");
    const {isAuthenticated} = useContext(AuthenticationContext);

    const fetchCategories = async () => {
        try {
            const _categories = await AfterWorkAPI.getAllCategorie()
            //console.log(_categories)
            setCategories(_categories)
        } catch (error) {
            //console.log('erreur : ' + error)
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
            setRole(_role)
        }else if (token["client"] === true) {
            _role = await Credentials.getIdRoleClient(token["id_client"])
            setRole(_role)
        }
    }
    useEffect(() => {
        fetchRole();
    }, [])

    const addToBasket =(e) => {
        var idProduit = e.target.value
        var panier = [idProduit]
        panier.push(localStorage.getItem("panier"))
        localStorage.setItem("panier", panier.join("#"))
        return alert("Produit ajouté au panier !")
    }

    const handleAfficherCateg = (e) => {
        var valueCateg = e.target.value
        setCurrentCateg(valueCateg)
        // eslint-disable-next-line array-callback-return
        setProduitCateg(produits.map(produit => {
            if (valueCateg == produit.idCategorie["idCategorie"] || valueCateg === 'all') {
                if ((produit["activation"] === true && produit.idCategorie.activation === true) || (role === 1 && isAuthenticated)) {
                    return <div className="col-sm-6 text-center" key={produit.idProduit}>
                        <div className="card shadow-lg p-3 mb-5 bg-body rounded ">
                            <div className="card-body">
                                {(produit["activation"] === false && (
                                    <h5 className="color-warning">inactif</h5>
                                ))}
                                <img className="imageBoutique"
                                     src="https://petitboutdechou.fr/wp-content/uploads/2019/03/ennergisant5.png"
                                     alt="le produit"/>
                                <h5 className="card-title">{produit.libelleProduit}</h5>
                                <div className="d-grid gap-2 d-md-block">
                                    <button onClick={addToBasket} value={produit.idProduit+";"+produit.libelleProduit} className="btn btn-primary">Ajouter au panier

                                    </button>
                                    <Link to={`/produits/details/${produit.idProduit}`}
                                          className="btn btn-light">Détails</Link>
                                    {((role === 1 && isAuthenticated) && (
                                        <Link to={`/modifier/produit/${produit.idProduit}`}
                                              className="btn btn-warning">Modifier</Link>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                }
            }
        }))
    }
    return (
        <>
            <h1 className="text-center mt-3">Les produits</h1>
            <div className="container mt-5">
                <div className="row">
                    {((!currentCateg) && (
                        <div className="text-center fs-2">Séléctionnez une catégorie </div>
                    ))}
                    <div className="col border">
                        <div className="btn-group mt-3">
                            <Link type="button" className="btn border" to="/categories">Categories</Link>
                            <button type="button" className="btn border dropdown-toggle dropdown-toggle-split"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                            </button>
                            <ul className="dropdown-menu">

                                {categories.map(categorie => {
                                    if (categorie.activation === true || (role === 1 && isAuthenticated)) {
                                        return <li key={categorie.idCategorie}>
                                            <button className="dropdown-item"
                                                    value={categorie.idCategorie}
                                                    onClick={handleAfficherCateg}>{categorie.libelleCategorie}</button>
                                        </li>
                                    }
                                })}
                                <li>
                                    <hr className="dropdown-divider"/>
                                </li>
                                <li>
                                    <button className="dropdown-item" value="all" onClick={handleAfficherCateg}>Toutes
                                    </button>
                                </li>
                            </ul>
                        </div>
                        {((role === 1 && isAuthenticated) && (
                            <Link className=" mt-3 btn btn-light border" to={`ajouter/produit`}>Ajouter un
                                produit</Link>
                        ))}
                    </div>
                    <div className="col-9">
                        <div className="row">
                            {produitCateg}
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default AccueilProduit;

//<img className="banniere" src="https://images.squarespace-cdn.com/content/v1/5ad8575e8f513097d5c2f88d/1546260487736-LO90J0KRY3S4KUTAX6ZP/banniere-cafe.jpg?format=2500w" alt=""/>