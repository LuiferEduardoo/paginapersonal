import React, { useState } from 'react';
import style from '../assets/styles/nav.module.css';
import { Link, useLocation } from 'react-router-dom';
import { ArrowLeftIcon } from '@heroicons/react/24/outline';
import { Bars3Icon, XMarkIcon } from '@heroicons/react/24/outline';

function Navbar() {
  const [menu, setMenu] = useState(false);
  const toggleMenu = () => {
    setMenu(!menu);
  };

  // Obtener el objeto de ubicación actual
  const location = useLocation();

  return (
    <nav className={/^\/portfolio\/.+$/.test(location.pathname) ? style.menuProyect : style.menu}>
      {/^\/portfolio\/.+$/.test(location.pathname) ? ( // Utilizamos una expresión regular para verificar la ruta
        <>
          <Link to="/portfolio">
            <ArrowLeftIcon className="h-6 w-6 text-gray-500" />
          </Link>
          <img src="https://cdn.luifereduardoo.com/img/logo/logo-black.svg" />
        </>
      ) : (
        <>
          <div className={style.menuMobileIcon}>
            {menu ? (
              <XMarkIcon className="h-6 w-6 text-gray-500" onClick={toggleMenu} />
            ) : (
              <Bars3Icon className={'h-6 w-6 text-gray-500'} onClick={toggleMenu} />
            )}
          </div>
          <Link to="/" className={style.menuLogo}>
            <img src="https://cdn.luifereduardoo.com/img/logo/logo-black.svg" className={style.logo} alt="Logo" />
          </Link>
          <ul className={`${style.menuDesktop} ${menu ? style.isActive : ''}`}>
            <li>
              <Link to="/" onClick={toggleMenu}>
                Home
              </Link>
            </li>
            <li>
              <Link to="/portfolio" onClick={toggleMenu}>
                Portafolio
              </Link>
            </li>
            <li>
              <Link to="/blog" onClick={toggleMenu}>
                Blog
              </Link>
            </li>
            <li>
              <Link to="/contact" onClick={toggleMenu}>
                Contacto
              </Link>
            </li>
          </ul>
        </>
      )}
    </nav>
  );
}

export { Navbar };
