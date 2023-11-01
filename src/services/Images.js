import axios from 'axios';
const apiServiceImage = process.env.REACT_APP_API_SERVICE_IMAGE;

const obtain = async (token) => {
    try {
        const response = await axios.get(`${apiServiceImage}/images`,{
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

const upload = async (token, image, folder) => {
    const formData = new FormData();
    formData.append('image', image); // Agregar la imagen al FormData
    formData.append('folder', folder); // Agregar la imagen al FormData
    try {
        const response = await axios.post(`${apiServiceImage}/images/create`, formData, {
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
        const response = await axios.delete(`${apiServiceImage}/images?id=${id}`, {
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