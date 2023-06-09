import React, { useState, useEffect } from 'react';
import { Navigate } from 'react-router-dom';
import {dataDescrypt} from './data-descrypt';
import AuthService from '../services/AuthService';
import Cookies from 'js-cookie';

function ProtectedRoute({isToken, children}) {
    const checkToken = async () => {
        return new Promise(async (resolve, reject) => {
            const encryptedToken = Cookies.get('token');
            const decryptedToken = dataDescrypt(encryptedToken);
            if (decryptedToken) {
                try {
                const userInfo = await AuthService.userInfo(decryptedToken);
                resolve(userInfo);
                } catch (error) {
                Cookies.remove('token');
                reject(error);
                }
            } else {
                reject(new Error('Invalid token'));
            }
        });
    };
    
    useEffect(() => {
        checkToken()
            .then(() => {
                // Token is valid, do nothing
            })
            .catch(() => {
                // Token is invalid, redirect to login
                window.location.href = '/login';
        });
    }, []);

    if(!isToken){
        return <Navigate to='/login' />
    }

    return children;
}
export {ProtectedRoute};
