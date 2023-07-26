import React, { useState, useEffect } from "react";
import { Routes, Route } from "react-router-dom";
import '../../assets/styles/portafolio.css';
import {PortfolioContent} from './PortfolioContent';
import { ProjectInformation } from "./ProjectInformation";
import Elements from "../../services/Elements";
import SkeletonComponent from "../../components/SkeletonComponent";

const Portfolio = () => {
    useEffect(() => {
        window.scrollTo(0, 0);
      });
      const [projects, setProjects] = useState([]);
      const [isLoading, setIsLoading] = useState(true);
      const [error, setError] = useState(null);
    
      useEffect(() => {
        const fetchData = async () => {
          try {
            const callToAPI = await Elements.obtain("projects");
            setProjects(callToAPI);
            setIsLoading(false);
          } catch (error) {
            setError(error.message);
            setIsLoading(false);
          }
        };
    
        fetchData();
      }, []);
    return (
    <>
        {isLoading ? (
          <SkeletonComponent />
        ) : (
            <Routes>
              <Route
                path="/"
                element={<PortfolioContent projects={projects}/>}
              />
              <Route
                path="/:link"
                element={<ProjectInformation projects={projects}/>}
              />
            </Routes>
        )}
        </>
        );
}

export default Portfolio;