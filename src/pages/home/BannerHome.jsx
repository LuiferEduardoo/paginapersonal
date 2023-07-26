import React from 'react';
import { ChevronDoubleDownIcon } from "@heroicons/react/20/solid";
import {ButtonBanner} from '../../components/Buttons';
import {Banner} from '../../components/Banner';
import styles from '../../assets/styles/bannerHome.module.css';
function BannerHome({handleButtonBanner}) {
    return(
        <Banner img ="https://cdn.luifereduardoo.com/img/banner/home/png_20230213_185406_0000.webp" location="home">
        <section className={styles.headerContainerInformation}>
            <span className={styles.presentation}>I'am</span>
            <span className={styles.name}>Luifer Ortega</span>
            <span className={styles.profession}> Full stack developer</span>
            <ButtonBanner handleButtonBanner={handleButtonBanner}>
                Sobre mí <ChevronDoubleDownIcon className="h-4 w-4 text-white" />
            </ButtonBanner>
        </section>
        </Banner>
    );
}
export {BannerHome}