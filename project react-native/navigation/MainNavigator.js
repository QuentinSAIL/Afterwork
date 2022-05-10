import React from "react";
import {createBottomTabNavigator} from "@react-navigation/bottom-tabs";
import {MaterialIcons} from '@expo/vector-icons';

import {Entypo} from '@expo/vector-icons';

// scenes
import LogOutScene from "../scenes/LogOutScene";
import DetailsCommandScene from "../scenes/DetailsCommandScene";


import HomeScene from "../scenes/HomeScene";
import OrderTrakingScene from "../scenes/OrderTrakingScene";

const Tab = createBottomTabNavigator();

const DefTabNavigator = () => {
    return (
        <Tab.Navigator
            initialRouteName="Posts"
            screenOptions={{
                headerStyle: {
                    backgroundColor: "#2E4057"
                },
                headerTitleStyle: {
                    color: "white",
                    fontWeight: "bold"
                },
                headerTitleAlign: "center",
                tabBarStyle: {
                    backgroundColor: "#2E4057",
                    borderRadius: 15,
                    fontWeight: "bold",
                    marginBottom: 10,
                    marginHorizontal: 10
                },
                tabBarActiveTintColor: "coral",
                tabBarInactiveTintColor: "white"
            }}
        >
            <Tab.Screen
                name="OrderTrakingScene"
                component={OrderTrakingScene}
                options={{
                    headerTitle: "Commande(s) en cours",
                    tabBarLabel: "Suivi des commandes",
                    tabBarIcon: ({focused, color}) =>
                        <MaterialIcons name="list" size={focused ? 28 : 20} color={color}/>
                }}
            />
            <Tab.Screen
                name="Deco"
                component={LogOutScene}
                options={{
                    headerTitle: "Déconnexion",
                    tabBarLabel: "Déconnexion",
                    tabBarIcon: ({focused, color}) =>
                        <MaterialIcons name="logout" size={focused ? 28 : 20} color={color}/>

                }}
            />
        </Tab.Navigator>
    )
}

export default DefTabNavigator;