import axios from 'axios';
import React from 'react';
const apiKey = process.env.REACT_APP_API_KEY;


const obtain = async (element) => {
    try {
        const response = await axios.get(`https://api.luifereduardoo.com/v1/${element}`,{
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