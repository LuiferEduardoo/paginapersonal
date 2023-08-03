import React, {useEffect} from 'react';
import { Helmet } from "react-helmet";
import { useParams, Link} from 'react-router-dom';
import { ArrowLeftIcon, ArrowTopRightOnSquareIcon } from "@heroicons/react/24/outline";
import styles from '../../assets/styles/proyectInformation.module.css';
import {Banner} from '../../components/Banner';
import NotFound from "../not_found/NotFound";

const ProjectInformation = ({projects}) => {
    const { link } = useParams();
    const item = projects.find((item) => item.link == link);
    const itemHistory = item && item.history[0];
    useEffect(() => {
        window.scrollTo(0, 0);
    });
    return (
        <>
            {item ? 
                <>
                    <Helmet>
                        <title>{item.name}</title>
                        <meta name="description" content={item.brief_description} />
                        <meta 
                            property="og:image" 
                            content={item.image[0].url}
                        />
                    </Helmet>
                    <Banner img ={item.image[0].url} altImage={item.image[0].name}>
                        <div className ={styles.headerCapa}></div>
                        <section className={styles.headerContainerDescription}>
                            <span className={styles.title}>{item.name}</span>
                                <span className={`${styles.status} ${itemHistory.url_proyect ? styles.statusOnline : styles.statusOffline}`}>
                                    <div className={styles.iconContainer}>
                                        <span className={`${styles.icon} ${itemHistory.url_proyect ? styles.iconBackgroundOnline: styles.iconBackgroundOffline}`}></span>
                                            <i className="fas fa-check"></i>
                                    </div>{itemHistory.url_proyect ? <p>En linea</p> : <p>Fuera de linea</p>}
                                </span>
                                {itemHistory.url_proyect ?
                                <div className={styles.link}>
                                    <a href={itemHistory.url_repository} target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path className={styles.iconGithub}d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"/></svg>
                                    </a>
                                    <a href={itemHistory.url_proyect} target="_blank">
                                        <ArrowTopRightOnSquareIcon className="h-9 w-9 text-gray-500 ml-2" />
                                    </a>
                                </div>
                                :
                            <div className={styles.link}>
                                <a href={itemHistory.url_repository} target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path className={styles.iconGithub}d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"/></svg>
                                </a>
                            </div> }
                            <div className={styles.version}>
                                <span>{itemHistory.version}</span>
                            </div>
                        </section>
                    </Banner>
                    <section className={styles.mainContainerProyectsInformation}>
                        <section className ={styles.mainContainerProyectsInformationOne}>
                            <div className={styles.description}>
                                <h2>Descripción:</h2>
                                <span dangerouslySetInnerHTML={{ __html: itemHistory.description }}/>
                            </div>
                            <div className={styles.information}>
                                <span>Categoria: <p>{item.categories[0].name}</p></span>
                                <span>Ultima actualización: <p>{itemHistory.updated}</p></span>
                                <span>Ultimo push: <p>{itemHistory.pushed_at}</p></span>
                            </div>
                        </section>
                        <section className ={styles.mainContainerProyectsInformationTwo}>
                            <div className={styles.subcategories}>
                                <h2>Subcategorias</h2>
                                {item.subcategories.map(element => <span key={element.id}>{element.name}</span>)}
                            </div>
                            <div className={styles.tecnology}>
                                <h2>Tecnologias</h2>
                                <div className={styles.tecnologyItem}>
                                    {item.technology.map( element => <span key={element.id}>{element.name}</span>)}
                                </div>
                            </div>
                        </section>
                        <section className={styles.mainContainerProyectsInformationThree}>
                            {itemHistory.contributors != "[]" ? 
                            <div className={styles.contributors}>
                                <p>Contribuidores:</p>
                                {JSON.parse(itemHistory.contributors).map(elements => <span key={elements}>{elements}</span>)}
                            </div> :null}
                            {itemHistory.documentation?
                                <div className={styles.documentation}>
                                    <p>Documentación:</p>
                                    <a href={itemHistory.documentation}><i className="las la-file-download"></i></a>
                                </div>:null
                            }
                        </section>
                    </section>
                </> : 
                <>
                    <NotFound />
                </>}
            
        </>
    );
}

export  {ProjectInformation};
