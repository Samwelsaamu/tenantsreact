import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useState, useContext } from 'react';
import DashFooter from './DashFooter';
import Carousel from 'react-bootstrap/Carousel';
import Button from 'react-bootstrap/Button';
import Spinner from 'react-bootstrap/Spinner';
import Modal from 'react-bootstrap/Modal';

import Swal from 'sweetalert';
import axios from 'axios';

import Calendar from 'react-calendar';
import LoadWaterbill from './LoadWaterbill';
import ReLogin from '../home/ReLogin';
import LoadPayments from './LoadPayments';
import DailyCharts from './charts/DailyCharts';
import MonthlyCharts from './charts/MonthlyCharts';
import MonthlyNewlyCharts from './charts/MonthlyNewlyCharts';
import MonthlyWaterbillCharts from './charts/MonthlyWaterbillCharts';
import MonthlyRentGarbageCharts from './charts/MonthlyRentGarbageCharts';
import DownloadWaterbillModal from './DownloadWaterbillModal';
import DownloadPaymentModal from './DownloadPaymentModal';


import { LoginContext } from '../contexts/LoginContext';


function Dashboard() {
    document.title="DashboardHome";

    const {socket,loggedname, setLoggedName, loggedtoken, setLoggedToken, loggedpermissions, setLoggedPermissions, loggedroles, setLoggedRoles, loggedrole,setLoggedRole,sitedata,setSiteData} =useContext(LoginContext);
    const [closed,setClosed]=useState(true)
    const [isOpen, setIsOpen] = useState(false)

    const [loggedoff,setLoggedOff]=useState(false);
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

  
  const [loadingdownloadpayment,setLoadingDownloadPayment]=useState(false);
  const [loadingdownload,setLoadingDownload]=useState(false);
  const [loadingtotalhouses,setLoadingTotalHouses]=useState(false);
  const [loadingtotalproperties,setLoadingTotalProperties]=useState(false);
  const [loadingtotaltenants,setLoadingTotalTenants]=useState(false);
  const [loadingtotalvacanthouses,setLoadingTotalVacantHouses]=useState(false);
  const [loadingtotaloccupiedhouses,setLoadingTotalOccupiedHouses]=useState(false);
  const [loadingtotaldoublehouses,setLoadingTotalDoubleHouses]=useState(false);
  const [loadingdoublesdata,setLoadingDoublesData]=useState(false);

  const [loadingtotalhousesmonthly,setLoadingTotalHousesMonthly]=useState(false);
  const [loadingtotalpropertiesmonthly,setLoadingTotalPropertiesMonthly]=useState(false);
  const [loadingtotaltenantsmonthly,setLoadingTotalTenantsMonthly]=useState(false);
  const [loadingtotalvacanthousesmonthly,setLoadingTotalVacantHousesMonthly]=useState(false);
  const [loadingtotaloccupiedhousesmonthly,setLoadingTotalOccupiedHousesMonthly]=useState(false);
  const [loadingtotaldoublehousesmonthly,setLoadingTotalDoubleHousesMonthly]=useState(false);
  const [loadingdoublesdatamonthly,setLoadingDoublesDataMonthly]=useState(false);

  const [loadingtotalrent,setLoadingTotalRent]=useState(false);
  const [loadingtotalwaterbill,setLoadingTotalWaterbill]=useState(false);
  const [loadingtotalgarbage,setLoadingTotalGarbage]=useState(false);
  const [loadingtotalwaterd,setLoadingTotalWaterD]=useState(false);
  const [loadingtotalkplcd,setLoadingTotalKPLCD]=useState(false);
  const [loadingtotalrentd,setLoadingTotalRentD]=useState(false);
  const [loadingtotalarrears,setLoadingTotalArrears]=useState(false);
  const [loadingtotalexcess,setLoadingTotalExcess]=useState(false);
  const [loadingtotalbilled,setLoadingTotalBilled]=useState(false);
  const [loadingtotalpaid,setLoadingTotalPaid]=useState(false);
  const [loadingtotalbalance,setLoadingTotalBalance]=useState(false);
  const [loadingtotalrefund,setLoadingTotalRefund]=useState(false);
  const [loadingtotallease,setLoadingTotalLease]=useState(false);
  const [loadingtotalforwaded,setLoadingTotalForwaded]=useState(false);
  
//   const [loading,setLoading]=useState(true);
//   const [loading,setLoading]=useState(true);
//   const [loading,setLoading]=useState(true);
//   const [loading,setLoading]=useState(true);

const [monthlyinsights,setMonthlyInsights]=useState([])
const [monthlyinsightsstatus,setMonthlyInsightsStatus]=useState(false)
const [monthlyinsightserror,setMonthlyInsightsError]=useState('')

const [monthlyinsightsrentrefresh,setMonthlyInsightsRentRefresh]=useState(false)
const [monthlyinsightsrent,setMonthlyInsightsRent]=useState([])
const [monthlyinsightsstatusrent,setMonthlyInsightsStatusRent]=useState(false)
const [monthlyinsightserrorrent,setMonthlyInsightsErrorRent]=useState('')

  // State for date selected by user
  const [selectedDate, setSelectedDate] = useState(new Date());

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

    const [show,setShow]=useState(false);
    const [showdownloadpayments,setShowDownloadPayments]=useState(false);
    const [property,setProperty]=useState('');
    const [propertyid,setPropertyId]=useState('');

    const [showmonth,setShowMonth]=useState(false);

    const [dashrefresh,setDashStatsRefres]=useState(false);

    const [redirect,setRedirect]=useState(false);
    const [loading,setLoading]=useState(true);
    const [loadingmonths,setLoadingMonths]=useState(true);
    const [loadingwater,setLoadingWater]=useState(false);
    const [loadingpayment,setLoadingPayment]=useState(false);
    const [loadingstats,setLoadingStats]=useState(false);
    

    const [url,setUrl]=useState('');
    const [authname,setAuthName]=useState('');

    const [prevmonths,setPrevMonths]=useState([]);
    const [currentmonth,setCurrentMonth]=useState('');
    const [currentyear,setCurrentYear]=useState('');
    const [currentyearup,setCurrentYearUp]=useState('');
    const [currentyeardown,setCurrentYearDown]=useState('');
    const [currentmonthname,setCurrentMonthName]=useState('');
    
    const [selectedmonth,setSelectedMonth]=useState('');
    const [totalmonths,setTotalMonths]=useState(0);

    const [totalhouses,setTotalHouses]=useState(0);
    const [totalproperties,setTotalProperties]=useState(0);
    const [totaltenants,setTotalTenants]=useState(0);
    const [totalvacanthouses,setTotalVacantHouses]=useState(0);
    const [totaloccupiedhouses,setTotalOccupiedHouses]=useState(0);
    const [totaldoublehouses,setTotalDoubleHouses]=useState(0);
    const [doublesdata,setDoublesData]=useState([]);

    const [totalhousesmonthly,setTotalHousesMonthly]=useState(0);
    const [totalpropertiesmonthly,setTotalPropertiesMonthly]=useState(0);
    const [totaltenantsmonthly,setTotalTenantsMonthly]=useState(0);
    const [totalvacanthousesmonthly,setTotalVacantHousesMonthly]=useState(0);
    const [totaloccupiedhousesmonthly,setTotalOccupiedHousesMonthly]=useState(0);
    const [totaldoublehousesmonthly,setTotalDoubleHousesMonthly]=useState(0);
    const [doublesdatamonthly,setDoublesDataMonthly]=useState([]);

    const [totalrent,setTotalRent]=useState(0);
    const [totalwaterbill,setTotalWaterbill]=useState(0);
    const [totalgarbage,setTotalGarbage]=useState(0);
    const [totalwaterd,setTotalWaterD]=useState(0);
    const [totalkplcd,setTotalKPLCD]=useState(0);
    const [totalrentd,setTotalRentD]=useState(0);
    const [totallease,setTotalLease]=useState(0);
    const [totalarrears,setTotalArrears]=useState(0);
    const [totalexcess,setTotalExcess]=useState(0);
    const [totalbilled,setTotalBilled]=useState(0);
    const [totalpaid,setTotalPaid]=useState(0);
    const [totalbalance,setTotalBalance]=useState(0);
    const [totalrefund,setTotalRefund]=useState(0);
    const [totalforwaded,setTotalForwaded]=useState(0);

    const [waterbill,setWaterbill]=useState([]);
    const [stats,setStats]=useState([]);
    const [totalwaterbillproperties,setTotalWaterbillProperties]=useState(0);
    const [payments,setPayments]=useState([]);

    // /properties/dash/payments
    // /properties/dash/water
    // /properties/dash/water/prev
    // /properties/update/waterbill/1/2022 12
    // /properties/updateload/waterbill/1/2022 10

    useEffect( () =>{
        socket.on('tenant_vacated', (msg) =>{
            setDashStatsRefres(true);
        })

    }, []);

    useEffect(()=>{
        axios.get('/v2/isAuthencticated')
            .then(response=>{
                if(response.data.status=== 200){
                    setAuthName(localStorage.getItem("auth_name")); 
                }
                setLoading(false)
            })
            .catch((error)=>{
                setAuthName('');
                setRedirect(true);
                setUrl('/login');
            })

            return () =>{
                setAuthName('');
                setRedirect(true);
                setUrl('/login');
            };
    },[])


     useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            loadPropertyStats();
            loadPropertyStatsMonthly();
        }
        return ()=>{
            doneloading=false;
        }
    },[loggedoff, selectedmonth,dashrefresh])
    


    
    
