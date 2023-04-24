import React, {useEffect} from 'react';
import { Helmet } from "react-helmet";
import {Navbar} from '../../components/Navbar';
import {Footer} from '../../components/Footer';
import {blogApi} from '../../services/blogApi';
import styles from '../../assets/styles/postblog.module.css';
import { useParams, Link} from 'react-router-dom';


function PostBlog() {
    const { link } = useParams();
    const item = blogApi.find(item => item.link == link);
    useEffect(() => {
        window.scrollTo(0, 0);
    });
    return (
        <>
            <Helmet>
                <title>{item.title}</title>
                <meta name="description" content="Descubre escritos facinantes en mi blog" />
            </Helmet>
            <Navbar />
            <main className ={styles.main}>
                <article>
                    <div className={styles.postHeader}>
                        <div className={styles.postHeaderWrap}>
                            <section className={styles.postHeaderContent}>
                                <h1>{item.title}</h1>
                                <div className={styles.postHeaderContentMeta}>
                                    <div className={styles.postHeaderContentAuthor}>
                                        <div className={styles.postHeaderContentAuthorImage}>
                                            <img src="cdn.luifereduardoo.com/img/about/profile/mzkuzpvn-400x400.webp"/>
                                        </div>
                                        <div className={styles.postHeaderContentAuthorInformation}>
                                            <span className={styles.author}>Luifer Ortega</span>
                                            <span className={styles.date}>{item.date}</span>
                                        </div>
                                    </div>
                                    <div className={styles.postHeaderContentShare}>
                                    <a href="https://www.twitter.com/luifereduardoo" target="_blank"><i className="lab la-twitter"></i></a>
                                    <a href="https://www.instagram.com/luifereduardoo" target="_blank"><i className="lab la-instagram"></i></a>
                                    <a href="https://www.facebook.com/luifereduardoo" target="_blank"><i className="lab la-facebook"></i></a>
                                    <a href="https://www.linkedin.com/in/luifereduardoo/" target="_blank"><i className="lab la-linkedin-in"></i></a>
                                    </div>
                                </div>
                            </section>
                            <section className={styles.postHeaderImage}>
                                <figure>
                                    <div>
                                        <img src={item.image}/>
                                    </div>
                                    {item.creditosImage === undefined? undefined : <figcaption>Photo by <a href={item.creditosImage[0]}>{item.creditosImage[1]}</a></figcaption>}
                                </figure>
                            </section>
                        </div>
                    </div>
                    <section className={styles.mainContainer}>
                        <section className={styles.mainContainerContent}>
                            {item.content}
                        </section>
                    </section>
                </article>
                <aside className={styles.mainContainerAside}>
                    <span>Post relacionados</span>
                    <div className={styles.mainContainerAsidePost}>
                    </div>
                </aside>
            </main>
            <Footer />
        </>
    );
}

export {PostBlog};