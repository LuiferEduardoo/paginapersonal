import React from 'react';
import { Helmet } from "react-helmet";
import styles from '../../assets/styles/notFound.module.css';

const NotFound = () => {
    return (
        <>
            <Helmet>
                <title>Página no encontrada</title>
            </Helmet>
            <div className={styles.main}>
                <img src="https://cdn.luifereduardoo.com/img/error-page/error-404-page.webp" alt='404' />
                <h1>Página no encontrada</h1>
                <a href="/">Ir a Home</a>
            </div>
        </>
    );
};  
export default NotFound;