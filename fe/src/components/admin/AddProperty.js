import { useEffect, useContext, useState } from 'react';

import Spinner from 'react-bootstrap/Spinner';

import Modal from 'react-bootstrap/Modal';
import Select from 'react-select';

import axios from 'axios';

import Swal from 'sweetalert';
import ReLogin from '../home/ReLogin';
import AddPropertyType from './AddPropertyType';
import { LoginContext } from '../contexts/LoginContext';



function AddProperty({showaddproperty,handleCloseAddProperty,currentproperty,loadProperties}) {
    document.title=(currentproperty!=='')?'Update Property : '+currentproperty.Plotname:'Add New Property';
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
        Plotname:currentproperty.Plotname,
        Plotarea:currentproperty.Plotarea,
        Waterbill:currentproperty.Waterbill,
        id:currentproperty.id,
        Waterdeposit:currentproperty.Waterdeposit,
        Plotaddr:(currentproperty.Plotaddr===null?"":currentproperty.Plotaddr),
        Plotdesc:(currentproperty.Plotdesc===null?"":currentproperty.Plotdesc),
        Outsourced:currentproperty.Outsourced,
        Kplcdeposit:currentproperty.Kplcdeposit,
        Garbage:currentproperty.Garbage,
        Deposit:currentproperty.Deposit,
        error_list:[],
    });
        // useEffect( () =>{
        //     socket.on('load_credit_balance', (msg) =>{
        //         loadAppData();
        //     })
    
        // }, []);
    
    
    // console.log(formdata)
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
            let url=`/v2/properties/propertyhousetype/load/property`;
            
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
                        if(currentproperty!==''){
                            respropertyhousetypedata.map((propertyt) => {
                                if(currentproperty.propertytype===propertyt.id){
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
            let url=`/v2/properties/propertyhousetype/load/property`;
            
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
                        if(currentproperty!==''){
                            respropertyhousetypedata.map((propertyt) => {
                                if(currentproperty.propertytype===propertyt.id){
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
        
        // console.log(waterbillpropertyid)
        const form={
            Plotcode:formdata.Plotcode,
            Plotname:formdata.Plotname,
            Plotarea:formdata.Plotarea,
            Plotdesc:formdata.Plotdesc,
            Waterbill:formdata.Waterbill,
            id:formdata.id,
            Waterdeposit:formdata.Waterdeposit,
            Plotaddr:formdata.Plotaddr,
            Outsourced:formdata.Outsourced,
            Kplcdeposit:formdata.Kplcdeposit,
            Garbage:formdata.Garbage,
            Deposit:formdata.Deposit,
            propertyhousetypeid:waterbillpropertyid.value,
        }

        let title='Sure to '+(currentproperty!==''?'Update':'Add')+ ' '+(currentproperty!==''?currentproperty.Plotname:formdata.Plotname) +' ?';
        let text='';
        if(currentproperty!==''){
            text="New Changes for "+currentproperty.Plotname+" will be :"+
                (currentproperty.Plotname.trim().toLowerCase()!==formdata.Plotname.trim().toLowerCase()?"\n Name :: "+currentproperty.Plotname+" > "+formdata.Plotname:'')+
                (currentproperty.Plotarea.trim().toLowerCase()!==formdata.Plotarea.trim().toLowerCase()?"\n Area :: "+currentproperty.Plotarea+" > "+formdata.Plotarea:'')+
                (currentproperty.Plotcode.trim().toLowerCase()!==formdata.Plotcode.trim().toLowerCase()?"\n Code :: "+currentproperty.Plotcode+" > "+formdata.Plotcode:'')+
                ((currentproperty.Plotaddr===null?"":currentproperty.Plotaddr).trim().toLowerCase()!==formdata.Plotaddr.trim().toLowerCase()?"\n Address :: "+(currentproperty.Plotaddr===null?"":currentproperty.Plotaddr)+" > "+formdata.Plotaddr:'')+
                ((currentproperty.Plotdesc===null?"":currentproperty.Plotdesc).trim().toLowerCase()!==formdata.Plotdesc.trim().toLowerCase()?"\n Description :: "+(currentproperty.Plotdesc===null?"":currentproperty.Plotdesc)+" > "+formdata.Plotdesc:'')+
                (currentproperty.Waterbill.trim().toLowerCase()!==formdata.Waterbill.trim().toLowerCase()?"\n Waterbill :: "+currentproperty.Waterbill+" > "+formdata.Waterbill:'')+
                (currentproperty.Kplcdeposit.trim().toLowerCase()!==formdata.Kplcdeposit.trim().toLowerCase()?"\n KPLCD :: "+currentproperty.Kplcdeposit+" > "+formdata.Kplcdeposit:'')+
                (currentproperty.Garbage.trim().toLowerCase()!==formdata.Garbage.trim().toLowerCase()?"\n Garbage :: "+currentproperty.Garbage+" > "+formdata.Garbage:'')+
                (currentproperty.Outsourced.trim().toLowerCase()!==formdata.Outsourced.trim().toLowerCase()?"\n Outsourced :: "+currentproperty.Outsourced+" > "+formdata.Outsourced:'')+
                (currentproperty.Deposit.trim().toLowerCase()!==formdata.Deposit.trim().toLowerCase()?"\n Deposit :: "+currentproperty.Deposit+" Deposit> "+formdata.Deposit:'')+
                (currentproperty.Waterdeposit.trim().toLowerCase()!==formdata.Waterdeposit.trim().toLowerCase()?"\n WaterD :: "+currentproperty.Waterdeposit+" Waterdeposit> "+formdata.Waterdeposit:'')+
                (currentproperty.propertytype!==waterbillpropertyid.value?"\n Type :: "+currentproperty.propertytypename+" > "+waterbillpropertyid.label:'')
                ;
        }
        else{
            text="New Information for "+formdata.Plotname+" will be :"+
                "\n Name :: "+formdata.Plotname+
                "\n Area :: "+formdata.Plotarea+
                "\n Code :: "+formdata.Plotcode+
                "\n Address :: "+formdata.Plotaddr+
                "\n Description :: "+formdata.Plotdesc+
                "\n Waterbill :: "+formdata.Waterbill+
                "\n KPLCD :: "+formdata.Kplcdeposit+
                "\n Garbage :: "+formdata.Garbage+
                "\n Outsourced :: "+formdata.Outsourced+
                "\n Deposit :: "+formdata.Deposit+
                "\n Water Deposit :: "+formdata.Waterdeposit+
                "\n Property Type :: "+waterbillpropertyid.label;
        }
        let url='';
        if(currentproperty!==''){
            url='/v2/update/property/save';
        }
        else{
            url='/v2/save/property/save';
        }

        if(text.trim()==="New Changes for "+currentproperty.Plotname+" will be :"){
            Swal("Updating "+currentproperty.Plotname,"You have not made any changes.");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else if(text.trim()==="New Information for "+formdata.Plotname+" will be :"){
            Swal("New Property","No Property Infomation Specified.");
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
                            loadProperties();
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
        document.title="Add or Update Property Type";
    };

    const handleShow = () => {
        setShow(true);
    };

    

  return (
    <>
    
        <Modal size='lg' show={showaddproperty} onHide={handleCloseAddProperty} className='text-sm'>
        {(currentproperty !=='')?
            <>
                <Modal.Header className='justify-content-center bg-warning m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-dark'> 
                        <h5>Update Property for : {currentproperty.Plotname} ({currentproperty.Plotcode})</h5>
                    </Modal.Title>
                </Modal.Header>
            </>
            :
            <>
                <Modal.Header className='justify-content-center bg-info m-0 p-2' closeButton>
                    <Modal.Title className='mx-auto text-white'> 
                        <h5>Add New Property </h5>
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
                                <div className="border-info card p-1 pb-3 elevation-2">
                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Plotname" className="col-sm-4 col-12 col-form-label text-md-right">Name</label>

                                        <div className="col-sm-8 col-12 m-0 p-0">
                                            <input id="Plotname" type="text" className="form-control" name="Plotname" value={formdata.Plotname} onChange={handleInputChange} placeholder="Property Name" required autoComplete="Plotname" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Plotname && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Plotname}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Plotarea" className="col-sm-4 col-12 col-form-label text-md-right">Area</label>

                                        <div className="col-sm-8 col-12 m-0 p-0">
                                            <input id="Plotarea" type="text" className="form-control" name="Plotarea" value={formdata.Plotarea} onChange={handleInputChange} placeholder="Property Area" required autoComplete="Plotarea" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Plotarea && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Plotarea}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Plotcode" className="col-sm-4 col-12 col-form-label text-md-right">Code</label>

                                        <div className="col-sm-8 col-12 m-0 p-0">
                                            <input id="Plotcode" type="text" className="form-control" name="Plotcode" value={formdata.Plotcode} onChange={handleInputChange} placeholder="Property Code" required autoComplete="Plotcode" autoFocus/>
                                            {formdata.error_list && formdata.error_list.Plotcode && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Plotcode}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Plotaddr" className="col-sm-4 col-12 col-form-label text-md-right">Addr</label>

                                        <div className="col-sm-8 col-12 m-0 p-0">
                                            <textarea  id="Plotaddr" type="text" className="form-control" name="Plotaddr" value={formdata.Plotaddr} onChange={handleInputChange} placeholder="Property Address"></textarea>
                                            {formdata.error_list && formdata.error_list.Plotaddr && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Plotaddr}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 pb-2">
                                        <label htmlFor="Plotdesc" className="col-sm-4 col-12 col-form-label text-md-right">Desc</label>

                                        <div className="col-sm-8 col-12 m-0 p-0">
                                            <textarea id="Plotdesc" type="text" className="form-control" name="Plotdesc" value={formdata.Plotdesc} onChange={handleInputChange} placeholder="Property Description"></textarea>
                                            {formdata.error_list && formdata.error_list.Plotdesc && 
                                                <span className="help-block text-danger">
                                                    <strong>{formdata.error_list.Plotdesc}</strong>
                                                </span>
                                            }
                                        </div>
                                    </div>

                                    
                                </div>
                            </div>

                            <div className="col-6 m-0 p-1">
                                <div className="border-info card p-1 elevation-2">
                                    <div className="form-group row m-0 p-1 border-light mb-1 ">
                                        <label htmlFor="Waterbill" className="col-sm-5 col-12 col-form-label text-md-right">Water Bill</label>

                                        <div className="col-sm-7 col-12 m-0 p-0" style={{"float":"right"}}>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                    <input type="radio" required checked={formdata.Waterbill==="Monthly"?"true":""} onChange={handleInputChange} className="" name="Waterbill" value="Monthly" autoComplete="Waterbill"/> Paid Monthly
                                            </label>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                <input type="radio" required checked={formdata.Waterbill==="None"?"true":""} onChange={handleInputChange} className="" name="Waterbill" value="None" autoComplete="Waterbill"/> None
                                            </label>
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 border-light mb-1 ">
                                        <label htmlFor="Waterdeposit" className="col-sm-5 col-12 col-form-label text-md-right">Water Deposit</label>

                                        <div className="col-sm-7 col-12 m-0 p-0" style={{"float":"right"}}>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                    <input type="radio" required checked={formdata.Waterdeposit==="Once"?"true":""}  onChange={handleInputChange} className="" name="Waterdeposit" value="Once" autoComplete="Waterdeposit"/> Paid Once
                                            </label>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                <input type="radio" required checked={formdata.Waterdeposit==="None"?"true":""} onChange={handleInputChange} className="" name="Waterdeposit" value="None" autoComplete="Waterdeposit"/> None
                                            </label>
                                        </div>
                                    </div>

                                    

                                    <div className="form-group row m-0 p-1 border-light mb-1 ">
                                        <label htmlFor="Garbage" className="col-sm-5 col-12 col-form-label text-md-right">Garbage</label>

                                        <div className="col-sm-7 col-12 m-0 p-0" style={{"float":"right"}}>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                    <input type="radio" required checked={formdata.Garbage==="Monthly"?"true":""} onChange={handleInputChange} className="" name="Garbage" value="Monthly" autoComplete="Garbage"/> Paid Monthly
                                            </label>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                <input type="radio" required checked={formdata.Garbage==="None"?"true":""} onChange={handleInputChange} className="" name="Garbage" value="None" autoComplete="Garbage"/> None
                                            </label>
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 border-light mb-1 ">
                                        <label htmlFor="Kplcdeposit" className="col-sm-5 col-12 col-form-label text-md-right">KPLC Deposit</label>

                                        <div className="col-sm-7 col-12 m-0 p-0" style={{"float":"right"}}>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                    <input type="radio" required checked={formdata.Kplcdeposit==="Once"?"true":""} onChange={handleInputChange} className="" name="Kplcdeposit" value="Once" autoComplete="Kplcdeposit"/> Paid Once
                                            </label>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                <input type="radio" required checked={formdata.Kplcdeposit==="None"?"true":""} onChange={handleInputChange} className="" name="Kplcdeposit" value="None" autoComplete="Kplcdeposit"/> None
                                            </label>
                                        </div>
                                    </div>


                                    <div className="form-group row m-0 p-1 border-light mb-1 ">
                                        <label htmlFor="Outsourced" className="col-sm-5 col-12 col-form-label text-md-right">Outsourced Water</label>

                                        <div className="col-sm-7 col-12 m-0 p-0 text-sm-left">
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                    <input type="radio" required checked={formdata.Outsourced==="Yes"?"true":""} onChange={handleInputChange} className="" name="Outsourced" value="Yes" autoComplete="Outsourced"/> Yes
                                            </label>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                <input type="radio" required checked={formdata.Outsourced==="None"?"true":""} onChange={handleInputChange} className="" name="Outsourced" value="None" autoComplete="Outsourced" /> None
                                            </label>
                                        </div>
                                    </div>

                                    <div className="form-group row m-0 p-1 border-light mb-1 ">
                                        <label htmlFor="Deposit" className="col-sm-5 col-12 col-form-label text-md-right">Deposit</label>

                                        <div className="col-sm-7 col-12 m-0 p-0" style={{"float":"right"}}>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                    <input type="radio" required checked={formdata.Deposit==="Once"?"true":""} onChange={handleInputChange} className="" name="Deposit" value="Once" autoComplete="Deposit"/> Paid Once
                                                </label>
                                            <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                <input type="radio" required checked={formdata.Deposit==="None"?"true":""} onChange={handleInputChange} className="" name="Deposit" value="None" autoComplete="Deposit"/> None
                                                </label>
                                        </div>
                                    </div>

                                    

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
                                                {(currentproperty !=='')?'Updating':'Adding'} ...</span>
                                                
                                        </div>
                                    }

                                    {!loading && loadingresok ==='' && 
                                        <div className="col-md-10">
                                            <button type="submit" className="btn btn-success">
                                                {(currentproperty !=='')?'Update ':'Save Property'} {currentproperty.Plotname} Information
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
            <AddPropertyType show={show} handleClose={handleClose} currentproperty={currentproperty} propertydata={propertydata} loadPropertyHouseTypes={loadPropertyHouseTypes}/>
        }

    </>
  );
}

export default AddProperty;