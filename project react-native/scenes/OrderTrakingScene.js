import React, {useEffect, useState} from "react"
import {Text, View, StyleSheet, FlatList, Image, Pressable, Button} from "react-native"
import Commands from "../services/Commands";

const OrderTrakingScene = ({navigation}) => {
    const [commands, setCommands] = useState([]);

    const fetchCommands = async () => {
        try {
            const _commands = await Commands.getAllCommands()
            //console.log(_commands)
            setCommands(_commands)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchCommands();
    }, [])

    const RefreshCommand = async () => {
        try {
            const _commands = await Commands.getAllCommands()
            //console.log(_commands)
            setCommands(_commands)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }


    return (

        <View>
            <View>
                <Button title="Actualiser " onPress={RefreshCommand}/>
            </View>
            <FlatList data={commands} renderItem={({item}) => (
                <View key={item.idCommande}>
                    {((item.idStatut.idStatut !== 6) && (
                        <Pressable
                            onPress={() => navigation.navigate("DetailsCommand", {idCommande: item.idCommande})}
                            style={styles.container}>
                            <Text>Numéro de commande : {item.idCommande}</Text>
                            <Text>Numéro de table : {item.noTable}</Text>
                            <Text>{item.idStatut.libelleStatut}</Text>
                            {((item.idStatut.idStatut !== 1) && (
                                <Text>employe en charge de la commande
                                    : {item.idEmploye.nom} {item.idEmploye.prenom}</Text>
                            ))}
                        </Pressable>
                    ))}
                </View>
            )}
                      keyExtractor={item => item.idCommande}
            />
        </View>
    )
}


const styles = StyleSheet.create({
    titre: {
        alignItems: "center"
    },
    container: {
        color: "black",
        margin: 5,
        padding: 15,
        backgroundColor: "lightgrey",
        borderRadius: 6,
    }

})

export default OrderTrakingScene