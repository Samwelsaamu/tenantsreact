import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useContext, useState } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';
import axios from 'axios';

import { Link, useNavigate } from 'react-router-dom';
import Select from 'react-select';
import { useParams } from 'react-router';

import Swal from 'sweetalert';

import avatar from '../../assets/img/avatar5.png';
import avatar3 from '../../assets/img/avatar2.png';
import AddHouse from './AddHouse';
import HouseDetailsSpinner from '../spinners/HouseDetailsSpinner';
import HouseTenantDetailsSpinner from '../spinners/HouseTenantDetailsSpinner';
import ManagePropertyVacateHseTenant from './ManagePropertyVacateHseTenant';
import ReLogin from '../home/ReLogin';
import { LoginContext } from '../contexts/LoginContext';


function ManagePropertyHouse(props) {
    document.title="Manage House";
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
         
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();


    let par=useParams()

    const [plot,setPlotID]=useState((par.plot)?par.plot:'')
    const [id,setID]=useState((par.id)?par.id:'')
    const [tenantsid,setTenantsId]=useState([""]);
    // console.log(plot,id)

    const [closed,setClosed]=useState(false)
    const [isOpen, setIsOpen] = useState(false)


    const [waterbillpropertyid,setWaterbillPropertyId]=useState([""]);
    const [managehouseid,setManageHouseId]=useState([""]);
    const [waterbillmonth,setWaterbillMonth]=useState([""]);
    const [updatemonths, setUpdateMonths] = useState([""]);
    const [propertyinfo, setPropertyinfo] = useState([""]);
    const [tenantdata, setTenantdata] = useState([]);
    const [tenantinfo, setTenantinfo] = useState([""]);
    
    const [houseinfo, setHouseinfo] = useState([""]);
    
    const [propertydata, setPropertydata] = useState([]);
    const [housedata, setHousedata] = useState([]);
    const [agreementdata, setAgreementdata] = useState([]);
    const [waterbilldata, setWaterbillData] = useState([""]);
    
    const [currentwaterbill, setCurrentWaterbill] = useState([""]);

    const [currentproperty, setCurrentProperty] = useState([""]);
    const [currentpropertyhousesel, setCurrentPropertyHouseSel] = useState([""]);

    const [currenthouse, setCurrentHouse] = useState('');
    const [showaddhouse,setShowAddHouse]=useState(false);
    
    const [preview, setPreview] = useState(false);

    const [search,setSearch]=useState({
        value:'',
        result:[]
    })

    const [searchhouse,setSearchhouse]=useState({
        value:'',
        result:[]
    })

    const [uploadwaterbill,setUploadWaterbill]=useState({
        upwaterbill:[]
    })
      

    const [showvacatehouse,setShowVacateHouse]=useState(false);
    
    const [showaddproperty,setShowAddProperty]=useState(false);
    const [show,setShow]=useState(false);
    
    const [property,setProperty]=useState('');
   

    // const [loading,setLoading]=useState(true);
    const [loadingmonths,setLoadingMonths]=useState(true);
    const [loadingwater,setLoadingWater]=useState(true);
    const [loadingstats,setLoadingStats]=useState(true);
    

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
                arr1.push({value: '', label: 'Select Property' });
            const arr2 = [];
                arr2.push({value: '', label: 'Select Tenant' });

            let url=`/v2/properties/house/${plot}/${id}`;
            if(plot===''){
                url='/v2/properties/manage/load';
            }
            else{
                // if(plot==='all'){
                //     url=`/v2/properties/manage/load`;
                // }
                // else{
                    setLoadingMonths(false)
                    return false;
                // }
                
            }
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        let restenantinfo = response.data.tenantinfo;
                        let reshouseinfo = response.data.houseinfo;

                        let resthisproperty = response.data.thisproperty;
                        setCurrentProperty(response.data.thisproperty);

                        setPropertydata(response.data.propertyinfo)
                        setHousedata([])
                        setAgreementdata([])

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr1)

                        reshouseinfo.map((houseup) => {
                            return arr.push({value: houseup.id, label: houseup.Housename+'('+houseup.Status+')' , data: houseup});
                        });
                        setHouseinfo(arr)

                        restenantinfo.map((tenantsdata) => {
                            return arr2.push({value: tenantsdata.id, label: tenantsdata.Fname+' '+tenantsdata.Oname+'('+tenantsdata.Status+')' , data: tenantsdata});
                        });
                        setTenantinfo(arr2)
                        
                        let options=[];
                        if(id!==''){
                            options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
                            setPlotID(resthisproperty.id);
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


    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getProperties = async (e) => { 
            const arr = [];
                arr.push({value: '', label: 'Select House' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Property' });
            const arr2 = [];
                arr2.push({value: '', label: 'Select Tenant' });
            let url=`/v2/properties/house/${plot}/${id}`;
            

            if(id===''){
                setLoadingMonths(false)
                return false;
            }
            else{
                if(id==='all'){
                    url=`/v2/properties/manage/load`;
                }
                else{
                    url=`/v2/properties/house/${plot}/${id}`;
                }
            }


            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        
                        let reshouseinfo = response.data.propertyhouses;
                        let restenantinfo = response.data.tenantinfo;

                        let resthisproperty = response.data.thisproperty;
                        setCurrentProperty(response.data.thisproperty);

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr1)

                        reshouseinfo.map((houseup) => {
                            return arr.push({value: houseup.id, label: houseup.Housename+'('+houseup.Status+')' , data: houseup});
                        });
                        setHouseinfo(arr)

                        restenantinfo.map((tenantsdata) => {
                            return arr2.push({value: tenantsdata.id, label: tenantsdata.Fname+' '+tenantsdata.Oname+'('+tenantsdata.Status+')' , data: tenantsdata});
                        });
                        setTenantinfo(arr2)

                        setHousedata(response.data.thishouse)
                        setAgreementdata(response.data.agreementinfo);
                        setPropertydata([])
                        // setWaterbillData(response.data.waterbilldata);
                        
                        let options=[];
                        if(id!==''){
                            options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
                            setPlotID(resthisproperty.id);
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
        getProperties();

        return ()=>{
            doneloading=false;
        }
    },[id,loggedoff])


    // const loadTenants =() =>{
    //     let doneloading=true;
    //     if (doneloading) {
    //         // setLoadingMonths(true)
    //     }
    //     const getPrevMonths = async (e) => { 
    //         const arr = [];
    //             arr.push({value: '', label: 'Select Month' });
    //         const arr1 = [];
    //             arr1.push({value: '', label: 'Select Tenants IN' });
                
               
    //         let url=`/v2/properties/mgr/tenants/load/${id}`;
    //         // console.log(id)
    //         if(id===''){
    //             // setLoadingMonths(false)
    //             return false;
    //         }
    //         else{
    //             url=`/v2/properties/mgr/tenants/load/${id}`;
    //         }

            
    //         await axios.get(url)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     let respropertyinfo = response.data.propertyinfo;
    //                     let restenantinfo = response.data.tenantinfo;
                        
    //                     let resthisproperty = response.data.thisproperty;

    //                     setPropertydata(response.data.tenantinfo)
    //                     setHousedata([])

    //                     respropertyinfo.map((monthsup) => {
    //                         return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
    //                     });

    //                     arr1.push({value: 'Vacated', label: 'Vacated' });
    //                     arr1.push({value: 'Assigned', label: 'Assigned' });
    //                     arr1.push({value: 'Reassigned', label: 'Reassigned' });
    //                     arr1.push({value: 'Transferred', label: 'Transferred' });
    //                     arr1.push({value: 'New', label: 'New Tenants' });
    //                     arr1.push({value: 'Other', label: 'Others' });
    //                     setPropertyinfo(arr1)

                        

    //                     let options=[];
    //                     if(id!==''){
    //                         if(id==='Vacated' || id==='New' || id==='Assigned' || id==='Reassigned' || id==='Other' || id==='Transferred'){
    //                             options={value: id, label: id , data: resthisproperty}
    //                         }
                            
    //                         else{
    //                             options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
    //                         }
                            
    //                     }
    //                     setWaterbillPropertyId(options)
    //                     // setLoadingMonths(false)
    //                 }
    //                 else if(response.data.status=== 401){
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else if(response.data.status=== 500){
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else{
    //                     // setLoadingMonths(false)
    //                 }
    //                 // setLoadingMonths(false)
    //             }
    //         })
    //         .catch(error=>{
    //             Swal("Error",""+error,"error");
    //             // setLoadingMonths(false)
    //         })
    //     };
    //     getPrevMonths();
    // }


    useEffect(()=>{
        if(id!==''){
            let thisurl=`/properties/house/${plot}/${id}`;
            navigate(thisurl)
        }
        else{
            let thisurl=`/properties/manage/${plot}`;
            navigate(thisurl)
        }
    },[id])


    useEffect(()=>{
        // if(plot==='all'){
        //     let thisurl=`/properties/manage`;
        //     navigate(thisurl)
        // }
        // else{
            if(id===''){
                let thisurl=`/properties/manage/${plot}`;
                navigate(thisurl)
            }
        // }
       
    },[plot])


    const handleShowVacateHouse = (property) => {
        setShowVacateHouse(true);
        setCurrentProperty(property)
    };

    const handleCloseVacateHouse = () => {
        setShowVacateHouse(false);
        document.title="Vacate Tenant ";
    };

    const handleClose = () => {
        setShow(false);
        document.title="Add or Upload Waterbill";
    };

    const handleShow = (waterbill) => {
        setShow(true);
        setCurrentWaterbill(waterbill)
    };

    const handleShowAddHouse = (property) => {
        setShowAddHouse(true);
        setCurrentHouse(property)
    };

    const handleCloseAddHouse = () => {
        setShowAddHouse(false);
        document.title="Manage Property";
    };
  
    function handleWaterbillMonthChange(val) {
        setLoadingMonths(true)
        let monthoptions={value: val.value, label: val.label}
        setWaterbillMonth(monthoptions) 
        setLoadingMonths(false)
    }

    const handleShowAddProperty = (property) => {
        setShowAddProperty(true);
        setCurrentProperty(property)
    };

    const handleCloseAddProperty = () => {
        setShowAddProperty(false);
        document.title="Manage Properties";
    };

    function handlePropertyChange(val) {
        setLoadingMonths(true)
        setPlotID(val.value)
        setID('')
        let options={value: val.value, label: val.label , data: val}
        setWaterbillPropertyId(options) 
        setLoadingMonths(false)
    }

    function handleHouseChange(val) {
        setLoadingMonths(true)
        setID(val.value)
        let options={value: val.value, label: val.label , data: val}
        setManageHouseId(options) 
        setLoadingMonths(false)
    }

    function handleTenantChange(val) {
        setLoadingMonths(true)
        let options={value: val.value, label: val.label , data: val}
        setTenantsId(options) 

        const tenantid=val.value;
        if(tenantid!==''){
            let thisurl=`/properties/mgr/tenants/${tenantid}`;
            navigate(thisurl)
        }
        setLoadingMonths(false)
    }

    

    const handleSearchChange =(e) =>{
        setLoadingMonths(true)
        if(propertydata.length>0){
            const results=propertydata.filter(property =>{
                if(e.target.value=== '') return propertydata
                return property.Plotname.toLowerCase().includes(e.target.value.toLowerCase()) || property.Plotcode.toLowerCase().includes(e.target.value.toLowerCase())
            })
            setSearch({
                value:e.target.value,
                result:results
            })
        }
        else if(housedata.length>0){
            const results=housedata.filter(house =>{
                if(e.target.value=== '') return housedata
                return house.Housename.toLowerCase().includes(e.target.value.toLowerCase()) || house.tenantname.toLowerCase().includes(e.target.value.toLowerCase())
            })
            setSearchhouse({
                value:e.target.value,
                result:results
            })
        }
        setLoadingMonths(false)
    }


    const handleChange= (e) => {
        const {value,checked}=e.target;
        const {upwaterbill} =uploadwaterbill;

        if(value==='all'){
            const arr = [];
            if(checked){
                waterbilldata.map((waterbilld) => {
                    if(waterbilld.waterid===null){
                        if(waterbilld.tid!=='Vacated'){
                            if(waterbilld.prevmatches==='Yes'){
                                arr.push(waterbilld.id+'?'+waterbilld.housename+'?'+waterbilld.tid+'?'+waterbilld.tenantname+'?'+waterbilld.previous+'?'+waterbilld.current+'?'+waterbilld.cost+'?'+waterbilld.units+'?'+waterbilld.total+'?'+waterbilld.waterid+'?')
                            }
                        }
                    }
                    else{
                        arr.push(waterbilld.id+'?'+waterbilld.housename+'?'+waterbilld.tid+'?'+waterbilld.tenantname+'?'+waterbilld.previous+'?'+waterbilld.current+'?'+waterbilld.cost+'?'+waterbilld.units+'?'+waterbilld.total+'?'+waterbilld.waterid+'?')
                    }
                })
                setUploadWaterbill({
                    upwaterbill:arr,
                })
            }
            else{
                setUploadWaterbill({
                    upwaterbill:[],
                })
            }
            
        }
        else{
            if(checked){
                setUploadWaterbill({
                    upwaterbill:[... upwaterbill,value],
                });
                
            }
            else{
                setUploadWaterbill({
                    upwaterbill:upwaterbill.filter((e) => e !== value),
                })
            }

        }
    }

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
                Swal("Deleting....","Please Wait");
                axios.post('/v2/delete/house/save',form)
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


    const loadTenants =() =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getProperties = async (e) => { 
            const arr = [];
                arr.push({value: '', label: 'Select House' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Property' });
            let url=`/v2/properties/house/${plot}/${id}`;
            

            if(id===''){
                setLoadingMonths(false)
                return false;
            }
            else{
                if(id==='all'){
                    url=`/v2/properties/manage/load`;
                }
                else{
                    url=`/v2/properties/house/${plot}/${id}`;
                }
            }


            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;
                        
                        let reshouseinfo = response.data.propertyhouses;

                        let resthisproperty = response.data.thisproperty;
                        setCurrentProperty(response.data.thisproperty);

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr1)

                        reshouseinfo.map((houseup) => {
                            return arr.push({value: houseup.id, label: houseup.Housename+'('+houseup.Status+')' , data: houseup});
                        });
                        setHouseinfo(arr)

                        setHousedata(response.data.thishouse)
                        setAgreementdata(response.data.agreementinfo);
                        setPropertydata([])
                        // setWaterbillData(response.data.waterbilldata);
                        
                        let options=[];
                        if(id!==''){
                            options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
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
        getProperties();
    }

  return (
    <>
    <div className="wrapper">
        {loggedoff ? 
            <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
        :
        <>
        <DashNavBar setClosed={setClosed} closed={closed} active='manage'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='manage'/>
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
                                    <li className="breadcrumb-item"><Link to={'/properties/manage'}>Properties</Link></li>
                                    <li className="breadcrumb-item"><Link to={'/properties/manage/'+plot}> { !loadingmonths && waterbillpropertyid.label!==undefined && waterbillpropertyid.label}</Link> </li>
                                    <li className="breadcrumb-item active"> House:{ !loadingmonths && housedata.Housename} (
                                        <Link to={'/properties/mgr/tenants/'+housedata.tenant}> { !loadingmonths && housedata.tenantname}</Link> ) 
                                    </li>

                                </ol>
                            </div>

                        <div className="col-12">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-none m-0 p-0" >
                                        <div className="card-header text-white elevation-2 m-0 p-0">
            
                                            <div className='row justify-content-center text-center p-1 m-0'>
                                                <div className="col-6 col-lg-4 m-0 p-1 text-sm text-dark">
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

                                                <div className="col-6 col-lg-4 m-0 p-1 text-sm text-dark">
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

                                                {/* <div className="col-12 col-lg-4 text-xs float-right m-0 p-1">
                                                    {waterbillpropertyid.label!==undefined &&
                                                        <button className='btn btn-primary border-info m-1 p-1 pl-2 pr-2'><Link to={'/properties/manage/'+plot} className='text-white'><i className='fa fa-chevron-left'></i> {waterbillpropertyid.label}</Link></button>
                                                    }
                                                    {housedata  && housedata.length>0 && <input onChange={handleSearchChange} value={searchhouse.value} className='border-info p-1 pt-0 pb-0' placeholder='Search House' />}
                                                </div> */}
                                            </div>
                                            
                                        </div>
                                        <div className="col-12 m-0 p-1 mt-1">
                                            <h4 className='text-info mx-auto text-center'>House { !loadingmonths && housedata.Housename} Details and Tenants
                                            </h4>
                                        </div>

                                        <div className="card-body text-center m-0 p-1">
                                        
                                            <div className="row m-0 p-0">
                                                

                                                <div className="row m-0 p-0" style={{"overflowX":"auto"}}>
                                                    {loadingmonths &&
                                                        <div className="col-12 col-md-6 col-lg-4 text-left m-0 p-1 mt-1 mb-2">
                                                            <HouseDetailsSpinner />
                                                        </div>
                                                    }
                                                     
                                                    
                                                    {!loadingmonths && 
                                                    <div className="col-12 col-md-6 col-lg-6 text-left m-0 p-1 mt-1 mb-2">
                                                        <div className="card border-info housedetails m-0 p-1" >
                                                            <div className="card-header text-muted text-center m-0 p-0 pt-1 pb-2">
                                                                <span style={{"float":"left"}}>
                                                                    <span className={`m-0 p-1 text-sm bg-light ${housedata.Status==='Occupied'?'text-info':'text-danger'}`}
                                                                        style={{"borderRadius":"30px","border":"2px solid white"}}> {housedata.tenantname}</span> 
                                                                </span> 
                                                                <span className='m-0 p-1 text-sm text-dark mx-auto'>{housedata.Housename}</span>
                                                                <span style={{"float":"right"}}>
                                                                    <span className={`m-0 p-1 text-sm bg-light ${housedata.Status==='Occupied'?'text-success':'text-danger'}`}
                                                                        style={{"borderRadius":"30px","border":"2px solid white"}}> {housedata.Status}</span>  
                                                                </span>
                                                                
                                                            </div>
                                                            
                                                            <div className="card-body text-center text-muted text-sm m-0 p-0 pt-1">
                                                                <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                                                    <div className="col-6 m-0 p-0">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">Rent:</label>

                                                                            <div className="col-7">
                                                                                {housedata.Rent}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-6 m-0 p-0">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">Bin:</label>

                                                                            <div className="col-7">
                                                                                {housedata.Garbage}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                                                    <div className="col-6 m-0 p-0">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">Deposit:</label>

                                                                            <div className="col-7">
                                                                                {housedata.Deposit}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-6 m-0 p-0">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">Lease:</label>

                                                                            <div className="col-7">
                                                                                {housedata.Lease}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            

                                                                <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                                                    <div className="col-6 m-0 p-0">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">Kplc D:</label>

                                                                            <div className="col-7">
                                                                                {housedata.Kplc}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-6 m-0 p-0">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">Water D:</label>

                                                                            <div className="col-7">
                                                                                {housedata.Water}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            
                                                                <div className='row m-0 mb-3 p-1 elevation-0 border-none'>
                                                                    <div className="col-6 m-0 p-0">
                                                                        <div className="form-group row m-0 p-0">
                                                                            <label className="col-5 m-0 p-0 text-md-right">Due:</label>

                                                                            <div className="col-7 m-0 p-0">
                                                                                {housedata.DueDay}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div className="col-6 m-0 p-0">
                                                                        <div className='d-flex justify-content-center m-0 p-0'>
                                                                            <button className='bg-warning m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-dark' onClick={()=>{handleShowAddHouse(housedata)}}><small><i className='fa fa-edit'></i> Edit</small></button>
                                                                            <button className='bg-danger m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{deleteHouse(housedata)}}><small><i className='fa fa-trash'> Delete</i></small></button>
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
                                                            {agreementdata  && agreementdata.map((agreement,key) => (
                                                                <div key={key}  className="col-12 col-md-6 col-lg-6 text-left m-0 p-1 mt-1 mb-2">
                                                                    {agreement.Status === 'Vacated' || agreement.Status === 'Deleted' || agreement.Month !== "0" ?
                                                                        <div className="card border-danger housedetails m-0 p-0" >
                                                                            <div className="card-header text-muted m-0 p-0 pt-1">
                                                                                <span className='m-0 p-1 text-danger bg-light'
                                                                                            style={{"borderRadius":"50%","border":"2px solid white"}}> <i className='fa fa-ban'></i></span>  
                                                                                <span >{agreement.tenantname}</span>
                                                                                <span style={{"float":"right"}}>
                                                                                    {agreement.Gender==='Male' ?
                                                                                        <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                                            style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar} alt="Tenant Profile" />
                                                                                    :
                                                                                        <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                                            style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar3} alt="Tenant Profile" />
                                                                                    }
                                                                                </span>
                                                                                
                                                                            </div>
                                                                            <div className="card-body text-center text-danger text-sm m-0 p-1">
                                                                                <p><span>Status : </span><strong>{agreement.Tenant === housedata.tenant?"Vacated":agreement.Status}</strong></p>
                                                                                <p><span>Assigned On : </span><strong>{agreement.DateAssigned}</strong></p>
                                                                                <p><span>Vacated On : </span><strong>{agreement.DateVacated}</strong></p>
                                                                            </div>
                                                                        </div>
                                                                    :
                                                                        <>
                                                                            {agreement.iscurrent === 'Yes' ?
                                                                                <div className="card border-ok housedetails m-0 p-0" >
                                                                                    <div className="card-header text-dark m-0 p-0 pt-1">
                                                                                        <span className='m-1 p-1 text-lime bg-light'
                                                                                            style={{"borderRadius":"50%","border":"2px solid white"}}> <i className='fa fa-check'> </i></span> 
                                                                                        <span>{agreement.tenantname} ({agreement.housesassigned})</span>
                                                                                        <span style={{"float":"right"}}>
                                                                                            {agreement.Gender==='Male' ?
                                                                                                <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                                                    style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar} alt="Tenant Profile" />
                                                                                            :
                                                                                                <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                                                    style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar3} alt="Tenant Profile" />
                                                                                            }
                                                                                        </span>
                                                                                        
                                                                                    </div>
                                                                                    <div className="card-body text-center text-sm m-0 p-1">
                                                                                        <p><span>Status : </span><strong className='text-success'>{agreement.Status}</strong> (<span>{agreement.DateAssigned} </span>)</p>
                                                                                        {agreement.housesoccupied && <p><span>Other Houses ({agreement.housesassigned -1}) : </span><strong>{agreement.housesoccupied}</strong></p>}
                                                                                        
                                                                                        <p>
                                                                                        {agreement.housesdata  && agreement.housesdata.map((houses,key) => (
                                                                                            // <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-danger' onClick={()=>{handleShowVacateHouse(houses)}}><small><i className='fa fa-minus-circle'></i> Vacate {houses.Housename}</small></button>
                                                                                            <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-danger'><Link to={'/properties/mgr/tenants/'+agreement.Tenant+'/vacate/'+houses.hid} className='text-danger'><i className='fa fa-minus-circle'></i> Vacate {houses.Housename}</Link></button>
                                                                                            // <TenantsHouseLinks houses={houses} key={key} no={key} handleShowAddProperty={handleShowAddProperty}/>
                                                                                        ))
                                                                                        }
                                                                                            {/* <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-danger' onClick={()=>{handleShowVacateHouse(currentproperty)}}><small><i className='fa fa-minus-circle'></i> Vacate {housedata.Housename}</small></button> */}
                                                                                            <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-info'><Link to={'/properties/mgr/tenants/'+housedata.tenant+'/addhouse/'} className='text-info'><i className='fa fa-plus-circle'></i> Add House</Link></button>
                                                                                            <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-info'><Link to={'/properties/mgr/tenants/'+housedata.tenant} className='text-info'><i className='fa fa-info-circle'></i> View {agreement.tenantfname}</Link></button>
                                                                                            <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-info'><Link to={'/properties/mgr/tenants/'+housedata.tenant+'/reassign/'} className='text-success'><i className='fa fa-exchange-alt'></i> Change House</Link></button>
                                                                                            <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-info'><Link to={'/properties/mgr/tenants/'+housedata.tenant+'/transfer/'} className='text-primary'><i className='fa fa-play'></i> Transfer IN</Link></button>
                                                                                            
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
                                                                                        <span >{agreement.tenantname}</span>
                                                                                        <span style={{"float":"right"}}>
                                                                                            {agreement.Gender==='Male' ?
                                                                                                <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                                                    style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar} alt="Tenant Profile" />
                                                                                            :
                                                                                                <img width="35px" className="brand-image img-circle m-0 p-0"
                                                                                                    style={{"borderRadius":"50%","border":"2px solid white"}} src={avatar3} alt="Tenant Profile" />
                                                                                            }
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
                                    <ManagePropertyVacateHseTenant showvacatehouse={showvacatehouse} handleCloseVacateHouse={handleCloseVacateHouse} handleShowAddHouse={handleShowAddHouse} handleCloseAddProperty={handleCloseAddProperty} currentpropertyhousesel={currentproperty} loadTenants={loadTenants}/>
                                }


                                {showaddhouse && 
                                    <AddHouse showaddhouse={showaddhouse} handleCloseAddHouse={handleCloseAddHouse} currentproperty={currentproperty} currenthouse={currenthouse}/>
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

export default ManagePropertyHouse;