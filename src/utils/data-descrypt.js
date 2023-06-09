import CryptoJS from 'crypto-js';
import { secretKey } from './secret-key';

export const dataDescrypt = (value) => {
    const decryptedBytes = CryptoJS.AES.decrypt(value, secretKey);
    const decryptedData = decryptedBytes.toString(CryptoJS.enc.Utf8);
    return decryptedData;
}