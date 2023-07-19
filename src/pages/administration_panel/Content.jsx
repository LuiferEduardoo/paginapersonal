import React, { useState, useEffect } from "react";
import { Routes, Route } from 'react-router-dom';
import { PencilSquareIcon, TrashIcon } from "@heroicons/react/24/outline";
import { Toaster, toast } from 'sonner';
import moment from 'moment';
import Cookies from 'js-cookie';
import SubMenu from './SubMenu';
import styles from '../../assets/styles/administrationPanel.module.css';
import { Modal, ElementsEdit, ElementsDelete, ElementPreview } from "./Modal";
import {dataDescrypt} from '../../utils/data-descrypt';
import Elements from '../../services/Elements';
import { InputComponent } from './InputComponent';
import { Classification } from './Classification';
import { ImagesComponent } from './ImagesComponent';

const ViewElements = ({ elementObtain }) => {
    const [elements, setElements] = useState([]);
    const [technology, setTechnology] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);
    const [isOpenEdit, setIsOpenEdit] = useState(false);
    const [isOpenDelete, setIsOpenDelete] = useState(false);
    const [hoveredIndex, setHoveredIndex] = useState(null);
    const [valueElement, setValueElement] = useState(null);


    useEffect(() => {
        const fetchData = async () => {
            try {
                const callToAPI = await Elements.obtain(elementObtain);
                if(elementObtain === 'projects'){
                    const callToAPITwo = await Elements.obtain('skills');
                    setTechnology(callToAPITwo);
                }
                setElements(callToAPI);
                setIsLoading(false);
            } catch (error) {
                setError(error.message);
                setIsLoading(false);
            }
        };

        fetchData();
        }, [elementObtain]);

        const handleEditClick = (element) => {
            setIsOpenEdit(true);
            setIsOpenDelete(false);
            setValueElement(element);
        };
        
        const handleDeleteClick = (element) => {
            setIsOpenDelete(true);
            setIsOpenEdit(false);
            setValueElement(element);
        };

    if (isLoading) {
        return <div>Loading...</div>;
    }

    if (error) {
        return <div>Error al obtener el/la {elementObtain}</div>;
    }
    console.log(elements);
    return (
        <>
            {isOpenEdit && <Modal setIsOpen={setIsOpenEdit} title='Editar' component={ElementsEdit} element={valueElement} technology={technology} />}
            {isOpenDelete && <Modal setIsOpen={setIsOpenDelete} title='Borrar' component={ElementsDelete} element={valueElement} />}
            <Toaster richColors position="top-center" />
            <section className={`${styles.viewElements} grid grid-cols-4 gap-20`}>
                {elements.map((element, index) => (
                    <div
                    key={element.id}
                    className="bg-white p-4 rounded-lg shadow relative"
                    onMouseEnter={() => setHoveredIndex(index)}
                    onMouseLeave={() => setHoveredIndex(null)}
                    >
                        <h1 className="text-xl font-bold">{element.name}</h1>
                        <img
                            className="mt-2 rounded-lg h-auto w-full"
                            src={element.image[0].url}
                            alt={element.name}
                        />
                        {element.brief_description ? (
                            <p className="mt-2">{element.brief_description}</p>
                        ) : null}
                        {hoveredIndex === index && (
                            <div className="absolute top-2 right-2 z-10">
                                <PencilSquareIcon
                                    className="h-6 w-6 text-gray-500"
                                    onClick={() => handleEditClick(element)}
                                />
                                <TrashIcon
                                    className="h-6 w-6 text-gray-500"
                                    onClick={() => handleDeleteClick(element)}
                                />
                            </div>
                        )}
                    </div>
                ))}
        </section>
    </>
    );
}

