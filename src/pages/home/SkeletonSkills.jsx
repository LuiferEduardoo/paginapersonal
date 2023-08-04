import React from "react";
import { useLocation } from 'react-router-dom';
import Box from '@mui/material/Box';
import { Skeleton, useMediaQuery} from "@mui/material";
import Grid from '@mui/material/Grid';
import styles from '../../assets/styles/home.module.css';

const SkeletonSkills  = () => {
    return (
        <section className={styles.mainContainerSkills}>
            <Skeleton height={50} animation="wave" width={20}/>
            <div className={styles.mainContainerSkillsInformation}>
                {[1,2,3,4,5].map(item => <Skeleton key={item} height='100%' width='200px' animation="wave" />)}
            </div>
        </section>
    )
}


export default SkeletonSkills; 