import {useEffect, useContext, useState } from 'react';

import Spinner from 'react-bootstrap/Spinner';

import Modal from 'react-bootstrap/Modal';
import Select from 'react-select';


import axios from 'axios';

import Swal from 'sweetalert';
import ReLogin from '../home/ReLogin';
import AddHouseType from './AddHouseType';
import { LoginContext } from '../contexts/LoginContext';



function AddHouse({showaddhouse,handleCloseAddHouse,currentproperty,currenthouse,loadHouses}) {
    document.title=(currenthouse==='')?'Add New House for : '+currentproperty.Plotname:'Update House : '+currenthouse.Housename;
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
         
    const [loggedoff,setLoggedOff]=useState(false);
    const [loading,setLoading]=useState(false);

    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');

    const [loadingwater,setLoadingWater]=useState(true);
    const [show,setShow]=useState(false);

    
    const [propertyinfo, setPropertyinfo] = useState([""]);
    const [waterbillpropertyid,setWaterbillPropertyId]=useState([""]);
    
    const [propertydata, setPropertydata] = useState([]);

    const [formdata,setFormData]=useState({
        Plotcode:currentproperty.Plotcode,
        Housename:currenthouse===''?currentproperty.Plotcode+"-":currenthouse.Housename,
        Rent:currenthouse.Rent,
        Deposit:currenthouse.Deposit,
        hid:currenthouse.id,
        id:currenthouse.Plot,
        Kplc:currenthouse.Kplc,
        Water:currenthouse.Water,
        Lease:currenthouse.Lease,
        Garbage:currenthouse.Garbage,
        DueDay:currenthouse.DueDay,
        Status:currenthouse.Status,
        error_list:[],
    });
    
        // useEffect( () =>{
        //     socket.on('load_credit_balance', (msg) =>{
        //         loadAppData();
        //     })
    
        // }, []);
    
    
    const handleInputChange=(e)=>{
        e.persist();
        setFormData({...formdata,[e.target.name]:e.target.value})
    }


    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingWater(true)
        }
        const getWaterbill = async (e) => { 
            const arr = [];
            // arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
            arr1.push({value: '', label: 'Select Type' });
            let url=`/v2/properties/propertyhousetype/load/house`;
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyhousetypedata = response.data.propertyhousetypedata;
                        setPropertydata(response.data.propertyhousetypedata);
                        // let resthisproperty = response.data.thisproperty;
                        respropertyhousetypedata.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.typename , data: monthsup});
                        });
                        setPropertyinfo(arr1)
                        
                        let options=[];
                        if(currenthouse!==''){
                            respropertyhousetypedata.map((propertyt) => {
                                if(currenthouse.housetype===propertyt.id){
                                   options={value: propertyt.id, label: propertyt.typename , data: propertyt}
                                }
                            })
                            
                        }
                        setWaterbillPropertyId(options)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error7",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error8",response.data.message,"error");
                    }
                    setLoadingWater(false)
                }
            })
            .catch(error=>{
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
                        Swal("Error",""+error,"error");
                    }
                    setLoadingWater(false)
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingWater(false)
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
            getWaterbill();
        

        return ()=>{
            doneloading=false;
        }
    },[loggedoff])

    const loadPropertyHouseTypes =() =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingWater(true)
        }
        const getWaterbill = async (e) => { 
            const arr = [];
            // arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
            arr1.push({value: '', label: 'Select Type' });
            let url=`/v2/properties/propertyhousetype/load/house`;
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyhousetypedata = response.data.propertyhousetypedata;
                        setPropertydata(response.data.propertyhousetypedata);
                      
                        // let resthisproperty = response.data.thisproperty;
                        respropertyhousetypedata.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.typename , data: monthsup});
                        });
                        setPropertyinfo(arr1)
                        
                        let options=[];
                        if(currenthouse!==''){
                            respropertyhousetypedata.map((propertyt) => {
                                if(currenthouse.housetype===propertyt.id){
                                   options={value: propertyt.id, label: propertyt.typename , data: propertyt}
                                }
                            })
                            
                        }
                        setWaterbillPropertyId(options)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error7",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error8",response.data.message,"error");
                    }
                    setLoadingWater(false)
                }
            })
            .catch(error=>{
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
                        Swal("Error",""+error,"error");
                    }
                    setLoadingWater(false)
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingWater(false)
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
        
        getWaterbill();
            
            
    }

    const submitProperty= (e)=>{
        e.preventDefault();
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")
        
        const form={
            Plotcode:currentproperty.Plotcode,
            Housename:formdata.Housename,
            Rent:formdata.Rent,
            Deposit:formdata.Deposit,
            Kplc:formdata.Kplc,
            Water:formdata.Water,
            Lease:formdata.Lease,
            Garbage:formdata.Garbage,
            DueDay:formdata.DueDay,
            Status:formdata.Status,
            id:currentproperty.id,
            hid:currenthouse.id,
            propertyhousetypeid:waterbillpropertyid.value,
        }

        let title='Sure to '+(currenthouse!==''?'Update':'Add New')+ ' '+(currenthouse!==''?currenthouse.Housename:formdata.Housename) +' ?';
        let text='';
        if(currenthouse!==''){
            text="New Changes for "+currenthouse.Housename+" will be :"+
                (currenthouse.Housename.trim().toLowerCase()!==formdata.Housename.trim().toLowerCase()?"\n Name :: "+currenthouse.Housename+" > "+formdata.Housename:'')+
                (currenthouse.Rent!==formdata.Rent?"\n Rent :: "+currenthouse.Rent+" > "+formdata.Rent:'')+
                (currenthouse.Deposit!==formdata.Deposit?"\n Deposit :: "+currenthouse.Deposit+" > "+formdata.Deposit:'')+
                (currenthouse.Kplc!==formdata.Kplc?"\n Kplc D :: "+currenthouse.Kplc+" > "+formdata.Kplc:'')+
                (currenthouse.Water!==formdata.Water?"\n Water D :: "+currenthouse.Water+" > "+formdata.Water:'')+
                (currenthouse.Garbage!==formdata.Garbage?"\n Garbage D :: "+currenthouse.Garbage+" > "+formdata.Garbage:'')+
                (currenthouse.DueDay!==formdata.DueDay?"\n Due Date :: "+currenthouse.DueDay+" > "+formdata.DueDay:'')+
                (currenthouse.Status!==formdata.Status?"\n Status :: "+currenthouse.Status+" > "+formdata.Status:'')+
                (currenthouse.housetype!==waterbillpropertyid.value?"\n House Type :: "+currenthouse.housetypename+" > "+waterbillpropertyid.label:'');
        }
        else{
            text="New Information for "+formdata.Housename+" will be :"+
                "\n Name :: "+formdata.Housename+
                "\n Rent :: "+formdata.Rent+
                "\n Deposit :: "+formdata.Deposit+
                "\n Kplc D :: "+formdata.Kplc+
                "\n Water D :: "+formdata.Water+
                "\n Garbage D :: "+formdata.Garbage+
                "\n Due Date :: "+formdata.DueDay;
        }
        let url='';
        if(currenthouse!==''){
            url='/v2/update/house/save';
        }
        else{
            url='/v2/save/house/save';
        }

        if(text.trim()==="New Changes for "+currenthouse.Housename+" will be :"){
            Swal("Updating "+currenthouse.Housename,"You have not made any changes.");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else if(text.trim()==="New Information for "+formdata.Housename+" will be :"){
            Swal("New House","No House Infomation Specified.");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else{

            Swal({
                title:title,
                text:text,
                buttons:true,
                infoMode:true,
            })
            .then((willcontinue) =>{
                if(willcontinue){
                    axios.post(url,form)
                    .then(response=>{
                        if(response.data.status=== 200){
                            setLoadingResOk(response.data.message)
                            setFormData({...formdata,error_list:[]});
                            setLoadingRes("")
                            loadHouses();
                        }
                        else if(response.data.status=== 401){
                            // setLoggedOff(true);    
                            setLoadingRes(response.data.message)
                            setFormData({...formdata,error_list:[]});
                            setLoadingResOk("")
                        }
                        else if(response.data.status=== 500){
                            setLoadingRes(response.data.message)
                            setFormData({...formdata,error_list:[]});
                            setLoadingResOk("")
                        }
                        else{
                            setFormData({...formdata,error_list:response.data.errors});
                        }
                        setLoading(false);
                        // console.log(formdata.error_list)
    
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

    function handlePropertyChange(val) {
        setLoadingWater(true)
        // setID(val.value);
        let options={value: val.value, label: val.label , data: val}
        setWaterbillPropertyId(options) 
        setLoadingWater(false)
        // setRefresh(!refresh);
    }

    const handleClose = () => {
        setShow(false);
        document.title="Add or Update House Type";
    };

    const handleShow = () => {
        setShow(true);
    };

    

  return (
    <>
        <Modal size='lg' show={showaddhouse} onHide={handleCloseAddHouse} className='text-sm'>
        {(currenthouse ==='')?
            <>
                <Modal.Header className='justify-content-center bg-info m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-white text-sm'> 
                        <h5>Add New House for : {currentproperty.Plotname} ({currentproperty.Plotcode})</h5>
                    </Modal.Title>
                </Modal.Header>
            </>
            :
            <>
                <Modal.Header className='justify-content-center bg-warning m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-dark text-sm'> 
                        <h5>Update House : {currenthouse.Housename}</h5>
                    </Modal.Title>
                </Modal.Header>
            </>
            }
            
            
            <Modal.Body className='m-0 p-0'>
                    {loggedoff ? 
                        <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
                    :
                    <div className="row m-0 p-0 justify-content-center text-center border-none">
                    <form onSubmit={submitProperty}>
                        <div className="row m-0 p-0 mt-3 mb-3">
                            <div className="col-6 m-0 p-1">
                                <div className="border-info card p-1 elevation-2">
                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Housename" className="col-sm-5 col-12 col-form-label text-md-right">House Name</label>

                                        <div className="col-sm-7 col-12 m-0 p-0">
                                            <input id="Housename" type="text" className="form-control" name="Housename" value={formdata.Housename} onChange={handleInputChange} placeholder="House Name" required autoComplete="Housename" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Housename && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Housename}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Rent" className="col-sm-5 col-12 col-form-label text-md-right">House Rent</label>

                                        <div className="col-sm-7 col-12 m-0 p-0">
                                            <input id="Rent" type="text" className="form-control" name="Rent" value={formdata.Rent} onChange={handleInputChange} placeholder="0.00" required autoComplete="Rent" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Rent && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Rent}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Deposit" className="col-sm-5 col-12 col-form-label text-md-right">House Deposit</label>

                                        <div className="col-sm-7 col-12 m-0 p-0">
                                            <input id="Deposit" type="text" className="form-control" name="Deposit" value={formdata.Deposit} onChange={handleInputChange} placeholder="0.00" required autoComplete="Deposit" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Deposit && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Deposit}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Kplc" className="col-sm-5 col-12 col-form-label text-md-right">KPLC Deposit</label>

                                        <div className="col-sm-7 col-12 m-0 p-0">
                                            <input id="Kplc" type="text" className="form-control" name="Kplc" value={formdata.Kplc} onChange={handleInputChange} placeholder="0.00" required autoComplete="Kplc" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Kplc && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Kplc}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div className="col-6 m-0 p-1">
                                <div className="border-info card p-1 elevation-2">
                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Water" className="col-sm-5 col-12 col-form-label text-md-right">Water Deposit</label>

                                        <div className="col-sm-7 col-12 m-0 p-0">
                                            <input id="Water" type="text" className="form-control" name="Water" value={formdata.Water} onChange={handleInputChange} placeholder="0.00" required autoComplete="Water" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Water && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Water}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>


                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Lease" className="col-sm-5 col-12 col-form-label text-md-right">Lease Amount</label>

                                        <div className="col-sm-7 col-12 m-0 p-0">
                                            <input id="Lease" type="text" className="form-control" name="Lease" value={formdata.Lease} onChange={handleInputChange} placeholder="0.00" required autoComplete="Lease" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Lease && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Lease}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>


                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Garbage" className="col-sm-5 col-12 col-form-label text-md-right">Garbage Deposit</label>

                                        <div className="col-sm-7 col-12 m-0 p-0">
                                            <input id="Garbage" type="text" className="form-control" name="Garbage" value={formdata.Garbage} onChange={handleInputChange} placeholder="0.00" required autoComplete="Garbage" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Garbage && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Garbage}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>


                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="DueDay" className="col-sm-5 col-12 col-form-label text-md-right">Due Date</label>

                                        <div className="col-sm-7 col-12 m-0 p-0">
                                            <input id="DueDay" type="number" min="1" max="20" className="form-control" name="DueDay" value={formdata.DueDay} onChange={handleInputChange} placeholder="1" required autoComplete="DueDay" autoFocus/>
                                            {formdata.error_list && formdata.error_list.DueDay && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.DueDay}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    

                                    {currenthouse !=='' && currenthouse.tenant!=="Vacated" && currenthouse.Status==="Vacant" &&
                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Status" className="col-sm-5 col-12 col-form-label text-md-right">Status</label>

                                        <div className="col-sm-7 col-12 m-0 p-0">
                                            <input id="Status" type="text" className="form-control" name="Status" value={formdata.Status} onChange={handleInputChange}  required autoComplete="Status" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Status && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Status}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>
                                    }


                                </div>
                            </div>
                            <div className="col-12 m-0 p-0">
                                <div className="border-info card p-1 elevation-2">
                                    <div className="form-group row m-0 p-1 border-light mb-1 ">
                                        <label htmlFor="Deposit" className="col-sm-3 col-12 col-form-label text-md-right">Type</label>
                                        <div className="col-sm-6 col-12 m-0 p-0" >
                                            {loadingwater &&
                                                <Spinner  variant="blue" size="md" role="status"></Spinner>
                                            }
                                            {!loadingwater && 
                                                <Select
                                                    className='text-sm'
                                                    placeholder= "Select Property"
                                                    value={waterbillpropertyid}
                                                    name="manage-property"
                                                    options={propertyinfo}
                                                    onChange={handlePropertyChange}
                                                />
                                            }
                                        </div>
                                        <div className="col-sm-3 col-12 m-0 p-0" style={{"float":"right"}}>
                                            <button type="button" className="btn btn-primary m-1 p-1" onClick={()=>{handleShow()}}>
                                                <i className='fa fa-plus-circle'></i> Add Type
                                            </button>
                                        </div>
                                    </div>
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
                                                {(currenthouse !=='')?'Updating':'Adding'} ...</span>
                                                
                                        </div>
                                    }

                                    {!loading && loadingresok ==='' && 
                                        <div className="col-md-10">
                                            <button type="submit" className="btn btn-success">
                                                {(currenthouse !=='')?'Update ':'Save New House for : '} {currentproperty.Plotname}
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
                            </div>
                        </div>
                    </form>
                </div>
                }
            </Modal.Body>
        </Modal>

        {show && 
            <AddHouseType show={show} handleClose={handleClose} currentproperty={currentproperty} propertydata={propertydata} loadPropertyHouseTypes={loadPropertyHouseTypes} loadHouses ={loadHouses} />
        }

    </>
  );
}

export default AddHouse;