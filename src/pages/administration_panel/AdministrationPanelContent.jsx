import React, { useState } from 'react';
import { Navigate, Routes, Route } from 'react-router-dom';
import Sideber from './Sideber';
import { Toaster, toast } from 'sonner';
import Content from './Content';
import ImagesContentComponet from './ImagesContentComponet';
import Settings from './Settings';
import styles from '../../assets/styles/administrationPanel.module.css';


function AdministrationPanelContent({ userInfo, token }){
    return(
        <>
            <main className={styles.flex}>
                <Sideber />
                <Toaster richColors position="top-center" />
                <div className={styles.content}>
                    <Routes>
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