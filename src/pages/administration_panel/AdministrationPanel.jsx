import React, { useState } from 'react';
import Sideber from './Sideber';
import Nav from './Nav';
import { Helmet } from "react-helmet";
import AdministrationPanelContent from './AdministrationPanelContent';

function AdministrationPanel({ token, userInfo }){
    <Helmet>
        <title>Administration Panel</title>
    </Helmet>
    return(
        <>
            <Nav userInfo={userInfo} token={token}/>
            <AdministrationPanelContent userInfo={userInfo} token={token}/>
        </>
    );
}

export default AdministrationPanel