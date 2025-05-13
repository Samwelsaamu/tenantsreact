import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useContext, useState } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';

import Carousel from 'react-bootstrap/Carousel';
import Button from 'react-bootstrap/Button';

import axios from 'axios';

import {Link, useNavigate} from 'react-router-dom';
import { useParams } from 'react-router';


import Select from 'react-select';
import Swal from 'sweetalert';

import AddHouse from './AddHouse';
import TableSmallSpinner from '../spinners/TableSmallSpinner';

import TenantsTable from './Tables/TenantsTable';
import ManagePropertyHseTenant from './ManagePropertyHseTenant';
import AddTenant from './AddTenant';
import ManagePropertyVacateHseTenant from './ManagePropertyVacateHseTenant';
import ReLogin from '../home/ReLogin';
import { LoginContext } from '../contexts/LoginContext';

const baseStyle={
    flex:1,
    display:"flex",
    flexDirection:"column",
    alignItems:"center",
    padding:"6px",
    borderWidth:2,
    borderRadius:2,
    border:"4px dotted #007bff",
    backgroundColor:"#ffffff",
    color:"#bdbdbd",
    outline:"none",
    transition:"border .24s ease-in-out"
}

const activeStyle={
    border:"2px dotted #6f42c1"
}

const acceptStyle={
    border:"2px dotted #00e676"
}

const rejectStyle={
    border:"2px dotted #ff1744"
}


