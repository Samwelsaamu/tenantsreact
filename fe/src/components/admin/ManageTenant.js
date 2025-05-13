import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useContext, useState } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';

import Carousel from 'react-bootstrap/Carousel';
import Button from 'react-bootstrap/Button';

import axios from 'axios';

import {useNavigate,Link} from 'react-router-dom';
import { useParams } from 'react-router';


import Select from 'react-select';
import Swal from 'sweetalert';

import AddHouse from './AddHouse';
import TableSmallSpinner from '../spinners/TableSmallSpinner';

import TenantsTable from './Tables/TenantsTable';
import ManagePropertyHseTenant from './ManagePropertyHseTenant';
import AddTenant from './AddTenant';
import HouseDetailsSpinner from '../spinners/HouseDetailsSpinner';
import HouseTenantDetailsSpinner from '../spinners/HouseTenantDetailsSpinner';
import ManagePropertyVacateHseTenant from './ManagePropertyVacateHseTenant';

import avatar from '../../assets/img/avatar5.png';
import avatar3 from '../../assets/img/avatar2.png';
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


function ManageTenant(props) {
    document.title="Manage Tenant";
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
         
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();

    let par=useParams()

    const [id,setID]=useState((par.id)?par.id:'')

    // console.log(id)

    const [closed,setClosed]=useState(false)

    const [waterbillpropertyid,setWaterbillPropertyId]=useState([""]);
    const [tenantsid,setTenantsId]=useState([""]);
    const [plot,setPlotID]=useState([""]);
    const [house,setHouseID]=useState([""]);
    const [managehouseid,setManageHouseId]=useState([""]);
    const [houseinfo, setHouseinfo] = useState([""]);
    
    
    const [propertydata, setPropertydata] = useState([]);
    const [tenantdata, setTenantdata] = useState([]);
    const [agreementdata, setAgreementdata] = useState([]);
    
    const [propertyinfo, setPropertyinfo] = useState([""]);
    const [tenantinfo, setTenantinfo] = useState([""]);
    const [currentproperty, setCurrentProperty] = useState('');
    
    // const [currentpropertyhouse, setCurrentProperty] = useState('');
    const [currenttenant, setCurrentTenant] = useState('');
    const [currenthouse, setCurrentHouse] = useState('');

    const [currentwaterbill, setCurrentWaterbill] = useState([""]);
    const [show,setShow]=useState(false);
    

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

    

    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getPrevMonths = async (e) => { 
            const arr = [];
                arr.push({value: '', label: 'Select House' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Tenant' });
            const arr2 = [];
                arr2.push({value: '', label: 'Select Property' });

               
            let url=`/v2/properties/mgr/tenants/load/${id}`;
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
                        let reshouseinfo = response.data.houseinfo;
                        let restenantinfo = response.data.tenantinfo;
                        
                        let resthisproperty = response.data.thisproperty;
                        let resthistenant = response.data.thistenant;

                        setPropertydata(response.data.tenantinfo)
                        setTenantdata(response.data.thistenant)
                        setAgreementdata(response.data.agreementinfo);

                        restenantinfo.map((tenantsdata) => {
                            return arr1.push({value: tenantsdata.id, label: tenantsdata.Fname+' '+tenantsdata.Oname+'('+tenantsdata.Status+')' , data: tenantsdata});
                        });
                        setTenantinfo(arr1)

                        respropertyinfo.map((monthsup) => {
                            return arr2.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr2)

                        reshouseinfo.map((houseup) => {
                            return arr.push({value: houseup.id, label: houseup.Housename+'('+houseup.Status+')' , data: houseup});
                        });
                        setHouseinfo(arr)


                        let options=[];
                        if(id!==''){
                            options={value: resthistenant[0].id, label: resthistenant[0].Fname+' '+resthistenant[0].Oname+'('+resthistenant[0].Status+')' , data: resthistenant}
                        }
                        
                        
                        setTenantsId(options)
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
                arr.push({value: '', label: 'Select House' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Tenant' });
            const arr2 = [];
                arr2.push({value: '', label: 'Select Property' });
                
               
            let url=`/v2/properties/mgr/tenants/load/${id}`;
            // console.log(id)
            if(id===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                url=`/v2/properties/mgr/tenants/load/${id}`;
            }

            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let restenantinfo = response.data.tenantinfo;
                        let reshouseinfo = response.data.houseinfo;
                        
                        let resthisproperty = response.data.thisproperty;
                        let resthistenant = response.data.thistenant;

                        setPropertydata(response.data.tenantinfo)
                        setTenantdata(response.data.thistenant)
                        setAgreementdata(response.data.agreementinfo);

                        restenantinfo.map((tenantsdata) => {
                            return arr1.push({value: tenantsdata.id, label: tenantsdata.Fname+' '+tenantsdata.Oname+'('+tenantsdata.Status+')' , data: tenantsdata});
                        });
                        setTenantinfo(arr1)

                        respropertyinfo.map((monthsup) => {
                            return arr2.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr2)

                        reshouseinfo.map((houseup) => {
                            return arr.push({value: houseup.id, label: houseup.Housename+'('+houseup.Status+')' , data: houseup});
                        });
                        setHouseinfo(arr)

                        let options=[];
                        if(id!==''){
                            options={value: resthistenant[0].id, label: resthistenant[0].Fname+' '+resthistenant[0].Oname+'('+resthistenant[0].Status+')' , data: resthistenant}
                        }
                        
                        
                        setTenantsId(options)
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
                arr.push({value: '', label: 'Select House' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Tenant' });
            const arr2 = [];
                arr2.push({value: '', label: 'Select Property' });
                
               
            let url=`/v2/properties/mgr/tenants/load/${id}`;
            // console.log(id)
            if(id===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                url=`/v2/properties/mgr/tenants/load/${id}`;
            }

            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let restenantinfo = response.data.tenantinfo;
                        
                        let resthisproperty = response.data.thisproperty;
                        let resthistenant = response.data.thistenant;
                        // let resthishouse = response.data.thishouse;
                        let reshouseinfo = response.data.houseinfo;

                        setPropertydata(response.data.tenantinfo)
                        setTenantdata(response.data.thistenant)
                        setAgreementdata(response.data.agreementinfo);

                        restenantinfo.map((tenantsdata) => {
                            return arr1.push({value: tenantsdata.id, label: tenantsdata.Fname+' '+tenantsdata.Oname+'('+tenantsdata.Status+')' , data: tenantsdata});
                        });
                        setTenantinfo(arr1)

                        respropertyinfo.map((monthsup) => {
                            return arr2.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr2)

                        reshouseinfo.map((houseup) => {
                            return arr.push({value: houseup.id, label: houseup.Housename+'('+houseup.Status+')' , data: houseup});
                        });
                        setHouseinfo(arr)

                        let options=[];
                        if(id!==''){
                            options={value: resthistenant[0].id, label: resthistenant[0].Fname+' '+resthistenant[0].Oname+'('+resthistenant[0].Status+')' , data: resthistenant}
                        }
                        setTenantsId(options)

                        // let optionshse=[];
                        // if(id!==''){
                        //     optionshse={value: resthishouse.id, label: resthishouse.Housename+'('+resthishouse.Status+')' , data: resthishouse}
                        // }
                        // setManageHouseId(optionshse)


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


    const handleShowVacateHouse = (property) => {
        setShowVacateHouse(true);
        setCurrentProperty(property)
    };

    const handleCloseVacateHouse = () => {
        setShowVacateHouse(false);
        document.title="Vacate Tenant ";
    };

    const handleShowAddProperty = (property) => {
        setShowAddProperty(true);
        setCurrentProperty(property)
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


    function handleTenantChange(val) {
        setLoadingMonths(true)
        setID(val.value)
        setHouseID('')
        setPlotID('')
        let options={value: val.value, label: val.label , data: val}
        setTenantsId(options) 
        
        const tenantid=val.value;
        if(tenantid!==''){
            let thisurl=`/properties/mgr/tenants/${tenantid}`;
            navigate(thisurl)
        }
        setLoadingMonths(false)
    }

    function handleHouseChange(val) {
        setLoadingMonths(true)
        setHouseID(val.value)
        let options={value: val.value, label: val.label , data: val}
        setManageHouseId(options) 
        const plotid=val.data['plot'];
        const houseid=val.value;
        setPlotID(val.data['plot'])

        // alert(val.data['plot'])

        if(houseid!=='' && plotid!==''){
            let thisurl=`/properties/house/${plotid}/${houseid}`;
            navigate(thisurl)
        }
        setLoadingMonths(false)
    }

    function handlePropertyChange(val) {
        // console.log(val)
        setLoadingMonths(true)
        setPlotID(val.value)
        let options={value: val.value, label: val.label , data: val}
        setWaterbillPropertyId(options) 

        const plotid=val.value;
        if(plotid!==''){
            let thisurl=`/properties/mgr/tenants/category/${plotid}`;
            navigate(thisurl)
        }
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

    const handleShow = (waterbill) => {
        // setShow(true);
        // setCurrentWaterbill(waterbill)
    };

    

    

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
                                    <li className="breadcrumb-item"><Link to={'/properties/mgr/tenants'}>Tenants</Link></li>
                                    <li className="breadcrumb-item">{ !loadingmonths && tenantdata.tenantname } </li>
                                    <li className="breadcrumb-item active">In House : { !loadingmonths && tenantdata.Housenames && tenantdata.Housenames} </li>
                                </ol>
                            </div>

                        <div className="col-12">
                            <div className="row m-0 p-0">
                                

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-none m-0 p-0" >
                                        <div className="card-header text-white elevation-2 m-0 p-0">

                                            <div className='row justify-content-center text-center p-1 m-0'>
                                                <div className="col-12 col-md-6 col-lg-4 m-0 p-1 text-sm text-dark">
                                                    {loadingmonths &&
                                                        <Spinner  variant="blue" size="md" role="status"></Spinner>
                                                    }
                                                    {!loadingmonths &&
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

                                                <div className="col-12 col-md-6 col-lg-4 m-0 p-1 text-sm text-dark">
                                                    {loadingmonths &&
                                                        <Spinner  variant="blue" size="md" role="status"></Spinner>
                                                    }
                                                    {!loadingmonths &&
                                                        <Select
                                                            className='text-sm'
                                                            placeholder= "Select House"
                                                            value={managehouseid}
                                                            name="house-property"
                                                            options={houseinfo}
                                                            onChange={handleHouseChange}
                                                        />
                                                    }
                                                </div>

                                                <div className="col-12 col-md-6 col-lg-4 m-0 p-1 text-sm text-dark">
                                                    {loadingmonths &&
                                                        <Spinner  variant="info" size="md" role="status"></Spinner>
                                                    }
                                                    {!loadingmonths &&
                                                        <Select
                                                            placeholder= "Select Tenant"
                                                            value={tenantsid}
                                                            name="waterbill-property"
                                                            options={tenantinfo}
                                                            onChange={handleTenantChange}
                                                        />
                                                    }
                                                </div>
                                                <div className="col-12 m-0 p-1 mt-1">
                                                    
                                                    <h4 className='text-info mx-auto text-center'>
                                                        Viewing Tenant 
                                                        <span className='text-lime'> { !loadingmonths && tenantdata.tenantname } </span>
                                                        <small>
                                                            <i className='text-purple'>
                                                             { !loadingmonths?tenantdata.Housenames?'( '+tenantdata.Housenames+' )':'( '+tenantdata.Status+' )':""} 
                                                            </i>
                                                        </small>
                                                        <button className='btn btn-success border-info  m-1 p-1 pl-2 pr-2' onClick={()=>{handleShowAddTenant('')}}>
                                                            <small><i className='fa fa-plus-circle'></i> Add New Tenant</small>
                                                        </button>
                                                    </h4>
                                            </div>
                                            </div>
                                            
            
                                            
                                        </div>

                                        <div className="card-body text-center m-0 p-1">
                                        
                                            <div className="row m-0 p-0">
                                                

                                                <div className="row m-0 p-0" style={{"overflowX":"auto"}}>
                                                    {loadingmonths &&
                                                        <div className="col-12 col-md-6 col-lg-4 text-left m-0 p-1 mt-1 mb-2">
                                                            <HouseDetailsSpinner />
                                                        </div>
                                                    }
                                                     
                                                    
                                                    {!loadingmonths &&  tenantdata[0].Status &&
                                                    <div className="col-12 col-md-12 col-lg-6 text-left m-0 p-1 mt-1 mb-2">
                                                        <div className="card border-info housedetails m-0 p-1" >
                                                            <div className="card-header text-muted text-center m-0 p-0 pt-1 pb-2">
                                                                <span style={{"float":"left"}}>
                                                                    <span className={`m-0 p-1 text-sm bg-light ${tenantdata.Status==='Vacated'?'text-danger':'text-info'}`}
                                                                        style={{"borderRadius":"30px","border":"2px solid white"}}> {tenantdata.tenantname}</span>
                                                                        
                                                                </span> 
                                                                <span className='m-0 p-1 text-sm text-dark mx-auto'>{tenantdata.Housenames}</span>
                                                                <span style={{"float":"right"}}>
                                                                    {tenantdata.Gender==='Male' ?
                                                                        <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                            style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar} alt="Tenant Profile" />
                                                                    :
                                                                        <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                            style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar3} alt="Tenant Profile" />
                                                                    }
                                                                </span>
                                                                
                                                                
                                                            </div>
                                                            
                                                            <div className="card-body text-left text-muted text-sm m-0 p-0 pt-1">
                                                                <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                                                    <div className="col-8 col-lg-7 m-0 p-0">
                                                                        {tenantdata.Fname} {tenantdata.Oname} 
                                                                           
                                                                    </div>
                                                                    <div className="col-4 col-lg-5 m-0 p-0">
                                                                        
                                                                        <span style={{"float":"right"}}>
                                                                            <span className={`m-0 p-1 text-sm bg-light ${tenantdata.Status==='Vacated'?'text-danger':'text-success'}`}
                                                                                style={{"borderRadius":"30px","border":"2px solid white"}}> {tenantdata.Status}</span>  
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                                                    <div className="col-6 col-lg-6 m-0 p-0">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-4 m-0 p-0 text-md-right">Phone:</label>

                                                                            <div className="col-8">
                                                                                {tenantdata.PhoneMasked}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-6 col-lg-6 m-0 p-0">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">Gender:</label>

                                                                            <div className="col-7">
                                                                                {tenantdata.Gender}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            
                                                            
                                                                <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                                                    <div className="col-6 m-0 p-0">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">IDno:</label>

                                                                            <div className="col-7 m-0 p-0">
                                                                                {tenantdata.IDno}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-6 m-0 p-0">
                                                                    
                                                                        <div className='d-flex justify-content-center m-0 p-0'>
                                                                        {tenantdata.Status &&
                                                                            <>
                                                                                <button className='bg-warning m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-dark' onClick={()=>{handleShowAddTenant(tenantdata[0])}}><small><i className='fa fa-edit'></i> Edit</small></button>
                                                                                {tenantdata.Status==='New' &&
                                                                                    <button className='bg-danger m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{deleteTenant(tenantdata[0])}}><small><i className='fa fa-trash'> Delete</i></small></button>
                                                                                }
                                                                            </>
                                                                        }
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                                                    <div className='d-flex justify-content-center m-0 p-0'>
                                                                        {tenantdata.Status &&
                                                                            <>
                                                                                {(tenantdata.Status==='New' || tenantdata.Status==='Vacated' || tenantdata.Status==='Other') ?
                                                                                    <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+tenantdata[0].id+'/assign/'} className='text-info'><i className='fa fa-check'></i> Assign New House</Link></button>
                                                                                    :
                                                                                    <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+tenantdata[0].id+'/addhouse/'} className='text-info'><i className='fa fa-plus-circle'></i> Add Another House</Link></button>
                                                                                }

                                                                                
                                                                            </>
                                                                        }           
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
                                                            {agreementdata  && agreementdata.map((agreement,key) => (
                                                                <div key={key}  className="col-12 col-lg-6 text-left m-0 p-1 mt-1 mb-2">
                                                                    {agreement.Status === 'Vacated' || agreement.Status === 'Deleted' ?
                                                                        <div className="card border-danger housedetails m-0 p-0" >
                                                                            <div className="card-header text-muted m-0 p-0 pt-1">
                                                                                <span className='m-0 p-1 text-danger bg-light'
                                                                                            style={{"borderRadius":"50%","border":"2px solid white"}}> <i className='fa fa-ban'></i></span>  
                                                                                <span>{agreement.Housename && agreement.Housename} ({key+1})</span>
                                                                                <span style={{"float":"right"}}>
                                                                                    <span className={`m-0 p-1 text-sm bg-light ${tenantdata.Status==='Vacated'?'text-danger':'text-success'}`}
                                                                                        style={{"borderRadius":"30px","border":"2px solid white"}}> {tenantdata.Status}</span>  
                                                                                </span>
                                                                                
                                                                            </div>
                                                                            <div className="card-body text-center text-danger text-sm m-0 p-1">
                                                                                <p><span>Status : </span><strong>{agreement.Status}</strong></p>
                                                                                <p><span>{agreement.Status} On : </span><strong>{agreement.DateVacated}</strong></p>
                                                                            </div>
                                                                        </div>
                                                                    :
                                                                        <>
                                                                            {agreement.Tenant === tenantdata.id ?
                                                                                <div className="card border-ok housedetails m-0 p-0" >
                                                                                    <div className="card-header text-dark m-0 p-0 pt-1">
                                                                                        <span className='m-1 p-1 text-lime bg-light'
                                                                                            style={{"borderRadius":"50%","border":"2px solid white"}}> <i className='fa fa-check'> </i></span> 
                                                                                        <span>{agreement.Housename && agreement.Housename} ({key+1})</span>
                                                                                        <span style={{"float":"right"}}>
                                                                                            <span className={`m-0 p-1 text-sm bg-light ${tenantdata.Status==='Vacated'?'text-danger':'text-success'}`}
                                                                                                style={{"borderRadius":"30px","border":"2px solid white"}}> {tenantdata.Status}</span>  
                                                                                        </span>
                                                                                        
                                                                                    </div>
                                                                                    <div className="card-body text-center text-sm m-0 p-1">
                                                                                        <p><span>Status : </span><strong className='text-success'>{agreement.Status}</strong> (<span>{agreement.DateAssigned}</span>)</p>
                                                                                        {agreement.housesoccupied && <p><span>Other Houses ({agreement.housesassigned -1}) : </span><strong>{agreement.housesoccupied}</strong></p>}
                                                                                        
                                                                                        <p>
                                                                                        
                                                                                            {/* <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-danger' onClick={()=>{handleShowVacateHouse(currentproperty)}}><small><i className='fa fa-minus-circle'></i> Vacate {housedata.Housename}</small></button> */}
                                                                                            <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-danger'><Link to={'/properties/mgr/tenants/'+agreement.Tenant+'/vacate/'+agreement.House} className='text-danger'><i className='fa fa-minus-circle'></i> Vacate </Link></button>
                                                                                            <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+agreement.Tenant+'/reassign/'+agreement.House} className='text-success'><i className='fa fa-exchange-alt'></i> Change House</Link></button>
                                                                                            <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+agreement.Tenant+'/transfer/'+agreement.House} className='text-primary'><i className='fa fa-play'></i> Transfer IN</Link></button>
                                                                                                   
                                                                                            
                                                                                        </p>
                                                                                        <div>
                                                                                        
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            :
                                                                                <div className="card border-info housedetails m-0 p-0" >
                                                                                    <div className="card-header text-muted m-0 p-0">
                                                                                        <span className='m-0 p-1 text-muted bg-light'
                                                                                            style={{"borderRadius":"50%","border":"2px solid white"}}> <i className='fa fa-ban'></i></span>  
                                                                                        <span >{agreement.tenantname} ({key+1})</span>
                                                                                        <span style={{"float":"right"}}>
                                                                                            <span className={`m-0 p-1 text-sm bg-light ${tenantdata.Status==='Vacated'?'text-danger':'text-success'}`}
                                                                                                style={{"borderRadius":"30px","border":"2px solid white"}}> {tenantdata.Status}</span>  
                                                                                        </span>
                                                                                        
                                                                                    </div>
                                                                                    <div className="card-body text-center text-muted text-sm m-0 p-1">
                                                                                        <p><span>Status : </span><strong>{agreement.Status}</strong> (<span>{agreement.DateTo}</span>)</p>
                                                                                        <p><span>Current House : </span><strong>{agreement.Housename}</strong></p>
                                                                                    </div>
                                                                                </div>
                                                                            }
                                                                        </>
                                                                    }
                                                                </div>
                                                                    
                                                                ))
                                                            }
                                                        </>
                                                    }


                                                </div>
                                            </div>

                                        </div>

                                        

                                    </div>
                                </div>

                                
                                {showvacatehouse && 
                                    <ManagePropertyVacateHseTenant showvacatehouse={showvacatehouse} handleCloseVacateHouse={handleCloseVacateHouse} handleShowAddHouse={handleShowAddHouse} handleCloseAddProperty={handleCloseAddProperty} currentproperty={currentproperty} loadTenants={loadTenants} />
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

export default ManageTenant;