import React from 'react';
import styles from '../assets/styles/footer.module.css';

function Footer() {
    return (
        <footer className={styles.footer}>
            <div className={styles.footerConteiner}>
                <section className={styles.footerConteinerInforMobile}>
                    <div className={styles.footerConteinerInforLogo}>
                        <img src="https://cdn.luifereduardoo.com/img/logo/logo-white.svg" alt="Logo" />
                    </div>
                    <div className={styles.footerConteinerInforSocialMedia}>
                        <a href="https://www.twitter.com/luifereduardoo" target="_blank"><i className="lab la-twitter"></i></a>
                        <a href="https://www.instagram.com/luifereduardoo" target="_blank"><i className="lab la-instagram"></i></a>
                        <a href="https://www.facebook.com/luifereduardoo" target="_blank"><i className="lab la-facebook"></i></a>
                        <a href="https://www.linkedin.com/in/luifereduardoo/" target="_blank"><i className="lab la-linkedin-in"></i></a>
                        <a href="https://github.com/LuiferEduardoo" target="_blank"><i className="lab la-github"></i></a>
                    </div>
                </section>
                <section className={styles.footerConteinerContact}>
                    <p>CORREO</p>
                    <div className={styles.footerContainerContactEmail}>
                        <i className="las la-envelope-open-text"></i>
                        <a href="mailto:contacto@luifereduardoo.com">contacto@luifereduardoo.com</a>
                    </div>
                </section>
                <section className={styles.footerConteinerInfor}>
                    <div className={styles.footerConteinerInforLogo}>
                        <img src="https://cdn.luifereduardoo.com/img/logo/logo-white.svg" alt="Logo" />
                    </div>
                    <div className={styles.footerConteinerInforSocialMedia}>
                        <a href="https://www.twitter.com/luifereduardoo" target="_blank"><i className="lab la-twitter"></i></a>
                        <a href="https://www.instagram.com/luifereduardoo" target="_blank"><i className="lab la-instagram"></i></a>
                        <a href="https://www.facebook.com/luifereduardoo" target="_blank"><i className="lab la-facebook"></i></a>
                        <a href="https://www.linkedin.com/in/luifereduardoo/" target="_blank"><i className="lab la-linkedin-in"></i></a>
                        <a href="https://github.com/LuiferEduardoo" target="_blank"><i className="lab la-github"></i></a>
                    </div>
                </section>
                <section className={styles.footerConteinerLink}>
                    <p>ENLACES</p>
                    <ul>
                        <li>
                        <a href="/">Home</a>
                        </li>
                        <li>
                        <a href="/portfolio">Portafolio</a>
                        </li>
                        <li>
                        <a href="/blog">Blog</a>
                        </li>
                        <li>
                        <a href="/contact">Contacto</a>
                        </li>
                    </ul>
                </section>
            </div>
            <div className={styles.footerCopyright}>
                <p>Luifer Eduardo Ortega ©2023</p>
            </div>
        </footer>
        );
}

export {Footer};