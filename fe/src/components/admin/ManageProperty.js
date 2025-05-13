import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useContext, useState } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';

import axios from 'axios';
import Swal from 'sweetalert';

import { Link, useNavigate, useLocation } from 'react-router-dom';
import Select from 'react-select';
import { useParams } from 'react-router';


import AddHouse from './AddHouse';
import TableSmallSpinner from '../spinners/TableSmallSpinner';
import PropertyTable from './Tables/PropertyTable';
import ReLogin from '../home/ReLogin';
import AddHouseType from './AddHouseType';
import { LoginContext } from '../contexts/LoginContext';



function ManageProperty(props) {
    document.title="Manage Property";
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
         
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();
    const location=useLocation();


    // State for date selected by user
    const [selectedDate, setSelectedDate] = useState(new Date());
    let par=useParams()

    const [id,setID]=useState((par.id)?par.id:'')


    // console.log(id)

    const [closed,setClosed]=useState(false)
    const [isOpen, setIsOpen] = useState(false)

    const [formdata,setFormData]=useState({
        UpdateValue:'',
        error_list:[],
    });


    const [date, setDate] = useState(new Date());

    const [waterbillpropertyid,setWaterbillPropertyId]=useState([""]);
    const [waterbillmonth,setWaterbillMonth]=useState([""]);
    const [updatemonths, setUpdateMonths] = useState([""]);
    const [propertyinfo, setPropertyinfo] = useState([""]);

    
    const [detailpropertyid,setDetailPropertyId]=useState([""]);
    const [detailsinfo, setDetailinfo] = useState([""]);

   

    const [propertytypeid,setPropertyTypeId]=useState([""]);
    const [propertytypeinfo, setPropertyTypeinfo] = useState([""]);
    const [propertytypedata, setPropertyTypedata] = useState([]);
    
    
    
    const [propertydata, setPropertydata] = useState([]);
    const [housedata, setHousedata] = useState([]);
    const [waterbilldata, setWaterbillData] = useState([""]);
    
    const [currentwaterbill, setCurrentWaterbill] = useState([""]);

    const [currentproperty, setCurrentProperty] = useState([""]);
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
    
    const [totalvalid,setTotalValid]=useState(0)
    const [allchecked,setAllchecked]=useState(false);
    const [totalchecked,setTotalChecked]=useState(0);

    const [show,setShow]=useState(false);
    const [showdownloadpayments,setShowDownloadPayments]=useState(false);
    const [property,setProperty]=useState('');
    const [propertyid,setPropertyId]=useState('');

    const [showmonth,setShowMonth]=useState(false);

    const [redirect,setRedirect]=useState(false);
    const [waterbillurl,setWaterbillUrl]=useState('');

    const [refresh,setRefresh]=useState(true);
    const [loadingmonths,setLoadingMonths]=useState(true);
    const [loadingwater,setLoadingWater]=useState(true);
    const [loadingstats,setLoadingStats]=useState(true);
    

    const [loading,setLoading]=useState(false);

    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');
    
    const [selectedmonth,setSelectedMonth]=useState('');
    const [totalmonths,setTotalMonths]=useState(0);

    
    
        // useEffect( () =>{
        //     socket.on('load_credit_balance', (msg) =>{
        //         loadAppData();
        //     })
    
        // }, []);
    

    // /properties/dash/payments
    // /properties/dash/water
    // /properties/dash/water/prev
    // /properties/update/waterbill/1/2022 12
    // /properties/updateload/waterbill/1/2022 10
    // /properties/download/Reports/Waterbill/1/2022%2012
    // /properties/save/waterbill/uploadupdate


    useEffect(()=>{
        let doneloading=true;
        
        const getPrevMonths = async (e) => { 
            const arr = [];
                arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Property' });
            const arr2 = [];    
            let url=`/v2/properties/manage/load/${id}`;
            if(id===''){
                url='/v2/properties/manage/load';
            }
            else{
                // if(id==='all'){
                //     url=`/v2/properties/manage/load`;
                // }
                // else{
                    return false;
                // }
                
            }
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;

                        setCurrentProperty(response.data.thisproperty)
                        let resthisproperty = response.data.thisproperty;

                        setPropertydata(response.data.propertyinfo)
                        setHousedata([])

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr1)
                        
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

        return ()=>{
            doneloading=false;
            
        }
    },[id,refresh,loggedoff])


    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getProperties = async (e) => { 
            const arr1 = [];
                arr1.push({value: '', label: 'Select Property' });
            let url=`/v2/properties/manage/load/${id}`;
            

            if(id===''){
                setLoadingMonths(false)
                return false;
            }
            else{
                // if(id==='all'){
                //     url=`/v2/properties/manage/load`;
                // }
                // else{
                    url=`/v2/properties/manage/load/${id}`;
                // }
            }

            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let respropertyinfo = response.data.propertyinfo;

                        setCurrentProperty(response.data.thisproperty)
                        let resthisproperty = response.data.thisproperty;

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr1)

                        setHousedata(response.data.houseinfo)
                        setPropertydata([])
                        
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
                    
                    setUploadWaterbill({
                        upwaterbill:[],
                    })
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
    },[id,refresh,loggedoff])

    

    const loadHouses = () =>{
            let doneloading=true;
            // if (doneloading) {
            //     setLoadingMonths(true)
            // }
            const getProperties = async (e) => { 
                const arr1 = [];
                    arr1.push({value: '', label: 'Select Property' });
                let url=`/v2/properties/manage/load/${id}`;
                
    
                if(id===''){
                    setLoadingMonths(false)
                    return false;
                }
                else{
                    // if(id==='all'){
                    //     url=`/v2/properties/manage/load`;
                    // }
                    // else{
                        url=`/v2/properties/manage/load/${id}`;
                    // }
                }
    
                await axios.get(url)
                .then(response=>{
                    if (doneloading) {
                        if(response.data.status=== 200){
                            let respropertyinfo = response.data.propertyinfo;
    
                            setCurrentProperty(response.data.thisproperty)
                            let resthisproperty = response.data.thisproperty;
    
                            respropertyinfo.map((monthsup) => {
                                return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                            });
                            setPropertyinfo(arr1)
    
                            setHousedata(response.data.houseinfo)
                            setPropertydata([])
                            
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

                        setUploadWaterbill({
                            upwaterbill:[],
                        })
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
                        setPropertyTypedata(response.data.propertyhousetypedata);
                        // let resthisproperty = response.data.thisproperty;
                        respropertyhousetypedata.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.typename , data: monthsup});
                        });
                        setPropertyTypeinfo(arr1)
                        
                        let options=[];
                        if(currenthouse!==''){
                            respropertyhousetypedata.map((propertyt) => {
                                if(currenthouse.housetype===propertyt.id){
                                   options={value: propertyt.id, label: propertyt.typename , data: propertyt}
                                }
                            })
                            
                        }
                        setPropertyTypeId(options)
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


    const loadPropertyHouseTypes = () => {
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
                        setPropertyTypedata(response.data.propertyhousetypedata);
                        // let resthisproperty = response.data.thisproperty;
                        respropertyhousetypedata.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.typename , data: monthsup});
                        });
                        setPropertyTypeinfo(arr1)
                        
                        let options=[];
                        if(currenthouse!==''){
                            respropertyhousetypedata.map((propertyt) => {
                                if(currenthouse.housetype===propertyt.id){
                                   options={value: propertyt.id, label: propertyt.typename , data: propertyt}
                                }
                            })
                            
                        }
                        setPropertyTypeId(options)
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

    useEffect(()=>{
        // if(id==='all'){
        //     let thisurl=`/properties/manage`;
        //     navigate(thisurl)
        // }
        // else{
            if(id!==''){
                let thisurl=`/properties/manage/${id}`;
                navigate(thisurl)
            }
            else{
                navigate(location.pathname)
            }
        // }
       
    },[id,location.pathname])

    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingWater(true)
        }
        const arr12 = [];
        arr12.push({value: '', label: 'Select What to Update' });
        arr12.push({value: 'Rent', label: 'Rent' });
        arr12.push({value: 'Deposit', label: 'House Deposit' });
        arr12.push({value: 'Kplc', label: 'Kplc Deposit' });
        arr12.push({value: 'Water', label: 'Water Deposit' });
        arr12.push({value: 'Lease', label: 'Lease' });
        arr12.push({value: 'Garbage', label: 'Garbage' });
        arr12.push({value: 'DueDay', label: 'DueDay' });
        arr12.push({value: 'PrevName', label: 'Previous House Name' });
        arr12.push({value: 'housetype', label: 'House type' });
        arr12.push({value: '', label: '' });

        setDetailinfo(arr12)
        setLoadingWater(false)
    },[])

    useEffect(()=>{
        
        let previewuncheckedtotal=0;
        if(searchhouse.value===''){
            housedata.map((waterbilld) => {
                previewuncheckedtotal++;
            })
        }
        else{
            searchhouse.result.map((waterbilld) => {
                previewuncheckedtotal++;
            })
        }
            
        
        setTotalValid(previewuncheckedtotal)
        
    },[housedata,searchhouse.result])

    
    useEffect(()=>{
        if(uploadwaterbill.upwaterbill.length < totalvalid){
            setAllchecked(false)
            if(uploadwaterbill.upwaterbill.length===0){
                setAllchecked(false)
            }
        }
        
        else if(uploadwaterbill.upwaterbill.length===totalvalid){
            setAllchecked(true)
            if(uploadwaterbill.upwaterbill.length===0){
                setAllchecked(false)
            }
        }
        
        
    },[uploadwaterbill.upwaterbill.length])

    
    
    const handleShowAddProperty = () => {
        setShowAddHouse(true);
        setCurrentHouse('')
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

    function handlePropertyChange(val) {
        setLoadingMonths(true)
        setID(val.value);
        let options={value: val.value, label: val.label , data: val}
        setWaterbillPropertyId(options) 
        setLoadingMonths(false)
        setRefresh(!refresh);
    }

    function handlePropertyTypeChange(val) {
        let options={value: val.value, label: val.label , data: val}
        setPropertyTypeId(options) 
    }

    function handleDetailChange(val) {
        let options={value: val.value, label: val.label , data: val}
        setDetailPropertyId(options) 
    }

    const handleSearchChange =(e) =>{
        setLoadingMonths(true)
        // if(propertydata.length>0){
        //     const results=propertydata.filter(property =>{
        //         if(e.target.value=== '') return propertydata
        //         return property.Plotname.toLowerCase().includes(e.target.value.toLowerCase()) || property.Plotcode.toLowerCase().includes(e.target.value.toLowerCase())
        //     })
        //     setSearch({
        //         value:e.target.value,
        //         result:results
        //     })
        // }
        // else if(housedata.length>0){
            const results=housedata.filter(house =>{
                if(e.target.value=== '') return housedata
                return house.Housename.toLowerCase().includes(e.target.value.toLowerCase()) || house.tenantname.toLowerCase().includes(e.target.value.toLowerCase()) || (house.Rent.toString().toLowerCase()===e.target.value.toLowerCase()) || (house.Water.toString().toLowerCase()===e.target.value.toLowerCase()) || house.Status.toLowerCase().includes(e.target.value.toLowerCase())
            })
            setSearchhouse({
                value:e.target.value,
                result:results
            })
        // }
        setLoadingMonths(false)
    }


    const handleChange= (e) => {
        const {value,checked}=e.target;
        const {upwaterbill} =uploadwaterbill;
        if(searchhouse.value===''){
            if(value==='all'){
                const arr = [];
                if(checked){
                    housedata.map((waterbilld) => {
                        arr.push(waterbilld.id+'?'+waterbilld.Housename+'?'+waterbilld.tenant+'?'+waterbilld.tenantname+'?') 
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
        else{
            if(value==='all'){
                const arr = [];
                if(checked){
                    searchhouse.result.map((waterbilld) => {
                        arr.push(waterbilld.id+'?'+waterbilld.Housename+'?'+waterbilld.tenant+'?'+waterbilld.tenantname+'?') 
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
    }


    const handleInputChange=(e)=>{
        e.persist();
        setFormData({...formdata,[e.target.name]:e.target.value})
    }

    

    // savepid
    // savemonth
    // waterbillvaluesupdate


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
                        loadHouses();
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

    const submitUpdateDetail= (e)=>{
        e.preventDefault();
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")
        
        const form={
            UpdateValue:(detailpropertyid.value==='housetype')?propertytypeid.value:formdata.UpdateValue,
            detailpropertyid:detailpropertyid.value,
            valuestoupdate:uploadwaterbill.upwaterbill,
        }

        let title="Sure to Update House " ;
        let text="Total Selected Houses ( "+uploadwaterbill.upwaterbill.length+" ) ?";
        
        let url='/v2/save/updatehousedetail/save';
        let valued=(propertytypeid.value===undefined)?'':propertytypeid.value;
        if(detailpropertyid.value==='' || detailpropertyid.value===undefined){
            Swal("What to Update","Please Speficy what to update.","error");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else if(detailpropertyid.value==='housetype' && (valued==='')){
            Swal("Check Value ","Please Specify House type to Update.","error");
            setLoadingRes("")
            setLoadingResOk("")
            setLoading(false);
        }
        else if(detailpropertyid.value!=='housetype' && ((formdata.UpdateValue.trim())==='')){
            Swal("Check Value ","Please Specify Value to Update.","error");
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
                    Swal("Updating","Please Wait.");
                    axios.post(url,form)
                    .then(response=>{
                        if(response.data.status=== 200){
                            Swal("Success",response.data.message);
                            setFormData({...formdata,error_list:[]});
                            loadHouses();
                        }
                        else if(response.data.status=== 401){
                            Swal("Error",response.data.message,"error");
                            setLoadingRes(response.data.message)
                            setFormData({...formdata,error_list:[]});
                        }
                        else if(response.data.status=== 500){
                            Swal("Error",response.data.message,"error");
                            setFormData({...formdata,error_list:[]});
                        }
                        else{
                            Swal("Error","Could not Update","error");
                            setFormData({...formdata,error_list:response.data.errors});
                        }
                        setUploadWaterbill({
                            upwaterbill:[],
                        })
                        setLoading(false);
    
                    })
                    .catch((error)=>{
                        Swal("Error",""+error,"error");
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

    const handleClose = () => {
        setShow(false);
        document.title="Add or Update House Type";
    };

    const handleShow = () => {
        setShow(true);
    };


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
                                <li className="breadcrumb-item active">{ !loadingmonths && waterbillpropertyid.label!==undefined && waterbillpropertyid.label} </li>
                                {/* <li className="breadcrumb-item active">In House : { !loadingmonths && tenantdata.Housenames && tenantdata.Housenames} </li> */}
                            </ol>
                        </div>


                        <div className="col-12">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info m-0 p-0" >
                                        <div className="card-header text-white elevation-2 m-0 p-0">
            
                                            <div className='row justify-content-center text-center p-1 m-0'>
                                                <div className="col-12 col-lg-6 m-0 p-1 text-sm text-dark">
                                                    {loadingmonths &&
                                                        <Spinner  variant="blue" size="md" role="status"></Spinner>
                                                    }
                                                    {!loadingmonths &&
                                                        <Select
                                                            className='text-sm'
                                                            placeholder= "Select Property"
                                                            value={waterbillpropertyid}
                                                            name="waterbill-property"
                                                            options={propertyinfo}
                                                            onChange={handlePropertyChange}
                                                        />
                                                    }
                                                </div>

                                                <div className="col-12 col-lg-6 text-xs float-right m-0 p-0">
                                                    {/* {waterbillpropertyid.label!==undefined &&
                                                        <button className='btn btn-primary border-info m-1 p-1 pl-2 pr-2'><Link to='/properties/manage' className='text-white'><i className='fa fa-chevron'></i> <small> View Properties</small></Link></button>
                                                    }  */}
                                                    <button className='btn btn-success border-info m-1 p-1 pl-2 pr-2' onClick={()=>{handleShowAddProperty()}}><small><i className='fa fa-plus-circle'></i> New House ({housedata.length})</small></button>
                                                    
                                                    {housedata  && housedata.length>0 && <input onChange={handleSearchChange} value={searchhouse.value} className='border-info p-2 pt-0 pb-0' placeholder='Search House' />}
                                                    <span className="text-md float-right m-0 p-1 text-bold text-purple">
                                                        {uploadwaterbill.upwaterbill && uploadwaterbill.upwaterbill.length} /
                                                        {totalvalid}
                                                    </span>
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
                                                    <>
                                                        <div className={`${uploadwaterbill.upwaterbill.length > 1?'propertyinfo':'tableinfo'} col-12 m-0 p-0 `} style={{"overflowX":"auto"}}>
                                                        <table border="1" className="table table-hover table-bordered text-xs" id="example1">
                                                            
                                                            {housedata  && housedata.length>0 &&
                                                                <thead  >
                                                                    
                                                                <tr  style={{"color":"white","backgroundColor":"#77B5ED"}}>
                                                                    <th className='elevation-2 m-0 p-1'>
                                                                        <label className="selwaterbill d-block m-0 p-0" style={{"fontSize":"12px"}}>
                                                                            <input type="checkbox" 
                                                                                className="selectedwaterbilltenants" 
                                                                                name="waterbillvalues[]"
                                                                                value="all"
                                                                                checked={allchecked}
                                                                                onChange={handleChange} />
                                                                                <span className="m-0 p-1 text-bold text-white">
                                                                                    {uploadwaterbill.upwaterbill && uploadwaterbill.upwaterbill.length} /
                                                                                    {totalvalid}
                                                                                </span>
                                                                        </label>
                                                                    </th>
                                                                    <th className='elevation-2 m-0 p-1'>Housename</th>
                                                                    <th className='elevation-2 m-0 p-1'>Tenant</th>
                                                                    <th className='elevation-2 m-0 p-1'>Type</th>
                                                                    <th className='elevation-2 m-0 p-1'>Rent</th>
                                                                    <th className='elevation-2 m-0 p-1'>KPLC</th>
                                                                    <th className='elevation-2 m-0 p-1'>Water</th>
                                                                    <th className='elevation-2 m-0 p-1'>Lease</th>
                                                                    <th className='elevation-2 m-0 p-1'>Bin</th>
                                                                    <th className='elevation-2 m-0 p-1'>Due</th>
                                                                    <th className='elevation-2 m-0 p-1'>Status</th>
                                                                    <th className='elevation-2 m-0 p-1'>Action</th>
                                                                </tr></thead>
                                                            }
                                                            <tbody>
                                                                
                                                                {housedata  && housedata.length>0 &&
                                                                    <>
                                                                        {(searchhouse.value==='')?
                                                                            <>
                                                                                {housedata  && housedata.map((property,key) => (
                                                                                    <PropertyTable property={property} key={key} no={key} handleShowAddHouse={handleShowAddHouse} deleteHouse={deleteHouse} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} />
                                                                                ))
                                                                                }
                                                                            </>
                                                                        :
                                                                            <>
                                                                                {searchhouse.result  && searchhouse.result.map((property,key) => (
                                                                                    <PropertyTable property={property} key={key} no={key} handleShowAddHouse={handleShowAddHouse} deleteHouse={deleteHouse} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} />    
                                                                                ))
                                                                                }
                                                                            </>
                                                                        
                                                                        }
                                                                    </>
                                                                }
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </>
                                                }
                                            </div>

                                        </div>
                                        {uploadwaterbill.upwaterbill.length > 1 &&
                                            <div className="card-header text-white elevation-2 m-0 p-0">
                                                <form onSubmit={submitUpdateDetail}>
                                                    <div className='row justify-content-center text-center p-1 m-0'>
                                                        <div className="col-12 m-0 p-0">
                                                            <div className="border-info card p-1 elevation-2">
                                                                <div className="form-group row m-0 p-1 border-light mb-0 ">
                                                                    <label htmlFor="manage-what-to-update-property" className="col-lg-2 col-6 col-form-label text-md-right">Update What?</label>
                                                                    <div className="col-lg-3 col-6 m-0 p-0" >
                                                                        {loadingwater &&
                                                                            <Spinner  variant="blue" size="md" role="status"></Spinner>
                                                                        }
                                                                        {!loadingwater && 
                                                                            <Select
                                                                                className='text-sm p-1'
                                                                                placeholder= "Select What to Update"
                                                                                value={detailpropertyid}
                                                                                name="manage-what-to-update-property"
                                                                                options={detailsinfo}
                                                                                onChange={handleDetailChange}
                                                                            />
                                                                        }
                                                                    </div>
                                                                    <label htmlFor="UpdateValue" className="col-lg-2 col-6 col-form-label text-md-right">Value</label>

                                                                    <div className="col-lg-3 d-flex col-6 m-0 p-0">
                                                                        {detailpropertyid.value==='housetype' ?
                                                                        <>
                                                                            <Select
                                                                                className='text-sm m-0 p-1'
                                                                                placeholder= "Select Property"
                                                                                value={propertytypeid}
                                                                                name="manage-property"
                                                                                options={propertytypeinfo}
                                                                                onChange={handlePropertyTypeChange}
                                                                            />
                                                                            <button type="button" className="btn btn-primary m-1 p-1" style={{"float":"right"}} onClick={()=>{handleShow()}}>
                                                                                <i className='fa fa-plus-circle'></i>
                                                                            </button>
                                                                        </>
                                                                        :
                                                                            <input id="UpdateValue" type="text" className="form-control p-1" name="UpdateValue" value={formdata.UpdateValue} onChange={handleInputChange} placeholder="Value to Update" required autoComplete="UpdateValue" autoFocus/>
                                                                        }
                                                                        {formdata.error_list && formdata.error_list.UpdateValue && 
                                                                            <span className="help-block text-danger">
                                                                                <strong>{formdata.error_list.UpdateValue}</strong>
                                                                            </span>
                                                                        }
                                                                        
                                                                    </div>

                                                                    <div className="col-lg-2 col-12 m-0 p-0" style={{"float":"right"}}>
                                                                        <button type="submit" className="btn btn-success m-1 p-1">
                                                                            <i className='fa fa-check-circle'></i> Update Value ({uploadwaterbill.upwaterbill.length})
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        }

                                        

                                    </div>
                                </div>

                                {showaddhouse && 
                                    <AddHouse showaddhouse={showaddhouse} handleCloseAddHouse={handleCloseAddHouse} currentproperty={currentproperty} currenthouse={currenthouse} loadHouses={loadHouses}/>
                                }

                                {show && 
                                    <AddHouseType show={show} handleClose={handleClose} currentproperty={currentproperty} propertydata={propertytypedata} loadPropertyHouseTypes={loadPropertyHouseTypes} loadHouses={loadHouses} />
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

export default ManageProperty;