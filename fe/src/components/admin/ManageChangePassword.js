import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useContext, useMemo, useState, useCallback } from 'react';
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

function ManageChangePassword(props) {
    document.title="Manage Change Password";
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
         
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

   
    

    const [formdata,setFormData]=useState({
        email:'',
        password:'',
        newpassword:'',
        confirmpassword:'',
        error_list:[],
    });
    
    const handleInputChange=(e)=>{
        e.persist();
        setFormData({...formdata,[e.target.name]:e.target.value})
        setLoadingRes('');
    }

    // useEffect( () =>{
    //     socket.on('load_credit_balance', (msg) =>{
    //         loadAppData();
    //     })

    // }, []);

    const newPasswordChange= (e)=>{
        e.preventDefault();

        if((formdata.password).trim() ===''){
            Swal("Current Password is Required","Please Enter Current Password","error");
            return;
        }

        if((formdata.newpassword).trim() ===''){
            Swal("New Password is Required","Please Enter New Password","error");
            return;
        }

        if((formdata.confirmpassword).trim() ===''){
            Swal("Confirm New Password is Required","Please Enter Confirm New Password","error");
            return;
        }

        if((formdata.newpassword).trim() !== (formdata.confirmpassword).trim()){
            Swal("Password Must Match","Please Enter Matching password","error");
            return;
        }
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")
        const form={
            email:formdata.email,
            password:formdata.password,
            newpassword:formdata.newpassword,
        }

        axios.post('/v2/save/change/password',form,{
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
            }
            else{
                setLoadingRes(response.data.message)
                setLoadingResOk("")
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
        <DashNavBar setClosed={setClosed} closed={closed} active='changepassword'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='changepassword'/>
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
                                    
                                    <li className="breadcrumb-item"><Link to={'/profile'}>Profile</Link> </li>
                                    <li className="breadcrumb-item active"> Change Password </li>
                                </ol>
                            </div>

                        

                        <div className="col-lg-12 m-0 p-0">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-info m-0 p-0" >
                                        <div className="card-header elevation-2 m-0 p-2">
                                            <p className='text-center p-1 m-0'>
                                                
                                                <h2>
                                                    Change Your Account Password
                                                </h2>
                                            </p>
                                            
                                        </div>

                                        <div className="card-body text-center m-1 p-1 mt-3" >
                                        
                                        <form className='justify-content-center' onSubmit={newPasswordChange}>
                                            {!loading && 
                                                <>
                                                    <div class="form-group row">
                                                        <label for="password" class="col-md-4 col-form-label text-md-right">Current Password</label>

                                                        <div class="col-md-6">
                                                            <input id="password" type="password" className="form-control @error('password') is-invalid @enderror" name="password" placeholder="Current Password" value={formdata.password} onChange={handleInputChange} required autoComplete="current-password"/>
                                                            {formdata.error_list && formdata.error_list.password && 
                                                                <span className="help-block text-danger">
                                                                    <strong>{formdata.error_list.password}</strong>
                                                                </span>
                                                            }
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="newpassword" class="col-md-4 col-form-label text-md-right">New Password</label>

                                                        <div class="col-md-6">
                                                            <input id="newpassword" type="password" className="form-control @error('password') is-invalid @enderror" name="newpassword" placeholder="New Password" value={formdata.newpassword} onChange={handleInputChange} required autoComplete="current-password"/>
                                                            {formdata.error_list && formdata.error_list.newpassword && 
                                                                <span className="help-block text-danger">
                                                                    <strong>{formdata.error_list.newpassword}</strong>
                                                                </span>
                                                            }
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="confirmpassword" class="col-md-4 col-form-label text-md-right">Confirm New Password</label>

                                                        <div class="col-md-6">
                                                            <input id="confirmpassword" type="password" className="form-control @error('confirmpassword') is-invalid @enderror" name="confirmpassword" placeholder="Confirm New Password" value={formdata.confirmpassword} onChange={handleInputChange} required/>
                                                            
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
                                                        <span className='text-info p-4' style={{"padding": "10px","display":"inline-block"}}>Changing Password...</span>
                                                        
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
                                                                    Save New Password
                                                                </button>
                                                            </div>
                                                        </div>
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

export default ManageChangePassword;