// load previous and current month
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingMonths(true)
            setPrevMonths([]);
            setCurrentMonth('');
            setCurrentMonthName('');
            setTotalMonths(0);
        }
        const getPrevMonths = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/v2/dash/month/prev/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setPrevMonths(response.data.previousmonths);
                        setCurrentMonth(response.data.currentdate);
                        setCurrentMonthName(response.data.currentmonthname);
                        setSelectedMonth(response.data.selectedmonth);
                        setTotalMonths(response.data.totals);
                        setCurrentYear(response.data.yearly)
                        setLoadingMonths(false)
                        
                    }
                    else{
                        setPrevMonths([]);
                        setCurrentMonth('');
                        setCurrentMonthName('');
                        setTotalMonths(0);
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
                    setPrevMonths([]);
                    setCurrentMonth('');
                    setCurrentMonthName('');
                    setTotalMonths(0);
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
                        setPrevMonths([]);
                        setCurrentMonth('');
                        setCurrentMonthName('');
                        setTotalMonths(0);
                        setLoadingMonths(false)
                    }
                }

            })
        };
        getPrevMonths();

        return ()=>{
            doneloading=false;
        }
    },[loggedoff])

    // load property stats
    // useEffect(()=>{
    //     let doneloading=true;
    //     if (doneloading) {
    //         setLoadingTotalProperties(true)
    //         setTotalProperties(0);

    //         setLoadingTotalHouses(true)
    //         setTotalHouses(0);

    //         setLoadingTotalTenants(true)
    //         setTotalTenants(0);

    //         setLoadingTotalOccupiedHouses(true)
    //         setTotalOccupiedHouses(0);

    //         setLoadingTotalVacantHouses(true)
    //         setTotalVacantHouses(0);

    //         setLoadingTotalDoubleHouses(true)
    //         setTotalDoubleHouses(0);
    //         setDoublesData([])
            
    //     }
    //     const getDashStats = async (e) => { 
    //         const sno=(selectedmonth==='')?0:selectedmonth;
    //         const month=(!loadingmonths)?prevmonths[sno].month:0;
    //         await axios.get(`/v2/dash/stats/property/${month}`)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     setTotalProperties(response.data.totalproperties);
    //                     setLoadingTotalProperties(false)

    //                     setTotalHouses(response.data.totalhouses);
    //                     setLoadingTotalHouses(false)

    //                     setTotalTenants(response.data.totaltenants);
    //                     setLoadingTotalTenants(false)

    //                     setTotalOccupiedHouses(response.data.totaloccupiedhouses);
    //                     setLoadingTotalOccupiedHouses(false)

    //                     setTotalVacantHouses(response.data.totalvacanthouses);
    //                     setLoadingTotalVacantHouses(false)

    //                     setTotalDoubleHouses(response.data.totaldoublehouses);
    //                     setDoublesData(response.data.doublehouses);
    //                     setLoadingTotalDoubleHouses(false)
                        
    //                 }
    //                 else if(response.data.status=== 401){
    //                     setLoggedOff(true);    
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else if(response.data.status=== 500){
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else{
    //                     setLoadingTotalProperties(false)
    //                 }
    //                 setLoadingTotalProperties(false)
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
    //                 setLoadingTotalProperties(false)
    //                 setTotalProperties(0)
                    
    //                 setLoadingTotalHouses(true)
    //                 setTotalHouses(0);

    //                 setLoadingTotalTenants(true)
    //                 setTotalTenants(0);

    //                 setLoadingTotalOccupiedHouses(true)
    //                 setTotalOccupiedHouses(0);

    //                 setLoadingTotalVacantHouses(true)
    //                 setTotalVacantHouses(0);

    //                 setLoadingTotalDoubleHouses(true)
    //                 setTotalDoubleHouses(0);
    //                 setDoublesData([])
    //             }
    //             else{
    //                 let ex=error['response'].data.message;
    //                 if(ex==='Unauthenticated.'){
    //                     setLoggedOff(true); 
    //                     localStorage.removeItem('auth_token');
    //                     localStorage.removeItem('auth_name');
    //                 }
    //                 else{
    //                     setLoadingTotalProperties(false)
    //                     setTotalProperties(0)

    //                     setLoadingTotalHouses(true)
    //                     setTotalHouses(0);

    //                     setLoadingTotalTenants(true)
    //                     setTotalTenants(0);

    //                     setLoadingTotalOccupiedHouses(true)
    //                     setTotalOccupiedHouses(0);

    //                     setLoadingTotalVacantHouses(true)
    //                     setTotalVacantHouses(0);

    //                     setLoadingTotalDoubleHouses(true)
    //                     setTotalDoubleHouses(0);
    //                     setDoublesData([])
                        

    //                     Swal("Error",""+error,"error");
    //                 }
    //             }
                
    //         })
    //     };
    //     getDashStats();

    //     return ()=>{
    //         doneloading=false;
    //     }
    // },[dashrefresh])

    const loadPropertyStats = () =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalProperties(true)
            setTotalProperties(0);

            setLoadingTotalHouses(true)
            setTotalHouses(0);

            setLoadingTotalTenants(true)
            setTotalTenants(0);

            setLoadingTotalOccupiedHouses(true)
            setTotalOccupiedHouses(0);

            setLoadingTotalVacantHouses(true)
            setTotalVacantHouses(0);

            setLoadingTotalDoubleHouses(true)
            setTotalDoubleHouses(0);
            setDoublesData([])
            
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths && prevmonths[sno].month:0;
            await axios.get(`/v2/dash/stats/property/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalProperties(response.data.totalproperties);
                        setLoadingTotalProperties(false)

                        setTotalHouses(response.data.totalhouses);
                        setLoadingTotalHouses(false)

                        setTotalTenants(response.data.totaltenants);
                        setLoadingTotalTenants(false)

                        setTotalOccupiedHouses(response.data.totaloccupiedhouses);
                        setLoadingTotalOccupiedHouses(false)

                        setTotalVacantHouses(response.data.totalvacanthouses);
                        setLoadingTotalVacantHouses(false)

                        setTotalDoubleHouses(response.data.totaldoublehouses);
                        setDoublesData(response.data.doublehouses);
                        setLoadingTotalDoubleHouses(false)
                        
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalProperties(false)
                    }
                    setLoadingTotalProperties(false)
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
                    setLoadingTotalProperties(false)
                    setTotalProperties(0)
                    
                    setLoadingTotalHouses(true)
                    setTotalHouses(0);

                    setLoadingTotalTenants(true)
                    setTotalTenants(0);

                    setLoadingTotalOccupiedHouses(true)
                    setTotalOccupiedHouses(0);

                    setLoadingTotalVacantHouses(true)
                    setTotalVacantHouses(0);

                    setLoadingTotalDoubleHouses(true)
                    setTotalDoubleHouses(0);
                    setDoublesData([])
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingTotalProperties(false)
                        setTotalProperties(0)

                        setLoadingTotalHouses(true)
                        setTotalHouses(0);

                        setLoadingTotalTenants(true)
                        setTotalTenants(0);

                        setLoadingTotalOccupiedHouses(true)
                        setTotalOccupiedHouses(0);

                        setLoadingTotalVacantHouses(true)
                        setTotalVacantHouses(0);

                        setLoadingTotalDoubleHouses(true)
                        setTotalDoubleHouses(0);
                        setDoublesData([])
                        

                        Swal("Error",""+error,"error");
                    }
                }
                
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    }

    const loadPropertyStatsMonthly =() =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalPropertiesMonthly(true)
            setTotalPropertiesMonthly(0);

            setLoadingTotalHousesMonthly(true)
            setTotalHousesMonthly(0);

            setLoadingTotalTenantsMonthly(true)
            setTotalTenantsMonthly(0);

            setLoadingTotalOccupiedHousesMonthly(true)
            setTotalOccupiedHousesMonthly(0);

            setLoadingTotalVacantHousesMonthly(true)
            setTotalVacantHousesMonthly(0);

            setLoadingTotalDoubleHousesMonthly(true)
            setTotalDoubleHousesMonthly(0);
            setDoublesDataMonthly([])
            
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths && prevmonths[sno].month:0;
            await axios.get(`/v2/dash/stats/propertymonthly/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalPropertiesMonthly(response.data.totalproperties);
                        setLoadingTotalPropertiesMonthly(false)

                        setTotalHousesMonthly(response.data.totalhouses);
                        setLoadingTotalHousesMonthly(false)

                        setTotalTenantsMonthly(response.data.totaltenants);
                        setLoadingTotalTenantsMonthly(false)

                        setTotalOccupiedHousesMonthly(response.data.totaloccupiedhouses);
                        setLoadingTotalOccupiedHousesMonthly(false)

                        setTotalVacantHousesMonthly(response.data.totalvacanthouses);
                        setLoadingTotalVacantHousesMonthly(false)

                        setTotalDoubleHousesMonthly(response.data.totaldoublehouses);
                        setDoublesDataMonthly(response.data.doublehouses);
                        setLoadingTotalDoubleHousesMonthly(false)
                        
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalPropertiesMonthly(false)
                    }
                    setLoadingTotalPropertiesMonthly(false)
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
                    setLoadingTotalPropertiesMonthly(false)
                    setTotalPropertiesMonthly(0)
                    
                    setLoadingTotalHousesMonthly(true)
                    setTotalHousesMonthly(0);

                    setLoadingTotalTenantsMonthly(true)
                    setTotalTenantsMonthly(0);

                    setLoadingTotalOccupiedHousesMonthly(true)
                    setTotalOccupiedHousesMonthly(0);

                    setLoadingTotalVacantHousesMonthly(true)
                    setTotalVacantHousesMonthly(0);

                    setLoadingTotalDoubleHousesMonthly(true)
                    setTotalDoubleHousesMonthly(0);
                    setDoublesDataMonthly([])
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingTotalPropertiesMonthly(false)
                        setTotalPropertiesMonthly(0)

                        setLoadingTotalHousesMonthly(true)
                        setTotalHousesMonthly(0);

                        setLoadingTotalTenantsMonthly(true)
                        setTotalTenantsMonthly(0);

                        setLoadingTotalOccupiedHousesMonthly(true)
                        setTotalOccupiedHousesMonthly(0);

                        setLoadingTotalVacantHousesMonthly(true)
                        setTotalVacantHousesMonthly(0);

                        setLoadingTotalDoubleHousesMonthly(true)
                        setTotalDoubleHousesMonthly(0);
                        setDoublesDataMonthly([])
                        

                        Swal("Error",""+error,"error");
                    }
                }
                
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    }

    // load property monthly stats
    // useEffect(()=>{
    //     let doneloading=true;
    //     if (doneloading) {
    //         setLoadingTotalPropertiesMonthly(true)
    //         setTotalPropertiesMonthly(0);

    //         setLoadingTotalHousesMonthly(true)
    //         setTotalHousesMonthly(0);

    //         setLoadingTotalTenantsMonthly(true)
    //         setTotalTenantsMonthly(0);

    //         setLoadingTotalOccupiedHousesMonthly(true)
    //         setTotalOccupiedHousesMonthly(0);

    //         setLoadingTotalVacantHousesMonthly(true)
    //         setTotalVacantHousesMonthly(0);

    //         setLoadingTotalDoubleHousesMonthly(true)
    //         setTotalDoubleHousesMonthly(0);
    //         setDoublesDataMonthly([])
            
    //     }
    //     const getDashStats = async (e) => { 
    //         const sno=(selectedmonth==='')?0:selectedmonth;
    //         const month=(!loadingmonths)?prevmonths[sno].month:0;
    //         await axios.get(`/v2/dash/stats/propertymonthly/${month}`)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     setTotalPropertiesMonthly(response.data.totalproperties);
    //                     setLoadingTotalPropertiesMonthly(false)

    //                     setTotalHousesMonthly(response.data.totalhouses);
    //                     setLoadingTotalHousesMonthly(false)

    //                     setTotalTenantsMonthly(response.data.totaltenants);
    //                     setLoadingTotalTenantsMonthly(false)

    //                     setTotalOccupiedHousesMonthly(response.data.totaloccupiedhouses);
    //                     setLoadingTotalOccupiedHousesMonthly(false)

    //                     setTotalVacantHousesMonthly(response.data.totalvacanthouses);
    //                     setLoadingTotalVacantHousesMonthly(false)

    //                     setTotalDoubleHousesMonthly(response.data.totaldoublehouses);
    //                     setDoublesDataMonthly(response.data.doublehouses);
    //                     setLoadingTotalDoubleHousesMonthly(false)
                        
    //                 }
    //                 else if(response.data.status=== 401){
    //                     setLoggedOff(true);    
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else if(response.data.status=== 500){
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else{
    //                     setLoadingTotalPropertiesMonthly(false)
    //                 }
    //                 setLoadingTotalPropertiesMonthly(false)
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
    //                 setLoadingTotalPropertiesMonthly(false)
    //                 setTotalPropertiesMonthly(0)
                    
    //                 setLoadingTotalHousesMonthly(true)
    //                 setTotalHousesMonthly(0);

    //                 setLoadingTotalTenantsMonthly(true)
    //                 setTotalTenantsMonthly(0);

    //                 setLoadingTotalOccupiedHousesMonthly(true)
    //                 setTotalOccupiedHousesMonthly(0);

    //                 setLoadingTotalVacantHousesMonthly(true)
    //                 setTotalVacantHousesMonthly(0);

    //                 setLoadingTotalDoubleHousesMonthly(true)
    //                 setTotalDoubleHousesMonthly(0);
    //                 setDoublesDataMonthly([])
    //             }
    //             else{
    //                 let ex=error['response'].data.message;
    //                 if(ex==='Unauthenticated.'){
    //                     setLoggedOff(true); 
    //                     localStorage.removeItem('auth_token');
    //                     localStorage.removeItem('auth_name');
    //                 }
    //                 else{
    //                     setLoadingTotalPropertiesMonthly(false)
    //                     setTotalPropertiesMonthly(0)

    //                     setLoadingTotalHousesMonthly(true)
    //                     setTotalHousesMonthly(0);

    //                     setLoadingTotalTenantsMonthly(true)
    //                     setTotalTenantsMonthly(0);

    //                     setLoadingTotalOccupiedHousesMonthly(true)
    //                     setTotalOccupiedHousesMonthly(0);

    //                     setLoadingTotalVacantHousesMonthly(true)
    //                     setTotalVacantHousesMonthly(0);

    //                     setLoadingTotalDoubleHousesMonthly(true)
    //                     setTotalDoubleHousesMonthly(0);
    //                     setDoublesDataMonthly([])
                        

    //                     Swal("Error",""+error,"error");
    //                 }
    //             }
                
    //         })
    //     };
    //     getDashStats();

    //     return ()=>{
    //         doneloading=false;
    //     }
    // },[selectedmonth,dashrefresh])


    // load bills stats
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalRent(true)
            setTotalRent(0);

            setLoadingTotalWaterbill(true)
            setTotalWaterbill(0);

            setLoadingTotalGarbage(true)
            setTotalGarbage(0);

            setLoadingTotalWaterD(true)
            setTotalWaterD(0);

            setLoadingTotalKPLCD(true)
            setTotalKPLCD(0);

            setLoadingTotalRentD(true)
            setTotalRentD(0);

            setLoadingTotalArrears(true)
            setTotalArrears(0);

            setLoadingTotalExcess(true)
            setTotalExcess(0);

            setLoadingTotalBilled(true)
            setTotalBilled(0);

            setLoadingTotalPaid(true)
            setTotalPaid(0);

            setLoadingTotalBalance(true)
            setTotalBalance(0);

            setLoadingTotalRefund(true)
            setTotalRefund(0);

            setLoadingTotalLease(true)
            setTotalLease(0);

            setLoadingTotalForwaded(true)
            setTotalForwaded(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/v2/dash/stats/bills/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalRent(response.data.totalrent);
                        setLoadingTotalRent(false)

                        setTotalWaterbill(response.data.totalwaterbill);
                        setLoadingTotalWaterbill(false)

                        setTotalGarbage(response.data.totalgarbage);
                        setLoadingTotalGarbage(false)

                        setTotalWaterD(response.data.totalwaterd);
                        setLoadingTotalWaterD(false)

                        setTotalKPLCD(response.data.totalkplcd);
                        setLoadingTotalKPLCD(false)

                        setTotalRentD(response.data.totalrentd);
                        setLoadingTotalRentD(false)

                        setTotalArrears(response.data.totalarrears);
                        setLoadingTotalArrears(false)

                        setTotalExcess(response.data.totalexcess);
                        setLoadingTotalExcess(false)

                        setTotalBilled(response.data.totalbilled);
                        setLoadingTotalBilled(false)

                        setTotalPaid(response.data.totalpaid);
                        setLoadingTotalPaid(false)

                        setTotalBalance(response.data.totalbalance);
                        setLoadingTotalBalance(false)

                        setTotalLease(response.data.totallease);
                        setLoadingTotalLease(false)

                        
                        setTotalRefund(response.data.totalrefund);
                        setLoadingTotalRefund(false)

                        
                        setTotalForwaded(response.data.totalforwaded);
                        setLoadingTotalForwaded(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalRent(false)
                        setLoadingTotalWaterbill(false)
                        setLoadingTotalGarbage(false)
                        setLoadingTotalWaterD(false)
                        setLoadingTotalKPLCD(false)
                        setLoadingTotalRentD(false)
                        setLoadingTotalArrears(false)
                        setLoadingTotalExcess(false)
                        setLoadingTotalBilled(false)
                        setLoadingTotalPaid(false)
                        setLoadingTotalBalance(false)
                        setLoadingTotalRefund(false)
                        setLoadingTotalLease(false)
                        setLoadingTotalForwaded(false)
                    }
                    setLoadingTotalRent(false)
                    setLoadingTotalWaterbill(false)
                    setLoadingTotalGarbage(false)
                    setLoadingTotalWaterD(false)
                    setLoadingTotalKPLCD(false)
                    setLoadingTotalRentD(false)
                    setLoadingTotalArrears(false)
                    setLoadingTotalExcess(false)
                    setLoadingTotalBilled(false)
                    setLoadingTotalPaid(false)
                    setLoadingTotalBalance(false)
                    setLoadingTotalRefund(false)
                    setLoadingTotalLease(false)
                    setLoadingTotalForwaded(false)
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
                    setLoadingTotalRent(false)
                    setTotalRent(0)

                    setLoadingTotalWaterbill(false)
                    setTotalWaterbill(0)

                    setLoadingTotalGarbage(false)
                    setTotalGarbage(0)

                    setLoadingTotalWaterD(false)
                    setTotalWaterD(0)

                    setLoadingTotalKPLCD(false)
                    setTotalKPLCD(0)

                    setLoadingTotalRentD(false)
                    setTotalRentD(0)

                    setLoadingTotalArrears(false)
                    setTotalArrears(0)

                    setLoadingTotalExcess(false)
                    setTotalExcess(0)

                    setLoadingTotalBilled(false)
                    setTotalBilled(0)

                    setLoadingTotalPaid(false)
                    setTotalPaid(0)

                    setLoadingTotalBalance(false)
                    setTotalBalance(0)

                    setLoadingTotalRefund(false)
                    setTotalRefund(0)

                    setLoadingTotalLease(false)
                    setTotalLease(0)

                    setLoadingTotalForwaded(false)
                    setTotalForwaded(0)
                }
                else{
                    let ex=error['response'].data.message;
                    if(ex==='Unauthenticated.'){
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingTotalRent(false)
                        setTotalRent(0)

                        setLoadingTotalWaterbill(false)
                        setTotalWaterbill(0)
                        
                        setLoadingTotalGarbage(false)
                        setTotalGarbage(0)

                        setLoadingTotalWaterD(false)
                        setTotalWaterD(0)

                        setLoadingTotalKPLCD(false)
                        setTotalKPLCD(0)

                        setLoadingTotalRentD(false)
                        setTotalRentD(0)

                        setLoadingTotalArrears(false)
                        setTotalArrears(0)

                        setLoadingTotalExcess(false)
                        setTotalExcess(0)

                        setLoadingTotalBilled(false)
                        setTotalBilled(0)

                        setLoadingTotalPaid(false)
                        setTotalPaid(0)

                        setLoadingTotalBalance(false)
                        setTotalBalance(0)


                        setLoadingTotalRefund(false)
                        setTotalRefund(0)
    
                        setLoadingTotalLease(false)
                        setTotalLease(0)
    
                        setLoadingTotalForwaded(false)
                        setTotalForwaded(0)
                        

                        Swal("Error",""+error,"error");
                    }
                }
               
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth,dashrefresh])


    // chart insights
    useEffect(()=>{
        let doneloading=true;
        setMonthlyInsightsStatus(false)
        const getMonthlyInsights = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/v2/dash/insights/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setMonthlyInsights(response.data.insights);
                        setMonthlyInsightsStatus(true)
                        setMonthlyInsightsError('')
                    }
                }
            })
            .catch(error=>{
                // setMonthlyInsights([]);
                // console.log('' +error)
                setMonthlyInsightsError(' '+error)
            })
        };
        getMonthlyInsights();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth,dashrefresh])

    //monthly rent and garbage insights
    useEffect(()=>{
        let doneloading=true;
        setMonthlyInsightsStatusRent(false)
        const getMonthlyInsights = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/v2/dash/insights/rents/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setMonthlyInsightsRent(response.data.insights);
                        setMonthlyInsightsStatusRent(true)
                        setMonthlyInsightsErrorRent('')
                    }
                    setMonthlyInsightsRentRefresh(false)
                }
            })
            .catch(error=>{
                setMonthlyInsightsRent([]);
                // console.log('' +error)
                setMonthlyInsightsErrorRent(' '+error.message)
                setMonthlyInsightsRentRefresh(false);
                setMonthlyInsightsStatusRent(true)
            })
        };
        getMonthlyInsights();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth,dashrefresh,monthlyinsightsrentrefresh])

    useEffect(()=>{
        let doneloading=true;
        setLoadingWater(true)
        const getWaterbill = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/v2/dash/water/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        // console.log(response.data)
                        setWaterbill(response.data.waterbill);
                        setTotalWaterbillProperties(response.data.totals);
                        setCurrentMonth(response.data.currentdate);
                        setCurrentMonthName(response.data.currentmonthname);
                        setCurrentYear(response.data.yearly);
                        setCurrentYearUp(response.data.yearlyup);
                        setCurrentYearDown(response.data.yearlydown);
                        setLoadingWater(false)
                    }
                }
            })
            .catch(error=>{
                setWaterbill([]);
            })
        };
        getWaterbill();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth,dashrefresh])

    useEffect(()=>{
        let doneloading=true;
        setLoadingPayment(true)
        const getPayments = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/v2/dash/payments/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        // console.log(response.data)
                        setPayments(response.data.payments);
                        setTotalWaterbillProperties(response.data.totals);
                        setCurrentMonth(response.data.currentdate);
                        setCurrentMonthName(response.data.currentmonthname);
                        setCurrentYear(response.data.yearly);
                        setCurrentYearUp(response.data.yearlyup);
                        setCurrentYearDown(response.data.yearlydown);
                        setLoadingPayment(false)
                    }
                }
            })
            .catch(error=>{
                setPayments([]);
            })
        };
        getPayments();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth,dashrefresh])

