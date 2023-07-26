import React from 'react';
import { ChevronDoubleDownIcon } from "@heroicons/react/20/solid";
import {ButtonBanner} from '../../components/Buttons';
import {Banner} from '../../components/Banner';
import style from '../../assets/styles/bannerPortfolio.module.css';

const BannerPortfolio = ({handleButtonBanner}) => {
    return(
        <Banner img ="https://cdn.luifereduardoo.com/img/banner/portfolio/coding-924920_1280.webp" location='portfolio'>
        <div className ={style.headerCapa}></div>
        <section className={style.headerContainerDescription}>
            <span className={style.information}>Conoce sobre mis proyectos y experiencia</span>
            <ButtonBanner handleButtonBanner={handleButtonBanner}>
                <ChevronDoubleDownIcon className="h-4 w-4 text-white" />
            </ButtonBanner>
        </section>
        </Banner>
    );
}
export {BannerPortfolio}