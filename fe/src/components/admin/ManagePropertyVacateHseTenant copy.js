import Modal from 'react-bootstrap/Modal';

import axios from 'axios';
import {useNavigate} from 'react-router-dom';
import { useEffect, useState } from 'react';

import Swal from 'sweetalert';

import Spinner from 'react-bootstrap/Spinner';
import HouseDetailsSpinner from '../spinners/HouseDetailsSpinner';
import HouseTenantDetailsSpinner from '../spinners/HouseTenantDetailsSpinner';


function ManagePropertyVacateHseTenant({currentproperty,showvacatehouse,handleCloseVacateHouse,handleShowAddHouse, handleCloseAddProperty,loadTenants}) {
   
    const navigate=useNavigate();
    const [plot,setPlotID]=useState((currentproperty===undefined)?'':currentproperty.pid)
    const [id,setID]=useState((currentproperty===undefined)?'':currentproperty.hid)



    // console.log(plot,id)

    const [housedata, setHousedata] = useState([]);
    const [agreementdata, setAgreementdata] = useState([]);
    
    const [paymentsdata, setPaymentsdata] = useState([]);
    
    // const [loading,setLoading]=useState(true);
    const [loadingmonths,setLoadingMonths]=useState(true);
    

    const [loading,setLoading]=useState(false);
    
    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');

    
    const [formdata,setFormData]=useState({
        // TotalUsed
        // TotalPaid
        // Deposit
        // Balance
        // Refund

        Damages:0.00,
        Refund:0.00,
        Transaction:0.00,
    });

    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getProperties = async (e) => { 
           
            let url=`/api/properties/mgr/tenants/vacate/${plot}/${id}`;
            
            if(id===''){
                setLoadingMonths(false)
                return false;
            }
            else{
                if(id==='all'){
                    url=`/api/properties/manage/load`;
                }
                else{
                    url=`/api/properties/mgr/tenants/vacate/${plot}/${id}`;
                }
            }


            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){

                        setHousedata(response.data.thishouse)
                        setAgreementdata(response.data.agreementinfo);
                        setPaymentsdata(response.data.payments);
                        setLoadingMonths(false)
                    }
                    else if(response.data.status=== 401){
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingMonths(false)
                    }
                    setLoadingMonths(false)
                }
            })
            .catch(error=>{
                Swal("Error",""+error,"error");
                setLoadingMonths(false)
            })
        };
        getProperties();

        return ()=>{
            doneloading=false;
        }
    },[id])


    const handleInputChange=(e)=>{
        e.persist();
        setFormData({...formdata,[e.target.name]:e.target.value})
    }

    const handleDamagesChange=(e)=>{
        e.persist();
        const damages=e.target.value;
        const current=formdata.current;
        const cost=formdata.cost;

        const units=(current)-(0);
        const total=(units)*(cost);
    }

    const handleShow = (house) => {
        handleShowAddHouse(house)
    };

    
    const vacateTenant= (house)=>{

        if(formdata.DateVacated===undefined || formdata.DateVacated ===""){
            Swal("Date Vacated Needed","Please Choose Date Tenant Vacated","error");
            return;
        }
        const form={
            hid:house.id,
            tid:paymentsdata[0].tenant,
            aid:paymentsdata[0].aid,
            Deposit:paymentsdata[0].Deposit,
            Refund:(new Number((new Number(paymentsdata[0].TotalUsed)+new Number(formdata.Damages)+new Number(formdata.Transaction))-(new Number(paymentsdata[0].TotalPaid)+new Number(paymentsdata[0].Deposit))).toFixed(2)),
            Arrears:paymentsdata[0].Balance,
            Damages:(formdata.Damages),
            DateVacated:formdata.DateVacated,
            Transaction:(formdata.Transaction),
        }
        // console.log(form)

        let title='Vacate  '+ housedata.tenantname + ' From '+house.Housename;
        let text="This will Vacate tenant from this House.\n"+
        
        (((new Number(paymentsdata[0].TotalUsed)+new Number(formdata.Damages)+new Number(formdata.Transaction))-(new Number(paymentsdata[0].TotalPaid)+new Number(paymentsdata[0].Deposit)))>0 ?"\n Tenant Arrears :: "+
            new Number((new Number(paymentsdata[0].TotalUsed)+new Number(formdata.Damages)+new Number(formdata.Transaction))-(new Number(paymentsdata[0].TotalPaid)+new Number(paymentsdata[0].Deposit))).toFixed(2)
        :"\n To Refund :: "+
        Math.abs(new Number((new Number(paymentsdata[0].TotalUsed)+new Number(formdata.Damages)+new Number(formdata.Transaction))-(new Number(paymentsdata[0].TotalPaid)+new Number(paymentsdata[0].Deposit)))).toFixed(2));
        Swal({
            title:title+' ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                axios.post('/api/vacate/house/save',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        setFormData({...formdata,error_list:[]});
                        handleCloseVacateHouse();
                        handleCloseAddProperty();
                        Swal("Success",response.data.message);
                        loadTenants();
                        // window.location.reload();
                    }
                    else if(response.data.status=== 401){
                        setFormData({...formdata,error_list:[]});
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        setFormData({...formdata,error_list:[]});
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setFormData({...formdata,error_list:response.data.errors});
                    }
                    setLoading(false);
                })
                .catch((error)=>{
                    Swal("Error",""+error,"error");
                    setLoading(false);
                })
            }
            else{
                setLoading(false);
            }
        })
    }


  return (
    <>
    
        <Modal size='xl' show={showvacatehouse} onHide={handleCloseVacateHouse} className='text-sm m-2 mt-4'>
        
            <Modal.Header className='justify-content-center bg-danger m-0 p-2' closeButton>
                <Modal.Title className='mx-auto text-white'> 
                    <h5>Vacate {housedata && housedata.tenantname} from House : {currentproperty !==undefined && currentproperty.Housename}</h5>
                </Modal.Title>
            </Modal.Header>
            
            <Modal.Body className='m-0 p-0' style={{"minHeight":"100vh","overflowX":"auto"}}>
                <div className="row m-0 p-0 justify-content-center text-center border-none">
                    <div className="row m-0 p-0" style={{"overflowX":"auto"}}>
                        {loadingmonths &&
                            <div className="col-12 col-md-6 col-lg-4 text-left m-0 p-1 mt-1 mb-2">
                                <HouseDetailsSpinner />
                            </div>
                        }
                        {!loadingmonths && 
                        <div className="col-12 col-lg-6 text-left m-0 p-1 mt-1 mb-2">
                            <div className="card border-info m-2 p-1" >
                                <div className="card-header bg-success border-info text-muted text-center m-0 mb-2 p-2 pt-1 pb-2">
                                    <span style={{"float":"left"}}>
                                        <span className={`m-0 p-1 text-sm bg-light ${housedata.Status==='Occupied'?'text-info':'text-danger'}`}
                                            style={{"borderRadius":"30px","border":"2px solid white"}}> {housedata.tenantfullname}</span> 
                                    </span> 
                                    <span className='m-0 p-1 text-sm text-dark mx-auto'>{housedata.Housename}</span>
                                    <span style={{"float":"right"}}>
                                        <span className={`m-0 p-1 text-sm bg-light ${housedata.Status==='Occupied'?'text-success':'text-danger'}`}
                                            style={{"borderRadius":"30px","border":"2px solid white"}}> {agreementdata[0].PhoneMasked}</span>  
                                    </span>
                                    
                                </div>
                                
                                <div className="card-body text-center text-muted text-sm m-0 p-0 pt-1">
                                    <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">IDNo:</label>

                                                <div className="col-7 bold text-md-left p-1">
                                                    {agreementdata[0].IDno}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Status:</label>

                                                <div className="col-7 bold text-md-left p-1">
                                                    {agreementdata[0].Status}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>

                                    <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Total Used:</label>

                                                <div className="col-7 bold text-md-left p-1">
                                                    {new Number(paymentsdata[0].TotalUsed).toFixed(2)}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Total Paid:</label>

                                                <div className="col-7 bold text-md-left p-1">
                                                    {new Number(paymentsdata[0].TotalPaid).toFixed(2)}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>

                                    <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Deposit:</label>

                                                <div className="col-7 bold text-md-left p-1">
                                                    {new Number(paymentsdata[0].Deposit).toFixed(2)}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Balance:</label>

                                                <div className="col-7 bold text-md-left p-1">
                                                    {new Number(paymentsdata[0].Balance).toFixed(2)}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                

                                    <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Refund:</label>

                                                <div className="col-7 bold text-md-left p-1">
                                                    {new Number(paymentsdata[0].Refund).toFixed(2)}
                                                </div>
                                            </div>
                                        </div>
                                        <div className="col-6 m-0 p-0">
                                            <div className="form-group row m-0 p-0">
                                                <label className="col-5 m-0 p-0 text-md-right">Assigned On:</label>

                                                <div className="col-7 bold text-md-left p-1">
                                                    {paymentsdata[0].dateToMonthName}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                

                                    
                                </div>

                            </div>
                        </div>
                        }

                        {loadingmonths &&
                            <>
                                <div className="col-12 col-md-6 col-lg-4 text-left m-0 p-1 mt-1 mb-2">
                                    <HouseTenantDetailsSpinner />
                                </div>
                                <div className="col-12 col-md-6 col-lg-4 text-left m-0 p-1 mt-1 mb-2">
                                    <HouseTenantDetailsSpinner />
                                </div>
                            </>
                        }
                        {!loadingmonths &&
                            <>
                                <div className="col-12 col-lg-6 text-left m-0 p-1 mt-3 mb-2">
                                    <div className="border-info card p-1 elevation-0">
                                        <div className="card-header bg-success border-info text-muted text-center m-0 mb-2 p-2 pt-1 pb-2">
                                            
                                            <span className='m-0 p-1 text-sm text-dark mx-auto'>Update the Following information where necessary and click on Vacate</span>
                                            
                                            
                                        </div>
                                        <div className="form-group row m-0 p-1 pb-2">
                                            <label htmlFor="Damages" className="col-sm-4 col-12 col-form-label text-md-right">Damages</label>

                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                <input id="Damages" type="text" className="form-control" name="Damages" value={formdata.Damages} onChange={handleInputChange} placeholder="0.00" required autoComplete="Damages" autoFocus/>
                                                {formdata.error_list && formdata.error_list.Damages && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.Damages}</strong>
                                                    </span>
                                                }
                                            </div>
                                        </div>


                                        <div className="form-group row m-0 p-1 pb-2">
                                            <label htmlFor="Transaction" className="col-sm-4 col-12 col-form-label text-md-right">Transaction</label>

                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                <input id="Transaction" type="text" className="form-control" name="Transaction" value={formdata.Transaction} onChange={handleInputChange} placeholder="0.00" required autoComplete="Transaction" autoFocus/>
                                                {formdata.error_list && formdata.error_list.Transaction && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.Transaction}</strong>
                                                    </span>
                                                }
                                            </div>
                                        </div>


                                        <div className="form-group row m-0 p-1 pb-2">
                                            <label htmlFor="ToRefund" className="col-sm-4 col-12 col-form-label text-md-right">
                                            {((new Number(paymentsdata[0].TotalUsed)+new Number(formdata.Damages)+new Number(formdata.Transaction))-(new Number(paymentsdata[0].TotalPaid)+new Number(paymentsdata[0].Deposit)))>0 ?
                                            "Arrears":"To Refund"
                                            }
                                            </label>

                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                <input id="ToRefund" type="text" className="form-control" name="ToRefund" value={Math.abs(new Number((new Number(paymentsdata[0].TotalUsed)+new Number(formdata.Damages)+new Number(formdata.Transaction))-(new Number(paymentsdata[0].TotalPaid)+new Number(paymentsdata[0].Deposit)))).toFixed(2)} onChange={handleInputChange} placeholder="0.00" required autoComplete="ToRefund" readonly="readonly" autoFocus/>
                                                {formdata.error_list && formdata.error_list.ToRefund && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.ToRefund}</strong>
                                                    </span>
                                                }
                                            </div>
                                        </div>


                                        <div className="form-group row m-0 p-1 pb-2">
                                            <label htmlFor="DateVacated" className="col-sm-4 col-12 col-form-label text-md-right">Vacating Date</label>

                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                <input id="DateVacated" type="date" className="form-control" name="DateVacated" value={formdata.DateVacated} onChange={handleInputChange} placeholder="1" required autoComplete="DateVacated" autoFocus/>
                                                {formdata.error_list && formdata.error_list.DateVacated && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.DateVacated}</strong>
                                                    </span>
                                                }
                                            </div>
                                        </div>

                                        <div className="col-12 m-0 p-0">
                                            <div className="form-group row mb-0 justify-content-center m-1 mt-2 p-2 border-none">
                                                
                                                {loadingresok!=='' && 
                                                    <div className="col-md-10 elevation-0 mb-2 p-2 text-center border-ok">
                                                        <span className="help-block text-success">
                                                            <strong>{loadingresok!=='' && loadingresok}</strong>
                                                        </span>
                                                    </div>
                                                }

                                                {loading && 
                                                    <div className="col-md-12 text-center text-white">
                                                            <Spinner
                                                            as="span"
                                                            variant='info'
                                                            animation="border"
                                                            size="lg"
                                                            role="status"
                                                            aria-hidden="true"
                                                            />
                                                            <span className='text-info' style={{"padding": "10px","display":"inline-block"}}>
                                                            Vacating ...</span>
                                                            
                                                    </div>
                                                }

                                                {!loading && loadingresok ==='' && 
                                                    <div className="col-md-12 justify-content-center text-center">
                                                        <button type="submit" className="btn btn-danger" onClick={()=>{vacateTenant(paymentsdata[0])}}>
                                                            Vacate Tenant
                                                        </button>
                                                    </div>
                                                }

                                                {loadingres!=='' && 
                                                    <div className="col-md-12 elevation-0 mt-2 p-2 text-center border-error">
                                                        <span className="help-block text-danger">
                                                            <strong>{loadingres!=='' && loadingres}</strong>
                                                        </span>
                                                    </div>
                                                }
                                                
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </>
                        }


                    </div>
                </div>
            </Modal.Body>
        </Modal>

    </>
  );
}

export default ManagePropertyVacateHseTenant;