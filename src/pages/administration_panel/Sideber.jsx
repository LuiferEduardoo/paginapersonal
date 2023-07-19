import React from 'react';
import { Link } from 'react-router-dom';
import styles from '../../assets/styles/sideber.module.css';

const Sideber = () => {
    return (
        <div className={styles.sideber}>
            <img src ="https://cdn.luifereduardoo.com/img/logo/logo-white.svg" alt="logo-white"/>
            <ul>
                <li>
                    <Link to="/administration-panel">Dashboard</Link>
                </li>
                <li>
                    <Link to="/administration-panel/content/skills">Gestión de Contenido</Link>
                </li>
                <li>
                    <Link to="/administration-panel/images">Imagenes</Link>
                </li>
                <li>
                    <Link to="/administration-panel/settings">Configuración</Link>
                </li>
            </ul>
        </div>
    );
};

export default Sideber;