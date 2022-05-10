import React, {useState} from 'react';
import AfterWorkAPI from "../services/AfterWorkAPI";
import Credentials from "../services/Credentials";
import {useParams} from "react-router-dom";

const AjouterArticle = () => {

    const {id} = useParams();

    const [titre, setTitre] = useState("");
    const [contenu, setContenu] = useState("");

    const handleSubmit = async e => {
        e.preventDefault()
        await AfterWorkAPI.InsertArticle(titre, await Credentials.getPayload()["id_employe"], contenu,(new Date).toLocaleString(),id)
        //console.log(titre, await Credentials.getPayload()["id_employe"], description,(new Date).toLocaleString(),id)
        document.location.href = `http://localhost:3000/articles/${id}`;
    }

    return (
        <div className="container formulaire">
            <form onSubmit={handleSubmit} method="post" className="me-5 mt-5 ms-5">
                <div className="mb-3">
                    <label className="form-label">Titre</label>
                    <input type="text"
                           className="form-control"
                           id="titre"
                           placeholder="Titre de l'article"
                           value={titre}
                           onChange={e => setTitre(e.target.value)}/>
                </div>

                <div className="mb-3">
                    <label className="form-label">Contenu</label>
                    <input type="text"
                           className="form-control"
                           id="description"
                           placeholder="Présentation de .."
                           value={contenu}
                           onChange={e => setContenu(e.target.value)}/>
                </div>

                <button name="submit " type="submit" className="px-5 btn btn-primary">Ajouter</button>
            </form>
        </div>
    )
}

export default AjouterArticle;