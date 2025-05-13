import Modal from 'react-bootstrap/Modal';

import axios from 'axios';


import { useEffect, useState } from 'react';

import Swal from 'sweetalert';

import Spinner from 'react-bootstrap/Spinner';
import HouseDetailsSpinner from '../spinners/HouseDetailsSpinner';
import HouseTenantDetailsSpinner from '../spinners/HouseTenantDetailsSpinner';


function ManagePropertyVacateHseTenant({currentproperty,showvacatehouse,handleCloseVacateHouse,handleShowAddHouse}) {
   
    const [plot,setPlotID]=useState((currentproperty===undefined)?'':currentproperty.pid)
    const [id,setID]=useState((currentproperty===undefined)?'':currentproperty.hid)

    console.log(plot,id)

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

        damages:(!loadingmonths)?agreementdata[0].Damages:0,
        refund:(!loadingmonths)?paymentsdata[0].Refund:0,
        cost:0,
        units:0,
        total:0,
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


    const deleteHouse= (house)=>{
        const form={
            id:house.id,
        }

        let title='Delete '+house.Housename;
        let text="This will remove this House from the Property.";
        Swal({
            title:title+' ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                axios.post('/api/delete/house/save',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        Swal("Success",response.data.message);
                    }
                    else if(response.data.status=== 401){
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
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
                                                <label className="col-5 m-0 p-0 text-md-right">Arrears:</label>

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
                                <div className="col-12 col-lg-6 text-left m-0 p-1 mt-1 mb-2">
                                    <div className="border-info card p-1 elevation-2">
                                        <div className="form-group row m-0 p-1 pb-2">
                                            <label htmlFor="Water" className="col-sm-4 col-12 col-form-label text-md-right">Damages</label>

                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                <input id="Water" type="text" className="form-control" name="Water" value={formdata.Water} onChange={handleInputChange} placeholder="0.00" required autoComplete="Water" autoFocus/>
                                                {formdata.error_list && formdata.error_list.Water && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.Water}</strong>
                                                    </span>
                                                }
                                            </div>
                                        </div>


                                        <div className="form-group row m-0 p-1 pb-2">
                                            <label htmlFor="Lease" className="col-sm-4 col-12 col-form-label text-md-right">Transaction</label>

                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                <input id="Lease" type="text" className="form-control" name="Lease" value={formdata.Lease} onChange={handleInputChange} placeholder="0.00" required autoComplete="Lease" autoFocus/>
                                                {formdata.error_list && formdata.error_list.Lease && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.Lease}</strong>
                                                    </span>
                                                }
                                            </div>
                                        </div>


                                        <div className="form-group row m-0 p-1 pb-2">
                                            <label htmlFor="Garbage" className="col-sm-4 col-12 col-form-label text-md-right">To Refund</label>

                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                <input id="Garbage" type="text" className="form-control" name="Garbage" value={formdata.Garbage} onChange={handleInputChange} placeholder="0.00" required autoComplete="Garbage" readonly="readonly" autoFocus/>
                                                {formdata.error_list && formdata.error_list.Garbage && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.Garbage}</strong>
                                                    </span>
                                                }
                                            </div>
                                        </div>


                                        <div className="form-group row m-0 p-1 pb-2">
                                            <label htmlFor="DueDay" className="col-sm-4 col-12 col-form-label text-md-right">Vacating Date</label>

                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                <input id="DueDay" type="date" className="form-control" name="DueDay" value={formdata.DueDay} onChange={handleInputChange} placeholder="1" required autoComplete="DueDay" autoFocus/>
                                                {formdata.error_list && formdata.error_list.DueDay && 
                                                    <span className="help-block text-danger">
                                                        <strong>{formdata.error_list.DueDay}</strong>
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
                                                        <button type="submit" className="btn btn-danger">
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