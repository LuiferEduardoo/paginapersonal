import React from 'react';
import styles from '../assets/styles/banner.module.css'

function Banner(props) {
    return (
        <header className= {props.location === 'home' ? 
        styles['headerHome'] : 
        props.location === 'portfolio' ? 
        styles['headerPortfolio'] : 
        styles['headerProyect']}>
            <div className={styles.headerImage}>
                <img src ={props.img}/>
            </div>
            <div className={styles.headerContainer}>
            {props.children}
            </div>
        </header>
        );
}

export {Banner};