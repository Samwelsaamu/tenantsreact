import React ,{useState,useEffect, useLayoutEffect} from 'react'

import './App.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import {BrowserRouter , Route, Routes} from 'react-router-dom';

import Home from './components/home/Home';
import Spinner from 'react-bootstrap/Spinner';
import Button from 'react-bootstrap/Button';
import Card from 'react-bootstrap/Card';
import Placeholder from 'react-bootstrap/Placeholder';
import 'react-calendar/dist/Calendar.css';

import "react-toastify/dist/ReactToastify.css";

import './assets/plugins/fontawesome-free/css/all.min.css';
import './assets/plugins/toastr/toastr.min.css';

import './css/app.css';
import './css/home.css';
import './css/allcss.css';

// import 'react-select/dist/css/react-select.css';


// import './assets/dist/css/adminlte.min.css'






import './js/app.js';

import io from 'socket.io-client';

import {LoginContext} from "./components/contexts/LoginContext.js";


import PageIsNotLive from './components/home/PageIsNotLive';

import HomeProperties from './components/home/HomeProperties';
import HomeContactus from './components/home/HomeContactus';
import HomeGallery from './components/home/HomeGallery';
import HomeAboutus from './components/home/HomeAboutus';
import Login from './components/home/Login';
import Register from './components/home/Register';
import PasswordReset from './components/home/PasswordReset';
import axios from 'axios';
import Dashboard from './components/admin/Dashboard';
import DashboardNotFound from './components/admin/DashboardNotFound';
import PageNotFound from './components/home/PageNotFound';
import NotFound from './components/routes/NotFound';
import UpdateWaterbill from './components/admin/UpdateWaterbill';
import ManageProperties from './components/admin/ManageProperties';
import ManageProperty from './components/admin/ManageProperty';
import ManagePropertyHouse from './components/admin/ManagePropertyHouse';
import ManageTenants from './components/admin/ManageTenants';
import ManageTenant from './components/admin/ManageTenant.js';
import ManagePropertyHseTenant from './components/admin/ManagePropertyHseTenant.js';
import ManagePropertyVacateHseTenant from './components/admin/ManagePropertyVacateHseTenant.js';
import ManagePropertyAssignHseTenant from './components/admin/ManagePropertyAssignHseTenant.js';
import ManagePropertyVacateHseIDTenant from './components/admin/ManagePropertyVacateHseIDTenant.js';
import ManagePropertyVacateHseTenantID from './components/admin/ManagePropertyVacateHseTenantID.js';
import ManagePropertyAddHseTenant from './components/admin/ManagePropertyAddHseTenant.js';
import ManagePropertyReassignHseTenant from './components/admin/ManagePropertyReassignHseTenant.js';
import MessageNew from './components/admin/MessageNew.js';
import MessageWater from './components/admin/MessageWater.js';
import MessageTenant from './components/admin/MessageTenant.js';
import MessagePayments from './components/admin/MessagePayments.js';
import MessageBillReminders from './components/admin/MessageBillReminders.js';
import UpdateRentGarbage from './components/admin/UpdateRentGarbage.js';
import ManageMonthlyBills from './components/admin/ManageMonthlyBills.js';
import ManageNewTenantBills from './components/admin/ManageNewTenantBills.js';
import ManageRefunds from './components/admin/ManageRefunds.js';
import ManageDeposits from './components/admin/ManageDeposits.js';
import ManageLeases from './components/admin/ManageLeases.js';
import ManageChangePassword from './components/admin/ManageChangePassword.js';
import ManageProfile from './components/admin/ManageProfile.js';
import ManageUsers from './components/admin/ManageUsers.js';
import ManageUser from './components/admin/ManageUser.js';
import ManageUserNew from './components/admin/ManageUserNew.js';
import ManageSettings from './components/admin/ManageSettings.js';

import { ToastContainer } from "react-toastify";

  axios.defaults.baseURL=process.env.REACT_APP_BACKEND_API_URL;
  axios.defaults.headers.post['Content-Type']='application/json';
  axios.defaults.headers.post['Accept']='application/json';
  // axios.defaults.headers.post['content-type']='multipart/form-data';
  // Access-Control-Allow-Origin: http://www.example.org
  axios.defaults.withCredentials=true;
  axios.defaults.withXSRFToken = true;

  axios.interceptors.request.use(function (config){
    const token=localStorage.getItem('auth_token');
    config.headers.Authorization=token ? `Bearer ${token}` : ``;
    return config;
  });

  
const ENDPOINT=process.env.REACT_APP_NODE_API_URL;
  
const socket= io(ENDPOINT);

  
  
