import React from 'react';
import {Link} from "react-router-dom";

const ErrorPage = () => {
    return (
        <>
            <h1 className="mt-5">404 La page demandée n'existe PAS</h1>
            <div className="d-flex justify-content-center">
                <Link className="text-center mt-3 btn btn-light border" to={`/`}>Retourner à l'accueil</Link>
            </div>
        </>
    )
}

export default ErrorPage;