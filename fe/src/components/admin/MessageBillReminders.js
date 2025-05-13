import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useMemo, useState, useCallback, useContext } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';

import axios from 'axios';

import { useNavigate } from 'react-router-dom';
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
import WaterbillMessageTable from './Tables/WaterbillMessageTable';
import ReminderMessageTable from './Tables/ReminderMessageTable';
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

function MessageBillReminders(props) {
    document.title="Send Bill Due Reminders";
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
        
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

    const [formdata,setFormData]=useState({
        Phone:'',
        Message:'Reminder: {house} Your {month} Bill is Ksh.{total}. {rent} {garbage} {waterbillds} {housed} {waterd} {kplcd} {lease} {paid} {balance}',
        error_list:[],
    });

    const [msglength, setMsglength] = useState(0)
    const [msgcount, setMsgcount] = useState(0)

    const handleInputChange=(e)=>{
        e.persist();
        setUploadWaterbill({
            upwaterbill:[],
        })
        setFormData({...formdata,[e.target.name]:e.target.value})

        if(e.target.name=='Message'){
            let msg_length=e.target.value.length;
            setMsglength(msg_length);

            if (msg_length>160) {
                let msg_count=Math.floor(msg_length/160)+1;
                setMsgcount(msg_count);
            }
            else{
                let msg_count=1;
                setMsgcount(msg_count);
            }

        }
    }

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
    // /messages/water/1/2022 12
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
            let url=`/v2/update/messages/reminder/load/${id}/${month}`;
            if(id===''){
                url='/v2/update/waterbill/load';
            }
            else{
                setLoadingMonths(false)
                setLoadingWater(false)
                return false;
                // url=`/v2/update/messages/reminder/load/${id}/${month}`;
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
            
            setLoadingMonths(false)
        }
    },[loggedoff])


    // useEffect(()=>{
    //     let doneloading=true;
        // if (doneloading) {
        //     setLoadingWater(true)
        // }
    //     const getWaterbill = async (e) => { 
            // const arr = [];
            //     // arr.push({value: '', label: 'Select Month' });
            // const arr1 = [];
            //     arr1.push({value: '', label: 'Select Property' });
    //         let url=`/v2/update/messages/reminder/load/${id}/${month}`;
    //         if(id===''){
    //             setLoadingWater(false)
    //             return false;
    //         }
    //         else{
    //             url=`/v2/update/messages/reminder/load/${id}/${month}`;
    //         }
    //         await axios.get(url)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     let resmonths = response.data.previousmonths;
    //                     let respropertyinfo = response.data.propertyinfo;

    //                     let resthisproperty = response.data.thisproperty;
    //                     let resselectedmonth= response.data.selectedmonth;
    //                     let resselectedmonthname= response.data.selectedmonthname;

    //                     respropertyinfo.map((monthsup) => {
    //                         return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
    //                     });
    //                     setPropertyinfo(arr1)

    //                     resmonths.map((months) => {
    //                         return arr.push({value: months.month, label: months.monthname , data: months});
    //                     });
    //                     setUpdateMonths(arr)
                        

    //                     setWaterbillData(response.data.waterbilldata);
    //                     setPreview(response.data.preview);

                        
                        
    //                     let monthoptions=[];
    //                     if(id!==''){
    //                         monthoptions={value: resselectedmonth, label: resselectedmonthname}
    //                     }
                        
    //                     setWaterbillMonth(monthoptions)

    //                     let options=[];
    //                     if(id!==''){
    //                         options={value: resthisproperty.id, label: resthisproperty.Plotname+'('+resthisproperty.Plotcode+')' , data: resthisproperty}
    //                     }
                        
    //                     setWaterbillPropertyId(options)
                       
    //                     setLoadingWater(false)
    //                 }
    //                 else if(response.data.status=== 401){
    //                     setLoggedOff(true);    
    //                     Swal("Error4",response.data.message,"error");
    //                 }
    //                 else if(response.data.status=== 500){
    //                     Swal("Error5",response.data.message,"error");
    //                 }
    //                 else{
    //                     setLoadingWater(false)
    //                 }
    //                 setLoadingWater(false)
    //             }
    //         })
    //         .catch(error=>{
    //             if(!localStorage.getItem("auth_token")){
    //                 let ex=error['response'].data.message;
    //                 if(ex==='Unauthenticated.'){
    //                     if(!localStorage.getItem("auth_token")){
    //                         setLoggedOff(true); 
    //                     }  
    //                     else{ 
    //                         setLoggedOff(true); 
    //                         localStorage.removeItem('auth_token');
    //                         localStorage.removeItem('auth_name');
    //                     }              
    //                 }
    //                 else{
    //                     Swal("Error",""+error,"error");
    //                 }
    //                 setLoadingWater(false)
    //             }
    //             else{
    //                 let ex=error['response'].data.message;
    //                 if(ex==='Unauthenticated.'){
    //                     setLoggedOff(true); 
    //                     localStorage.removeItem('auth_token');
    //                     localStorage.removeItem('auth_name');
    //                 }
    //                 else{
    //                     setLoadingWater(false)
    //                     Swal("Error",""+error,"error");
    //                 }
    //             }
    //         })
    //     };
    //     getWaterbill();

    //     return ()=>{
    //         doneloading=false;
    //     }
    // },[id,month,loggedoff])


    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingWater(true)
        }
        const getWaterbill = async (e) => { 
            const arr = [];
            // arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
            arr1.push({value: '', label: 'Select Property' });
            let url=`/v2/update/messages/reminder/load/${id}/${month}`;
            if(id===''){
                setLoadingWater(false)
                return false;
            }
            else{
                url=`/v2/update/messages/reminder/load/${id}/${month}`;
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

                        setWaterbillData(response.data.waterbilldata);

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr1)

                        resmonths.map((months) => {
                            return arr.push({value: months.month, label: months.monthname , data: months});
                        });
                        setUpdateMonths(arr)

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
        if(!acceptedFiles[0]){
            getWaterbill();
        }
        

        return ()=>{
            doneloading=false;
        }
    },[id,month,loggedoff])

    const loadMessages =() =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingWater(true)
        }
        const getWaterbill = async (e) => { 
            const arr = [];
            // arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
            arr1.push({value: '', label: 'Select Property' });
            let url=`/v2/update/messages/reminder/load/${id}/${month}`;
            if(id===''){
                setLoadingWater(false)
                return false;
            }
            else{
                url=`/v2/update/messages/reminder/load/${id}/${month}`;
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

                        setWaterbillData(response.data.waterbilldata);

                        respropertyinfo.map((monthsup) => {
                            return arr1.push({value: monthsup.id, label: monthsup.Plotname , data: monthsup});
                        });
                        setPropertyinfo(arr1)

                        resmonths.map((months) => {
                            return arr.push({value: months.month, label: months.monthname , data: months});
                        });
                        setUpdateMonths(arr)

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
        if(id!==''){
            let thisurl=`/messages/reminders/${id}/${month}`;
            navigate(thisurl)
        }
        
        setUploadWaterbill({
            upwaterbill:[],
        });
    },[id,month])

    
    useEffect(()=>{
        
        let previewuncheckedtotal=0;
        waterbilldata.map((waterbilld) => {
            if((waterbilld.phone.length ==13) && (waterbilld.isNumberBlacklisted =='No' || waterbilld.isNumberBlacklisted ==null)){
                previewuncheckedtotal++;
            }
        })

        setTotalValid(previewuncheckedtotal)
        
    },[waterbilldata])

    
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
                    if((waterbilld.phone.length ==13) && (waterbilld.isNumberBlacklisted =='No' || waterbilld.isNumberBlacklisted ==null)){
                        let balance='';
                        if(waterbilld.Balance>0){
                            if(waterbilld.CarriedForward>0){
                                balance='Previous Arrears '+Math.abs(waterbilld.CarriedForward) +' Kindly Pay a Balance of Kshs.'+waterbilld.Balance+'.';
                            }
                            else{
                                balance='Kindly Pay a Balance of Kshs.'+waterbilld.Balance+'.';
                            }
                        }
                        else if(waterbilld.Balance<0){
                            balance='Overpayment Kshs.'+Math.abs(waterbilld.Balance)+'.';
                        }
                        else if(waterbilld.Balance==0){
                            balance='You have No Arrears.';
                        }

                        let paid='';
                        if(waterbilld.TotalPaid>0){
                            if(waterbilld.CarriedForward<0){
                                paid='Received Ksh.'+waterbilld.TotalPaid+'. Last Month Overpayment of '+Math.abs(waterbilld.CarriedForward);
                            }
                            else{
                                paid='Received Ksh.'+waterbilld.TotalPaid+'.';
                            }
                        }
                        else{
                            if(waterbilld.CarriedForward<0){
                                if(waterbilld.TotalPaid>0){
                                    paid='Received Ksh.'+waterbilld.TotalPaid+'. Last Month Overpayment of '+Math.abs(waterbilld.CarriedForward);
                                }
                                else{
                                    paid='Last Month Overpayment of '+Math.abs(waterbilld.CarriedForward);
                                }

                            }
                        }

                        let rent='';
                        if(waterbilld.Rent>0){
                            rent='Rent '+waterbilld.Rent+' ';
                        }

                        let garbage='';
                        if(waterbilld.Garbage>0){
                            garbage='Garbage '+waterbilld.Garbage+' ';
                        }
                        
                        let waterbillds='';
                        if(waterbilld.Waterbill>0){
                            waterbillds='Waterbill '+waterbilld.Waterbill+' ';
                        }
                        
                        let housed='';
                        if(waterbilld.HseDeposit>0){
                            housed='Deposit '+waterbilld.HseDeposit+' ';
                        }
                        
                        let waterd='';
                        if(waterbilld.Water>0){
                            waterd='Water Deposit '+waterbilld.Water+' ';
                        }
                        
                        let kplcd='';
                        if(waterbilld.KPLC>0){
                            kplcd='KPLC Deposit '+waterbilld.KPLC+' ';
                        }
                        
                        let lease='';
                        if(waterbilld.Lease>0){
                            lease='Lease '+waterbilld.Lease+' ';
                        }

                        // String.prototype.namef =function() { return this.replace(/{fname}/g, waterbill.tenantfname) }
                        String.prototype.name =function() { return this.replace(/{name}/g, waterbilld.tenantfname) }
                        String.prototype.house =function() { return this.replace(/{house}/g, waterbilld.housename) }
                        String.prototype.balance =function() { return this.replace(/{balance}/g, balance) }
                        String.prototype.paid =function() { return this.replace(/{paid}/g, paid) }
                        String.prototype.total =function() { return this.replace(/{total}/g, waterbilld.TotalUsed) }
                        String.prototype.month =function() { return this.replace(/{month}/g, waterbilld.monthdate) }
                        String.prototype.rent =function() { return this.replace(/{rent}/g, rent) }
                        String.prototype.garbage =function() { return this.replace(/{garbage}/g, garbage) }
                        String.prototype.waterbillds =function() { return this.replace(/{waterbillds}/g, waterbillds) }
                        String.prototype.housed =function() { return this.replace(/{housed}/g, housed) }
                        String.prototype.waterd =function() { return this.replace(/{waterd}/g, waterd) }
                        String.prototype.kplcd =function() { return this.replace(/{kplcd}/g, kplcd) }
                        String.prototype.lease =function() { return this.replace(/{lease}/g, lease) }

                        let message=(formdata.Message).name();
                        message=(message).house();
                        message=(message).balance();
                        message=(message).paid();
                        message=(message).month();
                        message=(message).total();
                        message=(message).rent();
                        message=(message).garbage();
                        message=(message).waterbillds();
                        message=(message).housed();
                        message=(message).waterd();
                        message=(message).kplcd();
                        message=(message).lease();

                        let msg_length=message.length;
                        let msg_count=0;

                        if (msg_length>160) {
                            msg_count=Math.floor(msg_length/160)+1;
                        }
                        else{
                            msg_count=1;
                        }


                        arr.push(waterbilld.phone+'/'+waterbilld.house+'/'+message);
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

//     savepid
// savemonth
// waterbillvaluesupdate

    const submitWaterbill= (e)=>{
        e.preventDefault();

        if((formdata.Message).trim() ===''){
            Swal("Enter Message Details","Please Enter Message you wish to Send","error");
            return;
        }

        const form={
            month:waterbillmonth.value,
            waterchoosen:uploadwaterbill.upwaterbill,
            pid:waterbillpropertyid.value,
        }
        
        let title='Are you sure to Send ('+uploadwaterbill.upwaterbill.length;
        let text=waterbillpropertyid.label!==undefined && waterbillpropertyid.label+' '+waterbillmonth.label;
        Swal({
            title:title+') Selected Payment Messages ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Sending....","Please Wait");
                axios.post('/v2/send/message/all/reminder',form)
                .then(response=>{
                    if(socket) {
                        socket.emit('message_sent',response.data.message);
                    }
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
                    setUploadWaterbill({
                        upwaterbill:[],
                    })
                })
                .catch((error)=>{
                    if(!localStorage.getItem("auth_token")){
                        let ex=error['response'].data.message;
                        if(ex==='Unauthenticated.'){
                            if(!localStorage.getItem("auth_token")){
                                setLoading(false)
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

    const sendMessage= (waterbill,message)=>{
        // let message='WATER BILL: Greetings '+waterbill.housename+' :'+waterbill.monthdate+';Previous:'+waterbill.previous+';Current:'+waterbill.current+';UnitCost Kshs.'+waterbill.cost+';Units:'+waterbill.units+';'+(waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':'')+'Total Kshs.'+(waterbill.total+waterbill.total_os)+'. Thank You';
        if((message).trim() ===''){
            Swal("Enter Message Details","Please Enter Message you wish to Send","error");
            return;
        }
        const form={
            Phone:waterbill.phone,
            Message:message,
            month:waterbill.month,
            hid:waterbill.house,
            pid:waterbillpropertyid.value,
        }

        let title='Are you sure to Send';
        let text="Phone numbers ( "+waterbill.phone+" ) \n Message: \n"+message;
        Swal({
            title:title+' this Message ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Sending....","Please Wait");
                axios.post('/v2/send/message/reminder',form)
                .then(response=>{
                    if(socket) {
                        socket.emit('message_sent',response.data.message);
                    }
                    if(response.data.status=== 200){
                        loadMessages();
                        Swal("Success",response.data.message);
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        Swal("Error","Please Review the Errors","error");
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

  return (
    <>
    <div className="wrapper">
        {loggedoff ? 
            <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
        :
        <>
        <DashNavBar setClosed={setClosed} closed={closed} active='rentremainders'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='rentremainders'/>
        {/* className={`nav-link ${active==='home'?'active':''}`} */}
        
        <main className="py-3">
            <div className={`content-wrapper ${closed?'closed':''}`}>

                    <section className="content">
                    <div className="container">
                        <div className="row justify-content-center">

                        <div className="col-lg-4 ">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info elevation-2" >
                                        <div className="card-body text-center p-0 m-1">
                                            <div className="row m-0 p-0">
                                                <div className="col-md-12 m-0 mt-1 p-0">
                                                    {loadingmonths &&
                                                        <Spinner  variant="info" size="md" role="status"></Spinner>
                                                    }
                                                    {!loadingmonths &&
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
                                                    {loadingmonths &&
                                                        <Spinner  variant="info" size="md" role="status"></Spinner>
                                                    }
                                                    {!loadingmonths &&
                                                        <Select
                                                            placeholder= "Select Month"
                                                            value={waterbillmonth}
                                                            name="waterbill-month"
                                                            options={updatemonths}
                                                            onChange={handleWaterbillMonthChange}
                                                        />
                                                    }
                                                </div>

                                                <div className="col-md-12 m-0 mt-1 p-0">
                                                    {loadingmonths &&
                                                        <Spinner  variant="info" size="md" role="status"></Spinner>
                                                    }
                                                    {!loadingmonths &&
                                                        <div className="form-group row m-0 p-1 pb-2">
                                                            <div className="col-12 m-0 p-0">
                                                                <textarea id="Message" type="text" className="form-control" rows='3' name="Message" value={formdata.Message} onChange={handleInputChange} placeholder="Compose Payment Reminder Message to send to tenants Here"></textarea>
                                                                <div className="text-black text-sm">Characters: {msglength}, {msgcount} Message(s)</div>
                                                
                                                                {formdata.error_list && formdata.error_list.Message && 
                                                                    <span className="help-block text-danger">
                                                                        <strong>{formdata.error_list.Message}</strong>
                                                                    </span>
                                                                }
                                                            </div>
                                                        </div>
                                                    }
                                                </div>

                                                {!loadingmonths &&
                                                    <div className='m-0 p-1 text-xs'>
                                                        <div className='justify-content-center  m-0 p-0'>
                                                            {/* <p className='text-success p-0 border-5'> Please Click on <strong>Send</strong> Button to Send A single Message</p> */}
                                                            <div className='elevation-3 border-5'>
                                                                <div className='elevation-2 p-1 bg-info text-dark text-sm bold'>Include More details in the Message box.</div>
                                                                <div className='bg-light text-dark p-1 border-5 text-left'>
                                                                    <strong>Name:</strong>{'{name}'}.
                                                                    <strong> House:</strong>{'{house}'}.
                                                                    <strong> Month:</strong>{'{month}'}.
                                                                    <strong> Rent:</strong>{'{rent}'}.
                                                                    <strong> Garbage:</strong>{'{garbage}'}.
                                                                    <strong> Waterbill:</strong>{'{waterbilld}'}.
                                                                    <strong> WaterD:</strong>{'{waterd}'}.
                                                                    <strong> HouseD:</strong>{'{housed}'}.
                                                                    <strong> KPLCD:</strong>{'{kplcd}'}.
                                                                    <strong> Lease:</strong>{'{lease}'}.
                                                                    <strong> Total:</strong>{'{total}'}.
                                                                    <strong> Paid:</strong>{'{paid}'}.
                                                                    <strong> Balance:</strong>{'{balance}'}.
                                                                </div>
                                                                <div className='elevation-2 p-1 bg-secondary text-white'>
                                                                    <strong className='text-lg'>Example</strong> <br/>
                                                                    Reminder: {'{name}'} of {'{house}'} Your {'{month}'} Bill is Ksh.{'{total}'}. {'{rent}'} {'{garbage}'} {'{waterbillds}'} {'{housed}'} {'{waterd}'} {'{kplcd}'} {'{lease}'} {'{paid}'} {'{balance}'}
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                }

                                                {/* {!loadingmonths &&
                                                    <p className='m-1 p-1 text-xs'>
                                                        <div className='justify-content-center'>
                                                            <p className='text-success p-0 border-5'> Please Click on <strong>Send / Resend</strong> Button to Send A single Message</p>
                                                            <p className='text-light text-dark p-0 border-5'><strong>White BackgroundColor</strong> <br/>Means Message not sent yet</p>
                                                            <p className='bg-warning text-dark p-0 border-5'><strong>Yellow backgroundColor:</strong> <br/>Means Message has been sent.</p>
                                                            <p className='bg-secondary text-info p-0 border-5'><strong>Grey backgroundColor:</strong> <br/>Show selected Messages.</p>
                                                        </div>
                                                    </p>
                                                } */}
                                                {uploadwaterbill.upwaterbill.length > 1 ?
                                                    <>
                                                        {!loading && 
                                                            <span className="text-xs float-right m-2 p-1">
                                                                <button className='btn btn-success border-success elevation-4 p-1 pt-0 pb-0' onClick={submitWaterbill}>
                                                                    Send ({uploadwaterbill.upwaterbill.length}) {waterbillpropertyid.label!==undefined && waterbillpropertyid.label+" "+waterbillmonth.label} Payment Reminder Messages 
                                                                </button>
                                                            </span>
                                                        }
                                                    </>
                                                    :
                                                    <>
                                                    <p className='m-0 p-0 text-center text-danger'>Please Select two or more Messages to Send at Once </p>
                                                    </>
                                                }
                                            </div>
                                                
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                        </div>

                        <div className="col-lg-8">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info m-0 p-1" >
                                        <div className="card-header bg-info text-white elevation-2 m-0 p-0">
                                            <p className='text-center p-1 m-0'>
                                                {!loadingwater &&
                                                    <span className="text-xs float-left m-0 p-0">
                                                        {(uploadwaterbill.upwaterbill.length) != (totalvalid) ?
                                                            <label className="selwaterbill m-0 p-0 bg-light border-info" style={{"fontSize":"10px"}}>
                                                                <input type="checkbox" 
                                                                    className="selectedwaterbilltenants" 
                                                                    name="waterbillvalues[]"
                                                                    value="all"
                                                                    checked={allchecked}
                                                                    onChange={handleChange} />
                                                                    <span className="text-md  m-0 p-0 pl-1 pr-1 text-bold text-dark">
                                                                        {uploadwaterbill.upwaterbill && uploadwaterbill.upwaterbill.length} /
                                                                        {totalvalid}
                                                                    </span>
                                                            </label>
                                                            :
                                                            <label className="selwaterbill m-0 p-0 bg-secondary border-info" style={{"fontSize":"10px"}}>
                                                                <input type="checkbox" 
                                                                    className="selectedwaterbilltenants" 
                                                                    name="waterbillvalues[]"
                                                                    value="all"
                                                                    checked={allchecked}
                                                                    onChange={handleChange} />
                                                                    <span className="text-md  m-0 p-0 pl-1 pr-1 text-bold text-dark">
                                                                        {uploadwaterbill.upwaterbill && uploadwaterbill.upwaterbill.length} /
                                                                        {totalvalid}
                                                                    </span>
                                                            </label>
                                                            }
                                                    </span>
                                                   
                                                }
                                                <span>
                                                    {loadingwater &&
                                                        <Spinner  variant="light" size="md" role="status"></Spinner>
                                                    }
                                                    {!loadingwater &&
                                                        <>
                                                            {waterbillmonth.label} Payment Reminder Messages {waterbillpropertyid.label!==undefined && " for: "+waterbillpropertyid.label} 
                                                          
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
                                                        <>
                                                            {(search.value==='')?
                                                                <>
                                                                    {waterbilldata  && waterbilldata.map((waterbill,key) => (
                                                                        <ReminderMessageTable waterbill={waterbill} key={key} no={key} handleShow={handleShow} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} sendMessage={sendMessage} formdata={formdata} /> 
                                                                    ))
                                                                    }
                                                                </>
                                                            :
                                                                <>
                                                                    {search.result  && search.result.map((waterbill,key) => (
                                                                        <ReminderMessageTable waterbill={waterbill} key={key} no={key} handleShow={handleShow} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} sendMessage={sendMessage} formdata={formdata} /> 
                                                                    ))
                                                                    }
                                                                </>
                                                            
                                                            }
                                                        </>   
                                                    }
                                                </div>
                                            </div>

                                        </div>

                                        <div className="card-footer bg-light text-white elevation-2 m-0 p-0">
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

                                                {uploadwaterbill.upwaterbill.length > 1 ?
                                                    <>
                                                        {!loading && 
                                                            <span className="text-xs float-right m-0 p-1">
                                                                <button className='btn btn-success border-success p-1 pt-0 pb-0' onClick={submitWaterbill}>
                                                                    Send ({uploadwaterbill.upwaterbill.length}) {waterbillpropertyid.label!==undefined && waterbillpropertyid.label+" "+waterbillmonth.label} Payment Reminder Messages 
                                                                </button>
                                                            </span>
                                                        }
                                                    </>
                                                    :
                                                    <>
                                                    <p className='m-0 p-0 text-center text-danger'>Please Select two or more Messages to Send at Once </p>
                                                    </>
                                                }
                                            </div>
                                            
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

export default MessageBillReminders;