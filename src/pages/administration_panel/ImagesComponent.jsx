import React, { useState, useEffect } from "react";
import styles from '../../assets/styles/modal.module.css';
import Cookies from 'js-cookie';
import Images from '../../services/Images';
import { Modal, Buttons } from "./Modal";
import {dataDescrypt} from '../../utils/data-descrypt';

const ModalImagen = ({setIsOpen, element, setSelectedFile}) =>{

    const [imagesPreSelected, setImagesPreSelected] = useState([]);

    const handleAddImage = () => {
        setSelectedFile(imagesPreSelected);
        setIsOpen(false)
    };

    const handleImagesSelect = (image) => {
        const isSelected = imagesPreSelected.some((imagePreSelected) => imagePreSelected.id === image.id);
        if (isSelected) {
            setImagesPreSelected(imagesPreSelected.filter((imagePreSelected) => imagePreSelected.id !== image.id));
        } else {
            setImagesPreSelected([...imagesPreSelected, image]);
        }
    };

    return (
        <>
            <div className={styles.body}>
                <div className="grid grid-cols-3 gap-4">
                    {element.map((image) => (
                        <div>
                            <img
                                key={image.id}
                                src={image.url}
                                alt={image.name}
                                onClick={() => handleImagesSelect(image)}
                                className={` mt-2 rounded-lg h-40 w-40 object-cover mx-auto cursor-pointer ${imagesPreSelected.some((imagePreSelected) => imagePreSelected.id === image.id) ? "opacity-100 border-4 border-blue-500" : "opacity-50"}`}
                            />
                        </div>
                    ))}
                </div>
            </div>
            <Buttons setIsOpen={setIsOpen} callToAPI={handleAddImage} nameButtonAPI={'Agregar'} />
        </>
    );
}

const ImagesComponent = ({setSelectedFile, selectedFile, tipeFile, setReplaceFile=null, replaceFile=null}) => {
    const [selectedOption, setSelectedOption] = useState("computer");
    const [imageOpen, setImageOpen] = useState(false);
    const [images, setImages] = useState([]);
    
    const openImage = async () => {
        setImageOpen(true);
        const encryptedToken = Cookies.get('token');
        const decryptedToken = dataDescrypt(encryptedToken);
        try {
            const response = await Images.obtain(decryptedToken);
            setImages(response);
        } catch (error) {
            setImages(error.message);
        }
    };

    const handleImageUpload = (e) => {
        const files = Array.from(e.target.files);
        setSelectedFile(files);
    };

    const handleMiniatureUpload = (e) => {
        const files = Array.from(e.target.files);
        setSelectedFile(files);
    };

    const handleReplaceFile = (e) => {
        setReplaceFile(e.target.checked);
    };

    return (
        <>
            {imageOpen && <Modal setIsOpen={setImageOpen} title='Imagen' component={ModalImagen} element={images} setSelectedFile={setSelectedFile} />}
            <div className="flex flex-col">
                <label htmlFor="image" className="text-sm font-medium text-gray-700 flex items-start">
                    {tipeFile}:
                </label>
                <div className="flex mt-1">
                    {selectedOption === "computer" && (
                    <label
                        htmlFor="uploadFile"
                        className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md cursor-pointer"
                    >
                        Cargar desde el ordenador
                        <input
                        type="file"
                        id="uploadFile"
                        className="hidden"
                        accept="image/*"
                        multiple
                        onChange={tipeFile === 'Image' ? handleImageUpload : handleMiniatureUpload}
                        />
                    </label>
                    )}
                    {selectedOption === "app" && (
                        <button
                            className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md border-none text-sm"
                            onClick={openImage}
                        >
                            Cargar desde la aplicación
                        </button>
                    )}
                </div>
                {selectedFile.length > 0 && (
                    <>
                        <ul className="mt-2">
                            {selectedFile.map((file, index) => (
                                <li key={index} className="text-gray-500">
                                {file.name}
                                </li>
                            ))}
                        </ul>
                        {replaceFile != null && (
                            <div className="flex items-center">
                                <input
                                    type="checkbox"
                                    checked={replaceFile}
                                    onChange={handleReplaceFile}
                                    className="mr-2 text-indigo-500 focus:ring-indigo-500"
                                />
                                <label className="text-gray-700">Reemplazar {tipeFile} existente</label>
                            </div>
                        )}

                    </>
                )}
                <div className="mt-2">
                    <label htmlFor="option" className="text-sm font-medium text-gray-700">
                    Selecciona la opción:
                    </label>
                    <select
                    id="option"
                    className="border border-gray-300 rounded-md px-3 py-2 mt-1 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    value={selectedOption}
                    onChange={(e) => setSelectedOption(e.target.value)}
                    >
                    <option value="computer">Cargar desde el ordenador</option>
                    <option value="app">Cargar desde la aplicación</option>
                    </select>
                </div>
            </div>
        </>
    );
}
export { ImagesComponent };