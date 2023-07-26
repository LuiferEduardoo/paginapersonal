import React, {useEffect} from 'react';
import { Helmet } from "react-helmet";
import {HomeContent} from './HomeContent';

const Home = () => {
    useEffect(() => {
        window.scrollTo(0, 0);
    });
    return (
        <>
            <Helmet>
                <title>Luifer Ortega</title>
                <meta name="description" content="¡Bienvenido a mi sitio web! Soy Luifer, un apasionado por la tecnología, la programación y todo lo relacionado con el mundo digital. Aquí encontrarás información sobre mi experiencia en el campo de la tecnología, así como consejos, trucos y tutoriales relacionados con la programación y el internet. Explora mi sitio para descubrir más acerca de mis proyectos y cómo puedo ayudarte a alcanzar tus metas en el mundo digital."/>
                <meta property="og:image" content="https://ejemplo.com/mi-imagen.jpg"/>
            </Helmet>
            <HomeContent />
        </>
    );
}

export default Home;
