import { useEffect, useContext, useState } from 'react';
import Spinner from 'react-bootstrap/Spinner';

import Modal from 'react-bootstrap/Modal';

import axios from 'axios';

import Swal from 'sweetalert';
import ReLogin from '../home/ReLogin';
import { LoginContext } from '../contexts/LoginContext';


function AddWaterbill({show,handleClose,currentwaterbill ,setMonth,loadWaterbill}) {
    document.title=(currentwaterbill.present==='Yes')?'Update Waterbill for '+currentwaterbill.housename:'Add Waterbill for '+currentwaterbill.housename;
    // console.log(currentwaterbill)
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
      
    const [loggedoff,setLoggedOff]=useState(false);
    const [loading,setLoading]=useState(false);

    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');
    const [sendinfo,setSendInfo]=useState({
        month:'',
        message:''
    })
   
    const [formdata,setFormData]=useState({
        previous:currentwaterbill.previous,
        current:currentwaterbill.current,
        cost:currentwaterbill.cost,
        units:currentwaterbill.units,
        total:currentwaterbill.total,
        error_list:[],
    });
        
    const handleCurrentChange=(e)=>{
        e.persist();
        const previous=formdata.previous;
        const current=e.target.value;
        const cost=formdata.cost;

        const units=(current)-(previous);
        const total=(units)*(cost);

        if(!isNaN(current)){
            setFormData({
                previous:formdata.previous,
                current:e.target.value,
                cost:formdata.cost,
                units:units,
                total:total,
            })
        }
    }

    const handlePreviousChange=(e)=>{
        e.persist();
        const previous=e.target.value;
        const current=formdata.current;
        const cost=formdata.cost;

        const units=(current)-(previous);
        const total=(units)*(cost);

        if(!isNaN(previous)){
            setFormData({
                previous:e.target.value,
                current:formdata.current,
                cost:formdata.cost,
                units:units,
                total:total,
            })
        }
    }

    const handleCostChange=(e)=>{
        e.persist();
        const previous=formdata.previous;
        const current=formdata.current;

        const cost=e.target.value;

        const units=(current)-(previous);
        const total=(units)*(cost);

        if(!isNaN(cost)){
            setFormData({
                previous:formdata.previous,
                current:formdata.current,
                cost:e.target.value,
                units:units,
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
            Previous:formdata.previous,
            Current:formdata.current,
            Cost:new Number(formdata.cost),
            Units:formdata.units,
            Total:formdata.total,
            Total_OS:currentwaterbill.total_os,
            month:currentwaterbill.month,
            Tenant:currentwaterbill.tid,
            pid:currentwaterbill.pid,
            hid:currentwaterbill.id,
            waterid:currentwaterbill.waterid,
        }
        // setSendInfo({
        //     month:'',
        //     message:''
        // })

        let text='';
        let title='Are you sure to '+(currentwaterbill.present==='Yes'?'Update':'Add');
        if(currentwaterbill.present==='Yes'){
            text="New Changes for "+currentwaterbill.monthname+"( "+currentwaterbill.housename+" ) will be :"+
                (currentwaterbill.previous!==formdata.previous?"\n Previous :: "+currentwaterbill.previous+" > "+formdata.previous:'')+
                (currentwaterbill.current!==formdata.current?"\n Current :: "+currentwaterbill.current+" > "+formdata.current:'')+
                (currentwaterbill.units!==formdata.units?"\n Units :: "+currentwaterbill.units+" > "+formdata.units:'')+
                (currentwaterbill.cost!==formdata.cost?"\n Cost :: "+currentwaterbill.cost+" > "+formdata.cost:'')+
                ((currentwaterbill.previous!==formdata.previous) || (currentwaterbill.current!==formdata.current)?"\n New Waterbill :: "+formdata.previous+" : "+formdata.current:'')+
                (currentwaterbill.total!==formdata.total?"\n Total Kshs. :: "+new Number(currentwaterbill.total).toFixed(2)+" > "+new Number(formdata.total).toFixed(2):'');
        }
        else{
            text="New Changes for "+currentwaterbill.monthname+"( "+currentwaterbill.housename+" ) will be :"+
                (currentwaterbill.previous!==formdata.previous?"\n Previous :: "+currentwaterbill.previous+" > "+formdata.previous:'')+
                (currentwaterbill.current!==formdata.current?"\n Current :: "+currentwaterbill.previous+" > "+formdata.current:'')+
                (currentwaterbill.units!==formdata.units?"\n Units :: "+currentwaterbill.units+" > "+formdata.units:'')+
                (currentwaterbill.cost!==formdata.cost?"\n Cost :: "+currentwaterbill.cost+" > "+formdata.cost:'')+
                ((currentwaterbill.previous!==formdata.previous) || (currentwaterbill.current!==formdata.current)?"\n New Waterbill :: "+formdata.previous+" : "+formdata.current:'')+
                (currentwaterbill.total!==formdata.total?"\n Total Kshs. :: "+new Number(currentwaterbill.total).toFixed(2)+" > "+new Number(formdata.total).toFixed(2):'');
        }
        
        let url='';
        if(currentwaterbill.present==='Yes'){
            url='/v2/update/waterbill/save';
        }
        else{
            url='/v2/save/waterbill/save';
        }

        if(text.toLowerCase()===("New Changes for "+currentwaterbill.monthname+"( "+currentwaterbill.housename+" ) will be :").toLowerCase()){
            Swal(currentwaterbill.present==='Yes'?currentwaterbill.monthname+' Waterbill for '+currentwaterbill.housename:currentwaterbill.monthname+' Waterbill for '+currentwaterbill.housename,"You have not made any changes.");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else{
            Swal({
                title:title+' this waterbill ?',
                text:text,
                buttons:true,
                infoMode:true,
            })
            .then((willcontinue) =>{
                if(willcontinue){
                    axios.post(url,form)
                    .then(response=>{
                        if(response.data.status=== 200){
                            setMonth(currentwaterbill.month);
                            let thissendinfo={
                                month:currentwaterbill.month,
                                message:response.data.message
                            }
                            if(socket) {
                                    socket.emit('waterbill_added',thissendinfo);
                            }
                            loadWaterbill();
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
        {(currentwaterbill.present==='Yes')?
            <>
                <Modal.Header className='justify-content-center bg-warning m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-dark'> 
                        <h5>{(currentwaterbill.present==='Yes')?'Update':'Add'} Waterbill for : {currentwaterbill.housename} ({currentwaterbill.tenantname})</h5>
                    </Modal.Title>
                </Modal.Header>
            </>
            :
            <>
                <Modal.Header className='justify-content-center bg-info m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-white'> 
                        <h5>{(currentwaterbill.present==='Yes')?'Update':'Add'} Waterbill for : {currentwaterbill.housename} ({currentwaterbill.tenantname})</h5>
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
                                    <label htmlFor="previous" className="col-4 col-form-label text-md-right">Previous</label>
                                    <div className="col-8">
                                        <input id="previous" type="text" className="form-control" name="previous" placeholder="Previous" value={formdata.previous} onChange={handlePreviousChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.previous && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.previous}</strong>
                                            </span>
                                        }
                                        
                                    </div>
                                </div>
                                <div className='row m-0 p-1'>
                                    <label htmlFor="current" className="col-4 col-form-label text-md-right">Current</label>
                                    <div className="col-8">
                                        <input id="current" type="text" className="form-control" name="current" placeholder="Current" value={formdata.current} onChange={handleCurrentChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.current && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.current}</strong>
                                            </span>
                                        }
                                        
                                    </div>
                                </div>
                                <div className='row m-0 p-1'>
                                    <label htmlFor="cost" className="col-4 col-form-label text-md-right">Cost</label>
                                    <div className="col-8">
                                        <input id="cost" type="text" className="form-control" name="cost" placeholder="Cost" value={new Number(formdata.cost).toFixed(2)} onChange={handleCostChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.cost && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.cost}</strong>
                                            </span>
                                        }
                                        
                                    </div>
                                </div>
                            
                                <div className='row m-0 p-1'>
                                    <label htmlFor="units" className="col-4 col-form-label text-md-right">Units</label>
                                    <div className="col-8">
                                        <input id="units" type="text" className="form-control border-none" name="units" readOnly value={(formdata.units)} autoFocus/>
                                        {formdata.error_list && formdata.error_list.units && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.units}</strong>
                                            </span>
                                        }
                                        
                                    </div>
                                </div>
                                <div className='row m-0 p-1'>
                                    <label htmlFor="total" className="col-4 col-form-label text-md-right">Total(<small>Kshs</small>)</label>
                                    <div className="col-8">
                                        <input id="total" type="text" className="form-control border-none" name="total" readOnly value={new Number((formdata.cost)*(formdata.units)).toFixed(2)} autoFocus/>
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
                                            {(currentwaterbill.present==='Yes')?'Updating':'Adding'} ...</span>
                                            
                                    </div>
                                }

                                {!loading && loadingresok ==='' && 
                                    <div className="col-md-10">
                                        <button type="submit" className="btn btn-primary">
                                        {(currentwaterbill.present==='Yes')?'Update ':'Save '} {currentwaterbill.monthname} Waterbill for {currentwaterbill.housename}
                                        </button>
                                    </div>
                                }

                                {loadingres!=='' && 
                                    <div className="col-md-10 elevation-0 mt-2 p-2 text-center border-error">
                                        <span className="help-block text-danger">
                                            <strong>{loadingres!=='' && loadingres}</strong>
                                        </span>
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

export default AddWaterbill;