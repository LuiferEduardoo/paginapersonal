import React, {useEffect} from 'react';
import { Helmet } from "react-helmet";
import {Navbar} from '../../components/Navbar';
import {Footer} from '../../components/Footer';
import {BlogContent} from './BlogContent';

function Blog() {
    useEffect(() => {
        window.scrollTo(0, 0);
    });
    return (
    <>
        <Helmet>
            <title>Blog</title>
            <meta name="description" content="Descubre escritos facinantes en mi blog" />
        </Helmet>
        <Navbar />
        <BlogContent />
        <Footer />
    </>
    );
}

export {Blog};