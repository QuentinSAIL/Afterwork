import React, {useEffect, useState} from 'react';
import AfterWorkAPI from "../services/AfterWorkAPI";
import Products from "../services/Products";

const AjouterProduits = () => {



    const [categories, setCategories] = useState([]);
    const [tva, setTva] = useState([]);

    const [libelle_produit, setLibelle_produit] = useState("");
    const [id_tva, setId_tva] = useState("");
    const [id_categorie, setId_categorie] = useState("");
    const [description_produit, setDescription_produit] = useState("");
    const [prix_unitaire_HT, setPrix_unitaire_HT] = useState("");
    const [image, setImage] = useState("");
    const [activation, setActivation] = useState(0);
    const [Import, setImport] = useState(0);

    const handleSubmit = async e => {
        e.preventDefault()
        await Products.newProduct(libelle_produit, id_tva, id_categorie, description_produit, prix_unitaire_HT, image, activation, Import)
        document.location.href = "http://localhost:3000/";

    }

    const fetchCategories = async () => {
        try {
            const _categories = await AfterWorkAPI.getAllCategorie()
            //console.log(_categories)
            setCategories(_categories)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchCategories();
    }, [])

    const fetchTva = async () => {
        try {
            const _tva = await AfterWorkAPI.getTva()
            console.log(_tva)
            setTva(_tva)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchTva();
    }, [])

    return (
        <div className="container formulaire">
            <form onSubmit={handleSubmit} method="post" className="me-5 mt-5 ms-5">
                <div className="mb-3">
                    <label className="form-label">Nom</label>
                    <input type="text"
                           className="form-control"
                           id="libelle_produit"
                           placeholder="excellium"
                           value={libelle_produit}
                           onChange={e => setLibelle_produit(e.target.value)}/>
                </div>

                <div className="mb-3">
                <label className="form-label">TVA</label>
                    <select className="form-control" id="id_tva" onClick={e => setId_tva(e.target.value)}>
                        {tva.map(tva => {
                            return <option key={tva.idTva} value={tva.idTva}>{tva.pourcentagetva}%</option>
                        })}
                    </select>
                </div>

                <div className="mb-3">
                <label className="form-label">Categorie</label>
                    <select className="form-control" id="id_categorie" onClick={e => setId_categorie(e.target.value)}>
                        {categories.map(categorie => {
                            return <option key={categorie.idCategorie}
                                           value={categorie.idCategorie}>{categorie.libelleCategorie}</option>
                        })}
                    </select>
                </div>

                <div className="mb-3 mt-3">
                    <label className="form-label">Description</label>
                    <input type="text"
                           className="form-control"
                           id="description_produit"
                           placeholder="Un gout intense et ??pic??"
                           value={description_produit}
                           onChange={e => setDescription_produit(e.target.value)}/>
                </div>

                <div className="mb-3">
                    <label className="form-label">Prix</label>
                    <input type="number"
                           className="form-control"
                           id="prix_unitaire_HT"
                           placeholder="2.37"
                           value={prix_unitaire_HT}
                           onChange={e => setPrix_unitaire_HT(e.target.value)}/>
                </div>

                <div className="mb-3">
                    <label className="form-label">Image</label>
                    <input type=""
                           className="form-control"
                           id="image"
                           value={image}
                           onChange={e => setImage(e.target.value)}/>
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

export default AjouterProduits;