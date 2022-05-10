import React, {useEffect, useState} from "react"
import {Text, View, StyleSheet, FlatList, Image, Pressable} from "react-native"
import Products from "../services/Products";

const HomeScene = ({navigation}) => {
    const [products, setProducts] = useState([]);

    const fetchProducts = async () => {
        try {
            const _products = await Products.getAllProducts()
            //console.log(_products)
            setProducts(_products)
        } catch (error) {
            console.log('erreur : ' + error)
        }
    }
    useEffect(() => {
        fetchProducts();
    }, [])


    return (
        <View>
            <Text style={styles.titre}>Voici la liste des produits !</Text>
            <FlatList data={products} renderItem={({item}) => (
                <View style={styles.container} key={item.idProduit}>
                    <Image style={styles.tinyLogo}
                                source={require('../img/imgProduit.png')}/>
                    <Text>{item.libelleProduit}</Text>
                    <Pressable onPress={() => navigation.navigate("DetailsProductScene", {idProduit: item.idProduit})}>
                        <Text>{item.idProduit}</Text>
                    </Pressable>
                    <Text>cocdzo !</Text>
                </View>
            )}
                      keyExtractor={item => item.idProduit}
            />
        </View>
    )

}


const styles = StyleSheet.create({
    titre: {
        color: "black",
        alignItems: "center"
    },
    tinyLogo: {
        width: 50,
        height: 50,
    },
    container: {
        marginVertical: 5,
        padding: 20,
        backgroundColor: "grey",
        borderRadius: 30
    }
})

export default HomeScene