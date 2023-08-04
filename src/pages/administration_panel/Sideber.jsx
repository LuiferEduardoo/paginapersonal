import React from 'react';
import { Link} from 'react-router-dom';
import { WrenchScrewdriverIcon, PhotoIcon, WalletIcon } from "@heroicons/react/24/outline";
import styles from '../../assets/styles/sideber.module.css';

const Sideber = () => {
    return (
        <div className={styles.sideber}>
            <img src ="https://cdn.luifereduardoo.com/img/logo/logo-white.svg" alt="logo-white"/>
            <ul>
                <li>
                    <Link to="/administration-panel/content/skills" className="flex items-center space-x-2">
                        <WalletIcon className="h-6 w-6"/>
                        <span className="ml-2">Gestión de Contenido</span>
                    </Link>
                </li>
                <li>
                    <Link to="/administration-panel/images" className="flex items-center space-x-2">
                        <PhotoIcon className="h-6 w-6"/>
                        <span className="ml-2">Imagenes</span>
                    </Link>
                </li>
                <li>
                    <Link to="/administration-panel/settings" className="flex items-center space-x-2">
                        <WrenchScrewdriverIcon className="h-6 w-6"/>
                        <span className="ml-2">Configuración</span>
                    </Link>
                </li>
            </ul>
        </div>
    ); 
};

export default Sideber;