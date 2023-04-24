import React from 'react'
import { Link } from 'react-router-dom';

function Proyect(props){
    return(
        <div className = "main-container-proyects-container">
            <Link to={props.link} key={props.link}>
                <img src ={props.miniature}/>
                <div className="main-container-proyects-container-description">
                    <div className='main-container-proyects-container-description-top'>
                        <h1>{props.title}</h1>
                        <span>{props.brief_description}</span>
                    </div>
                    <div className='main-container-proyects-container-description-bottom'>
                        <span><i className="las la-tag"></i> {props.category}</span>
                    </div>
                </div>
            </Link>
        </div>
    );   
}
export {Proyect};