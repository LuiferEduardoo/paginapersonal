import axios from 'axios';
import React from 'react';

const apiKey = process.env.REACT_APP_API_KEY;
const apiWebSite = process.env.REACT_APP_API_WEB_SITE;


const obtain = async (element) => {
    try {
        const response = await axios.get(`${apiWebSite}/${element}`, {
            headers: {
                'Content-Type': 'application/json',
                'x-api-key': apiKey
            },
        });

        if (response.status === 200) {
            const elements = response.data;
            return elements;
        } else {
            throw new Error(`Error a la hora de obtener ${element}`);
        }
    } catch (error) {
        throw new Error(error);
    }
};

const createElement = async (token, element, data) => {
    try {
        const formData = new FormData();
        if (data.images) {
            data.images.forEach((image, index) => {
                formData.append(`images[${index}]`, image);
            });
        }
        for (let key in data) {
            if (data.hasOwnProperty(key) && key != "images") {
                formData.append(key, data[key]);
            }
        }

        const response = await axios.post(`${apiWebSite}/${element}/create`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'x-api-key': apiKey,
                Authorization: `Bearer ${token}`,
            },
        });

        if (response.status === 200) {
            const elements = response.data;
            return elements;
        } else {
            throw new Error(`Error a la hora de crear ${element}`);
        }
    } catch (error) {
        if (error.response && error.response.data) {
            throw new Error(error.response.data.message);
        }
        throw new Error(error);
    }
}

const update = async (token, element, id, dataToUpdate) => {
    try {
        const formData = new FormData();
        formData.append('_method', 'PATCH')

        if (dataToUpdate.images) {
            dataToUpdate.images.forEach((image, index) => {
                formData.append(`images[${index}]`, image);
            });
        }
        for (let key in dataToUpdate) {
            if (dataToUpdate.hasOwnProperty(key) && key != "images") {
                formData.append(key, dataToUpdate[key]);
            }
        }


        const formDataObject = {};
        formData.forEach((value, key) => {
            formDataObject[key] = value;
        });
        console.log(dataToUpdate);
        console.log({formDataObject: formDataObject, formData: formData});

        const response = await axios.post(`${apiWebSite}/${element}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'x-api-key': apiKey,
                Authorization: `Bearer ${token}`,
            },
        });

        if (response.status === 200) {
            const elements = response.data;
            return elements;
        } else {
            throw new Error(`Error a la hora de actualizar ${element}`);
        }
    } catch (error) {
        console.log({error});
        throw new Error(error);
    }
};

const deleteElement = async (token, id, element, eliminateImages) => {
    const params = { id: id }
    try {
        const response = await axios.delete(`${apiWebSite}/${element}`, {
            headers: {
                'Content-Type': 'application/json',
                'x-api-key': apiKey,
                Authorization: `Bearer ${token}`,
            },
            data: eliminateImages,
            params: params,
        });

        if (response.status === 200) {
            const elements = response.data;
            return elements;
        } else {
            throw new Error(`Error a la hora de eliminar el/la ${element}`);
        }
    } catch (error) {
        throw new Error(error);
    }
}
export default { obtain, createElement, update, deleteElement };