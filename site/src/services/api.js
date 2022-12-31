import axios from 'axios';
import GenericStore from "@/store/genericStore";

function cakeApi() {
	const cakeApiUrl = GenericStore.state.api;
    const cakeApi = axios.create({
        baseURL: cakeApiUrl,
        headers: {
            'Access-Control-Allow-Methods': 'GET, POST, PUT, PATCH, DELETE',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    });
    return cakeApi;
}

export {
	cakeApi
}