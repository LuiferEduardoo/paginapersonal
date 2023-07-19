import React, { useState, useEffect } from "react";
import { Routes, Route } from 'react-router-dom';
import { PencilSquareIcon, TrashIcon } from "@heroicons/react/24/outline";
import { Toaster, toast } from 'sonner';
import Cookies from 'js-cookie';
import Images from '../../services/Images';
import SubMenu from './SubMenu';
import styles from '../../assets/styles/administrationPanel.module.css';
import { Modal, ElementsDelete } from "./Modal";
import {dataDescrypt} from '../../utils/data-descrypt';

const ContentImagesView = () => {
    const [images, setImages] = useState([]);
    const [technology, setTechnology] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);
    const [isOpenEdit, setIsOpenEdit] = useState(false);
    const [isOpenDelete, setIsOpenDelete] = useState(false);
    const [hoveredIndex, setHoveredIndex] = useState(null);
    const [valueImage, setValueImage] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const encryptedToken = Cookies.get('token');
                const decryptedToken = dataDescrypt(encryptedToken);
                const callToAPI = await Images.obtain(decryptedToken);
                setImages(callToAPI);
                setIsLoading(false);
            } catch (error) {
                setError(error.message);
                console.log(error.message);
                setIsLoading(false);
            }
        };

        fetchData();
    }, [])
        const handleDeleteClick = (image) => {
            setIsOpenDelete(true);
            setValueImage(image);
        };

    if (isLoading) {
        return <div>Loading...</div>;
    }

    if (error) {
        return <div>Error al obtener las imagenes</div>;
    }

    return(
        <>
            {isOpenDelete && <Modal setIsOpen={setIsOpenDelete} title='Borrar' component={ElementsDelete} element={valueImage} />}
            <Toaster richColors position="top-center" />
            <section className={`${styles.viewElements} grid grid-cols-4 gap-20`}>
                {images.map((image, index) => (
                    <div
                    key={image.id}
                    className="bg-white p-4 rounded-lg shadow relative"
                    onMouseEnter={() => setHoveredIndex(index)}
                    onMouseLeave={() => setHoveredIndex(null)}
                    >
                        <img
                            className="mt-2 rounded-lg h-auto w-full"
                            src={image.url}
                            alt={image.name}
                        />
                        {hoveredIndex === index && (
                            <div className="absolute top-2 right-2 z-10">
                                <TrashIcon
                                    className="h-6 w-6 text-gray-500"
                                    onClick={() => handleDeleteClick(image)}
                                />
                            </div>
                        )}
                    </div>
                ))}
        </section>
        </>
    )
}

const ContentImagesUpload = () => {
    const [selectedImage, setSelectedImage] = useState(null);
    const [loading, setLoading] = useState(false);
    const [uploadSuccess, setUploadSuccess] = useState(false);

    const handleImageUpload = async (event) => {
        const file = event.target.files[0];
        setSelectedImage(URL.createObjectURL(file));
        setLoading(true);
        await uploadImage(file);
        setLoading(false);
    };

    const handleDragOver = (event) => {
        event.preventDefault();
    };

    const handleDrop = (event) => {
        event.preventDefault();
        const file = event.dataTransfer.files[0];
        setSelectedImage(URL.createObjectURL(file));
        setLoading(true);
        uploadImage(file);
        setLoading(false);
    };

    const uploadImage = async (file) => {
        try {
            const encryptedToken = Cookies.get('token');
            const decryptedToken = dataDescrypt(encryptedToken);
            const response = await Images.upload(decryptedToken, file);
            setUploadSuccess(true);
        } catch (error) {
            toast.error('Error al cargar la imagen:', error);
            // Manejar el error, mostrar notificaciones, etc.
            setUploadSuccess(false);
        }
    };

    const handleCopyUrl = () => {
        const imageUrl = selectedImage;
        navigator.clipboard.writeText(imageUrl).then(() => {
            toast.success('URL de imagen copiada al portapapeles');
        });
    };

    return (
        <>
            <Toaster richColors position="top-center" />
            <div>
                <div
                className="drop-area"
                onDragOver={handleDragOver}
                onDrop={handleDrop}
                >
                {loading ? (
                    <p>Cargando...</p>
                ) : uploadSuccess ? (
                    <div>
                    <p>Carga exitosa!</p>
                    <img src={selectedImage} alt="Selected" />
                    <button onClick={handleCopyUrl}>Copiar URL de imagen</button>
                    </div>
                ) : selectedImage ? (
                    <img src={selectedImage} alt="Selected" />
                ) : (
                    <p>Arrastra y suelta una imagen aquí o haz clic para seleccionarla</p>
                )}
                </div>
                <input
                type="file"
                accept="image/*"
                onChange={handleImageUpload}
                />
            </div>
        </>
    );
}

const ImagesContent = ({ selectedSection }) => {
    return(
        <>
            { selectedSection === 'View' && <ContentImagesView/>}
            { selectedSection === 'Create' && <ContentImagesUpload/>}
        </>
    )
}

const ImagesContentComponet = () => {
    const [selectedSection, setSelectedSection] = useState('View');

    const handleViewClick = () => {
        setSelectedSection('View');
    };

    const handleCreateClick = () => {
        setSelectedSection('Create');
    };
    return(
        <>
            <SubMenu
                selectedSection={selectedSection}
                onViewClick={handleViewClick}
                onCreateClick={handleCreateClick}
                />
            <div className={styles.contentContent}>
                <ImagesContent selectedSection={selectedSection}/>
            </div>
        </>
    )
}

export default ImagesContentComponet;