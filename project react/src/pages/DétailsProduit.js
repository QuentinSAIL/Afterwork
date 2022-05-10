import React, {useContext, useEffect, useState} from 'react';
import AfterWorkAPI from "../services/AfterWorkAPI";
import {useParams} from "react-router-dom";
import AuthenticationContext from "../config/credentialsContext";
import Credentials from "../services/Credentials";

const DetailsProduit = () => {
    const {id} = useParams();
    const {isAuthenticated} = useContext(AuthenticationContext);

    const [idUser, setIdUser] = useState([]);
    const [produit, setProduit] = useState([]);
    const [commmentaires, setCommmentaires] = useState([]);
    const [titre, setTitre] = useState([]);
    const [description, setDescription] = useState([]);


    const fetchProduit = async () => {
        try {
            //console.log(id)
            const _produit = await AfterWorkAPI.getDetailsProduit(id)
            setProduit(_produit)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchProduit();
    })

    const fetchGetIdClient = async () => {
        try {
            const token = Credentials.getPayload()
            const _idUser = token["id_client"]
            //console.log(_idUser)
            setIdUser(_idUser)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchGetIdClient();
    })

    const fetchCommentaires = async () => {
        try {
            const _commmentaires = await AfterWorkAPI.getCommentairesProduit(id)
            //console.log(_commmentaires)
            setCommmentaires(_commmentaires)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchCommentaires();
    })

    const handleSubmit = async e => {
        e.preventDefault()

        const dateTime = new Date().getFullYear() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getDate() + ' ' + new Date().getHours() + ":" + new Date().getMinutes() + ":" + new Date().getSeconds()
        console.log(id, idUser, titre, description, dateTime)
        await AfterWorkAPI.InsertCommentairesProduit(id, idUser, titre, description, dateTime)
        window.location.reload();
    }

    return (
        <>
            <h1 className="text-center">Description</h1>
            {produit.map(info => {
                //console.log(info)
                return <div className="container mt-5">
                    <div className="row">
                        <div className="col">
                            <h5>{info.libelleProduit}</h5>
                            <p>
                                {info.descriptionProduit}
                            </p>
                            <p>
                                <b>Le prix unitaire hors taxe est de : </b> {info.prixUnitaireHt} €.
                            </p>
                        </div>
                        <div className="text-center col">
                            <img className="imageBoutique"
                                 src="https://petitboutdechou.fr/wp-content/uploads/2019/03/ennergisant5.png"
                                 alt="le produit"/>
                        </div>
                    </div>
                    <h3 className="mt-5">Commentaires :</h3>
                    <div className="row">
                        {commmentaires.map(commentaire => {
                            return <div className="col-sm-6" key={commentaire.idCommentaire}>
                                <div className="card mx-2">
                                    <div className="card-header">
                                        {commentaire.idClient.prenomClient} {commentaire.idClient.nomClient}
                                    </div>
                                    <div className="card-body">
                                        <h5 className="card-title">{commentaire.titre}</h5>
                                        <p className="card-text">{commentaire.description}</p>
                                        <p>{new Date(commentaire.date).toLocaleString()}</p>
                                    </div>
                                </div>
                            </div>
                        })}
                    </div>
                </div>
            })}
            {(isAuthenticated && (
                <div className="form-floating me-5 mt-5 ms-5 border">
                    <label htmlFor="commentaire">Nouveau commentaire</label>
                    <form onSubmit={handleSubmit} method="post" className="me-5 ms-5">
                        Titre :
                        <input className="mt-5 border border-white"
                               type="tex"
                               placeholder="le titre"
                               id="TitreComment"
                               value={titre}
                               onChange={e => setTitre(e.target.value)}/>
                        <textarea className="form-control pr-5"
                                  placeholder="Vous pouvez postez votre commentaire ici"
                                  id="DescriptionComment"
                                  value={description}
                                  onChange={e => setDescription(e.target.value)}/>
                        <button name="submit" type="submit" className=" btn btn-primary">Ajouter</button>
                    </form>
                </div>
            ))}
        </>
    )
}

export default DetailsProduit;

