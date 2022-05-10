import React, {useState} from 'react';
import Products from "../services/Products";

const AjouterCategorie = () => {


    const [libelle_categorie, setLibelle_categorie] = useState("");
    const [description_categorie, setDescription_categorie] = useState("");
    const [activation, setActivation] = useState(0);
    const [Import, setImport] = useState(0);

    const handleSubmit = async e => {
        e.preventDefault()
        await Products.newCategory(libelle_categorie, description_categorie, activation, Import)
        document.location.href = "http://localhost:3000/categories";
    }

    return (
        <div className="container formulaire">
            <form onSubmit={handleSubmit} method="post" className="me-5 mt-5 ms-5">
                <div className="mb-3">
                    <label className="form-label">Nom</label>
                    <input type="text"
                           className="form-control"
                           id="libelle_categorie"
                           placeholder="excellium"
                           value={libelle_categorie}
                           onChange={e => setLibelle_categorie(e.target.value)}/>
                </div>

                <div className="mb-3 mt-3">
                    <label className="form-label">Description</label>
                    <input type="text"
                           className="form-control"
                           id="description_categorie"
                           value={description_categorie}
                           onChange={e => setDescription_categorie(e.target.value)}/>
                </div>

                <div className="mb-3 form-check">
                    <label className="form-label">Activation</label>
                    <input type="checkbox"
                           className="form-check-input"
                           id="activation"
                           value={activation}
                           onChange={e => setActivation(e.target.value + 1 - (e.target.value + e.target.value))}/>
                </div>

                <div className="mb-3 form-check">
                    <label className="form-label">Import</label>
                    <input type="checkbox"
                           className="form-check-input"
                           id="import"
                           value={Import}
                           onChange={e => setImport(e.target.value + 1 - (e.target.value + e.target.value))}/>
                </div>

                <button name="submit " type="submit" className="px-5 btn btn-primary">Ajouter</button>
            </form>
        </div>
    )
}

export default AjouterCategorie;