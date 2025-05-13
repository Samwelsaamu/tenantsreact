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

function ManageUsers(props) {
    document.title="Manage Users";
    
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();


    const [closed,setClosed]=useState(false)
    const [isOpen,setIsOpen] = useState(false)


    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');

 
    
    const [totalvalid,setTotalValid]=useState(0)
    const [allchecked,setAllchecked]=useState(false);

    const [show,setShow]=useState(false);
    
    // const [loading,setLoading]=useState(true);
    const [loadingmonths,setLoadingMonths]=useState(true);
    const [loadingwater,setLoadingWater]=useState(true);
   

    const [loading,setLoading]=useState(false);
    const [loadingpage,setLoadingPage]=useState(false);

    const [currentuser,setCurrentUser]=useState([]);
    const [users,setUsers]=useState([]);

   
    

    const [formdata,setFormData]=useState({
        Fullname:'',
        Username:'',
        email:'',
        Idno:'',
        Phone:'',
        error_list:[],
    });

    // useEffect(()=>{
    //     let doneloading=true;
    //     if (doneloading) {
    //         loadThisUsersLogs();
    //     }
    //     return ()=>{
    //         doneloading=false;
    //     }
    // },[currentuser.id])
    
    const handleInputChange=(e)=>{
        e.persist();
        setFormData({...formdata,[e.target.name]:e.target.value})
        setLoadingRes('');
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
                    loadThisUsersLogs();
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


    const loadUsers= ()=>{
        axios.get(`/v2/load/users`)
        .then(response=>{
            if(response.data.status=== 200){
                setUsers(response.data.users);
            } 
            else if(response.data.status=== 401){
                setUsers([]) 
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


    useEffect(()=>{
        let doneloading=true;
        setLoadingPage(true)
        const getPrograms = async (e) => { 
            
            await axios.get(`/v2/load/users`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setUsers(response.data.users);
                        setLoadingPage(false)
                    }
                    else if(response.data.status=== 401){
                        Swal("Error1",response.data.message,"error");
                        setUsers([]);
                        setLoadingPage(false)
                    }
                    else if(response.data.status=== 500){
                        Swal("Error2",response.data.message,"error");
                        setUsers([]);
                        setLoadingPage(false)
                    }
                    else{
                        setUsers([]);
                        setLoadingPage(false)
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
                        setUsers([]);
                    }
                }
                else{
                    Swal("Error",""+error,"error");
                }
                
                setLoadingPage(false)

                setUsers([]);
            })
        };
        getPrograms();

        return ()=>{
            doneloading=false;
            
        }
    },[loggedoff])

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
                                    
                                    <li className="breadcrumb-item active"> Users </li>
                                </ol>
                            </div>

                        

                        <div className="col-lg-12 m-0 p-0">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info m-0 p-0" >
                                        <div className="card-header border-none m-0 p-1">
                                            <p className='text-center p-1 m-0'>
                                                {isOpen &&
                                                <h2>
                                                    Update User :: {currentuser && currentuser.Username}
                                                    <button type="button" onClick={()=>{setIsOpen(!isOpen)}}  className="float-right btn btn-outline-danger border-danger m-1 p-1">
                                                        <i className='fa fa-chevron-left'></i>
                                                    </button>
                                                </h2>
                                                }
                                                {!isOpen &&
                                                <h2>
                                                    Manage Users
                                                    <Link to={'/users/create'}>
                                                        <button type="button" onClick={()=>{setIsOpen(!isOpen)}}  className="float-right btn btn-success m-0 p-0 pl-1 pr-1">
                                                            <small><span className='fa fa-plus-circle'></span>  Create New User</small>
                                                        </button>
                                                    </Link>
                                                    
                                                </h2>
                                                }
                                            </p>
                                            
                                        </div>

                                        <div className="card-body text-center m-1 p-1 mt-3">
                                            {loadingpage?
                                                <LoadingDetailsSpinner />
                                            :
                                            <>
                                            {!loading && !isOpen &&
                                            <div className="row justify-content-center p-0 pt-0">
                                                {users && users.map((user,key) => (
                                                    <div className="col-12 col-lg-4 col-md-6 mb-2">
                                                        <div className="card card-primary card-outline p-0 border-info">
                                                        <div className="card-body box-profile p-0">
                                                            <div className="text-center ">
                                                                <img className="profile-user-img img-fluid img-circle bg-light p-0 border-white-b user-circle bg-light"
                                                                    src="/assets/img/avatar.png"
                                                                    alt="User profile picture"/>
                                                            </div>

                                                            <h3 className="profile-username text-center">{user.Fullname}</h3>

                                                            <p className="text-muted text-center">{user.Userrole}(
                                                                {user.user_online ?
                                                                <span className="text-success">Online</span>
                                                                :
                                                                <span className="text-muted">Offline</span>
                                                                }
                                                            )</p>
                                                           
                                                            <div className="text-center border-top m-1 p-1">
                                                                <p className='d-flex flex-column flex-sm-row justify-content-between m-1 p-1'>
                                                                    <b>Username:</b> <span className="text-muted">{user.Username}</span>
                                                                </p>
                                                            </div>

                                                            <div className="text-center border-top m-1 p-1">
                                                                <p className='d-flex flex-column flex-sm-row justify-content-between m-1 p-1'>
                                                                   <span className="text-muted">{user.email}</span>
                                                                    {/* <b>Email:</b> <span className="text-muted">{user.email}</span> */}
                                                                </p>
                                                            </div>
                                                            <div className="text-center border-top m-1 p-1">
                                                                <p className='d-flex flex-column flex-sm-row justify-content-between m-1 p-1'>
                                                                    <b>Last Login:</b> 
                                                                    <span className="text-muted">
                                                                    {user.last_login_at ?
                                                                        user.last_login_at
                                                                    :
                                                                        'Not Yet'
                                                                    }
                                                                    </span>
                                                                </p>
                                                            </div>

                                                            <div className="text-center border-top m-1 p-1">
                                                                <p className='d-flex flex-column flex-sm-row justify-content-between m-1 p-1'>
                                                                    <b>Last Activity:</b> 
                                                                    <span className="text-muted">
                                                                    {user.current_activity_at ?
                                                                        user.current_activity_at
                                                                    :
                                                                        'Not Yet'
                                                                    }
                                                                    </span>
                                                                </p>
                                                            </div>
                                                            

                                                            <div className='m-1 mb-3 p-2 border-top text-muted my-auto'>
                                                                <div className='mx-auto text-center'>
                                                                    <div>
                                                                        <Link to={'/user/'+user.id}>
                                                                            <button type="button"  className="btn btn-outline-info btn-sm border-none m-1 p-1">
                                                                                <span className='fa fa-info-circle'></span> View
                                                                            </button>
                                                                        </Link>
                                                                        <button type="button" onClick={()=>{updateUser(user)}} className="btn btn-outline-primary btn-sm border-none m-1 p-1">
                                                                            <span className='fa fa-edit'></span> Edit
                                                                        </button>
                                                                        
                                                                        <button type="button" onClick={()=>{deleteUser(user,'Delete')}} className="btn btn-outline-danger btn-sm border-none m-1 p-1">
                                                                            <span className='fa fa-trash'></span> Delete
                                                                        </button>

                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>

                                                           
                                                            

                                                        </div>
                                                        </div>
                                                    </div>
                                                ))}
                                            </div>  
                                            }
                                            </>
                                            }

                                            {isOpen &&
                                                <form className='justify-content-center' onSubmit={newUser}>
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

export default ManageUsers;