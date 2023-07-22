import React, { useState, useEffect } from "react";
import { Link, useLocation } from 'react-router-dom';
import styles from '../../assets/styles/modal.module.css';
import { XMarkIcon } from "@heroicons/react/24/outline";
import Cookies from 'js-cookie';
import { htmlToText} from 'html-to-text';
import ReactMarkdown from 'react-markdown';
import { Toaster, toast } from 'sonner';
import {dataDescrypt} from '../../utils/data-descrypt';
import Elements from '../../services/Elements';
import Images from '../../services/Images';
import { InputComponent } from './InputComponent';
import { Classification } from './Classification';
import { ImagesComponent } from './ImagesComponent';

function Modal({ setIsOpen, title, component: Component, element, technology=null, setSelectedFile=null, elementsPreview=null, updateOrDelete=null}) {
    return (
        <>
        <div className={styles.modalBackground}>
            <div className={styles.modalContainer}>
                <div className={styles.titleCloseBtn}>
                <button
                    onClick={() => {
                        setIsOpen(false);
                    }}
                >
                    <XMarkIcon className="h-6 w-6 text-gray-500" />
                </button>
                </div>
                <div className={styles.title}>
                <h2>{title}</h2>
                </div>
                    <Component setIsOpen={setIsOpen} element={element} technology={technology} setSelectedFile={setSelectedFile} elementsPreview={elementsPreview} updateOrDelete={updateOrDelete}/>
            </div>
        </div>
        </>
    );
}

const Buttons = ({setIsOpen, callToAPI, nameButtonAPI}) => {
    return(
        <div className={styles.footer}>
            <button className={styles.actionButton}
                onClick={() => {
                    setIsOpen(false);
                }}
                id={styles.cancelBtn}
            >
                Cancel
            </button>
            <button className={`${styles.actionButton} ${nameButtonAPI === 'Actualizar' ? styles.updateButton : (nameButtonAPI === 'Borrar' ? styles.deleteButton : styles.addButton)}`}
                onClick={async () => {
                    await callToAPI();
                }}
            >{nameButtonAPI}</button>
    </div>
    )
}

