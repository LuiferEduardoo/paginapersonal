import CryptoJS from 'crypto-js';
import { secretKey } from './secret-key';

export const dataEncrypt = (value) =>{
    const encryptedData = CryptoJS.AES.encrypt(value, secretKey).toString();
    return encryptedData;
}