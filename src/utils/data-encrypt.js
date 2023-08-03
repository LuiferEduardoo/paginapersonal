import CryptoJS from 'crypto-js';
const secretKey = process.env.REACT_APP_SECRET_KEY;

export const dataEncrypt = (value) =>{
    const encryptedData = CryptoJS.AES.encrypt(value, secretKey).toString();
    return encryptedData;
}