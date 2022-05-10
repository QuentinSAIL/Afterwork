import React, {useState} from "react"
import {Text, View, StyleSheet, Button} from "react-native"
import TextInput from "../components/TextInput";
import AsyncStorage from "@react-native-async-storage/async-storage";
import User from "../services/User";

const LoginScene = ({navigation}) => {

    const [mail, setMail] = useState("");
    const [password, setPassword] = useState("");

    const handleLogin = async () => {
        try {
            const token = await User.logIn(mail, password)
            // stocker token
            await AsyncStorage.setItem('token', token)
            //console.log(token)
            navigation.replace("MainTab")
        } catch (error) {
            console.log(error)
        }

    }

    return (
        <View style={styles.container}>
            <Text style={styles.title}>Connexion</Text>
            <View style={styles.input}>
                <TextInput icon="mail" placeholder="Entrer votre email" keyboardType="email-address"
                           onChangeText={value => setMail(value)}
                           value={mail}
                />
            </View>
            <View style={styles.input}>
                <TextInput icon="key" placeholder="Entrer votre mot de passe" secureTextEntry
                           onChangeText={value => setPassword(value)}
                           value={password}
                />
            </View>

            <Button title="Validation " onPress={handleLogin}/>
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
        marginBottom: 10,
        paddingHorizontal: 20
    }
})

export default LoginScene