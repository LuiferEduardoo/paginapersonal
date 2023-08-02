import React from 'react'
import { Link } from 'react-router-dom';

function Project(props){
    return(
        <div className = "main-container-proyects-container">
            <Link to={props.link} key={props.link}>
                <img src ={props.miniature[0].url} alt={props.miniature[0].name}/>
                <div className="main-container-proyects-container-description">
                    <div className='main-container-proyects-container-description-top'>
                        <h1>{props.name}</h1>
                        <span>{props.brief_description}</span>
                    </div>
                    <div className='main-container-proyects-container-description-bottom'>
                        <span><i className="las la-tag"></i> {props.category[0].name}</span>
                    </div>
                </div>
            </Link>
        </div>
    );   
}
export {Project};