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