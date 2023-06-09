import React from 'react';
import {skills} from '../../services/skills';

import styles from '../../assets/styles/home.module.css';

function HomeContent() {
    return (
        <section className={styles.mainContainer}>
            <div className={styles.mainContainerAbout}>
                <section className={styles.mainContainerAboutText}>
                    <h1>Sobre mí</h1>
                    <p>Mi nombre es Luifer Eduardo Ortega Pérez, tengo 17 años y desde pequeño mi gran pasión es la tecnología.</p>
                    <p>A los 13 años aprendí a programar imprimiendo mi primer "Hola mundo" gracias a mi curiosidad y mi capacidad autodidacta.</p>
                    <p>Aunque sé programar en lenguajes como: JavaScript, Python y PHP.</p>
                    Actualmente, me encuentro ampliando mis conocimientos en estas áreas. Aparte de la gran pasión que le tengo a la tecnología, también tengo un enorme amor a la escritura, por eso es que cuentan con la sección de <a href="/blog">Blog</a> donde podrán ver mis redacciones.
                    <p>¡No hay nada imposible, solo cosas difíciles!</p>
                </section>
                    <section className={styles.mainContainerAboutImg}>
                        <img src="https://cdn.luifereduardoo.com/img/about/img-1.webp" alt="Img-about-me" />
                    </section>
            </div>
            <section className={styles.mainContainerSkills}>
                <h1>My skills</h1>
                {skills.map((skill) => (
                <div key={skill.Id} className={styles.mainContainerSkillsInformation}>
                    <img src={skill.Image} alt={skill.Name} />
                    <h2>{skill.Name}</h2>
                </div>
                ))}
            </section>
            <div className={styles.mainContainerSocial}>
                <h1>Mis redes sociales</h1>
                <section>
                    <h2>LinkedIn</h2>
                    <section className={styles.mainContainerSocialLinkedin}>
                        <div className={styles.mainContainerSocialLinkedinConten} id="content-linkedin">
                            <a href="https://www.twitter.com/LuiferEduardoo" target="_blank">
                                <div className={styles.informationProfile}>
                                    <img src="https://pbs.twimg.com/profile_images/1546224621728538625/MZKuzpVn_400x400.jpg" alt="Photo-profile" />
                                    <div>
                                        <h3 className={styles.linkedinUsername}>luifereduardoo</h3>
                                        <p>Cargo o puesto</p>
                                    </div>
                                </div>
                                <div className={styles.content}>
                                    <img src="https://images.pexels.com/photos/1287142/pexels-photo-1287142.jpeg?cs=srgb&dl=pexels-eberhard-grossgasteiger-1287142.jpg&fm=jpg" alt="Anatomia" />
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ultrices mi vel malesuada elementum. Morbi consectetur feugiat metus, in vestibulum diam euismod id. Fusce lacinia tincidunt sem, vel varius ex consectetur id. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec eget risus eget nibh mollis tincidunt in eu risus. Etiam viverra enim vitae maur is faucibus, id hendrerit velit aliquam. Sed luctus enim eu tellus sag</p>
                                </div>
                                <div className={styles.footerLinkedin}>
                                    <i className="las la-thumbs-up icon-like"></i>
                                    <p>24 likes</p>
                                    <span>Ver comentarios</span>
                                </div>
                                </a>
                        </div>
                    </section>
                        <h2>Instagram</h2>
                    <section className={styles.mainContainerSocialInstagram}>
                        <div className={styles.mainContainerSocialInstagramConten} id="content-instagram">
                            <a href="https://www.instagram/p/${post.node.shortcode}" target="_blank">
                                <div className="items">
                                    <div className={styles.itemsPorfile}>
                                        <div className={styles.itemsPorfilePhoto}>
                                            <img src="https://pbs.twimg.com/profile_images/1546224621728538625/MZKuzpVn_400x400.jpg" alt="photo-porfile"/>
                                        </div>
                                        <div className={styles.itemsPorfileInformation}>
                                            <p>luifereduardoo</p>
                                            <p>@luifereduardoo</p>
                                        </div>
                                    </div>
                                    <div className={styles.img}>
                                        <img src="https://cdn.pixabay.com/photo/2017/10/17/16/10/fantasy-2861107_1280.jpg" alt="Imagen"/>
                                    </div>
                                    <div className={styles.description}>
                                        <p>Estas fotografías tomadas por mí, expresa mi amor por los atardeceres #atardecer</p>
                                    </div>
                                    <div className={styles.footerInstagram}>
                                        <img src="https://cdn-icons-png.flaticon.com/512/1077/1077035.png" alt="Icono de like"/>
                                        <p>5 likes</p>
                                        <span>Ver comentarios</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </section>
                </section>
            </div>
        </section>
);
}

export {HomeContent};
