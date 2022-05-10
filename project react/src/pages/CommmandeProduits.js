import React, {useEffect, useState} from "react";
import AfterWorkAPI from "../services/AfterWorkAPI";
import {Link} from "react-router-dom";

const CommmandeProduits = () => {


    const [nomsProduits, setNomsProduits] = useState([]);
    const [idsProduits, setIdsProduits] = useState([]);

    const namesProducts = []
    let tabProductsQ = []
    let idsProducts = []
    let cpt = 0

    const recupProduit = () => {
        if (localStorage.getItem("panier")) {
            const ListeIdProduct = localStorage.getItem("panier")
            let idProduct = "";
            let nameProduct = "";
            let id = false;
            for (let i = 0; i < ListeIdProduct.length; i++) {
                if (ListeIdProduct[i] !== ";" && id === false) {
                    idProduct += ListeIdProduct[i]
                } else if (ListeIdProduct[i] !== "#") {
                    if (ListeIdProduct[i] === ";") {
                        i++                                 // on évite d'avoir le ";" dans le nom du produit
                    }
                    id = true                               // ca veut dire que l'id à été trouvé on recherche mtn le nom
                    nameProduct += ListeIdProduct[i]
                } else {
                    //console.log(nameProduct)
                    namesProducts.push(nameProduct)
                    idsProducts.push(idProduct)
                    id = false
                    idProduct = "";
                    nameProduct = "";
                }
            }
            let quantite = {};
            for (let i = 0; i < idsProducts.length; i++) {
                quantite[idsProducts[i]] = quantite[idsProducts[i]] ? quantite[idsProducts[i]] + 1 : 1;
            }
            setNomsProduits(namesProducts)
            setIdsProduits(quantite)
        }
    }
    useEffect(() => {
        recupProduit();
    }, [])

    const viderPanier = () => {
        localStorage.removeItem("panier")
        window.location.reload();
    }

    const validerPanier = () => {
        if (!document.getElementById("numTable").value) {
            alert("Quelle est votre numéro de table ?")
        } else {
            const numTable = document.getElementById("numTable").value
            AfterWorkAPI.validateBasket(numTable, idsProduits)
            localStorage.removeItem("panier")
            window.location.reload();
        }
    }

    //const calculate = async () => {
    //    const productsAndQuantity = nomsProduits.reduce(function (acc, curr) {
    //        if (typeof acc[curr] == 'undefined') {
    //            acc[curr] = 1;
    //        } else {
    //            acc[curr] += 1;
    //        }
    //        return acc;
    //    }, {});
    //    //console.log(productsAndQuantity)
    //    //setProductQuantity(productsAndQuantity)
    //    const test = Object.entries(productsAndQuantity)
    //    for await (var [key, value] of test) {
    //        tabProductsQ.push(`${key} quantieté : ${value}`);
    //        console.log(tabProductsQ)
    //        console.log('caca')
    //        setProductQuantity(tabProductsQ)
    //    }
    //    //console.log(tabProductsQ)
//                                                          1h dessus => ca marche pas
    //}
    //useEffect(() => {
    //    calculate();
    //}, [])


    return <>
        {(localStorage.getItem("panier") === null && (
            <div className="text-center">
                <p className="text-center mt-5 fs-1">Panier vide</p>
                <Link className="btn btn-success me-2" to="/">allons voir les produits</Link>
            </div>
        ))}
        {(localStorage.getItem("panier") && (
            <div className="container text-center mt-5">
                <h5>Récaptitulatif de votre commande : </h5>
                <ul className="list-group">
                    {nomsProduits.map(nomProduits => {
                        cpt++
                        return <li className="list-group-item" key={cpt}>{nomProduits}</li>
                    })}
                </ul>

                <p className="fs-3">Numéro de table : </p>
                <div>
                    <input className="text-center border border-dark" type="number" placeholder="Numéro de table"
                           id="numTable"/>
                </div>
                <button className="mt-3 btn btn-success me-2" onClick={validerPanier}>Valider</button>
                <button className="mt-3 btn btn-warning me-2" onClick={viderPanier}>Vider le panier</button>
            </div>
        ))}
    </>
}
export default CommmandeProduits;