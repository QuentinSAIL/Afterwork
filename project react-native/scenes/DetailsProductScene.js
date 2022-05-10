import React, {useEffect, useState} from "react"
import {Text, View, StyleSheet, FlatList, Image, Button} from "react-native"
import Products from "../services/Products";

const DetailsProductScene = ({route, navigation}) => {

    const {idProduit} = route.params;

    const [product, setProduct] = useState([]);

    const fetchProduct = async () => {
        try {
            const _product = await Products.getDetailsProduct(idProduit)
            console.log(_product)
            setProduct(_product)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchProduct();
    }, [])


    return (
        <View>
            <Button
                title="Retour à la liste"
                onPress={() => {
                    navigation.goBack()
                }}
            />
        </View>
    )

}


const styles = StyleSheet.create({
    titre: {
        color: "black",
        alignItems: "center"
    },
    container: {
        marginVertical: 5,
        padding: 20,
        backgroundColor: "grey",
        borderRadius: 30
    }
})

export default DetailsProductScene