import {useEffect, useState } from 'react';

import Spinner from 'react-bootstrap/Spinner';

import Modal from 'react-bootstrap/Modal';
import Button from 'react-bootstrap/Button';
import Select from 'react-select';


import axios from 'axios';

import Swal from 'sweetalert';
import ReLogin from '../home/ReLogin';



function DownloadWaterbillModal({show,handleClose,property,propertyid,loadingmonths,prevmonths,selectedmonth,loadingwater,currentyearup,currentyear,currentyeardown,setLoggedOff}) {
    // document.title=(currenthouse==='')?'Add New House for : '+currentproperty.Plotname:'Update House : '+currenthouse.Housename;
    
    const [loadingdownload,setLoadingDownload]=useState(false);
   
    const downloadWaterbill = () =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingDownload(true)
        }
        const getDownload = async (e) => { 
            let url='/v2/downloads/Reports/Waterbill/'+propertyid+'/'+ (!loadingmonths && prevmonths && prevmonths[selectedmonth].month);
            await axios.get(url,{
                responseType: 'blob',
            })
            .then(response=>{
                if (doneloading) {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    const filename=property+' Water Bill for '+ (!loadingmonths && prevmonths[selectedmonth].monthname)+' .xlsx';
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link); // Clean up
                    setLoadingDownload(false)
                    Swal("Success","Your File Has been Successfully sent to your browser");
                }
            })
            .catch(error=>{
                if(!localStorage.getItem("auth_token")){
                    // let ex=error['response'].data.message;
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        if(!localStorage.getItem("auth_token")){
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                        }  
                        else{ 
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }              
                    }
                    else{
                        // console.log(error.response.statusText)
                        Swal("Error1",""+error,"error");
                    }
                    setLoadingDownload(false)
                }
                else{
                    let ex=error.response.statusText;
                    console.log(error.response)
                    if(ex==='Unauthorized'){
                        setLoadingDownload(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingDownload(false)
                        Swal("Error2",""+error,"error");
                    }
                }

            })
        };
        getDownload();

        return ()=>{
            doneloading=false;
        }
    }

    const downloadWaterbillAll = () =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingDownload(true)
        }
        const getDownload = async (e) => { 
            let url='/v2/downloads/Reports/Waterbill/All/'+ (!loadingmonths && prevmonths && prevmonths[selectedmonth].month);
            await axios.get(url,{
                responseType: 'blob',
            })
            .then(response=>{
                if (doneloading) {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    const filename='All Water Bills for '+ prevmonths[selectedmonth].monthname+' .xlsx';
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link); // Clean up
                    setLoadingDownload(false)
                    Swal("Success","Your File Has been Successfully sent to your browser");
                }
            })
            .catch(error=>{
                if(!localStorage.getItem("auth_token")){
                    // let ex=error['response'].data.message;
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        if(!localStorage.getItem("auth_token")){
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                        }  
                        else{ 
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }              
                    }
                    else{
                        // console.log(error.response.statusText)
                        Swal("Error",""+error,"error");
                    }
                    setLoadingDownload(false)
                }
                else{
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        setLoadingDownload(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingDownload(false)
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
        getDownload();

        return ()=>{
            doneloading=false;
        }
    }

    

    const downloadWaterbillPrevYear = () =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingDownload(true)
        }
        const getDownload = async (e) => { 
            let url='/v2/downloads/Reports/Waterbill/'+propertyid+'/Year/'+currentyeardown;
            await axios.get(url,{
                responseType: 'blob',
            })
            .then(response=>{
                if (doneloading) {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    const filename=property+' Water Bill for '+ currentyeardown+' .xlsx';
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link); // Clean up
                    setLoadingDownload(false)
                    Swal("Success","Your File Has been Successfully sent to your browser");
                }
            })
            .catch(error=>{
                if(!localStorage.getItem("auth_token")){
                    // let ex=error['response'].data.message;
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        if(!localStorage.getItem("auth_token")){
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                        }  
                        else{ 
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }              
                    }
                    else{
                        // console.log(error.response.statusText)
                        Swal("Error",""+error,"error");
                    }
                    setLoadingDownload(false)
                }
                else{
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        setLoadingDownload(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingDownload(false)
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
        getDownload();

        return ()=>{
            doneloading=false;
        }
    }

    const downloadWaterbillCurYear = () =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingDownload(true)
        }
        const getDownload = async (e) => { 
            let url='/v2/downloads/Reports/Waterbill/'+propertyid+'/Year/'+currentyear;
            await axios.get(url,{
                responseType: 'blob',
            })
            .then(response=>{
                if (doneloading) {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    const filename=property+' Water Bill for '+ currentyear+' .xlsx';
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link); // Clean up
                    setLoadingDownload(false)
                    Swal("Success","Your File Has been Successfully sent to your browser");
                }
            })
            .catch(error=>{
                if(!localStorage.getItem("auth_token")){
                    // let ex=error['response'].data.message;
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        if(!localStorage.getItem("auth_token")){
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                        }  
                        else{ 
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }              
                    }
                    else{
                        // console.log(error.response.statusText)
                        Swal("Error",""+error,"error");
                    }
                    setLoadingDownload(false)
                }
                else{
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        setLoadingDownload(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingDownload(false)
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
        getDownload();

        return ()=>{
            doneloading=false;
        }
    }

    const downloadWaterbillNextYear = () =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingDownload(true)
        }
        const getDownload = async (e) => { 
            {/* href={process.env.REACT_APP_BACKEND_API_URL+'/properties/downloads/Reports/Waterbill/'+propertyid+'/Year/'+currentyearup} */}
            let url='/v2/downloads/Reports/Waterbill/'+propertyid+'/Year/'+currentyearup;
            await axios.get(url,{
                responseType: 'blob',
            })
            .then(response=>{
                if (doneloading) {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    const filename=property+' Water Bill for '+ currentyearup+' .xlsx';
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link); // Clean up
                    setLoadingDownload(false)
                    Swal("Success","Your File Has been Successfully sent to your browser");
                }
            })
            .catch(error=>{
                if(!localStorage.getItem("auth_token")){
                    // let ex=error['response'].data.message;
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        if(!localStorage.getItem("auth_token")){
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                        }  
                        else{ 
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }              
                    }
                    else{
                        // console.log(error.response.statusText)
                        Swal("Error",""+error,"error");
                    }
                    setLoadingDownload(false)
                }
                else{
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        setLoadingDownload(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingDownload(false)
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
        getDownload();

        return ()=>{
            doneloading=false;
        }
    }

    

  return (
    <>
        <Modal show={show} onHide={handleClose} className="mt-4">
            <Modal.Header className='justify-content-center bg-info m-0 p-2' closeButton>
                <Modal.Title className='mx-auto text-white'> 
                    <h5>Download {property} Waterbill </h5>
                </Modal.Title>
            </Modal.Header>
            <Modal.Body>
                <div className="card-box-links m-0 p-1 justify-content-center text-center">
                    <div className="row m-0 p-0 justify-content-center text-center">
                        
                        {loadingdownload && 
                            <div className="col-md-12 text-center text-white">
                                <span className='text-black' style={{"padding": "10px","display":"inline-block"}}>Please Wait ...</span>
                                <br/>
                                <Spinner
                                    as="span"
                                    variant='info'
                                    animation="border"
                                    size="lg"
                                    role="status"
                                    aria-hidden="true"
                                />
                                <span className='text-info p-4' style={{"padding": "10px","display":"inline-block"}}>Preparing File...</span>
                                    
                            </div>
                        }

                        {!loadingdownload &&   
                            <>
                                <div className='col-12 p-0 m-1'>
                                    <a target='_blank' onClick={()=>{downloadWaterbill()}}   className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {property} Waterbill for {!loadingmonths && prevmonths && prevmonths[selectedmonth].monthname} </small> </i> 
                                    </a>
                                </div>

                                <div className='col-12 p-0 m-1'>
                                    <a target='_blank' onClick={()=>{downloadWaterbillAll()}} className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> All Properties Waterbill for {!loadingmonths && prevmonths && prevmonths[selectedmonth].monthname}</small> </i>
                                    </a>
                                </div>
                                
                                {!loadingwater && currentyearup != '' &&
                                    <div className='col-12 p-0 m-1'>
                                        {/* href={process.env.REACT_APP_BACKEND_API_URL+'/properties/downloads/Reports/Waterbill/'+propertyid+'/Year/'+currentyearup} */}
                                        <a target='_blank' onClick={()=>{downloadWaterbillNextYear()}}  className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {currentyearup} Waterbill</small> </i>
                                        </a>
                                    </div>
                                }

                                {!loadingwater && currentyear != '' &&
                                    <div className='col-12 p-0 m-1'>
                                        {/* href={process.env.REACT_APP_BACKEND_API_URL+'/properties/downloads/Reports/Waterbill/'+propertyid+'/Year/'+currentyear} */}
                                        <a target='_blank' onClick={()=>{downloadWaterbillCurYear()}}  className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {currentyear} Waterbill</small> </i>
                                        </a>
                                    </div>
                                }
                                {!loadingwater && currentyeardown != '' &&
                                    <div className='col-12 p-0 m-1'>
                                        {/* href={process.env.REACT_APP_BACKEND_API_URL+'/properties/downloads/Reports/Waterbill/'+propertyid+'/Year/'+currentyeardown} */}
                                        <a target='_blank' onClick={()=>{downloadWaterbillPrevYear()}}  className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {currentyeardown} Waterbill</small> </i>
                                        </a>
                                    </div>
                                }
                            </>  
                        }                                      
                        {/* href={process.env.REACT_APP_BACKEND_API_URL+'/properties/downloads/Reports/Waterbill/'+propertyid+'/'+ (!loadingmonths && prevmonths && prevmonths[selectedmonth].month)} */}
                        
                    </div>
                </div>
            </Modal.Body>
            <Modal.Footer className='justify-content-center bg-light'>
                <Button variant='secondary' onClick={handleClose}>
                    Close
                </Button>
            </Modal.Footer>
        </Modal>

    </>
  );
}

export default DownloadWaterbillModal;