import Header from "./components/Header";
import PrivateRoute from "./components/PrivateRoute";

import AjouterProduit from "./pages/AjouterProduit";
import ModifierProduit from "./pages/ModifierProduit";
import AjouterCategorie from "./pages/AjouterCategorie";
import AccueilProduit from "./pages/AccueilProduit";
import DetailsProduit from "./pages/DétailsProduit";
import AccueilConnexion from "./pages/AccueilConnexion";
import ErrorPage from "./pages/ErrorPage";
import AccueilCategories from "./pages/AccueilCategories";
import AcceuilRubrique from "./pages/AcceuilRubrique";
import AjouterRubriques from "./pages/AjouterRubrique";
import AcceuilRubriques from "./pages/AcceuilRubriques";
import AccueilAdmin from "./pages/AccueilAdmin";
import InscriptionClient from "./pages/InscriptionClient";
import InscriptionEmploye from "./pages/InscriptionEmploye";
import CommmandeProduits from "./pages/CommmandeProduits";

import Credentials from "./services/Credentials";
import AuthenticationContext from "./config/credentialsContext";

import {BrowserRouter, Route, Switch, withRouter} from "react-router-dom";
import {useEffect, useState} from "react";

import './App.css';
import AjouterArticle from "./pages/AjouterArticle";


function App() {

    const [isAuthenticated, setIsAuthenticated] = useState(Credentials.isAuthenticated)
    const [role, setRole] = useState([]);
    const AuthenticationContextValue = {
        isAuthenticated: isAuthenticated,
        setIsAuthenticated: setIsAuthenticated
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

    const HeaderWithRouter = withRouter(Header)

    return (
        <AuthenticationContext.Provider value={AuthenticationContextValue}>
            <BrowserRouter>
                <HeaderWithRouter/>
                <Switch>
                    <Route exact path="/" component={AccueilProduit}/>
                    <Route exact path="/connexion" component={AccueilConnexion}/>
                    <Route exact path="/inscription" component={InscriptionClient}/>
                    <Route exact path="/inscription-employe" component={InscriptionEmploye}/>
                    <Route exact path="/categories" component={AccueilCategories}/>
                    <Route exact path="/rubriques" component={AcceuilRubriques}/>
                    <PrivateRoute exact path="/ajouter/produit" isAuthenticated={(role === 1 && isAuthenticated)} component={AjouterProduit}/>
                    <PrivateRoute exact path="/modifier/produit/:id" isAuthenticated={(role === 1 && isAuthenticated)} component={ModifierProduit}/>
                    <PrivateRoute exact path="/ajouter/categorie" isAuthenticated={(role === 1 && isAuthenticated)} component={AjouterCategorie}/>
                    <PrivateRoute exact path="/ajouter/rubrique" isAuthenticated={((role === 1 || role === 4) && isAuthenticated)} component={AjouterRubriques}/>
                    <PrivateRoute exact path="/ajouter/article/:id" isAuthenticated={((role === 1 || role === 4) && isAuthenticated)} component={AjouterArticle}/>
                    <PrivateRoute exact path="/administrer" isAuthenticated={(role === 1 && isAuthenticated)} component={AccueilAdmin}/>
                    <Route exact path="/produits/details/:id" component={DetailsProduit}/>
                    <Route exact path="/articles/:idRubrique" component={AcceuilRubrique}/>
                    <Route exact path="/panier" component={CommmandeProduits}/>
                    <Route component={ErrorPage}/>
                </Switch>
            </BrowserRouter>
        </AuthenticationContext.Provider>
    );
}

export default App;
