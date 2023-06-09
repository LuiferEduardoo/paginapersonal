import React from 'react'
import {Proyect} from './Proyect'
import {proyectApi} from '../../services/proyectApi'

function PortfolioContent() {
    return(
        <section className="main-container">
            <section className="main-container-proyects">
            {proyectApi.map(elements =>
                <Proyect key ={elements.id} link={elements.link} miniature ={elements.miniature} title={elements.title} brief_description={elements.brief_description} category={elements.category}/>
            )}
            </section>
        </section>
    );   
}
export {PortfolioContent}