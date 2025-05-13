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

function ManagePropertyReassignHseTenant(props) {
    // {currentpropertyhousesel}
    
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
         
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();
    let par=useParams()
    const [house,setHouseID]=useState((par.house)?par.house:'None')
    const [tenant,setTenantID]=useState((par.id)?par.id:'')
    const [selhouse,setSelHouseID]=useState((par.selhouse)?par.selhouse:'None')

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
        Refund:'',
        InitialPay:'',
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
        CustomInitialCharges:false,
        CarriedForward:'',
        MonthlyCharge:false,
        Charge:500.00,
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

            let url=`/v2/properties/mgr/tenants/reassign/${house}/${tenant}/${selhouse}`;
            
           
            // console.log(id)
            if(tenant==='' || house===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                url=`/v2/properties/mgr/tenants/reassign/${house}/${tenant}/${selhouse}`;
            }
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let reshouseinfo = response.data.houseinfo;
                        let resthishouse = response.data.thishouse;
                        let resthistenant = response.data.thistenant;
                        let restenantinfo = response.data.tenantinfo;

                        let hid=response.data.hid;
                        let tid=response.data.tid;
                        let shid=response.data.shid;

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


                        let optionshse=[];
                        if(shid!==''){
                            optionshse={value: resthishouse.id, label: resthishouse.Housename+'('+resthishouse.Status+')' , data: resthishouse}
                        }
                        setManageHouseId(optionshse)

                        let optionstent=[];
                        if(tid!==''){
                            optionstent={value: resthistenant.id, label: resthistenant.Fname+' '+resthistenant.Oname+'('+resthistenant.Status+')' , data: resthistenant}
                        }
                        setTenantsId(optionstent)


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

            let url=`/v2/properties/mgr/tenants/reassign/${house}/${tenant}/${selhouse}`;
            
           
            // console.log(id)
            if(tenant==='' || house===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                url=`/v2/properties/mgr/tenants/reassign/${house}/${tenant}/${selhouse}`;
            }
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let reshouseinfo = response.data.houseinfo;
                        let resthishouse = response.data.thishouse;
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
                        let shid=response.data.shid;
                        
                        let optionshse=[];
                        if(shid!==''){
                            optionshse={value: resthishouse.id, label: resthishouse.Housename+'('+resthishouse.Status+')' , data: resthishouse}
                        }
                        setManageHouseId(optionshse)

                        let optionstent=[];
                        if(tid!==''){
                            optionstent={value: resthistenant.id, label: resthistenant.Fname+' '+resthistenant.Oname+'('+resthistenant.Status+')' , data: resthistenant}
                        }
                        setTenantsId(optionstent)

                        let thisurl=`/properties/mgr/tenants/${tid}/reassign/${hid}/${shid}`;
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
                url=`/v2/properties/mgr/tenants/reassign/${thihouse}/${thitenant}/None`;
            }
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let reshouseinfo = response.data.houseinfo;
                        let resthishouse = response.data.thishouse;
                        let resthistenant = response.data.thistenant;
                        let restenantinfo = response.data.tenantinfo;

                        let hid=response.data.hid;
                        let tid=response.data.tid;
                        let shid=response.data.shid;

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

                        let optionshse=[];
                        if(shid!==''){
                            optionshse={value: resthishouse.id, label: resthishouse.Housename+'('+resthishouse.Status+')' , data: resthishouse}
                        }
                        setManageHouseId(optionshse)

                        let optionstent=[];
                        if(tid!==''){
                            optionstent={value: resthistenant.id, label: resthistenant.Fname+' '+resthistenant.Oname+'('+resthistenant.Status+')' , data: resthistenant}
                        }
                        setTenantsId(optionstent)

                        let thisurl=`/properties/mgr/tenants/${tid}/reassign/${hid}`;
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

    const loadAssigning =(thihouse,thitenant,thisselhouse) =>{

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
            CustomInitialCharges:false,
            MonthlyCharge:false,
            Refund:'',
            InitialPay:'',
            Charge:500.00,
            CarriedForward:'',
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
            
            if(thihouse==='' || thitenant==='' || thisselhouse===''){
                // setLoadingMonths(false)
                return false;
            }
            else{
                url=`/v2/properties/mgr/tenants/reassign/${thihouse}/${thitenant}/${thisselhouse}`;
            }
            
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let reshouseinfo = response.data.houseinfo;
                        let resthishouse = response.data.thishouse;
                        let resthistenant = response.data.thistenant;
                        let restenantinfo = response.data.tenantinfo;

                        let hid=response.data.hid;
                        let tid=response.data.tid;
                        let shid=response.data.shid;

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

                        let optionshse=[];
                        if(shid!==''){
                            optionshse={value: resthishouse.id, label: resthishouse.Housename+'('+resthishouse.Status+')' , data: resthishouse}
                        }
                        setManageHouseId(optionshse)

                        let optionstent=[];
                        if(tid!==''){
                            optionstent={value: resthistenant.id, label: resthistenant.Fname+' '+resthistenant.Oname+'('+resthistenant.Status+')' , data: resthistenant}
                        }
                        setTenantsId(optionstent)

                        let thisurl=`/properties/mgr/tenants/${tid}/reassign/${hid}/${shid}`;
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
        
        if(e.target.name==='DateAssigned'){
            setFormData({...formdata,[e.target.name]:e.target.value})
        }
        else if(e.target.name==='InitialPay' && formdata.CustomInitialCharges===true){
            if(e.target.value===''){
                setFormData({...formdata,[e.target.name]:''})
            }
            else{
                setFormData({...formdata,[e.target.name]:e.target.value})
            }
        }
        else if(e.target.name==='Refund' && formdata.CustomRefund===true){
            if(e.target.value===''){
                setFormData({...formdata,[e.target.name]:''})
            }
            else{
                setFormData({...formdata,[e.target.name]:e.target.value})
            }
        }
        else if(e.target.name==='Charge'){
            if(e.target.value===''){
                setFormData({...formdata,[e.target.name]:''})
            }
            else{
                setFormData({...formdata,[e.target.name]:e.target.value})
            }
        }
        else if(e.target.name==='CarriedForward'){
            if(e.target.value===''){
                setFormData({...formdata,[e.target.name]:''})
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

    const handleDamagesChange=(e)=>{
        e.persist();
        const damages=e.target.value;
        const current=formdata.current;
        const cost=formdata.cost;

        const units=(current)-(0);
        const total=(units)*(cost);
    }

    

    
    const assignTenant= (house)=>{

        if(housedata ===''){
            Swal("House Not Selected","Please Select house Tenant is Moving to","error");
            return;
        }

        if(formdata.CustomInitialCharges===true && formdata.InitialPay ===''){
            Swal("Enter Initial Charges Value","Please Enter Custom Initial Charges Value to be Paid by Tenant on Moving to Property","error");
            return;
        }

        if(formdata.CustomRefund===true && formdata.Refund ===''){
            Swal("Enter Refund Value","Please Enter Custom Refund or Select What to be Refunded on Vacating Property","error");
            return;
        }

        let refund=formdata.CustomRefund?new Number(formdata.Refund).toFixed(2):(new Number(new Number(formdata.HouseRentRefund?housedata.Rent:0.00) + new Number(formdata.GarbageRefund?housedata.Garbage:0.00) + new Number(formdata.HouseDepositRefund?housedata.Deposit:0.00) + new Number(formdata.KPLCDepositRefund?housedata.Kplc:0.00) + new Number(formdata.WaterDepositRefund?housedata.Water:0.00) + new Number(formdata.LeaseRefund?housedata.Lease:0.00) )).toFixed(2)
        let initialpay=formdata.CustomInitialCharges?new Number(formdata.InitialPay).toFixed(2):(new Number(new Number(formdata.HouseRent?housedata.Rent:0.00) + new Number(formdata.Garbage?housedata.Garbage:0.00) + new Number(formdata.HouseDeposit?housedata.Deposit:0.00) + new Number(formdata.KPLCDeposit?housedata.Kplc:0.00) + new Number(formdata.WaterDeposit?housedata.Water:0.00) + new Number(formdata.Lease?housedata.Lease:0.00) )).toFixed(2)
        // let initialpay=(new Number(new Number(formdata.HouseRent?housedata.Rent:0.00) + new Number(formdata.Garbage?housedata.Garbage:0.00) + new Number(formdata.HouseDeposit?housedata.Deposit:0.00) + new Number(formdata.KPLCDeposit?housedata.Kplc:0.00) + new Number(formdata.WaterDeposit?housedata.Water:0.00) + new Number(formdata.Lease?housedata.Lease:0.00) )).toFixed(2)
        let monthlypay=formdata.MonthlyCharge?(new Number(new Number(formdata.HouseRent?housedata.Rent:0.00) + new Number(formdata.Garbage?housedata.Garbage:0.00) )).toFixed(2):0.00
        let carried=formdata.CarriedForward?new Number(formdata.CarriedForward).toFixed(2):new Number(paymentsdata[0].Refund).toFixed(2);
        //innclude arrears and excess
        let bal=(new Number(carried))>0?" (Arrears)":" (Excesss)"
        
        if(formdata.CustomInitialCharges===true  && formdata.InitialPay <0){
            Swal("Enter Initial Charges Value","Please Enter Custom Initial Charges Value to be Paid by Tenant on Moving to Property","error");
            return;
        }

        if(formdata.CustomRefund===true  && formdata.Refund <0){
            Swal("Enter Refund Value","Please Enter Custom Refund to be Refunded on Vacating Property","error");
            return;
        }


        if(formdata.CustomRefund===false && (housedata.Deposit >=0 || housedata.Kplc >=0 || housedata.Water >=0) && refund <=0){
            Swal("Select What to Refund","Please Select Values to Refund or Custom Refund to be Refunded on Vacating Property","error");
            return;
        }

        if(formdata.DateAssigned===undefined || formdata.DateAssigned ===""){
            Swal("Date Moving Tenant Needed","Please Choose Date Tenant Was Moved","error");
            return;
        }
        
        const form={
            hid:house.id,
            tid:tenant,
            fromhid:tenantdata.curhouse.id,
            Refund:refund,
            InitialCharges:(initialpay),
            MonthlyCharges:(monthlypay),
            Charges:new Number(formdata.Charge).toFixed(2),
            CarriedForward:(carried),
            DateAssigned:formdata.DateAssigned,
            HouseRent:formdata.HouseRent,
            Garbage:formdata.Garbage,
            HouseDeposit:formdata.HouseDeposit,
            WaterDeposit:formdata.WaterDeposit,
            KPLCDeposit:formdata.KPLCDeposit,
            Lease:formdata.Lease,
        }
        // console.log(form)

        // let
        let title='Move '+ tenantdata.tenantname + ' To '+house.Housename + ' From '+tenantdata.curhouse.Housename;
        let text="This will Move tenant to this House.\n"+
        "\n Initial Charges to Pay :: "+ new Number(initialpay).toFixed(2)+
        "\n Carried Forward :: "+ new Number(carried).toFixed(2)+ bal +
        "\n Miscellanoues Charges :: "+ new Number(formdata.Charge).toFixed(2)+
        "\n Deposit/Refundable :: "+ new Number(refund).toFixed(2)+
        "\n Total Amount Expected to Pay :: "+ new Number((new Number(initialpay)+new Number(carried))+new Number(formdata.Charge)).toFixed(2);
        Swal({
            title:title+' ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Moving....","Please Wait");
                axios.post('/v2/reassign/house/save',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        setFormData({...formdata,error_list:[]});
                        if(socket) {
                            socket.emit('tenant_assigned',response.data.message);
                        }
                        Swal("Success",response.data.message);
                        loadTenants();
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
                        Swal("Error","Please Update the Following errors","error");
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
        setSelHouseID('None')
        
        const tenantid=val.value;
        if(tenantid!==''){
            setHouseID(house)
            setTenantID(tenantid)
            let thisurl=`/properties/mgr/tenants/${tenantid}/reassign/${house}`;
            navigate(thisurl)
            loadVacating('None',tenantid);
        }

        // setLoadingMonths(false)
    }

    function handleHouseChange(val) {
        setLoadingMonths(true)
        setSelHouseID(val.value)
        let options={value: val.value, label: val.label , data: val}
        
        setManageHouseId(options)
        const plotid=val.data['Plot'];
        const houseid=val.value;

        if(houseid!==''){
            setHouseID(house)
            setTenantID(tenant)
            let thisurl=`/properties/mgr/tenants/${tenant}/reassign/${house}/${houseid}`;
            navigate(thisurl)
            loadAssigning('None',tenant,houseid);
        }

        // setLoadingMonths(false)
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

    function reassignTenantThis(house) {
        setLoadingMonths(true)
        setHouseID(house.hid)
        setSelHouseID('None')
        
        const curhouseid=house.hid;

        if(curhouseid!==''){
            setHouseID(house.hid)
            let thisurl=`/properties/mgr/tenants/${tenant}/reassign/${curhouseid}`;
            navigate(thisurl)
            loadVacating(curhouseid,tenant);
        }

        setLoadingMonths(false)
    }

    document.title="Reassign Tenant";

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
                                    <li className="breadcrumb-item active">Reassigning to: { !loadingmonths && housedata.Housename && housedata.Housename} </li>
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
                                                    {/* <div className="col-12 m-0 p-1 mt-1">
                                                        
                                                        <h4 className='text-info mx-auto text-center'>
                                                            {tenantdata.Status==="Reassigned"?'Reassigned':'Reassigning'} <span className='text-success'> { !loadingmonths && tenantdata.tenantname }
                                                            (<small>
                                                                <i className='text-lime'>
                                                                { !loadingmonths?tenantdata.Housenames?tenantdata.Housenames:tenantdata.Status:""} 
                                                                </i>
                                                            </small>)
                                                            </span> To: <small>
                                                                <i className='text-lime'>
                                                                { !loadingmonths?housedata.id?housedata.Housename:'No House Selected':""} 
                                                                </i>
                                                            </small>
                                                            
                                                        </h4>

                                                    </div> */}
                                                    {!loadingmonths && 
                                                        <div className="col-12 m-0 p-1 mt-1">
                                                            <p className='text-info mx-auto text-center'>
                                                            From:
                                                                <i className='text-red'>
                                                                { !loadingmonths?tenantdata.Housenames?" "+tenantdata.Housenames:tenantdata.Status:""} 
                                                                </i>,
                                                                To:
                                                                <i className='text-lime'>
                                                                { !loadingmonths?housedata.id?" "+housedata.Housename:' No House Selected':""} 
                                                                </i> 
                                                                {tenantdata.Houses >1 &&
                                                                    <>, Pick Another Instead?
                                                                    {tenantdata.housesdata  && tenantdata.housesdata.map((houses,key) => (
                                                                        <>
                                                                        {tenantdata.curhouse.id!==houses.hid &&
                                                                            <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-danger' onClick={()=>{reassignTenantThis(houses);setLoadingMonths(true)}}><Link to={'/properties/mgr/tenants/'+tenantdata.id+'/reassign/'+houses.hid} className='text-success'><i className='fa fa-exchange-alt'></i> Reassign {houses.Housename}</Link></button>
                                                                        }
                                                                        </>
                                                                    ))
                                                                    }
                                                                    </>
                                                                }
                                                            </p>
                                                            {/* <p className='text-info mx-auto text-center'>
                                                            <span className='text-info mx-auto text-center'>You Can Also Choose Another House to Reassign</span>
                                                            {tenantdata.housesdata  && tenantdata.housesdata.map((houses,key) => (
                                                                <>
                                                                {tenantdata.curhouse.id!==houses.hid &&
                                                                    <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-danger' onClick={()=>{reassignTenantThis(houses);setLoadingMonths(true)}}><Link to={'/properties/mgr/tenants/'+tenantdata.id+'/reassign/'+houses.hid} className='text-success'><i className='fa fa-exchange-alt'></i> Reassign {houses.Housename}</Link></button>
                                                                }
                                                                </>
                                                            ))
                                                            }
                                                            </p> */}
                                                        </div>
                                                    } 
                                                </div>

                                            </div>

                                            <div className="row m-0 p-0 justify-content-center text-center border-none">
                                                {agreementdata[0] && housedata.Status !=="Occupied" &&
                                                <div className="row m-0 p-0" style={{"overflowX":"auto"}}>
                                                    {loadingmonths &&
                                                        <div className="col-12 col-md-6 col-lg-4 text-left m-0 p-0 mt-1 mb-2">
                                                            <HouseDetailsSpinner />
                                                        </div>
                                                    }
                                                    {!loadingmonths && agreementdata[0] &&
                                                    <div className="col-12 col-lg-6 text-left m-0 p-0 mt-1 mb-2">
                                                        <div className="card border-info m-0 p-1 mt-3" >
                                                            <div className="card-header bg-success border-info text-muted text-center m-0 mb-2 p-2 pt-1 pb-2">
                                                                <span style={{"float":"left"}}>
                                                                    <span className={`m-0 p-1 text-sm bg-light ${tenantdata.curhouse.Status==='Occupied'?'text-info':'text-danger'}`}
                                                                        style={{"borderRadius":"30px","border":"2px solid white"}}> {tenantdata.Fname}</span> 
                                                                </span> 
                                                                <span className='m-0 p-1 text-sm text-white mx-auto elevation-2 border-info bg-primary text-center'><span className='text-warning'>{tenantdata.curhouse.Housename}</span> to: <span className='text-black'>{ !loadingmonths?housedata.id?housedata.Housename:'No House Selected':""}</span></span>
                                                                
                                                                
                                                            </div>
                                                            
                                                            <div className="card-body text-center text-muted text-sm m-0 p-0 pt-1">
                                                                <div className='row m-0 mb-2 p-1 elevation-0 border-none'>
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
                                                                                {agreementdata[0].PhoneMasked}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                {/* Current House Data */}
                                                                <div className='col-12 m-0 mb-2 p-1 border-ok elevation-2'>
                                                                    <p className='text-left mb-1'>House Moving from  <span style={{"float":"right"}} className='elevation-2 border-info p-0 pl-1 pr-1 text-right'>{tenantdata.curhouse.Housename}</span></p>
                                                                    <label className='bg-warning m-0 p-1 text-xs border-info reassignlbl' title='House Rent'>Rent: 
                                                                            <span className='bold reassigntxt'> {new Number(tenantdata.curhouse.Rent).toFixed(2)}</span> 
                                                                        </label>
                                                                    <label className='m-0 bg-warning p-1 text-xs border-info reassignlbl' title='Garbage'>Bin: 
                                                                        <span className='bold reassigntxt'> {new Number(tenantdata.curhouse.Garbage).toFixed(2)}</span> 
                                                                    </label>
                                                                    <label className='m-0 bg-warning p-1 text-xs border-info reassignlbl' title='House Deposit'>Deposit: 
                                                                        <span className='bold reassigntxt'> {new Number(tenantdata.curhouse.Deposit).toFixed(2)}</span> 
                                                                    </label>
                                                                    <label className='m-0 bg-warning p-1 text-xs border-info reassignlbl' title='KPLC Deposit'>KPLC D: 
                                                                        <span className='bold reassigntxt'> {new Number(tenantdata.curhouse.Kplc).toFixed(2)}</span> 
                                                                    </label>
                                                                    <label className='m-0 bg-warning p-1 text-xs border-info reassignlbl' title='Water Deposit'>Water D: 
                                                                        <span className='bold reassigntxt'> {new Number(tenantdata.curhouse.Water).toFixed(2)}</span> 
                                                                    </label>
                                                                    <label className='m-0 bg-warning p-1 text-xs border-info reassignlbl' title='Lease'>Lease: 
                                                                        <span className='bold reassigntxt'> {new Number(tenantdata.curhouse.Lease).toFixed(2)}</span> 
                                                                    </label>
                                                                    <label className='m-0 bg-light p-1 text-xs border-info reassignlbl' title='Total Used'>Billed: 
                                                                        <span className='bold reassigntxt'> {new Number(paymentsdata[0].TotalUsed).toFixed(2)}</span> 
                                                                    </label>
                                                                    <label className='m-0 bg-light p-1 text-xs border-info reassignlbl' title='Total Paid'>Paid: 
                                                                        <span className='bold reassigntxt'> {new Number(paymentsdata[0].TotalPaid).toFixed(2)}</span> 
                                                                    </label>
                                                                    <label className='m-0 bg-light p-1 text-xs border-info reassignlbl' title='Balance'>Balance: 
                                                                        <span className='bold reassigntxt'> {new Number(paymentsdata[0].Balance).toFixed(2)}</span> 
                                                                    </label>
                                                                    <label className='m-0 bg-light p-1 text-xs border-info reassignlbl' title='Refund'>Refund: 
                                                                        <span className='bold reassigntxt'> {new Number(paymentsdata[0].Refund).toFixed(2)}</span> 
                                                                    </label>
                                                                </div>

                                                                {/* House Moving to Data */}
                                                                <div className='col-12 m-0 mb-2 p-1 text-black border-info elevation-2'>
                                                                    <p className='text-left mb-1'>House Moving to <span style={{"float":"right"}} className='elevation-2 border-info p-0 pl-1 pr-1'>{ !loadingmonths?housedata.id?housedata.Housename:'No House Selected':""}</span> </p>
                                                                    {/* {`m-0 p-1 text-xs ${formdata.HouseRent?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`} */}
                                                                    <label className={`${formdata.HouseRent?'bg-success text-white':'bg-info text-white'} m-0 p-1 text-xs border-ok reassignlbl`} title='House Rent'>Rent: 
                                                                            <span className='bold reassigntxt'> {new Number(housedata.Rent).toFixed(2)}</span> {formdata.HouseRent? <i className='fa fa-check text-lime'></i>:''}
                                                                        </label>
                                                                    <label className={`${formdata.Garbage?'bg-success text-white':'bg-info text-white'} m-0 p-1 text-xs border-ok reassignlbl`} title='Garbage'>Garbage: 
                                                                        <span className='bold reassigntxt'> {new Number(housedata.Garbage).toFixed(2)}</span> {formdata.Garbage? <i className='fa fa-check text-lime'></i>:''}
                                                                    </label>
                                                                    <label className={`${formdata.HouseDeposit?'bg-success text-white':'bg-info text-white'} m-0 p-1 text-xs border-ok reassignlbl`} title='House Deposit'>Deposit: 
                                                                        <span className='bold reassigntxt'> {new Number(housedata.Deposit).toFixed(2)}</span> {formdata.HouseDeposit? <i className='fa fa-check text-lime'></i>:''}
                                                                    </label>
                                                                    <label className={`${formdata.KPLCDeposit?'bg-success text-white':'bg-info text-white'} m-0 p-1 text-xs border-ok reassignlbl`} title='KPLC Deposit'>KPLC D: 
                                                                        <span className='bold reassigntxt'> {new Number(housedata.Kplc).toFixed(2)}</span> {formdata.KPLCDeposit? <i className='fa fa-check text-lime'></i>:''}
                                                                    </label>
                                                                    <label className={`${formdata.WaterDeposit?'bg-success text-white':'bg-info text-white'} m-0 p-1 text-xs border-ok reassignlbl`} title='Water Deposit'>Water D: 
                                                                        <span className='bold reassigntxt'> {new Number(housedata.Water).toFixed(2)}</span> {formdata.WaterDeposit? <i className='fa fa-check text-lime'></i>:''}
                                                                    </label>
                                                                    <label className={`${formdata.Lease?'bg-success text-white':'bg-info text-white'} m-0 p-1 text-xs border-ok reassignlbl`} title='Lease'>Lease: 
                                                                        <span className='bold reassigntxt'> {new Number(housedata.Lease).toFixed(2)}</span> {formdata.Lease? <i className='fa fa-check text-lime'></i>:''}
                                                                    </label>
                                                                </div>       

                                                                <div className='col-12 m-0 mb-2 p-1 text-black border-info elevation-2'>
                                                                    <h4 className='text-sm p-2 text-black'>Select what to do</h4>
                                                                    {/* {housedata.Rent+housedata.Garbage >0 &&
                                                                        <label className={`m-0 p-1 text-xs ${formdata.MonthlyCharge?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`}  >
                                                                            <input type="checkbox" required checked={formdata.MonthlyCharge?'True':''} onChange={handleInputChange} className="" name="MonthlyCharge" value="MonthlyCharge"/> MonthlyCharge
                                                                        </label>
                                                                    } */}
                                                                    
                                                                    <label className={`m-0 p-1 text-xs ${formdata.CustomInitialCharges?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`}  >
                                                                        <input type="checkbox" required checked={formdata.CustomInitialCharges?'True':''} onChange={handleInputChange} className="" name="CustomInitialCharges" value="Custom Refund"/> Custom Initial Charges
                                                                    </label>
                                                                    <label className={`m-0 p-1 text-xs ${formdata.CustomRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`}  >
                                                                        <input type="checkbox" required checked={formdata.CustomRefund?'True':''} onChange={handleInputChange} className="" name="CustomRefund" value="Custom Refund"/> Custom Refund
                                                                    </label>
                                                                </div>  

                                                                {!formdata.CustomInitialCharges && 
                                                                        <div className='col-12 m-0 mb-2 p-1 text-white border-info elevation-2'>
                                                                            <h4 className='text-sm p-2 text-black'>Select What to Include in this Month for Tenant {tenantdata.tenantname} in House {housedata.Housename}</h4>
                                                                            
                                                                                {housedata.Rent >0 &&
                                                                                    <label className={`m-0 p-1 text-xs ${formdata.HouseRent?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`} title='House Rent Charges'>
                                                                                            <input type="checkbox" required checked={formdata.HouseRent?'True':''} onChange={handleInputChange} className="" name="HouseRent" value="Rent"/> Rent
                                                                                    </label>
                                                                                }
                                                                                {housedata.Garbage >0 &&
                                                                                    <label className={`m-0 p-1 text-xs ${formdata.Garbage?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`}  title='Garbage Charges' >
                                                                                        <input type="checkbox" required checked={formdata.Garbage?'True':''} onChange={handleInputChange} className="" name="Garbage" value="Garbage"/> Bin
                                                                                    </label>
                                                                                }
                                                                                {housedata.Deposit >0 &&
                                                                                    <label className={`m-0 p-1 text-xs ${formdata.HouseDeposit?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`} title='House Deposit' >
                                                                                            <input type="checkbox" required checked={formdata.HouseDeposit?'True':''} onChange={handleInputChange} className="" name="HouseDeposit" value="House Deposit"/> Deposit
                                                                                    </label>
                                                                                }
                                                                                {housedata.Kplc >0 &&
                                                                                    <label className={`m-0 p-1 text-xs ${formdata.KPLCDeposit?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`} title='KPLC Deposit'  >
                                                                                            <input type="checkbox" required checked={formdata.KPLCDeposit?'True':''} onChange={handleInputChange} className="" name="KPLCDeposit" value="KPLC Deposit"/> KPLC
                                                                                    </label>
                                                                                }
                                                                                {housedata.Water >0 &&
                                                                                    <label className={`m-0 p-1 text-xs ${formdata.WaterDeposit?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`} title='Water Deposit'  >
                                                                                        <input type="checkbox" required checked={formdata.WaterDeposit?'True':''} onChange={handleInputChange} className="" name="WaterDeposit" value="Water Deposit"/> Water
                                                                                    </label>
                                                                                }
                                                                                {housedata.Lease >0 &&
                                                                                    <label className={`m-0 p-1 text-xs ${formdata.Lease?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`}  >
                                                                                        <input type="checkbox" required checked={formdata.Lease?'True':''} onChange={handleInputChange} className="" name="Lease" value="Lease"/> Lease
                                                                                    </label>
                                                                                }
                                                                                
                                                                        </div>
                                                                    }
                                                                    {!formdata.CustomRefund && !formdata.CustomInitialCharges &&
                                                                        <div className='col-12 m-0 mb-2 p-1 text-black border-info elevation-2'>
                                                                            <h4 className='text-sm p-2 text-black'>Include this as Refundable to {tenantdata.tenantname} in House {housedata.Housename}</h4>
                                                                            
                                                                                {housedata.Garbage >0 &&
                                                                                    <label className={`m-0 p-1 text-xs ${formdata.GarbageRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`}  title='GarbageRefund Charges' >
                                                                                        <input type="checkbox" required checked={formdata.GarbageRefund?'True':''} onChange={handleInputChange} className="" name="GarbageRefund" value="GarbageRefund"/> Bin(Refund)
                                                                                    </label>
                                                                                }
                                                                                {housedata.Deposit >0 &&
                                                                                    <label className={`m-0 p-1 text-xs ${formdata.HouseDepositRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`} title='House Deposit' >
                                                                                            <input type="checkbox" required checked={formdata.HouseDepositRefund?'True':''} onChange={handleInputChange} className="" name="HouseDepositRefund" value="House Deposit"/> Deposit(Refund)
                                                                                    </label>
                                                                                }
                                                                                {housedata.Kplc >0 &&
                                                                                    <label className={`m-0 p-1 text-xs ${formdata.KPLCDepositRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`} title='KPLC Deposit'  >
                                                                                            <input type="checkbox" required checked={formdata.KPLCDepositRefund?'True':''} onChange={handleInputChange} className="" name="KPLCDepositRefund" value="KPLC Deposit"/> KPLC(Refund)
                                                                                    </label>
                                                                                }
                                                                                {housedata.Water >0 &&
                                                                                    <label className={`m-0 p-1 text-xs ${formdata.WaterDepositRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`} title='Water Deposit'  >
                                                                                        <input type="checkbox" required checked={formdata.WaterDepositRefund?'True':''} onChange={handleInputChange} className="" name="WaterDepositRefund" value="Water Deposit"/> Water(Refund)
                                                                                    </label>
                                                                                }
                                                                                {housedata.Lease >0 &&
                                                                                    <label className={`m-0 p-1 text-xs ${formdata.LeaseRefund?'bg-success text-white':'bg-white text-black'} border-info m-1 reassignlbl`}  >
                                                                                        <input type="checkbox" required checked={formdata.LeaseRefund?'True':''} onChange={handleInputChange} className="" name="LeaseRefund" value="LeaseRefund"/> Lease(Refund)
                                                                                    </label>
                                                                                }
                                                                            
                                                                        </div> 
                                                                    }                         
                                                         
                                                                
                                                                
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
                                                                <div className="border-info card m-0 p-1 elevation-0">
                                                                    {/* <div className="card-header bg-success border-info text-muted text-center m-0 mb-2 p-2 pt-1 pb-2">
                                                                        
                                                                        <span className='m-0 p-1 text-sm text-dark mx-auto'>Ensure you Click on Charges that applies to this Tenant and click on Assign</span>
                                                                        
                                                                    </div> */}
                                                                    
                                                                    <div className='col-12 m-0 mb-2 p-1 text-black border-info elevation-2'>
                                                                        <span className='m-0 p-1 text-sm text-dark mx-auto'>Ensure you Update Charges that applies to this Tenant and then Submit</span>

                                                                        <div className="form-group row m-0 p-1 pb-2">
                                                                            <label htmlFor="InitialCharges" className="col-sm-4 col-12 col-form-label text-md-right">Initial Charges</label>

                                                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                                                {formdata.CustomInitialCharges?
                                                                                    <input id="InitialPay" type="text" className="form-control" name="InitialPay" value={formdata.CustomInitialCharges?formdata.InitialPay:(new Number(new Number(formdata.HouseRent?housedata.Rent:0.00) + new Number(formdata.Garbage?housedata.Garbage:0.00) + new Number(formdata.HouseDeposit?housedata.Deposit:0.00) + new Number(formdata.KPLCDeposit?housedata.Kplc:0.00) + new Number(formdata.WaterDeposit?housedata.Water:0.00) + new Number(formdata.Lease?housedata.Lease:0.00) )).toFixed(2)} onChange={handleInputChange} placeholder="0.00" required autoFocus/>
                                                                                    :
                                                                                    <input id="InitialPay" type="text" className="form-control" name="InitialPay" value={formdata.CustomInitialCharges?formdata.InitialPay:(new Number(new Number(formdata.HouseRent?housedata.Rent:0.00) + new Number(formdata.Garbage?housedata.Garbage:0.00) + new Number(formdata.HouseDeposit?housedata.Deposit:0.00) + new Number(formdata.KPLCDeposit?housedata.Kplc:0.00) + new Number(formdata.WaterDeposit?housedata.Water:0.00) + new Number(formdata.Lease?housedata.Lease:0.00) )).toFixed(2)} onChange={handleInputChange} placeholder="0.00" required readOnly="readOnly" autoFocus/>
                                                                                }
                                                                                {formdata.error_list && formdata.error_list.InitialCharges && 
                                                                                    <span className="help-block text-danger">
                                                                                        <strong>{formdata.error_list.InitialCharges}</strong>
                                                                                    </span>
                                                                                }
                                                                            </div>

                                                                        </div>


                                                                        <div className="form-group row m-0 p-1 pb-2">
                                                                            <label htmlFor="Charge" className="col-sm-4 col-12 col-form-label text-md-right">Moving Charge: </label>

                                                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                                            
                                                                                <input id="Charge" type="text" className="form-control" name="Charge" value={formdata.Charge} onChange={handleInputChange} placeholder="0.00" required  autoFocus/>
                                                                                {formdata.error_list && formdata.error_list.Charge && 
                                                                                    <span className="help-block text-danger">
                                                                                        <strong>{formdata.error_list.Charge}</strong>
                                                                                    </span>
                                                                                }
                                                                            </div>
                                                                        </div>

                                                                        <div className="form-group row m-0 p-1 pb-2">
                                                                            <label htmlFor="CarriedForward" className="col-sm-4 col-12 col-form-label text-md-right">Carried Forward: </label>

                                                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                                            
                                                                                <input id="CarriedForward" type="text" className="form-control" name="CarriedForward" value={formdata.CarriedForward?formdata.CarriedForward:new Number(paymentsdata[0].Refund).toFixed(2)} onChange={handleInputChange} placeholder="0.00" required  autoFocus/>
                                                                                {formdata.error_list && formdata.error_list.CarriedForward && 
                                                                                    <span className="help-block text-danger">
                                                                                        <strong>{formdata.error_list.CarriedForward}</strong>
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
                                                                            <label htmlFor="Total Amount Expected to Pay" className="col-sm-4 col-12 col-form-label text-md-right">Total Amount Expected to Pay</label>

                                                                            <div className="col-sm-8 col-12 m-0 p-0">
                                                                                <input id="Total Amount Expected to Pay" type="text" className="form-control" name="Total Amount Expected to Pay" value={new Number(new Number(formdata.CustomInitialCharges?formdata.InitialPay:(new Number(new Number(formdata.HouseRent?housedata.Rent:0.00) + new Number(formdata.Garbage?housedata.Garbage:0.00) + new Number(formdata.HouseDeposit?housedata.Deposit:0.00) + new Number(formdata.KPLCDeposit?housedata.Kplc:0.00) + new Number(formdata.WaterDeposit?housedata.Water:0.00) + new Number(formdata.Lease?housedata.Lease:0.00) )))+new Number(formdata.CarriedForward?formdata.CarriedForward:new Number(paymentsdata[0].Refund))+new Number(formdata.Charge)).toFixed(2)} onChange={handleInputChange} placeholder="1" readOnly required autoComplete="Total Amount Expected to Pay" autoFocus/>
                                                                                {formdata.error_list && formdata.error_list.TotalAmountExpectedtoPay && 
                                                                                    <span className="help-block text-danger">
                                                                                        <strong>{formdata.error_list.TotalAmountExpectedtoPay}</strong>
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
                                                                                            Moving ...</span>
                                                                                            
                                                                                    </div>
                                                                                }
                                                                                
                                                                                {!loading && loadingresok ==='' && 
                                                                                    <div className="col-md-12 justify-content-center text-center">
                                                                                        <button type="submit" className="btn btn-success" onClick={()=>{assignTenant(housedata)}}>
                                                                                            Moving Tenant to { !loadingmonths?housedata.Housename?housedata.Housename:"No House Selected":""} 
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
                                                                            Tenant { !loadingmonths && tenantdata.tenantname }  has been reassigned to  { !loadingmonths?housedata.Housename?housedata.Housename:"No House Selected":""} 
                                                                        </span>
                                                                    </h4>
                                                                </div>
                                                                <div className="card-body text-center text-muted text-sm m-0 p-0 pt-1">
                                                                    <div className='row m-0 p-1 elevation-0 border-none'>
                                                                        <h4>You Can do the following meanwhile!!</h4>
                                                                            <p>
                                                                                <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+tenant} className='text-info'><i className='fa fa-plus-circle'></i> View Tenant</Link></button>
                                                                                <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+tenant+'/addhouse/'} className='text-info'><i className='fa fa-plus-circle'></i> Add House</Link></button>
                                                                                <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-danger'><Link to={'/properties/mgr/tenants/'+tenant+'/vacate/'+housedata.id} className='text-danger'><i className='fa fa-minus-circle'></i> Vacate </Link></button>
                                                                                <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+tenant+'/reassign/'+housedata.id} className='text-success'><i className='fa fa-exchange-alt'></i> Change House</Link></button>
                                                                                <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-info'><Link to={'/properties/mgr/tenants/'+tenant+'/transfer/'+housedata.id} className='text-primary'><i className='fa fa-play'></i> Transfer IN</Link></button>
                                                                                   
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

export default ManagePropertyReassignHseTenant;