function App() {
  const [spinning,setSpinning]=useState(true)

  const [sitedata, setSiteData] =useState([]);

  const [loggedname, setLoggedName] =useState(localStorage.getItem('auth_name'));
  const [loggedtoken, setLoggedToken] =useState(localStorage.getItem('auth_token'));
  const [loggedrole, setLoggedRole] =useState(localStorage.getItem('auth_role'));

  const [loggedpermissions, setLoggedPermissions]=useState([]);
  const [loggedroles, setLoggedRoles]=useState([]);

  const [socketMessage, setsocketMessage]=useState([]);
  const socketarr = [];

  // useLayoutEffect(()=>{
  //   setSpinning(true)
    
   
  // },[])


  useEffect(()=>{
    setLoggedName(localStorage.getItem('auth_name'));
    setLoggedToken(localStorage.getItem('auth_token'));
    setLoggedRole(localStorage.getItem('auth_role'));

    axios.get('/v2/site/data')
      .then(response=>{
          if(response.data.status=== 200){
            setSiteData(response.data.agencydetail)
          }
          else{
            setSiteData([])
          }
      })
      .catch((error)=>{
        setSiteData([])
      })


  },[loggedtoken])

  useLayoutEffect(()=>{
    setLoggedName(localStorage.getItem('auth_name'));
    setLoggedToken(localStorage.getItem('auth_token'));
    setLoggedRole(localStorage.getItem('auth_role'));

    axios.get('/v2/site/data')
      .then(response=>{
          if(response.data.status=== 200){
            setSiteData(response.data.agencydetail)
            // console.log(sitedata)
          }
          else{
            setSiteData([])
          }
      })
      .catch((error)=>{
        setSiteData([])
      })
    setTimeout(()=>setSpinning(false))
  },[])


  return (
    <LoginContext.Provider value={{socket,socketMessage,socketarr, setsocketMessage,loggedname, setLoggedName,loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions,loggedroles, setLoggedRoles, loggedrole, setLoggedRole,sitedata,setSiteData}} >
    <ToastContainer
        position="top-right"
        autoClose={10000}
        theme="colored"
        />
      {spinning=== false ?(
        // <>
        // {!spinning && sitedata[0] && sitedata[0].islive !=0 ?
        <BrowserRouter>
          <Routes>
                <Route path='/' element={<Home />} />
                <Route path='/allproperties' element={<HomeProperties />} />
                <Route path='/aboutus' element={<HomeAboutus />} />
                <Route path='/contactus' element={<HomeContactus />} />
                <Route path='/gallery' element={<HomeGallery />} />
                <Route path='/login' element={<Login />} />

                <Route path='/maintainance' element={<PageIsNotLive />} />
                


                <Route path='/password/request' element={<PasswordReset />} />
                {/* <Route path='/email/verify/:id/:token' element={<VerifyEmail />} /> */}
                {/* <Route path='/email/setup/:id/:token' element={<EmailSetup />} /> */}

                <Route path='/profile/change-password' element={<ManageChangePassword />} />
                <Route path='/users/create' element={<ManageUserNew />} />
                <Route path='/users' element={<ManageUsers />} />
                <Route path='/profile' element={<ManageProfile />} />
                
                <Route path='/settings' element={<ManageSettings />} />
                
                <Route path='/user/:id' element={<ManageUser />} />
                


                <Route path='/dashboard' element={<Dashboard />} />
                <Route path='/properties/update/waterbill' element={<UpdateWaterbill />} />
                <Route path='/properties/update/waterbill/:id' element={<UpdateWaterbill />} />
                <Route path='/properties/update/waterbill/:id/:month' element={<UpdateWaterbill />} />


                <Route path='/properties/update/rentandgarbage' element={<UpdateRentGarbage />} />
                <Route path='/properties/update/rentandgarbage/:id' element={<UpdateRentGarbage />} />
                <Route path='/properties/update/rentandgarbage/:id/:month' element={<UpdateRentGarbage />} />
                


                <Route path='/properties/update/monthlybills' element={<ManageMonthlyBills />} />
                <Route path='/properties/update/monthlybills/:id' element={<ManageMonthlyBills />} />
                <Route path='/properties/update/monthlybills/:id/:month' element={<ManageMonthlyBills />} />

                <Route path='/properties/update/newtenant/monthlybills' element={<ManageNewTenantBills />} />
                <Route path='/properties/update/newtenant/monthlybills/:id' element={<ManageNewTenantBills />} />
                <Route path='/properties/update/newtenant/monthlybills/:id/:month' element={<ManageNewTenantBills />} />
                
                
                <Route path='/properties/mgr/refunds' element={<ManageRefunds />} />
                <Route path='/properties/mgr/refunds/:id' element={<ManageRefunds />} />
                <Route path='/properties/mgr/refunds/:id/:month' element={<ManageRefunds />} />
                

                <Route path='/properties/mgr/deposits' element={<ManageDeposits />} />
                <Route path='/properties/mgr/deposits/:id' element={<ManageDeposits />} />
                <Route path='/properties/mgr/deposits/:id/:month' element={<ManageDeposits />} />
                

                <Route path='/properties/mgr/leases' element={<ManageLeases />} />
                <Route path='/properties/mgr/leases/:id' element={<ManageLeases />} />
                <Route path='/properties/mgr/leases/:id/:month' element={<ManageLeases />} />
                

                 {/* refunds
                deposits
                leases */}
                
                
                
                <Route path='/properties/manage' element={<ManageProperties />} />
                <Route path='/properties/manage/:id' element={<ManageProperty />} />

                <Route path='/properties/house/:plot/:id' element={<ManagePropertyHouse />} />

                <Route path='/properties/mgr/tenants' element={<ManageTenants />} />
                <Route path='/properties/mgr/tenants/:id' element={<ManageTenant />} />

                <Route path='/properties/mgr/tenants/vacate/:house' element={<ManagePropertyVacateHseIDTenant />} />
                <Route path='/properties/mgr/tenants/:id/vacate' element={<ManagePropertyVacateHseTenantID />} />
                <Route path='/properties/mgr/tenants/:id/vacate/:house' element={<ManagePropertyVacateHseTenant />} />
                
                <Route path='/properties/mgr/tenants/:id/assign' element={<ManagePropertyAssignHseTenant />} />
                <Route path='/properties/mgr/tenants/:id/assign/:house' element={<ManagePropertyAssignHseTenant />} />

                <Route path='/properties/mgr/tenants/:id/addhouse' element={<ManagePropertyAddHseTenant />} />
                <Route path='/properties/mgr/tenants/:id/addhouse/:house' element={<ManagePropertyAddHseTenant />} />

             
                <Route path='/properties/mgr/tenants/:id/reassign' element={<ManagePropertyReassignHseTenant />} />
                <Route path='/properties/mgr/tenants/:id/reassign/:house/' element={<ManagePropertyReassignHseTenant />} />
                <Route path='/properties/mgr/tenants/:id/reassign/:house/:newhouse' element={<ManagePropertyReassignHseTenant />} />

                <Route path='/properties/mgr/tenants/category/:id' element={<ManageTenants />} />

                <Route path='/messages/new' element={<MessageNew />} />

                <Route path='/messages/water' element={<MessageWater />} />
                <Route path='/messages/water/:id' element={<MessageWater />} />
                <Route path='/messages/water/:id/:month' element={<MessageWater />} />
                
                <Route path='/messages/tenant' element={<MessageTenant />} />
                <Route path='/messages/tenant/:id' element={<MessageTenant />} />

                <Route path='/messages/payments' element={<MessagePayments />} />
                <Route path='/messages/payments/:id' element={<MessagePayments />} />
                <Route path='/messages/payments/:id/:month' element={<MessagePayments />} />

                <Route path='/messages/reminders' element={<MessageBillReminders />} />
                <Route path='/messages/reminders/:id' element={<MessageBillReminders />} />
                <Route path='/messages/reminders/:id/:month' element={<MessageBillReminders />} />


               
                
                
                
                <Route path='/*' element={<NotFound />} />

                
          </Routes>
        </BrowserRouter>
        // :
        //   <>
        //     <PageIsNotLive />
           
        //   </>
        // }
      ):(
        <main className='' style={{"margin": "2%","padding":"10%","minHeight": "calc(70vh - 3rem)","borderRadius":"10px"}}>
          <div className="container text-cennter elevation-2" style={{"margin": "1%","padding":"5%","borderRadius":"10px"}}>
            <div className="row justify-content-center">
                <Spinner animation="grow" variant="primary" size="lg" role="status"></Spinner>
                <h5 className='text-center' style={{"marginTop": "5%"}}>Please Wait...</h5>
                <Card.Body>
                  
                  <Placeholder as={Card.Text} animation="glow">
                    <Placeholder className='border-info' xs={12} style={{"padding": "2%"}} /> 
                    <Placeholder className='border-info' xs={4} /> <Placeholder className='border-info' xs={4} />{' '}
                    <Placeholder className='border-info' xs={12} /> <Placeholder  className='border-info'xs={8} />
                  </Placeholder>
                </Card.Body>
            </div>
          </div>
        </main>
      )}
      
    </LoginContext.Provider>
  );
}

export default App;
