import React, {useEffect} from 'react';
import { Helmet } from "react-helmet";
import {Navbar} from '../../components/Navbar';
import {Footer} from '../../components/Footer';
import {ContactContent} from './ContactContent';

function Contact() {
    useEffect(() => {
        window.scrollTo(0, 0);
    });
    return (
    <>
        <Helmet>
            <title>Contacto</title>
            <meta name="description" content="Contactate conmigo por medio del correo electronico contacto@luifereduardoo.com" />
        </Helmet>
        <Navbar />
        <ContactContent />
        <Footer />
    </>
    );
}

export {Contact};