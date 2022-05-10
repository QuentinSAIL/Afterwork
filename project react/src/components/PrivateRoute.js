import {Redirect, Route} from "react-router-dom";

const PrivateRoute = (props) => {
    return props.isAuthenticated
        ? <Route path={props.path} component={props.component}/>
        : <Redirect to="/connexion"/>
}

export default PrivateRoute

