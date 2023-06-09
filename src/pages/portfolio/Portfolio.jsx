import React, {useEffect} from 'react';
import { Helmet } from "react-helmet";
import '../../assets/styles/portafolio.css';
import {BannerPortfolio} from './BannerPortfolio';
import {Navbar} from '../../components/Navbar';
import {Footer} from '../../components/Footer';
import {PortfolioContent} from './PortfolioContent';

function Portfolio() {
    useEffect(() => {
        window.scrollTo(0, 0);
    });
    return (
    <>
        <Helmet>
            <title>Portafolio</title>
        </Helmet>
        <Navbar />
        <BannerPortfolio />
        <PortfolioContent />
        <Footer />
    </>
    );
}

export {Portfolio};