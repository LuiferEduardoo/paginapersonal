import React, { useState, Suspense  } from 'react';
import { Navigate, Routes, Route } from 'react-router-dom';
import Cookies from 'js-cookie';
import '../assets/styles/global.css'
import SkeletonComponent from '../components/SkeletonComponent';

import Layout from '../components/Layout';

import ProtectedRoute from '../utils/ProtectedRoute';
import Login from '../pages/login/Login'; 
import AdministrationPanel from '../pages/administration_panel/AdministrationPanel';

const Home = React.lazy(() => import('../pages/home/Home'));
const Portfolio = React.lazy(() => import ('../pages/portfolio/Portfolio'));
const Blog = React.lazy(() => import ('../pages/blog/Blog'));
const Contact = React.lazy(() => import ('../pages/contact/Contact'));

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
                <Route path='/login' element ={isToken ? <Navigate to="/administration-panel" /> :<Login/>}/>
                <Route path= '/administration-panel/*' element={
                    <ProtectedRoute isToken={isToken}>
                        <AdministrationPanel/>
                    </ProtectedRoute>
                }/>
                <Route path='/*' element={ 
                    <Layout>
                        <Suspense fallback={<SkeletonComponent />}>
                            <Routes>
                                <Route index element ={<Home/>}/>
                                <Route path="/portfolio/*" element ={<Portfolio/>}/>
                                <Route path='/blog/*' element ={<Blog/>}/>
                                <Route path='/contact' element ={<Contact/>}/>
                            </Routes>
                        </Suspense>
                    </Layout>
                }/>
            </Routes>
        </>
    );
}