import React, {useState} from 'react';
import AfterWorkAPI from "../services/AfterWorkAPI";

const AjouterRubriques = () => {

    const [titre, setTitre] = useState("");
    const [description, setDescrption] = useState("");

    const handleSubmit = async e => {
        e.preventDefault()
        await AfterWorkAPI.InsertRubrique(titre, description)
        document.location.href = "http://localhost:3000/rubriques";
    }

    return (
        <div className="container formulaire">
            <form onSubmit={handleSubmit} method="post" className="me-5 mt-5 ms-5">
                <div className="mb-3">
                    <label className="form-label">Titre</label>
                    <input type="text"
                           className="form-control"
                           id="titre"
                           placeholder="Titre de la rubrique"
                           value={titre}
                           onChange={e => setTitre(e.target.value)}/>
                </div>

                <div className="mb-3">
                    <label className="form-label">Description</label>
                    <input type="text"
                           className="form-control"
                           id="description"
                           placeholder="Présentation de .."
                           value={description}
                           onChange={e => setDescrption(e.target.value)}/>
                </div>

                <button name="submit " type="submit" className="px-5 btn btn-primary">Ajouter</button>
            </form>
        </div>
    )
}

export default AjouterRubriques;