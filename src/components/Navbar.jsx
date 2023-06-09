import React, {useState} from 'react';
import style from '../assets/styles/nav.module.css'
import { Link } from 'react-router-dom';

function Navbar() {
    const [menu, setMenu] = useState(false); 
    const toggleMenu = () =>{
        setMenu(!menu)
    }
    return (
        <nav className={style.menu}>
            <div className={style.menuMobileIcon}>
                <i className={`${menu ? 'las la-times':'las la-bars'}`} onClick={toggleMenu}></i>
            </div>
            <Link  to="/" className={style.menuLogo}>
                <img src="https://cdn.luifereduardoo.com/img/logo/logo-black.svg" className={style.logo} alt="Logo" />
            </Link>
            <ul className={`${style.menuDesktop} ${menu ? style.isActive : ''}`}>
            <li>
                <Link to="/">Home</Link>
            </li>
            <li>
                <Link to="/portfolio">Portafolio</Link>
            </li>
            <li>
                <Link to="/blog">Blog</Link>
            </li>
            <li>
                <Link to="/contact">Contacto</Link>
            </li>
            </ul>
        </nav>
    );
    }

export {Navbar};