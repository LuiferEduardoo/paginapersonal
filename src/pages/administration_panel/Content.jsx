import React, { useState, useEffect } from "react";
import { Routes, Route } from 'react-router-dom';
import SubMenu from './SubMenu';
import styles from '../../assets/styles/administrationPanel.module.css';
import ViewElements from "./ViewElements";
import CreateElements from "./CreateElements";
const ViewSection = ({ element, title }) =>{
    return(
        <>
            <h1 className="text-2xl font-bold">{title}</h1>
            <ViewElements elementObtain={element} />
        </>
    )
}

const Skills =({ selectedSection }) =>{
    return(
        <>
            { selectedSection === 'View' && <ViewSection element="skills" title="Skills"/>}
            { selectedSection === 'Create' && <CreateElements element='skills'/>}
        </>
    );
}

const Projects =({ selectedSection }) =>{
    return(
        <>
            { selectedSection === 'View' && <ViewSection element="projects" title="Proyectos"/>}
            { selectedSection === 'Create' && <CreateElements element='project'/>}
        </>
    );
}

const Blog =({ selectedSection }) =>{
    return(
        <>
            { selectedSection === 'View' && <ViewSection element="blogposts" title="Blog Posts"/>}
            { selectedSection === 'Create' && <CreateElements element='blogpost'/>}
        </>
    );
}
const Content = () =>{
    const [selectedSection, setSelectedSection] = useState('View');

    const handleViewClick = () => {
        setSelectedSection('View');
    };

    const handleCreateClick = () => {
        setSelectedSection('Create');
    };
    return(
        <>
            <SubMenu
                selectedSection={selectedSection}
                onViewClick={handleViewClick}
                onCreateClick={handleCreateClick}
                />
            <div className={styles.contentContent}>
                <Routes>
                    <Route path="/skills" element={<Skills selectedSection={selectedSection}/>}/>
                    <Route path="/projects" element={<Projects selectedSection={selectedSection}/>}/>
                    <Route path="/blog" element={<Blog selectedSection={selectedSection}/>}/>
                </Routes>
            </div>
        </>
    )
}

export default Content;