import CryptoJS from 'crypto-js';
const secretKey = process.env.REACT_APP_SECRET_KEY;

export const dataDescrypt = (value) => {
    const decryptedBytes = CryptoJS.AES.decrypt(value, secretKey);
    const decryptedData = decryptedBytes.toString(CryptoJS.enc.Utf8);
    return decryptedData;
}