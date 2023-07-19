import axios from 'axios';

const obtain = async (token) => {
    try {
        const response = await axios.get(`https://cdn.luifereduardoo.com/api/images`,{
        headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${token}`,
        },
        });

        if (response.status === 200) {
        const elements = response.data;
        return elements;
        } else {
            throw new Error(`Error a la hora de obtener la imagen`);
        }
    } catch (error) {
        throw new Error(error);
    }
};

const upload = async (token, image) => {
    const formData = new FormData();
    formData.append('image', image); // Agregar la imagen al FormData
    try {
        const response = await axios.post(`https://cdn.luifereduardoo.com/api/images/create`, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            Authorization: `Bearer ${token}`,
        },
        });

        if (response.status === 200) {
        const elements = response.data;
        return elements;
        } else {
            throw new Error(`Error a la hora de subir la imagen`);
        }
    } catch (error) {
        throw new Error(error);
    }
};

const deleteImage = async (token, id) => {
    try {
        const response = await axios.delete(`https://cdn.luifereduardoo.com/api/images?id=${id}`, {
        headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${token}`,
        },
        });

        if (response.status === 200) {
        const elements = response.data;
        return elements;
        } else {
            throw new Error(`Error a la hora de eliminar la imagen`);
        }
    } catch (error) {
        throw new Error(error);
    }
}

export default { obtain, deleteImage, upload };