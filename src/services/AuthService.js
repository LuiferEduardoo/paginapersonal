const login = async (email, password) => {
    try {
        const response = await fetch('https://api.luifereduardoo.com/v1/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'x-api-key': '530e4e8b-45be-4a7b-86f5-d98018838693'
            },
            mode: 'cors',
            body: JSON.stringify({
            email: email,
            password: password,
            }),
        });
    
        if (response.ok) {
            const data = await response.json();
            const token = data.access_token;
            return token;
        } else {
            throw new Error('Credenciales incorrectas');
        }
    } catch (error) {
        throw new Error(error);
    }
};

const userInfo = async (token) =>{
    try {
        const response = await fetch('https://api.luifereduardoo.com/v1/user', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'x-api-key': '530e4e8b-45be-4a7b-86f5-d98018838693',
                Authorization: `Bearer ${token}`,
            },
        });

        if (response.ok) {
            // El token es válido
            const userInfo = await response.json();
            return userInfo;
        } else {
            // El token es inválido o ha expirado
            throw new Error('Token inválido');
        }
    } catch (error) {
        // Manejar cualquier error de la solicitud
        console.error('Error al obtener la información del usuario:', error);
        throw new Error('Error al obtener la información del usuario');
    }
}

export default { login, userInfo };
