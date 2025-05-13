import axios from 'axios';
import Swal from 'sweetalert';
import { useEffect, useState, useContext } from 'react';
import Button from 'react-bootstrap/Button';
import Form from 'react-bootstrap/Form';
import NavDropdown from 'react-bootstrap/NavDropdown';
import { useLocation } from 'react-router-dom';
import { useNavigate } from 'react-router-dom';
import Toast from 'react-bootstrap/Toast';
import ToastContainer from 'react-bootstrap/ToastContainer';


import { Link, Navigate } from 'react-router-dom';
import userlogo from '../../assets/img/avatar.png';
import Spinner from 'react-bootstrap/esm/Spinner';
import ReLogin from '../home/ReLogin';
import { LoginContext } from '../contexts/LoginContext';

function DashNavBar({setClosed,closed,active}) {
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
        
    const [loggedoff,setLoggedOff]=useState(false);
    const [redirect,setRedirect]=useState(false);
    const [loading,setLoading]=useState(true);
    const [url,setUrl]=useState('');
    const [authname,setAuthName]=useState('');
    const [searchopen,setSearchOpen]=useState(false)

    
    const [loadingappdata,setLoadingAppData]=useState(true);
    const [totalbalance,setAppDataBalance]=useState('');
    const [totalbalanceerror,setAppDataBalanceError]=useState('');

    const [searchhouse,setSearchhouse]=useState('')

    const [searchresult,setSearchResult]=useState([])
    const [resulttenants,setResultTenants]=useState([])
    const [resultproperties,setResultProperties]=useState([])
    const [resulthouses,setResultHouses]=useState([])
    const [resultcurmonth,setResultCurmonth]=useState('')
    const [resultcurmonthname,setResultCurmonthname]=useState('')
    const [resultpreviousmonth,setResultPreviousmonth]=useState('')
    const [resultpreviousmonthname,setResultPreviousmonthname]=useState('')

    const [resultthisplotname,setResultThisPlotname]=useState('')
    const [resultthisplotid,setResultThisPlotid]=useState('')
    
    const [loadingmonths,setLoadingMonths]=useState(true);
    const [showtoast,setShowToast]=useState(true);
    
  
    const location=useLocation();
    
    useEffect(()=>{
        if(!localStorage.getItem("auth_token")){
            setAuthName('');
            // setRedirect(true);
            // setUrl('/login');
            
        }
        else{
            setAuthName(localStorage.getItem("auth_name"));
        }
        setLoading(false)
      },[authname])

    useEffect( () =>{
        
        socket.on('message_sent', (msg) =>{
            loadAppData();
        })

        socket.on('load_credit_balance', (msg) =>{
            loadAppData();
        })

        return () => {
            socket.off("load_credit_balance");
            socket.off("message_sent");
        };

    }, []);


    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            loadAppData();
        }
        return ()=>{
            doneloading=false;
        }
    },[loggedoff])

    
    // useEffect(()=>{
    //     axios.get('/v2/isAuthencticated')
    //         .then(response=>{
    //             if(response.data.status=== 200){
    //                 setAuthName(localStorage.getItem("auth_name")); 
    //             }
    //             setLoading(false)
    //         })
    //         .catch((error)=>{
    //             localStorage.removeItem('auth_token');
    //             localStorage.removeItem('auth_name');
    //             setAuthName('');
    //             setRedirect(true);
    //             setUrl('/login');
    //         })

    //         return () =>{
    //             // setAuthName('');
    //             // setRedirect(true);
    //             // setUrl('/login');
    //         };
    // },[authname])

    const handleLogout=(e) =>{
        e.preventDefault();
        Swal("Logging Out....","Please Wait");
            axios.post('/v2/logout')
            .then(response=>{
                if(response.data.status=== 200){
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_name');
                    Swal("Success",response.data.message,"success");
                    setRedirect(true);
                    setUrl('/login');
                }
                setLoading(false)

            })
            .catch((error)=>{
                // Swal("Not Logged Out",""+error.message,"error");
                localStorage.removeItem('auth_token');
                localStorage.removeItem('auth_name');
                setRedirect(true);
                setUrl('/login');
            })
    }
    
    // axios.interceptors.response.use(undefined,function axiosRetryInterceptor(err){
    //     if(err.response.status===401){
    //         setRedirect(true);
    //         setUrl('/login');
    //     }
    //     return Promise.reject(err);
    // });


    // useEffect(()=>{
    //     let thisurl=location.pathname;
    //     console.log(thisurl)
    //     // setRedirect(true);
    //     // setUrl(location.pathname);
    //     // window.location.href=thisurl;
    // },[location.pathname])


    function handlePropertyChange(val) {
        setLoadingMonths(true)
        let thisurl=location.pathname;
        console.log(thisurl)
        setRedirect(true);
        setUrl(location.pathname);
        setLoadingMonths(false)
    }

    const handleInputChange=(e)=>{
        e.persist();
        setSearchhouse(e.target.value)
        let searchquery=e.target.value;
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
        }
        const getProperties = async (e) => { 
            
            if(searchquery===''){
                setLoadingMonths(false)
                return false;
            }
            

            await axios.get(`/v2/search/load/${searchquery}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        console.log(response.data)
                        let restenantinfo = response.data.thistenant;

                        setResultTenants(response.data.thistenant)
                        setResultProperties(response.data.thisproperty)
                        setResultHouses(response.data.thishouse)

                        setResultCurmonth(response.data.month)
                        setResultCurmonthname(response.data.curmonthname)
                        setResultPreviousmonth(response.data.previousmonth)
                        setResultPreviousmonthname(response.data.previousmonthname)

                        setResultThisPlotname(response.data.thisplotname)
                        setResultThisPlotid(response.data.thisplotid)
                       
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

    useEffect(()=>{
        
    },[])
    
    // load app data balance
    const loadAppData=()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingAppData(true)
            setAppDataBalance(0.00);
        }
        const getDashStats = (e) => { 
            axios.get(`/v2/getappdata`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setAppDataBalance(response.data.totalbalance);
                        
                        setLoadingAppData(false)
                    }
                    else if(response.data.status=== 401){
                        setAppDataBalance('0.00');
                        setLoggedOff(true);    
                    }
                    else if(response.data.status=== 500){
                        setAppDataBalance('0.00');
                    }
                    else{
                        setLoadingAppData(false)
                    }
                    setLoadingAppData(false)
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
                    setLoadingAppData(false)
                    setAppDataBalance('0.00');
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingAppData(false)
                        setAppDataBalance('0.00');
                    }
                }
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    }

    

    if(redirect){
        return <Navigate to={url} />
    }

  return (
    <>
        <nav className={`main-header navbar navbar-expand fixed-top ${closed?'closed':''} p-0`}>
            <ul className="navbar-nav">
                <li className="nav-item ml-4 mr-2" onClick={()=>{setClosed(!closed)}}>
                    <Link className="nav-link" data-widget="pushmenu" to="#" role="button">
                        <i className="fas fa-bars fa-2x"></i>
                    </Link>
                </li>
                {/* {closed?
                    <li className="nav-item ml-4 mr-2" onClick={()=>{setClosed(!closed)}}>
                        <Link className="nav-link" data-widget="pushmenu" to="#" role="button">
                            <i className="fas fa-bars fa-2x"></i>
                        </Link>
                    </li>
                :
                    <li className="nav-item ml-4 mr-2" onClick={()=>{setClosed(!closed)}}>
                        <Link className="nav-link text-red" data-widget="pushmenu" to="#" role="button">
                            <i className="fas fa-times fa-2x"></i>
                        </Link>
                        
                    </li>
                } */}
                <li className="nav-item d-none d-sm-inline-block">
                    <Link to="/profile" className="nav-link">{authname} (Admin)</Link>
                </li>

            </ul>


            {/* <Form className="d-flex mx-auto"> 
            <Form.Control
                type="search"
                placeholder="Search"
                className="me-2"
                aria-label="Search"
                />
                <Button bg="info" variant="primary"> {searchopen?<i className="fas fa-times"> Close</i>:<i className='fas fa-search'> </i>}  </Button>
            </Form>  */}

            

            <ul className="navbar-nav mx-auto">
                <Form.Control
                    type="search"
                    placeholder="Search"
                    className="d-flex mx-auto"
                    aria-label="Search"
                    onChange={handleInputChange} value={searchhouse}
                    //
                />
                {searchhouse?
                <div className="col-2 col-lg-2 m-0 p-0 text-sm text-dark float-right">
                    <button className='bg-danger m-0 ml-1 p-2 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{setSearchhouse('')}} > <i className='fas fa-times'> </i></button>
                </div>:''}
            </ul>

            <ul className="navbar-nav ml-auto">
                {/* <li className="nav-item dropdown">
                    <Button bg="info" variant="light" onClick={()=>{loadAppData()}}> <i className="fa fa-sync-alt"> </i>  </Button>    
                </li> */}
                {loadingappdata &&
                <li className="nav-item dropdown m-2 p-0">
                    <Spinner
                        as="span"
                        variant='light'
                        animation="border"
                        size="sm"
                        role="status"
                        aria-hidden="true"
                    />
                </li>
                }
                <li className="nav-item dropdown">
                    <NavDropdown title={`${loadingappdata?'':totalbalance} `} id="navbarScrollingDropdown">
                        <span className="dropdown-item dropdown-header">Africas Talking Topups</span>
                        <div className="dropdown-divider"></div>
                        <Link  className="dropdown-item">
                            <b>Paybill</b>: 525900<br/>
                            <b>Acct No</b>: WAGITONGA.api
                        </Link>
                    </NavDropdown>
                </li>


                <li className="nav-item dropdown">
                    <NavDropdown title={authname} id="navbarScrollingDropdown" bg='light'>
                            {/* <span className="dropdown-item dropdown-header">
                                <img src={userlogo} width="30px" className="brand-image img-circle m-0 p-0 mx-auto" alt="User Image"/> {authname}
                            </span> */}
                        
                            <Link className="dropdown-item text-primary" to="/profile"><img src={userlogo} width="30px" className="brand-image img-circle m-0 p-0 mx-auto" alt="User Image"/> {authname}</Link>
                            <div className="dropdown-divider"></div>
                            {/* <Link className="dropdown-item " to="/profile"><i className="fa fa-user text-lime"></i> Profile ({authname})</Link> */}
                            <Link className="dropdown-item " to="/profile/change-password"><i className="fa fa-lock text-primary"></i> Change Password</Link>
                            <div className="dropdown-divider"></div>
                            <Link className="dropdown-item " to="/users"><i className="fa fa-users text-primary"></i> Manage Users</Link>
                            <div className="dropdown-divider"></div>
                            <Link className="dropdown-item " to="/settings"><i className="fa fa-cogs text-primary"></i> Manage Settings</Link>
                            <div className="dropdown-divider"></div>
                            <Link className="dropdown-item " to="#" onClick={handleLogout}><i className="fa fa-power-off text-danger"></i> Logout </Link>
                            
                            
                    </NavDropdown>
                    

                </li>

            
            </ul>

            <div className={`main-header ${searchhouse?'':'searchopen'} main-search bg-white ${closed?'closed':''} p-0 mt-4` }>
                <div className='search-wrapper'>

                    {/* <div className='row justify-content-center elevation-2 border-info text-center p-0 m-0'>
                        <div className="col-10 col-lg-10 text-xs m-0 p-0">
                            <input name='search' onChange={handleInputChange} value={searchhouse} className='text-center text-sm border-none p-2 pt-0 pb-0 search-input' placeholder='Please Enter Key Words to Search' autoFocus />
                        </div>

                        <div className="col-2 col-lg-2 m-0 p-0 text-sm text-dark float-right">
                            <button className='bg-danger m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' > <i className='fas fa-times'> Close</i></button>
                        </div>
                        
                    </div> */}

                    {loggedoff ? 
                        <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
                    :
                    <div className='justify-content-center p-2'>
                        {searchhouse? 
                            <div className='elevation-2 border-info justify-content-center p-2'>
                                {loadingmonths &&
                                    <p><Spinner  variant="blue" size="sm" role="status"></Spinner></p>
                                }
                                
                                {!loadingmonths &&
                                    <div className='search-results '>
                                        {!loadingmonths &&
                                            <>
                                            {/* get properties details */}
                                                {resultproperties  && resultproperties.length>0 &&
                                                    <div className='search-results-div text-center'>
                                                        <h5 className='text-center'>Propeties Search ({resultproperties  && resultproperties.length})</h5>
                                                        <div className='p-0 m-1 text-center'>
                                                            <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}}><Link className='text-info' to={'/properties/manage'}> View Propeties</Link></p>
                                                        </div>
                                                        <div className='hr-search bg-info m-1 p-2 elevation-2'></div>
                                                        {resultproperties  && resultproperties.map((property,key) => (
                                                                <div className='p-0 m-1'>
                                                                    <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}}><Link className='text-success' to={'/properties/manage/'+property.id}> View {property.Plotname} ({property.totalhouses})</Link></p>
                                                                    {/* <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}} onClick={()=>{handlePropertyChange('/properties/manage/'+property.id)}}> View {property.Plotname} ({property.totalhouses})</p> */}
                                                                    <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}}><Link className='text-success' to={'/properties/update/waterbill/'+property.id+'/'+resultcurmonth}> Update {property.Plotname} Waterbill ({resultcurmonthname})</Link></p>
                                                                    <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}}><Link className='text-success' to={'/properties/update/waterbill/'+property.id+'/'+resultpreviousmonth}> Update {property.Plotname} Waterbill ({resultpreviousmonthname})</Link></p>
                                                                    <div className='hr-search bg-info m-0 p-0'>{key+1}</div>
                                                                </div>
                                                                
                                                            ))
                                                        }
                                                    </div>
                                                    
                                                }

                                            {/* get houses details */}
                                                {resulthouses  && resulthouses.length>0 &&
                                                    <div className='search-results-div text-center'>
                                                        <h5 className='text-center'>Houses Search ({resulthouses  && resulthouses.length})</h5>
                                                        <div className='p-0 m-1 '>
                                                            <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}}><Link className='text-info' to={'/properties/manage'}> View Propeties</Link></p>
                                                            <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}}><Link className='text-success' to={'/properties/manage/'+resultthisplotid}> View {resultthisplotname}</Link></p>
                                                            <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}}><Link className='text-success' to={'/properties/update/waterbill/'+resultthisplotid+'/'+resultcurmonth}> Update {resultthisplotname} Waterbill ({resultcurmonthname})</Link></p>
                                                            <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}}><Link className='text-success' to={'/properties/update/waterbill/'+resultthisplotid+'/'+resultpreviousmonth}> Update {resultthisplotname} Waterbill ({resultpreviousmonthname})</Link></p>
                                                            <div className='hr-search bg-info m-1 p-2 elevation-2'></div>
                                                        </div>
                                                        {resulthouses  && resulthouses.map((property,key) => (
                                                                <div className='p-0 m-1 '>
                                                                    <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}}><Link className='text-success' to={'/properties/house/'+property.pid+'/'+property.id}>View {property.Housename}</Link></p>
                                                                    <div className='hr-search bg-info m-0 p-0'>{key+1}</div>
                                                                </div>
                                                            ))
                                                        }
                                                    </div>
                                                    
                                                }

                                                {/* get tennat details */}
                                                {resulttenants  && resulttenants.length>0 &&
                                                    <div className='search-results-div text-center'>
                                                        <h5 className='text-center'>Tenant Search ({resulttenants  && resulttenants.length})</h5>
                                                        <div className='p-0 m-1'>
                                                            <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}}><Link className='text-info' to={'/properties/mgr/tenants'}> View Tenants</Link></p>
                                                        </div>
                                                        <div className='hr-search bg-info m-1 p-2 elevation-2'></div>
                                                        {resulttenants  && resulttenants.map((property,key) => (
                                                                <div className='p-0 m-1'>
                                                                    <p className='m-0 p-1 text-success'  style={{"whiteSpace":"nowrap"}}><Link className='text-success' to={'/properties/mgr/tenants/'+property.id}>View {property.tenantname}</Link></p>
                                                                    {property.Status==="New" || property.Status==="Vacated" ?
                                                                        <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-info'><Link to={'/properties/mgr/tenants/'+property.id+'/assign/'} className='text-success'><i className='fa fa-check'></i> Assign House to {property.tenantname}</Link></button>
                                                                    :
                                                                    <p>
                                                                        <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-info'><Link to={'/properties/mgr/tenants/'+property.id+'/addhouse/'} className='text-info'><i className='fa fa-plus-circle'></i> Add House to {property.tenantname}</Link></button>
                                                                        <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-danger'><Link to={'/properties/mgr/tenants/'+property.id+'/vacate/'} className='text-danger'><i className='fa fa-minus-circle'></i> Vacate {property.tenantname}</Link></button>
                                                                        <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-info'><Link to={'/properties/mgr/tenants/'+property.id+'/reassign/'} className='text-success'><i className='fa fa-exchange-alt'></i> Change {property.tenantname}</Link></button>
                                                                        <button className='bg-white m-0 mt-1 ml-1 pt-1 pl-2 pr-2 pb-1 border-none text-info'><Link to={'/properties/mgr/tenants/'+property.id+'/transfer/'} className='text-primary'><i className='fa fa-play'></i> Transfer {property.tenantname}</Link></button>
                                                                    </p>
                                                                    }
                                                                    
                                                                    <div className='hr-search bg-info m-0 p-0'>{key+1}</div>
                                                                </div>
                                                            ))
                                                        }
                                                    </div>
                                                    
                                                }
                                            </>
                                            
                                        }
                                        
                                    </div>
                                }
                            </div>
                            
                            :
                            <p className='search-welcome text-center text-lg justify-content-center border-none'>Please Enter Something to Search...</p>
                        }
                    </div>
                    }

                </div>
            </div>
            
        </nav>


    </>
    
  );
}

export default DashNavBar;