import React, { useState, useEffect, useRef } from "react";
import Elements from '../../services/Elements';
import styles from '../../assets/styles/home.module.css';
import { LazyLoadImage } from 'react-lazy-load-image-component';
import {BannerHome} from './BannerHome';
import SkeletonSkills from './SkeletonSkills'

const HomeContent = () => {
    const myComponent = useRef(null)
    const [skills, setSkills] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const callToAPI = await Elements.obtain('skills');
                setSkills(callToAPI);
                setIsLoading(false);
            } catch (error) {
                setError(error.message);
                setIsLoading(false);
            }
        };

        fetchData();
        }, []);
        const handleButtonBanner = () => {
            myComponent.current.scrollIntoView({
                behavior: 'smooth', // Hace que el desplazamiento sea suave
                block: 'start', // Desplazarse hasta la parte superior del componente
              });
          };
    return (
        <>
            <BannerHome handleButtonBanner={handleButtonBanner}/>
            <section className={styles.mainContainer}>
                <div className={styles.mainContainerAbout} ref={myComponent}>
                    <section className={styles.mainContainerAboutText}>
                        <h1>Sobre mí</h1>
                        <p>Mi nombre es Luifer Eduardo Ortega Pérez, tengo 17 años y desde pequeño mi gran pasión es la tecnología.</p>
                        <p>A los 13 años aprendí a programar imprimiendo mi primer "Hola mundo" gracias a mi curiosidad y mi capacidad autodidacta.</p>
                        <p>Aunque sé programar en lenguajes como: JavaScript, Python y PHP.</p>
                        Actualmente, me encuentro ampliando mis conocimientos en estas áreas. Aparte de la gran pasión que le tengo a la tecnología, también tengo un enorme amor a la escritura, por eso es que cuentan con la sección de <a href="/blog">Blog</a> donde podrán ver mis redacciones.
                        <p>¡No hay nada imposible, solo cosas difíciles!</p>
                    </section>
                        <section className={styles.mainContainerAboutImg}>
                            <LazyLoadImage src="https://cdn.luifereduardoo.com/img/about/img-1.webp" alt="Img-about-me" />
                        </section>
                </div>
                <section className={styles.mainContainerSkills}>
                    {isLoading ? (
                        <SkeletonSkills/>
                        ) : 
                        <>
                            <h1>My skills</h1>
                            {skills.map((skill) => (
                                <div key={skill.id} className={styles.mainContainerSkillsInformation}>
                                    <LazyLoadImage 
                                        src={skill.image[0].url} 
                                        alt={skill.name} 
                                    />
                                    <h2>{skill.name}</h2>
                                </div>
                            ))}
                        </>
                    }
                </section>
            </section>
        </>
);
}

export {HomeContent};
