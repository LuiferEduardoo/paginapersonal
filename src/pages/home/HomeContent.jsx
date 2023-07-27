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
                        <p>¡Hola! Mi nombre es Luifer Eduardo Ortega Pérez, y a mis 17 años, me apasiona profundamente la tecnología. Desde que era pequeño, he sentido una curiosidad innata por entender cómo funcionan las cosas y una habilidad autodidacta que me ha permitido aprender de forma independiente.</p>
                        <p>A la temprana edad de 13 años, di mis primeros pasos en la programación con una sencilla impresión de "Hola mundo". Fue ese pequeño logro el que despertó en mí una pasión desbordante por la informática y la programación.</p>
                        <p>En el ámbito del desarrollo web, me siento cómodo trabajando con distintos lenguajes como JavaScript, Python y PHP. Mis conocimientos me permiten crear páginas interactivas y dinámicas, lo que me fascina, ya que puedo dar vida a mis ideas y proyectos.</p>
                        <p>Además de mi amor por la tecnología, tengo una profunda pasión por la escritura. Es por eso que cuento con una sección de Blog, donde puedo compartir mis pensamientos, conocimientos y experiencias con el mundo. Creo firmemente que la combinación de la tecnología y la escritura me da una ventaja única para expresar mis ideas y aportar valor a la comunidad.</p>
                        <p>Un lema que siempre me ha inspirado es: "No hay nada imposible, solo cosas difíciles". Con esta mentalidad, enfrento nuevos desafíos en el mundo de la tecnología y la programación, siempre dispuesto a aprender y mejorar.</p>
                        <p>En resumen, soy un apasionado de la tecnología, con habilidades en el desarrollo frontend utilizando ReactJS y en el manejo de bases de datos como SQL. En el backend, mi experiencia se basa en el uso de Node.js, lo que me permite crear aplicaciones web completas y robustas.</p>
                        <p>Estoy emocionado por seguir creciendo en este apasionante mundo, enfrentando nuevos retos y contribuyendo al avance de la tecnología. Siempre dispuesto a aprender y compartir mis conocimientos, estoy seguro de que puedo aportar mucho valor a cualquier proyecto en el que participe. ¡El futuro es emocionante y estoy listo para aprovechar al máximo cada oportunidad que se presente!</p>
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
