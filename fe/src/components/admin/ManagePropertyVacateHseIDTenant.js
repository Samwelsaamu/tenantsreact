import Modal from 'react-bootstrap/Modal';

import axios from 'axios';
import {Link, useNavigate} from 'react-router-dom';
import { useEffect, useState, useContext } from 'react';
import { useParams } from 'react-router';
import Swal from 'sweetalert';

import Spinner from 'react-bootstrap/Spinner';
import Select from 'react-select';
import HouseDetailsSpinner from '../spinners/HouseDetailsSpinner';
import HouseTenantDetailsSpinner from '../spinners/HouseTenantDetailsSpinner';
import DashNavBar from './DashNavBar';
import DashSideNavBar from './DashSideNavBar';
import DashFooter from './DashFooter';
import ReLogin from '../home/ReLogin';
import { LoginContext } from '../contexts/LoginContext';


function ManagePropertyVacateHseIDTenant(props) {
    // {currentpropertyhousesel}
    
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
     
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();
    let par=useParams()
    const [house,setHouseID]=useState((par.house)?par.house:'')
    const [tenant,setTenantID]=useState('')

    // console.log(house,tenant)

    const [closed,setClosed]=useState(false)
    const [housedata, setHousedata] = useState([]);

    const [propertydata, setPropertydata] = useState([]);
    const [tenantdata, setTenantdata] = useState([]);
    const [agreementdata, setAgreementdata] = useState([]);
    
    const [paymentsdata, setPaymentsdata] = useState([]);
    
    const [waterbillpropertyid,setWaterbillPropertyId]=useState([""]);
    const [tenantsid,setTenantsId]=useState([""]);
    const [managehouseid,setManageHouseId]=useState([""]);

    // const [loading,setLoading]=useState(true);
    const [loadingmonths,setLoadingMonths]=useState(true);
    
    const [propertyinfo, setPropertyinfo] = useState([""]);
    const [tenantinfo, setTenantinfo] = useState([""]);
    const [houseinfo, setHouseinfo] = useState([""]);

    const [loading,setLoading]=useState(false);
    
    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');

    
    const [formdata,setFormData]=useState({
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
           
            const arr = [];
                arr.push({value: '', label: 'Select House' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Tenant' });
            const arr2 = [];
                arr2.push({value: '', label: 'Select Property' });

            let url=`/v2/properties/mgr/tenants/vacate/${house}/${tenant}`;
            
           
            // console.log(id)
            if(tenant===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                url=`/v2/properties/mgr/tenants/vacate/${house}/${tenant}`;
            }
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let reshouseinfo = response.data.houseinfo;
                        let resthistenant = response.data.thistenant;
                        let restenantinfo = response.data.tenantinfo;

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


                        setHousedata(response.data.thishouse)
                        setPaymentsdata(response.data.payments);
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
        getProperties();

        return ()=>{
            doneloading=false;
        }
    },[])

    const loadTenants =() =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getProperties = async (e) => { 
           
            const arr = [];
                arr.push({value: '', label: 'Select House' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Tenant' });
            const arr2 = [];
                arr2.push({value: '', label: 'Select Property' });

            let url=`/v2/properties/mgr/tenants/vacate/${house}/${tenant}`;
            
           
            // console.log(id)
            if(tenant===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                url=`/v2/properties/mgr/tenants/vacate/${house}/${tenant}`;
            }
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let reshouseinfo = response.data.houseinfo;
                        let resthistenant = response.data.thistenant;
                        let restenantinfo = response.data.tenantinfo;

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


                        setHousedata(response.data.thishouse)
                        setPaymentsdata(response.data.payments);

                        let hid=response.data.hid;
                        let tid=response.data.tid;
                        let thisurl=`/properties/mgr/tenants/${tid}/vacate/${hid}`;
                        navigate(thisurl)

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
        getProperties();

        return ()=>{
            doneloading=false;
        }
    }

    const loadVacating =(thihouse,thitenant) =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getProperties = async (e) => { 
           
            const arr = [];
                arr.push({value: '', label: 'Select House' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Tenant' });
            const arr2 = [];
                arr2.push({value: '', label: 'Select Property' });

            let url='';
           
            // console.log(id)
            
            if(thihouse==='' || thitenant===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                url=`/v2/properties/mgr/tenants/vacate/${thihouse}/${thitenant}`;
            }
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let reshouseinfo = response.data.houseinfo;
                        let resthistenant = response.data.thistenant;
                        let restenantinfo = response.data.tenantinfo;

                        let hid=response.data.hid;
                        let tid=response.data.tid;

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


                        setHousedata(response.data.thishouse)
                        setPaymentsdata(response.data.payments);

                        let thisurl=`/properties/mgr/tenants/${tid}/vacate/${hid}`;
                        navigate(thisurl)
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
        getProperties();

        return ()=>{
            doneloading=false;
        }
    }

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
                axios.post('/v2/vacate/house/save',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        setFormData({...formdata,error_list:[]});
                        if(socket) {
                            socket.emit('tenant_vacated',response.data.message);
                        }
                        Swal("Success",response.data.message);
                        loadTenants();
                        // window.location.reload();
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
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

    function handleTenantChange(val) {
        setLoadingMonths(true)
        let options={value: val.value, label: val.label , data: val}
        setTenantsId(options) 
        
        const tenantid=val.value;
        if(tenantid!==''){
            setHouseID(house)
            setTenantID(tenantid)
            let thisurl=`/properties/mgr/tenants/${tenantid}/vacate`;
            navigate(thisurl)
            loadVacating("None",tenantid);
        }

        // if(tenantid!==''){
        //     let thisurl=`/properties/mgr/tenants/${tenantid}`;
        //     navigate(thisurl)
        // }
        setLoadingMonths(false)
    }

    function handleHouseChange(val) {
        setLoadingMonths(true)
        setHouseID(val.value)
        let options={value: val.value, label: val.label , data: val}
        setManageHouseId(options) 
        const plotid=val.data['Plot'];
        const houseid=val.value;

        if(houseid!==''){
            setHouseID(house)
            let thisurl=`/properties/mgr/tenants/vacate/${houseid}`;
            navigate(thisurl)
            loadVacating(houseid,"None");
        }

        // if(houseid!=='' && plotid!==''){
        //     let thisurl=`/properties/house/${plotid}/${houseid}`;
        //     navigate(thisurl)
        // }
        setLoadingMonths(false)
    }

    function handlePropertyChange(val) {
        setLoadingMonths(true)
        let options={value: val.value, label: val.label , data: val}
        setWaterbillPropertyId(options) 

        const plotid=val.value;
        if(plotid!==''){
            let thisurl=`/properties/mgr/tenants/category/${plotid}`;
            navigate(thisurl)
        }
        setLoadingMonths(false)
    }

    document.title="Vacate Tenant";

  return (
    <>
        <div className="wrapper">
            {loggedoff ? 
                <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
            :
            <>
            <DashNavBar setClosed={setClosed} closed={closed} active='tenant'/>
            <DashSideNavBar setClosed={setClosed} closed={closed} active='tenant'/>
            
            <main className="py-3">
                
                <div className={`content-wrapper ${closed?'closed':''}`}>

                    <section className="content">
                        <div className="p-2">
                            {/* container class */}
                            <div className="row justify-content-center">


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
                                                            {tenantdata.Status==="Vacated"?'Vacated':'Vacating'} Tenant 
                                                            <span className='text-danger'> { !loadingmonths && tenantdata.tenantname }
                                                            {tenantdata.Status!=="Vacated"?<span>({tenantdata.Status})</span>:" "} 
                                                            </span>
                                                            { !loadingmonths && tenantdata.Housenames &&
                                                                <>
                                                                From <small>
                                                                    <i className='text-red'>
                                                                    { !loadingmonths?tenantdata.Housenames?tenantdata.Housenames:tenantdata.Status:""} 
                                                                    </i>
                                                                </small>
                                                                </>
                                                            }
                                                            
                                                        </h4>
                                                </div>
                                                </div>

                                            </div>

                                            <div className="row m-0 p-0 justify-content-center text-center border-none">
                                                {agreementdata[0] &&
                                                <div className="row m-0 p-0" style={{"overflowX":"auto"}}>
                                                    {loadingmonths &&
                                                        <div className="col-12 col-md-6 col-lg-4 text-left m-0 p-1 mt-1 mb-2">
                                                            <HouseDetailsSpinner />
                                                        </div>
                                                    }
                                                    {!loadingmonths && paymentsdata[0] &&
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
                                                                                {paymentsdata[0] && new Number(paymentsdata[0].Refund).toFixed(2)}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-6 m-0 p-0">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">Assigned On:</label>

                                                                            <div className="col-7 bold text-md-left p-1">
                                                                            {paymentsdata[0] &&  paymentsdata[0].dateToMonthName}
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
                                                    {!loadingmonths && paymentsdata[0] &&
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
                                                }
                                                
                                            </div>

                                            

                                        </div>
                                    </div>

                                    
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

export default ManagePropertyVacateHseIDTenant;