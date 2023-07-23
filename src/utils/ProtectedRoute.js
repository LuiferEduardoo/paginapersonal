import React, { useState, useEffect } from 'react';
import { Navigate } from 'react-router-dom';
import { dataDescrypt } from './data-descrypt';
import AuthService from '../services/AuthService';
import Cookies from 'js-cookie';
import { Skeleton } from "@mui/material";

function ProtectedRoute({ isToken, children }) {
    const [userInfo, setUserInfo] = useState(null);
    const [isLoading, setIsLoading] = useState(true); // Nuevo estado para controlar la carga

    useEffect(() => {
        const checkToken = async () => {
        try {
            const encryptedToken = Cookies.get('token');
            const decryptedToken = dataDescrypt(encryptedToken);
            if (decryptedToken) {
            const userInfo = await AuthService.userInfo(decryptedToken);
            setUserInfo(userInfo);
            } else {
            throw new Error('Invalid token');
            }
        } catch (error) {
            Cookies.remove('token');
            console.error('Error al obtener la información del usuario:', error);
        } finally {
            setIsLoading(false); // Marcar la carga como completa, independientemente del resultado
        }
        };

        checkToken();
    }, []);

    if (isLoading) {
        // Mostrar un componente de carga mientras se obtiene la información del usuario
        return (
            <div className="flex items-center justify-center h-screen">
                <Skeleton 
                    variant="rectangular"
                    width={900}
                    height={718}
                />
            </div>
        )
    }

    if (!isToken) {
        return <Navigate to="/login" />;
    }

    return React.Children.map(children, (child) => {
        return React.cloneElement(child, { token: dataDescrypt(Cookies.get('token')), userInfo });
    });
}

export { ProtectedRoute };

