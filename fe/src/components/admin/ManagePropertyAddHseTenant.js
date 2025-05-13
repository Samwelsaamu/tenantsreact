import Modal from 'react-bootstrap/Modal';

import axios, { formToJSON } from 'axios';
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


function ManagePropertyAddHseTenant(props) {
    // {currentpropertyhousesel}
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
          
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();
    let par=useParams()
    const [house,setHouseID]=useState((par.house)?par.house:'')
    const [tenant,setTenantID]=useState((par.id)?par.id:'')

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
        Refund:-1,
        Transaction:0.00,
        InitialCharges:0.00,
        MonthlyCharges:0.00,
        HouseRent:false,
        Garbage:false,
        HouseDeposit:false,
        WaterDeposit:false,
        KPLCDeposit:false,
        Lease:false,
        HouseRentRefund:false,
        GarbageRefund:false,
        HouseDepositRefund:false,
        WaterDepositRefund:false,
        KPLCDepositRefund:false,
        LeaseRefund:false,
        CustomRefund:false,
        MonthlyCharge:false,
        DateAssigned:'',
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

            let url='';
            
           
            // console.log(id)
            if(tenant===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                // setHouseID(house===''?"None":house);
                url=`/v2/properties/mgr/tenants/addhouse/${house===''?"None":house}/${tenant}`;
            }
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let reshouseinfo = response.data.houseinfo;
                        let resthistenant = response.data.thistenant;
                        let resthishouse = response.data.thishouse;
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

                        let options=[];
                        if(tenant!==''){
                            options={value: resthistenant.id, label: resthistenant.Fname+' '+resthistenant.Oname+'('+resthistenant.Status+')' , data: resthistenant}
                        }
                        setTenantsId(options)

                        let optionshse=[];
                        if(tenant!==''){
                            optionshse={value: resthishouse.id, label: resthishouse.Housename+'('+resthishouse.Status+')' , data: resthishouse}
                        }
                        setManageHouseId(optionshse)

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
    },[tenant,house,loggedoff])

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

            let url='';
            
           
            // console.log(id)
            if(tenant===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                // setHouseID(house===''?"None":house);
                url=`/v2/properties/mgr/tenants/addhouse/${house===''?"None":house}/${tenant}`;
            }
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let reshouseinfo = response.data.houseinfo;
                        let resthistenant = response.data.thistenant;
                        let resthishouse = response.data.thishouse;
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

                        let options=[];
                        if(tenant!==''){
                            options={value: resthistenant.id, label: resthistenant.Fname+' '+resthistenant.Oname+'('+resthistenant.Status+')' , data: resthistenant}
                        }
                        setTenantsId(options)

                        let optionshse=[];
                        if(tenant!==''){
                            optionshse={value: resthishouse.id, label: resthishouse.Housename+'('+resthishouse.Status+')' , data: resthishouse}
                        }
                        setManageHouseId(optionshse)

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
    }


    const loadAssigning =(thihouse,thistenant) =>{
        
        setFormData({
            HouseRent:false,
            Garbage:false,
            HouseDeposit:false,
            WaterDeposit:false,
            KPLCDeposit:false,
            Lease:false,
            HouseRentRefund:false,
            GarbageRefund:false,
            HouseDepositRefund:false,
            WaterDepositRefund:false,
            KPLCDepositRefund:false,
            LeaseRefund:false,
            CustomRefund:false,
            MonthlyCharge:false,
            Refund:-1,
            InitialCharges:0.00,
            MonthlyCharges:0.00,
            DateAssigned:'',
        })


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
            if(thistenant==='' || thihouse===''){
                return false;
            }
            else{
                url=`/v2/properties/mgr/tenants/addhouse/${thihouse===''?"None":thihouse}/${thistenant}`;
            }
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let reshouseinfo = response.data.houseinfo;
                        let resthistenant = response.data.thistenant;
                        let resthishouse = response.data.thishouse;
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

                        let options=[];
                        if(tenant!==''){
                            options={value: resthistenant.id, label: resthistenant.Fname+' '+resthistenant.Oname+'('+resthistenant.Status+')' , data: resthistenant}
                        }
                        setTenantsId(options)

                        let optionshse=[];
                        if(tenant!==''){
                            optionshse={value: resthishouse.id, label: resthishouse.Housename+'('+resthishouse.Status+')' , data: resthishouse}
                        }
                        setManageHouseId(optionshse)

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
        if(e.target.name==='DateAssigned'){
            setFormData({...formdata,[e.target.name]:e.target.value})
        }
        else if(e.target.name==='Refund' && formdata.CustomRefund===true){
            if(e.target.value===''){
                setFormData({...formdata,[e.target.name]:-1})
            }
            else{
                setFormData({...formdata,[e.target.name]:e.target.value})
            }
        }
        else if(e.target.name==='GarbageRefund' && formdata.Garbage===false){
            Swal("Missing Something","Please Select Bin/Garbage for Initial Charges First","error");            
        }
        else if(e.target.name==='HouseDepositRefund' && formdata.HouseDeposit===false){
            Swal("Missing Something","Please Select House Deposit for Initial Charges First","error");            
        }
        else if(e.target.name==='WaterDepositRefund' && formdata.WaterDeposit===false){
            Swal("Missing Something","Please Select Water Deposit for Initial Charges First","error");            
        }
        else if(e.target.name==='KPLCDepositRefund' && formdata.KPLCDeposit===false){
            Swal("Missing Something","Please Select KPLC Deposit for Initial Charges First","error");            
        }
        else if(e.target.name==='LeaseRefund' && formdata.Lease===false){
            Swal("Missing Something","Please Select Lease for Initial Charges First","error");            
        }
        else if(e.target.name==='Garbage' && formdata.GarbageRefund===true){
            Swal("Missing Something","Please Remove/Unselect Bin/Garbage from Amount to be Refunded","error");            
        }
        else if(e.target.name==='HouseDeposit' && formdata.HouseDepositRefund===true){
            Swal("Missing Something","Please Remove House Deposit from Amount to be Refunded","error");            
        }
        else if(e.target.name==='WaterDeposit' && formdata.WaterDepositRefund===true){
            Swal("Missing Something","Please Remove Water Deposit from Amount to be Refunded","error");            
        }
        else if(e.target.name==='KPLCDeposit' && formdata.KPLCDepositRefund===true){
            Swal("Missing Something","Please Remove KPLC Deposit from Amount to be Refunded","error");            
        }
        else if(e.target.name==='Lease' && formdata.LeaseRefund===true){
            Swal("Missing Something","Please Remove Lease from Amount to be Refunded","error");            
        }
        else{
            setFormData({...formdata,[e.target.name]:!formdata[e.target.name]})
        }
        

    }

    
    
    const assignTenant= (house)=>{

        if(formdata.DateAssigned===undefined || formdata.DateAssigned ===""){
            Swal("Date Assigned Needed","Please Choose Date Tenant Was Assigned","error");
            return;
        }

        let refund=formdata.CustomRefund?new Number(formdata.Refund).toFixed(2):(new Number(new Number(formdata.HouseRentRefund?housedata.Rent:0.00) + new Number(formdata.GarbageRefund?housedata.Garbage:0.00) + new Number(formdata.HouseDepositRefund?housedata.Deposit:0.00) + new Number(formdata.KPLCDepositRefund?housedata.Kplc:0.00) + new Number(formdata.WaterDepositRefund?housedata.Water:0.00) + new Number(formdata.LeaseRefund?housedata.Lease:0.00) )).toFixed(2)
        let initialpay=(new Number(new Number(formdata.HouseRent?housedata.Rent:0.00) + new Number(formdata.Garbage?housedata.Garbage:0.00) + new Number(formdata.HouseDeposit?housedata.Deposit:0.00) + new Number(formdata.KPLCDeposit?housedata.Kplc:0.00) + new Number(formdata.WaterDeposit?housedata.Water:0.00) + new Number(formdata.Lease?housedata.Lease:0.00) )).toFixed(2)
        let monthlypay=formdata.MonthlyCharge?(new Number(new Number(formdata.HouseRent?housedata.Rent:0.00) + new Number(formdata.Garbage?housedata.Garbage:0.00) )).toFixed(2):0.00
        
        if(formdata.CustomRefund===true  && formdata.Refund <0){
            Swal("Enter Refund Value","Please Enter Custom Refund to be Refunded on Vacating Property","error");
            return;
        }

        if(formdata.CustomRefund===false && (housedata.Deposit >=0 || housedata.Kplc >=0 || housedata.Water >=0) && refund <=0){
            Swal("Select What to Refund","Please Select Values to Refund to be Refunded on Vacating Property","error");
            return;
        }

        
        const form={
            hid:house.id,
            tid:house.tenant,
            Refund:refund,
            InitialCharges:(initialpay),
            MonthlyCharges:(monthlypay),
            DateAssigned:formdata.DateAssigned,
            HouseRent:formdata.HouseRent,
            Garbage:formdata.Garbage,
            HouseDeposit:formdata.HouseDeposit,
            WaterDeposit:formdata.WaterDeposit,
            KPLCDeposit:formdata.KPLCDeposit,
            Lease:formdata.Lease,
        }
        console.log(form)

        // let
        let title='Assign  '+ housedata.tenantname + ' To '+house.Housename;
        let text="This will Assign tenant to this House.\n"+
        "\n Initial Charges to Pay :: "+ new Number(initialpay).toFixed(2)+
        "\n Monthly Charges to Pay :: "+ new Number(monthlypay).toFixed(2)+
        "\n Deposit/Refundable :: "+ new Number(refund).toFixed(2);
        Swal({
            title:title+' ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Assigning....","Please Wait");
                axios.post('/v2/addhouse/house/save',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        setFormData({...formdata,error_list:[]});
                        if(socket) {
                            socket.emit('tenant_assigned',response.data.message);
                        }
                        Swal("Success",response.data.message);
                        loadTenants();
                        // window.location.reload();
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);
                        // console.log("sad4:"+loggedoff)
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
            let thisurl=`/properties/mgr/tenants/${tenantid}/addhouse/${house}`;
            navigate(thisurl)
            loadAssigning(house,tenantid);
        }
        setLoadingMonths(false)
    }

    function handleHouseChange(val) {
        setLoadingMonths(true)

        let options={value: val.value, label: val.label , data: val}
        setManageHouseId(options) 

        const houseid=val.value;
        if(houseid!=='' && tenant!==''){
            setHouseID(houseid)
            setTenantID(tenant)
            let thisurl=`/properties/mgr/tenants/${tenant}/addhouse/${houseid}`;
            navigate(thisurl)
            loadAssigning(houseid,tenant);
        }

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

    document.title="Add House to Tenant";
  return (
    <>
        <div className="wrapper">
            {loggedoff ? 
                <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
            :
            <>
            <DashNavBar setClosed={setClosed} closed={closed} active='tenant'/>
            <DashSideNavBar setClosed={setClosed} closed={closed} active='tenant'/>
            
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
                                    <li className="breadcrumb-item"><Link to={'/properties/mgr/tenants/'+tenant}>{ !loadingmonths && tenantdata.tenantname } </Link></li>
                                    
                                    <li className="breadcrumb-item ">In House : 
                                    {tenantdata.housesdata  && tenantdata.housesdata.map((houses,key) => (
                                        <Link to={'/properties/house/'+houses.pid+'/'+houses.hid} className='ml-2' target='_blank' > {houses.Housename} </Link>
                                     ))
                                    }
                                    </li>
                                    <li className="breadcrumb-item active">Adding Another House: { !loadingmonths && housedata.Housename && housedata.Housename} </li>
                                </ol>
                            </div>


                            <div className="col-12">
                                <div className="row m-0 p-0">
                                    

                                    <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                        <div className="card border-none m-0 p-0" >
                                            <div className="card-header text-white elevation-2 m-0 p-0">

                                                <div className='row justify-content-center text-center p-1 m-0'>
                                                    {loadingmonths &&
                                                        <Spinner  variant="blue" size="md" role="status"></Spinner>
                                                    }
                                                    {!loadingmonths &&
                                                        <>
                                                            <div className="col-12 col-md-6 col-lg-4 m-0 p-1 text-sm text-dark">
                                                                
                                                                <Select
                                                                    className='text-sm'
                                                                    placeholder= "Select Property"
                                                                    value={waterbillpropertyid}
                                                                    name="manage-property"
                                                                    options={propertyinfo}
                                                                    onChange={handlePropertyChange}
                                                                />
                                                            </div>

                                                            <div className="col-12 col-md-6 col-lg-4 m-0 p-1 text-sm text-dark">
                                                                
                                                                <Select
                                                                    className='text-sm'
                                                                    placeholder= "Select House"
                                                                    value={managehouseid}
                                                                    name="house-property"
                                                                    options={houseinfo}
                                                                    onChange={handleHouseChange}
                                                                />
                                                            </div>

                                                            <div className="col-12 col-md-6 col-lg-4 m-0 p-1 text-sm text-dark">
                                                                
                                                                <Select
                                                                    placeholder= "Select Tenant"
                                                                    value={tenantsid}
                                                                    name="waterbill-property"
                                                                    options={tenantinfo}
                                                                    onChange={handleTenantChange}
                                                                />
                                                            </div>
                                                        </>
                                                    }
                                                    
                                                    <div className="col-12 m-0 p-1 mt-1">
                                                        { !loadingmonths &&
                                                            <>
                                                                {/* <h4 className='text-info mx-auto text-center'>
                                                                    Adding House <i className='text-purple'>
                                                                    { !loadingmonths?housedata.Housename?housedata.Housename:"No House Selected":""} 
                                                                    </i>  to Tenant  <span className='text-success'> { !loadingmonths && tenantdata.tenantname }
                                                                    {tenantdata.Status!=="Vacated" || tenantdata.Status!=="New"?<span>({tenantdata.Status}) </span>:" "} 
                                                                    </span>
                                                                </h4> */}
                                                                <p className='text-info mx-auto text-center'>
                                                                To Add:
                                                                <i className='text-purple'>
                                                                { !loadingmonths?housedata.Housename?" "+ housedata.Housename:" No House Selected":""}  
                                                                </i>, Current Houses:  <span className='text-success'> { !loadingmonths && tenantdata.housesdatas }</span>
                                                                </p>
                                                            </>
                                                        }
                                                </div>
                                                </div>

                                            </div>

                                            <div className="row m-0 p-0 justify-content-center text-center border-none">
                                                {housedata && housedata.Status!=="Occupied" &&
                                                <div className="row m-0 p-0" style={{"overflowX":"auto"}}>
                                                    {loadingmonths &&
                                                        <div className="col-12 col-md-6 col-lg-4 text-left m-0 p-1 mt-1 mb-2">
                                                            <HouseDetailsSpinner />
                                                        </div>
                                                    }
                                                    {!loadingmonths && 
                                                    <div className="col-12 col-lg-6 text-left m-0 p-1 mt-1 mb-2">
                                                        <div className="card border-ok m-2 p-1" >
                                                            <div className="card-header bg-success border-info text-muted text-center m-0 mb-2 p-2 pt-1 pb-2">
                                                                
                                                                <span style={{"float":"left"}}>
                                                                    <span className={`m-0 p-1 text-sm bg-light ${tenantdata.Status==='Vacated'?'text-danger':'text-info'}`}
                                                                        style={{"borderRadius":"30px","border":"2px solid white"}}> {tenantdata.tenantname}</span>
                                                                        
                                                                </span> 
                                                                <span className='m-0 p-1 text-white mx-auto elevation-2 border-info bg-primary'>{housedata.Housename}</span>
                                                                <span style={{"float":"right"}}>
                                                                    <span className={`m-0 p-1 text-sm bg-light ${housedata.Status==='Occupied'?'text-success':'text-success'}`}
                                                                        style={{"borderRadius":"30px","border":"2px solid white"}}> {tenantdata.PhoneMasked}</span>  
                                                                </span>
                                                                
                                                            </div>
                                                            
                                                            <div className="card-body text-center text-muted text-sm m-0 p-0 pt-1">
                                                                <div className='row m-0 p-1 elevation-0 border-none'>
                                                                    <div className="col-6 m-0 p-1">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">IDNo:</label>

                                                                            <div className="col-7 bold text-md-left p-1">
                                                                                {tenantdata.IDno}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-6 m-0 p-1">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">Status:</label>

                                                                            <div className="col-7 bold text-md-left p-1">
                                                                                {tenantdata.Status}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div className='col-12 m-0 p-1 elevation-0 border-none'>

                                                                    {housedata.Rent >0 &&
                                                                        <label className='m-0 p-1 text-xs p-1 border-info m-1 assignlbl' title='House Rent'>Rent: 
                                                                            <span className='bold'> {new Number(housedata.Rent).toFixed(2)}</span> {formdata.HouseRent? <i className='fa fa-check text-lime'></i>:''}
                                                                        </label>
                                                                        // <div className="col-6 m-0 p-1">
                                                                        //     <div className="form-group row m-0 p-0">
                                                                        //         <label className="col-5 m-0 p-0 text-md-right" title='House Rent'>Rent:</label>

                                                                        //         <div className="col-7 bold text-md-left p-1" title='House Rent'>
                                                                        //             {new Number(housedata.Rent).toFixed(2)} {formdata.HouseRent? <i className='fa fa-check text-lime'></i>:''}
                                                                        //         </div>
                                                                        //     </div>
                                                                        // </div>
                                                                    }
                                                                    {housedata.Garbage >0 &&
                                                                    <label className='m-0 p-1 text-xs p-1 border-info m-1 assignlbl' title='Garbage'>Garbage: 
                                                                        <span className='bold'> {new Number(housedata.Garbage).toFixed(2)}</span> {formdata.Garbage? <i className='fa fa-check text-lime'></i>:''}
                                                                    </label>
                                                                        // <div className="col-6 m-0 p-1">
                                                                        //     <div className="form-group row m-0 p-0">
                                                                        //         <label className="col-5 m-0 p-0 text-md-right" title='Garbage'>Garbage:</label>

                                                                        //         <div className="col-7 bold text-md-left p-1" title='Garbage'>
                                                                        //             {new Number(housedata.Garbage).toFixed(2)} {formdata.Garbage? <i className='fa fa-check text-lime'></i>:''}
                                                                        //         </div>
                                                                        //     </div>
                                                                        // </div>
                                                                    }

                                                                    {housedata.Deposit >0 &&
                                                                    <label className='m-0 p-1 text-xs p-1 border-info m-1 assignlbl' title='House Deposit'>Deposit: 
                                                                        <span className='bold'> {new Number(housedata.Deposit).toFixed(2)}</span> {formdata.HouseDeposit? <i className='fa fa-check text-lime'></i>:''}
                                                                    </label>
                                                                        // <div className="col-6 m-0 p-1">
                                                                        //     <div className="form-group row m-0 p-0">
                                                                        //         <label className="col-5 m-0 p-0 text-md-right" title='House Deposit'>Deposit:</label>

                                                                        //         <div className="col-7 bold text-md-left p-1" title='House Deposit'>
                                                                        //             {new Number(housedata.Deposit).toFixed(2)} {formdata.HouseDeposit? <i className='fa fa-check text-lime'></i>:''}
                                                                        //         </div>
                                                                        //     </div>
                                                                        // </div>
                                                                    }
                                                                    {housedata.Kplc >0 &&
                                                                    <label className='m-0 p-1 text-xs p-1 border-info m-1 assignlbl' title='KPLC Deposit'>KPLC D: 
                                                                        <span className='bold'> {new Number(housedata.Kplc).toFixed(2)}</span> {formdata.KPLCDeposit? <i className='fa fa-check text-lime'></i>:''}
                                                                    </label>
                                                                        // <div className="col-6 m-0 p-1">
                                                                        //     <div className="form-group row m-0 p-0">
                                                                        //         <label className="col-5 m-0 p-0 text-md-right" title='KPLC Deposit'>KPLC D:</label>

                                                                        //         <div className="col-7 bold text-md-left p-1" title='KPLC Deposit'>
                                                                        //             {new Number(housedata.Kplc).toFixed(2)} {formdata.KPLCDeposit? <i className='fa fa-check text-lime'></i>:''}
                                                                        //         </div>
                                                                        //     </div>
                                                                        // </div>
                                                                    }

                                                                    {housedata.Water >0 &&
                                                                    <label className='m-0 p-1 text-xs p-1 border-info m-1 assignlbl' title='Water Deposit'>Water D: 
                                                                        <span className='bold'> {new Number(housedata.Water).toFixed(2)}</span> {formdata.WaterDeposit? <i className='fa fa-check text-lime'></i>:''}
                                                                    </label>
                                                                        // <div className="col-6 m-0 p-1">
                                                                        //     <div className="form-group row m-0 p-0">
                                                                        //         <label className="col-5 m-0 p-0 text-md-right" title='Water Deposit'>Water D:</label>

                                                                        //         <div className="col-7 bold text-md-left p-1" title='Water Deposit'>
                                                                        //             {new Number(housedata.Water).toFixed(2)} {formdata.WaterDeposit? <i className='fa fa-check text-lime'></i>:''}
                                                                        //         </div>
                                                                        //     </div>
                                                                        // </div>
                                                                    }
                                                                    {housedata.Lease >0 &&
                                                                    <label className='m-0 p-1 text-xs p-1 border-info m-1 assignlbl' title='Lease'>Lease: 
                                                                        <span className='bold'> {new Number(housedata.Lease).toFixed(2)}</span> {formdata.Lease? <i className='fa fa-check text-lime'></i>:''}
                                                                    </label>
                                                                        // <div className="col-6 m-0 p-1">
                                                                        //     <div className="form-group row m-0 p-0">
                                                                        //         <label className="col-5 m-0 p-0 text-md-right" title='Lease'>Lease:</label>

                                                                        //         <div className="col-7 bold text-md-left p-1" title='Lease'>
                                                                        //             {new Number(housedata.Lease).toFixed(2)} {formdata.Lease? <i className='fa fa-check text-lime'></i>:''}
                                                                        //         </div>
                                                                        //     </div>
                                                                        // </div>
                                                                    }


                                                                </div>

                                                                <h4 className='text-sm p-2 text-lime'>Please Select All That Applies to Tenant {tenantdata.tenantname} in House {housedata.Housename}</h4>
                                                         
                                                                <div className="col-12 m-0 p-0">
                                                                    {housedata.Rent >0 &&
                                                                        <label className={`m-0 p-1 text-xs p-1 ${formdata.HouseRent?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`} title='House Rent Charges'>
                                                                                <input type="checkbox" required checked={formdata.HouseRent?'True':''} onChange={handleInputChange} className="" name="HouseRent" value="Rent"/> Rent
                                                                        </label>
                                                                    }
                                                                    {housedata.Garbage >0 &&
                                                                        <label className={`m-0 p-1 text-xs p-1 ${formdata.Garbage?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`}  title='Garbage Charges' >
                                                                            <input type="checkbox" required checked={formdata.Garbage?'True':''} onChange={handleInputChange} className="" name="Garbage" value="Garbage"/> Bin
                                                                        </label>
                                                                    }
                                                                    {housedata.Deposit >0 &&
                                                                        <label className={`m-0 p-1 text-xs p-1 ${formdata.HouseDeposit?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`} title='House Deposit' >
                                                                                <input type="checkbox" required checked={formdata.HouseDeposit?'True':''} onChange={handleInputChange} className="" name="HouseDeposit" value="House Deposit"/> Deposit
                                                                        </label>
                                                                    }
                                                                    {housedata.Kplc >0 &&
                                                                        <label className={`m-0 p-1 text-xs p-1 ${formdata.KPLCDeposit?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`} title='KPLC Deposit'  >
                                                                                <input type="checkbox" required checked={formdata.KPLCDeposit?'True':''} onChange={handleInputChange} className="" name="KPLCDeposit" value="KPLC Deposit"/> KPLC
                                                                        </label>
                                                                    }
                                                                    {housedata.Water >0 &&
                                                                        <label className={`m-0 p-1 text-xs p-1 ${formdata.WaterDeposit?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`} title='Water Deposit'  >
                                                                            <input type="checkbox" required checked={formdata.WaterDeposit?'True':''} onChange={handleInputChange} className="" name="WaterDeposit" value="Water Deposit"/> Water
                                                                        </label>
                                                                    }
                                                                    {housedata.Lease >0 &&
                                                                        <label className={`m-0 p-1 text-xs p-1 ${formdata.Lease?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`}  >
                                                                            <input type="checkbox" required checked={formdata.Lease?'True':''} onChange={handleInputChange} className="" name="Lease" value="Lease"/> Lease
                                                                        </label>
                                                                    }
                                                                    {housedata.Rent+housedata.Garbage >0 &&
                                                                        <label className={`m-0 p-1 text-xs p-1 ${formdata.MonthlyCharge?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`}  >
                                                                            <input type="checkbox" required checked={formdata.MonthlyCharge?'True':''} onChange={handleInputChange} className="" name="MonthlyCharge" value="MonthlyCharge"/> MonthlyCharge
                                                                        </label>
                                                                    }
                                                                </div> 

                                                                <h4 className='text-sm p-2 text-danger'>Please Select All that will be Refunded to {tenantdata.tenantname} in House {housedata.Housename}</h4>
                                                                <div className="col-12 m-0 p-0">
                                                                    {!formdata.CustomRefund &&
                                                                        <>
                                                                        {housedata.Garbage >0 &&
                                                                            <label className={`m-0 p-1 text-xs p-1 ${formdata.GarbageRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`}  title='GarbageRefund Charges' >
                                                                                <input type="checkbox" required checked={formdata.GarbageRefund?'True':''} onChange={handleInputChange} className="" name="GarbageRefund" value="GarbageRefund"/> Bin(Refund)
                                                                            </label>
                                                                        }
                                                                        {housedata.Deposit >0 &&
                                                                            <label className={`m-0 p-1 text-xs p-1 ${formdata.HouseDepositRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`} title='House Deposit' >
                                                                                    <input type="checkbox" required checked={formdata.HouseDepositRefund?'True':''} onChange={handleInputChange} className="" name="HouseDepositRefund" value="House Deposit"/> Deposit(Refund)
                                                                            </label>
                                                                        }
                                                                        {housedata.Kplc >0 &&
                                                                            <label className={`m-0 p-1 text-xs p-1 ${formdata.KPLCDepositRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`} title='KPLC Deposit'  >
                                                                                    <input type="checkbox" required checked={formdata.KPLCDepositRefund?'True':''} onChange={handleInputChange} className="" name="KPLCDepositRefund" value="KPLC Deposit"/> KPLC(Refund)
                                                                            </label>
                                                                        }
                                                                        {housedata.Water >0 &&
                                                                            <label className={`m-0 p-1 text-xs p-1 ${formdata.WaterDepositRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`} title='Water Deposit'  >
                                                                                <input type="checkbox" required checked={formdata.WaterDepositRefund?'True':''} onChange={handleInputChange} className="" name="WaterDepositRefund" value="Water Deposit"/> Water(Refund)
                                                                            </label>
                                                                        }
                                                                        {housedata.Lease >0 &&
                                                                            <label className={`m-0 p-1 text-xs p-1 ${formdata.LeaseRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`}  >
                                                                                <input type="checkbox" required checked={formdata.LeaseRefund?'True':''} onChange={handleInputChange} className="" name="LeaseRefund" value="LeaseRefund"/> Lease(Refund)
                                                                            </label>
                                                                        }
                                                                        </>
                                                                    }
                                                                    <label className={`m-0 p-1 text-xs p-1 ${formdata.CustomRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 assignlbl`}  >
                                                                        <input type="checkbox" required checked={formdata.CustomRefund?'True':''} onChange={handleInputChange} className="" name="CustomRefund" value="Custom Refund"/> Custom Refund
                                                                    </label>
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
                                                                        
                                                                        <span className='m-0 p-1 text-sm text-dark mx-auto'>Ensure you Click on Charges that applies to this Tenant and click on Assign</span>
                                                                        
                                                                    </div>

                                                                    <div className="card-body text-center text-muted text-sm m-0 p-0 pt-1">
                                                                        
                                                                        
                                                                    </div>
                                                                    

                                                                    <div className="form-group row m-0 p-1 pb-2">
                                                                        <label htmlFor="InitialCharges" className="col-sm-4 col-12 col-form-label text-md-right">Initial Charges</label>

                                                                        <div className="col-sm-8 col-12 m-0 p-0">
                                                                        
                                                                            <input id="InitialCharges" type="text" className="form-control" name="InitialCharges" value={(new Number(new Number(formdata.HouseRent?housedata.Rent:0.00) + new Number(formdata.Garbage?housedata.Garbage:0.00) + new Number(formdata.HouseDeposit?housedata.Deposit:0.00) + new Number(formdata.KPLCDeposit?housedata.Kplc:0.00) + new Number(formdata.WaterDeposit?housedata.Water:0.00) + new Number(formdata.Lease?housedata.Lease:0.00) )).toFixed(2)} onChange={handleInputChange} placeholder="0.00" required readonly="readonly" autoFocus/>
                                                                            {formdata.error_list && formdata.error_list.InitialCharges && 
                                                                                <span className="help-block text-danger">
                                                                                    <strong>{formdata.error_list.InitialCharges}</strong>
                                                                                </span>
                                                                            }
                                                                        </div>
                                                                    </div>


                                                                    <div className="form-group row m-0 p-1 pb-2">
                                                                        <label htmlFor="MonthlyCharges" className="col-sm-4 col-12 col-form-label text-md-right">Monthly Charges: </label>

                                                                        <div className="col-sm-8 col-12 m-0 p-0">
                                                                        
                                                                            <input id="MonthlyCharges" type="text" className="form-control" name="MonthlyCharges" value={formdata.MonthlyCharge?(new Number(new Number(housedata.Rent) + new Number(housedata.Garbage) )).toFixed(2):'0.00'} onChange={handleInputChange} placeholder="0.00" required readonly="readonly" autoFocus/>
                                                                            {formdata.error_list && formdata.error_list.MonthlyCharges && 
                                                                                <span className="help-block text-danger">
                                                                                    <strong>{formdata.error_list.MonthlyCharges}</strong>
                                                                                </span>
                                                                            }
                                                                        </div>
                                                                    </div>

                                                                    <div className="form-group row m-0 p-1 pb-2">
                                                                        <label htmlFor="Refund" className="col-sm-4 col-12 col-form-label text-md-right">Deposit/Refund</label>

                                                                        <div className="col-sm-8 col-12 m-0 p-0">
                                                                            {formdata.CustomRefund?
                                                                                <input id="Refund" type="text" className="form-control" name="Refund" value={formdata.CustomRefund?formdata.Refund:(new Number(new Number(formdata.HouseRentRefund?housedata.Rent:0.00) + new Number(formdata.GarbageRefund?housedata.Garbage:0.00) + new Number(formdata.HouseDepositRefund?housedata.Deposit:0.00) + new Number(formdata.KPLCDepositRefund?housedata.Kplc:0.00) + new Number(formdata.WaterDepositRefund?housedata.Water:0.00) + new Number(formdata.LeaseRefund?housedata.Lease:0.00) )).toFixed(2)} onChange={handleInputChange} placeholder="0.00" required  autoFocus/>
                                                                                :
                                                                                <input id="Refund" type="text" className="form-control" name="Refund" value={formdata.CustomRefund?formdata.Refund:(new Number(new Number(formdata.HouseRentRefund?housedata.Rent:0.00) + new Number(formdata.GarbageRefund?housedata.Garbage:0.00) + new Number(formdata.HouseDepositRefund?housedata.Deposit:0.00) + new Number(formdata.KPLCDepositRefund?housedata.Kplc:0.00) + new Number(formdata.WaterDepositRefund?housedata.Water:0.00) + new Number(formdata.LeaseRefund?housedata.Lease:0.00) )).toFixed(2)} onChange={handleInputChange} placeholder="0.00" required readOnly autoFocus/>
                                                                            }
                                                                            {formdata.error_list && formdata.error_list.Refund && 
                                                                                <span className="help-block text-danger">
                                                                                    <strong>{formdata.error_list.Refund}</strong>
                                                                                </span>
                                                                            }
                                                                        </div>
                                                                    </div>


                                                                    <div className="form-group row m-0 p-1 pb-2">
                                                                        <label htmlFor="DateAssigned" className="col-sm-4 col-12 col-form-label text-md-right">Assigned Date</label>

                                                                        <div className="col-sm-8 col-12 m-0 p-0">
                                                                            <input id="DateAssigned" type="date" className="form-control" name="DateAssigned" value={formdata.DateAssigned} onChange={handleInputChange} placeholder="1" required autoComplete="DateAssigned" autoFocus/>
                                                                            {formdata.error_list && formdata.error_list.DateAssigned && 
                                                                                <span className="help-block text-danger">
                                                                                    <strong>{formdata.error_list.DateAssigned}</strong>
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
                                                                                        Assigning ...</span>
                                                                                        
                                                                                </div>
                                                                            }
                                                                            
                                                                            {!loading && loadingresok ==='' && 
                                                                                <div className="col-md-12 justify-content-center text-center">
                                                                                    <button type="submit" className="btn btn-success" onClick={()=>{assignTenant(housedata)}}>
                                                                                        Assign Tenant to { !loadingmonths?housedata.Housename?housedata.Housename:"No House Selected":""} 
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
                                                {housedata && housedata.Status==="Occupied" &&
                                                    <div className="row m-0 p-0" style={{"overflowX":"auto"}}>
                                                        <div className="col-12 text-left m-0 p-1 mt-1 mb-2">
                                                            <div className="card m-2 p-1" >                                  
                                                                <div className="card-header bg-info border-info text-muted text-center m-0 mb-2 p-2 pt-1 pb-2">
                                                                    <h4 className='text-success mx-auto text-center'>
                                                                        <span className='text-white'> 
                                                                            Tenant { !loadingmonths && tenantdata.tenantname }  has been assigned to  { !loadingmonths?housedata.Housename?housedata.Housename:"No House Selected":""} 
                                                                        </span>
                                                                    </h4>
                                                                </div>
                                                                <div className="card-body text-center text-muted text-sm m-0 p-0 pt-1">
                                                                    <div className='row m-0 p-1 elevation-0 border-none'>
                                                                        <h4>You Can do the following meanwhile!!</h4>
                                                                            <p>
                                                                                <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+housedata.tenant} className='text-info'><i className='fa fa-info-circle'></i> View Tenant</Link></button>
                                                                                {/* <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+housedata.tenant+'/addhouse/'} className='text-info'><i className='fa fa-info-circle'></i> Add House</Link></button> */}
                                                                                <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-danger'><Link to={'/properties/mgr/tenants/'+housedata.tenant+'/vacate/'+housedata.id} className='text-danger'><i className='fa fa-minus-circle'></i> Vacate </Link></button>
                                                                                <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+housedata.tenant+'/reassign/'+housedata.id} className='text-success'><i className='fa fa-exchange-alt'></i> Change House</Link></button>
                                                                                <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+housedata.tenant+'/transfer/'+housedata.id} className='text-primary'><i className='fa fa-play'></i> Transfer IN</Link></button>
                                                                            </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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

export default ManagePropertyAddHseTenant;