import React, { useRef }from 'react'
import { Helmet } from "react-helmet";
import {Project} from './Project'
import {BannerPortfolio} from './BannerPortfolio';
import {Navbar} from '../../components/Navbar';
import {Footer} from '../../components/Footer';
import '../../assets/styles/portafolio.css';

const PortfolioContent = ({projects}) => {
    const componenteProject = useRef(null);
    const handleButtonBanner = () => {
        componenteProject.current.scrollIntoView({
            behavior: 'smooth', // Hace que el desplazamiento sea suave
            block: 'start', // Desplazarse hasta la parte superior del componente
        });
    }
    return(
        <>
            <Helmet>
                <title>Portafolio</title>
                <meta name="description" content="Explora mi portafolio como desarrollador de software y descubre una colección de proyectos innovadores y soluciones tecnológicas. Encontrarás ejemplos de mi habilidad para crear software de calidad. ¡Conoce mis proyectos y experiencia en el desarrollo de soluciones digitales a medida!"/>
                <meta property="og:image" content="https://cdn.luifereduardoo.com/img/banner/portfolio/coding-924920_1280.webp"/>
            </Helmet>
            <BannerPortfolio handleButtonBanner={handleButtonBanner}/>
            <section className="main-container">
                <section ref={componenteProject} className="main-container-proyects">
                {projects.map(elements =>
                    <Project key ={elements.id} link={elements.link} miniature ={elements.miniature} name={elements.name} brief_description={elements.brief_description} category={elements.categories}/>
                )}
                </section>
            </section>
        </>
    );   
}
export {PortfolioContent}