const ElementsEdit = ({setIsOpen, element, technology, updateOrDelete }) =>{
    const location = useLocation();

    const nameCategories = element.categories.map(category => category.name);
    const nameSubcategories = element.subcategories.map(subcategory => subcategory.name);
    const nameTags = element.tags.map(tag => tag.name);
    const [activeSection, setActiveSection] = useState("category");
    let dataTechnologies = null;
    if (location.pathname === '/administration-panel/content/projects'){
        dataTechnologies = element.technology.map(technology => ({
            id: technology.id,
            name: technology.name
        }));
    }
    const [name, setName] = useState('');
    const [date, setDate] = useState('');
    const [briefDescription, setBriefDescription] = useState('');
    const [urlRepository, setUrlRepository] = useState('');
    const [title, setTitle] = useState('');
    const [content, setContent] = useState('');
    const [authors, setAuthors] = useState('');
    const [imageCredits, setImageCredits] = useState('');


    const [selectedImages, setSelectedImages] = useState([]);
    const [replaceImage, setReplaceImage] = useState(false);
    const [selectedMiniature, setSelectedMiniature] = useState([]);
    const [replaceMiniature, setReplaceMiniature] = useState(false);
    const [categories, setCategories] = useState(nameCategories);
    const [subcategories, setSubcategories] = useState(nameSubcategories);
    const [tags, setTags] = useState(nameTags);
    const [technologies, setTechnologies] = useState(dataTechnologies);

    const handleSubmit = (e) => {
        e.preventDefault(); // Evita la recarga de la página
    }

    const handleUpdate = async () => {
        const encryptedToken = Cookies.get('token');
        const decryptedToken = dataDescrypt(encryptedToken);
        const dataToUpdate = { }
        let elementToUpdate = null;
        const classification = () => {
            if(categories.length != 0 && JSON.stringify(element.categories.map(category => category.name)) != JSON.stringify(categories)){
                dataToUpdate['categories'] = categories;
            } 
            if(subcategories.length != 0 && JSON.stringify(element.subcategories.map(subcategory => subcategory.name)) != JSON.stringify(subcategories)){
                dataToUpdate['subcategories'] = subcategories;
            } 
            if(tags.length != 0 && JSON.stringify(element.tags.map(tag => tag.name)) != JSON.stringify(tags)){
                dataToUpdate['tags'] = tags;
            }
        }
        classification();
        if(location.pathname === '/administration-panel/content/skills'){
            elementToUpdate = 'skills';
            if(name && name != element.name){
                dataToUpdate['name'] = name;
            } 
            if(selectedImages.length != 0){
                if(selectedImages[0].id && selectedImages[0].id != element.image[0].id){
                    dataToUpdate['id_image'] = selectedImages[0].id;
                } else if(selectedImages[0]){
                    dataToUpdate['image'] = selectedImages[0];
                }
            } 
            if(replaceImage){
                dataToUpdate['replace_image'] = replaceImage; 
            }  
            if(date && date != element.date){
                dataToUpdate['date'] = date;
            }
        } else if(location.pathname === '/administration-panel/content/projects'){
            elementToUpdate = 'project';
            if(name && name != element.name){
                dataToUpdate['name'] = name;
            } 
            if(briefDescription && briefDescription != element.brief_description){
                dataToUpdate['brief_description'] = briefDescription;
            }
            if(urlRepository && urlRepository != element.url_repository){
                dataToUpdate['url_repository'] = urlRepository;
            }
            if(selectedMiniature.length != 0){
                if(selectedMiniature[0].id && selectedMiniature[0].id != element.miniature[0].id){
                    dataToUpdate['id_miniature'] = selectedMiniature[0].id;
                } else if(selectedMiniature[0].file){
                    dataToUpdate['miniature'] = selectedMiniature[0];
                }
            }
            if(selectedImages.length != 0){
                if(selectedImages[0].id && JSON.stringify(selectedImages.map(image => image.id)) != JSON.stringify(element.image.map(image => image.id))){
                    dataToUpdate['ids_images'] = selectedImages.map(image => image.id);
                } else if(selectedImages[0]){
                    dataToUpdate['images'] = selectedImages;
                }
            }
            if(replaceImage){
                dataToUpdate['replace_image'] = replaceImage; 
            }
            if(replaceMiniature){
                dataToUpdate['replace_miniature'] = replaceMiniature; 
            }
            if (technologies.length != 0 && JSON.stringify(element.technology.map(technology => technology.id)) != JSON.stringify(technologies.map(technology => technology.id))){
                dataToUpdate['technologies'] = technologies.map(tecnhology => tecnhology.id);
            }
        } else if(location.pathname === '/administration-panel/content/blog'){
            elementToUpdate = 'blogpost';
            if(title && title != element.title){
                dataToUpdate['title'] = title;
            }
            if(selectedImages.length != 0){
                if(selectedImages[0].id && selectedImages[0].id != element.image[0].id){
                    dataToUpdate['id_image'] = selectedImages[0].id;
                } else if(selectedImages[0]){
                    dataToUpdate['image'] = selectedImages[0];
                }
            } 
            if(replaceImage){
                dataToUpdate['replace_image'] = replaceImage; 
            }
            if(content && content != htmlToText(element.content)){
                dataToUpdate['content'] = content;
            }
            if(authors && authors != element.authors){
                dataToUpdate['authors'] = authors;
            }
            if(imageCredits && imageCredits != element.image_credits){
                dataToUpdate['image_credits'] = imageCredits;
            }
        }
        if(Object.keys(dataToUpdate).length != 0){
            try{
                const update = await Elements.update(decryptedToken, elementToUpdate, element.id, dataToUpdate);
                toast.success(update.message);
                updateOrDelete(true)
            } catch (error) {
                toast.error(error.message);
            }
        } else{
            toast.error('Ningún elemento ha sido actualizado');
        }
    }

    return(
        <>
            <div className={styles.body}>
                <form onSubmit={handleSubmit} className="space-y-4">
                    {location.pathname === '/administration-panel/content/skills'  ? (
                        <>
                            <InputComponent title={'Nombre'} id={'name'} setElement={setName} defaulValue={element.name}/>
                            <InputComponent title={'Fecha de obtención de la Skill'} id={'date'} setElement={setDate} defaulValue={element.date}/>
                        </>
                    ) : location.pathname === '/administration-panel/content/projects' ? (
                        <>
                            <InputComponent title={'Nombre'} id={'name'} setElement={setName} defaulValue={element.name}/>
                            <InputComponent title={'Breve descripción'} TypeInput={'textarea'} id={'brief_description'} setElement={setBriefDescription} defaulValue={element.brief_description}/>
                            <InputComponent title={'Url repositorio GitHub'} id={'url_repository'} setElement={setUrlRepository} defaulValue={element.url_repository}/>
                        </>
                    ) : (
                        <>
                            <InputComponent title={'Titulo'} id={'title'} setElement={setTitle} defaulValue={element.title}/>
                            <InputComponent title={'Contenido'} id={'content'} TypeInput={'textarea'} setElement={setContent} defaulValue={element.content}/>
                            <InputComponent title={'Autores'} id={'authors'} setElement={setAuthors} defaulValue={element.authors}/>
                            <InputComponent title={'Creditos de la imagen'} id={'image_credits'} setElement={setImageCredits} defaulValue={element.image_credits}/>
                        </>
                    )}
                        <ImagesComponent setSelectedFile={setSelectedImages} selectedFile={selectedImages} tipeFile={'Imagen'} setReplaceFile={setReplaceImage} replaceFile={replaceImage}/>
                        {location.pathname === '/administration-panel/content/projects' ? (
                            <>
                                <ImagesComponent setSelectedFile={setSelectedMiniature} selectedFile={selectedMiniature} tipeFile={'Miniature'} setReplaceFile={setReplaceMiniature} replaceFile={replaceMiniature}/>
                                <Classification element={element} haveTechnology={true} activeSection={activeSection} setActiveSection={setActiveSection} technology={technology} setTechnologies={setTechnologies} technologies={technologies} categories={categories} setCategories={setCategories} subcategories={subcategories} setSubcategories={setSubcategories} tags={tags} setTags={setTags} />
                            </>
                        ) : (<Classification element={element} activeSection={activeSection} setActiveSection={setActiveSection} categories={categories} setCategories={setCategories} subcategories={subcategories} setSubcategories={setSubcategories} tags={tags} setTags={setTags}/>) }
                </form>
            </div>
            <Buttons setIsOpen={setIsOpen} callToAPI={handleUpdate} nameButtonAPI={'Actualizar'}/>
        </>
    )
}

