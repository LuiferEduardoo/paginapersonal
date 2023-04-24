import { Routes, Route } from 'react-router-dom';
import {Home} from './pages/home/Home';
import {Portfolio} from './pages/portfolio/Portfolio';
import {ProyectInformation} from './pages/portfolio/ProyectInformation';
import {Blog} from './pages/blog/Blog';
import {PostBlog} from './pages/blog/PostBlog';
import {Contact} from './pages/contact/Contact';
import './assets/styles/global.css'


function App() {    
    return(
        <>
            <Routes>
                <Route path="/" element ={<Home/>}/>
                <Route path="/portfolio" element ={<Portfolio/>}/>
                <Route path='/portfolio/:link' element ={<ProyectInformation/>}/>
                <Route path='/blog' element ={<Blog/>}/>
                <Route path='/blog/:link' element ={<PostBlog/>}/>
                <Route path='/contact' element ={<Contact/>}/>
            </Routes>
        </>
    );
}

export default App;