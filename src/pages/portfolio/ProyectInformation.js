import React, {useEffect} from 'react';
import { Helmet } from "react-helmet";
import { useParams, Link} from 'react-router-dom';
import {proyectApi} from '../../services/proyectApi';
import styles from '../../assets/styles/proyectInformation.module.css';
import {Banner} from '../../components/Banner';

function ProyectInformation() {
    const { link } = useParams();
    const item = proyectApi.find(item => item.link == link);
    useEffect(() => {
        window.scrollTo(0, 0);
    });
    return (
        <>
            <Helmet>
                <title>{item.title}</title>
                <meta name="description" content={item.brief_description} />
            </Helmet>
            <nav className={styles.nav}>
                <Link to="/portfolio"><i className="las la-arrow-left"></i></Link>
                <img src ="https://cdn.luifereduardoo.com/img/logo/logo-black.svg"/>
            </nav>
            <Banner img ={item.images[0]}>
                <div className ={styles.headerCapa}></div>
                <section className={styles.headerContainerDescription}>
                    <span className={styles.title}>{item.title}</span>
                        <span className={`${styles.status} ${item.url ? styles.statusOnline : styles.statusOffline}`}>
                            <div className={styles.iconContainer}>
                                <span className={`${styles.icon} ${item.url ? styles.iconBackgroundOnline: styles.iconBackgroundOffline}`}></span>
                                    <i className="fas fa-check"></i>
                            </div>{item.url ? <p>En linea</p> : <p>Fuera de linea</p>}
                        </span>
                        {item.url ?
                        <div className={styles.link}>
                            <a href={item.respository} target="_blank"><i className="lab la-github"></i></a>
                            <a href={item.url} target="_blank"><i className="las la-globe-americas"></i></a>
                        </div>
                        :
                    <div className={styles.link}>
                        <a href={item.respository} target="_blank"><i className="lab la-github"></i></a>
                    </div> }
                    <div className={styles.version}>
                        <span>{item.version}</span>
                    </div>
                </section>
            </Banner>
            <section className={styles.mainContainerProyectsInformation}>
                <section className ={styles.mainContainerProyectsInformationOne}>
                    <div className={styles.description}>
                        <h2>Descripción:</h2>
                        <span>{item.description}</span>
                    </div>
                    <div className={styles.information}>
                        <span>Categoria: <p>{item.category}</p></span>
                        <span>Ultima actualización: <p>{item.updated}</p></span>
                    </div>
                </section>
                <section className ={styles.mainContainerProyectsInformationTwo}>
                    <div className={styles.subcategories}>
                        <h2>Subcategorias</h2>
                        {item.subcategories.map( elements => <span key={elements}>{elements}</span>)}
                    </div>
                    <div className={styles.tecnology}>
                        <h2>Tecnologias</h2>
                        <div className={styles.tecnologyItem}>
                            {item.technologies.map( elements => <span key={elements}>{elements}</span>)}
                        </div>
                    </div>
                </section>
                <section className={styles.mainContainerProyectsInformationThree}>
                    {item.contributors? 
                    <div className={styles.contributors}>
                        <p>Contribuidores:</p>
                        {item.contributors.map(elements => <span key={elements}>{elements}</span>)}
                    </div> :null}
                    {item.documentation?
                        <div className={styles.documentation}>
                            <p>Documentación:</p>
                            <a href={item.documentation}><i className="las la-file-download"></i></a>
                        </div>:null
                    }
                </section>
            </section>
        </>
    );
}

export  {ProyectInformation};
