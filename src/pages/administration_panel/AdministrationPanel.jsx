import React, { useState } from 'react';
import Sideber from './Sideber';
import Nav from './Nav';
import AdministrationPanelContent from './AdministrationPanelContent';

function AdministrationPanel({ token, userInfo }){
    return(
        <>
            <Nav userInfo={userInfo} token={token}/>
            <AdministrationPanelContent userInfo={userInfo} token={token}/>
        </>
    );
}

export {AdministrationPanel};