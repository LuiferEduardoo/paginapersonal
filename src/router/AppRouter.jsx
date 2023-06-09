import React, { useState, useEffect } from 'react';
import { Navigate, Routes, Route } from 'react-router-dom';
import Cookies from 'js-cookie';
import '../assets/styles/global.css'
import {Home} from '../pages/home/Home';
import {Portfolio} from '../pages/portfolio/Portfolio';
import {ProyectInformation} from '../pages/portfolio/ProyectInformation';
import {Blog} from '../pages/blog/Blog';
import {PostBlog} from '../pages/blog/PostBlog';
import {Contact} from '../pages/contact/Contact';
import {Login} from '../pages/login/Login';
import {ProtectedRoute} from '../utils/ProtectedRoute';
import {AdministrationPanel} from '../pages/administration_panel/AdministrationPanel';

export const AppRouter = () =>{

    const [isToken, setToken] = useState(() =>{
        const encryptedToken = Cookies.get('token');
        if (encryptedToken) {
            return true;
        } else {
            return false;
        }
    });

    return (
        <>
            <Routes>
                <Route index element ={<Home/>}/>
                <Route path="/portfolio" element ={<Portfolio/>}/>
                <Route path='/portfolio/:link' element ={<ProyectInformation/>}/>
                <Route path='/blog' element ={<Blog/>}/>
                <Route path='/blog/:link' element ={<PostBlog/>}/>
                <Route path='/contact' element ={<Contact/>}/>
                <Route path='/login' element ={isToken ? <Navigate to="/administration-panel" /> :<Login/>}/>
                <Route path= '/administration-panel' element={
                    <ProtectedRoute isToken={isToken}>
                        <AdministrationPanel/>
                    </ProtectedRoute>
                }/>
            </Routes>
        </>
    );
}