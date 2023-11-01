import React, { useState, useEffect } from "react";
import { Toaster, toast } from 'sonner';
import moment from 'moment';
import Cookies from 'js-cookie';
import { Modal, ElementsEdit, ElementsDelete, ElementPreview } from "./Modal";
import {dataDescrypt} from '../../utils/data-descrypt';
import Elements from '../../services/Elements';
import { InputComponent } from './InputComponent';
import { Classification } from './Classification';
import { ImagesComponent } from './ImagesComponent';
import LoadingSpinner from '../../components/LoadingSpinner';

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
    const [isLoading, setIsLoading] = useState(false);

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
        setIsLoading(true);
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
                ...(selectedImages[0]?.id ? { ids_images: selectedImages.map(image => image.id) } : selectedImages[0] ? { 'images': selectedImages } : {}), 
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
                ...(tags.length != 0 && {tags: tags}),
            };
        }
        if(Object.keys(data).length != 0){
            try{
                const create = await Elements.createElement(decryptedToken, element, data);
                setName('');
                setDate('');
                setBriefDescription('');
                setUrlRepository('');
                setTitle('');
                setContent('');
                setAuthors('');
                setImageCredits('');
                setSelectedImages([]);
                setSelectedMiniature([]);
                setCategories([]);
                setSubcategories([]);
                setTags([]);
                setTechnologies([]);
                setElementsPreview([]);
                toast.success(create.message);
            } catch (error) {
                toast.error(error.message);
            }
        }
        setIsLoading(false)
    }

    const handlePreview = () => {
        setElementsPreview({name:name, title:title, images:selectedImages, miniature:selectedMiniature, briefDescription:briefDescription, content:content, technologies:technologies, authors:authors, imageCredits:imageCredits})
        setIsOpenPreview(true);
    }

    return(
        <>
            {isOpenPreview && <Modal setIsOpen={setIsOpenPreview} title='Previsualización' component={ElementPreview} element={element} elementsPreview={elementsPreview} />}
            <h1>Crear {element}</h1>
            <form onSubmit={handleSubmit} className="space-y-4">
                {element === 'skills' ? (
                    <>
                        <InputComponent 
                            title={'Nombre'}
                            element={name} 
                            id={'name'} 
                            setElement={setName} 
                            placeholder={'HTML5'}
                            required={true}
                        />
                        <InputComponent 
                            type={'date'} 
                            element={date}
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
                            element={name} 
                            placeholder={'Proyecto veterinaria'}
                            required={true}
                        />
                        <InputComponent 
                            title={'Breve descripción'} 
                            element={briefDescription}
                            TypeInput={'textarea'} 
                            id={'brief_description'} 
                            setElement={setBriefDescription} 
                            placeholder={'Aquí va una breve descripción del proyecto'}
                            required={true}
                        />
                        <InputComponent 
                            title={'Url repositorio GitHub'}
                            element={urlRepository} 
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
                            element={title}
                            setElement={setTitle} 
                            placeholder={'Mi primer "¡Hola mundo!"'}
                            required={true}
                        />
                        <InputComponent 
                            title={'Contenido'} 
                            id={'content'} 
                            element={content}
                            TypeInput={'textarea'} 
                            setElement={setContent} 
                            placeholder={'Aquí va el contenido del blog'}
                            required={true}
                        />
                        <InputComponent
                            title={'Autores'} 
                            element={authors}
                            id={'authors'} 
                            setElement={setAuthors} 
                            placeholder={'David,Miguel'}
                        />
                        <InputComponent 
                            title={'Creditos de la imagen'} 
                            id={'image_credits'} 
                            element={imageCredits}
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
                    <div className="flex items-center space-x-4">
                        <button
                            onClick={() => {
                                handleCreate();
                            }}
                            disabled={isLoading}
                            className={`${
                                isLoading ? 'py-2' : 'py-2.5'
                              } bg-blue-500 hover:bg-blue-600 text-white px-4 rounded-md focus:outline-none border-none`}
                            >
                            {isLoading ? (
                                <>
                                    <span className="flex items-center">
                                        <LoadingSpinner size={20} color="#fff" className="mr-2" />
                                        <span className="ml-2">Processing...</span>
                                    </span>
                                </>
                            ) : (
                                <>
                                    Crear
                                </>
                            )}
                        </button>

                        <button
                            onClick={() => {
                            handlePreview();
                            }}
                            className="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2.5 rounded-md focus:outline-none border-none"
                        >
                            Previsualizar
                        </button>
                    </div>
            </form>
        </>
    )
}
export default CreateElements;