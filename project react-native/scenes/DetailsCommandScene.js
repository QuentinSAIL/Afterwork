import React, {useEffect, useState} from "react"
import {Text, View, StyleSheet, FlatList, Image, Button} from "react-native"
import Unorderedlist from 'react-native-unordered-list';
import Commands from "../services/Commands";
import jwtDecode from "jwt-decode";
import AsyncStorage from "@react-native-async-storage/async-storage";

const DetailsCommandScene = ({route, navigation}) => {

    const {idCommande} = route.params;

    const [command, setCommand] = useState([]);
    const [productsCommand, setProductsCommand] = useState([]);
    const [prix, setPrix] = useState(0);

    const fetchProductsCommand = async () => {
        try {
            const _productsCommand = await Commands.getProductsCommand(idCommande)
            //console.log(_productsCommand)
            setProductsCommand(_productsCommand)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchProductsCommand();
    }, [])

    const fetchCommand = async () => {
        try {
            const _command = await Commands.getDetailsCommand(idCommande)
            //console.log(_command)
            setCommand(_command)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchCommand();
    }, [])

    const formatingFloat = (nbr) => {
        return nbr.toFixed(2)
    }

    const plus1Command = async (idCommande) => {
        const id_Employe = jwtDecode(await AsyncStorage.getItem("token"))["id_employe"]
        await Commands.addOneStatutCommand(idCommande, id_Employe)
        const _command = await Commands.getDetailsCommand(idCommande)
        //console.log(_command)
        setCommand(_command)
    }
    const setStatCommand = async (idCommande, idStatut) => {
        const id_Employe = jwtDecode(await AsyncStorage.getItem("token"))["id_employe"]
        await Commands.SetStatutCommand(idCommande, idStatut, id_Employe)
        const _command = await Commands.getDetailsCommand(idCommande)
        //console.log(_command)
        setCommand(_command)
    }
    const DeleteOrder = async (idCommande) => {
        await Commands.DeleteCommmand(idCommande)
        navigation.goBack()
    }

    return (
        <View style={styles.all}>

            <View>
                <FlatList data={command} renderItem={({item}) => (
                    <View style={styles.container}>
                        <View>
                            <Text style={styles.fs}>Numéro de commande : {item.idCommande}</Text>
                            <Text style={styles.fs}>Numéro de table : {item.noTable}</Text>
                            {((item.idStatut.idStatut !== 1) && (
                                <Text style={styles.fs}>Employe : {item.idEmploye.nom} {item.idEmploye.prenom}</Text>
                            ))}
                            <Text style={styles.fs}>Produit(s) de la commande : </Text>
                            <FlatList data={productsCommand} renderItem={({item}) => (
                                <Unorderedlist>
                                    <Text style={styles.liste}>{item.libelle_produit}</Text>
                                    <Text style={styles.price}>Prix unité
                                        : {formatingFloat(item.prix_HT * (1 + item.montant_TVA))}€</Text>
                                    <Text style={styles.price}>Quantité : {item.quantité_produit}</Text>
                                    <Text style={styles.price}>Total
                                        : {formatingFloat(item.prix_HT * (1 + item.montant_TVA) * item.quantité_produit)}€</Text>
                                </Unorderedlist>
                            )}
                                      keyExtractor={item => item.id_produit}
                            />
                        </View>
                        <View style={styles.statut}>
                            <Text style={styles.fs}>Statut de la commande : {"\n"}</Text>
                            <Text style={styles.libelle}>{item.idStatut.libelleStatut}</Text>
                            {((item.idStatut.idStatut === 1) && (
                                <View style={styles.validation}>
                                    <Button title="accepter" color="#40A262" onPress={() => {
                                        plus1Command(item.idCommande)
                                    }}/>
                                    <Button title="refuser" color="#FF0000" onPress={() => {
                                        setStatCommand(item.idCommande, 7)
                                    }}/>
                                </View>
                            ))}
                            {((item.idStatut.idStatut === 2) && (
                                <View style={styles.validation}>
                                    <Button title="En préparation" onPress={() => {
                                        plus1Command(item.idCommande)
                                    }}/>
                                </View>
                            ))}
                            {((item.idStatut.idStatut === 3) && (
                                <View style={styles.validation}>
                                    <Button title="Préparation fini" color="#40A262" onPress={() => {
                                        plus1Command(item.idCommande)
                                    }}/>
                                    <Button title="CLIENT parti" color="#FF0000" onPress={() => {
                                        setStatCommand(item.idCommande, 8)
                                    }}/>
                                </View>
                            ))}
                            {((item.idStatut.idStatut === 4) && (
                                <View style={styles.validation}>
                                    <Button title="Livrée" color="#40A262" onPress={() => {
                                        plus1Command(item.idCommande)
                                    }}/>
                                    <Button title="CLIENT parti" color="#FF0000" onPress={() => {
                                        setStatCommand(item.idCommande, 8)
                                    }}/>
                                </View>
                            ))}
                            {((item.idStatut.idStatut === 5) && (
                                <View style={styles.validation}>
                                    <Button title="paiement validé" color="#40A262" onPress={() => {
                                        plus1Command(item.idCommande)
                                    }}/>
                                    <Button title="parti sans payer" color="#FF0000" onPress={() => {
                                        setStatCommand(item.idCommande, 9)
                                    }}/>
                                </View>
                            ))}
                            {((item.idStatut.idStatut === 6 || item.idStatut.idStatut === 7 || item.idStatut.idStatut === 8) && (
                                <View style={styles.validation}>
                                    <Button title="Supprimer la commande" color="#FF0000" onPress={() => {
                                        DeleteOrder(item.idCommande)
                                    }}/>
                                </View>
                            ))}
                            {((item.idStatut.idStatut === 9) && (
                                <View style={styles.validation}>
                                    <Button title="FAIRE UN RAPPORT" color="#FF0000"
                                            onPress={() => navigation.navigate("Report", {idCommande: item.idCommande})}/>
                                </View>
                            ))}
                            {((item.idStatut.idStatut === 10) && (
                                <View style={styles.validation}>
                                    <Text style={styles.libelle}>Le client est en train de faire une récalmation</Text>
                                </View>
                            ))}

                        </View>
                        {((item.idStatut.idStatut !== 6 && item.idStatut.idStatut !== 7 && item.idStatut.idStatut !== 8 && item.idStatut.idStatut !== 10) && (
                            <Button style={styles.titre} title="Supprimer la commande" color="#FF0000" onPress={() => {
                                DeleteOrder(item.idCommande)
                            }}/>
                        ))}
                    </View>
                )}
                          keyExtractor={item => item.idCommande}
                />
            </View>
            <View style={styles.titre}>
                <Button title="revenir à toutes les commandes" onPress={() => {
                    navigation.goBack()
                }}/>
            </View>
        </View>
    )
}
const styles = StyleSheet.create({
    validation: {
        margin: 10,
        fontSize: 40,
    },
    fs: {
        fontSize: 20,
    },
    liste: {
        backgroundColor: "#c9f5d2",
        borderStyle: "solid",
    },
    price: {
        backgroundColor: "#b2dbbb",
    },
    titre: {
        marginTop: 40,
        marginBottom: 40,
        color: "black",
        alignItems: "center"
    },
    container: {
        marginTop: 90,
        marginHorizontal: 40,
        padding: 10,
        backgroundColor: "lightgrey",
        borderRadius: 7
    },
    statut: {
        marginTop: 20,
        color: "black",
        backgroundColor: "#e6e6e6",
        borderRadius: 7
    },
    libelle: {
        fontSize: 23,
        color: "black",
        textAlign: "center",
        backgroundColor: "white",
    },
})
export default DetailsCommandScene