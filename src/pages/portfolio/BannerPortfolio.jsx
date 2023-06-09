import React from 'react';
import {ButtonBanner} from '../../components/Buttons';
import {Banner} from '../../components/Banner';
import style from '../../assets/styles/bannerPortfolio.module.css';

function BannerPortfolio() {
    return(
        <Banner img ="https://cdn.luifereduardoo.com/img/banner/portfolio/coding-924920_1280.webp" location='portfolio'>
        <div className ={style.headerCapa}></div>
        <section className={style.headerContainerDescription}>
            <span className={style.information}>Conoce sobre mis proyectos y experiencia</span>
            <ButtonBanner>
                <i className="las la-angle-double-down"></i>
            </ButtonBanner>
        </section>
        </Banner>
    );
}
export {BannerPortfolio}