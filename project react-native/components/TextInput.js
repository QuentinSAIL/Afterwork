import React from "react";
import {View, StyleSheet, TextInput as RNTextInput} from "react-native";
import {Entypo as Icon} from "@expo/vector-icons";

const TextInput = ({icon, ...otherProps}) => {
    return (
        <View style={styles.container}>
            <View style={styles.icon}>
                <Icon name={icon} size={17} color={"black"}></Icon>
            </View>
            <View style={styles.input}>
                <RNTextInput {...otherProps}/>
            </View>
        </View>
    )
}


const styles = StyleSheet.create({
    container: {
        flexDirection: "row",
        alignItems: "center",
        borderColor: "black",
        borderRadius: 10,
        borderWidth: 1,
        margin: -1,
    },
    icon : {
        padding: 10
    },
    input : {
        flex: 1
    }
})

export default TextInput;