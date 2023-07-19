import React, { useState } from 'react';
import styles from '../../assets/styles/administrationPanel.module.css';

const SubMenu = ({selectedSection, onViewClick, onCreateClick}) => {
    return (
        <div className={styles.submenu}>
            <ul className={styles.submenuSections}>
                <li
                    onClick={onViewClick}
                    className={`${styles.submenuSection} ${
                    selectedSection === 'View' ? styles.active : ''
                    }`}
                >
                    Ver
                </li>
                <li
                    onClick={onCreateClick}
                    className={`${styles.submenuSection} ${
                    selectedSection === 'Create' ? styles.active : ''
                    }`}
                >
                    Crear
                </li>
            </ul>
        </div>
    );
};

export default SubMenu;
