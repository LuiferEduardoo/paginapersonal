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