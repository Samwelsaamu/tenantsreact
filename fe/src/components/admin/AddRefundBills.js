import { useEffect, useContext , useState } from 'react';
import Spinner from 'react-bootstrap/Spinner';

import Modal from 'react-bootstrap/Modal';

import axios from 'axios';

import Swal from 'sweetalert';
import ReLogin from '../home/ReLogin';
import { LoginContext } from '../contexts/LoginContext';


function AddRefundBills({show,handleClose,currentwaterbill,loadRentGarbage}) {
    document.title=(currentwaterbill.saved==='Yes')?'Update Refund Bill for '+currentwaterbill.housename:'Add Refund Bill for '+currentwaterbill.housename;
    // console.log(currentwaterbill)
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
      
    const [loggedoff,setLoggedOff]=useState(false);
    const [loading,setLoading]=useState(false);

    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');

   
    const [formdata,setFormData]=useState({
        Arrears:currentwaterbill.Arrears,
        Deposit:currentwaterbill.Deposit,
        Damages:currentwaterbill.Damages,
        Transaction:currentwaterbill.Transaction,
        OtherCharges:currentwaterbill.OtherCharges,
        Refund:currentwaterbill.Refund,
        total:((currentwaterbill.Arrears+currentwaterbill.Damages+currentwaterbill.Transaction+currentwaterbill.OtherCharges)-(currentwaterbill.Deposit)),
        error_list:[],
    });
    
  
    //   useEffect( () =>{
    //       socket.on('load_credit_balance', (msg) =>{
    //           loadAppData();
    //       })
  
    //   }, []);
  
    const handleTransactionChange=(e)=>{
        e.persist();
        const Arrears=formdata.Arrears;
        const Deposit=formdata.Deposit;
        const Damages=formdata.Damages;
        const OtherCharges=formdata.OtherCharges;

        const Transaction=e.target.value;

        if(!isNaN(Transaction)){
            let total=(((new Number(Arrears)) + (new Number(Transaction)) + (new Number(OtherCharges)) + (new Number(Damages)))-(new Number(Deposit)) );
            setFormData({
                Arrears:formdata.Arrears,
                Deposit:formdata.Deposit,
                Damages:formdata.Damages,
                OtherCharges:formdata.OtherCharges,
                Transaction:e.target.value,
                total:total,
            })
        }
    }

    const handleDamagesChange=(e)=>{
        e.persist();
        const Damages=e.target.value;

        const Deposit=formdata.Deposit;
        const Arrears=formdata.Arrears;
        const OtherCharges=formdata.OtherCharges;
        const Transaction=formdata.Transaction;

        if(!isNaN(Damages)){
            let total=(((new Number(Damages)) + (new Number(Transaction)) + (new Number(OtherCharges)) + (new Number(Arrears))) -(new Number(Deposit)));
            setFormData({
                Damages:e.target.value,
                Deposit:formdata.Deposit,
                Arrears:formdata.Arrears,
                OtherCharges:formdata.OtherCharges,
                Transaction:formdata.Transaction,
                total:total,
            })
        }
    }

    const handleArrearsChange=(e)=>{
        e.persist();
        const Arrears=e.target.value;

        const Damages=formdata.Damages;
        const Deposit=formdata.Deposit;
        const Transaction=formdata.Transaction;
        const OtherCharges=formdata.OtherCharges;

        if(!isNaN(Arrears)){
            let total=(((new Number(Damages)) + (new Number(Transaction)) + (new Number(Arrears)) + (new Number(OtherCharges)))-(new Number(Deposit)) );
            setFormData({
                Arrears:e.target.value,
                Damages:formdata.Damages,
                Water:formdata.Water,
                Deposit:formdata.Deposit,
                Transaction:formdata.Transaction,
                OtherCharges:formdata.OtherCharges,
                total:total,
            })
        }
    }

    const handleOtherChargesChange=(e)=>{
        e.persist();
        const OtherCharges=e.target.value;

        const Damages=formdata.Damages;
        const Transaction=formdata.Transaction;
        const Arrears=formdata.Arrears;
        const Deposit=formdata.Deposit;

        if(!isNaN(OtherCharges)){
            let total=(((new Number(Damages))  + (new Number(Transaction)) + (new Number(Arrears)) + (new Number(OtherCharges))) -(new Number(Deposit)) );
            setFormData({
                OtherCharges:e.target.value,
                Damages:formdata.Damages,
                Transaction:formdata.Transaction,
                Arrears:formdata.Arrears,
                Deposit:formdata.Deposit,
                total:total,
            })
        }
    }
    const handleDepositChange=(e)=>{
        e.persist();
        const Deposit=e.target.value;

        const Damages=formdata.Damages;
        const Transaction=formdata.Transaction;
        const Arrears=formdata.Arrears;
        const OtherCharges=formdata.OtherCharges;

        if(!isNaN(Deposit)){
            let total=(((new Number(Damages)) + (new Number(Transaction)) + (new Number(Arrears)) + (new Number(OtherCharges)) )-(new Number(Deposit)));
            setFormData({
                Deposit:e.target.value,
                Damages:formdata.Damages,
                Transaction:formdata.Transaction,
                Arrears:formdata.Arrears,
                OtherCharges:formdata.OtherCharges,
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
            Damages:formdata.Damages,
            Transaction:formdata.Transaction,
            Deposit:formdata.Deposit,
            OtherCharges:formdata.OtherCharges,
            Arrears:formdata.Arrears,
            Total:formdata.total,
            month:currentwaterbill.month,
            Tenant:currentwaterbill.tid,
            pid:currentwaterbill.pid,
            hid:currentwaterbill.id,
            agreementid:currentwaterbill.agreementid,
        }

        let text='';
        let title='Save ';
        text="New Changes for "+currentwaterbill.monthname+"( "+currentwaterbill.housename+" ) will be :"+
                (currentwaterbill.Arrears!==formdata.Arrears?"\n Balance :: "+currentwaterbill.Arrears+" > "+formdata.Arrears:'')+
                (currentwaterbill.OtherCharges!==formdata.OtherCharges?"\n Other Charges :: "+currentwaterbill.OtherCharges+" > "+formdata.OtherCharges:'')+
                (currentwaterbill.Damages!==formdata.Damages?"\n Damages :: "+currentwaterbill.Damages+" > "+formdata.Damages:'')+
                (currentwaterbill.Transaction!==formdata.Transaction?"\n Transaction :: "+currentwaterbill.Transaction+" > "+formdata.Transaction:'')+
                ((currentwaterbill.Arrears!==formdata.Arrears) || (currentwaterbill.Damages!==formdata.Damages)
                || (currentwaterbill.Transaction!==formdata.Transaction)
                || (currentwaterbill.OtherCharges!==formdata.OtherCharges)?"\n New Refund .......... ":'')+
                (Math.abs(currentwaterbill.Refund)!==Math.abs(formdata.total)?"\n Total Kshs. :: "+new Number(currentwaterbill.Refund).toFixed(2)+" > "+new Number(formdata.total).toFixed(2):'');
        
        let url='/v2/save/refunds/save';
        
        if(text.toLowerCase()===("New Changes for "+currentwaterbill.monthname+"( "+currentwaterbill.housename+" ) will be :").toLowerCase()){
            Swal(currentwaterbill.saved==='Yes'?currentwaterbill.monthname+' Refund for '+currentwaterbill.housename:currentwaterbill.monthname+' Refund for '+currentwaterbill.housename,"You have not made any changes.");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else{
            Swal({
                title:title+' Refund Changes ?',
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
      
            <Modal.Header className='justify-content-center bg-warning m-0 p-2' closeButton>
                <Modal.Title className='mx-auto text-dark'> 
                    <h5 className='text-center'>{'Refund Details for: '+currentwaterbill.housename +'('+currentwaterbill.tenantfname+')'}
                        <br/>
                        {' Vacated On: '+currentwaterbill.VacatedOn}
                    </h5>
                    
                </Modal.Title>
            </Modal.Header>
            <Modal.Body className='m-0 p-0'>
                    {loggedoff ? 
                        <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
                    :
                    <div className="card-box-links m-0 p-0 justify-content-center text-center">
                    <div className="row m-1 p-0 justify-content-center text-center border-none">
                        <form onSubmit={submitWaterbill}>
                            <div className="form-group row m-0 p-2 border-none">
                                <div className='row m-0 p-1'>
                                    <label htmlFor="Arrears" className="col-2 m-0 p-0 col-form-label text-md-left">Balance</label>
                                    <div className="col-4">
                                        <input id="Arrears" type="text" className="form-control" name="Arrears" placeholder="Balance" value={formdata.Arrears} onChange={handleArrearsChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Arrears && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Arrears}</strong>
                                            </span>
                                        }
                                    </div>
                                    <label htmlFor="Damages" className="col-2 m-0 p-0 col-form-label text-md-left">Damages</label>
                                    <div className="col-4">
                                        <input id="Damages" type="text" className="form-control" name="Damages" placeholder="Damages" value={formdata.Damages} onChange={handleDamagesChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Damages && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Damages}</strong>
                                            </span>
                                        }
                                    </div>
                                </div>
                                <div className='row m-0 p-1'>
                                    <label htmlFor="Transaction" className="col-2 m-0 p-0 col-form-label text-md-left">Transaction</label>
                                    <div className="col-4">
                                        <input id="Transaction" type="text" className="form-control" name="Transaction" placeholder="Transaction" value={formdata.Transaction} onChange={handleTransactionChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Transaction && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Transaction}</strong>
                                            </span>
                                        }
                                    </div>
                                    <label htmlFor="OtherCharges" className="col-2 m-0 p-0 col-form-label text-md-left">Other Charges</label>
                                    <div className="col-4">
                                        <input id="OtherCharges" type="text" className="form-control" name="OtherCharges" placeholder="OtherCharges" value={formdata.OtherCharges} onChange={handleOtherChargesChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.OtherCharges && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.OtherCharges}</strong>
                                            </span>
                                        }
                                    </div>
                                </div>
                                <div className='row m-0 p-1'>
                                    <label htmlFor="Deposit" className="col-2 m-0 p-0 col-form-label text-md-left">Deposit</label>
                                    <div className="col-4">
                                        <input id="Deposit" type="text" className="form-control" name="Deposit" placeholder="Deposit" disabled value={formdata.Deposit} onChange={handleDepositChange} autoFocus/>
                                        {formdata.error_list && formdata.error_list.Deposit && 
                                            <span className="help-block text-danger">
                                                <strong>{formdata.error_list.Deposit}</strong>
                                            </span>
                                        }
                                    </div>
                                    <label htmlFor="total" className="col-2 m-0 p-0 col-form-label text-md-left">Total(<small>Kshs</small>)</label>
                                    <div className="col-4">
                                        <input id="total" type="text" className="form-control border-none" name="total" readOnly value={new Number( ((new Number(formdata.Arrears)+new Number(formdata.Damages) +new Number(formdata.Transaction)+new Number(formdata.OtherCharges)))- new Number(formdata.Deposit) ).toFixed(2)} autoFocus/>
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
                                        <button type="submit" className="btn btn-success">
                                        {(currentwaterbill.refunded==='Yes')?'':'Save Changes for: '} {currentwaterbill.monthname} Refund for {currentwaterbill.housename}
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

export default AddRefundBills;