const ElementsDelete = ({setIsOpen, element, updateOrDelete }) => {

    const [deleteFile, setDeleteFile] = useState(false);

    const handleDeleteFile = (e) => {
        setDeleteFile(e.target.checked);
    };

    const handleSubmit = (e) => {
        e.preventDefault(); // Evita la recarga de la página
    }

    const handleDelete = async () => {
        const encryptedToken = Cookies.get('token');
        const decryptedToken = dataDescrypt(encryptedToken);
        let elementToDelete = null;
        let eliminateImage = { }
        if(location.pathname === '/administration-panel/content/skills'){
            elementToDelete = 'skills';
            eliminateImage['eliminate_image'] = deleteFile;
        } else if(location.pathname === '/administration-panel/content/projects'){
            elementToDelete = 'project';
            eliminateImage['eliminate_images'] = deleteFile;
        } else if(location.pathname === '/administration-panel/content/blog'){
            elementToDelete = 'blogpost';
            eliminateImage['eliminate_image'] = deleteFile;
        }
        try{
            let deleteElement = null;
            if(location.pathname === '/administration-panel/images'){
                let deleteElement = await Images.deleteImage(decryptedToken, element.id);
            } else{
                let deleteElement = await Elements.deleteElement(decryptedToken, element.id, elementToDelete, eliminateImage);
            }
            toast.success('successfully removed');
            setIsOpen(false);
            updateOrDelete(true)
        } catch (error) {
            toast.error(error.message);
            setIsOpen(false);
        }
    }

    return(
        <>
            <div className={styles.body}>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <h2 className="text-2xl font-bold text-gray-800">¿Seguro quieres eliminar el elemento?</h2>
                    {location.pathname != '/administration-panel/images' && (
                        <div className="flex items-center">
                            <input
                                type="checkbox"
                                checked={deleteFile}
                                onChange={handleDeleteFile}
                                className="mr-2 text-indigo-500 focus:ring-indigo-500"
                            />
                            <label className="text-gray-700">Borrar la/las Imagenes asociadas a el elemento</label>
                        </div>
                    )}
                </form>
            </div>
            <Buttons setIsOpen={setIsOpen} callToAPI={handleDelete} nameButtonAPI={'Borrar'}/>
        </>
    );
}

const ElementPreview = ({ setIsOpen, element, elementsPreview }) => {
    return(
        <>
        <div className={styles.body}>
            {element === 'skills' ? (
                <div 
                className="bg-white p-4 rounded-lg shadow relative">
                    <img
                        className="mt-2 rounded-lg h-40 w-40"
                        src={elementsPreview.images[0].url ? elementsPreview.images[0].url : elementsPreview.images[0] ? URL.createObjectURL(elementsPreview.images[0]) : null}
                        alt={elementsPreview.images[0].name ? elementsPreview.images[0].name : null}
                    />
                    <h1 className="text-xl font-bold">{elementsPreview.name}</h1>
                </div>
            ) : element === 'project' ? (
                <div 
                className="bg-white p-4 rounded-lg shadow relative">
                    <h1 className="text-xl font-bold">{elementsPreview.name}</h1>
                    <img
                        className="mt-2 rounded-lg h-auto w-full"
                        src={elementsPreview.miniature[0].url ? elementsPreview.miniature[0].url : elementsPreview.miniature[0] ? URL.createObjectURL(elementsPreview.miniature[0]) : null }
                        alt={elementsPreview.miniature[0].name ? elementsPreview.miniature[0].name : null}
                    />
                    <p className="mt-2">{elementsPreview.briefDescription}</p>
                </div>
            ) : (
                <div 
                className="bg-white p-4 rounded-lg shadow relative">
                    <h1 className="text-xl font-bold">{elementsPreview.title}</h1>
                    <section>
                        <img
                            className="mt-2 rounded-lg h-auto w-full"
                            src={elementsPreview.images[0].url ? elementsPreview.images[0].url : elementsPreview.images[0] ? URL.createObjectURL(elementsPreview.images[0]) : null}
                            alt={elementsPreview.images[0].name ? elementsPreview.images[0].name : null}
                        />
                        <p>{elementsPreview.imageCredits}</p>
                    </section>
                    <p>Autor: {elementsPreview.authors}</p>
                    <div className="text-justify">
                        <ReactMarkdown>{elementsPreview.content}</ReactMarkdown>
                    </div>
                </div>
            )} 
        </div>
    </>
    )
}

export { Modal, ElementsEdit, ElementsDelete, ElementPreview, Buttons };