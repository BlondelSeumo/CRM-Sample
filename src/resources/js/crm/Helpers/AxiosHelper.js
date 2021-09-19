import AxiosFunction from "../../core/helpers/axios/AxiosFunction";
import axios from 'axios';
import AppFunction from "../../core/helpers/app/AppFunction";

export const urlGenerator = (url) => {
    return `${AppFunction.getBaseUrl()}/${url.split('/').filter(d => d).join('/')}`;
};

export const axiosGet = (url) => {
    return AxiosFunction.axiosGet((url));
};


export const axiosPost = (url, data) => {
    return AxiosFunction.axiosPost({
        url: url,
        data
    });
};

export const axiosPatch = (url, data) => {
    return axios.patch(url, data);
};

export const axiosDelete = (url) => {
    return AxiosFunction.axiosDelete(url);
};
