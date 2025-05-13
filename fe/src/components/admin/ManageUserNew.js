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

function ManageUserNew(props) {
    document.title="Manage Profile";
    
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();


    const [closed,setClosed]=useState(false)
    const [isOpen, setIsOpen] = useState(false)


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

    const [formdata,setFormData]=useState({
        Fullname:'',
        Username:'',
        email:'',
        Idno:'',
        Phone:'',
        error_list:[],
    });

    
    const handleInputChange=(e)=>{
        e.persist();
        setFormData({...formdata,[e.target.name]:e.target.value})
        setLoadingRes('');
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
            id:'',
            Fullname:formdata.Fullname,
            Username:formdata.Username,
            email:formdata.email,
            Phone:formdata.Phone,
            Idno:formdata.Idno,
            Userrole:formdata.Userrole,
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

    
  return (
    <>
    <div className="wrapper">
        {loggedoff ? 
            <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
        :
        <>
        <DashNavBar setClosed={setClosed} closed={closed} active='profile'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='profile'/>
        {/* className={`nav-link ${active==='home'?'active':''}`} */}
        
        <main className="py-1">
            <div className={`content-wrapper ${closed?'closed':''}`}>

                    <section className="content">
                    <div className="container">
                        <div className="row justify-content-center m-0 p-0">
                            <div className="col-12 m-0 p-0 ">
                                <ol className="breadcrumb float-sm-right text-xs m-0 p-1">
                                    <li className="breadcrumb-item"><Link to={'/dashboard'}>Dashboard</Link></li>
                                   
                                    <li className="breadcrumb-item"><Link to={'/users'}>Users</Link> </li>
                                    
                                    <li className="breadcrumb-item active"> Profile </li>
                                </ol>
                            </div>

                        

                        <div className="col-lg-12 m-0 p-0">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info m-0 p-0" >
                                        <div className="card-header elevation-2 m-0 p-2">
                                            <p className='text-center p-1 m-0'>
                                                
                                                <h2>
                                                    Create New User Account
                                                    <Link to={'/users'}>
                                                        <button type="button" onClick={()=>{setIsOpen(!isOpen)}}  className="float-right btn btn-success m-0 p-0 pl-1 pr-1">
                                                            <small><span className='fa fa-users'></span>  View Users</small>
                                                        </button>
                                                    </Link>
                                                </h2>
                                            </p>
                                            
                                        </div>

                                        <div className="card-body text-center m-1 p-1 mt-3" >
                                        
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

                                                        <div className="form-group row m-0 p-1 border-light mb-1 ">
                                                            <label htmlFor="Userrole" className="col-md-4 col-form-label text-md-right">Userrole</label>
                                                            {/* Admin Agent Owner Caretaker */}
                                                            <div className="col-md-6 " style={{"float":"right"}}>
                                                                <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                                        <input type="radio" required checked={formdata.Userrole==="Admin"?"true":""} onChange={handleInputChange} className="" name="Userrole" value="Admin" autoComplete="Userrole"/> Admin
                                                                </label>
                                                                <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                                    <input type="radio" required checked={formdata.Userrole==="Agent"?"true":""} onChange={handleInputChange} className="" name="Userrole" value="Agent" autoComplete="Userrole"/> Agent
                                                                </label>
                                                                {/* <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                                    <input type="radio" required checked={formdata.Userrole==="Owner"?"true":""} onChange={handleInputChange} className="" name="Userrole" value="Owner" autoComplete="Userrole"/> Owner
                                                                </label>
                                                                <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                                    <input type="radio" required checked={formdata.Userrole==="Caretaker"?"true":""} onChange={handleInputChange} className="" name="Userrole" value="Caretaker" autoComplete="Userrole"/> Caretaker
                                                                </label>
                                                                <label className='m-0 p-1 text-left' style={{"cursor": "pointer","float":"left"}}>
                                                                    <input type="radio" required checked={formdata.Userrole==="Landlord"?"true":""} onChange={handleInputChange} className="" name="Userrole" value="Landlord" autoComplete="Userrole"/> Landlord
                                                                </label> */}
                                                                {formdata.error_list && formdata.error_list.Userrole && 
                                                                    <span className="help-block text-danger">
                                                                        <strong>{formdata.error_list.Userrole}</strong>
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
                                                {!loading && loadingresok ==='' && 
                                                    <div className="form-group d-flex mb-0">
                                                        <div className="mx-auto">
                                                            <button type="submit" className="btn btn-outline-success border-info pl-4 pr-4">
                                                                Save Profile Changes
                                                            </button>
                                                        </div>
                                                    </div>
                                                }


                                            </>
                                            }
                                        </form>

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

export default ManageUserNew;