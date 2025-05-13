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
import LoadingDetailsSpinner from '../spinners/LoadingDetailsSpinner';
import UsersLogsTable from './Tables/UsersLogsTable';
import RolesTable from './Tables/RolesTable';
import PermissionsTable from './Tables/PermissionsTable';
import RolePermissionsTable from './Tables/RolePermissionsTable';
import RoleUsersTable from './Tables/RoleUsersTable';
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

function ManageSettings(props) {
    document.title="Manage Settings";
    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
         
    const [loggedoff,setLoggedOff]=useState(false);
    const navigate=useNavigate();

    let par=useParams()

    const [id,setID]=useState((par.id)?par.id:'')

    const [closed,setClosed]=useState(false)
    const [isOpen,setIsOpen] = useState(false)

    
    const [isOpenRole,setIsOpenRole] = useState(false)
    const [isOpenPermission,setIsOpenPermission] = useState(false)


    const [isOpenRoleUser,setIsOpenRoleUser] = useState(false)
    const [isOpenPermissionUser,setIsOpenPermissionUser] = useState(false)

    const [isOpenRolePermission,setIsOpenRolePermission] = useState(false)
    const [isOpenPermissionRole,setIsOpenPermissionRole] = useState(false)


    const [loadingresok,setLoadingResOk]=useState('');
    const [loadingres,setLoadingRes]=useState('');
    const [loadingresapi,setLoadingResApi]=useState('');

 
    const [permissions, setPermissions]=useState([]);
    const [roles, setRoles]=useState([]);
    const [agency, setAgency]=useState([]);
    const [agencymsg, setAgencyMsg]=useState([]);
    const [accessToken,setAccessToken]=useState('')
    

    const [rolepermissions, setRolePermissions]=useState([]);
    const [roleusers, setRoleUsers]=useState([]);


    
    // const [loading,setLoading]=useState(true);
    const [loadingmonths,setLoadingMonths]=useState(true);
    const [loadingwater,setLoadingWater]=useState(false);
   

    const [loading,setLoading]=useState(false);
    const [loadingpage,setLoadingPage]=useState(false);
    const [loadingagency,setLoadingAgency]=useState(false);
    const [loadingagencymsg,setLoadingAgencyMsg]=useState(false);
    const [currentuser,setCurrentUser]=useState([]);
    const [currentrole,setCurrentRole]=useState([]);
    const [currentpermission,setCurrentPermission]=useState([]);
    const [currentuserlogs,setCurrentUserLogs]=useState([]);
    const [users,setUsers]=useState([]);
    const [waterbilldata, setWaterbillData] = useState([]);
    const [viewall, setViewAll] = useState(false);

    
    const [loglimit,setLogLimit]=useState(0)

   
    

    const [formdata,setFormData]=useState({
        Fullname:'',
        Rolename:'',
        Permissionname:'',
        Username:'',
        email:'',
        Idno:'',
        Phone:'',
        Names:'',
        Address:'',
        Town:'',
        EmailAddress:'',
        islive:'',
        apiKey:'',
        username:'',
        sendfrom:'',
        sandapiKey:'',
        sandusername:'',
        sandsendfrom:'',
        error_list:[],
    });
        // useEffect( () =>{
        //     socket.on('load_credit_balance', (msg) =>{
        //         loadAppData();
        //     })
    
        // }, []);
    

    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            loadRolesPermissions();
            loadUsers();
            loadAgency();
            loadAgencyMsg();
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
        result:[],
        roles:[],
        permissions:[],
        rolepermissions:[],
        roleusers:[],
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

    const handleSearchPermissionChange =(e) =>{
        setLoadingMonths(true)
        const results=permissions.filter(role =>{
            if(e.target.value=== '') return permissions
            return role.name.toLowerCase().includes(e.target.value.toLowerCase())
        })
        setSearch({
            value:e.target.value,
            result:[],
            roles:[],
            rolepermissions:[],
            roleusers:[],
            permissions:results
        })
        setLoadingMonths(false)
    }

    const handleSearchRoleChange =(e) =>{
        setLoadingMonths(true)
        const results=roles.filter(permission =>{
            if(e.target.value=== '') return roles
            return permission.name.toLowerCase().includes(e.target.value.toLowerCase())
        })
        setSearch({
            value:e.target.value,
            result:[],
            roles:results,
            rolepermissions:[],
            roleusers:[],
            permissions:[]
        })
        setLoadingMonths(false)
    }


    
    const handleSearchRolePermissionChange =(e) =>{
        setLoadingMonths(true)
        const results=rolepermissions.filter(role =>{
            if(e.target.value=== '') return rolepermissions
            return role.name.toLowerCase().includes(e.target.value.toLowerCase())
        })
        setSearch({
            value:e.target.value,
            result:[],
            roles:[],
            permissions:[],
            roleusers:[],
            rolepermissions:results
        })
        setLoadingMonths(false)
    }

    const handleSearchRoleUserChange =(e) =>{
        setLoadingMonths(true)
        const results=roleusers.filter(user =>{
            if(e.target.value=== '') return roleusers
            return user.Fullname.toLowerCase().includes(e.target.value.toLowerCase())
        })
        setSearch({
            value:e.target.value,
            result:[],
            roles:[],
            permissions:[],
            roleusers:results,
            rolepermissions:[]
        })
        setLoadingMonths(false)
    }



    const loadRolesPermissions= ()=>{
        setLoading(true)
        axios.get('/v2/get/roles-permissions')
        .then(response=>{
            if(response.data.status=== 200){
                setPermissions(response.data.permissions)
                setRoles(response.data.roles)
                setLoading(false)
            }
            else{
                setPermissions([])
                setRoles([])
                setLoading(false)
            }
            

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

    const loadAgency= ()=>{
        setLoadingAgency(true)
        axios.get('/v2/get/agency')
        .then(response=>{
            if(response.data.status=== 200){
                setAgency(response.data.agency)
                setFormData({
                    Phone:response.data.agency.Phone,
                    Names:response.data.agency.Names,
                    Address:response.data.agency.Address,
                    Town:response.data.agency.Town,
                    EmailAddress:response.data.agency.Email,
                    islive:response.data.agency.islive
                })
                setLoadingAgency(false)
            }
            else{
                setAgency([])
                setFormData({
                    Phone:'',
                    Names:'',
                    Address:'',
                    Town:'',
                    EmailAddress:'',
                    islive:''
                })
                setLoadingAgency(false)
            }
        })
        .catch((error)=>{
            if(!localStorage.getItem("auth_token")){
                let ex=error['response'].data.message;
                if(ex==='Unauthenticated.'){
                    if(!localStorage.getItem("auth_token")){
                        setLoadingAgency(false)
                        setLoggedOff(true); 
                    }  
                    else{ 
                        setLoadingAgency(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }              
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingResOk("")
                setLoadingAgency(false)
            }
            else{
                let ex=error['response'].data.message;
                if(ex==='Unauthenticated.'){
                    setLoadingAgency(false)
                    setLoggedOff(true); 
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_name');
                }
                else{
                    setLoadingAgency(false)
                    Swal("Error",""+error,"error");
                }
            }
        })

    }

    const loadAgencyMsg= ()=>{
        setLoadingAgencyMsg(true)
        axios.get('/v2/get/agency/msg')
        .then(response=>{
            if(response.data.status=== 200){
                setAgencyMsg(response.data.msg)
                setFormData({
                    Names:response.data.msg.Names,
                    apiKey:response.data.msg.apiKey,
                    username:response.data.msg.username,
                    sendfrom:response.data.msg.sendfrom,
                    sandapiKey:response.data.msg.sandapiKey,
                    sandusername:response.data.msg.sandusername,
                    sandsendfrom:response.data.msg.sandsendfrom,
                    islive:response.data.msg.islive
                })
                setLoadingAgencyMsg(false)
            }
            else{
                setLoadingResApi(response.data.message)
                setAgencyMsg([])
                setFormData({
                    Names:'',
                    apiKey:'',
                    username:'',
                    sendfrom:'',
                    sandapiKey:'',
                    sandusername:'',
                    sandsendfrom:'',
                    islive:''
                })
                setLoadingAgencyMsg(false)
            }
        })
        .catch((error)=>{
            if(!localStorage.getItem("auth_token")){
                let ex=error['response'].data.message;
                if(ex==='Unauthenticated.'){
                    if(!localStorage.getItem("auth_token")){
                        setLoadingAgencyMsg(false)
                        setLoggedOff(true); 
                    }  
                    else{ 
                        setLoadingAgencyMsg(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }              
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingResOk("")
                setLoadingAgencyMsg(false)
            }
            else{
                let ex=error['response'].data.message;
                if(ex==='Unauthenticated.'){
                    setLoadingAgencyMsg(false)
                    setLoggedOff(true); 
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_name');
                }
                else{
                    setLoadingAgencyMsg(false)
                    Swal("Error",""+error,"error");
                }
            }
        })

    }

    const loadAccessToken= ()=>{
        axios.get('/v2/get/access/token')
        .then(response=>{
            setAccessToken(response.data)
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
                setLoadingResOk("")
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

    }

    const loadRegisterURL= ()=>{
        setLoadingAgencyMsg(true)
        axios.post('/v2/register-url')
        .then(response=>{
            if(response.data.status=== 200){
                setAgencyMsg(response.data.msg)
                setFormData({
                    Names:response.data.msg.Names,
                    apiKey:response.data.msg.apiKey,
                    username:response.data.msg.username,
                    sendfrom:response.data.msg.sendfrom,
                    sandapiKey:response.data.msg.sandapiKey,
                    sandusername:response.data.msg.sandusername,
                    sandsendfrom:response.data.msg.sandsendfrom,
                    islive:response.data.msg.islive
                })
                setLoadingAgencyMsg(false)
            }
            else{
                setAgencyMsg([])
                setFormData({
                    Names:'',
                    apiKey:'',
                    username:'',
                    sendfrom:'',
                    sandapiKey:'',
                    sandusername:'',
                    sandsendfrom:'',
                    islive:''
                })
                setLoadingAgencyMsg(false)
            }
        })
        .catch((error)=>{
            if(!localStorage.getItem("auth_token")){
                let ex=error['response'].data.message;
                if(ex==='Unauthenticated.'){
                    if(!localStorage.getItem("auth_token")){
                        setLoadingAgencyMsg(false)
                        setLoggedOff(true); 
                    }  
                    else{ 
                        setLoadingAgencyMsg(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }              
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingResOk("")
                setLoadingAgencyMsg(false)
            }
            else{
                let ex=error['response'].data.message;
                if(ex==='Unauthenticated.'){
                    setLoadingAgencyMsg(false)
                    setLoggedOff(true); 
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_name');
                }
                else{
                    setLoadingAgencyMsg(false)
                    Swal("Error",""+error,"error");
                }
            }
        })

    }

    


    const loadRolePermissions= (role)=>{
        setLoading(true)
        axios.get('/v2/get/rolepermissions/'+role.id)
        .then(response=>{
            if(response.data.status=== 200){
                setRolePermissions(response.data.rolepermissions)
                setLoading(false)
            }
            else{
                setRolePermissions([])
                setLoading(false)
            }
            

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

    const loadRoleUsers= (role)=>{
        setLoading(true)
        axios.get('/v2/get/roleusers/'+role.id)
        .then(response=>{
            if(response.data.status=== 200){
                setRoleUsers(response.data.roleusers)
                setLoading(false)
            }
            else{
                setRoleUsers([])
                setLoading(false)
            }

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

    const newRole= (e)=>{
        e.preventDefault();
        if(formdata.Rolename ===''){
            Swal("Enter Role Name","Please Enter Role Name","error");
            return;
        }
        
        
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")

        const form={
            id:(currentrole!=='')?currentrole.id:'',
            Rolename:formdata.Rolename,
        }
            axios.post('/v2/save/role',form,{
                headers:{
                    'content-type':'multipart/form-data'
                }
            })
            .then(response=>{
                if(response.data.status=== 200){
                    setLoadingResOk(response.data.message)
                    setLoadingRes("")
                    loadRolesPermissions();
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

    const newPermission= (e)=>{
        e.preventDefault();
        if(formdata.Permissionname ===''){
            Swal("Enter Permission Name","Please Enter Permission Name","error");
            return;
        }
        
        
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")

        const form={
            id:(currentpermission!=='')?currentpermission.id:'',
            Permissionname:formdata.Permissionname,
        }
            axios.post('/v2/save/permission',form,{
                headers:{
                    'content-type':'multipart/form-data'
                }
            })
            .then(response=>{
                if(response.data.status=== 200){
                    setLoadingResOk(response.data.message)
                    setLoadingRes("")
                    loadRolesPermissions();
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

    const updateRole = (role) => {
        setIsOpenRole(true)
        setIsOpenRolePermission(false)
        setIsOpenRoleUser(false)
        setCurrentRole(role)
        setFormData({
            Rolename:role.name,
            error_list:[],
        })
        
    };

    const updatePermission = (permission) => {
        setIsOpenPermission(true)
        setCurrentPermission(permission)
        setFormData({
            Permissionname:permission.name,
            error_list:[],
        })
        
    };

    const updateClose = (type) => {
        if(type==='role'){
            setIsOpenRole(false)
            setIsOpenRolePermission(false)
            setIsOpenRoleUser(false)
            setCurrentRole([])
            setFormData({
                Rolename:'',
                error_list:[],
            })
        }
        else if(type==='permission'){
            setIsOpenPermission(false)
            setIsOpenPermissionRole(false)
            setIsOpenPermissionUser(false)
            setCurrentPermission([])
            setFormData({
                Permissionname:'',
                error_list:[],
            })
        }
        setLoadingResOk("");
        setLoadingRes("");
    };

    
    
    const updateAssignRoleToUser = (role) => {
        setCurrentRole(role)
        setIsOpenRolePermission(false)
        setIsOpenRoleUser(true)
        setIsOpenRole(false)

        loadRoleUsers(role);
        setFormData({
            Rolename:role.name,
            error_list:[],
        })
        
    };

    const updateAssignRoleToPermission = (role) => {
        setCurrentRole(role)
        setIsOpenRolePermission(true)
        setIsOpenRoleUser(false)
        setIsOpenRole(false)
        loadRolePermissions(role);
        setFormData({
            Rolename:role.name,
            error_list:[],
        })
        
    };

    const updateAssignPermissionToUser = (permission) => {
        setIsOpenPermissionUser(true)
        setCurrentPermission(permission)
        setFormData({
            Permissionname:permission.name,
            error_list:[],
        })
        
    };

    const updateAssignPermissionToRole = (permission) => {
        setIsOpenPermissionRole(true)
        setCurrentPermission(permission)
        setFormData({
            Permissionname:permission.name,
            error_list:[],
        })
        
    };
    
    
    

    const deleteRole= (role,action)=>{
        const form={
            id:role.id,
            action:action,
        }

        let title=action+" "+role.name;
        let text="This will "+action+" this Role from the system.";
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
                axios.post('/v2/delete/role',form)
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
                    loadRolesPermissions();
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
                    }
                    else{
                        Swal("Error",""+error,"error");
                    }
                })
            }
            else{
                setLoading(false);
            }
        })

    }

    const deletePermission= (permission,action)=>{
        const form={
            id:permission.id,
        }

        let title=action+" "+permission.name;
        let text="This will "+action+" this Permission from the system.";
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
                axios.post('/v2/delete/permission',form)
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
                    loadRolesPermissions();
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
                    }
                    else{
                        Swal("Error",""+error,"error");
                    }
                    
                    setLoading(false)
                })
            }
            else{
                setLoading(false);
            }
        })

    }


    const assignPermission= (permission)=>{
        const form={
            roleid:currentrole.id,
            id:permission.id,
        }

        let title="Assign "+permission.name;
        let text="This will Assign Permission: "+permission.name+" to Role: "+currentrole.name;
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
                axios.post('/v2/permission/assign/role',form)
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
                    loadRolesPermissions();
                    loadRolePermissions(currentrole);
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
                    }
                    else{
                        Swal("Error",""+error,"error");
                    }
                    
                    setLoading(false)
                })
            }
            else{
                setLoading(false);
            }
        })

    }

    const removePermission= (permission)=>{
        const form={
            roleid:currentrole.id,
            id:permission.id,
        }

        let title="Remove "+permission.name;
        let text="This will Remove Permission: "+permission.name+" from Role: "+currentrole.name+" from the system.";
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
                axios.post('/v2/permission/remove/role',form)
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
                    loadRolesPermissions();
                    loadRolePermissions(currentrole);
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
                    }
                    else{
                        Swal("Error",""+error,"error");
                    }
                    
                    setLoading(false)
                })
            }
            else{
                setLoading(false);
            }
        })

    }


    const assignUser= (user)=>{
        const form={
            roleid:currentrole.id,
            id:user.id,
        }

        let title="Assign "+user.Fullname;
        let text="This will Assign User: "+user.Fullname+" to Role: "+currentrole.name;
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
                axios.post('/v2/user/assign/role',form)
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
                    loadRolesPermissions();
                    loadRoleUsers(currentrole);
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
                    }
                    else{
                        Swal("Error",""+error,"error");
                    }
                    
                    setLoading(false)
                })
            }
            else{
                setLoading(false);
            }
        })

    }

    const simulateC2B= (user)=>{
        const form={
            amount:1,
            account:23432,
        }

        let title="Simulate C2B ";
        let text="Receiving Amount:";
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
                axios.post('/v2/simulate/c2b',form)
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
                    loadRolesPermissions();
                    loadRoleUsers(currentrole);
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
                    }
                    else{
                        Swal("Error",""+error,"error");
                    }
                    
                    setLoading(false)
                })
            }
            else{
                setLoading(false);
            }
        })

    }

    

    const removeUser= (user)=>{
        const form={
            roleid:currentrole.id,
            id:user.id,
        }

        let title="Remove "+user.Fullname;
        let text="This will Remove User: "+user.Fullname+" from Role: "+currentrole.name+" from the system.";
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
                axios.post('/v2/user/remove/role',form)
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
                    loadRolesPermissions();
                    loadRoleUsers(currentrole);
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
                    }
                    else{
                        Swal("Error",""+error,"error");
                    }
                    
                    setLoading(false)
                })
            }
            else{
                setLoading(false);
            }
        })

    }


    const saveAgency= (e)=>{
        e.preventDefault();
        if(formdata.Names ===''){
            Swal("Enter Agency Name","Please Enter Agency Name","error");
            return;
        }
        if(formdata.Phone ===''){
            Swal("Enter Agency Phone Number","Please Enter Agency Phone Number","error");
            return;
        }
        if(formdata.Address ===''){
            Swal("Enter Agency Address","Please Enter Agency Address","error");
            return;
        }
        if(formdata.Town ===''){
            Swal("Enter Agency Town","Please Enter Agency Town","error");
            return;
        }
        if(formdata.EmailAddress ===''){
            Swal("Enter Agency Email Address","Please Enter Agency Email Address","error");
            return;
        }
        if(formdata.islive ===''){
            Swal("Enter Agency Live Status","Please Enter Agency Live Status","error");
            return;
        }
        
        
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")

        const form={
            id:(agency!=='')?agency.id:'',
            Names:formdata.Names,
            Phone:formdata.Phone,
            Address:formdata.Address,
            Town:formdata.Town,
            EmailAddress:formdata.EmailAddress,
            islive:formdata.islive,
        }
            axios.post('/v2/save/agency',form)
            .then(response=>{
                if(response.data.status=== 200){
                    setLoadingResOk(response.data.message)
                    setLoadingRes("")
                    loadAgency();
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

    const saveAgencyMsg= (e)=>{
        e.preventDefault();
        
        if(formdata.apiKey ===''){
            Swal("Enter Agency Msg Api key","Please Enter Agency Msg Api key","error");
            return;
        }
        if(formdata.username ===''){
            Swal("Enter Agency Msg Username","Please Enter Agency Msg Username","error");
            return;
        }
        if(formdata.sendfrom ===''){
            Swal("Enter Agency Msg From","Please Enter Agency Msg From","error");
            return;
        }
        if(formdata.sandapiKey ===''){
            Swal("Enter Agency Msg Sandbox Api Key","Please Enter Agency Msg Sandbox Api Key","error");
            return;
        }
        if(formdata.sandusername ===''){
            Swal("Enter Agency Sandbox Username","Please Enter Agency Sandbox Username","error");
            return;
        }
        
        if(formdata.sandsendfrom ===''){
            Swal("Enter Agency Sandbox Sendfrom","Please Enter Agency Sandbox Sendfrom","error");
            return;
        }
        
        
        
        setLoading(true);
        setLoadingRes("")
        setLoadingResOk("")

        const form={
            id:(agencymsg!=='')?agencymsg.id:'',
            apiKey:formdata.apiKey,
            username:formdata.username,
            sendfrom:formdata.sendfrom,
            sandapiKey:formdata.sandapiKey,
            sandusername:formdata.sandusername,
            sandsendfrom:formdata.sandsendfrom,
        }
            axios.post('/v2/save/agencymsg',form)
            .then(response=>{
                if(response.data.status=== 200){
                    setLoadingResOk(response.data.message)
                    setLoadingRes("")
                    loadAgencyMsg();
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
                                    
                                    <li className="breadcrumb-item active"> Manage Settings </li>
                                </ol>
                            </div>

                        

                        <div className="col-lg-12 m-0 p-0">
                            <div className="row m-0 p-0">

                                <div className="col-md-12 m-0 p-0 mt-2 mb-2">
                                    <div className="card border-none m-0 p-0" >
                                        <div className="card-header border-none m-0 p-1">
                                            <p className='text-center p-0 m-0'>
                                                
                                                
                                                
                                                <h5 className='m-0 p-0'>
                                                    Manage Settings
                                                    <>
                                                        {!loadingpage && currentuser &&
                                                            <Link to={'/users'} className='float-right text-sm m-0 p-0 text-info'><small><span className='fa fa-chevron-left'></span>  Back to Users</small></Link>
                                                        }
                                                    </>
                                                    
                                                    
                                                </h5>
                                            </p>
                                            
                                        </div>

                                        <div className="card-body text-center m-0 p-1">
                                            {loadingpage?
                                                <LoadingDetailsSpinner />
                                            :
                                            <div className="row justify-content-center p-0 pt-0">
                                                
                                                <div className="col-lg-12 ">
                                                    

                                                    {/* {!isOpenRole && !isOpenPermission && */}
                                                    <div className="row m-0 p-0">
                                                        <div className="col-md-12 m-0 p-0 mb-4">
                                                            <div class="card">
                                                                <div class="card-header p-1">
                                                                    <ul class="nav nav-pills m-0 p-0">
                                                                        <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm active" href="#userlogs" data-toggle="tab">Roles</a></li>
                                                                        {/* <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm" href="#logins" data-toggle="tab">Logins</a></li> */}
                                                                        <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm" href="#permissions" data-toggle="tab">Permissions</a></li>
                                                                        <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm" href="#site" data-toggle="tab">Site</a></li>
                                                                        <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm" href="#mpesa" data-toggle="tab">MPesa</a></li>
                                                                        <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm" href="#messages" data-toggle="tab">Messages</a></li>
                                                                        {/* <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm" href="#users" data-toggle="tab">Users</a></li> */}
                                                                        {/* <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm" href="#banks" data-toggle="tab">Banks</a></li>
                                                                        <li class="nav-item m-0 p-0"><a class="nav-link nav-link-pills m-0 ml-1 mr-1 p-1 pl-2 pr-2 text-sm" href="#mail" data-toggle="tab">Mail</a></li> */}
                                                                        <li className="nav-item m-0 p-0">
                                                                            <button className='btn btn-success m-1 p-0 pl-2 pr-2' onClick={()=>{setViewAll(!viewall)}}>{viewall?<small> <i className='fa fa-minus '></i> Mini Table</small>:<small> <i className='fa fa-plus '></i> Full Table</small>}</button>
                                                                        </li>
                                                                        {/* <li class="nav-item ml-auto">
                                                                            <span className="text-xs float-right m-0 p-1">
                                                                                <input onChange={handleSearchChange} value={search.value} className='border-info p-1 pt-0 pb-0' placeholder='Search House,Tenant, Year' />
                                                                            </span>
                                                                        </li> */}
                                                                    </ul>
                                                                </div>
                                                                <div class="card-body m-0 p-0">
                                                                    <div class="tab-content m-0 p-0">
                                                                        <div class="active tab-pane m-0 p-0" id="userlogs">
                                                                            {/* <h4 class="text-info text-center">User Logs & Activities </h4> */}
                                                                            {isOpenRole &&
                                                                            <div className="row m-0 p-0">
                                                                                <div className="col-md-12 m-0 p-0 mb-4 border-info">
                                                                                    {isOpenRole &&
                                                                                    <h5 className='m-0 mt-2 p-2 border-bottom'>
                                                                                        Update Role :: {!loadingpage && currentrole && currentrole.name}
                                                                                        <button type="button" onClick={()=>{updateClose('role')}}  className="float-right btn btn-outline-info border-none m-0 p-1">
                                                                                            <small><span className='fa fa-chevron-left'></span>  Back to Roles</small>
                                                                                        </button>
                                                                                    </h5>
                                                                                    }

                                                                                    <form className='justify-content-center mt-4 mb-4' onSubmit={newRole}>
                                                                                        {loadingpage?
                                                                                                <LoadingDetailsSpinner />
                                                                                            :
                                                                                            <>
                                                                                            {!loading && 
                                                                                                <>
                                                                                                    <div className="form-group row">
                                                                                                        <label for="Rolename" className="col-md-4 col-form-label text-md-right">Role Name <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <input id="Rolename" type="text" className="form-control" name="Rolename" placeholder="Rolename" value={formdata.Rolename} onChange={handleInputChange} required autoComplete="Rolename" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.Rolename && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.Rolename}</strong>
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
                                                                                                        <Spinner as="span" variant='info' animation="border" size="lg" role="status" aria-hidden="true"
                                                                                                        />
                                                                                                        <span className='text-info p-4' style={{"padding": "10px","display":"inline-block"}}>Saving Role...</span>
                                                                                                        
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
                                                                                                            <i className='fa fa-check'></i> Save Role
                                                                                                        </button>
                                                                                                        <button type="button" onClick={()=>{updateClose('role')}}  className="btn btn-outline-secondary border-danger m-1 pl-4 pr-4">
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
                                                                            }
                                                                            {isOpenRolePermission &&
                                                                            <div className="row m-0 p-0">
                                                                                <div className="col-md-12 m-0 p-0 mb-4 border-info">
                                                                                    <h5 className='m-0 mt-2 p-2 border-bottom'>
                                                                                        Assign Permissions to Role :: {!loadingpage && currentrole && currentrole.name}
                                                                                        <button type="button" onClick={()=>{updateClose('role')}}  className=" btn btn-outline-info border-none m-0 ml-2 p-1">
                                                                                            <small><span className='fa fa-chevron-left'></span>  Back to Roles</small>
                                                                                        </button>
                                                                                    </h5>
                                                                                    
                                                                                    <div className="row m-0 p-0">
                                                                                        <div className={`col-12 m-0 p-0 ${viewall?'tablemaxinfo':'tableinfo'}`}>
                                                                                        
                                                                                        {loading &&
                                                                                            <div className="col-12 text-left m-0 p-1 mt-1 mb-2">
                                                                                                <TableSmallSpinner />
                                                                                            </div>
                                                                                        }
                                        
                                                                                        
                                                                                        {!loading &&
                                                                                            <table border="1" className="table table-hover table-bordered text-xs" id="example1">
                                                                                                {currentrole  &&
                                                                                                    <thead  >
                                                                                                    <tr  style={{"color":"white","backgroundColor":"#77B5ED"}}>
                                                                                                        <th className='elevation-2 m-0 p-1'>Sno </th>
                                                                                                        <th className='elevation-2 m-0 p-1'>Permissions ({permissions  && permissions.length})</th>
                                                                                                        <th className='elevation-2 m-0 p-1'> 
                                                                                                        <span className="text-xs float-right m-0 p-1">
                                                                                                            <input onChange={handleSearchRolePermissionChange} value={search.value} className='border-info p-1 pt-0 pb-0' placeholder='Search Permission' />
                                                                                                        </span>

                                                                                                        </th>
                                                                                                    </tr></thead>
                                                                                                }
                                                                                                <tbody>
                                                                                                    <>
                                                                                                        {(search.value==='')?
                                                                                                            <>
                                                                                                                {rolepermissions  && rolepermissions.map((permission,key) => (
                                                                                                                    <RolePermissionsTable permission={permission} key={key} no={key} assignPermission={assignPermission} removePermission={removePermission} updateAssignPermissionToUser={updateAssignPermissionToUser} updateAssignPermissionToRole={updateAssignPermissionToRole} />
                                                                                                                ))
                                                                                                                }
                                                                                                            </>
                                                                                                        :
                                                                                                            <>
                                                                                                                {search.rolepermissions  && search.rolepermissions.map((permission,key) => (
                                                                                                                    <RolePermissionsTable permission={permission} key={key} no={key} assignPermission={assignPermission} removePermission={removePermission} updateAssignPermissionToUser={updateAssignPermissionToUser} updateAssignPermissionToRole={updateAssignPermissionToRole} />
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
                                                                            </div>
                                                                            }
                                                                            {isOpenRoleUser &&
                                                                            <div className="row m-0 p-0">
                                                                                <div className="col-md-12 m-0 p-0 mb-4 border-info">
                                                                                    <h5 className='m-0 mt-2 p-2 border-bottom'>
                                                                                    Assign Users to Role :: {!loadingpage && currentrole && currentrole.name}
                                                                                        <button type="button" onClick={()=>{updateClose('role')}}  className=" btn btn-outline-info border-none m-0 ml-2 p-1">
                                                                                            <small><span className='fa fa-chevron-left'></span>  Back to Roles</small>
                                                                                        </button>
                                                                                    </h5>

                                                                                    <div className="row m-0 p-0">
                                                                                        <div className={`col-12 m-0 p-0 ${viewall?'tablemaxinfo':'tableinfo'}`}>
                                                                                        
                                                                                        {loading &&
                                                                                            <div className="col-12 text-left m-0 p-1 mt-1 mb-2">
                                                                                                <TableSmallSpinner />
                                                                                            </div>
                                                                                        }
                                        
                                                                                        
                                                                                        {!loading &&
                                                                                            <table border="1" className="table table-hover table-bordered text-xs" id="example1">
                                                                                                {currentrole  &&
                                                                                                    <thead  >
                                                                                                    <tr  style={{"color":"white","backgroundColor":"#77B5ED"}}>
                                                                                                        <th className='elevation-2 m-0 p-1'>Sno </th>
                                                                                                        <th className='elevation-2 m-0 p-1'>User ({roleusers  && roleusers.length})</th>
                                                                                                        <th className='elevation-2 m-0 p-1'>Role </th>
                                                                                                        <th className='elevation-2 m-0 p-1'> 
                                                                                                        <span className="text-xs float-right m-0 p-1">
                                                                                                            <input onChange={handleSearchRoleUserChange} value={search.value} className='border-info p-1 pt-0 pb-0' placeholder='Search Permission' />
                                                                                                        </span>

                                                                                                        </th>
                                                                                                    </tr></thead>
                                                                                                }
                                                                                                <tbody>
                                                                                                    <>
                                                                                                        {(search.value==='')?
                                                                                                            <>
                                                                                                                {roleusers  && roleusers.map((user,key) => (
                                                                                                                    <RoleUsersTable user={user} key={key} no={key} assignUser={assignUser} removeUser={removeUser} />
                                                                                                                ))
                                                                                                                }
                                                                                                            </>
                                                                                                        :
                                                                                                            <>
                                                                                                                {search.roleusers  && search.roleusers.map((user,key) => (
                                                                                                                    <RoleUsersTable user={user} key={key} no={key} assignUser={assignUser} removeUser={removeUser} />
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
                                                                            </div>
                                                                            }
                                                                            {!isOpenRole && !isOpenRolePermission && !isOpenRoleUser &&
                                                                            <>
                                                                            <div className="mx-auto m-0 p-0">
                                                                                <button type="submit" className="btn btn-primary text-xs m-1 p-1 pl-2 pr-2"
                                                                                    onClick={()=>{loadRolesPermissions()}}> <i className='fa fa-sync-alt '></i> 
                                                                                </button>
                                                                                <button type="submit" className="btn btn-success text-xs m-1 p-1"
                                                                                    onClick={()=>{setIsOpenRole(!isOpenRole)}}> <i className='fa fa-plus '></i> New
                                                                                </button>
                                                                                <span className="text-xs float-right m-0 p-1">
                                                                                    <input onChange={handleSearchRoleChange} value={search.value} className='border-info p-1 pt-0 pb-0' placeholder='Search Permission' />
                                                                                </span>
                                                                            </div>
                                                                            <div className="row m-0 p-0">
                                                                                <div className={`col-12 m-0 p-0 ${viewall?'tablemaxinfo':'tableinfo'}`}>
                                                                                
                                                                                {loading &&
                                                                                    <div className="col-12 text-left m-0 p-1 mt-1 mb-2">
                                                                                        <TableSmallSpinner />
                                                                                    </div>
                                                                                }
                                
                                                                                
                                                                                {!loading &&
                                                                                    <table border="1" className="table table-hover table-bordered text-xs" id="example1">
                                                                                        {currentuserlogs  &&
                                                                                            <thead  >
                                                                                            <tr  style={{"color":"white","backgroundColor":"#77B5ED"}}>
                                                                                                <th className='elevation-2 m-0 p-1'>Sno </th>
                                                                                                <th className='elevation-2 m-0 p-1'>Role ({roles  && roles.length})</th>
                                                                                                <th className='elevation-2 m-0 p-1'> </th>
                                                                                            </tr></thead>
                                                                                        }
                                                                                        <tbody>
                                                                                            <>
                                                                                                {(search.value==='')?
                                                                                                    <>
                                                                                                        {roles  && roles.map((role,key) => (
                                                                                                            <RolesTable role={role} key={key} no={key} updateRole={updateRole} deleteRole={deleteRole}  updateAssignRoleToUser={updateAssignRoleToUser} updateAssignRoleToPermission={updateAssignRoleToPermission}/>
                                                                                                        ))
                                                                                                        }
                                                                                                    </>
                                                                                                :
                                                                                                    <>
                                                                                                        {search.roles  && search.roles.map((role,key) => (
                                                                                                            <RolesTable role={role} key={key} no={key} updateRole={updateRole} deleteRole={deleteRole}  updateAssignRoleToUser={updateAssignRoleToUser} updateAssignRoleToPermission={updateAssignRoleToPermission}/>
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
                                                                            </>
                                                                            }
                                                                        </div>
                                                                        {/* <div class="tab-pane" id="logins">
                                                                            <h4 class="text-info text-center">User Logins </h4>
                                                                        </div> */}
                                                                        <div class="tab-pane" id="permissions">
                                                                        {isOpenPermission ?
                                                                            <div className="row m-0 p-0">
                                                                                <div className="col-md-12 m-0 p-0 mb-4 border-info">
                                                                                    {isOpenPermission &&
                                                                                        <h5 className='m-0 mt-2 p-2 border-bottom'>
                                                                                            Update Permission :: {!loadingpage && currentpermission && currentpermission.name}
                                                                                            <button type="button" onClick={()=>{updateClose('permission')}}  className="float-right btn btn-outline-info border-none m-0 p-1">
                                                                                                <small><span className='fa fa-chevron-left'></span>  Back to Permissions</small>
                                                                                            </button>
                                                                                        </h5>
                                                                                    }
                                                                                    <form className='justify-content-center mt-4 mb-4' onSubmit={newPermission}>
                                                                                        {loadingpage?
                                                                                                <LoadingDetailsSpinner />
                                                                                            :
                                                                                            <>
                                                                                            {!loading && 
                                                                                                <>
                                                                                                    <div className="form-group row">
                                                                                                        <label for="Permissionname" className="col-md-4 col-form-label text-md-right">Permission Name <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <input id="Permissionname" type="text" className="form-control" name="Permissionname" placeholder="Permissionname" value={formdata.Permissionname} onChange={handleInputChange} required autoComplete="Permissionname" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.Permissionname && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.Permissionname}</strong>
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
                                                                                                        <span className='text-info p-4' style={{"padding": "10px","display":"inline-block"}}>Saving Permission...</span>
                                                                                                        
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
                                                                                                            <i className='fa fa-check'></i> Save Permission
                                                                                                        </button>
                                                                                                        <button type="button" onClick={()=>{updateClose('permission')}}  className="btn btn-outline-secondary border-danger m-1 pl-4 pr-4">
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
                                                                            <>
                                                                            <div className="mx-auto m-0 p-0">
                                                                                <button type="submit" className="btn btn-primary text-xs m-1 p-1  pl-2 pr-2"
                                                                                    onClick={()=>{loadRolesPermissions()}}> <i className='fa fa-sync-alt '></i>
                                                                                </button>
                                                                                
                                                                                <button type="submit" className="btn btn-success text-xs m-1 p-1"
                                                                                    onClick={()=>{setIsOpenPermission(!isOpenPermission)}}> <i className='fa fa-plus '></i> New
                                                                                </button>

                                                                                <span className="text-xs float-right m-0 p-1">
                                                                                    <input onChange={handleSearchPermissionChange} value={search.value} className='border-info p-1 pt-0 pb-0' placeholder='Search Permission' />
                                                                                </span>
                                                                            </div>
                                                                            <div className="row m-0 p-0">
                                                                                <div className={`col-12 m-0 p-0 ${viewall?'tablemaxinfo':'tableinfo'}`}>
                                                                                
                                                                                {loading &&
                                                                                    <div className="col-12 text-left m-0 p-1 mt-1 mb-2">
                                                                                        <TableSmallSpinner />
                                                                                    </div>
                                                                                }
                                
                                                                                
                                                                                {!loading &&
                                                                                    <table border="1" className="table table-hover table-bordered text-xs" id="example1">
                                                                                        {currentuserlogs  &&
                                                                                            <thead  >
                                                                                            <tr  style={{"color":"white","backgroundColor":"#77B5ED"}}>
                                                                                                <th className='elevation-2 m-0 p-1'>Sno </th>
                                                                                                <th className='elevation-2 m-0 p-1'>Permissions ({permissions  && permissions.length})</th>
                                                                                                
                                                                                                <th className='elevation-2 m-0 p-1'>Roles </th>
                                                                                                <th className='elevation-2 m-0 p-1'> </th>
                                                                                            </tr></thead>
                                                                                        }
                                                                                        <tbody>
                                                                                            <>
                                                                                                {(search.value==='')?
                                                                                                    <>
                                                                                                        {permissions  && permissions.map((permission,key) => (
                                                                                                            <PermissionsTable permission={permission} key={key} no={key} updatePermission={updatePermission} deletePermission={deletePermission} updateAssignPermissionToUser={updateAssignPermissionToUser} updateAssignPermissionToRole={updateAssignPermissionToRole} />
                                                                                                        ))
                                                                                                        }
                                                                                                    </>
                                                                                                :
                                                                                                    <>
                                                                                                        {search.permissions  && search.permissions.map((permission,key) => (
                                                                                                            <PermissionsTable permission={permission} key={key} no={key} updatePermission={updatePermission} deletePermission={deletePermission} updateAssignPermissionToUser={updateAssignPermissionToUser} updateAssignPermissionToRole={updateAssignPermissionToRole}/>
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
                                                                            </>
                                                                            }
                                                                        </div>
                                                                        {/* <div class="tab-pane" id="users">
                                                                            <h4>Coming Up</h4>
                                                                        </div> */}
                                                                        <div class="tab-pane" id="site">
                                                                            <div className="row m-0 p-0">
                                                                                <div className="col-md-12 m-0 p-0 mb-4 border-info">
                                                                                        <h5 className='m-0 mt-2 p-2 border-bottom'>
                                                                                            Update Agency Details
                                                                                            <button type="submit" className="btn btn-primary text-xs m-1 p-1 pl-2 pr-2"
                                                                                                onClick={()=>{loadAgency()}}> <i className='fa fa-sync-alt '></i> 
                                                                                            </button>
                                                                                        </h5>
                                                                                    <form className='justify-content-center mt-4 mb-4' onSubmit={saveAgency}>
                                                                                        {loadingagency?
                                                                                                <LoadingDetailsSpinner />
                                                                                            :
                                                                                            <>
                                                                                            {!loading && 
                                                                                                <>
                                                                                                    <div className="form-group row">
                                                                                                        <label for="Names" className="col-md-4 col-form-label text-md-right">Agency Name <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <input id="Names" type="text" className="form-control" name="Names" placeholder="Names" value={formdata.Names} onChange={handleInputChange} required autoComplete="Names" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.Names && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.Names}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div className="form-group row">
                                                                                                        <label for="Address" className="col-md-4 col-form-label text-md-right">Agency Address <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <input id="Address" type="text" className="form-control" name="Address" placeholder="Address" value={formdata.Address} onChange={handleInputChange} required autoComplete="Address" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.Address && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.Address}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>


                                                                                                    <div className="form-group row">
                                                                                                        <label for="Town" className="col-md-4 col-form-label text-md-right">Agency Town <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <input id="Town" type="text" className="form-control" name="Town" placeholder="Town" value={formdata.Town} onChange={handleInputChange} required autoComplete="Town" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.Town && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.Town}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div className="form-group row">
                                                                                                        <label for="Phone" className="col-md-4 col-form-label text-md-right">Agency Phone <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <input id="Phone" type="text" className="form-control" name="Phone" placeholder="Phone" value={formdata.Phone} onChange={handleInputChange} required autoComplete="Phone" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.Phone && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.Phone}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div className="form-group row">
                                                                                                        <label for="EmailAddress" className="col-md-4 col-form-label text-md-right">Email <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <input id="EmailAddress" type="text" className="form-control" name="EmailAddress" placeholder="EmailAddress" value={formdata.EmailAddress} onChange={handleInputChange} required autoComplete="EmailAddress" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.EmailAddress && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.EmailAddress}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div className="form-group row">
                                                                                                        <label for="islive" className="col-md-4 col-form-label text-md-right">Is Live <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <label className={`m-0 p-1 text-left border-info mr-1 ml-1 pl-2 pr-2 ${formdata.islive==="1"?'bg-success text-white':''}`} style={{"cursor": "pointer"}}>
                                                                                                                <input type="radio" required checked={formdata.islive==="1"?"true":""} onChange={handleInputChange} className="" name="islive" value="1" autoComplete="islive"/> Live
                                                                                                            </label>
                                                                                                            <label className={`m-0 p-1 text-left border-info mr-1 ml-1 pl-2 pr-2 ${formdata.islive==="0"?'bg-danger text-white':''}`} style={{"cursor": "pointer"}}>
                                                                                                                <input type="radio" required checked={formdata.islive==="0"?"true":""} onChange={handleInputChange} className="" name="islive" value="0" autoComplete="islive"/> Offline
                                                                                                            </label>
                                                                                                            {formdata.error_list && formdata.error_list.islive && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.islive}</strong>
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
                                                                                                        <span className='text-info p-4' style={{"padding": "10px","display":"inline-block"}}>Saving Agency Info...</span>
                                                                                                        
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
                                                                                                            <i className='fa fa-check'></i> Update Agency Info
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
                                                                        <div class="tab-pane" id="banks">
                                                                            <h4>Coming Up</h4>
                                                                        </div>
                                                                        <div class="tab-pane" id="mail">
                                                                            <h4>Coming Up</h4>
                                                                        </div>
                                                                        <div class="tab-pane" id="mpesa">
                                                                            <div className="row m-0 p-0">
                                                                                <div className="col-md-12 m-0 p-0 mb-4 border-info">
                                                                                        <h5 className='m-0 mt-2 p-2 border-bottom'>
                                                                                           Update MPesa Configuration Details
                                                                                            <button type="submit" className="btn btn-primary text-xs m-1 p-1 pl-2 pr-2"
                                                                                                onClick={()=>{loadAccessToken()}}> <i className='fa fa-sync-alt '></i> Get Access Token
                                                                                            </button>

                                                                                            <button type="submit" className="btn btn-primary text-xs m-1 p-1 pl-2 pr-2"
                                                                                                onClick={()=>{loadRegisterURL()}}> <i className='fa fa-sync-alt '></i> Register URLs
                                                                                            </button>

                                                                                            
                                                                                            <button type="submit" className="btn btn-primary text-xs m-1 p-1 pl-2 pr-2"
                                                                                                onClick={()=>{simulateC2B()}}> <i className='fa fa-sync-alt '></i> Simulate C2B
                                                                                            </button>

                                                                                            

                                                                                            <button type="submit" className="btn btn-primary text-xs m-1 p-1 pl-2 pr-2"
                                                                                                onClick={()=>{loadAgencyMsg()}}> <i className='fa fa-sync-alt '></i> 
                                                                                            </button>
                                                                                        </h5>
                                                                                    <form className='justify-content-center mt-4 mb-4' onSubmit={saveAgencyMsg}>
                                                                                        {loadingagencymsg?
                                                                                                <LoadingDetailsSpinner />
                                                                                            :
                                                                                            <>
                                                                                            {!loading && 
                                                                                                <>
                                                                                                    {/* <p className='text-indigo text-xs'>Credentials will be Choosen by the System According to 'islive' Status on 'Site' Tab</p> */}
                                                                                                    <div className="form-group row bg-success m-0 p-1">
                                                                                                        <label for="apiKey" className="col-md-4 col-form-label text-md-right">Access Token <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-8 ">
                                                                                                        <input id="accessToken" type="text" className="form-control" disabled name="accessToken" placeholder="Access Token" value={accessToken} required autoComplete="accessToken" autoFocus/>
                                                                                                           
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <p className='m-0 p-2'>Register Url Here</p>

                                                                                                    <div className="form-group row bg-success m-0 p-1">
                                                                                                        <label for="username" className="col-md-4 col-form-label text-md-right">Validation Url <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-8 ">
                                                                                                            <input id="username" type="text" className="form-control" name="username" placeholder="msgusername" value={formdata.username} onChange={handleInputChange} required autoComplete="username" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.username && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.username}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>


                                                                                                    <div className="form-group row bg-success m-0 p-1">
                                                                                                        <label for="sendfrom" className="col-md-4 col-form-label text-md-right">Confirmation Url<sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-8 ">
                                                                                                            <input id="sendfrom" type="text" className="form-control" name="sendfrom" placeholder="sendfrom" value={formdata.sendfrom} onChange={handleInputChange} required autoComplete="sendfrom" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.sendfrom && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.sendfrom}</strong>
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
                                                                                                        <span className='text-info p-4' style={{"padding": "10px","display":"inline-block"}}>Saving Agency Messaging Info...</span>
                                                                                                        
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
                                                                                                            <i className='fa fa-check'></i> Update Agency Messaging Info
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
                                                                        <div class="tab-pane" id="messages">
                                                                            <div className="row m-0 p-0">
                                                                                <div className="col-md-12 m-0 p-0 mb-4 border-info">
                                                                                        <h5 className='m-0 mt-2 p-2 border-bottom'>
                                                                                            Update Agency Message Details
                                                                                            <button type="submit" className="btn btn-primary text-xs m-1 p-1 pl-2 pr-2"
                                                                                                onClick={()=>{loadAgencyMsg()}}> <i className='fa fa-sync-alt '></i> 
                                                                                            </button>
                                                                                        </h5>
                                                                                    <form className='justify-content-center mt-4 mb-4' onSubmit={saveAgencyMsg}>
                                                                                        {loadingagencymsg?
                                                                                                <LoadingDetailsSpinner />
                                                                                            :
                                                                                            <>
                                                                                            {loadingresapi=="You do not have rights to Update Api Keys." &&
                                                                                            
                                                                                            <>
                                                                                            {!loading && 
                                                                                                <>
                                                                                                    <p className='text-indigo text-xs'>Credentials will be Choosen by the System According to 'islive' Status on 'Site' Tab</p>
                                                                                                    <div className="form-group row bg-success m-0 p-1">
                                                                                                        <label for="apiKey" className="col-md-4 col-form-label text-md-right">Message ApiKey <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                        <textarea  id="apiKey" type="text" className="form-control" name="apiKey" value={formdata.apiKey} onChange={handleInputChange} placeholder="Api Key"></textarea>
                                                                                                            {formdata.error_list && formdata.error_list.apiKey && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.apiKey}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div className="form-group row bg-success m-0 p-1">
                                                                                                        <label for="username" className="col-md-4 col-form-label text-md-right">Message Username <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <input id="username" type="text" className="form-control" name="username" placeholder="msgusername" value={formdata.username} onChange={handleInputChange} required autoComplete="username" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.username && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.username}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>


                                                                                                    <div className="form-group row bg-success m-0 p-1">
                                                                                                        <label for="sendfrom" className="col-md-4 col-form-label text-md-right">Message Sendfrom <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <input id="sendfrom" type="text" className="form-control" name="sendfrom" placeholder="sendfrom" value={formdata.sendfrom} onChange={handleInputChange} required autoComplete="sendfrom" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.sendfrom && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.sendfrom}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div className="form-group row bg-danger m-0 p-1">
                                                                                                        <label for="sandapiKey" className="col-md-4 col-form-label text-md-right">Sandbox ApiKey <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                        <textarea  id="sandapiKey" type="text" className="form-control" name="sandapiKey" value={formdata.sandapiKey} onChange={handleInputChange} placeholder="Sandbox APi Key"></textarea>
                                                                                                            {formdata.error_list && formdata.error_list.sandapiKey && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.sandapiKey}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div className="form-group row bg-danger m-0 p-1">
                                                                                                        <label for="sandusername" className="col-md-4 col-form-label text-md-right">Sandbox Username <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <input id="sandusername" type="text" className="form-control" name="sandusername" placeholder="msgsandusername" value={formdata.sandusername} onChange={handleInputChange} required autoComplete="sandusername" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.sandusername && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.sandusername}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>


                                                                                                    <div className="form-group row bg-danger m-0 p-1">
                                                                                                        <label for="sandsendfrom" className="col-md-4 col-form-label text-md-right">Sandbox SendFrom <sub className='text-red text-sm'>*</sub></label>
                                                                                                        <div className="col-md-6 ">
                                                                                                            <input id="sandsendfrom" type="text" className="form-control" name="sandsendfrom" placeholder="sandsendfrom" value={formdata.sandsendfrom} onChange={handleInputChange} required autoComplete="sandsendfrom" autoFocus/>
                                                                                                            {formdata.error_list && formdata.error_list.sandsendfrom && 
                                                                                                                <span className="help-block text-danger">
                                                                                                                    <strong>{formdata.error_list.sandsendfrom}</strong>
                                                                                                                </span>
                                                                                                            }
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    

                                                                                                </>
                                                                                            }
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
                                                                                                        <span className='text-info p-4' style={{"padding": "10px","display":"inline-block"}}>Saving Agency Messaging Info...</span>
                                                                                                        
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
                                                                                                    {loadingresapi=="You do not have rights to Update Api Keys." ?
                                                                                                        <span className="help-block text-danger">
                                                                                                            <strong>{loadingresapi!=='' && loadingresapi}</strong>
                                                                                                        </span>
                                                                                                        :
                                                                                                        <button type="submit" className="btn btn-outline-success border-info m-1 pl-4 pr-4">
                                                                                                            <i className='fa fa-check'></i> Update Agency Messaging Info
                                                                                                        </button>
                                                                                                    }
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
                                                    </div>
                                                    {/* } */}
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

export default ManageSettings;