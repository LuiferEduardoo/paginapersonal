import React, {useState} from 'react';
import style from '../assets/styles/nav.module.css'
import { Link } from 'react-router-dom';
import { Bars3Icon, XMarkIcon  } from "@heroicons/react/24/outline";


function Navbar() {
    const [menu, setMenu] = useState(false); 
    const toggleMenu = () =>{
        setMenu(!menu)
    }
    return (
        <nav className={style.menu}>
            <div className={style.menuMobileIcon}>
                {
                    menu ? (<XMarkIcon className="h-6 w-6 text-gray-500" onClick={toggleMenu}/>) : (<Bars3Icon className={'h-6 w-6 text-gray-500'} onClick={toggleMenu} />)
                }
            </div>
            <Link  to="/" className={style.menuLogo}>
                <img src="https://cdn.luifereduardoo.com/img/logo/logo-black.svg" className={style.logo} alt="Logo" />
            </Link>
            <ul className={`${style.menuDesktop} ${menu ? style.isActive : ''}`}>
            <li>
                <Link to="/" onClick={toggleMenu}>Home</Link>
            </li>
            <li>
                <Link to="/portfolio" onClick={toggleMenu}>Portafolio</Link>
            </li>
            <li>
                <Link to="/blog" onClick={toggleMenu}>Blog</Link>
            </li>
            <li>
                <Link to="/contact" onClick={toggleMenu}>Contacto</Link>
            </li>
            </ul>
        </nav>
    );
    }

export {Navbar};