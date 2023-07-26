import React from "react";
import { useLocation } from 'react-router-dom';
import Box from '@mui/material/Box';
import { Skeleton, useMediaQuery} from "@mui/material";
import Grid from '@mui/material/Grid';
import styles from '../assets/styles/SkeletonComponent.module.css'

const SkeletonComponent = () => {

    const isSmallScreen = useMediaQuery('(max-width: 772px)'); // Ejemplo: tamaño pequeño hasta 600px

    const style = {
        firstImage: {
            height: isSmallScreen ? 340 : 600,
            borderRadius: 30,
        },
        secondImage: {
            height: isSmallScreen ? 340 : 300,
            borderRadius: 30,
        }
    };
    const getHeightBasedOnPathname = () => {
        if (location.pathname === '/portfolio') {
          return 500; // Altura 600 cuando la ruta es "/portfolio"
        } else {
          return 600; // Altura 300 para otras rutas (incluyendo "/")
        }
      };

      const skeletonHeight = getHeightBasedOnPathname();

    return(
        <>
            {location.pathname != '/blog' && location.pathname != '/contact' && (
                <section>
                    <Grid>
                        <Skeleton variant="rectangular" height={skeletonHeight} animation="wave" />
                    </Grid>
                </section>
            )}

            {location.pathname === '/' && (
            <section className={styles.mainContainer}>
                <Box className={styles.contentHome}>
                    <Skeleton height={50} animation="wave" />
                    {[1,2,3,4,5,6,7,8,9,10].map(item => <Skeleton key={item} animation="wave" />)}
                </Box>
                <Box className={styles.imageHome}>
                    <Skeleton variant="rectangular" animation="wave" height={500}/>
                </Box>
            </section>
            )}

            {location.pathname === '/portfolio' && (
                <section className={styles.mainPortfolio}>
                    {[1,2,3,4].map(item => 
                        <Grid key={item} sx={{ width: '100%', marginRight: '20px', borderRadius: 10 }}>
                            <Skeleton variant="rectangular" width='100%' height={200} />
                            <Skeleton height={50} animation="wave" />
                            {[1,2,3].map(item => <Skeleton key={item} animation="wave" />)}
                            <Skeleton variant="rectangular" width='20%' sx={{marginTop: 6}}/>
                        </Grid>
                    )}
                </section>
            )}

            {location.pathname === '/blog' && (
                <section className={styles.mainContainerBlog}> 
                    <section className={styles.mainContainerBanner}>
                        {[1,2].map(item => 
                        <Grid key={item} className={item == 1 ? styles.firstPostBlog : styles.seconPostBlog}>
                            <Skeleton variant="rectangular" width='100%' style={item === 1 ? style.firstImage : style.secondImage} />
                            <Skeleton height={80} animation="wave" />
                            {[1,2,3,4].map(item => <Skeleton key={item} animation="wave" />)}
                        </Grid>)}
                    </section>
                </section>
            )}

            {location.pathname === '/contact' && (
                <section className={styles.mainContainerContact}>
                    <Grid className={styles.informationPerson}>
                        <Skeleton height={50} animation="wave" width={150}/>
                        <div className={styles.informationPersonEmail}>
                            <Skeleton height={50} animation="wave" width="70%"/>
                        </div>
                        <div className={styles.informationSocialMedia}>
                            {[1,2,3,4,5].map(item => <Skeleton key={item} variant="circular" width={45} height={45} style={{marginRight: 10}}/>)}
                        </div>
                    </Grid>
                    <div className={styles.contactForm}>
                        <Skeleton height={50} animation="wave" width="30%" disableMargins/>
                        <Skeleton height={80} animation="wave" width="100%" disableMargins/>
                        <Skeleton height={80} animation="wave" width="100%" disableMargins/>
                        <Skeleton height={80} animation="wave" width="100%" disableMargins/>
                        <Skeleton height={400} animation="wave" width="100%" disableMargins/>
                        <Skeleton height={85} animation="wave" width="100%" disableMargins/>
                    </div>
                </section>
            )}
        </>
    )
}

export default SkeletonComponent;