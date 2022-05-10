import React, {useContext, useState} from 'react';
import Credentials from "../services/Credentials";
import AuthenticationContext from "../config/credentialsContext";

const AccueilIdentification = () => {

    // State pour le username et le mot de passe
    const [mail, setMail] = useState("");
    const [password, setPassword] = useState("");

    const {setIsAuthenticated} = useContext(AuthenticationContext)


    // Soumission du formulaire
    const handleSubmit = async e => {
        e.preventDefault()
        const token = await Credentials.logIn(mail, password)
        localStorage.setItem("token", token)
        setIsAuthenticated(true)     // setIsAuthenticated
        document.location.href = "http://localhost:3000/";
    }

    return (
        <div className="container formulaire">
            <form onSubmit={handleSubmit} method="get" className="me-5 mt-5 ms-5">
                <div className="mb-3">
                    <label className="form-label">Email</label>
                    <input type="email"
                           className="form-control"
                           id="mail"
                           placeholder="Exemple@Email.com"
                           value={mail}
                           onChange={e => setMail(e.target.value)}/>
                </div>

                <div className="mb-3">
                    <label className="form-label">Mot de passe</label>
                    <input type="password"
                           className="form-control"
                           id="password"
                           value={password}
                           onChange={e => setPassword(e.target.value)}/>
                </div>
                <button name="submit " type="submit" className="btn btn-primary">Valider</button>
            </form>
        </div>
    )

}

export default AccueilIdentification;