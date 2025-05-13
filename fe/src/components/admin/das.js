import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useState } from 'react';
import DashFooter from './DashFooter';
import Carousel from 'react-bootstrap/Carousel';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import logo from '../../assets/img/wagitonga1.png'

import {BrowserRouter , Route, Routes} from 'react-router-dom';

function DashboardHome() {
    document.title="DashboardHome";
    const [closed,setClosed]=useState(false)

    const [show,setShow]=useState(false);
    const [showdownloadpayments,setShowDownloadPayments]=useState(false);
    const [property,setProperty]=useState('');
    const [propertyid,setPropertyId]=useState('');

    const handleClose = () => {
        setShow(false);
        setProperty('');
        setPropertyId('');
    };
    const handleShow = (names,id) => {
        setShow(true);
        setProperty(names);
        setPropertyId(id);
    };

    const handlePaymentClose = () => {
        setShowDownloadPayments(false);
        setProperty('');
        setPropertyId('');
    };
    const handlePaymentShow = (names,id) => {
        setShowDownloadPayments(true);
        setProperty(names);
        setPropertyId(id);
    };

    



  return (
    <>
    <div className="wrapper">
        <DashNavBar setClosed={setClosed} closed={closed} active='home'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='home'/>
        {/* className={`nav-link ${active==='home'?'active':''}`} */}
        
        <main class="py-3">
            <div className={`content-wrapper ${closed?'closed':''}`} style={{"paddingTop": "10px"}}>
           
                <section className="content">
                    <div class="container">
                        <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div className="row m-0 p-0">
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 m-0 p-1">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Properties</span>
                                            <span class="info-box-number">
                                            10
                                            <small>%</small>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 m-0 p-1">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Houses</span>
                                            <span class="info-box-number">41,410</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 m-0 p-1">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Tenants</span>
                                            <span class="info-box-number">760</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 m-0 p-1">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Rent</span>
                                            <span class="info-box-number">2,000</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 m-0 p-1">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Waterbill</span>
                                            <span class="info-box-number">2,000</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 m-0 p-1">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Houses</span>
                                            <span class="info-box-number">41,410</span>
                                        </div>
                                    </div>
                                </div>


                                <div className="col-md-12 m-0 p-0 mt-4 mb-4">
                                    <div className="card border-info" >
                                        <div className="card-header bg-info text-white elevation-2 m-0 p-0">
                                            <h4 style={{"textAlign": "center"}}>Water Bill Dec 2022 </h4>
                                        </div>

                                        <div className="card-body text-center m-0 mb-2 p-2" style={{"paddingTop": "10px"}}>
                                            
                                            <Carousel className='' >
                                                <Carousel.Item >
                                                    <div className="row m-0 mb-0 ml-4 mr-4 pl-2 pr-2">
                                                        <div className="col-12 col-md-6 col-lg-4 m-0 mb-2 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-info elevation-1">ED1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 101550.00</span>
                                                                    <span className='card-box-text'>
                                                                        <div className="card-box-links m-0 p-1">
                                                                            <div className="row m-0 p-0">
                                                                                <div className='col-4 p-0 m-0'>
                                                                                    <a href="#" class="p-0 m-0 pl-1 pr-1 btn btn-outline-primary"> <i className='fa fa-upload text-sm'></i></a>
                                                                                </div>
                                                                                
                                                                                <div className='col-4 p-0 m-0'>
                                                                                    <a href="#" class="p-0 m-0 pl-1 pr-1 btn btn-outline-success"> <i className='fa fa-envelope text-sm'></i></a>
                                                                                </div>

                                                                                <div className='col-4 p-0 m-0'>
                                                                                    <a href="#" class="p-0 m-0 pl-1 pr-1 btn btn-outline-secondary" onClick={()=>{handleShow("ED1","1")}}> <i className='fa fa-download text-sm'></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 mb-2 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-info elevation-1">EA1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 101550.00</span>
                                                                    <span className='card-box-text'>
                                                                        <div className="card-box-links m-0 p-1">
                                                                            <div className="row m-0 p-0">
                                                                                <div className='col-4 p-0 m-0'>
                                                                                    <a href="#" class="p-0 m-0 pl-1 pr-1 btn btn-outline-primary"> <i className='fa fa-upload text-sm'></i></a>
                                                                                </div>
                                                                                
                                                                                <div className='col-4 p-0 m-0'>
                                                                                    <a href="#" class="p-0 m-0 pl-1 pr-1 btn btn-outline-success"> <i className='fa fa-envelope text-sm'></i></a>
                                                                                </div>

                                                                                <div className='col-4 p-0 m-0'>
                                                                                    <a href="#" class="p-0 m-0 pl-1 pr-1 btn btn-outline-secondary" onClick={()=>{handleShow("EA1","2")}}> <i className='fa fa-download text-sm'></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 mb-2 p-1">
                                                            <div className='bg-light m-0'>
                                                                <div class="card-box mb-1">
                                                                    <div className='card-icon'>
                                                                        <span class="card-box-icon bg-info elevation-1">AS 1</span>
                                                                        <span class="card-box-icon bg-light elevation-1">0</span>
                                                                    </div>

                                                                    <div class="card-box-content">
                                                                        <span class="card-box-text">41/45 </span>
                                                                        <span class="card-box-number">Kshs. 19650.00</span>
                                                                        <span className='card-box-text'>
                                                                            <div className="card-box-links m-0 p-1">
                                                                                <div className="row m-0 p-0">
                                                                                    <div className='col-4 p-0 m-0'>
                                                                                        <a href="#" class="p-0 m-0 pl-1 pr-1 btn btn-outline-primary"> <i className='fa fa-upload text-sm'></i></a>
                                                                                    </div>
                                                                                    
                                                                                    <div className='col-4 p-0 m-0'>
                                                                                        <a href="#" class="p-0 m-0 pl-1 pr-1 btn btn-outline-success"> <i className='fa fa-envelope text-sm'></i></a>
                                                                                    </div>

                                                                                    <div className='col-4 p-0 m-0'>
                                                                                        <a href="#" class="p-0 m-0 pl-1 pr-1 btn btn-outline-secondary" onClick={()=>{handleShow("AS 1","3")}}> <i className='fa fa-download text-sm'></i></a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </span>
                                                                    </div>
                                                                    
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </Carousel.Item>
                                                <Carousel.Item >
                                                    <div className="row m-0 mb-0 ml-4 mr-4 pl-2 pr-2">
                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-info elevation-1">E1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 101550.00</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-info elevation-1">E1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 101550.00</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-info elevation-1">TIM 1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </Carousel.Item>
                                                <Carousel.Item >
                                                    <div className="row m-0 mb-0 ml-4 mr-4 pl-2 pr-2">
                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-info elevation-1">NG</span>
                                                                    <span class="card-box-icon bg-light elevation-1">031</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 101550.00</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-info elevation-1">E1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 101550.00</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-info elevation-1">ESTL</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </Carousel.Item>
                                                <Carousel.Item >
                                                    <div className="row m-0 mb-0 ml-4 mr-4 pl-2 pr-2">
                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-info elevation-1">E1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 101550.00</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-info elevation-1">E1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 101550.00</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-info elevation-1">TIM 1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </Carousel.Item>
                                            </Carousel>
                                        </div>
                                    </div>
                                </div>
                                


                                <div className="col-md-12 m-0 p-0 mt-4 mb-4">
                                    <div className="card border-ok" >
                                        <div className="card-header bg-success text-white elevation-2 m-0 p-0">
                                            <h4 style={{"textAlign": "center"}}>Payments Dec 2022 </h4>
                                        </div>

                                        <div className="card-body text-center m-0 mb-2 p-2" style={{"paddingTop": "10px"}}>
                                            
                                            <Carousel className='' >
                                                <Carousel.Item >
                                                    <div className="row m-0 mb-0 ml-4 mr-4 pl-2 pr-2">
                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-success elevation-1">E1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">Kshs. 105550.00</span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>

                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon ">
                                                                        <div className='p-0 m-0'>
                                                                            <a href="#" class="btn btn-outline-primary p-0 pl-2 pr-2 m-0" onClick={()=>{handlePaymentShow("E1","1")}}> <i className='fa fa-briefcase text-sm'></i></a>
                                                                        </div>
                                                                    </span>
                                                                    <span class="card-box-icon ">
                                                                        <div className='p-0 m-0'>
                                                                            <a href="#" class="btn btn-outline-success p-0 pl-2 pr-2 m-0" onClick={()=>{handlePaymentShow("E1","1")}}> <i className='fa fa-envelope text-sm'></i></a>
                                                                        </div>
                                                                    </span>
                                                                    <span class="card-box-icon ">
                                                                        <div className='p-0 m-0'>
                                                                            <a href="#" class="btn btn-outline-secondary p-0 pl-2 pr-2 m-0" onClick={()=>{handlePaymentShow("E1","1")}}> <i className='fa fa-download text-sm'></i></a>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-success elevation-1">E1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">105550.00</span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-success elevation-1">TIM 1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">105550.00</span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>

                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon ">
                                                                        <div className='p-0 m-0'>
                                                                            <a href="#" class="btn btn-outline-primary p-0 pl-2 pr-2 m-0" onClick={()=>{handlePaymentShow("ED1","1")}}> <i className='fa fa-briefcase text-sm'></i></a>
                                                                        </div>
                                                                    </span>
                                                                    <span class="card-box-icon ">
                                                                        <div className='p-0 m-0'>
                                                                            <a href="#" class="btn btn-outline-success p-0 pl-2 pr-2 m-0" onClick={()=>{handlePaymentShow("ED1","1")}}> <i className='fa fa-envelope text-sm'></i></a>
                                                                        </div>
                                                                    </span>
                                                                    <span class="card-box-icon ">
                                                                        <div className='p-0 m-0'>
                                                                            <a href="#" class="btn btn-outline-secondary p-0 pl-2 pr-2 m-0" onClick={()=>{handlePaymentShow("ED1","1")}}> <i className='fa fa-download text-sm'></i></a>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </Carousel.Item>
                                                <Carousel.Item >
                                                    <div className="row m-0 mb-0 ml-4 mr-4 pl-2 pr-2">
                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-success elevation-1">NG</span>
                                                                    <span class="card-box-icon bg-light elevation-1">031</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">105550.00</span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>

                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon ">
                                                                        <div className='p-0 m-0'>
                                                                            <a href="#" class="btn btn-outline-primary p-0 pl-2 pr-2 m-0" onClick={()=>{handlePaymentShow("ED1","1")}}> <i className='fa fa-briefcase text-sm'></i></a>
                                                                        </div>
                                                                    </span>
                                                                    <span class="card-box-icon ">
                                                                        <div className='p-0 m-0'>
                                                                            <a href="#" class="btn btn-outline-success p-0 pl-2 pr-2 m-0" onClick={()=>{handlePaymentShow("ED1","1")}}> <i className='fa fa-envelope text-sm'></i></a>
                                                                        </div>
                                                                    </span>
                                                                    <span class="card-box-icon ">
                                                                        <div className='p-0 m-0'>
                                                                            <a href="#" class="btn btn-outline-secondary p-0 pl-2 pr-2 m-0" onClick={()=>{handlePaymentShow("ED1","1")}}> <i className='fa fa-download text-sm'></i></a>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-success elevation-1">E1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">105550.00</span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-success elevation-1">ESTL</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">105550.00</span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </Carousel.Item>
                                                <Carousel.Item >
                                                    <div className="row m-0 mb-0 ml-4 mr-4 pl-2 pr-2">
                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-success elevation-1">E1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">105550.00</span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-success elevation-1">E1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">105550.00</span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div className="col-12 col-md-6 col-lg-4 m-0 p-1">
                                                            <div class="card-box mb-1">
                                                                <div className='card-icon'>
                                                                    <span class="card-box-icon bg-success elevation-1">TIM 1</span>
                                                                    <span class="card-box-icon bg-light elevation-1">0</span>
                                                                </div>
                                                                

                                                                <div class="card-box-content">
                                                                    <span class="card-box-text">41/45 </span>
                                                                    <span class="card-box-number">105550.00</span>
                                                                    <span class="card-box-number">Kshs. 19650.00</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </Carousel.Item>
                                            </Carousel>
                                        </div>
                                    </div>
                                </div>
                                {/* <Button variant='primary' onClick={()=>{handleShow("D1","2")}}>
                                    Show Modal
                                </Button> */}
                                <Modal show={show} onHide={handleClose} className="mt-4">
                                    <Modal.Header className='justify-content-center bg-info m-0 p-2' closeButton>
                                        <Modal.Title className='mx-auto text-white'> 
                                            <h5>Download {property} Waterbill </h5>
                                        </Modal.Title>
                                    </Modal.Header>
                                    <Modal.Body>
                                        <div className="card-box-links m-0 p-1 justify-content-center text-center">
                                            <div className="row m-0 p-0 justify-content-center text-center">
                                                <div className='col-12 p-0 m-1'>
                                                    <a href="#" class="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {property} Waterbill for Dec 2022({propertyid})</small> </i>
                                                    </a>
                                                </div>
                                                
                                                <div className='col-12 p-0 m-1'>
                                                    <a href="#" class="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-info"> <i className='fa fa-download text-md'> <small> {property} Waterbill for 2022({propertyid})</small> </i>
                                                    </a>
                                                </div>

                                                <div className='col-12 p-0 m-1'>
                                                    <a href="#" class="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {property} Waterbill for 2021({propertyid})</small> </i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </Modal.Body>
                                    <Modal.Footer className='justify-content-center bg-light'>
                                        <Button variant='secondary' onClick={handleClose}>
                                            Close
                                        </Button>
                                    </Modal.Footer>
                                </Modal>

                                <Modal show={showdownloadpayments} onHide={handlePaymentClose} className="mt-4">
                                    <Modal.Header className='justify-content-center bg-info m-0 p-2' closeButton>
                                        <Modal.Title className='mx-auto text-white'> 
                                            <h5>Download {property} Payments Reports </h5>
                                        </Modal.Title>
                                    </Modal.Header>
                                    <Modal.Body>
                                        <div className="card-box-links m-0 p-1 justify-content-center text-center">
                                            <div className="row m-0 p-0 justify-content-center text-center">
                                                <div className='col-12 p-0 m-1'>
                                                    <a href="#" class="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {property} Payments for Dec 2022({propertyid})</small> </i>
                                                    </a>
                                                </div>
                                                
                                                <div className='col-12 p-0 m-1'>
                                                    <a href="#" class="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-info"> <i className='fa fa-download text-md'> <small> {property} Payments for 2022({propertyid})</small> </i>
                                                    </a>
                                                </div>

                                                <div className='col-12 p-0 m-1'>
                                                    <a href="#" class="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {property} Payments for 2021({propertyid})</small> </i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </Modal.Body>
                                    <Modal.Footer className='justify-content-center bg-light'>
                                        <Button variant='secondary' onClick={handlePaymentClose}>
                                            Close
                                        </Button>
                                    </Modal.Footer>
                                </Modal>

                            
                                

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info" >
                                        <div className="card-header bg-info text-white elevation-2 m-0 p-0">
                                            <h4 style={{"textAlign": "center"}}>Welcome all </h4>
                                        </div>

                                        <div className="card-body text-center" style={{"paddingTop": "10px"}}>
                                            
                                            <p>
                                                Welcome to Wagitonga Agencies Dashboard
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info" >
                                        <div className="card-header bg-info text-white elevation-2 m-0 p-0">
                                            <h4 style={{"textAlign": "center"}}>Welcome all </h4>
                                        </div>

                                        <div className="card-body text-center" style={{"paddingTop": "10px"}}>
                                            
                                            <p>
                                                Welcome to Wagitonga Agencies Dashboard
                                            </p>
                                        </div>
                                    </div>
                                </div>


                                

                                
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div className="col-md-12 m-0 p-0 ">
                                <div className="card border-none" >
                                    <div className="card-header  bg-info text-white elevation-2  m-0 p-0">
                                        <h5 style={{"textAlign": "center"}}>
                                            <i class="fa fa-bell fa-fw"></i> 
                                            Notifications Panel 
                                        </h5>
                                    </div>

                                    <div className="card-body m-0 p-0 pt-2" >
                                        
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <i class="fa fa-comment fa-fw"></i> New Comment
                                                <span class="pull-right text-muted small"><em>4 minutes ago</em>
                                                </span>
                                            </a>
                                            <a href="#" class="list-group-item">
                                                <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                                <span class="pull-right text-muted small"><em>12 minutes ago</em>
                                                </span>
                                            </a>
                                            <a href="#" class="list-group-item">
                                                <i class="fa fa-envelope fa-fw"></i> Message Sent
                                                <span class="pull-right text-muted small"><em>27 minutes ago</em>
                                                </span>
                                            </a>
                                            <a href="#" class="list-group-item">
                                                <i class="fa fa-tasks fa-fw"></i> New Task
                                                <span class="pull-right text-muted small"><em>43 minutes ago</em>
                                                </span>
                                            </a>
                                            <a href="#" class="list-group-item">
                                                <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                                <span class="pull-right text-muted small"><em>11:32 AM</em>
                                                </span>
                                            </a>
                                            <a href="#" class="list-group-item">
                                                <i class="fa fa-bolt fa-fw"></i> Server Crashed!
                                                <span class="pull-right text-muted small"><em>11:13 AM</em>
                                                </span>
                                            </a>
                                            <a href="#" class="list-group-item">
                                                <i class="fa fa-warning fa-fw"></i> Server Not Responding
                                                <span class="pull-right text-muted small"><em>10:57 AM</em>
                                                </span>
                                            </a>
                                            <a href="#" class="list-group-item">
                                                <i class="fa fa-shopping-cart fa-fw"></i> New Order Placed
                                                <span class="pull-right text-muted small"><em>9:49 AM</em>
                                                </span>
                                            </a>
                                            <a href="#" class="list-group-item">
                                                <i class="fa fa-money fa-fw"></i> Payment Received
                                                <span class="pull-right text-muted small"><em>Yesterday</em>
                                                </span>
                                            </a>
                                        </div>
                                        <a href="#" class="btn btn-default btn-block">View All Alerts</a>
                                    </div>
                                </div>

                                
                                
                            </div>
                        </div>
                            
                        </div>

                    </div>

                    <div className='container'>
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                
                            </div>
                            <div class="col-lg-4">

                            </div>
                        </div>
                    </div>
                    {/* <div className="container">
                        <div className="row justify-content-center">
                            <div className="col-md-12">
                                <div className="card" >
                                    <div className="card-header text-info" style={{"backgroundColor": "transparent"}}>
                                        <h1 style={{"textAlign": "center"}}>Welcome </h1>
                                    </div>

                                    <div className="card-body" style={{"paddingTop": "10px"}}>
                                        <div className="row">

                                        <div className="p-1 m-0" >
                                            <div className="elevation-4 bg-white" >
                                            <div className="card-header bg-info" >  
                                                <span className="mx-auto text-center text-sm">Waterbill 
                                                    <span className="text-sm text-danger monthy-title"></span>
                                                </span>
                                                
                                                <span className="text-sm float-right text-white" id="monthy-title"></span>
                                            </div>
                                            <div className="card-body m-1" >
                                                <div className="">
                                                <div className=" " id="monthlywaterbills">
                                                    <p className="text-danger text-center">Please Wait ... </p>
                                                </div>
                                                </div>
                                            </div>
                                            </div> 
                                        </div>


                                        </div>


                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div> */}

                </section>
            </div>
        </main>


        <DashFooter />
      </div>
    </>
  );
}

export default DashboardHome;