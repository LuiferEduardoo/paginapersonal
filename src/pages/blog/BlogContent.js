import React from 'react';
import { Helmet } from "react-helmet";
import styles from '../../assets/styles/blog.module.css';
import {blogApi} from '../../services/blogApi';
import { Link } from 'react-router-dom';

function BlogContent() {
    const firstTwoPosts = blogApi.slice(0, 2);
    const postsAfterFirstTwo = blogApi.slice(2);
    return (
        <section className={styles.mainContainer}>
            <section className={styles.mainContainerBanner}>
                {firstTwoPosts.map((element, index) => 
                        <div key={index} className={`${styles.post} ${index === 0 ? styles.firstPost : styles.seconPost}`}>
                            <Link to={element.link}>
                                <div>
                                    <img src={element.image}/>
                                </div>
                                <h1>{element.title}</h1>
                                <span className={styles.date}>{element.date}</span>
                                <span className={styles.content}>{element.content}</span>
                            </Link>
                        </div>
                )}
            </section>
            <section className={styles.mainContainerAfterFirstTwo}>
                {postsAfterFirstTwo.map((element, index) => 
                            <div key={index} className={styles.post}>
                                <Link to={element.link}>
                                    <div>
                                        <img src={element.image}/>
                                    </div>
                                    <h1>{element.title}</h1>
                                    <span className={styles.date}>{element.date}</span>
                                    <span className={styles.content}>{element.content}</span>
                                </Link>
                            </div>
                    )}
            </section>
        </section>
    );
}


export {BlogContent};