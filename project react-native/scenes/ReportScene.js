import React, {useEffect, useState} from "react"
import {Text, View, StyleSheet, Button} from "react-native"
import TextInput from "../components/TextInput";
import Commands from "../services/Commands";


const ReportScene = ({route, navigation}) => {
    const {idCommande} = route.params;

    var today = new Date();
    var date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
    var time = today.getHours() + "h 0"+ today.getMinutes();
    var dateTime = date+' à '+time;

const Validate = () => {
    alert("Commande signalé")
    navigation.goBack()
}

    return (
        <View style={styles.container}>
            <View style={styles.input}>
                <Text>Numéro de commande : {idCommande}</Text>
                <Text>Date et heure de l'incident : {dateTime}</Text>
                <TextInput icon="info" placeholder="Nom client"/>
                <TextInput icon="info" placeholder="Prenom client"/>
                <TextInput icon="info" placeholder="Numéro de table" keyboardType="numeric"/>
                <Button title="Valider " onPress={Validate}/>
            </View>
        </View>
    )
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: "center",
        alignItems: "center"
    },
    title: {
        color: "#2E4057",
        fontSize: 28,
        fontWeight: "bold",
        marginBottom: 40
    },
    input: {
        width: "100%",
        paddingHorizontal: 20
    }
})

export default ReportScene