// console.log(currentmonth,selectedmonth,currentyear);
    
    const handleClose = () => {
        setShow(false);
        setProperty('');
        setPropertyId('');
    };

    const handleShow = (propert) => {
        setShow(true);
        setProperty(propert.plotname);
        setPropertyId(propert.id);
    };

    const handlePaymentClose = () => {
        setShowDownloadPayments(false);
        setProperty('');
        setPropertyId('');
    };

    const handlePaymentShow = (names) => {
        setShowDownloadPayments(true);
        setProperty(names.plotname);
        setPropertyId(names.id);
    };

    const handleMonth =(months) =>{
        setLoadingMonths(true)
        setLoadingWater(true)
        setSelectedMonth(months.sno);
        setShowMonth(false)
        setLoadingMonths(false)
        setMonthlyInsightsRentRefresh(true)
    }

    const handleNext =() =>{
        setLoadingMonths(true)
        setLoadingWater(true)
        if(selectedmonth > 0){
            setSelectedMonth(selectedmonth-1)
            setMonthlyInsightsRentRefresh(true)
        }
        else{
            setLoadingWater(false)
        }
        setLoadingMonths(false)
    }

    const handlePrev =() =>{
        setLoadingMonths(true)
        setLoadingWater(true)
        if(selectedmonth < totalmonths){
            setSelectedMonth(selectedmonth+1)
            setMonthlyInsightsRentRefresh(true)
        }
        else{
            setLoadingWater(false)
        }
        setLoadingMonths(false)
    }


    var prevmonthslist="";
    if(!loadingmonths){
        prevmonthslist=
        prevmonths && prevmonths.map((months,key) => ( 
            <div key={key}>
                {/* {key <= 11 && */}
                    <>
                        {months.month === currentmonth &&
                            <div key={key} className='m-1 mt-0 mb-0 p-0 pt-0 pb-0' onClick={()=>{handleMonth(months)}}>
                                <span className='btn btn-info btn-block border-none m-0 p-2'>  {months.monthname}</span>
                            </div>
                        }
                        {months.month !== currentmonth &&
                            <>
                                {!loadingmonths && prevmonths && prevmonths[selectedmonth].month===months.month?
                                <div key={key} className='m-1 mt-0 mb-0 p-0 pt-0 pb-0' onClick={()=>{handleMonth(months)}}>
                                    <span className='btn btn-light btn-block border-info text-info m-0 p-2'>  {months.monthname}</span>
                                </div>
                                :
                                <div key={key} className='m-1 mt-0 mb-0 p-0 pt-0 pb-0' onClick={()=>{handleMonth(months)}}>
                                    <span className='btn btn-outline-secondary btn-block border-none m-0 p-2'>  {months.monthname}</span>
                                </div>
                                }
                            </>
                            // <div key={key} className='m-1 mt-0 mb-0 p-0 pt-0 pb-0' onClick={()=>{handleMonth(months)}}>
                            //     <span className='btn btn-outline-secondary btn-block border-info m-0 p-0'>{months.monthname}</span>
                            // </div>
                        }
                    </>
                 {/* } */}
                    
                {/* ) */}
            </div>
        ))
        
    }

    const downloadPaymentAll = () =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingDownloadPayment(true)
        }
        const getDownload = async (e) => { 
            let url='/v2/downloads/Reports/Payments/Summary/'+ (!loadingmonths && prevmonths && prevmonths[selectedmonth].month);
            await axios.get(url,{
                responseType: 'blob',
            })
            .then(response=>{
                if (doneloading) {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    const filename=' Payment Summary for '+ (!loadingmonths && prevmonths[selectedmonth].monthname)+' .xlsx';
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link); // Clean up
                    setLoadingDownloadPayment(false)
                    Swal("Success","Your File Has been Successfully sent to your browser");
                }
            })
            .catch(error=>{
                if(!localStorage.getItem("auth_token")){
                    // let ex=error['response'].data.message;
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        if(!localStorage.getItem("auth_token")){
                            setLoadingDownloadPayment(false)
                            setLoggedOff(true); 
                        }  
                        else{ 
                            setLoadingDownloadPayment(false)
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }              
                    }
                    else{
                        // console.log(error.response.statusText)
                        Swal("Error",""+error,"error");
                    }
                    setLoadingDownloadPayment(false)
                }
                else{
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        setLoadingDownloadPayment(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingDownloadPayment(false)
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
        getDownload();

        return ()=>{
            doneloading=false;
        }
    }

    const downloadWaterbillAll = () =>{
        let doneloading=true;
        if (doneloading) {
            setLoadingDownload(true)
        }
        const getDownload = async (e) => { 
            let url='/v2/downloads/Reports/Waterbill/Summary/'+ (!loadingmonths && prevmonths && prevmonths[selectedmonth].month);
            await axios.get(url,{
                responseType: 'blob',
            })
            .then(response=>{
                if (doneloading) {
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    const filename='Waterbill Summary for '+ (!loadingmonths && prevmonths[selectedmonth].monthname)+' .xlsx';
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link); // Clean up
                    setLoadingDownload(false)
                    Swal("Success","Your File Has been Successfully sent to your browser");
                }
            })
            .catch(error=>{
                if(!localStorage.getItem("auth_token")){
                    // let ex=error['response'].data.message;
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        if(!localStorage.getItem("auth_token")){
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                        }  
                        else{ 
                            setLoadingDownload(false)
                            setLoggedOff(true); 
                            localStorage.removeItem('auth_token');
                            localStorage.removeItem('auth_name');
                        }              
                    }
                    else{
                        // console.log(error.response.statusText)
                        Swal("Error",""+error,"error");
                    }
                    setLoadingDownload(false)
                }
                else{
                    let ex=error.response.statusText;
                    if(ex==='Unauthorized'){
                        setLoadingDownload(false)
                        setLoggedOff(true); 
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('auth_name');
                    }
                    else{
                        setLoadingDownload(false)
                        Swal("Error",""+error,"error");
                    }
                }
            })
        };
        getDownload();

        return ()=>{
            doneloading=false;
        }
    }

    if(loadingmonths){
        prevmonthslist= (
            <Spinner animation="grow" variant="primary" size="lg" role="status"></Spinner>
        );
    }


  return (
    <>
    <div className="wrapper">
        {loggedoff ? 
            <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
        :
        <>
        <DashNavBar setClosed={setClosed} closed={closed} active='home'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='home'/>
        
        {/* className={`nav-link ${active==='home'?'active':''}`} */}
        
        <main className="py-3">
            <div className={`content-wrapper ${closed?'closed':''}`} style={{"paddingTop": "10px"}}>
                
                <section className="content">
                    <div className="container">
                        <div className="row justify-content-center">
                        <div className="col-lg-8">
                            <div className="row m-0 p-0">
                                <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                    <div className="card border-none" >
                                        <div className="card-header bg-info text-white m-0 p-0">
                                            <h4 style={{"textAlign": "center"}}>Welcome to Wagitonga Agencies Dashboard
                                                <Button style={{'float':'right'}} className='text-white' variant="info" onClick={()=>{setDashStatsRefres(!dashrefresh)}}> <i className="fa fa-sync-alt"> </i>  </Button>    
                                            </h4>
                                            <div className='monthchanger p-1 elevation-3 border-info'>
                                                {!showmonth && selectedmonth !== '' &&
                                                    <>
                                                        {selectedmonth < totalmonths-1 &&
                                                            <div className='monthchanger-prev'>
                                                                <button className='btn btn-outline-primary btn-block border-info m-0 p-1' onClick={handlePrev}><i className="fas fa-chevron-left"></i></button>
                                                            </div>
                                                        }
                                                    </>
                                                }
                                                {showmonth && <button className='btn btn-danger border-info m-0 p-2' onClick={()=>{setShowMonth(!showmonth)}} ><i className="fas fa-times"></i></button>}
                                                <div className='monthchanger-date'>
                                                    <div className='monthchanger-date-show m-0 p-1' onClick={()=>{setShowMonth(!showmonth)}}>
                                                         
                                                        <span className='m-0 p-2'>
                                                        {showmonth ?<i className="fas fa-chevron-up"></i>:<i className="fas fa-chevron-down"></i>}
                                                        </span> 
                                                        {/* ({totalmonths-selectedmonth}). */}
                                                        <span className='m-0 p-2'>
                                                            {loadingmonths &&
                                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                            }
                                                            {selectedmonth !== '' &&
                                                                !loadingmonths && prevmonths && prevmonths[selectedmonth].monthname
                                                            }
                                                            
                                                        </span>
                                                        {showmonth && <button className='btn btn-danger border-info m-0 p-2' onClick={()=>{setShowMonth(!showmonth)}} ><i className="fas fa-times"></i></button>}
                                                    </div>
                                                    {showmonth && 
                                                        <div className='bg-light elevation-2 border-none pt-1 pb-1' style={{"maxHeight":"300px","overflowY":"auto"}}>
                                                            {prevmonthslist}
                                                        </div>
                                                    }
                                                    {showmonth && <button className='btn btn-danger border-info m-0 p-2' onClick={()=>{setShowMonth(!showmonth)}} ><i className="fas fa-times"></i></button>}
                                                </div>
                                                {!showmonth && selectedmonth !== '' &&
                                                    <>
                                                        {selectedmonth > 0 &&
                                                            <div className='monthchanger-next'>
                                                                <button className='btn btn-outline-primary btn-block border-info m-0 p-1' onClick={handleNext}><i className="fas fa-chevron-right"></i></button>
                                                            </div>
                                                        }
                                                    </>
                                                }
                                                {showmonth && <button className='btn btn-danger border-info m-0 p-2' onClick={()=>{setShowMonth(!showmonth)}} ><i className="fas fa-times"></i></button>}
                                            </div>
                                            
                                        </div>

                                        <div className="card-body text-center m-0 p-1 mt-2" style={{"paddingTop": "10px"}}>
                                            <div className="col-md-12 m-0 p-0 mt-2 mb-2 justify-content-center">
                                                <div className="row justify-content-center m-0 p-0 mt-3">

                                                <h4 style={{"textAlign": "center"}}>
                                                {loadingtotalproperties?
                                                    <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                    :
                                                    'All Properties Summary'
                                                }
                                                    
                                                </h4>

                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div>
                                                                <div className='elevation-1 border-light p-1'>Properties</div>
                                                                <div className='bold text-primary text-sm p-1'>
                                                                    {loadingtotalproperties &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                        !loadingtotalproperties && totalproperties
                                                                    }
                                                                    
                                                                </div>
                                                                <div className='bold text-lime text-sm p-1 border-top'>
                                                                    <span className='text-dark text-xs'>{!loadingmonths && prevmonths && " "+prevmonths[selectedmonth].monthname}: </span>
                                                                    {loadingtotalpropertiesmonthly &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                        !loadingtotalpropertiesmonthly && totalpropertiesmonthly
                                                                    }
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div>
                                                                <div className='elevation-1 border-light p-1'>Houses</div>
                                                                <div className='bold text-primary text-sm p-1'>
                                                                    {loadingtotalhouses &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                        !loadingtotalhouses && totalhouses
                                                                    }
                                                                </div>
                                                                <div className='bold text-lime text-sm p-1 border-top'>
                                                                    <span className='text-dark text-xs'>{!loadingmonths && prevmonths && " "+prevmonths[selectedmonth].monthname}: </span>
                                                                    {loadingtotalhousesmonthly &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                        !loadingtotalhousesmonthly && totalhousesmonthly
                                                                    }
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div>
                                                                <div className='elevation-1 border-light p-1'>Tenants</div>
                                                                <div className='bold text-primary text-sm p-1'>
                                                                    {loadingtotaltenants &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                        !loadingtotaltenants && totaltenants
                                                                    } 
                                                                </div>
                                                                <div className='bold text-lime text-sm p-1 border-top'>
                                                                    <span className='text-dark text-xs'>{!loadingmonths && prevmonths && " "+prevmonths[selectedmonth].monthname}: </span>
                                                                    {loadingtotaltenantsmonthly &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                        !loadingtotaltenantsmonthly && totaltenantsmonthly
                                                                    } 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div>
                                                                <div className='elevation-1 border-light p-1'>Occupied</div>
                                                                <div className='bold text-primary text-sm p-1'>
                                                                    {loadingtotaloccupiedhouses &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                        !loadingtotaloccupiedhouses && totaloccupiedhouses
                                                                    }  
                                                                </div>
                                                                <div className='bold text-lime text-sm p-1 border-top'>
                                                                    <span className='text-dark text-xs'>{!loadingmonths && prevmonths && " "+prevmonths[selectedmonth].monthname}: </span>
                                                                    {loadingtotaloccupiedhousesmonthly &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                        !loadingtotaloccupiedhousesmonthly && totaloccupiedhousesmonthly
                                                                    }  
                                                                </div>
                                                            </div>
                                                        </div>    
                                                    </div>
                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div>
                                                                <div className='elevation-1 border-light p-1'>Vacant</div>
                                                                <div className='bold text-primary text-sm p-1'>
                                                                    {loadingtotalvacanthouses &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                    !loadingtotalvacanthouses && totalvacanthouses
                                                                    }  
                                                                </div>
                                                                <div className='bold text-lime text-sm p-1 border-top'>
                                                                    <span className='text-dark text-xs'>{!loadingmonths && prevmonths && " "+prevmonths[selectedmonth].monthname}: </span>
                                                                    {loadingtotalvacanthousesmonthly &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                    !loadingtotalvacanthousesmonthly && totalvacanthousesmonthly
                                                                    }  
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div>
                                                                <div className='elevation-1 border-light p-1'>Doubles</div>
                                                                <div className='bold text-primary text-sm p-1'>
                                                                    {loadingtotaldoublehouses &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                        !loadingtotaldoublehouses && totaldoublehouses
                                                                    }  
                                                                </div>
                                                                <div className='bold text-lime text-sm p-1 border-top'>
                                                                    <span className='text-dark text-xs'>{!loadingmonths && prevmonths && " "+prevmonths[selectedmonth].monthname}: </span>
                                                                    {loadingtotaldoublehousesmonthly &&
                                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                    }
                                                                    {selectedmonth !== '' &&
                                                                        !loadingtotaldoublehousesmonthly && totaldoublehousesmonthly
                                                                    }  
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                
                                {/* <div className="col-md-12 m-0 p-0 mb-4">
                                    <div className="card border-none" >
                                        <div className="card-header bg-light elevation-3  m-0 p-1">
                                            
                                            <div className='monthchanger'>
                                                {!showmonth && selectedmonth !== '' &&
                                                    <>
                                                        {selectedmonth < totalmonths-1 &&
                                                            <div className='monthchanger-prev'>
                                                                <button className='btn btn-outline-primary btn-block border-info m-0 p-1' onClick={handlePrev}><i className="fas fa-chevron-left"></i></button>
                                                            </div>
                                                        }
                                                    </>
                                                }
                                                {showmonth && <button className='btn btn-danger border-info m-0 p-2' onClick={()=>{setShowMonth(!showmonth)}} ><i className="fas fa-times"></i></button>}
                                                <div className='monthchanger-date'>
                                                    <div className='monthchanger-date-show m-0 p-1' onClick={()=>{setShowMonth(!showmonth)}}>
                                                         
                                                        <span className='m-0 p-2'>
                                                        {showmonth ?<i className="fas fa-chevron-up"></i>:<i className="fas fa-chevron-down"></i>}
                                                        </span> 
                                                        <span className='m-0 p-2'>
                                                            {loadingmonths &&
                                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                            }
                                                            {selectedmonth !== '' &&
                                                                !loadingmonths && prevmonths && prevmonths[selectedmonth].monthname
                                                            }
                                                            
                                                        </span>
                                                        {showmonth && <button className='btn btn-danger border-info m-0 p-2' onClick={()=>{setShowMonth(!showmonth)}} ><i className="fas fa-times"></i></button>}
                                                    </div>
                                                    {showmonth && 
                                                        <div className='bg-light elevation-2 border-none pt-1 pb-1' style={{"maxHeight":"300px","overflowY":"auto"}}>
                                                            {prevmonthslist}
                                                        </div>
                                                    }
                                                    {showmonth && <button className='btn btn-danger border-info m-0 p-2' onClick={()=>{setShowMonth(!showmonth)}} ><i className="fas fa-times"></i></button>}
                                                </div>
                                                {!showmonth && selectedmonth !== '' &&
                                                    <>
                                                        {selectedmonth > 0 &&
                                                            <div className='monthchanger-next'>
                                                                <button className='btn btn-outline-primary btn-block border-info m-0 p-1' onClick={handleNext}><i className="fas fa-chevron-right"></i></button>
                                                            </div>
                                                        }
                                                    </>
                                                }
                                                {showmonth && <button className='btn btn-danger border-info m-0 p-2' onClick={()=>{setShowMonth(!showmonth)}} ><i className="fas fa-times"></i></button>}
                                            </div>
                                            
                                        </div>

                                        
                                    </div>
                                </div> */}

                                <div className="col-md-12 m-0 p-0 mb-4 justify-content-center">
                                    <div className="row justify-content-center m-0 p-0">
                                    <h4 style={{"textAlign": "center"}}>
                                        {!loadingmonths ?
                                        <>
                                            {!loadingtotalrent ?
                                                <>
                                                Summary for 
                                                {selectedmonth !== '' &&
                                                    !loadingmonths && prevmonths && " "+prevmonths[selectedmonth].monthname
                                                }
                                                </>
                                                :
                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                            }
                                        </>
                                        :
                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                        }
                                        

                                        {/* {loadingtotalproperties?
                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                            :
                                            'All Properties Summary'
                                        }
                                         */}
                                        
                                    </h4>

                                    {selectedmonth !== '' &&  (totalrent) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>Rent:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotalrent &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalrent && new Number(totalrent).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }

                                    {selectedmonth !== '' &&  (totalrentd) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>Rent D:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotalrentd &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalrentd && new Number(totalrentd).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }
                                    {selectedmonth !== '' &&  (totalwaterd) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>Waterbill:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotalwaterd &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalwaterd && new Number(totalwaterd).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }
                                    {selectedmonth !== '' &&  (totalwaterd) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>Water D:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotalwaterd &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalwaterd && new Number(totalwaterd).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }
                                    {selectedmonth !== '' &&  (totalgarbage) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>Garbage:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotalgarbage &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalgarbage && new Number(totalgarbage).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }
                                    {selectedmonth !== '' &&  (totalkplcd) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>KPLC D:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotalkplcd &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalkplcd && new Number(totalkplcd).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }                                    
                                    {selectedmonth !== '' &&  (totalforwaded) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>Carried:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotalforwaded &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalforwaded && new Number(totalforwaded).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }
                                    {selectedmonth !== '' &&  (totallease) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>Leases:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotallease &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotallease && new Number(totallease).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }
                                    {selectedmonth !== '' &&  (totalbilled) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>Billed:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotalbilled &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalbilled && new Number(totalbilled).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }
                                    {selectedmonth !== '' &&  (totalpaid) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>Paid:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotalpaid &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalpaid && new Number(totalpaid).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }
                                           

                                    {selectedmonth !== '' &&  (totalbalance) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>Bal:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotalbalance &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalbalance && new Number(totalbalance).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }
                                    {selectedmonth !== '' &&  (totalrefund) >0 &&
                                        <div className='col-6 col-lg-3 col-md-3 m-0 p-0 text-left'>
                                            <div className='m-0 mt-2 p-0 border-white-b '>
                                                <div className='elevation-1 p-2 border-light'>Refund:
                                                    <span className='float-right bold p-0 text-info text-sm'>
                                                        {loadingtotalrefund &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalrefund && new Number(totalrefund).toFixed(2)
                                                        }  
                                                    </span>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    }
                                    
                                        
                                        {/* {selectedmonth !== '' &&  (totalwaterbill + totalwaterd) >0 &&
                                            <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                <div className='m-0 p-0 mr-2 border-white-b '>
                                                    {selectedmonth !== '' &&  tota2waterbill >0 &&
                                                    <div>
                                                        <div className='elevation-1 p-0 border-light'>Waterbill</div>
                                                        <div className='bold p-1 text-info text-sm'>
                                                            {loadingtotalwaterbill &&
                                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                            }
                                                            {selectedmonth !== '' &&
                                                                !loadingtotalwaterbill && new Number(totalwaterbill).toFixed(2)
                                                            } 
                                                        </div>
                                                    </div>
                                                    }
                                                    {selectedmonth !== '' &&  totalwaterd >0 &&
                                                    <div>
                                                        <div className='elevation-1 p-0 border-light'>Water D</div>
                                                    <div className='bold p-1 text-info text-sm'>
                                                        {loadingtotalwaterd &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalwaterd && new Number(totalwaterd).toFixed(2)
                                                        }  
                                                    </div>
                                                    
                                                    </div>
                                                    }
                                                </div>
                                            </div>
                                        } */}
                                        
                                    </div>
                                    
                                </div>

                                <div className="row m-0 p-0">
                                    <div className="col-12 col-lg-6 m-0 p-0 mt-2 mb-4">
                                        <div className="card-body bg-light border-info elevation-1 text-center m-1 mb-3 p-1">
                                            
                                            <div className='text-md bold text-center weight-bold'>
                                                Water Bill 
                                                {loadingmonths &&
                                                    <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                }
                                                
                                                {selectedmonth !== '' &&
                                                    !loadingmonths && prevmonths && " "+prevmonths[selectedmonth].monthname
                                                }
                                                <span className='float-right m-0 mr-1'>
                                                    {loadingdownload && 
                                                        <>
                                                        <Spinner
                                                            as="span"
                                                            variant='info'
                                                            animation="border"
                                                            size="sm"
                                                            role="status"
                                                            aria-hidden="true"
                                                            />
                                                            <span className='text-info text-sm' style={{"padding": "10px","display":"inline-block"}}>
                                                            Preparing ...</span>
                                                        </>
                                                    }
                                                    {!loadingdownload && 
                                                        <>
                                                        <a target='_blank' onClick={()=>{downloadWaterbillAll()}}
                                                        className="p-0 m-1 btn btn-block btn-outline-primary">
                                                        <i className='fa fa-download text-sm'>
                                                            <small> {!loadingmonths && prevmonths && prevmonths[selectedmonth].monthname}</small> </i></a>
                                                        </>
                                                    }
                                                </span>

                                                {/* <h6><span className='text-info'>Next Month</span>: Billed on Selected Month but to be Paid on Next Month</h6>
                                                <h6><span className='text-lime'>Current Month</span>: <span className='text-success'>Billed on Last Month but to be Paid on Selected Month</span></h6> */}
                                                
                                            </div>
                                            
                                            {loadingwater &&
                                                <Carousel className='' >
                                                    <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                </Carousel>
                                            }

                                            {!loadingwater &&
                                                <LoadWaterbill waterbill={waterbill} handleShow={handleShow} />
                                            }
                                                
                                        </div>
                                    </div>

                                    <div className="col-12 col-lg-6 m-0 p-0 mt-2 mb-4">
                                        <div className="card-body bg-light border-info elevation-1 text-center m-0 mb-3 p-1">
                                            <div className='text-md bold text-center weight-bold'>
                                                Payments  
                                                {loadingmonths &&
                                                    <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                }
                                                
                                                {selectedmonth !== '' &&
                                                    !loadingmonths && prevmonths && " "+prevmonths[selectedmonth].monthname
                                                }

                                                <span className='float-right m-0 mr-1'>
                                                    {loadingdownloadpayment && 
                                                        <>
                                                        <Spinner
                                                            as="span"
                                                            variant='info'
                                                            animation="border"
                                                            size="sm"
                                                            role="status"
                                                            aria-hidden="true"
                                                            />
                                                            <span className='text-success text-sm' style={{"padding": "10px","display":"inline-block"}}>
                                                            Preparing ...</span>
                                                        </>
                                                    }
                                                    {!loadingdownloadpayment && 
                                                    <>
                                                    <a target='_blank' onClick={()=>{downloadPaymentAll()}}
                                                     className="p-0 m-1 btn btn-block btn-outline-success"> 
                                                     <i className='fa fa-download text-sm'> 
                                                    <small> {!loadingmonths && prevmonths && prevmonths[selectedmonth].monthname}</small> </i></a>
                                                    </>
                                                    }
                                                </span>
                                                
                                            </div>
                                            
                                            {loadingpayment &&
                                                <Carousel className='' >
                                                    <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                </Carousel>
                                            }

                                            {!loadingpayment &&
                                                <LoadPayments payments={payments} handlePaymentShow={handlePaymentShow} />
                                            }
                                                
                                        </div>
                                    </div>
                                </div>

                                {monthlyinsightsstatus &&
                                    <>
                                        {monthlyinsights[0].waterbillw !='' &&
                                            <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                                <div className="card border-white-b" >

                                                    <div className="card-body text-center" style={{"paddingTop": "10px"}}>
                                                        {monthlyinsightsstatus &&
                                                            <MonthlyWaterbillCharts monthlyinsights={monthlyinsights}/>
                                                        }
                                                        {!monthlyinsightsstatus &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }

                                                    </div>
                                                </div>
                                            </div>    
                                        }
                                    </>
                                }

                                {monthlyinsightsstatus &&
                                    <>
                                        {monthlyinsights[0].rentbinsw !='' &&
                                            <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                                <div className="card border-white-b" >

                                                    <div className="card-body text-center" style={{"paddingTop": "10px"}}>
                                                        
                                                        {monthlyinsightsstatusrent && monthlyinsightsrent[0] &&
                                                            <MonthlyRentGarbageCharts monthlyinsightsrent={monthlyinsightsrent}/>
                                                        }
                                                        {!monthlyinsightsstatusrent &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                    </div>
                                                </div>
                                            </div>
                                        }
                                    </>
                                }


                                {monthlyinsightsstatus &&
                                    <>
                                        {monthlyinsights[0].propertsr !='' &&
                                        <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                            <div className="card border-white-b" >

                                                <div className="card-body text-center" style={{"paddingTop": "10px"}}>
                                                    
                                                    {monthlyinsightsstatus &&
                                                        <MonthlyCharts monthlyinsights={monthlyinsights}/>
                                                    }
                                                    {!monthlyinsightsstatus &&
                                                        <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                    }
                                                </div>
                                            </div>
                                        </div>
                                        }
                                    </>
                                }

                                {monthlyinsightsstatus &&
                                    <>
                                        {monthlyinsights[0].propertsd !='' &&
                                            <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                                <div className="card border-white-b" >

                                                    <div className="card-body text-center" style={{"paddingTop": "10px"}}>
                                                        
                                                        {monthlyinsightsstatus &&
                                                            <MonthlyNewlyCharts monthlyinsights={monthlyinsights}/>
                                                        }
                                                        {!monthlyinsightsstatus &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                    </div>
                                                </div>
                                            </div>
                                        }
                                    </>
                                }

                                

                                {show && 
                                    <DownloadWaterbillModal show={show} handleClose={handleClose} property={property} propertyid={propertyid} loadingmonths={loadingmonths} prevmonths={prevmonths} selectedmonth={selectedmonth} loadingwater={loadingwater} currentyearup={currentyearup} currentyear={currentyear}   currentyeardown={currentyeardown} setLoggedOff={setLoggedOff} />
                                }

                                {showdownloadpayments && 
                                    <DownloadPaymentModal showdownloadpayments={showdownloadpayments} handlePaymentClose={handlePaymentClose} property={property} propertyid={propertyid}  loadingmonths={loadingmonths} prevmonths={prevmonths} selectedmonth={selectedmonth} loadingpayment={loadingpayment} currentyearup={currentyearup} currentyear={currentyear}   currentyeardown={currentyeardown}  setLoggedOff={setLoggedOff} />
                                }

                                {/* <Modal show={showdownloadpayments} onHide={handlePaymentClose} className="mt-4">
                                    <Modal.Header className='justify-content-center bg-success m-0 p-2' closeButton>
                                        <Modal.Title className='mx-auto text-white'> 
                                            <h5>Download {property} Payments Reports </h5>
                                        </Modal.Title>
                                    </Modal.Header>
                                    <Modal.Body>
                                        <div className="card-box-links m-0 p-1 justify-content-center text-center">
                                            <div className="row m-0 p-0 justify-content-center text-center">
                                                <div className='col-12 p-0 m-1'>
                                                    <a href="#" className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-success"> <i className='fa fa-download text-md'> <small> {property} Payments for Dec 2022({propertyid})</small> </i>
                                                    </a>
                                                </div>
                                                
                                                <div className='col-12 p-0 m-1'>
                                                    <a href="#" className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-success"> <i className='fa fa-download text-md'> <small> {property} Payments for 2022({propertyid})</small> </i>
                                                    </a>
                                                </div>

                                                <div className='col-12 p-0 m-1'>
                                                    <a href="#" className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-success"> <i className='fa fa-download text-md'> <small> {property} Payments for 2021({propertyid})</small> </i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </Modal.Body>
                                    <Modal.Footer className='justify-content-center bg-light'>
                                        <Button variant='secondary' onClick={handlePaymentClose}>
                                            Close
                                        </Button>
                                    </Modal.Footer>
                                </Modal> */}


                            </div>
                        </div>

                        <div className="col-lg-4">
                            <h4 className='text-center bg-info border-info text-light p-1'>Notifications</h4>
                            <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                
                            </div>

                            <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                <div className="card border-none" >
                                    

                                    
                                </div>
                            </div>
                            <div className="col-md-12 m-0 p-0 ">
                                <div className="card border-none" >
                                    <div className="card-header  bg-info text-white elevation-2  m-0 p-0">
                                        <h5 style={{"textAlign": "center"}}>
                                            <i className="fa fa-bell fa-fw"></i> 
                                            Notifications Panel 
                                        </h5>
                                    </div>

                                    <div className="card-body m-0 p-0 pt-2" >
                                        <div>
                                            <h2 className="calender-details">{calendarText}</h2>
                                            <div>
                                                <Calendar 
                                                views={['month']}
                                                onClickMonth={handleMonthChange}
                                                onClickYear={handleYearChange}
                                                onChange={handleDateChange}
                                                value={selectedDate}/>
                                            </div>
                                        </div>

                                        <div className="list-group">
                                            <a href="#" className="list-group-item">
                                                <i className="fa fa-comment fa-fw"></i> New Comment
                                                <span className="pull-right text-muted small"><em>4 minutes ago</em>
                                                </span>
                                            </a>
                                            <a href="#" className="list-group-item">
                                                <i className="fa fa-twitter fa-fw"></i> 3 New Followers
                                                <span className="pull-right text-muted small"><em>12 minutes ago</em>
                                                </span>
                                            </a>
                                            <a href="#" className="list-group-item">
                                                <i className="fa fa-envelope fa-fw"></i> Message Sent
                                                <span className="pull-right text-muted small"><em>27 minutes ago</em>
                                                </span>
                                            </a>
                                            <a href="#" className="list-group-item">
                                                <i className="fa fa-tasks fa-fw"></i> New Task
                                                <span className="pull-right text-muted small"><em>43 minutes ago</em>
                                                </span>
                                            </a>
                                            <a href="#" className="list-group-item">
                                                <i className="fa fa-upload fa-fw"></i> Server Rebooted
                                                <span className="pull-right text-muted small"><em>11:32 AM</em>
                                                </span>
                                            </a>
                                            <a href="#" className="list-group-item">
                                                <i className="fa fa-bolt fa-fw"></i> Server Crashed!
                                                <span className="pull-right text-muted small"><em>11:13 AM</em>
                                                </span>
                                            </a>
                                            <a href="#" className="list-group-item">
                                                <i className="fa fa-warning fa-fw"></i> Server Not Responding
                                                <span className="pull-right text-muted small"><em>10:57 AM</em>
                                                </span>
                                            </a>
                                            <a href="#" className="list-group-item">
                                                <i className="fa fa-shopping-cart fa-fw"></i> New Order Placed
                                                <span className="pull-right text-muted small"><em>9:49 AM</em>
                                                </span>
                                            </a>
                                            <a href="#" className="list-group-item">
                                                <i className="fa fa-money fa-fw"></i> Payment Received
                                                <span className="pull-right text-muted small"><em>Yesterday</em>
                                                </span>
                                            </a>
                                        </div>
                                        <a href="#" className="btn btn-default btn-block">View All Alerts</a>
                                    </div>
                                </div>

                                
                                
                            </div>
                        </div>
                            
                        </div>

                    </div>
                    

                </section>
            </div>
        </main>
        </>
        }

        <DashFooter />
      </div>
    </>
  );
}

export default Dashboard;