import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useMemo, useState, useCallback } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';

import axios from 'axios';

import { Link, useNavigate } from 'react-router-dom';
import Select from 'react-select';
import { useParams } from 'react-router';
import { useDropzone } from 'react-dropzone';

import Swal from 'sweetalert';
import AddWaterbill from './AddWaterbill';
import TableSmallSpinner from '../spinners/TableSmallSpinner';
import VacatedWaterTable from './Tables/VacatedWaterTable';
import WaterbillTable from './Tables/WaterbillTable';
import WaterbillPreviewNoMatchTable from './Tables/WaterbillPreviewNoMatchTable';
import WaterbillPreviewMatchTable from './Tables/WaterbillPreviewMatchTable';
import WaterbillPreviewSavedTable from './Tables/WaterbillPreviewSavedTable';
import ReLogin from '../home/ReLogin';
import RentGarbageTable from './Tables/RentGarbageTable';
import AddMonthlyBill from './AddMonthlyBill';
import AddRentGarbage from './AddRentGarbage';

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

function UpdateRentGarbage(props) {
    document.title="Update Rent & Garbage Bills";
    
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();


    // State for date selected by user
    const [selectedDate, setSelectedDate] = useState(new Date());
    let par=useParams()

    const [id,setID]=useState((par.id)?par.id:'')
    const [month,setMonth]=useState((par.month)?par.month:`${selectedDate.getFullYear()} ${selectedDate.getMonth()+1}`)

    // console.log(id,month)

    const [closed,setClosed]=useState(false)
    const [isOpen, setIsOpen] = useState(false)

    // Array to store month string values
    const allMonthValues = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
    ];


    // State for date selected by user
    // const [selectedDate, setSelectedDate] = useState(new Date());

    // State for text above calander
    const [calendarText, setCalendarText] = useState(selectedDate.toDateString());

    // Function to update selected date and calander text
    const handleDateChange = (value) => {
        setSelectedDate(value);
        setCalendarText(value.toDateString());
    };
    

    // Function to handle selected Year change
    const handleYearChange = (value) => {
        const yearValue = value.getFullYear();
        setCalendarText(`${yearValue} Year`);
    };

    // Function to handle selected Month change
    const handleMonthChange = (value) => {
        const monthValue = allMonthValues[value.getMonth()];
        const yearValue = value.getFullYear();
        setCalendarText(`${value.getMonth()} , ${yearValue} `);
    };


    const [date, setDate] = useState(new Date());

    const [waterbillpropertyid,setWaterbillPropertyId]=useState([""]);
    const [waterbillmonth,setWaterbillMonth]=useState([""]);
    const [updatemonths, setUpdateMonths] = useState([""]);
    const [propertyinfo, setPropertyinfo] = useState([""]);
    const [waterbilldata, setWaterbillData] = useState([]);
    
    const [currentwaterbill, setCurrentWaterbill] = useState([""]);
    
    const [preview, setPreview] = useState(false);

    

    const [search,setSearch]=useState({
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

    // const [loading,setLoading]=useState(true);
    const [loadingmonths,setLoadingMonths]=useState(true);
    const [loadingwater,setLoadingWater]=useState(true);
    const [loadingstats,setLoadingStats]=useState(true);
    

    const [loading,setLoading]=useState(false);

    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');
    
    const [selectedmonth,setSelectedMonth]=useState('');
    const [totalmonths,setTotalMonths]=useState(0);

    

    const {
        getRootProps,
        getInputProps,
        isDragActive,
        isDragAccept,
        isDragReject,
        acceptedFiles
    }= useDropzone({ accept:'.xls,.xlsx'});

    const style=useMemo(
        () => ({
            ...baseStyle,
            ...(isDragActive ? activeStyle:{}),
            ...(isDragAccept ? acceptStyle:{}),
            ...(isDragReject ? rejectStyle:{}),
        }),
        [isDragActive, isDragReject, isDragAccept]
    );
    
    

    // /properties/dash/payments
    // /properties/dash/water
    // /properties/dash/water/prev
    // /properties/update/waterbill/1/2022 12
    // /properties/updateload/waterbill/1/2022 10
    // /properties/download/Reports/Waterbill/1/2022%2012
    // /properties/save/waterbill/uploadupdate


    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getPrevMonths = async (e) => { 
            const arr = [];
                // arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Property' });
            const arr2 = [];    
            let url=`/api/update/rent/load/${id}/${month}`;
            if(id===''){
                url='/api/update/waterbill/load';
            }
            else{
                setLoadingMonths(false)
                return false;
                // url=`/api/update/rent/load/${id}/${month}`;
            }
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let resmonths = response.data.previousmonths;
                        let respropertyinfo = response.data.propertyinfo;
                        
                        let resthisproperty = response.data.thisproperty;
                        let resselectedmonth= response.data.selectedmonth;
                        let resselectedmonthname= response.data.selectedmonthname;

                        
                        resmonths.map((months) => {
                            return arr.push({value: months.month, label: months.monthname , data: months});
                        });
                        setUpdateMonths(arr)
                        let monthoptions=[];
                        if(id!==''){
                            monthoptions={value: resselectedmonth, label: resselectedmonthname}
                        }
                        
                        setWaterbillMonth(monthoptions)


                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr1)
                        
                        let options=[];
                        if(id!==''){
                            options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
                        }
                        
                        setTotalValid(response.data.totals)
                        setWaterbillPropertyId(options)
                        setLoadingMonths(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error1",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error2",response.data.message,"error");
                    }
                    else{
                        setLoadingMonths(false)
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
                        Swal("Error2",""+error,"error");
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
        }
    },[loggedoff])



    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingWater(true)
            // setLoadingMonths(true)
        }
        const getWaterbill = async (e) => { 
            const arr = [];
                // arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Property' });
            let url=`/api/update/rent/load/${id}/${month}`;
            if(id===''){
                setLoadingWater(false)
                // setLoadingMonths(false)
                return false;
            }
            else{
                url=`/api/update/rent/load/${id}/${month}`;
            }
            await axios.get(url)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        let resthisproperty = response.data.thisproperty;
                        let resselectedmonth= response.data.selectedmonth;
                        let resselectedmonthname= response.data.selectedmonthname;

                        let resmonths = response.data.previousmonths;
                        let respropertyinfo = response.data.propertyinfo;

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr1)

                        resmonths.map((months) => {
                            return arr.push({value: months.month, label: months.monthname , data: months});
                        });
                        setUpdateMonths(arr)

                        setTotalValid(response.data.totals)
                        setWaterbillData(response.data.waterbilldata);
                        setPreview(response.data.preview);
                        
                        let monthoptions=[];
                        if(id!==''){
                            monthoptions={value: resselectedmonth, label: resselectedmonthname}
                        }
                        
                        setWaterbillMonth(monthoptions)

                        let options=[];
                        if(id!==''){
                            options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
                        }
                        
                        setWaterbillPropertyId(options)
                        setLoadingWater(false)
                        // setLoadingMonths(false)
                       
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error7",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error8",response.data.message,"error");
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
                    // setLoadingMonths(false)
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
                        // setLoadingMonths(false)
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
        if(!acceptedFiles[0]){
            getWaterbill();
        }
        

        return ()=>{
            doneloading=false;
        }
    },[id,month,loggedoff])


    useEffect(()=>{
        if(id!==''){
            acceptedFiles[0]='';
            let thisurl=`/properties/update/rentandgarbage/${id}/${month}`;
            navigate(thisurl)
        }
        
        setUploadWaterbill({
            upwaterbill:[],
        });
    },[id,month])

    
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

    

    const files=acceptedFiles.map(file =>(
        <span key={file.path}>
            {file.path}
        </span>
    ));

    useEffect(()=>{
        console.log(acceptedFiles)
    },[acceptedFiles])
    

    const loadRentGarbage =(e) =>{
        
            let doneloading=true;
            if (doneloading) {
                // setLoadingWater(true)
                // setLoadingMonths(true)
            }
            const getWaterbill = async (e) => { 
                const arr = [];
                    // arr.push({value: '', label: 'Select Month' });
                const arr1 = [];
                    arr1.push({value: '', label: 'Select Property' });
                let url=`/api/update/rent/load/${id}/${month}`;
                if(id===''){
                    setLoadingWater(false)
                    // setLoadingMonths(false)
                    return false;
                }
                else{
                    url=`/api/update/rent/load/${id}/${month}`;
                }
                await axios.get(url)
                .then(response=>{
                    if (doneloading) {
                        if(response.data.status=== 200){
                            let resthisproperty = response.data.thisproperty;
                            let resselectedmonth= response.data.selectedmonth;
                            let resselectedmonthname= response.data.selectedmonthname;
    
                            let resmonths = response.data.previousmonths;
                            let respropertyinfo = response.data.propertyinfo;
    
                            respropertyinfo.map((monthsup) => {
                                return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                            });
                            setPropertyinfo(arr1)
    
                            resmonths.map((months) => {
                                return arr.push({value: months.month, label: months.monthname , data: months});
                            });
                            setUpdateMonths(arr)
    
                            setTotalValid(response.data.totals)
                            setWaterbillData(response.data.waterbilldata);
                            setPreview(response.data.preview);
                            
                            let monthoptions=[];
                            if(id!==''){
                                monthoptions={value: resselectedmonth, label: resselectedmonthname}
                            }
                            
                            setWaterbillMonth(monthoptions)
    
                            let options=[];
                            if(id!==''){
                                options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
                            }
                            
                            setWaterbillPropertyId(options)
                            setLoadingWater(false)
                            // setLoadingMonths(false)
                           
                        }
                        else if(response.data.status=== 401){
                            setLoggedOff(true);    
                            Swal("Error7",response.data.message,"error");
                        }
                        else if(response.data.status=== 500){
                            Swal("Error8",response.data.message,"error");
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
                        // setLoadingMonths(false)
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
                            // setLoadingMonths(false)
                            Swal("Error",""+error,"error");
                        }
                    }
                })
            };
                getWaterbill();
    }

    const previewUpload= (e)=>{
        e.preventDefault();
        setLoadingMonths(true)
        const form={
            files:acceptedFiles[0],
            pid:waterbillpropertyid.value,
            month:waterbillmonth.value,
        }
            axios.post('/api/update/waterbill/preview',form,{
                headers:{
                    'content-type':'multipart/form-data'
                }
            })
            .then(response=>{
                if(response.data.status=== 200){
                //    console.log(response.data.files)
                   setWaterbillData(response.data.waterbilldata);
                   setPreview(response.data.preview);
                    Swal("Success",response.data.message);
                } 
                else if(response.data.status=== 401){
                    setLoggedOff(true);    
                    Swal("Error",response.data.message,"error");
                }
                
                else if(response.data.status=== 500){
                    Swal("Error",response.data.message,"error");
                }
                setLoadingMonths(false);
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
    }


    
    const closePreviewUpload= (e)=>{
        e.preventDefault();
        setLoadingMonths(true)
        axios.get(`/api/update/rent/load/${id}/${month}`)
        .then(response=>{
            if(response.data.status=== 200){
                setWaterbillData(response.data.waterbilldata);
                setPreview(response.data.preview);
                acceptedFiles[0]='';
            } 
            else if(response.data.status=== 401){
                setLoggedOff(true);    
                Swal("Error",response.data.message,"error");
            }
            
            else if(response.data.status=== 500){
                Swal("Error",response.data.message,"error");
            }
            setLoadingMonths(false);
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

    }

    

    const handleClose = () => {
        setShow(false);
        document.title="Add or Upload Waterbill";
    };

    const handleShow = (waterbill) => {
        setShow(true);
        setCurrentWaterbill(waterbill)
    };

  
    function handleWaterbillMonthChange(val) {
    // setWaterbillPropertyId(val.value)
        setLoadingMonths(true)
        setMonth(val.value)
        let monthoptions={value: val.value, label: val.label}
        setWaterbillMonth(monthoptions) 
        setLoadingMonths(false)
    }

    function handlePropertyChange(val) {
        setLoadingMonths(true)
        setID(val.value)
        let options={value: val.value, label: val.label , data: val}
        setWaterbillPropertyId(options) 
        setLoadingMonths(false)
    }

    const handleSearchChange =(e) =>{
    setLoadingMonths(true)
    const results=waterbilldata.filter(waterbill =>{
        if(e.target.value=== '') return waterbilldata
        return waterbill.housename.toLowerCase().includes(e.target.value.toLowerCase()) || waterbill.tenantname.toLowerCase().includes(e.target.value.toLowerCase())
    })
    setSearch({
        value:e.target.value,
        result:results
    })
    setLoadingMonths(false)
    }


    const handleChange= (e) => {
        const {value,checked}=e.target;
        const {upwaterbill} =uploadwaterbill;

        if(value==='all'){
            const arr = [];
            if(checked){
                waterbilldata.map((waterbilld) => {
                    arr.push(waterbilld.id+'?'+waterbilld.housename+'?'+waterbilld.tid+'?'+waterbilld.tenantname+'?'+waterbilld.pid+'?'+waterbilld.paymentid+'?')
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

//     savepid
// savemonth
// waterbillvaluesupdate
    const generateAllRentGarbage= (e)=>{
        e.preventDefault();
        const form={
            savepid:waterbillpropertyid.value,
            savemonth:waterbillmonth.value,
        }

        let title='Are you sure to Generate for All ';
        let text=waterbillpropertyid.label!==undefined && "\n "+
        waterbillpropertyid.label+" "+waterbillmonth.label+
        "\nThis action will Bill Rent & Garbage for all houses according to each house data.";
        Swal({
            title:title+' '+waterbillmonth.label+' Rent & Garbage ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Generating....","Please Wait");
                axios.post('/api/generate/rentgarbage/all',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        loadRentGarbage();
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
                    setUploadWaterbill({
                        upwaterbill:[],
                    })
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
    const generateSelectedRentGarbage= (e)=>{
        e.preventDefault();

        // if(uploadwaterbill.upwaterbill.length <0 ){
        //     Swal("Select Houses","Please Select atleast 2 house to Generate","error");
        //     return;
        // }
        const form={
            savepid:waterbillpropertyid.value,
            savemonth:waterbillmonth.value,
            waterbillvaluesupdate:uploadwaterbill.upwaterbill,
        }

        let title='Are you sure to Generate ';
        let text=waterbillpropertyid.label!==undefined && "Total Selected Houses ( "+uploadwaterbill.upwaterbill.length+" ) \n "+
        waterbillpropertyid.label+" "+waterbillmonth.label+
        "\nThis action will Bill Rent & Garbage for all Selected houses according to each house data.";
        Swal({
            title:title+' '+waterbillmonth.label+' Rent & Garbage ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Generating....","Please Wait");
                axios.post('/api/generate/rentgarbage/selected',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        loadRentGarbage();
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
                    setUploadWaterbill({
                        upwaterbill:[],
                    })
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

  return (
    <>
    <div className="wrapper">
        {loggedoff ? 
            <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
        :
        <>
            <DashNavBar setClosed={setClosed} closed={closed} active='rent'/>
            <DashSideNavBar setClosed={setClosed} closed={closed} active='rent'/>
            {/* className={`nav-link ${active==='home'?'active':''}`} */}
            
            <main className="py-1">
                <div className={`content-wrapper ${closed?'closed':''}`}>
                        <section className="content">
                        <div className="container">
                            <div className="row justify-content-center m-0 p-0">
                                <div className="col-12 m-0 p-0 ">
                                    <ol className="breadcrumb float-sm-right text-xs m-0 p-1">
                                        <li className="breadcrumb-item"><Link to={'/dashboard'}>Dashboard</Link></li>
                                        <li className="breadcrumb-item">{ !loadingmonths && waterbillpropertyid.label } </li>
                                        <li className="breadcrumb-item">{ !loadingmonths && waterbillmonth.label } </li>
                                        <li className="breadcrumb-item active"> Monthly Rent And Garbage </li>
                                    </ol>
                                </div>

                            <div className="col-lg-3 ">
                                <div className="row m-0 p-0">

                                    <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                        <div className="card border-info elevation-2" >
                                            <div className="card-body text-center p-0 m-1">
                                                <div className="row m-0 p-0">
                                                    <div className="col-md-12 m-0 mt-1 p-0">
                                                        {(loadingmonths || loadingwater) &&
                                                            <Spinner  variant="info" size="md" role="status"></Spinner>
                                                        }
                                                        {!loadingmonths && !loadingwater &&
                                                            <Select
                                                                placeholder= "Select Property"
                                                                value={waterbillpropertyid}
                                                                name="waterbill-property"
                                                                options={propertyinfo}
                                                                onChange={handlePropertyChange}
                                                            />
                                                        }
                                                    </div>

                                                    <div className="col-md-12 m-0 mt-1 p-0">
                                                        {(loadingmonths || loadingwater) &&
                                                            <Spinner  variant="info" size="md" role="status"></Spinner>
                                                        }
                                                        {!loadingmonths && !loadingwater &&
                                                            <Select
                                                                placeholder= "Select Month"
                                                                value={waterbillmonth}
                                                                name="waterbill-month"
                                                                options={updatemonths}
                                                                onChange={handleWaterbillMonthChange}
                                                            />
                                                        }
                                                    </div>
                                                    {(loadingmonths || loadingwater) &&
                                                        <Spinner  variant="info" size="md" role="status"></Spinner>
                                                    }
                                                    {!loadingmonths && !loadingwater &&
                                                        <div className="col-md-12 m-0 mt-1 p-0">
                                                            {waterbillmonth.label  && waterbillpropertyid.label!==undefined && waterbillpropertyid.label &&
                                                                <div className="text-xs mx-auto m-1 p-1">
                                                                    <button className='btn btn-primary border-success m-1 p-1' onClick={generateAllRentGarbage}>
                                                                        Generate for ALL : ({waterbillmonth.label})
                                                                    </button>
                                                                </div>
                                                            }
                                                        </div>
                                                    }

                                                    <div className="col-md-12 m-0 mt-1 p-0">
                                                        {uploadwaterbill.upwaterbill.length > 0 &&
                                                            <>
                                                                {!loading && 
                                                                    <div className="text-xs mx-auto m-1 p-1">
                                                                        <button className='btn btn-success border-success m-1 p-1' onClick={generateSelectedRentGarbage}>
                                                                            Generate for <span className='text-md bold'>{uploadwaterbill.upwaterbill && uploadwaterbill.upwaterbill.length}</span> Houses ({waterbillmonth.label})
                                                                        </button>
                                                                        {/* <button className='btn btn-info border-success m-1 p-1' onClick={submitWaterbill}>
                                                                            Save or Update Rent & Garbage ({waterbillmonth.label})
                                                                        </button> */}
                                                                    </div>
                                                                }
                                                            </>
                                                        }
                                                    </div>

                                                    
                                                    {acceptedFiles[0] && !loadingmonths && preview && 
                                                        <>
                                                            <div className="col-md-12 m-0 mt-1 p-0 elevation-2 border-info">
                                                                <p className='m-1 p-1'>
                                                                    <div className='justify-content-center'>
                                                                        <p className='text-success p-1'><strong>Changed:</strong> <small>Shows <strong>Yes</strong> when the value Saved is Same as Upload and <strong>No</strong> when not same</small></p>
                                                                        <p className='text-purple p-1'><strong>Matches:</strong> <small>Shows <strong>Yes</strong> for all readings where current bill for previous month is this upload month previous bill or otherwise <strong>No</strong></small></p>
                                                                        <p className='bg-warning text-dark p-1'><strong>Yellow backgroundColor:</strong> <small>Used to represent waterbills already saved or uploaded.</small></p>
                                                                        <p className='bg-danger text-dark p-1'><strong>Red backgroundColor:</strong> Used to represent waterbills where previous month's current does not match current month previous bill.</p>
                                                                    </div>
                                                                </p>
                                                            </div>
                                                        </>
                                                    }


                                                </div>
                                                    
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>

                            <div className="col-lg-9">
                                <div className="row m-0 p-0">

                                    <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                        <div className="card border-info m-0 p-0" >
                                            <div className="card-header bg-info text-white elevation-2 m-0 p-0">
                                                <p className='text-center p-1 m-0'>
                                                    {!loadingwater &&
                                                        <>
                                                            <span className="text-md float-left m-0 p-0 text-bold text-warning">
                                                                {uploadwaterbill.upwaterbill && uploadwaterbill.upwaterbill.length} /
                                                                {totalvalid}
                                                            </span>
                                                        </>
                                                    }
                                                    <span>
                                                        {loadingwater &&
                                                            <Spinner  variant="light" size="md" role="status"></Spinner>
                                                        }
                                                        {!loadingwater &&
                                                            <>
                                                                Rent & Garbage for {waterbillpropertyid.label!==undefined && waterbillpropertyid.label+" "+waterbillmonth.label} 
                                                                {/* {preview && 
                                                                <span className='text-warning'>
                                                                    (Previewing Upload)
                                                                </span>
                                                                } */}
                                                            </>
                                                        }
                                                    </span>

                                                    <span className="text-xs float-right m-0 p-0">
                                                        <input onChange={handleSearchChange} value={search.value} className='border-info p-1 pt-0 pb-0' placeholder='Search House,Tenant' />
                                                    </span>
                                                </p>
                                                
                                            </div>

                                            <div className="card-body text-center m-0 p-1" >
                                            
                                                <div className="row m-0 p-0">
                                                    <div className="messageinfo col-12 m-0 p-0">
                                                    
                                                    {loadingwater &&
                                                        <div className="col-12 text-left m-0 p-1 mt-1 mb-2">
                                                            <TableSmallSpinner />
                                                        </div>
                                                    }

                                                    {loadingmonths &&
                                                        <div className="col-12 text-left m-0 p-1 mt-1 mb-2">
                                                            <TableSmallSpinner />
                                                        </div>
                                                    }
                                                    
                                                    {!loadingwater && !loadingmonths &&
                                                        <table border="1" className="table table-hover table-bordered text-xs" id="example1">
                                                            {waterbilldata  && waterbilldata &&
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
                                                                                    Sno
                                                                                </span>
                                                                        </label>
                                                                        
                                                                    </th>
                                                                    <th className='elevation-2 m-0 p-1'>Housename</th>
                                                                    <th className='elevation-2 m-0 p-1'>Tenant</th>
                                                                    <th className='elevation-2 m-0 p-1'>Forwaded</th>
                                                                    <th className='elevation-2 m-0 p-1'>Rent</th>
                                                                    <th className='elevation-2 m-0 p-1'>Garbage</th>
                                                                    <th className='elevation-2 m-0 p-1'>Waterbill</th>
                                                                    <th className='elevation-2 m-0 p-1' title='House Deposit + KPLC Deposit + Water Deposit'>Deposits</th>
                                                                    <th className='elevation-2 m-0 p-1'>Lease</th>
                                                                    <th className='elevation-2 m-0 p-1'>Total</th>
                                                                    
                                                                    <th className='elevation-2 m-0 p-1'>Action</th>
                                                                </tr></thead>
                                                            }
                                                            <tbody>
                                                            {(acceptedFiles[0])?
                                                                <>
                                                                    {preview &&
                                                                        <>
                                                                            {!loadingmonths &&
                                                                                <>
                                                                                    {(search.value==='')?
                                                                                        <>
                                                                                            {waterbilldata  && waterbilldata.map((waterbill,key) => (
                                                                                                    <>
                                                                                                        {waterbill.waterid===null?
                                                                                                            <>
                                                                                                                {waterbill.tid==='Vacated'?
                                                                                                                    <>
                                                                                                                        <VacatedWaterTable waterbill={waterbill} key={key} no={key} preview={preview} />
                                                                                                                    </>
                                                                                                                :
                                                                                                                    <>
                                                                                                                        {waterbill.prevmatches==='Yes'?
                                                                                                                            <>
                                                                                                                                <WaterbillPreviewMatchTable waterbill={waterbill} key={key} no={key} preview={preview} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} /> 
                                                                                                                            </>
                                                                                                                        :
                                                                                                                            <WaterbillPreviewNoMatchTable waterbill={waterbill} key={key} no={key} preview={preview} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange}/>
                                                                                                                        } 
                                                                                                                    </>
                                                                                                                }
                                                                                                            </>
                                                                                                        :
                                                                                                            <>
                                                                                                                <WaterbillPreviewSavedTable waterbill={waterbill} key={key} no={key} preview={preview} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} /> 
                                                                                                            </>
                                                                                                        }
                    
                                                                                                    </>
                                                                                                ))
                                                                                            }
                                                                                        </>
                                                                                    :
                                                                                        <>
                                                                                            {search.result  && search.result.map((waterbill,key) => (
                                                                                                <>
                                                                                                {waterbill.waterid===null?
                                                                                                        <>
                                                                                                            {waterbill.tid==='Vacated'?
                                                                                                                <>
                                                                                                                    <VacatedWaterTable waterbill={waterbill} key={key} no={key} preview={preview} />
                                                                                                                </>
                                                                                                            :
                                                                                                                <>
                                                                                                                    {waterbill.prevmatches==='Yes'?
                                                                                                                        <>
                                                                                                                            <WaterbillPreviewMatchTable waterbill={waterbill} key={key} no={key} preview={preview} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} /> 
                                                                                                                        </>
                                                                                                                    :
                                                                                                                        <WaterbillPreviewNoMatchTable waterbill={waterbill} key={key} no={key} preview={preview} />
                                                                                                                    }
                                                                                                                </>
                                                                                                            }
                                                                                                        </>
                                                                                                    :
                                                                                                        <>
                                                                                                            <WaterbillPreviewSavedTable waterbill={waterbill} key={key} no={key} preview={preview} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} /> 
                                                                                                        </>
                                                                                                }

                                                                                                </>
                                                                                                ))
                                                                                            }
                                                                                        </>
                                                                                    
                                                                                    }
                                                                                </>
                                                                            }
                                                                        </>  
                                                                    }
                                                                </>
                                                                // ggfd
                                                            :
                                                                <>
                                                                    {(search.value==='')?
                                                                        <>
                                                                            {waterbilldata  && waterbilldata.map((waterbill,key) => (
                                                                                <RentGarbageTable waterbill={waterbill} key={key} no={key} preview={preview} handleShow={handleShow} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} />
                                                                            ))
                                                                            }
                                                                        </>
                                                                    :
                                                                        <>
                                                                            {search.result  && search.result.map((waterbill,key) => (
                                                                                <RentGarbageTable waterbill={waterbill} key={key} no={key} preview={preview} handleShow={handleShow} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} />
                                                                            ))
                                                                            }
                                                                        </>
                                                                    
                                                                    }
                                                                </>      
                                                            }
                                                            </tbody>
                                                        </table>
                                                    }
                                                    </div>
                                                </div>

                                            </div>
                                                <div className="card-footer bg-white mx-auto m-0 p-0">
                                                    <div className='p-1 m-0'>
                                                        {!loadingwater &&
                                                            <>
                                                                {preview && 
                                                                    (<span className="text-lg float-left m-0 p-0 pr-2 border-info text-bold text-danger">
                                                                        {uploadwaterbill.upwaterbill && uploadwaterbill.upwaterbill.length} /
                                                                        {totalvalid}
                                                                    </span>)
                                                                }
                                                            </>
                                                        }

                                                        {loading && 
                                                            <span className="mx-auto justify-content-center text-center text-white">
                                                                    <Spinner
                                                                    as="span"
                                                                    variant='dark'
                                                                    animation="border"
                                                                    size="lg"
                                                                    role="status"
                                                                    aria-hidden="true"
                                                                    />
                                                                    <span className='text-white' style={{"padding": "10px","display":"inline-block"}}>
                                                                        {waterbillpropertyid.label!==undefined && waterbillpropertyid.label+" "+waterbillmonth.label} ...
                                                                    </span>
                                                                    
                                                            </span>
                                                        }

                                                        {uploadwaterbill.upwaterbill.length > 0 &&
                                                            <>
                                                                {!loading && 
                                                                    <div className="text-xs mx-auto m-1 p-1">
                                                                        <button className='btn btn-success border-success m-1 p-1' onClick={generateSelectedRentGarbage}>
                                                                            Generate for <span className='text-md bold'>{uploadwaterbill.upwaterbill && uploadwaterbill.upwaterbill.length}</span> Houses ({waterbillmonth.label})
                                                                        </button>
                                                                        {/* <button className='btn btn-info border-success m-1 p-1' onClick={submitWaterbill}>
                                                                            Save or Update Rent & Garbage ({waterbillmonth.label})
                                                                        </button> */}
                                                                    </div>
                                                                }
                                                            </>
                                                        }
                                                    </div>
                                                    
                                                </div>

                                        </div>
                                    </div>

                                    {acceptedFiles[0] &&
                                        <>
                                            {!loadingmonths &&
                                                <p className='m-1 p-1'>
                                                    {preview && 
                                                        <div className='justify-content-center'>
                                                            <p className='text-success p-1'><strong>Changed:</strong> Shows <strong>Yes</strong> when the value Saved is Same as Upload and <strong>No</strong> when not same</p>
                                                            <p className='text-purple p-1'><strong>Matches:</strong> Shows <strong>Yes</strong> for all readings where current bill for previous month is this upload month previous bill or otherwise <strong>No</strong></p>
                                                            <p className='bg-warning text-dark p-1'><strong>Yellow backgroundColor:</strong> Used to represent waterbills already saved or uploaded.</p>
                                                            <p className='bg-danger text-dark p-1'><strong>Red backgroundColor:</strong> Used to represent waterbills where previous month's current does not match current month previous bill.</p>
                                                        </div>
                                                    }
                                                </p>
                                            }
                                        </>
                                    }


                                    {show && 
                                        <AddRentGarbage show={show} handleClose={handleClose} currentwaterbill={currentwaterbill} loadRentGarbage={loadRentGarbage}/>
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

export default UpdateRentGarbage;