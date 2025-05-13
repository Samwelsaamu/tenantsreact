import { useEffect, useContext, useState } from 'react';
import Spinner from 'react-bootstrap/Spinner';

import Modal from 'react-bootstrap/Modal';

import axios from 'axios';

import Swal from 'sweetalert';
import ReLogin from '../home/ReLogin';
import { LoginContext } from '../contexts/LoginContext';


function AddRentGarbage({show,handleClose,currentwaterbill,loadRentGarbage}) {
    document.title=(currentwaterbill.saved==='Yes')?'Update Rent & Garbage for '+currentwaterbill.housename:'Add Rent & Garbage for '+currentwaterbill.housename;
    // console.log(currentwaterbill)
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
      
    const [loggedoff,setLoggedOff]=useState(false);
    const [loading,setLoading]=useState(false);

    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');

   
    const [formdata,setFormData]=useState({
        Rent:currentwaterbill.Rent,
        Garbage:currentwaterbill.Garbage,
        total:(currentwaterbill.Rent+currentwaterbill.Garbage),
        error_list:[],
    });
    
        // useEffect( () =>{
        //     socket.on('load_credit_balance', (msg) =>{
        //         loadAppData();
        //     })
    
        // }, []);
    
  
    const handleGarbageChange=(e)=>{
        e.persist();
        const Rent=formdata.Rent;
        const Garbage=e.target.value;

        if(!isNaN(Garbage)){
            let total=((new Number(Rent)) + (new Number(Garbage)));
            setFormData({
                Rent:formdata.Rent,
                Garbage:e.target.value,
                total:total,
            })
        }
    }

    const handleRentChange=(e)=>{
        e.persist();
        const Rent=e.target.value;
        const Garbage=formdata.Garbage;

        if(!isNaN(Rent)){
            let total=((new Number(Rent)) + (new Number(Garbage)));
            setFormData({
                Rent:e.target.value,
                Garbage:formdata.Garbage,
                total:total,
            })
        }
    }

    

    const submitWaterbill= (e)=>{
        e.preventDefault();
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")
        
        const form={
            Rent:formdata.Rent,
            Garbage:formdata.Garbage,
            Total:formdata.total,
            month:currentwaterbill.month,
            Tenant:currentwaterbill.tid,
            pid:currentwaterbill.pid,
            hid:currentwaterbill.id,
            paymentid:currentwaterbill.paymentid,
        }

        let text='';
        let title='Are you sure to '+(currentwaterbill.saved==='Yes'?'Update':'Add');
        if(currentwaterbill.saved==='Yes'){
            text="New Changes for "+currentwaterbill.monthname+"( "+currentwaterbill.housename+" ) will be :"+
                (currentwaterbill.Rent!==formdata.Rent?"\n Rent :: "+currentwaterbill.Rent+" > "+formdata.Rent:'')+
                (currentwaterbill.Garbage!==formdata.Garbage?"\n Garbage :: "+currentwaterbill.Garbage+" > "+formdata.Garbage:'')+
                ((currentwaterbill.Rent!==formdata.Rent) || (currentwaterbill.Garbage!==formdata.Garbage)?"\n New Rent & Garbage :: "+formdata.Rent+" : "+formdata.Garbage:'')+
                (currentwaterbill.total!==formdata.total?"\n Total Kshs. :: "+new Number(currentwaterbill.total).toFixed(2)+" > "+new Number(formdata.total).toFixed(2):'');
        }
        else{
            text="New Changes for "+currentwaterbill.monthname+"( "+currentwaterbill.housename+" ) will be :"+
                (currentwaterbill.Rent!==formdata.Rent?"\n Rent :: "+currentwaterbill.Rent+" > "+formdata.Rent:'')+
                (currentwaterbill.Garbage!==formdata.Garbage?"\n Garbage :: "+currentwaterbill.Garbage+" > "+formdata.Garbage:'')+
                ((currentwaterbill.Rent!==formdata.Rent) || (currentwaterbill.Garbage!==formdata.Garbage)?"\n New Rent & Garbage :: "+formdata.Rent+" : "+formdata.Garbage:'')+
                (currentwaterbill.total!==formdata.total?"\n Total Kshs. :: "+new Number(currentwaterbill.total).toFixed(2)+" > "+new Number(formdata.total).toFixed(2):'');
        }
        
        let url='';
        if(currentwaterbill.saved==='Yes'){
            url='/v2/update/rentgarbage/save';
        }
        else{
            url='/v2/save/rentgarbage/save';
        }

        if(text.toLowerCase()===("New Changes for "+currentwaterbill.monthname+"( "+currentwaterbill.housename+" ) will be :").toLowerCase()){
            Swal(currentwaterbill.saved==='Yes'?currentwaterbill.monthname+' Rent & Garbage for '+currentwaterbill.housename:currentwaterbill.monthname+' Rent & Garbage for '+currentwaterbill.housename,"You have not made any changes.");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else{
            Swal({
                title:title+' this Rent & Garbage ?',
                text:text,
                buttons:true,
                infoMode:true,
            })
            .then((willcontinue) =>{
                if(willcontinue){
                    axios.post(url,form)
                    .then(response=>{
                        if(response.data.status=== 200){
                            loadRentGarbage();
                            setLoadingResOk(response.data.message)
                            setLoadingRes("")
                        }
                        else if(response.data.status=== 401){
                            // setLoggedOff(true);    
                            setLoadingRes(response.data.message)
                            setLoadingResOk("")
                        }
                        else if(response.data.status=== 500){
                            setLoadingRes(response.data.message)
                            setLoadingResOk("")
                        }
                        setLoading(false);
    
                    })
                    .catch((error)=>{
                        // if(error.message==="Request failed with status code 401"){
                        //     setLoggedOff(true);                    
                        // }
                        // else if(error.message==="Request failed with status code 500"){
                        //     setLoggedOff(true);                    
                        // }
                        setLoadingRes(""+error)
                        setLoadingResOk("")
                        setLoading(false);
                    })
                }
                else{
                    setLoadingRes("")
                    setLoadingResOk("")
                    setLoading(false);
                }
            })
        }

        

    }


  return (
    <>
    
        <Modal size='lg' show={show} onHide={handleClose} className="mt-4">
        {(currentwaterbill.saved==='Yes')?
            <>
                <Modal.Header className='justify-content-center bg-warning m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-dark'> 
                        <h5>{(currentwaterbill.saved==='Yes')?'Update Rent & Garbage for '+currentwaterbill.housename:'Add Rent & Garbage for '+currentwaterbill.housename} ({currentwaterbill.tenantname})</h5>
                        {/* {(currentwaterbill.present==='Yes')?'Update':'Add'} Waterbill for : {currentwaterbill.housename} ({currentwaterbill.tenantname})</h5> */}
                    </Modal.Title>
                </Modal.Header>
            </>
            :
            <>
                <Modal.Header className='justify-content-center bg-info m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-white'> 
                    <h5>{(currentwaterbill.saved==='Yes')?'Update Rent & Garbage for '+currentwaterbill.housename:'Add Rent & Garbage for '+currentwaterbill.housename} ({currentwaterbill.tenantname})</h5>
                        {/* <h5>{(currentwaterbill.present==='Yes')?'Update':'Add'} Waterbill for : {currentwaterbill.housename} ({currentwaterbill.tenantname})</h5> */}
                    </Modal.Title>
                </Modal.Header>
            </>
            }
            
            
            <Modal.Body className='m-0 p-0'>
                    {loggedoff ? 
                        <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
                    :
                    <div className="card-box-links m-0 p-0 justify-content-center text-center">
                    <div className="row m-1 p-0 justify-content-center text-center border-none">
                        <form onSubmit={submitWaterbill}>
                            <div className="form-group row m-0 p-2 border-none">
                                <div className='row m-0 p-1'>
                                    <label htmlFor="Rent" className="col-4 col-form-label text-md-right">Rent</label>
                                    <div className="col-8">
                                        <input id="Rent" type="text" className="form-control" name="Rent" placeholder="Rent" value={formdata.Rent} onChange={handleRentChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Rent && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Rent}</strong>
                                            </span>
                                        }
                                        
                                    </div>
                                </div>
                                <div className='row m-0 p-1'>
                                    <label htmlFor="Garbage" className="col-4 col-form-label text-md-right">Garbage</label>
                                    <div className="col-8">
                                        <input id="Garbage" type="text" className="form-control" name="Garbage" placeholder="Garbage" value={formdata.Garbage} onChange={handleGarbageChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Garbage && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Garbage}</strong>
                                            </span>
                                        }
                                        
                                    </div>
                                </div>
                                
                                <div className='row m-0 p-1'>
                                    <label htmlFor="total" className="col-4 col-form-label text-md-right">Total(<small>Kshs</small>)</label>
                                    <div className="col-8">
                                        <input id="total" type="text" className="form-control border-none" name="total" readOnly value={new Number(new Number(formdata.Rent)+new Number(formdata.Garbage)).toFixed(2)} autoFocus/>
                                        {formdata.error_list && formdata.error_list.total && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.total}</strong>
                                            </span>
                                        }
                                        
                                    </div>
                                </div>
                                
                                
                            </div>

                                        
                            <hr className='text-danger' style={{"border":"1px solid blue"}} />
                            <div className="form-group row mb-0 justify-content-center m-1 mt-4 p-2 border-none">
                                
                                {loadingresok!=='' && 
                                    <div className="col-md-10 elevation-0 mb-2 p-2 text-center border-ok">
                                        <span className="help-block text-success">
                                            <strong>{loadingresok!=='' && loadingresok}</strong>
                                        </span>
                                    </div>
                                }

                                {loading && 
                                    <div className="col-md-12 text-center text-white">
                                        {/* <Button className='btn-block'  disabled> */}
                                            <Spinner
                                            as="span"
                                            variant='info'
                                            animation="border"
                                            size="lg"
                                            role="status"
                                            aria-hidden="true"
                                            />
                                            <span className='text-info' style={{"padding": "10px","display":"inline-block"}}>
                                            {(currentwaterbill.saved==='Yes')?'Updating':'Adding'} ...</span>
                                            
                                    </div>
                                }

                                {loadingres!=='' && 
                                    <div className="col-md-10 elevation-0 mb-2 p-0 text-center">
                                        <span className="help-block text-danger">
                                            <strong>{loadingres!=='' && loadingres}</strong>
                                        </span>
                                    </div>
                                }

                                {!loading && loadingresok ==='' && 
                                    <div className="col-md-10">
                                        <button type="submit" className="btn btn-primary">
                                        {(currentwaterbill.saved==='Yes')?'Update ':'Save '} {currentwaterbill.monthname} Rent & Garbage for {currentwaterbill.housename}
                                        </button>
                                    </div>
                                }

                                
                            </div>
                            </form>
                    </div>
                </div>
                }
            </Modal.Body>
            
        </Modal>

    </>
  );
}

export default AddRentGarbage;