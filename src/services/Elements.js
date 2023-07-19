import axios from 'axios';
import React from 'react';


const obtain = async (element) => {
    try {
        const response = await axios.get(`https://api.luifereduardoo.com/v1/${element}`,{
        headers: {
            'Content-Type': 'application/json',
            'x-api-key': '530e4e8b-45be-4a7b-86f5-d98018838693'
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

        for (let key in data) {
            if (data.hasOwnProperty(key)) {
                formData.append(key, data[key]);
            }
        }

        for (let entry of formData.entries()) {
            console.log(entry[0] + ": " + entry[1]);
        }
        
        const response = await axios.post(`https://api.luifereduardoo.com/v1/${element}/create`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'x-api-key': '530e4e8b-45be-4a7b-86f5-d98018838693',
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
        throw new Error(error);
    }
}

const update = async (token, element, id, dataToUpdate) => {
    try {
        const formData = new FormData();
        formData.append('_method', 'PATCH')

        for (let key in dataToUpdate) {
            if (dataToUpdate.hasOwnProperty(key)) {
                formData.append(key, dataToUpdate[key]);
            }
        }
        
        const response = await axios.post(`https://api.luifereduardoo.com/v1/${element}/${id}`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'x-api-key': '530e4e8b-45be-4a7b-86f5-d98018838693',
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
        throw new Error(error);
    }
};

const deleteElement = async (token, id, element, eliminateImages) => {
    const params = { id: id}
    try {
        const response = await axios.delete(`https://api.luifereduardoo.com/v1/${element}`, { params, eliminateImages,
        headers: {
            'Content-Type': 'application/json',
            'x-api-key': '530e4e8b-45be-4a7b-86f5-d98018838693',
            Authorization: `Bearer ${token}`,
        },
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