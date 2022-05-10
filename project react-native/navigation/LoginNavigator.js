import React from "react";
import {createNativeStackNavigator} from "@react-navigation/native-stack";
import LoginScene from "../scenes/LoginScene";
import MainNavigator from "./MainNavigator";
import DetailsCommandScene from "../scenes/DetailsCommandScene";
import ReportScene from "../scenes/ReportScene";


const Stack = createNativeStackNavigator();


const LoginStack = () => {
    return (
        <Stack.Navigator
            initialRouteName="Login"
            screenOptions={{
                headerShown: false
            }}
        >
            <Stack.Screen
                name="Login"
                component={LoginScene}
            />
            <Stack.Screen
                name="MainTab"
                component={MainNavigator}
            />
            <Stack.Screen
                name="DetailsCommand"
                component={DetailsCommandScene}
            />
            <Stack.Screen
                name="Report"
                component={ReportScene}
            />

        </Stack.Navigator>


    )
}

export default LoginStack;