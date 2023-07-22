import React, { useState, useEffect } from "react";
import { Toaster, toast } from 'sonner';
import Cookies from 'js-cookie';
import {dataDescrypt} from '../../utils/data-descrypt';
import AuthService from '../../services/AuthService';
import { InputComponent } from './InputComponent';
import { ImagesComponent } from './ImagesComponent';
import styles from '../../assets/styles/administrationPanel.module.css';

const Settings = ({ userInfo }) =>{
    const [name, setName] = useState("");
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [newPassword, setNewPassword] = useState("");
    const [repeatNewPassword, setRepeatNewPassword] = useState("");
    const [profilePicture, setProfilePicture] = useState("");
    const [replaceProfile, setReplaceProfile] = useState(false);
    console.log(userInfo);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const encryptedToken = Cookies.get('token');
        const decryptedToken = dataDescrypt(encryptedToken);
        const dataToUpdate = { }
        if(profilePicture.length != 0){
            if(profilePicture[0].id && profilePicture[0].id != userInfo[0].profile[0].id){
                dataToUpdate['id_image'] = profilePicture[0].id;
            } else if(profilePicture[0]){
                dataToUpdate['image'] = profilePicture[0];
            }
        }
        if(name && name != userInfo[0].name){
            dataToUpdate['name'] = name;
        }
        if(email && email != userInfo[0].email){
            dataToUpdate['email'] = email;
        }
        if(newPassword){
            if(repeatNewPassword === newPassword){
                dataToUpdate['password'] = password;
                dataToUpdate['new_password'] = newPassword;
            }
            else{
                return toast.error('Las contraseñas no son iguales');
            }
        }
        if(replaceProfile){
            dataToUpdate['replace_image'] = replaceProfile; 
        }
        if(Object.keys(dataToUpdate).length != 0){
            try{
                const update = await AuthService.update(decryptedToken, dataToUpdate);
                toast.success(update.message);
            } catch (error) {
                toast.error(error.message);
            }
        } else{
            toast.error('Ningún elemento ha sido actualizado');
        }
    };
    return(
        <div className={styles.contentSetting}>
            <Toaster richColors position="top-center" />
            <h2>Editar Perfil</h2>
            <form onSubmit={handleSubmit} className="space-y-4">
                <ImagesComponent 
                    setSelectedFile={setProfilePicture} 
                    selectedFile={profilePicture} 
                    tipeFile={'Foto de perfil'} 
                    setReplaceFile={setReplaceProfile} 
                    replaceFile={replaceProfile}
                />
                <InputComponent 
                    title={'Nombre'} 
                    id={'name'} 
                    setElement={setName}
                    defaulValue={userInfo[0].name}
                />

                <InputComponent
                    title={'Email'} 
                    id={'email'} 
                    setElement={setEmail}
                    defaulValue={userInfo[0].email}
                />

                <InputComponent
                    type={'password'}
                    title={'Contraseña actual'} 
                    id={'password'} 
                    setElement={setPassword}
                    placeholder={'********'}
                />
                <InputComponent
                    type={'password'}
                    title={'Nueva contraseña'} 
                    id={'new_password'} 
                    setElement={setNewPassword}
                    placeholder={'********'}
                />

                <InputComponent
                    type={'password'}
                    title={'Repite la nueva contraseña'} 
                    id={'repeat_new_password'} 
                    setElement={setRepeatNewPassword}
                    placeholder={'********'}
                />
                <button type="submit" className="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 border-none">
                    Actualizar
                </button>
            </form>
        </div>
    )
}

export default Settings;