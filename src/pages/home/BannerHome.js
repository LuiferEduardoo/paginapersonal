import React from 'react';
import {ButtonBanner} from '../../components/Buttons';
import {Banner} from '../../components/Banner';
import styles from '../../assets/styles/bannerHome.module.css';
function BannerHome() {
    return(
        <Banner img ="https://cdn.luifereduardoo.com/img/banner/home/png_20230213_185406_0000.webp" location="home">
        <section className={styles.headerContainerInformation}>
            <span className={styles.presentation}>I'am</span>
            <span className={styles.name}>Luifer Ortega</span>
            <span className={styles.profession}> Full stack developer</span>
            <ButtonBanner>
                Sobre mí <i className="las la-angle-double-down"></i>
            </ButtonBanner>
        </section>
        </Banner>
    );
}
export {BannerHome}