function ManageTenants(props) {
    document.title="Manage Tenants";
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
         
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();

    let par=useParams()

    const [id,setID]=useState((par.id)?par.id:'')

    // console.log(id)

    const [closed,setClosed]=useState(false)

    const [waterbillpropertyid,setWaterbillPropertyId]=useState([""]);
    
    
    const [propertydata, setPropertydata] = useState([]);
    const [housedata, setHousedata] = useState([]);
    
    const [propertyinfo, setPropertyinfo] = useState([""]);
    const [currentproperty, setCurrentProperty] = useState('');
    
    // const [currentpropertyhouse, setCurrentProperty] = useState('');
    const [currenttenant, setCurrentTenant] = useState('');
    const [currenthouse, setCurrentHouse] = useState('');
    

    const [search,setSearch]=useState({
        value:'',
        result:[]
    })
    
    const [showvacatehouse,setShowVacateHouse]=useState(false);
    const [showaddproperty,setShowAddProperty]=useState(false);
    const [showaddtenant,setShowAddTenant]=useState(false);
    const [showaddhouse,setShowAddHouse]=useState(false);
    const [loadingmonths,setLoadingMonths]=useState(true);

    const [loading,setLoading]=useState(false);

    // useEffect( () =>{
    //     socket.on('load_credit_balance', (msg) =>{
    //         loadAppData();
    //     })

    // }, []);

    
    // useEffect(()=>{
    //     let doneloading=true;
    //     const getPrevMonths = async (e) => { 
    //         const arr = [];
    //             arr.push({value: '', label: 'Select Month' });
    //         const arr1 = [];
    //             arr1.push({value: '', label: 'Select Property' });
    //         const arr2 = [];    
    //         let url=`/v2/properties/manage/load/${id}`;
    //         if(id===''){
    //             url='/v2/properties/manage/load';
    //         }
    //         else{
    //             if(id==='all'){
    //                 url=`/v2/properties/manage/load`;
    //             }
    //             else{
    //                 setLoadingMonths(false)
    //                 return false;
    //             }
                
    //         }
    //         await axios.get(url)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     let respropertyinfo = response.data.propertyinfo;
                        
    //                     let resthisproperty = response.data.thisproperty;

    //                     setPropertydata(response.data.propertyinfo)
    //                     setHousedata([])
                        
    //                     let options=[];
    //                     if(id!==''){
    //                         options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
    //                     }
                        
    //                     setWaterbillPropertyId(options)
    //                 }
    //                 else if(response.data.status=== 401){
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else if(response.data.status=== 500){
    //                     Swal("Error",response.data.message,"error");
    //                 }
                    
    //             }
    //         })
    //         .catch(error=>{
    //             Swal("Error",""+error,"error");
    //         })
    //     };
    //     getPrevMonths();

    //     return ()=>{
    //         doneloading=false;
            
    //         setLoadingMonths(false)
    //     }
    // },[])

    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getPrevMonths = async (e) => { 
            const arr = [];
                arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Tenants IN' });

               
            let url=`/v2/properties/mgr/tenants/category/load/${id}`;
            if(id===''){
                url='/v2/properties/mgr/tenants/load';
            }
            else{
                if(id==='all'){
                    url=`/v2/properties/mgr/tenants/load`;
                }
                else{
                    setLoadingMonths(false)
                    return false;
                }
                
            }

            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let restenantinfo = response.data.tenantinfo;
                        
                        let resthisproperty = response.data.thisproperty;

                        setPropertydata(response.data.tenantinfo)
                        setHousedata([])

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });

                        
                        arr1.push({value: 'All', label: 'All' });
                        arr1.push({value: 'Vacated', label: 'Vacated' });
                        arr1.push({value: 'Assigned', label: 'Assigned' });
                        arr1.push({value: 'Reassigned', label: 'Reassigned' });
                        arr1.push({value: 'Transferred', label: 'Transferred' });
                        arr1.push({value: 'New', label: 'New Tenants' });
                        arr1.push({value: 'Other', label: 'Others' });
                        setPropertyinfo(arr1)

                        let options=[];
                        if(id!==''){
                            if(id==='All' || id==='Vacated' || id==='New' || id==='Assigned' || id==='Reassigned' || id==='Other' || id==='Transferred'){
                                options={value: id, label: id , data: resthisproperty}
                            }
                            
                            else{
                                options={value: resthisproperty[0].id, label: resthisproperty[0].Plotname+'('+resthisproperty[0].Plotcode+')' , data: resthisproperty}
                            }
                        }
                        
                        
                        setWaterbillPropertyId(options)
                        setLoadingMonths(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
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
                    setLoadingMonths(false)
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingMonths(false)
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
        getPrevMonths();

        return ()=>{
            doneloading=false;
            
            setLoadingMonths(false)
        }
    },[loggedoff])

    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getPrevMonths = async (e) => { 
            const arr = [];
                arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Tenants IN' });
                
               
            let url=`/v2/properties/mgr/tenants/category/load/${id}`;
            // console.log(id)
            if(id===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                url=`/v2/properties/mgr/tenants/category/load/${id}`;
            }

            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let restenantinfo = response.data.tenantinfo;
                        
                        let resthisproperty = response.data.thisproperty;

                        setPropertydata(response.data.tenantinfo)
                        setHousedata([])

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });

                        arr1.push({value: 'All', label: 'All' });
                        arr1.push({value: 'Vacated', label: 'Vacated' });
                        arr1.push({value: 'Assigned', label: 'Assigned' });
                        arr1.push({value: 'Reassigned', label: 'Reassigned' });
                        arr1.push({value: 'Transferred', label: 'Transferred' });
                        arr1.push({value: 'New', label: 'New Tenants' });
                        arr1.push({value: 'Other', label: 'Others' });
                        setPropertyinfo(arr1)

                        

                        let options=[];
                        if(id!==''){
                            if(id==='All' || id==='Vacated' || id==='New' || id==='Assigned' || id==='Reassigned' || id==='Other' || id==='Transferred'){
                                options={value: id, label: id , data: resthisproperty}
                            }
                            
                            else{
                                options={value: resthisproperty[0].id, label: resthisproperty[0].Plotname+'('+resthisproperty[0].Plotcode+')' , data: resthisproperty}
                            }
                            
                        }
                        setWaterbillPropertyId(options)
                        setLoadingMonths(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
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
                    setLoadingMonths(false)
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingMonths(false)
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
        getPrevMonths();

        return ()=>{
            doneloading=false;
            
            setLoadingMonths(false)
        }
    },[id,loggedoff])

    const loadTenants =() =>{
        let doneloading=true;
        if (doneloading) {
            // setLoadingMonths(true)
        }
        const getPrevMonths = async (e) => { 
            const arr = [];
                arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Tenants IN' });
                
               
            let url=`/v2/properties/mgr/tenants/category/load/${id}`;
            // console.log(id)
            if(id===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                url=`/v2/properties/mgr/tenants/category/load/${id}`;
            }

            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let restenantinfo = response.data.tenantinfo;
                        
                        let resthisproperty = response.data.thisproperty;

                        setPropertydata(response.data.tenantinfo)
                        setHousedata([])

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });

                        arr1.push({value: 'All', label: 'All' });
                        arr1.push({value: 'Vacated', label: 'Vacated' });
                        arr1.push({value: 'Assigned', label: 'Assigned' });
                        arr1.push({value: 'Reassigned', label: 'Reassigned' });
                        arr1.push({value: 'Transferred', label: 'Transferred' });
                        arr1.push({value: 'New', label: 'New Tenants' });
                        arr1.push({value: 'Other', label: 'Others' });
                        setPropertyinfo(arr1)

                        

                        let options=[];
                        if(id!==''){
                            if(id==='All' || id==='Vacated' || id==='New' || id==='Assigned' || id==='Reassigned' || id==='Other' || id==='Transferred'){
                                options={value: id, label: id , data: resthisproperty}
                            }
                            
                            else{
                                options={value: resthisproperty[0].id, label: resthisproperty[0].Plotname+'('+resthisproperty[0].Plotcode+')' , data: resthisproperty}
                            }
                            
                        }
                        setWaterbillPropertyId(options)
                        // setLoadingMonths(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        // setLoadingMonths(false)
                    }
                    // setLoadingMonths(false)
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
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
        getPrevMonths();
    }

    // properties/mgr/tenants
    useEffect(()=>{
        if(id==='all'){
            let thisurl=`/properties/mgr/tenants`;
            navigate(thisurl)
        }
        else{
            if(id!==''){
                let thisurl=`/properties/mgr/tenants/category/${id}`;
                navigate(thisurl)
            }
        }
       
    },[id])

    const handleShowVacateHouse = (property) => {
        setShowVacateHouse(true);
        setCurrentProperty(property)
        // console.log('current pro',property)
    };

    const handleCloseVacateHouse = () => {
        setShowVacateHouse(false);
        document.title="Vacate Tenant ";
    };

    const handleShowAddProperty = (property) => {
        setShowAddProperty(true);
        setCurrentProperty(property)
        // console.log('dsd',property)
    };

    const handleCloseAddProperty = () => {
        setShowAddProperty(false);
        document.title="Manage Properties";
    };

    const handleShowAddTenant = (tenant) => {
        setShowAddTenant(true);
        setCurrentTenant(tenant)
    };

    const handleCloseAddTenant = () => {
        setShowAddTenant(false);
        document.title="Manage Tenants";
    };
    

    // const handleShowAddHouse = (property) => {
    //     setShowAddHouse(true);
    //     setCurrentProperty(property)
    //     setCurrentHouse('')
    // };

    const handleShowAddHouse = (property) => {
        setShowAddHouse(true);
        setCurrentHouse(property)
    };

    const handleCloseAddHouse = () => {
        setShowAddHouse(false);
        document.title="Manage House";
    };


    function handlePropertyChange(val) {
        
        // console.log(val.label)
        setLoadingMonths(true)
        setID(val.value)
        let options={value: val.value, label: val.label , data: val}
        setWaterbillPropertyId(options) 
        setLoadingMonths(false)
    }

    const handleSearchChange =(e) =>{
        setLoadingMonths(true)
            const results=propertydata.filter(property =>{
                if(e.target.value=== '') return propertydata
                const value_array=e.target.value.split(':');
                if(value_array.length > 1){
                    let lbl=value_array[0];
                    let vals=value_array[1];
                    if(vals=== '') return propertydata
                    if(lbl==='fname') return property.Fname.toLowerCase().includes(vals.toLowerCase())
                    else if(lbl==='oname') return property.Oname.toLowerCase().includes(vals.toLowerCase())
                    else if(lbl==='phone') return property.PhoneMasked.toLowerCase().includes(vals.toLowerCase())
                    else if(lbl==='idno') return property.IDno.toLowerCase().includes(vals.toLowerCase())
                    else if(lbl==='status') return property.Status.includes(vals)
                    else if(lbl==='house') return property.Housenames.toLowerCase().includes(vals.toLowerCase())
                    else if(lbl==='totalhouses') return property.Houses.toString().toLowerCase()===vals.toLowerCase()
                }
                else{
                    return property.Fname.toLowerCase().includes(e.target.value.toLowerCase()) ||
                        property.Oname.toLowerCase().includes(e.target.value.toLowerCase()) ||
                        property.PhoneMasked.toLowerCase().includes(e.target.value.toLowerCase()) ||
                        property.IDno.toLowerCase().includes(e.target.value.toLowerCase()) ||
                        property.Status.includes(e.target.value) ||
                        property.Housenames.toLowerCase().includes(e.target.value.toLowerCase())
                }
                
            })

            setSearch({
                value:e.target.value,
                result:results
            })
        setLoadingMonths(false)
    }


    const deleteTenant= (property)=>{
        const form={
            id:property.id,
        }

        let title='Delete '+property.Fname+' '+property.Oname;
        let text="This will remove this Tenant from the system.";
        Swal({
            title:title+' ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Deleting....","Please Wait");
                axios.post('/v2/delete/tenant/save',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        Swal("Success",response.data.message);
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    loadTenants();
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
                            Swal("Error",""+error,"error");
                        }
                        setLoading(false)
                    }
                    else{
                        let ex=error['response'].data.message;
                        if(ex==='Unauthenticated.'){
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }
                        else{
                            setLoading(false)
                            Swal("Error",""+error,"error");
                        }
                    }
                })
            }
            else{
                setLoading(false);
            }
        })

    }

    useEffect(()=>{
        
    },[])

    

  return (
    <>
    <div className="wrapper">
    {loggedoff ? 
            <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
        :
        <>
        <DashNavBar setClosed={setClosed} closed={closed} active='tenant'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='tenant'/>
        {/* className={`nav-link ${active==='home'?'active':''}`} */}
        
        <main className="py-1">
            
            <div className={`content-wrapper ${closed?'closed':''}`}>
                    
                    <section className="content">
                    <div className="p-2">
                        {/* container class */}
                        <div className="row justify-content-center m-0 p-0">
                        <div className="col-12 m-0 p-0 ">
                            <ol className="breadcrumb float-sm-right text-xs m-0 p-1">
                                <li className="breadcrumb-item"><Link to={'/dashboard'}>Dashboard</Link></li>
                                <li className="breadcrumb-item active">{ !loadingmonths && waterbillpropertyid.label } Tenants </li>
                            </ol>
                        </div>


                        <div className="col-12">
                            <div className="row m-0 p-0">
                                

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info m-0 p-0" >
                                        <div className="card-header bg-info elevation-2 m-0 p-0">
            
                                            <div className='row justify-content-center text-center p-1 m-0'>
                                                

                                                <div className="col-lg-4 text-xs m-0 p-0">
                                                    
                                                    {loadingmonths &&
                                                        <Spinner  variant="info" size="md" role="status"></Spinner>
                                                    }
                                                    {!loadingmonths &&
                                                        <Select
                                                            placeholder= "Select Tenants IN"
                                                            value={waterbillpropertyid}
                                                            name="waterbill-property"
                                                            options={propertyinfo}
                                                            onChange={handlePropertyChange}
                                                        />
                                                    }
                                                </div>

                                                <div className="col-lg-8 text-xs float-right m-0 p-0">
                                                    {loadingmonths &&
                                                        <Spinner  variant="light" size="md" role="status"></Spinner>
                                                    }
                                                     
                                                    <button className='btn btn-success border-info m-1 p-1 pl-2 pr-2' onClick={()=>{handleShowAddTenant('')}}><small><i className='fa fa-plus-circle'></i> Tenant</small></button>
                                                    {propertydata  && propertydata.length>0 && <input onChange={handleSearchChange} value={search.value} className='border-info p-1 pt-0 pb-0 col-6' placeholder='Find Fname, Oname, Property Code,Phone,Status etc' />}
                                                    
                                                </div>
                                                <div className="col-lg-12 text-xs m-0 p-0">
                                                    <p className='m-0 p-0'>Find using:: fname:value, oname:value, phone:value, idno:value, status:value, house:value, totalhouses:value</p>
                                                    <p className='text-warning'>Click on House to Add Another House or Change/Re Assign House</p>
                                                </div>


                                            </div>
                                            
                                        </div>

                                        <div className="card-body text-center m-0 p-1">
                                        
                                            <div className="row m-0 p-0">
                                                 {loadingmonths &&
                                                    <div className="col-12 text-left m-0 p-1 mt-1 mb-2">
                                                        <TableSmallSpinner />
                                                    </div>
                                                 }
                                                 {!loadingmonths &&
                                                    <div className="tableinfo col-12 m-0 p-0" style={{"overflowX":"auto"}}>
                                                        <table border="1" className="table table-hover table-bordered text-xs" id="example1">
                                                            {propertydata  && propertydata.length>0 &&
                                                                <thead>
                                                                <tr  style={{"color":"white","backgroundColor":"#77B5ED"}}>
                                                                    <th className='elevation-2 m-0 p-1'>Sno</th>
                                                                    <th className='elevation-2 m-0 p-1'>Firstnames</th>
                                                                    <th className='elevation-2 m-0 p-1'>Oname</th>
                                                                    {/* <th className='elevation-2 m-0 p-1'>Gender</th>
                                                                    <th className='elevation-2 m-0 p-1'>IDno</th>
                                                                    <th className='elevation-2 m-0 p-1'>Phone</th> */}
                                                                    <th className='elevation-2 m-0 p-1'>Status</th>
                                                                    <th className='elevation-2 m-0 p-1'>No of Houses</th>
                                                                    <th className='elevation-2 m-0 p-1'>House(s)</th>
                                                                    <th className='elevation-2 m-0 p-1'>Action</th>
                                                                </tr></thead>
                                                            }
                                                            
                                                            <tbody>
                                                                {propertydata  && propertydata.length>0 &&
                                                                    <>
                                                                        {(search.value==='')?
                                                                        <>
                                                                            {propertydata  && propertydata.map((property,key) => (
                                                                                <TenantsTable property={property} key={key} no={key} handleShowAddTenant={handleShowAddTenant} handleShowAddProperty={handleShowAddProperty} deleteTenant={deleteTenant} />
                                                                            ))
                                                                            }
                                                                        </>
                                                                    :
                                                                        <>
                                                                            {search.result  && search.result.map((property,key) => (
                                                                                <TenantsTable property={property} key={key} no={key} handleShowAddTenant={handleShowAddTenant} handleShowAddProperty={handleShowAddProperty} deleteTenant={deleteTenant} />
                                                                            ))
                                                                            }
                                                                        </>
                                                                    
                                                                    }
                                                                    </>
                                                                }
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                 }
                                            </div>

                                        </div>

                                        

                                    </div>
                                </div>

                                
                                {showvacatehouse && 
                                    <ManagePropertyVacateHseTenant showvacatehouse={showvacatehouse} handleCloseVacateHouse={handleCloseVacateHouse} handleShowAddHouse={handleShowAddHouse} handleCloseAddProperty={handleCloseAddProperty} currentpropertyhousesel={currentproperty} loadTenants={loadTenants} />
                                }


                                {showaddproperty && 
                                    <ManagePropertyHseTenant showaddproperty={showaddproperty} handleCloseAddProperty={handleCloseAddProperty} handleShowAddHouse={handleShowAddHouse} handleShowVacateHouse={handleShowVacateHouse} currentproperty={currentproperty} />
                                }

                                {showaddhouse && 
                                    <AddHouse showaddhouse={showaddhouse} handleCloseAddHouse={handleCloseAddHouse} currentproperty={currentproperty} currenthouse={currenthouse}/>
                                }

                                {showaddtenant && 
                                    <AddTenant showaddtenant={showaddtenant} handleCloseAddTenant={handleCloseAddTenant} currenttenant={currenttenant} loadTenants={loadTenants} />
                                }

                                
                            </div>
                        </div>

                        
                            
                        </div>

                    </div>


                </section>
            </div>
        </main>


        <DashFooter />
        </>
        }
      </div>
    </>
  );
}

export default ManageTenants;