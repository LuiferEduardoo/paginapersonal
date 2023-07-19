import React, { useState } from 'react';
import { Navigate, Routes, Route } from 'react-router-dom';
import Sideber from './Sideber';
import AuthService from '../../services/AuthService';
import Dashboard from './Dashboard';
import Content from './Content';
import ImagesContentComponet from './ImagesContentComponet';
import Settings from './Settings';
import styles from '../../assets/styles/administrationPanel.module.css';


function AdministrationPanelContent({ userInfo, token }){
    return(
        <>
            <main className={styles.flex}>
                <Sideber />
                <div className={styles.content}>
                    <Routes>
                        <Route path="/*" exact={true} element={<Dashboard />}/>
                        <Route path="/content/*" exact={true} element={<Content />}/>
                        <Route path="/images/*" exact={true} element={<ImagesContentComponet />}/>
                        <Route path="/settings" exact={true} element={<Settings userInfo={userInfo}/>}/>
                    </Routes>
                </div>
            </main>
        </>
    );
}

export default AdministrationPanelContent;