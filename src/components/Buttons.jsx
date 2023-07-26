import React from 'react';
import styles from '../assets/styles/buttons.module.css'
function ButtonBanner(props) {
    return(
        <button onClick={props.handleButtonBanner} id={styles.buttonBanner}>
        {props.children}
        </button>
    );
}
export {ButtonBanner}