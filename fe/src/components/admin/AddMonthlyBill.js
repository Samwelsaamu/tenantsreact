import { useEffect, useContext, useState } from 'react';
import Spinner from 'react-bootstrap/Spinner';

import Modal from 'react-bootstrap/Modal';

import axios from 'axios';

import Swal from 'sweetalert';
import ReLogin from '../home/ReLogin';
import { LoginContext } from '../contexts/LoginContext';


function AddMonthlyBill({show,handleClose,currentwaterbill,loadRentGarbage}) {
    document.title=(currentwaterbill.saved==='Yes')?'Update Monthly Bill for '+currentwaterbill.housename:'Add Monthly Bill for '+currentwaterbill.housename;
    // console.log(currentwaterbill)
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
      
    const [loggedoff,setLoggedOff]=useState(false);
    const [loading,setLoading]=useState(false);

    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');

   
    const [formdata,setFormData]=useState({
        Rent:currentwaterbill.Rent,
        Garbage:currentwaterbill.Garbage,
        HseDeposit:currentwaterbill.HseDeposit,
        Water:currentwaterbill.Water,
        Arrears:currentwaterbill.Arrears,
        Excess:currentwaterbill.Excess,
        Waterbill:currentwaterbill.Waterbill,
        KPLC:currentwaterbill.KPLC,
        Lease:currentwaterbill.Lease,
        total:(currentwaterbill.Rent+currentwaterbill.Garbage+currentwaterbill.Arrears+currentwaterbill.HseDeposit+currentwaterbill.Water+currentwaterbill.Excess+currentwaterbill.Waterbill+currentwaterbill.KPLC+currentwaterbill.Lease),
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
        const HseDeposit=formdata.HseDeposit;
        const Water=formdata.Water;
        const Arrears=formdata.Arrears;
        const Excess=formdata.Excess;
        const Waterbill=formdata.Waterbill;
        const KPLC=formdata.KPLC;
        const Lease=formdata.Lease;

        const Garbage=e.target.value;

        if(!isNaN(Garbage)){
            let total=(((new Number(Rent)) + (new Number(Garbage)) + (new Number(HseDeposit)) + (new Number(Water)) + (new Number(Arrears)) + (new Number(Lease)) + (new Number(Waterbill)) + (new Number(KPLC))) - (new Number(Excess)));
            setFormData({
                Rent:formdata.Rent,
                HseDeposit:formdata.HseDeposit,
                Water:formdata.Water,
                Arrears:formdata.Arrears,
                Excess:formdata.Excess,
                Waterbill:formdata.Waterbill,
                KPLC:formdata.KPLC,
                Lease:formdata.Lease,
                Garbage:e.target.value,
                total:total,
            })
        }
    }

    const handleRentChange=(e)=>{
        e.persist();
        const Rent=e.target.value;

        const HseDeposit=formdata.HseDeposit;
        const Water=formdata.Water;
        const Arrears=formdata.Arrears;
        const Excess=formdata.Excess;
        const Waterbill=formdata.Waterbill;
        const KPLC=formdata.KPLC;
        const Lease=formdata.Lease;
        const Garbage=formdata.Garbage;

        if(!isNaN(Rent)){
            let total=(((new Number(Rent)) + (new Number(Garbage)) + (new Number(HseDeposit)) + (new Number(Water)) + (new Number(Arrears)) + (new Number(Lease)) + (new Number(Waterbill)) + (new Number(KPLC))) - (new Number(Excess)));
            setFormData({
                Rent:e.target.value,
                HseDeposit:formdata.HseDeposit,
                Water:formdata.Water,
                Arrears:formdata.Arrears,
                Excess:formdata.Excess,
                Waterbill:formdata.Waterbill,
                KPLC:formdata.KPLC,
                Lease:formdata.Lease,
                Garbage:formdata.Garbage,
                total:total,
            })
        }
    }

    const handleArrearsChange=(e)=>{
        e.persist();
        const Arrears=e.target.value;

        const Rent=formdata.Rent;
        const Water=formdata.Water;
        const HseDeposit=formdata.HseDeposit;
        const Excess=formdata.Excess;
        const Waterbill=formdata.Waterbill;
        const KPLC=formdata.KPLC;
        const Lease=formdata.Lease;
        const Garbage=formdata.Garbage;

        if(!isNaN(Arrears)){
            let total=(((new Number(Rent)) + (new Number(Garbage)) + (new Number(HseDeposit)) + (new Number(Water)) + (new Number(Arrears)) + (new Number(Lease)) + (new Number(Waterbill)) + (new Number(KPLC))) - (new Number(Excess)));
            setFormData({
                Arrears:e.target.value,
                Rent:formdata.Rent,
                Water:formdata.Water,
                HseDeposit:formdata.HseDeposit,
                Excess:formdata.Excess,
                Waterbill:formdata.Waterbill,
                KPLC:formdata.KPLC,
                Lease:formdata.Lease,
                Garbage:formdata.Garbage,
                total:total,
            })
        }
    }

    const handleExcessChange=(e)=>{
        e.persist();
        const Excess=e.target.value;

        const Rent=formdata.Rent;
        const Water=formdata.Water;
        const Arrears=formdata.Arrears;
        const HseDeposit=formdata.HseDeposit;
        const Waterbill=formdata.Waterbill;
        const KPLC=formdata.KPLC;
        const Lease=formdata.Lease;
        const Garbage=formdata.Garbage;

        if(!isNaN(Excess)){
            let total=(((new Number(Rent)) + (new Number(Garbage)) + (new Number(HseDeposit)) + (new Number(Water)) + (new Number(Arrears)) + (new Number(Lease)) + (new Number(Waterbill)) + (new Number(KPLC))) - (new Number(Excess)));
            setFormData({
                Excess:e.target.value,
                Rent:formdata.Rent,
                Water:formdata.Water,
                Arrears:formdata.Arrears,
                HseDeposit:formdata.HseDeposit,
                Waterbill:formdata.Waterbill,
                KPLC:formdata.KPLC,
                Lease:formdata.Lease,
                Garbage:formdata.Garbage,
                total:total,
            })
        }
    }
    const handleWaterbillChange=(e)=>{
        e.persist();
        const Waterbill=e.target.value;

        const Rent=formdata.Rent;
        const Water=formdata.Water;
        const Arrears=formdata.Arrears;
        const Excess=formdata.Excess;
        const HseDeposit=formdata.HseDeposit;
        const KPLC=formdata.KPLC;
        const Lease=formdata.Lease;
        const Garbage=formdata.Garbage;

        if(!isNaN(Waterbill)){
            let total=(((new Number(Rent)) + (new Number(Garbage)) + (new Number(HseDeposit)) + (new Number(Water)) + (new Number(Arrears)) + (new Number(Lease)) + (new Number(Waterbill)) + (new Number(KPLC))) - (new Number(Excess)));
            setFormData({
                Waterbill:e.target.value,
                Rent:formdata.Rent,
                Water:formdata.Water,
                Arrears:formdata.Arrears,
                Excess:formdata.Excess,
                HseDeposit:formdata.HseDeposit,
                KPLC:formdata.KPLC,
                Lease:formdata.Lease,
                Garbage:formdata.Garbage,
                total:total,
            })
        }
    }
    const handleHseDepositChange=(e)=>{
        e.persist();
        const HseDeposit=e.target.value;

        const Rent=formdata.Rent;
        const Water=formdata.Water;
        const Arrears=formdata.Arrears;
        const Excess=formdata.Excess;
        const Waterbill=formdata.Waterbill;
        const KPLC=formdata.KPLC;
        const Lease=formdata.Lease;
        const Garbage=formdata.Garbage;

        if(!isNaN(HseDeposit)){
            let total=(((new Number(Rent)) + (new Number(Garbage)) + (new Number(HseDeposit)) + (new Number(Water)) + (new Number(Arrears)) + (new Number(Lease)) + (new Number(Waterbill)) + (new Number(KPLC))) - (new Number(Excess)));
            setFormData({
                HseDeposit:e.target.value,
                Rent:formdata.Rent,
                Water:formdata.Water,
                Arrears:formdata.Arrears,
                Excess:formdata.Excess,
                Waterbill:formdata.Waterbill,
                KPLC:formdata.KPLC,
                Lease:formdata.Lease,
                Garbage:formdata.Garbage,
                total:total,
            })
        }
    }
    const handleKPLCChange=(e)=>{
        e.persist();
        const KPLC=e.target.value;

        const Rent=formdata.Rent;
        const Water=formdata.Water;
        const Arrears=formdata.Arrears;
        const Excess=formdata.Excess;
        const Waterbill=formdata.Waterbill;
        const HseDeposit=formdata.HseDeposit;
        const Lease=formdata.Lease;
        const Garbage=formdata.Garbage;

        if(!isNaN(KPLC)){
            let total=(((new Number(Rent)) + (new Number(Garbage)) + (new Number(HseDeposit)) + (new Number(Water)) + (new Number(Arrears)) + (new Number(Lease)) + (new Number(Waterbill)) + (new Number(KPLC))) - (new Number(Excess)));
            setFormData({
                KPLC:e.target.value,
                Rent:formdata.Rent,
                Water:formdata.Water,
                Arrears:formdata.Arrears,
                Excess:formdata.Excess,
                Waterbill:formdata.Waterbill,
                HseDeposit:formdata.HseDeposit,
                Lease:formdata.Lease,
                Garbage:formdata.Garbage,
                total:total,
            })
        }
    }
    const handleWaterChange=(e)=>{
        e.persist();
        const Water=e.target.value;

        const Rent=formdata.Rent;
        const HseDeposit=formdata.HseDeposit;
        const Arrears=formdata.Arrears;
        const Excess=formdata.Excess;
        const Waterbill=formdata.Waterbill;
        const KPLC=formdata.KPLC;
        const Lease=formdata.Lease;
        const Garbage=formdata.Garbage;

        if(!isNaN(Water)){
            let total=(((new Number(Rent)) + (new Number(Garbage)) + (new Number(HseDeposit)) + (new Number(Water)) + (new Number(Arrears)) + (new Number(Lease)) + (new Number(Waterbill)) + (new Number(KPLC))) - (new Number(Excess)));
            setFormData({
                Water:e.target.value,
                Rent:formdata.Rent,
                HseDeposit:formdata.HseDeposit,
                Arrears:formdata.Arrears,
                Excess:formdata.Excess,
                Waterbill:formdata.Waterbill,
                KPLC:formdata.KPLC,
                Lease:formdata.Lease,
                Garbage:formdata.Garbage,
                total:total,
            })
        }
    }
    const handleLeaseChange=(e)=>{
        e.persist();
        const Lease=e.target.value;

        const Rent=formdata.Rent;
        const Water=formdata.Water;
        const Arrears=formdata.Arrears;
        const Excess=formdata.Excess;
        const Waterbill=formdata.Waterbill;
        const KPLC=formdata.KPLC;
        const HseDeposit=formdata.HseDeposit;
        const Garbage=formdata.Garbage;

        if(!isNaN(Lease)){
            let total=(((new Number(Rent)) + (new Number(Garbage)) + (new Number(HseDeposit)) + (new Number(Water)) + (new Number(Arrears)) + (new Number(Lease)) + (new Number(Waterbill)) + (new Number(KPLC))) - (new Number(Excess)));
            setFormData({
                Lease:e.target.value,
                Rent:formdata.Rent,
                Water:formdata.Water,
                Arrears:formdata.Arrears,
                Excess:formdata.Excess,
                Waterbill:formdata.Waterbill,
                KPLC:formdata.KPLC,
                HseDeposit:formdata.HseDeposit,
                Garbage:formdata.Garbage,
                total:total,
            })
        }
    }
    // const handleHseDepositChange=(e)=>{
    //     e.persist();
    //     const HseDeposit=e.target.value;

    //     const Rent=formdata.Rent;
    //     const Water=formdata.Water;
    //     const Arrears=formdata.Arrears;
    //     const Excess=formdata.Excess;
    //     const Waterbill=formdata.Waterbill;
    //     const KPLC=formdata.KPLC;
    //     const Lease=formdata.Lease;
    //     const Garbage=formdata.Garbage;

    //     if(!isNaN(HseDeposit)){
    //         let total=((new Number(Rent)) + (new Number(Garbage)) + (new Number(HseDeposit)) + (new Number(Water)) + (new Number(Arrears)) + (new Number(Excess)) + (new Number(Waterbill)) + (new Number(KPLC)) + (new Number(Lease)));
    //         setFormData({
    //             HseDeposit:e.target.value,
    //             Rent:formdata.Rent,
    //             Water:formdata.Water,
    //             Arrears:formdata.Arrears,
    //             Excess:formdata.Excess,
    //             Waterbill:formdata.Waterbill,
    //             KPLC:formdata.KPLC,
    //             Lease:formdata.Lease,
    //             Garbage:formdata.Garbage,
    //             total:total,
    //         })
    //     }
    // }
    

    const submitWaterbill= (e)=>{
        e.preventDefault();
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")
        
        const form={
            Rent:formdata.Rent,
            Garbage:formdata.Garbage,
            HseDeposit:formdata.HseDeposit,
            Water:formdata.Water,
            Arrears:formdata.Arrears,
            Excess:formdata.Excess,
            Waterbill:formdata.Waterbill,
            KPLC:formdata.KPLC,
            Lease:formdata.Lease,
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
                (currentwaterbill.HseDeposit!==formdata.HseDeposit?"\n House Deposit :: "+currentwaterbill.HseDeposit+" > "+formdata.HseDeposit:'')+
                (currentwaterbill.Water!==formdata.Water?"\n Water Deposit :: "+currentwaterbill.Water+" > "+formdata.Water:'')+
                (currentwaterbill.KPLC!==formdata.KPLC?"\n KPLC Deposit :: "+currentwaterbill.KPLC+" > "+formdata.KPLC:'')+
                (currentwaterbill.Lease!==formdata.Lease?"\n Lease :: "+currentwaterbill.Lease+" > "+formdata.Lease:'')+
                (currentwaterbill.Arrears!==formdata.Arrears?"\n Arrears :: "+currentwaterbill.Arrears+" > "+formdata.Arrears:'')+
                (currentwaterbill.Excess!==formdata.Excess?"\n Excess :: "+currentwaterbill.Excess+" > "+formdata.Excess:'')+
                ((currentwaterbill.Rent!==formdata.Rent) || (currentwaterbill.Garbage!==formdata.Garbage)
                || (currentwaterbill.HseDeposit!==formdata.HseDeposit)|| (currentwaterbill.Water!==formdata.Water)
                || (currentwaterbill.KPLC!==formdata.KPLC)
                || (currentwaterbill.Lease!==formdata.Lease)
                || (currentwaterbill.Arrears!==formdata.Arrears)
                || (currentwaterbill.Excess!==formdata.Excess)?"\n New Monthly Bill :: ":'')+
                (currentwaterbill.total!==formdata.total?"\n Total Kshs. :: "+new Number(currentwaterbill.total).toFixed(2)+" > "+new Number(formdata.total).toFixed(2):'');
        }
        else{
            text="New Changes for "+currentwaterbill.monthname+"( "+currentwaterbill.housename+" ) will be :"+
                (currentwaterbill.Rent!==formdata.Rent?"\n Rent :: "+currentwaterbill.Rent+" > "+formdata.Rent:'')+
                (currentwaterbill.Garbage!==formdata.Garbage?"\n Garbage :: "+currentwaterbill.Garbage+" > "+formdata.Garbage:'')+
                (currentwaterbill.HseDeposit!==formdata.HseDeposit?"\n House Deposit :: "+currentwaterbill.HseDeposit+" > "+formdata.HseDeposit:'')+
                (currentwaterbill.Water!==formdata.Water?"\n Water Deposit :: "+currentwaterbill.Water+" > "+formdata.Water:'')+
                (currentwaterbill.KPLC!==formdata.KPLC?"\n KPLC Deposit :: "+currentwaterbill.KPLC+" > "+formdata.KPLC:'')+
                (currentwaterbill.Lease!==formdata.Lease?"\n Lease :: "+currentwaterbill.Lease+" > "+formdata.Lease:'')+
                (currentwaterbill.Arrears!==formdata.Arrears?"\n Arrears :: "+currentwaterbill.Arrears+" > "+formdata.Arrears:'')+
                (currentwaterbill.Excess!==formdata.Excess?"\n Excess :: "+currentwaterbill.Excess+" > "+formdata.Excess:'')+
                ((currentwaterbill.Rent!==formdata.Rent) || (currentwaterbill.Garbage!==formdata.Garbage)
                || (currentwaterbill.HseDeposit!==formdata.HseDeposit)|| (currentwaterbill.Water!==formdata.Water)
                || (currentwaterbill.KPLC!==formdata.KPLC)
                || (currentwaterbill.Lease!==formdata.Lease)
                || (currentwaterbill.Arrears!==formdata.Arrears)
                || (currentwaterbill.Excess!==formdata.Excess)?"\n New Monthly Bill :: ":'')+
                (currentwaterbill.total!==formdata.total?"\n Total Kshs. :: "+new Number(currentwaterbill.total).toFixed(2)+" > "+new Number(formdata.total).toFixed(2):'');
        }
        
        let url='/v2/save/monthlybills/save';
        // if(currentwaterbill.saved==='Yes'){
        //     url='/v2/update/monthlybills/save';
        // }
        // else{
        //     url='/v2/save/monthlybills/save';
        // }

        if(text.toLowerCase()===("New Changes for "+currentwaterbill.monthname+"( "+currentwaterbill.housename+" ) will be :").toLowerCase()){
            Swal(currentwaterbill.saved==='Yes'?currentwaterbill.monthname+' Monthly Bill for '+currentwaterbill.housename:currentwaterbill.monthname+' Monthly Bill for '+currentwaterbill.housename,"You have not made any changes.");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else{
            Swal({
                title:title+' this Monthly Bill ?',
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
                            setLoggedOff(true);    
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
                        if(!localStorage.getItem("auth_token")){
                            let ex=error['response'].data.message;
                            if(ex==='Unauthenticated.'){
                                if(!localStorage.getItem("auth_token")){
                                    setLoggedOff(true); 
                                }  
                                else{ 
                                    setLoggedOff(true); 
                                    localStorage.removeItem('auth_token');
                                    localStorage.removeItem('auth_name');
                                }              
                            }
                            else{
                                setLoadingRes(""+error)
                                setLoadingResOk("")
                                setLoading(false);
                            }
                            setLoading(false);
                        }
                        else{
                            let ex=error['response'].data.message;
                            if(ex==='Unauthenticated.'){
                                setLoggedOff(true); 
                                localStorage.removeItem('auth_token');
                                localStorage.removeItem('auth_name');
                            }
                            else{
                                setLoadingRes(""+error)
                                setLoadingResOk("")
                                setLoading(false);
                            }
                        }
                        
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
                        <h5>{(currentwaterbill.saved==='Yes')?'Update Monthly Bill for '+currentwaterbill.housename:'Add Monthly Bill for '+currentwaterbill.housename} ({currentwaterbill.tenantname})</h5>
                        {/* {(currentwaterbill.present==='Yes')?'Update':'Add'} Waterbill for : {currentwaterbill.housename} ({currentwaterbill.tenantname})</h5> */}
                    </Modal.Title>
                </Modal.Header>
            </>
            :
            <>
                <Modal.Header className='justify-content-center bg-info m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-white'> 
                    <h5>{(currentwaterbill.saved==='Yes')?'Update Monthly Bill for '+currentwaterbill.housename:'Add Monthly Bill for '+currentwaterbill.housename} ({currentwaterbill.tenantname})</h5>
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
                                    <label htmlFor="Arrears" className="col-2 m-0 p-0 col-form-label text-md-left">Arrears</label>
                                    <div className="col-4">
                                        <input id="Arrears" type="text" className="form-control" name="Arrears" placeholder="Arrears" value={formdata.Arrears} onChange={handleArrearsChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Arrears && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Arrears}</strong>
                                            </span>
                                        }
                                    </div>
                                    <label htmlFor="Excess" className="col-2 m-0 p-0 col-form-label text-md-left">Excess</label>
                                    <div className="col-4">
                                        <input id="Excess" type="text" className="form-control" name="Excess" placeholder="Excess" value={formdata.Excess} onChange={handleExcessChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Excess && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Excess}</strong>
                                            </span>
                                        }
                                    </div>
                                </div>
                                {/* <div className='row m-0 p-1'>
                                    <label htmlFor="Excess" className="col-4 col-form-label text-md-right">Excess</label>
                                    <div className="col-8">
                                        <input id="Excess" type="text" className="form-control" name="Excess" placeholder="Excess" value={formdata.Excess} onChange={handleExcessChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Excess && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Excess}</strong>
                                            </span>
                                        }
                                    </div>
                                </div> */}
                                <div className='row m-0 p-1'>
                                    <label htmlFor="Rent" className="col-2 m-0 p-0 col-form-label text-md-left">Rent</label>
                                    <div className="col-4">
                                        <input id="Rent" type="text" className="form-control" name="Rent" placeholder="Rent" value={formdata.Rent} onChange={handleRentChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Rent && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Rent}</strong>
                                            </span>
                                        }
                                    </div>
                                    <label htmlFor="Garbage" className="col-2 m-0 p-0 col-form-label text-md-left">Garbage</label>
                                    <div className="col-4">
                                        <input id="Garbage" type="text" className="form-control" name="Garbage" placeholder="Garbage" value={formdata.Garbage} onChange={handleGarbageChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Garbage && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Garbage}</strong>
                                            </span>
                                        }
                                    </div>
                                </div>
                                {/* <div className='row m-0 p-1'>
                                    <label htmlFor="Garbage" className="col-4 col-form-label text-md-right">Garbage</label>
                                    <div className="col-8">
                                        <input id="Garbage" type="text" className="form-control" name="Garbage" placeholder="Garbage" value={formdata.Garbage} onChange={handleGarbageChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Garbage && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Garbage}</strong>
                                            </span>
                                        }
                                    </div>
                                </div> */}
                                <div className='row m-0 p-1'>
                                    <label htmlFor="Waterbill" className="col-2 m-0 p-0 col-form-label text-md-left">Waterbill</label>
                                    <div className="col-4">
                                        <input id="Waterbill" type="text" className="form-control" name="Waterbill" placeholder="Waterbill" disabled value={formdata.Waterbill} onChange={handleWaterbillChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Waterbill && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Waterbill}</strong>
                                            </span>
                                        }
                                    </div>
                                    <label htmlFor="HseDeposit" className="col-2 m-0 p-0 col-form-label text-md-left">House Deposit</label>
                                    <div className="col-4">
                                        <input id="HseDeposit" type="text" className="form-control" name="HseDeposit" placeholder="House Deposit" value={formdata.HseDeposit} onChange={handleHseDepositChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.HseDeposit && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.HseDeposit}</strong>
                                            </span>
                                        }
                                    </div>
                                </div>
                                {/* <div className='row m-0 p-1'>
                                    <label htmlFor="HseDeposit" className="col-4 col-form-label text-md-right">House Deposit</label>
                                    <div className="col-8">
                                        <input id="HseDeposit" type="text" className="form-control" name="HseDeposit" placeholder="House Deposit" value={formdata.HseDeposit} onChange={handleHseDepositChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.HseDeposit && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.HseDeposit}</strong>
                                            </span>
                                        }
                                    </div>
                                </div> */}
                                <div className='row m-0 p-1'>
                                    <label htmlFor="KPLC" className="col-2 m-0 p-0 col-form-label text-md-left">KPLC Deposit</label>
                                    <div className="col-4">
                                        <input id="KPLC" type="text" className="form-control" name="KPLC" placeholder="KPLC Deposit" value={formdata.KPLC} onChange={handleKPLCChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.KPLC && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.KPLC}</strong>
                                            </span>
                                        }
                                    </div>
                                    <label htmlFor="Water" className="col-2 m-0 p-0 col-form-label text-md-left">Water Deposit</label>
                                    <div className="col-4">
                                        <input id="Water" type="text" className="form-control" name="Water" placeholder="Water Deposit" value={formdata.Water} onChange={handleWaterChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Water && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Water}</strong>
                                            </span>
                                        }
                                    </div>
                                </div>
                                {/* <div className='row m-0 p-1'>
                                    <label htmlFor="Water" className="col-4 col-form-label text-md-right">Water Deposit</label>
                                    <div className="col-8">
                                        <input id="Water" type="text" className="form-control" name="Water" placeholder="Water Deposit" value={formdata.Water} onChange={handleWaterChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Water && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Water}</strong>
                                            </span>
                                        }
                                    </div>
                                </div> */}
                                <div className='row m-0 p-1'>
                                    <label htmlFor="Lease" className="col-2 m-0 p-0 col-form-label text-md-left">Lease</label>
                                    <div className="col-4">
                                        <input id="Lease" type="text" className="form-control" name="Lease" placeholder="Lease" value={formdata.Lease} onChange={handleLeaseChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Lease && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Lease}</strong>
                                            </span>
                                        }
                                    </div>
                                    <label htmlFor="total" className="col-2 m-0 p-0 col-form-label text-md-left">Total(<small>Kshs</small>)</label>
                                    <div className="col-4">
                                        <input id="total" type="text" className="form-control border-none" name="total" readOnly value={new Number((new Number(formdata.Rent)+new Number(formdata.Garbage) +new Number(formdata.Arrears)+new Number(formdata.Water)+new Number(formdata.HseDeposit) +new Number(formdata.Waterbill)+new Number(formdata.KPLC) +new Number(formdata.Lease)) -new Number(formdata.Excess)).toFixed(2)} autoFocus/>
                                        {formdata.error_list && formdata.error_list.total && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.total}</strong>
                                            </span>
                                        }
                                        
                                    </div>
                                </div>
                                
                                {/* <div className='row m-0 p-1'>
                                    <label htmlFor="total" className="col-4 col-form-label text-md-right">Total(<small>Kshs</small>)</label>
                                    <div className="col-8">
                                        <input id="total" type="text" className="form-control border-none" name="total" readOnly value={new Number((new Number(formdata.Rent)+new Number(formdata.Garbage) +new Number(formdata.Arrears)+new Number(formdata.Water)+new Number(formdata.HseDeposit) +new Number(formdata.Waterbill)+new Number(formdata.KPLC) +new Number(formdata.Lease)) -new Number(formdata.Excess)).toFixed(2)} autoFocus/>
                                        {formdata.error_list && formdata.error_list.total && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.total}</strong>
                                            </span>
                                        }
                                        
                                    </div>
                                </div> */}
                                
                                
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
                                        {(currentwaterbill.saved==='Yes')?'Update ':'Save '} {currentwaterbill.monthname} Monthly Bill for {currentwaterbill.housename}
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

export default AddMonthlyBill;