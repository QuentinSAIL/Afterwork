import React, {useState} from 'react';
import Credentials from "../services/Credentials";

const InscriptionClient = () => {

    const [nom, setNom] = useState("");
    const [prenom, setPrenom] = useState("");
    const [password, setPassword] = useState("");
    const [email, setEmail] = useState("");
    const [tel, setTel] = useState("");

    const handleSubmit = async e => {
        e.preventDefault()
        await Credentials.newClient(nom,prenom,password,email,tel)
        document.location.href = "http://localhost:3000/connexion";
    }

    return (
        <div className="container formulaire">
            <form onSubmit={handleSubmit} method="post" className="me-5 mt-5 ms-5">
                <div className="mb-3">
                    <label className="form-label">Nom</label>
                    <input type="text"
                           className="form-control"
                           id="nom"
                           placeholder="dupont"
                           value={nom}
                           onChange={e => setNom(e.target.value)}/>
                </div>

                <div className="mb-3">
                    <label className="form-label">Prenom</label>
                    <input type="text"
                           className="form-control"
                           id="prenom"
                           placeholder="Jean"
                           value={prenom}
                           onChange={e => setPrenom(e.target.value)}/>
                </div>

                <div className="mb-3">
                    <label className="form-label">Mot de passe</label>
                    <input type="password"
                           className="form-control"
                           id="password"
                           value={password}
                           onChange={e => setPassword(e.target.value)}/>
                </div>

                <div className="mb-3 mt-3">
                    <label className="form-label">Adresse email</label>
                    <input type="email"
                           className="form-control"
                           id="email"
                           placeholder="Jean@domaine.fr"
                           value={email}
                           onChange={e => setEmail(e.target.value)}/>
                </div>

                <div className="mb-3">
                    <label className="form-label">Tel</label>
                    <input type="text"
                           className="form-control"
                           id="tel"
                           placeholder="1234567890"
                           value={tel}
                           onChange={e => setTel(e.target.value)}/>
                </div>

                <button name="submit " type="submit" className="px-5 btn btn-primary">Ajouter</button>
            </form>
        </div>
    )
}

export default InscriptionClient;