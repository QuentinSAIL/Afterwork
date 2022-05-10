import {StatusBar} from 'expo-status-bar';
import {StyleSheet} from 'react-native';
import {NavigationContainer} from "@react-navigation/native";
import LoginStack from "./navigation/LoginNavigator";
import React from "react";

export default function App() {
  return (
      <NavigationContainer>
        <LoginStack>
        </LoginStack>
      </NavigationContainer>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: 'grey',
    alignItems: 'center',
    justifyContent: 'center',
  },
});