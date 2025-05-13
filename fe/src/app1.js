import logo from './logo.svg';
import React ,{useState,useEffect, useLayoutEffect} from 'react'
// import './App.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import {BrowserRouter , Route, Routes} from 'react-router-dom';

import Home from './components/home/Home';
import Spinner from 'react-bootstrap/Spinner';
import Button from 'react-bootstrap/Button';
import Card from 'react-bootstrap/Card';
import Placeholder from 'react-bootstrap/Placeholder';


import './assets/plugins/fontawesome-free/css/all.min.css'

import './assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css'
// import './assets/dist/css/adminlte.min.css'
import './assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css'
// import './assets/plugins/daterangepicker/daterangepicker.css'
// import './assets/plugins/select2/css/select2.min.css'
// import './assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'
import './css/app.css'
import './css/home.css'

import './js/app.js'





import HomeProperties from './components/home/HomeProperties';
import HomeContactus from './components/home/HomeContactus';
import HomeGallery from './components/home/HomeGallery';
import HomeAboutus from './components/home/HomeAboutus';
import Login from './components/home/Login';
import Register from './components/home/Register';
import PasswordReset from './components/home/PasswordReset';
import axios from 'axios';
import Dashboard from './components/home/admin/Dashboard';

  axios.defaults.baseURL=process.env.REACT_APP_BACKEND_API_URL;
  axios.defaults.headers.post['Content-Type']='application/json';
  axios.defaults.headers.post['Accept']='application/json';
  axios.defaults.withCredentials=true;
  
function App() {
  const [spinning,setSpinning]=useState(true)

  useLayoutEffect(()=>{
    setTimeout(()=>setSpinning(false))
  },[])
  return (
    <>
      {spinning=== false ?(
        <BrowserRouter>
          <Routes>
                <Route path='/' element={<Home />} />
                <Route path='/allproperties' element={<HomeProperties />} />
                <Route path='/aboutus' element={<HomeAboutus />} />
                <Route path='/contactus' element={<HomeContactus />} />
                <Route path='/gallery' element={<HomeGallery />} />
                <Route path='/login' element={<Login />} />
                {/* <Route path='/register' element={<Register />} /> */}
                <Route path='/password/request' element={<PasswordReset />} />
                <Route path='/dashboard' element={<Dashboard />} />

                
          </Routes>
        </BrowserRouter>
      ):(
        <main className='' style={{"margin": "2%","padding":"10%","minHeight": "calc(70vh - 3rem)","borderRadius":"10px"}}>
          <div className="container text-cennter elevation-2" style={{"margin": "1%","padding":"5%","borderRadius":"10px"}}>
            <div className="row justify-content-center">
              {/* <div className=''>
                <Spinner animation="grow" variant="primary" size="lg" role="status"></Spinner>
                <h5 className="visually-hidden" style={{"padding": "5px","display":"inline-block"}}> Please Wait...</h5>
                <h5 className='text-bold' style={{"marginTop": "5%","marginLeft": "-5%"}}>Please Wait...</h5>
              </div> */}
                <Spinner animation="grow" variant="primary" size="lg" role="status"></Spinner>
                <h5 className='text-center' style={{"marginTop": "5%"}}>Please Wait...</h5>
                <Card.Body>
                  
                  <Placeholder as={Card.Text} animation="glow">
                    <Placeholder className='border-info' xs={12} style={{"padding": "2%"}} /> 
                    <Placeholder className='border-info' xs={4} /> <Placeholder className='border-info' xs={4} />{' '}
                    <Placeholder className='border-info' xs={12} /> <Placeholder  className='border-info'xs={8} />
                  </Placeholder>
                </Card.Body>
            </div>
          </div>
        </main>
      )}
      
    </>
  );
}

export default App;
