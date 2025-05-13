import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useMemo, useState, useContext } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';

import axios from 'axios';

import { useNavigate } from 'react-router-dom';
import Select from 'react-select';
import { useParams } from 'react-router';
import { useDropzone } from 'react-dropzone';

import Swal from 'sweetalert';

import Toast from 'react-bootstrap/Toast';
import ToastContainer from 'react-bootstrap/ToastContainer';

import AddWaterbill from './AddWaterbill';
import TableSmallSpinner from '../spinners/TableSmallSpinner';
import VacatedWaterTable from './Tables/VacatedWaterTable';
import WaterbillTable from './Tables/WaterbillTable';
import WaterbillPreviewNoMatchTable from './Tables/WaterbillPreviewNoMatchTable';
import WaterbillPreviewMatchTable from './Tables/WaterbillPreviewMatchTable';
import WaterbillPreviewSavedTable from './Tables/WaterbillPreviewSavedTable';
import ReLogin from '../home/ReLogin';
import WaterbillMessageTable from './Tables/WaterbillMessageTable';
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

function MessageWater(props) {
    document.title="Send Waterbill Messages";
    
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
    const [showtoast,setShowToast]=useState(true);

    

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
    
    useEffect( () =>{
        socket.on('waterbill_added', (msg) =>{
            loadMessages();
        })
        socket.on('message_sent', (msg) =>{
            loadMessages();
        })

    }, []);

    useEffect(()=>{
        let doneloading=true;

        if (doneloading) {
            setLoadingMonths(true)
            setLoadingWater(true)
            loadMessages();
        }
        return ()=>{
            doneloading=false;
        }
    },[id,month,loggedoff])

   

    const loadMessages =() =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
            setLoadingWater(true)
        }
        const getWaterbill = async (e) => { 
            const arr = [];
                arr.push({value: '', label: 'Select Month' });
            const arr1 = [];
                arr1.push({value: '', label: 'Select Property' });
            const arr2 = [];    
            let id1=(id==='')?0:id;
            
            await axios.get(`/v2/update/messages/water/load/${id1}/${month}`)
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

                        setLoadingMonths(false)
                        setLoadingWater(false)
                       
                    }
                    else if(response.data.status=== 401){
                        setLoadingMonths(false)
                        setLoadingWater(false)
                        setLoggedOff(true);    
                        Swal("Error7",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        setLoadingMonths(false)
                        setLoadingWater(false)
                        Swal("Error8",response.data.message,"error");
                    }
                    setLoadingMonths(false)
                    setLoadingWater(false)
                }
            })
            .catch(error=>{
                if(!localStorage.getItem("auth_token")){
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        if(!localStorage.getItem("auth_token")){
                            setLoadingMonths(false)
                            setLoadingWater(false)
                            setLoggedOff(true); 
                        }  
                        else{ 
                            setLoadingMonths(false)
                            setLoadingWater(false)
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }              
                    }
                    else{
                        setLoadingMonths(false)
                        setLoadingWater(false)
                        Swal("Error",""+error,"error");
                    }
                    setLoadingMonths(false)
                    setLoadingWater(false)
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoadingMonths(false)
                        setLoadingWater(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingMonths(false)
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
            let thisurl=`/messages/water/${id}/${month}`;
            navigate(thisurl)
        }
        
        setUploadWaterbill({
            upwaterbill:[],
        });
    },[id,month])

    
    useEffect(()=>{
        
        let previewuncheckedtotal=0;
        waterbilldata.map((waterbilld) => {
            if((('+254'+waterbilld.phone).length ==13) && (waterbilld.isNumberBlacklisted =='No' || waterbilld.isNumberBlacklisted ==null)){
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
                    if((('+254'+waterbilld.phone).length ==13) && (waterbilld.isNumberBlacklisted =='No' || waterbilld.isNumberBlacklisted ==null)){
                        arr.push(waterbilld.phone+'/'+waterbilld.house+'/WATER BILL: Greetings '+waterbilld.housename+' :'+waterbilld.monthdate+';Previous:'+waterbilld.previous+';Current:'+waterbilld.current+';UnitCost Kshs.'+waterbilld.cost+';Units:'+waterbilld.units+';'+(waterbilld.total_os>0?'CC:'+waterbilld.total+';Other:'+waterbilld.total_os+';':'')+'Total Kshs.'+(waterbilld.total+waterbilld.total_os)+'.Thank You');
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
        
        const form={
            month:waterbillmonth.value,
            waterchoosen:uploadwaterbill.upwaterbill,
        }

        let title='Are you sure to Send ('+uploadwaterbill.upwaterbill.length;
        let text=waterbillpropertyid.label!==undefined && waterbillpropertyid.label+' '+waterbillmonth.label;
        Swal({
            title:title+') Selected Waterbill Messages ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Sending....","Please Wait");
                axios.post('/v2/send/message/all/water',form)
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

    const sendMessage= (waterbill)=>{
        let message='WATER BILL: Greetings '+waterbill.housename+' :'+waterbill.monthdate+';Previous:'+waterbill.previous+';Current:'+waterbill.current+';UnitCost Kshs.'+waterbill.cost+';Units:'+waterbill.units+';'+(waterbill.total_os>0?'CC:'+waterbill.total+';Other:'+waterbill.total_os+';':'')+'Total Kshs.'+(waterbill.total+waterbill.total_os)+'. Thank You';
        const form={
            Phone:waterbill.phone,
            Message:message,
            month:waterbill.month,
            hid:waterbill.house,
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
                axios.post('/v2/send/message/single/water',form)
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
        <DashNavBar setClosed={setClosed} closed={closed} active='waterbillmessages'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='waterbillmessages'/>
        {/* className={`nav-link ${active==='home'?'active':''}`} */}
        
        <main className="py-3">
            <div className={`content-wrapper ${closed?'closed':''}`}>
                        
                    <section className="content">
                    <div className="container">
                        
                        <div className="row justify-content-center">
                        <div aria-live='polite'
                            aria-atomic='true'
                            className='m-0 p-0 bg-dark position-relative' >
                                <ToastContainer position='top-end' className='m-0 p-0' style={{zIndex:999,opacity:.8}}>
                                    <Toast className='bg-success' onClose={() =>setShowToast(false)} show={showtoast} delay={20000} autohide>
                                        <Toast.Header className='bg-info text-danger'>
                                            <strong className='me-auto'>Something has Changed</strong>
                                        </Toast.Header>
                                        <Toast.Body className='text-white'>
                                            Hello world. this is the toast Message.
                                        </Toast.Body>
                                    </Toast>
                                </ToastContainer>
                        </div>

                        <div className="col-lg-3 ">
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

                                                {!loadingmonths &&
                                                    <div className='m-1 p-1 text-xs'>
                                                        <div className='justify-content-center'>
                                                            <p className='text-success p-0 border-5'> Please Click on <strong>Send / Resend</strong> Button to Send A single Message. No of Characters and total message to be priced is shown.</p>
                                                            <p className='text-light text-dark p-0 border-5'><strong>White BackgroundColor</strong> <br/>Means Message not sent yet</p>
                                                            <p className='bg-warning text-dark p-0 border-5'><strong>Yellow backgroundColor:</strong> <br/>Means Message has been sent.</p>
                                                            <p className='bg-secondary text-info p-0 border-5'><strong>Grey backgroundColor:</strong> <br/>Show selected Messages.</p>
                                                        </div>
                                                    </div>
                                                }
                                                {uploadwaterbill.upwaterbill.length > 1 ?
                                                    <>
                                                        {!loading && 
                                                            <span className="text-xs float-right m-0 p-1">
                                                                <button className='btn btn-success border-success p-1 pt-0 pb-0' onClick={submitWaterbill}>
                                                                    Send ({uploadwaterbill.upwaterbill.length}) {waterbillpropertyid.label!==undefined && waterbillpropertyid.label+" "+waterbillmonth.label} Waterbill Messages 
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

                        <div className="col-lg-9">
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
                                                            {waterbillmonth.label} Water Messages {waterbillpropertyid.label!==undefined && " for: "+waterbillpropertyid.label} 
                                                          
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
                                                                        <WaterbillMessageTable waterbill={waterbill} key={key} no={key} handleShow={handleShow} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} sendMessage={sendMessage} /> 
                                                                    ))
                                                                    }
                                                                </>
                                                            :
                                                                <>
                                                                    {search.result  && search.result.map((waterbill,key) => (
                                                                        <WaterbillMessageTable waterbill={waterbill} key={key} no={key} handleShow={handleShow} uploadwaterbill={uploadwaterbill} allchecked={allchecked} handleChange={handleChange} sendMessage={sendMessage} /> 
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
                                                                    Send ({uploadwaterbill.upwaterbill.length}) {waterbillpropertyid.label!==undefined && waterbillpropertyid.label+" "+waterbillmonth.label} Waterbill Messages 
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

export default MessageWater;