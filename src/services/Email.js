import axios from 'axios';
import React from 'react';

const PostEmail = async (data) => {
    try {
        const formData = new FormData();
        for (let key in data) {
            if (data.hasOwnProperty(key)) {
                    formData.append(key, data[key]);
            }
        }
        const response = await axios.post(`https://api.luifereduardoo.com/v1/email`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'x-api-key': '530e4e8b-45be-4a7b-86f5-d98018838693',
        },
        });

        if (response.status === 200) {
        const elements = response.data;
        return elements;
        } else {
            throw new Error(`Error a la hora de enviar el email`);
        }
    } catch (error) {
        if (error.response && error.response.data) {
            throw new Error(error.response.data.message);
        }
        throw new Error(error);
    }
}

export default { PostEmail };