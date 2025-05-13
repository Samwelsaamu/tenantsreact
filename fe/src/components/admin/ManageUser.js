import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useMemo, useState, useCallback } from 'react';
import DashFooter from './DashFooter';
import Spinner from 'react-bootstrap/Spinner';

import axios from 'axios';

import { Link, useNavigate, useLocation } from 'react-router-dom';
import Select from 'react-select';
import { useParams } from 'react-router';
import { useDropzone } from 'react-dropzone';

import Swal from 'sweetalert';
import AddWaterbill from './AddWaterbill';
import TableSmallSpinner from '../spinners/TableSmallSpinner';
import ReLogin from '../home/ReLogin';
import AddRefundBills from './AddRefundBills';
import MonthlyDepositsTable from './Tables/MonthlyDepositsTable';
import MonthlyLeasesTable from './Tables/MonthlyLeasesTable';
import LoadingDetailsSpinner from '../spinners/LoadingDetailsSpinner';
import UsersLogsTable from './Tables/UsersLogsTable';

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

function ManageUser(props) {
    document.title="Manage Users";
    
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();

    let par=useParams()

    const [id,setID]=useState((par.id)?par.id:'')

    const [closed,setClosed]=useState(false)
    const [isOpen,setIsOpen] = useState(false)


    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');

 
    
    const [totalvalid,setTotalValid]=useState(0)
    const [allchecked,setAllchecked]=useState(false);

    const [show,setShow]=useState(false);
    
    // const [loading,setLoading]=useState(true);
    const [loadingmonths,setLoadingMonths]=useState(true);
    const [loadingwater,setLoadingWater]=useState(false);
   

    const [loading,setLoading]=useState(false);
    const [loadingpage,setLoadingPage]=useState(false);

    const [currentuser,setCurrentUser]=useState([]);
    const [currentuserlogs,setCurrentUserLogs]=useState([]);
    const [users,setUsers]=useState([]);
    const [waterbilldata, setWaterbillData] = useState([]);
    const [viewall, setViewAll] = useState(false);

    
    const [loglimit,setLogLimit]=useState(0)

   
    

    const [formdata,setFormData]=useState({
        Fullname:'',
        Username:'',
        email:'',
        Idno:'',
        Phone:'',
        error_list:[],
    });

    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            loadUser();
        }
        return ()=>{
            doneloading=false;
        }
    },[loggedoff,id])
    
    const handleInputChange=(e)=>{
        e.persist();
        setFormData({...formdata,[e.target.name]:e.target.value})
        setLoadingRes('');
    }

    const [search,setSearch]=useState({
        value:'',
        result:[]
    })
    

    const handleSearchChange =(e) =>{
        setLoadingMonths(true)
        const results=currentuserlogs.filter(userlog =>{
            if(e.target.value=== '') return currentuserlogs
            return userlog.Message.toLowerCase().includes(e.target.value.toLowerCase()) || userlog.created_at.toLowerCase().includes(e.target.value.toLowerCase())
        })
        setSearch({
            value:e.target.value,
            result:results
        })
        setLoadingMonths(false)
    }

    const loadThisUsersLogs= ()=>{
        setLoadingPage(true)
        let url=`/v2/load/currentuser`;
        axios.get(url)
        .then(response=>{
            if(response.data.status=== 200){
                setCurrentUser(response.data.user);
                
                setFormData({
                    Fullname:response.data.user['Fullname'],
                    Username:response.data.user['Username'],
                    email:response.data.user['email'],
                    Phone:response.data.user['Phone'],
                    Idno:response.data.user['Idno'],
                    error_list:[],
                })
                setLoadingPage(false)
            }
            else{
                setLoadingPage(false)
            }
            
        })
        .catch(error=>{
            if(!localStorage.getItem("auth_token")){
                let ex=error['response'].data.message;
                if(ex==='Unauthenticated.'){
                    if(!localStorage.getItem("auth_token")){
                        setLoadingPage(false)
                        setLoggedOff(true); 
                    }  
                    else{ 
                        setLoadingPage(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }              
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingResOk("")
                setLoadingPage(false)
            }
            else{
                let ex=error['response'].data.message;
                if(ex==='Unauthenticated.'){
                    setLoadingPage(false)
                    setLoggedOff(true); 
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_name');
                }
                else{
                    setLoadingPage(false)
                    Swal("Error",""+error,"error");
                }
            }
        })

    }

    const newUser= (e)=>{
        e.preventDefault();
        let phone=''+formdata.Phone;
        let idno=''+formdata.Idno;
        if(formdata.Fullname ===''){
            Swal("Enter First Name","Please Enter Users First Name","error");
            return;
        }
        if(formdata.Username ===''){
            Swal("Enter Username Name","Please Enter Users Username Name","error");
            return;
        }

        if(formdata.email ===''){
            Swal("Enter Email Address","Please Enter Users Email Address","error");
            return;
        }

        if(formdata.Idno !=='' && isNaN(formdata.Idno) ){
            Swal("Correct IDno","Please Specify IDno With Digits","error");
            return;
        }

        if(idno.length <7 ){
            Swal("Correct Phone Number","Please Enter a digit IDNo Number More than 7 digits","error");
            return;
        }

        if(idno.length >10 ){
            Swal("Correct Phone Number","Please Enter a digit IDNo Number Less than 10 digits","error");
            return;
        }

        if(formdata.Phone !=='' && isNaN(formdata.Phone) ){
            Swal("Correct Phone Number","Please Specify Phone Number With Digits","error");
            return;
        }
        if(phone.length !== 9 ){
            Swal("Correct Phone Number","Please Enter 9 digit Phone Number like 712345678","error");
            return;
        }
        
        
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")

        const form={
            id:(currentuser!=='')?currentuser.id:'',
            Fullname:formdata.Fullname,
            Username:formdata.Username,
            email:formdata.email,
            Phone:formdata.Phone,
            Idno:formdata.Idno,
        }
            axios.post('/v2/save/user',form,{
                headers:{
                    'content-type':'multipart/form-data'
                }
            })
            .then(response=>{
                if(response.data.status=== 200){
                    setLoadingResOk(response.data.message)
                    setLoadingRes("")
                    loadUser();
                    loadUsers();
                    setFormData({...formdata,error_list:[]});
                }
                else if(response.data.status=== 500){
                    setLoadingRes(response.data.message)
                    setLoadingResOk("")
                    setFormData({...formdata,error_list:[]});
                    Swal("Error","Please Update the Following errors","error");
                }
                else{
                    setLoadingRes(response.data.message)
                    setLoadingResOk("")
                    setFormData({...formdata,error_list:[]});
                    Swal("Error","Please Update the Following errors","error");
                    setFormData({...formdata,error_list:response.data.errors});
                }
                setLoading(false);

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
                            setLoading(false)
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }              
                    }
                    else{
                        Swal("Error",""+error,"error");
                    }
                    setLoadingResOk("")
                    setLoading(false)
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoading(false)
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

    const loadUser= ()=>{
        setLoadingPage(true)
        axios.get(`/v2/load/user/`+id)
        .then(response=>{
            if(response.data.status=== 200){
                setCurrentUser(response.data.user);
            } 
            else if(response.data.status=== 401){
                setCurrentUser([]) 
                Swal("Error",response.data.message,"error");
            }
            
            else if(response.data.status=== 500){
                Swal("Error",response.data.message,"error");
                setCurrentUser([]) 
            }
            setLoadingPage(false)
        })
        .catch((error)=>{
            if(!localStorage.getItem("auth_token")){
                let ex=error['response'].data.message;
                if(ex==='Unauthenticated.'){
                    if(!localStorage.getItem("auth_token")){
                        setLoadingPage(false)
                        setLoggedOff(true); 
                    }  
                    else{ 
                        setLoadingPage(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }              
                }
                else{
                    setLoadingPage(false)
                    Swal("Error",""+error,"error");
                    setCurrentUser([]);
                }
            }
            else{
                setLoadingPage(false)
                Swal("Error",""+error,"error");
            }
            setLoadingPage(false)
            setCurrentUser([]) 
        })

    }

    const loadUserLogs= (limit)=>{
        document.title="Manager User Logs"
        setLogLimit(limit)
        setLoadingWater(true)
        axios.get(`/v2/load/user/logs/`+id+'/'+limit)
        .then(response=>{
            if(response.data.status=== 200){
                setCurrentUserLogs(response.data.userlogs);
            } 
            else if(response.data.status=== 401){
                setCurrentUserLogs([])  
                setLoggedOff(true);  
                Swal("Error",response.data.message,"error");
            }
            
            else if(response.data.status=== 500){
                Swal("Error",response.data.message,"error");
                setCurrentUserLogs([]) 
            }
            setLoadingWater(false)
        })
        .catch((error)=>{
            if(!localStorage.getItem("auth_token")){
                let ex=error['response'].data.message;
                if(ex==='Unauthenticated.'){
                    if(!localStorage.getItem("auth_token")){
                        setLoadingWater(false)
                        setLoggedOff(true); 
                    }  
                    else{ 
                        setLoadingWater(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }              
                }
                else{
                    setLoadingWater(false)
                    Swal("Error",""+error,"error");
                    setCurrentUserLogs([]);
                }
            }
            else{
                setLoadingWater(false)
                Swal("Error",""+error,"error");
            }
            setLoadingWater(false)
            setCurrentUserLogs([]) 
        })

    }


    const loadUsers= ()=>{
        axios.get(`/v2/load/users`)
        .then(response=>{
            if(response.data.status=== 200){
                setUsers(response.data.users);
            } 
            else if(response.data.status=== 401){
                setUsers([]) 
                setLoggedOff(true);  
                Swal("Error",response.data.message,"error");
            }
            
            else if(response.data.status=== 500){
                Swal("Error",response.data.message,"error");
                setUsers([]) 
            }
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
                    setUsers([]);
                }
            }
            else{
                Swal("Error",""+error,"error");
            }
            setUsers([]) 
        })

    }


    // useEffect(()=>{
    //     let doneloading=true;
    //     setLoadingPage(true)
    //     const getPrograms = async (e) => { 
            
    //         await axios.get(`/v2/load/users`)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     setUsers(response.data.users);
    //                     setLoadingPage(false)
    //                 }
    //                 else if(response.data.status=== 401){
    //                     Swal("Error1",response.data.message,"error");
    //                     setUsers([]);
    //                     setLoadingPage(false)
    //                 }
    //                 else if(response.data.status=== 500){
    //                     Swal("Error2",response.data.message,"error");
    //                     setUsers([]);
    //                     setLoadingPage(false)
    //                 }
    //                 else{
    //                     setUsers([]);
    //                     setLoadingPage(false)
    //                 }
                    
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
    //                     setUsers([]);
    //                 }
    //             }
    //             else{
    //                 Swal("Error",""+error,"error");
    //             }
                
    //             setLoadingPage(false)

    //             setUsers([]);
    //         })
    //     };
    //     getPrograms();

    //     return ()=>{
    //         doneloading=false;
            
    //     }
    // },[loggedoff])

    const updateUser = (user) => {
        setIsOpen(true)
        setCurrentUser(user)
        setFormData({
            Fullname:user.Fullname,
            Username:user.Username,
            email:user.email,
            Phone:user.Phone,
            Idno:user.Idno,
            error_list:[],
        })
        
    };

    const deleteUser= (user,action)=>{
        const form={
            id:user.id,
            action:action,
        }

        let title=action+" "+user.Fullname;
        let text="This will "+action+" this User from the system.";
        Swal({
            title:title+' ?',
            text:text,
            buttons:true,
            infoMode:true,
        })
        .then((willcontinue) =>{
            if(willcontinue){
                setLoading(true);
                Swal("Taking Action....","Please Wait");
                axios.post('/v2/delete/user',form)
                .then(response=>{
                    if(response.data.status=== 200){
                        Swal("Success",response.data.message);
                    }
                    else if(response.data.status=== 401){ 
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    loadUsers();
                    setLoading(false);
                })
                .catch((error)=>{
                    if(error.message==="Request failed with status code 401"){
                        setLoggedOff(true);                    
                    }
                    else if(error.message==="Request failed with status code 500"){
                        setLoggedOff(true);                    
                    }
                    else{
                        Swal("Error",""+error,"error");
                    }
                    setLoading(false);
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
        <DashNavBar setClosed={setClosed} closed={closed} active='users'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='users'/>
        {/* className={`nav-link ${active==='home'?'active':''}`} */}
        
        <main className="py-1">
            <div className={`content-wrapper ${closed?'closed':''}`}>

                <section className="content">
                    <div className="container">
                        <div className="row justify-content-center m-0 p-0">
                            <div className="col-12 m-0 p-0 ">
                                <ol className="breadcrumb float-sm-right text-xs m-0 p-1">
                                    <li className="breadcrumb-item"><Link to={'/dashboard'}>Dashboard</Link></li>
                                    <li className="breadcrumb-item"><Link to={'/profile'}>Profile</Link> </li>
                                    <li className="breadcrumb-item"><Link to={'/users'}>Users</Link> </li>
                                    
                                    <li className="breadcrumb-item active"> {!loadingpage && currentuser && currentuser.Fullname+' ( '+currentuser.Userrole+')'} </li>
                                </ol>
                            </div>

                        

                        <div className="col-lg-12 m-0 p-0">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-2">
                                    <div className="card border-none m-0 p-0" >
                                        <div className="card-header border-none m-0 p-1">
                                            <p className='text-center p-0 m-0'>
                                                {isOpen &&
                                                <h5 className='m-0 p-0'>
                                                    Update User :: {!loadingpage && currentuser && currentuser.Username}
                                                    <button type="button" onClick={()=>{setIsOpen(!isOpen)}}  className="float-right btn btn-outline-info text-info border-none m-0 p-0">
                                                        <small><span className='fa fa-chevron-left'></span>  Back to User</small>
                                                    </button>
                                                </h5>
                                                }
                                                {!isOpen &&
                                                <h5 className='m-0 p-0'>
                                                    Manage :: {!loadingpage && currentuser && currentuser.Fullname}
                                                    <>
                                                        {!loadingpage && currentuser &&
                                                            <Link to={'/users'} className='float-right text-sm m-0 p-0 text-info'><small><span className='fa fa-chevron-left'></span>  Back to Users</small></Link>
                                                        }
                                                    </>
                                                    
                                                    
                                                </h5>
                                                }
                                            </p>
                                            
                                        </div>

                                        <div className="card-body text-center m-0 p-1">
                                            {loadingpage?
                                                <LoadingDetailsSpinner />
                                            :
                                            <div className="row justify-content-center p-0 pt-0">
                                                <div className="col-lg-4 ">
                                                    <div className="row m-0 p-0">
                                                        <div className="col-md-12 m-0 p-0 mb-4">
                                                            <div className="card card-primary card-outline p-0 border-info">
                                                                <div className="card-body box-profile p-0">
                                                                    <div className="text-center ">
                                                                        <img className="profile-user-img img-fluid img-circle bg-light p-0 border-white-b user-circle bg-light"
                                                                            src="/assets/img/avatar.png"
                                                                            alt="User profile picture"/>
                                                                    </div>

                                                                    <h3 className="profile-username text-center">{currentuser.Fullname}</h3>

                                                                    <p className="text-muted text-center">{currentuser.Userrole}(
                                                                        {currentuser.user_online ?
                                                                        <span className="text-success">Online</span>
                                                                        :
                                                                        <span className="text-muted">Offline</span>
                                                                        }
                                                                    )</p>
                                                                
                                                                    <div className="text-center border-top m-1 p-1">
                                                                        <p className='d-flex flex-row justify-content-between m-1 p-1'>
                                                                            <b>Username:</b> <span className="text-muted">{currentuser.Username}</span>
                                                                        </p>
                                                                    </div>

                                                                    <div className="text-center border-top m-1 p-1">
                                                                        <p className='d-flex flex-row justify-content-between m-1 p-1'>
                                                                            <b className='hidden-xs'>Email:</b> <span className="text-muted">{currentuser.email}</span>
                                                                        </p>
                                                                    </div>
                                                                    <div className="text-center border-top m-1 p-1">
                                                                        <p className='d-flex flex-row justify-content-between m-1 p-1'>
                                                                            <b>Last Login:</b> 
                                                                            <span className="text-muted">
                                                                            {currentuser.last_login_at ?
                                                                                currentuser.last_login_at
                                                                            :
                                                                                'Not Yet'
                                                                            }
                                                                            </span>
                                                                        </p>
                                                                    </div>

                                                                    <div className="text-center border-top m-1 p-1">
                                                                        <p className='d-flex flex-row justify-content-between m-1 p-1'>
                                                                            <b>Last Activity:</b> 
                                                                            <span className="text-muted">
                                                                            {currentuser.current_activity_at ?
                                                                                currentuser.current_activity_at
                                                                            :
                                                                                'Not Yet'
                                                                            }
                                                                            </span>
                                                                        </p>
                                                                    </div>
                                                                    

                                                                    <div className='m-1 mb-3 p-2 border-top text-muted my-auto'>
                                                                        <div className='mx-auto text-center'>
                                                                            <div>
                                                                                <button type="button"  className="btn btn-outline-info btn-sm border-none m-1 p-1">
                                                                                <Link to={'/user/'+currentuser.id}><span className='fa fa-info-circle'></span> View</Link>
                                                                                </button>
                                                                                <button type="button" onClick={()=>{updateUser(currentuser)}} className="btn btn-outline-primary btn-sm border-none m-1 p-1">
                                                                                    <span className='fa fa-edit'></span> Edit
                                                                                </button>
                                                                                
                                                                                <button type="button" onClick={()=>{deleteUser(currentuser,'Delete')}} className="btn btn-outline-danger btn-sm border-none m-1 p-1">
                                                                                    <span className='fa fa-trash'></span> Delete
                                                                                </button>

                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                
                                                                    

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div className="col-lg-8 ">
                                                    {isOpen ?
                                                    <div className="row m-0 p-0">
                                                        <div className="col-md-12 m-0 p-0 mb-4 border-info">
                                                            <form className='justify-content-center mt-4 mb-4' onSubmit={newUser}>
                                                                {loadingpage?
                                                                        <LoadingDetailsSpinner />
                                                                    :
                                                                    <>
                                                                    {!loading && 
                                                                        <>
                                                                            <div className="form-group row">
                                                                                <label for="Fullname" className="col-md-4 col-form-label text-md-right">Fullname <sub className='text-red text-sm'>*</sub></label>
                                                                                <div className="col-md-6 ">
                                                                                    <input id="Fullname" type="text" className="form-control" name="Fullname" placeholder="Fullname" value={formdata.Fullname} onChange={handleInputChange} required autoComplete="Fullname" autoFocus/>
                                                                                    {formdata.error_list && formdata.error_list.Fullname && 
                                                                                        <span className="help-block text-danger">
                                                                                            <strong>{formdata.error_list.Fullname}</strong>
                                                                                        </span>
                                                                                    }
                                                                                </div>
                                                                            </div>

                                                                            <div className="form-group row">
                                                                                <label for="Username" className="col-md-4 col-form-label text-md-right">Username <sub className='text-red text-sm'>*</sub></label>
                                                                                <div className="col-md-6 ">
                                                                                    <input id="Username" type="text" className="form-control" name="Username" placeholder="Username" value={formdata.Username} onChange={handleInputChange} required autoComplete="Username" autoFocus/>
                                                                                    {formdata.error_list && formdata.error_list.Username && 
                                                                                        <span className="help-block text-danger">
                                                                                            <strong>{formdata.error_list.Username}</strong>
                                                                                        </span>
                                                                                    }
                                                                                </div>
                                                                            </div>
                                                                        
                                                                            <div className="form-group row">
                                                                                <label for="Email" className="col-md-4 col-form-label text-md-right">Email Address <sub className='text-red text-sm'>*</sub></label>
                                                                                <div className="col-md-6 ">
                                                                                    <input id="Email" type="email" className="form-control" name="email" placeholder="Email Address" value={formdata.email} onChange={handleInputChange} required autoComplete="Email" autoFocus/>
                                                                                    {formdata.error_list && formdata.error_list.email && 
                                                                                        <span className="help-block text-danger">
                                                                                            <strong>{formdata.error_list.email}</strong>
                                                                                        </span>
                                                                                    }
                                                                                </div>
                                                                            </div>

                                                                            <div className="form-group row">
                                                                                <label for="Idno" className="col-md-4 col-form-label text-md-right">Idno Number <sub className='text-red text-sm'>*</sub> </label>
                                                                                <div className="col-md-6 ">
                                                                                    <input id="Idno" type="text" className="form-control" name="Idno" placeholder="00000000/0000000000" value={formdata.Idno} onChange={handleInputChange} required autoComplete="Idno" autoFocus/>
                                                                                    {formdata.error_list && formdata.error_list.Idno && 
                                                                                        <span className="help-block text-danger">
                                                                                            <strong>{formdata.error_list.Idno}</strong>
                                                                                        </span>
                                                                                    }
                                                                                </div>
                                                                            </div>

                                                                            <div className="form-group row">
                                                                                <label for="Phone" className="col-md-4 col-form-label text-md-right">Phone Number <sub className='text-red text-sm'>*</sub> </label>
                                                                                <div className="col-md-6 ">
                                                                                    <input id="Phone" type="text" className="form-control" name="Phone" placeholder="700000000/100000000" value={formdata.Phone} onChange={handleInputChange} required autoComplete="Phone" autoFocus/>
                                                                                    {formdata.error_list && formdata.error_list.Phone && 
                                                                                        <span className="help-block text-danger">
                                                                                            <strong>{formdata.error_list.Phone}</strong>
                                                                                        </span>
                                                                                    }
                                                                                </div>
                                                                            </div>

                                                                            
                                                                        </>
                                                                    }

                                                                    {loadingresok!=='' && 
                                                                        <div className="col-md-12 elevation-0 mb-2 p-2 text-center">
                                                                            <span className="help-block text-success">
                                                                                <strong>{loadingresok!=='' && loadingresok}</strong>
                                                                            </span>
                                                                        </div>
                                                                    }

                                                                    {loading && 
                                                                        <div className="col-md-12 text-center text-white">
                                                                                <span className='text-black' style={{"padding": "10px","display":"inline-block"}}>Please Wait ...</span>
                                                                                <br/>
                                                                                <Spinner
                                                                                    as="span"
                                                                                    variant='info'
                                                                                    animation="border"
                                                                                    size="lg"
                                                                                    role="status"
                                                                                    aria-hidden="true"
                                                                                />
                                                                                <span className='text-info p-4' style={{"padding": "10px","display":"inline-block"}}>Updating Profile...</span>
                                                                                
                                                                        </div>
                                                                    }

                                                                    {loadingres!=='' && 
                                                                        <div className="col-md-12 elevation-0 mt-2 p-2 text-center border-none">
                                                                            <span className="help-block text-danger">
                                                                                <strong>{loadingres!=='' && loadingres}</strong>
                                                                            </span>
                                                                        </div>
                                                                    }
                                                                    {!loading &&
                                                                        <div className="form-group d-flex mb-0">
                                                                            <div className="mx-auto">
                                                                                <button type="submit" className="btn btn-outline-success border-info m-1 pl-4 pr-4">
                                                                                    <i className='fa fa-check'></i> Save Profile Changes
                                                                                </button>
                                                                                <button type="button" onClick={()=>{setIsOpen(!isOpen)}}  className="btn btn-outline-secondary border-danger m-1 pl-4 pr-4">
                                                                                <i className='fa fa-chevron-left'></i> Back
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    }


                                                                </>
                                                                }
                                                            </form>
                                                        </div>
                                                    </div>
                                                    :
                                                    <div className="row m-0 p-0">
                                                        <div className="col-md-12 m-0 p-0 mb-4">
                                                            <div class="card">
                                                                <div class="card-header p-1">
                                                                    <ul class="nav nav-pills m-0 p-0">
                                                                        <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm active" href="#userlogs" data-toggle="tab">User Logs</a></li>
                                                                        {/* <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm" href="#logins" data-toggle="tab">Logins</a></li> */}
                                                                        <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm" href="#permissions" data-toggle="tab">Permissions</a></li>
                                                                        <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm" href="#settings" data-toggle="tab">Settings</a></li>
                                                                        <li className="nav-item m-0 p-0">
                                                                            <button className='btn btn-success m-1 p-0 pl-2 pr-2' onClick={()=>{setViewAll(!viewall)}}>{viewall?<small> <i className='fa fa-minus '></i> Mini Table</small>:<small> <i className='fa fa-plus '></i> Full Table</small>}</button>
                                                                        </li>
                                                                        <li class="nav-item ml-auto">
                                                                            <span className="text-xs float-right m-0 p-1">
                                                                                <input onChange={handleSearchChange} value={search.value} className='border-info p-1 pt-0 pb-0' placeholder='Search House,Tenant, Year' />
                                                                            </span>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="card-body m-0 p-0">
                                                                    <div class="tab-content m-0 p-0">
                                                                        <div class="active tab-pane m-0 p-0" id="userlogs">
                                                                            {/* <h4 class="text-info text-center">User Logs & Activities </h4> */}
                                                                            <div className="mx-auto m-0 p-0">
                                                                                {loglimit ==100 ?
                                                                                    <button type="submit" className="btn btn-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(100)}}> Load-100
                                                                                    </button>
                                                                                :
                                                                                    <button type="submit" className="btn btn-outline-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(100)}}> Load-100
                                                                                    </button>
                                                                                }
                                                                                {loglimit ==300 ?
                                                                                    <button type="submit" className="btn btn-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(300)}}> Load-300
                                                                                    </button>
                                                                                : 
                                                                                    <button type="submit" className="btn btn-outline-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(300)}}> Load-300
                                                                                    </button>
                                                                                }
                                                                                {loglimit ==500 ?
                                                                                    <button type="submit" className="btn btn-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(500)}}> Load-500
                                                                                    </button>
                                                                                :
                                                                                    <button type="submit" className="btn btn-outline-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(500)}}> Load-500
                                                                                    </button>
                                                                                }
                                                                                {loglimit ==1000 ?
                                                                                    <button type="submit" className="btn btn-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(1000)}}> Load-1000
                                                                                    </button>
                                                                                : 
                                                                                    <button type="submit" className="btn btn-outline-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(1000)}}> Load-1000
                                                                                    </button>
                                                                                }
                                                                                {loglimit ==2000 ?
                                                                                    <button type="submit" className="btn btn-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(2000)}}> Load-2000
                                                                                    </button>
                                                                                    :
                                                                                    <button type="submit" className="btn btn-outline-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(2000)}}> Load-2000
                                                                                    </button>
                                                                                }
                                                                                {loglimit ==3000 ?
                                                                                    <button type="submit" className="btn btn-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(3000)}}> Load-3000
                                                                                    </button>
                                                                                    :
                                                                                    <button type="submit" className="btn btn-outline-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(3000)}}> Load-3000
                                                                                    </button>
                                                                                }

                                                                                {loglimit ==5000 ?      
                                                                                    <button type="submit" className="btn btn-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(5000)}}> Load-5000
                                                                                    </button>
                                                                                    :
                                                                                    <button type="submit" className="btn btn-outline-primary text-xs m-1 p-0 pl-1 pr-1"
                                                                                        onClick={()=>{loadUserLogs(5000)}}> Load-5000
                                                                                    </button>
                                                                                }
                                                                            </div>
                                                                            <div className="row m-0 p-0">
                                                                                <div className={`col-12 m-0 p-0 ${viewall?'tablemaxinfo':'tableinfo'}`}>
                                                                                
                                                                                {loadingwater &&
                                                                                    <div className="col-12 text-left m-0 p-1 mt-1 mb-2">
                                                                                        <TableSmallSpinner />
                                                                                    </div>
                                                                                }
                                
                                                                                
                                                                                {!loadingwater &&
                                                                                    <table border="1" className="table table-hover table-bordered text-xs" id="example1">
                                                                                        {currentuserlogs  &&
                                                                                            <thead  >
                                                                                            <tr  style={{"color":"white","backgroundColor":"#77B5ED"}}>
                                                                                                <th className='elevation-2 m-0 p-1'>Sno </th>
                                                                                                <th className='elevation-2 m-0 p-1'>Log Activity ({currentuserlogs  && currentuserlogs.length})</th>
                                                                                                <th className='elevation-2 m-0 p-1'>Logged On</th>
                                                                                            </tr></thead>
                                                                                        }
                                                                                        <tbody>
                                                                                            <>
                                                                                                {(search.value==='')?
                                                                                                    <>
                                                                                                        {currentuserlogs  && currentuserlogs.map((waterbill,key) => (
                                                                                                            <UsersLogsTable waterbill={waterbill} key={key} no={key} />
                                                                                                        ))
                                                                                                        }
                                                                                                    </>
                                                                                                :
                                                                                                    <>
                                                                                                        {search.result  && search.result.map((waterbill,key) => (
                                                                                                            <UsersLogsTable waterbill={waterbill} key={key} no={key} />
                                                                                                        ))
                                                                                                        }
                                                                                                    </>
                                                                                                
                                                                                                }
                                                                                            </>  
                                                                                        </tbody>
                                                                                    </table>
                                                                                }
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        {/* <div class="tab-pane" id="logins">
                                                                            <h4 class="text-info text-center">User Logins </h4>
                                                                        </div> */}
                                                                        <div class="tab-pane" id="permissions">
                                                                            <h4 class="text-info text-center">Permissions </h4>
                                                                        </div>
                                                                        <div class="tab-pane" id="settings">
                                                                            <h4>Coming Up</h4>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    }
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

export default ManageUser;