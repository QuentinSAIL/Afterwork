import axios from "axios";
import AsyncStorage from "@react-native-async-storage/async-storage";

const axiosInstance = axios.create({
    "baseURL": "http://localhost:8000"
})

axiosInstance.interceptors.request.use(async (request) => {
    // Récupérer le token dans le AsyncStorage
    const token = await AsyncStorage.getItem('token')
    if (token) {
        request.headers.Authorization = `Bearer ${token}`
    }
    return request
})
export default axiosInstance