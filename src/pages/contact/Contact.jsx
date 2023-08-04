import React, {useEffect} from 'react';
import { Helmet } from "react-helmet";
import {ContactContent} from './ContactContent';
import SkeletonComponent from '../../components/SkeletonComponent';

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
        <ContactContent />
    </>
    );
}

export default Contact;