const CreateElements = ({ element }) => {

    const [technology, setTechnology] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                if(element === 'project'){
                    const callToAPI = await Elements.obtain('skills');
                    setTechnology(callToAPI);
                }
            } catch (error) {
            }
        };

        fetchData();
        }, [element]);

    const [activeSection, setActiveSection] = useState("category");
    const [isOpenPreview, setIsOpenPreview] = useState(false);

    const [name, setName] = useState('');
    const [date, setDate] = useState('');
    const [briefDescription, setBriefDescription] = useState('');
    const [urlRepository, setUrlRepository] = useState('');
    const [title, setTitle] = useState('');
    const [content, setContent] = useState('');
    const [authors, setAuthors] = useState('');
    const [imageCredits, setImageCredits] = useState('');
    const [selectedImages, setSelectedImages] = useState([]);
    const [selectedMiniature, setSelectedMiniature] = useState([]);
    const [categories, setCategories] = useState([]);
    const [subcategories, setSubcategories] = useState([]);
    const [tags, setTags] = useState([]);
    const [technologies, setTechnologies] = useState([]);
    const [elementsPreview, setElementsPreview] = useState([]);

    const handleSubmit = (e) => {
        e.preventDefault(); // Evita la recarga de la página
    }

    const handleCreate = async () => {
        const encryptedToken = Cookies.get('token');
        const decryptedToken = dataDescrypt(encryptedToken);
        let data = null;
        if(element == 'skills'){
            const formattedDate = moment(date).format('YYYY-MM-DD');
            data = { 
                ...(name && {name: name}), 
                ...(selectedImages[0]?.id ? { id_image: selectedImages[0].id } : selectedImages[0] ? { image: selectedImages[0] } : {}),
                ...(formattedDate && {date: formattedDate}), 
                ...(categories.length != 0 && {categories: categories}), 
                ...(subcategories.length != 0 && {subcategories: subcategories}), 
                ...(tags.length != 0 && {tags: tags})
            };
        } else if (element == 'project'){
            data = { 
                ...(name && {name: name}), 
                ...(briefDescription && {brief_description: briefDescription}),
                ...(urlRepository && {url_repository: urlRepository}),
                ...(selectedMiniature[0]?.id ? { id_miniature: selectedMiniature[0].id } : selectedMiniature[0] ? { miniature: selectedMiniature[0] } : {}),
                ...(selectedImages[0]?.id ? { ids_images: selectedImages.map(image => image.id) } : selectedImages ? { 'images[]': selectedImages } : {}), 
                ...(categories.length != 0 && {categories: categories}), 
                ...(subcategories.length != 0 && {subcategories: subcategories}), 
                ...(technologies.length != 0 && {technologies: technologies.map(tecnhology => tecnhology.id)}),
                ...(tags.length != 0 && {tags: tags}),
            };
        } else if (element == 'blogpost'){
            data = { 
                ...(title && {title: title}),
                ...(content && {content: content}),
                ...(authors && {authors: authors}),
                ...(imageCredits && {image_credits: imageCredits}),
                ...(selectedImages[0]?.id ? { id_image: selectedImages[0].id } : selectedImages[0] ? { image: selectedImages[0] } : {}),
                ...(categories.length != 0 && {categories: categories}), 
                ...(subcategories.length != 0 && {subcategories: subcategories}), 
                ...(technologies.length != 0 && {technologies: technologies}),
            };
        }
        console.log(data);
        if(Object.keys(data).length != 0){
            try{
                const create = await Elements.createElement(decryptedToken, element, data);
                toast.success(create.message);
            } catch (error) {
                toast.error(error.message);
            }
        }
    }

    const handlePreview = () => {
        setElementsPreview({name:name, title:title, images:selectedImages, miniature:selectedMiniature, briefDescription:briefDescription, content:content, technologies:technologies, authors:authors, imageCredits:imageCredits})
        setIsOpenPreview(true);
    }

    return(
        <>
            {isOpenPreview && <Modal setIsOpen={setIsOpenPreview} title='Previsualización' component={ElementPreview} element={element} elementsPreview={elementsPreview} />}
            <Toaster richColors position="top-center" />
            <h1>Crear {element}</h1>
            <form onSubmit={handleSubmit} className="space-y-4">
                {element === 'skills' ? (
                    <>
                        <InputComponent 
                            title={'Nombre'} 
                            id={'name'} 
                            setElement={setName} 
                            placeholder={'HTML5'}
                            required={true}
                        />
                        <InputComponent 
                            type={'date'} 
                            title={'Fecha de obtención de la Skill'} 
                            id={'date'} 
                            setElement={setDate}
                            required={true}

                        />
                    </>
                ): element === 'project' ? (
                    <>
                        <InputComponent 
                            title={'Nombre'} 
                            id={'name'} 
                            setElement={setName} 
                            placeholder={'Proyecto veterinaria'}
                            required={true}
                        />
                        <InputComponent 
                            title={'Breve descripción'} 
                            TypeInput={'textarea'} 
                            id={'brief_description'} 
                            setElement={setBriefDescription} 
                            placeholder={'Aquí va una breve descripción del proyecto'}
                            required={true}
                        />
                        <InputComponent 
                            title={'Url repositorio GitHub'} 
                            id={'url_repository'} 
                            setElement={setUrlRepository} 
                            defaulValue={element.url_repository} 
                            placeholder={'https://github.com/LuiferEduardoo/Sitio-web-personal'}
                            required={true}
                        />
                        <ImagesComponent 
                            setSelectedFile={setSelectedMiniature} 
                            selectedFile={selectedMiniature} 
                            tipeFile={'Miniature'}
                        />
                    </>
                ) : (
                    <>
                        <InputComponent 
                            title={'Titulo'} 
                            id={'title'} 
                            setElement={setTitle} 
                            placeholder={'Mi primer "¡Hola mundo!"'}
                            required={true}
                        />
                        <InputComponent 
                            title={'Contenido'} 
                            id={'content'} 
                            TypeInput={'textarea'} 
                            setElement={setContent} 
                            placeholder={'Aquí va el contenido del blog'}
                            required={true}
                        />
                        <InputComponent 
                            title={'Autores'} 
                            id={'authors'} 
                            setElement={setAuthors} 
                            placeholder={'David,Miguel'}
                        />
                        <InputComponent 
                            title={'Creditos de la imagen'} 
                            id={'image_credits'} 
                            setElement={setImageCredits} 
                            placeholder={'Linux en Español'}
                        />
                    </>
                )}
                <ImagesComponent 
                    setSelectedFile={setSelectedImages}
                    selectedFile={selectedImages}
                    tipeFile={'Imagen'}
                />
                <Classification
                    {...(element === 'project'
                        && {
                            haveTechnology: true,
                            technology,
                            setTechnologies,
                            technologies,
                        }
                        )}
                    element={element}
                    activeSection={activeSection}
                    setActiveSection={setActiveSection}
                    categories={categories}
                    setCategories={setCategories}
                    subcategories={subcategories}
                    setSubcategories={setSubcategories}
                    tags={tags}
                    setTags={setTags}
                    />
                <div>
                    <button  
                        onClick={() => {
                            handleCreate();
                        }}>
                        Crear
                    </button>

                    <button  
                        onClick={() => {
                            handlePreview();
                        }}>
                        Previsualizar
                    </button>
                </div>
            </form>
        </>
    )
}
const ViewSection = ({ element, title }) =>{
    return(
        <>
            <h1 className="text-2xl font-bold">{title}</h1>
            <ViewElements elementObtain={element} />
        </>
    )
}

const Skills =({ selectedSection }) =>{
    return(
        <>
            { selectedSection === 'View' && <ViewSection element="skills" title="Skills"/>}
            { selectedSection === 'Create' && <CreateElements element='skills'/>}
        </>
    );
}

const Projects =({ selectedSection }) =>{
    return(
        <>
            { selectedSection === 'View' && <ViewSection element="projects" title="Proyectos"/>}
            { selectedSection === 'Create' && <CreateElements element='project'/>}
        </>
    );
}

const Blog =({ selectedSection }) =>{
    return(
        <>
            { selectedSection === 'View' && <ViewSection element="blogposts" title="Blog Posts"/>}
            { selectedSection === 'Create' && <CreateElements element='blogpost'/>}
        </>
    );
}
const Content = () =>{
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
                <Routes>
                    <Route path="/skills" element={<Skills selectedSection={selectedSection}/>}/>
                    <Route path="/projects" element={<Projects selectedSection={selectedSection}/>}/>
                    <Route path="/blog" element={<Blog selectedSection={selectedSection}/>}/>
                </Routes>
            </div>
        </>
    )
}

export default Content;