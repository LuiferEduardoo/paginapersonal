import React, { useState, useEffect } from 'react';
import { Link, useLocation } from 'react-router-dom';
import styles from '../../assets/styles/navAdmistrationPanel.module.css';
import AuthService from '../../services/AuthService';
import Cookies from 'js-cookie';

const Nav = ({userInfo, token}) => {
    const [isMenuOpen, setMenuOpen] = useState(false);

    const handleMenuToggle = () => {
        setMenuOpen(!isMenuOpen);
    };

    const handleLogout = async() => {
        try {
            const logout = await AuthService.logout(token);
            Cookies.remove('token');
            window.location.href = '/login';
        } catch (error) {
            toast.error(error.message);
        }
    };

    const location = useLocation();
    const isContentPath =
        location.pathname === '/administration-panel/content/skills' ||
        location.pathname === '/administration-panel/content/projects' ||
        location.pathname === '/administration-panel/content/blog';
    const isImagesPath = location.pathname === '/administration-panel/images';
    const isSettingsPath = location.pathname === '/administration-panel/settings';

    let pageTitle = '';
    if (isContentPath) {
        pageTitle = 'Gestión de Contenido';
    } else if (isImagesPath) {
        pageTitle = 'Imágenes';
    } else if (isSettingsPath) {
        pageTitle = 'Configuración';
    }

    return (
        <nav className={styles.menu}>
            <h2 className={`${styles.pageTitle} text-2xl font-bold`}>{pageTitle}</h2>
            <div className={styles.container}>
            {isContentPath && (
                <ul className={styles.menuSections}>
                    <li>
                        <Link to="/administration-panel/content/skills">Habilidades</Link>
                    </li>
                    <li>
                        <Link to="/administration-panel/content/projects">Proyectos</Link>
                    </li>
                    <li>
                        <Link to="/administration-panel/content/blog">Blog</Link>
                    </li>
                </ul>
            )}

            <div className={styles.profileIcon} onClick={handleMenuToggle}>
                    <img src={userInfo[0].profile[0].url} alt="profile"/>
                </div>
                {isMenuOpen && (
                    <div className={`${styles.dropdownMenu} bg-white rounded shadow-md absolute right-0 mt-2`}>
                        <ul className="py-2">
                            <li>
                                <Link to="/administration-panel/settings" className="block px-4 py-2 text-gray-800 hover:bg-gray-200">Configuración</Link>
                            </li>
                            <li>
                                <span onClick={handleLogout} className="block px-4 py-2 text-gray-800 hover:bg-gray-200 cursor-pointer">Cerrar sesión</span>
                            </li>
                        </ul>
                    </div>
                )}
            </div>
        </nav>
    );
};

export default Nav;

