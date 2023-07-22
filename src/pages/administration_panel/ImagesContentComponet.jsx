import React, { useState, useEffect, useRef } from "react";
import { Routes, Route } from 'react-router-dom';
import { ClipboardDocumentIcon, TrashIcon } from "@heroicons/react/24/outline";
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

    const handleCopyUrl = (image) => {
        navigator.clipboard.writeText(image.url).then(() => {
            toast.success('URL de imagen copiada al portapapeles');
        });
    }
    if (isLoading) {
        return <div>Loading...</div>;
    }

    if (error) {
        return <div>Error al obtener las imagenes</div>;
    }

    return(
        <>
            {isOpenDelete && <Modal setIsOpen={setIsOpenDelete} title='Borrar' component={ElementsDelete} element={valueImage} />}
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
                            <div className="absolute top-2 right-2 z-0">
                                <TrashIcon
                                    className="h-6 w-6 text-gray-500 cursor-pointer"
                                    onClick={() => handleDeleteClick(image)}
                                />
                                <ClipboardDocumentIcon 
                                    className="h-6 w-6 text-gray-500 cursor-pointer"
                                    onClick={() => handleCopyUrl(image)}
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
    const fileInputRef = useRef(null);
    const [selectedImage, setSelectedImage] = useState(null);
    const [urlImage, setUrlImage] = useState(null);
    const [loading, setLoading] = useState(false);
    const [uploadSuccess, setUploadSuccess] = useState(false);
    const [selectFolder, setSelecdFolder] = useState('about');

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
            const response = await Images.upload(decryptedToken, file, selectFolder);
            setUrlImage(response.database.url);
            setUploadSuccess(true);
            toast.success(response.message);

        } catch (error) {
            toast.error('Error al cargar la imagen:', error);
            // Manejar el error, mostrar notificaciones, etc.
            setUploadSuccess(false);
        }
    };

    const handleCopyUrl = () => {
        navigator.clipboard.writeText(urlImage).then(() => {
            toast.success('URL de imagen copiada al portapapeles');
        });
    };

    const handleClick = () => {
        fileInputRef.current.click();
    };

    const handleSelectChange = (event) => {
        setSelecdFolder(event.target.value);
      };

    return (
        <>
            <div>
                <div
                className="drop-area"
                onDragOver={handleDragOver}
                onDrop={handleDrop}
                >
                {loading ? (
                    <div>
                        Subiendo...
                    </div>
                ) : uploadSuccess ? (
                    <div>
                        <button onClick={handleCopyUrl} className="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">Copiar URL de imagen</button>
                        <img src={selectedImage} alt="Selected" className="h-50 w-50"/>
                    </div>
                ) : selectedImage ? (
                    <img src={selectedImage} alt="Selected" />
                ) : (
                    <>
                        <div className="flex flex-col space-y-4">
                        <label htmlFor="image-folder" className="text-sm font-medium text-gray-700">
                            Folder donde se guarda la imagen:
                        </label>
                        <select
                            id="image-folder"
                            value={selectFolder}
                            onChange={handleSelectChange}
                            className="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 focus:outline-none"
                        >
                            <option value="about">About</option>
                            <option value="banner">Banner</option>
                            <option value="blog">Blog</option>
                            <option value="email">Email</option>
                            <option value="error-page">Error paginas</option>
                            <option value="icon">Icon</option>
                            <option value="logo">Logo</option>
                            <option value="project">Project</option>
                            <option value="skill">Skill</option>
                        </select>
                        </div>
                        <h1>Arrastra y suelta una imagen aquí o haz click para seleccionarla</h1>
                        <div className="flex items-center justify-center">
                            <button
                                type="button"
                                onClick={handleClick}
                                className="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 border-none"
                            >
                                Subir imagen
                            </button>
                            <input
                                ref={fileInputRef}
                                type="file"
                                accept="image/*"
                                onChange={handleImageUpload}
                                className="hidden"
                            />
                        </div>
                    </>
                )}
                </div>
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