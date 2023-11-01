import React, { useState, useEffect } from "react";
import { PencilSquareIcon, TrashIcon  } from "@heroicons/react/24/outline";
import { Skeleton } from "@mui/material";
import TruncatedHTML from "../../services/TruncatedHTML";
import styles from '../../assets/styles/administrationPanel.module.css';
import { Modal, ElementsEdit, ElementsDelete, ElementPreview } from "./Modal";
import Elements from '../../services/Elements';


const ViewElements = ({ elementObtain }) => {
    const [shouldResetEffect, setShouldResetEffect] = useState(false);

    const [elements, setElements] = useState([]);
    const [technology, setTechnology] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);
    const [isOpenEdit, setIsOpenEdit] = useState(false);
    const [isOpenDelete, setIsOpenDelete] = useState(false);
    const [hoveredIndex, setHoveredIndex] = useState(null);
    const [valueElement, setValueElement] = useState(null);


    useEffect(() => {

        if (shouldResetEffect) {
            setShouldResetEffect(false); // Reseteamos la variable para que no se vuelva a ejecutar hasta que cambie de nuevo.
        }
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
        }, [elementObtain, shouldResetEffect]);

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

    if (error) {
        return <div>Error al obtener el/la {elementObtain}</div>;
    }

    return (
        <>
            {isOpenEdit && <Modal setIsOpen={setIsOpenEdit} title='Editar' component={ElementsEdit} element={valueElement} technology={technology} updateOrDelete={setShouldResetEffect}/>}
            {isOpenDelete && <Modal setIsOpen={setIsOpenDelete} title='Borrar' component={ElementsDelete} element={valueElement} updateOrDelete={setShouldResetEffect} />}
            <section className={`${styles.viewElements} ${elementObtain === 'blogposts' || elementObtain === 'projects' ? 'grid grid-cols-3 gap-20'  : 'grid grid-cols-5 gap-20' }`}>
                    {isLoading ? (
                        [1,2,3,4,5,6].map((item) => (
                            <Skeleton
                                key={item}
                                variant="rectangular" 
                                height={250}
                            />
                        ))

                    ) : elements.map((element, index) => (
                        <div
                        key={element.id}
                        className="bg-white p-4 rounded-lg shadow relative text-center"
                        onMouseEnter={() => setHoveredIndex(index)}
                        onMouseLeave={() => setHoveredIndex(null)}
                        >
                            <h1 className="text-xl font-bold">{element.name ? element.name : element.title ? element.title : null}</h1>
                            <div>
                                <img
                                    className="mt-2 rounded-lg h-auto w-full object-cover mx-auto"
                                    src={elementObtain === 'projects' ? element.miniature[0].url : element.image[0] ? element.image[0].url : null}
                                    alt={element.name ? element.name : null}
                                    loading="lazy"
                                />
                            </div>
                            {element.brief_description && (
                                <p className="mt-2">{element.brief_description}</p>
                            )}
                            {element.content && (
                                <TruncatedHTML content={element.content} maxLength={150} />
                            )}
                            {hoveredIndex === index && (
                                <div className="absolute top-2 right-2 z-0">
                                    <PencilSquareIcon
                                        className="h-6 w-6 text-gray-500 cursor-pointer"
                                        onClick={() => handleEditClick(element)}
                                    />
                                    <TrashIcon
                                        className="h-6 w-6 text-gray-500 cursor-pointer"
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

export default ViewElements;