import DashSideNavBar from './DashSideNavBar';
import DashNavBar from './DashNavBar';
import { useEffect, useState } from 'react';
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


function Dashboard() {
    document.title="DashboardHome";
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


  const [loadingtotalhouses,setLoadingTotalHouses]=useState(false);
  const [loadingtotalproperties,setLoadingTotalProperties]=useState(false);
  const [loadingtotaltenants,setLoadingTotalTenants]=useState(false);
  const [loadingtotalvacanthouses,setLoadingTotalVacantHouses]=useState(false);
  const [loadingtotaloccupiedhouses,setLoadingTotalOccupiedHouses]=useState(false);
  const [loadingtotaldoublehouses,setLoadingTotalDoubleHouses]=useState(false);
  const [loadingdoublesdata,setLoadingDoublesData]=useState(false);
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

    const [waterbill,setWaterbill]=useState([]);
    const [stats,setStats]=useState([]);
    const [totalwaterbillproperties,setTotalWaterbillProperties]=useState(0);
    const [payments,setPayments]=useState([]);

    // /properties/dash/payments
    // /properties/dash/water
    // /properties/dash/water/prev
    // /properties/update/waterbill/1/2022 12
    // /properties/updateload/waterbill/1/2022 10

    // useEffect(()=>{
    //     axios.get('/api/isAuthencticated')
    //         .then(response=>{
    //             if(response.data.status=== 200){
    //                 setAuthName(localStorage.getItem("auth_name")); 
    //             }
    //             setLoading(false)
    //         })
    //         .catch((error)=>{
    //             setAuthName('');
    //             setRedirect(true);
    //             setUrl('/login');
    //         })

    //         return () =>{
    //             setAuthName('');
    //             setRedirect(true);
    //             setUrl('/login');
    //         };
    // },[])


    
    
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
            await axios.get(`/api/dash/month/prev/${month}`)
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
                setPrevMonths([]);
                setCurrentMonth('');
                setCurrentMonthName('');
                setTotalMonths(0);
            })
        };
        getPrevMonths();

        return ()=>{
            doneloading=false;
        }
    },[loggedoff])

    // load property stats
    useEffect(()=>{
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
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/property/${month}`)
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
                
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[dashrefresh])


    // // load total properties
    // useEffect(()=>{
    //     let doneloading=true;
    //     if (doneloading) {
    //         setLoadingTotalProperties(true)
    //         setTotalProperties(0);
    //     }
    //     const getDashStats = async (e) => { 
    //         const sno=(selectedmonth==='')?0:selectedmonth;
    //         const month=(!loadingmonths)?prevmonths[sno].month:0;
    //         await axios.get(`/api/dash/stats/properties/${month}`)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     setTotalProperties(response.data.totalproperties);
    //                     setLoadingTotalProperties(false)
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
    //             if(error.message==="Request failed with status code 401"){
    //                 setLoggedOff(true);                    
    //             }
    //             else if(error.message==="Request failed with status code 500"){
    //                 setLoggedOff(true);                    
    //             }
    //             else{
    //                 Swal("Error",""+error,"error");
    //             }
    //             setLoadingTotalProperties(false)
    //             setTotalProperties(0)
    //         })
    //     };
    //     getDashStats();

    //     return ()=>{
    //         doneloading=false;
    //     }
    // },[dashrefresh])

    // // load total houses
    // useEffect(()=>{
    //     let doneloading=true;
    //     if (doneloading) {
    //         setLoadingTotalHouses(true)
    //         setTotalHouses(0);
    //     }
    //     const getDashStats = async (e) => { 
    //         const sno=(selectedmonth==='')?0:selectedmonth;
    //         const month=(!loadingmonths)?prevmonths[sno].month:0;
    //         await axios.get(`/api/dash/stats/houses/${month}`)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     setTotalHouses(response.data.totalhouses);
    //                     setLoadingTotalHouses(false)
    //                 }
    //                 else if(response.data.status=== 401){
    //                     setLoggedOff(true);    
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else if(response.data.status=== 500){
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else{
    //                     setLoadingTotalHouses(false)
    //                 }
    //                 setLoadingTotalHouses(false)
    //             }
    //         })
    //         .catch(error=>{
    //             if(error.message==="Request failed with status code 401"){
    //                 setLoggedOff(true);                    
    //             }
    //             else if(error.message==="Request failed with status code 500"){
    //                 setLoggedOff(true);                    
    //             }
    //             else{
    //                 Swal("Error",""+error,"error");
    //             }
    //             setLoadingTotalHouses(false)
    //             setTotalHouses(0)
    //         })
    //     };
    //     getDashStats();

    //     return ()=>{
    //         doneloading=false;
    //     }
    // },[])

    // // load total tenants
    // useEffect(()=>{
    //     let doneloading=true;
    //     if (doneloading) {
    //         setLoadingTotalTenants(true)
    //         setTotalTenants(0);
    //     }
    //     const getDashStats = async (e) => { 
    //         const sno=(selectedmonth==='')?0:selectedmonth;
    //         const month=(!loadingmonths)?prevmonths[sno].month:0;
    //         await axios.get(`/api/dash/stats/tenants/${month}`)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     setTotalTenants(response.data.totaltenants);
    //                     setLoadingTotalTenants(false)
    //                 }
    //                 else if(response.data.status=== 401){
    //                     setLoggedOff(true);    
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else if(response.data.status=== 500){
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else{
    //                     setLoadingTotalTenants(false)
    //                 }
    //                 setLoadingTotalTenants(false)
    //             }
    //         })
    //         .catch(error=>{
    //             if(error.message==="Request failed with status code 401"){
    //                 setLoggedOff(true);                    
    //             }
    //             else if(error.message==="Request failed with status code 500"){
    //                 setLoggedOff(true);                    
    //             }
    //             else{
    //                 Swal("Error",""+error,"error");
    //             }
    //             setLoadingTotalTenants(false)
    //             setTotalTenants(0)
    //         })
    //     };
    //     getDashStats();

    //     return ()=>{
    //         doneloading=false;
    //     }
    // },[])

    // // load total total occupied houses
    // useEffect(()=>{
    //     let doneloading=true;
    //     if (doneloading) {
    //         setLoadingTotalOccupiedHouses(true)
    //         setTotalOccupiedHouses(0);
    //     }
    //     const getDashStats = async (e) => { 
    //         const sno=(selectedmonth==='')?0:selectedmonth;
    //         const month=(!loadingmonths)?prevmonths[sno].month:0;
    //         await axios.get(`/api/dash/stats/occupiedhouses/${month}`)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     setTotalOccupiedHouses(response.data.totaloccupiedhouses);
    //                     setLoadingTotalOccupiedHouses(false)
    //                 }
    //                 else if(response.data.status=== 401){
    //                     setLoggedOff(true);    
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else if(response.data.status=== 500){
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else{
    //                     setLoadingTotalOccupiedHouses(false)
    //                 }
    //                 setLoadingTotalOccupiedHouses(false)
    //             }
    //         })
    //         .catch(error=>{
    //             if(error.message==="Request failed with status code 401"){
    //                 setLoggedOff(true);                    
    //             }
    //             else if(error.message==="Request failed with status code 500"){
    //                 setLoggedOff(true);                    
    //             }
    //             else{
    //                 Swal("Error",""+error,"error");
    //             }
    //             setLoadingTotalOccupiedHouses(false)
    //             setTotalOccupiedHouses(0)
    //         })
    //     };
    //     getDashStats();

    //     return ()=>{
    //         doneloading=false;
    //     }
    // },[])

    // // load total vacant houses
    // useEffect(()=>{
    //     let doneloading=true;
    //     if (doneloading) {
    //         setLoadingTotalVacantHouses(true)
    //         setTotalVacantHouses(0);
    //     }
    //     const getDashStats = async (e) => { 
    //         const sno=(selectedmonth==='')?0:selectedmonth;
    //         const month=(!loadingmonths)?prevmonths[sno].month:0;
    //         await axios.get(`/api/dash/stats/vacanthouses/${month}`)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     setTotalVacantHouses(response.data.totalvacanthouses);
    //                     setLoadingTotalVacantHouses(false)
    //                 }
    //                 else if(response.data.status=== 401){
    //                     setLoggedOff(true);    
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else if(response.data.status=== 500){
    //                     Swal("Error",response.data.message,"error");
    //                 }
    //                 else{
    //                     setLoadingTotalVacantHouses(false)
    //                 }
    //                 setLoadingTotalVacantHouses(false)
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
    //                 setLoadingTotalVacantHouses(false)
    //                 setTotalVacantHouses(0)
    //             }
    //             else{
    //                 setLoadingTotalVacantHouses(false)
    //                 setTotalVacantHouses(0)
    //                 Swal("Error",""+error,"error");
    //             }
                
    //         })
    //     };
    //     getDashStats();

    //     return ()=>{
    //         doneloading=false;
    //     }
    // },[])

    

    // // load total double houses
    // useEffect(()=>{
    //     let doneloading=true;
    //     if (doneloading) {
    //         setLoadingTotalDoubleHouses(true)
    //         setTotalDoubleHouses(0);
    //         setDoublesData([])
    //     }
    //     const getDashStats = async (e) => { 
    //         const sno=(selectedmonth==='')?0:selectedmonth;
    //         const month=(!loadingmonths)?prevmonths[sno].month:0;
    //         await axios.get(`/api/dash/stats/doublehouses/${month}`)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
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
    //                     setLoadingTotalDoubleHouses(false)
    //                 }
    //                 setLoadingTotalDoubleHouses(false)
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
    //                 setLoadingTotalDoubleHouses(false)
    //                 setTotalDoubleHouses(0)
    //                 setDoublesData([])
    //             }
    //             else{
    //                 setLoadingTotalDoubleHouses(false)
    //                 setTotalDoubleHouses(0)
    //                 setDoublesData([])
    //                 Swal("Error",""+error,"error");
    //             }

    //         })
    //     };
    //     getDashStats();

    //     return ()=>{
    //         doneloading=false;
    //     }
    // },[])

    // load  total rent
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalRent(true)
            setTotalRent(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/rent/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalRent(response.data.totalrent);
                        setLoadingTotalRent(false)
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
                    }
                    setLoadingTotalRent(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalRent(false)
                setTotalRent(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    // load  total waterbill
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalWaterbill(true)
            setTotalWaterbill(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/waterbill/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalWaterbill(response.data.totalwaterbill);
                        setLoadingTotalWaterbill(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalWaterbill(false)
                    }
                    setLoadingTotalWaterbill(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalWaterbill(false)
                setTotalWaterbill(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    // load  total garbage
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalGarbage(true)
            setTotalGarbage(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/garbage/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalGarbage(response.data.totalgarbage);
                        setLoadingTotalGarbage(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalGarbage(false)
                    }
                    setLoadingTotalGarbage(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalGarbage(false)
                setTotalGarbage(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    // load  total waterd
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalWaterD(true)
            setTotalWaterD(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/waterd/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalWaterD(response.data.totalwaterd);
                        setLoadingTotalWaterD(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalWaterD(false)
                    }
                    setLoadingTotalWaterD(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalWaterD(false)
                setTotalWaterD(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    // load  total kplcd
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalKPLCD(true)
            setTotalKPLCD(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/kplcd/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalKPLCD(response.data.totalkplcd);
                        setLoadingTotalKPLCD(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalKPLCD(false)
                    }
                    setLoadingTotalKPLCD(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalKPLCD(false)
                setTotalKPLCD(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    // load  total rentd
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalRentD(true)
            setTotalRentD(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/rentd/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalRentD(response.data.totalrentd);
                        setLoadingTotalRentD(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalRentD(false)
                    }
                    setLoadingTotalRentD(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalRentD(false)
                setTotalRentD(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    // load  total arrears
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalArrears(true)
            setTotalArrears(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/arrears/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalArrears(response.data.totalarrears);
                        setLoadingTotalArrears(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalArrears(false)
                    }
                    setLoadingTotalArrears(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalArrears(false)
                setTotalArrears(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    // load  total excess
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalExcess(true)
            setTotalExcess(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/excess/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalExcess(response.data.totalexcess);
                        setLoadingTotalExcess(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalExcess(false)
                    }
                    setLoadingTotalExcess(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalExcess(false)
                setTotalExcess(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    // load  total billed
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalBilled(true)
            setTotalBilled(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/billed/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalBilled(response.data.totalbilled);
                        setLoadingTotalBilled(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalBilled(false)
                    }
                    setLoadingTotalBilled(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalBilled(false)
                setTotalBilled(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    // load  total paid
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalPaid(true)
            setTotalPaid(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/paid/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalPaid(response.data.totalpaid);
                        setLoadingTotalPaid(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalPaid(false)
                    }
                    setLoadingTotalPaid(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalPaid(false)
                setTotalPaid(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    // load  total balance
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalBalance(true)
            setTotalBalance(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/balance/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalBalance(response.data.totalbalance);
                        setLoadingTotalBalance(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalBalance(false)
                    }
                    setLoadingTotalBalance(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalBalance(false)
                setTotalBalance(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    // load  total refund
    useEffect(()=>{
        let doneloading=true;
        if (doneloading) {
            setLoadingTotalRefund(true)
            setTotalRefund(0);
        }
        const getDashStats = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/stats/refund/${month}`)
            .then(response=>{
                if (doneloading) {
                    if(response.data.status=== 200){
                        setTotalRefund(response.data.totalrefund);
                        setLoadingTotalRefund(false)
                    }
                    else if(response.data.status=== 401){
                        setLoggedOff(true);    
                        Swal("Error",response.data.message,"error");
                    }
                    else if(response.data.status=== 500){
                        Swal("Error",response.data.message,"error");
                    }
                    else{
                        setLoadingTotalRefund(false)
                    }
                    setLoadingTotalRefund(false)
                }
            })
            .catch(error=>{
                if(error.message==="Request failed with status code 401"){
                    setLoggedOff(true);                    
                }
                else if(error.message==="Request failed with status code 500"){
                    setLoggedOff(true);                    
                }
                else{
                    Swal("Error",""+error,"error");
                }
                setLoadingTotalRefund(false)
                setTotalRefund(0)
            })
        };
        getDashStats();

        return ()=>{
            doneloading=false;
        }
    },[selectedmonth])

    

    // useEffect(()=>{
    //     let doneloading=true;
    //     setLoadingStats(true)
    //     const getDashStats = async (e) => { 
    //         const sno=(selectedmonth==='')?0:selectedmonth;
    //         const month=(!loadingmonths)?prevmonths[sno].month:0;
    //         await axios.get(`/api/dash/${month}`)
    //         .then(response=>{
    //             if (doneloading) {
    //                 if(response.data.status=== 200){
    //                     setStats(response.data.stats);
    //                     setLoadingStats(false)
    //                 }
    //             }
    //         })
    //         .catch(error=>{
    //             setLoadingStats(false)
    //             setStats([]);
    //         })
    //     };
    //     getDashStats();

    //     return ()=>{
    //         doneloading=false;
    //     }
    // },[selectedmonth])

    // chart insights
    useEffect(()=>{
        let doneloading=true;
        setMonthlyInsightsStatus(false)
        const getMonthlyInsights = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/insights/${month}`)
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
    },[selectedmonth])

    //monthly rent and garbage insights
    useEffect(()=>{
        let doneloading=true;
        setMonthlyInsightsStatusRent(false)
        const getMonthlyInsights = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/insights/rents/${month}`)
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
    },[selectedmonth,monthlyinsightsrentrefresh])

    useEffect(()=>{
        let doneloading=true;
        setLoadingWater(true)
        const getWaterbill = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/water/${month}`)
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
    },[selectedmonth])

    useEffect(()=>{
        let doneloading=true;
        setLoadingPayment(true)
        const getPayments = async (e) => { 
            const sno=(selectedmonth==='')?0:selectedmonth;
            const month=(!loadingmonths)?prevmonths[sno].month:0;
            await axios.get(`/api/dash/payments/${month}`)
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
    },[selectedmonth])

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

    const handlePaymentShow = (names,id) => {
        setShowDownloadPayments(true);
        setProperty(names);
        setPropertyId(id);
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

    if(loadingmonths){
        prevmonthslist= (
            <Spinner animation="grow" variant="primary" size="lg" role="status"></Spinner>
        );
    }
    

  return (
    <>
    <div className="wrapper">
        <DashNavBar setClosed={setClosed} closed={closed} active='home'/>
        <DashSideNavBar setClosed={setClosed} closed={closed} active='home'/>
        
        {/* className={`nav-link ${active==='home'?'active':''}`} */}
        
        <main className="py-3">
            <div className={`content-wrapper ${closed?'closed':''}`} style={{"paddingTop": "10px"}}>
                {loggedoff ? 
                    <ReLogin setLoggedOff={setLoggedOff} loggedoff={loggedoff} />
                :
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
                                            
                                        </div>

                                        <div className="card-body text-center m-0 p-1 mt-2" style={{"paddingTop": "10px"}}>
                                            <div className="col-md-12 m-0 p-0 mt-2 mb-2 justify-content-center">
                                                <div className="row justify-content-center m-0 p-0">
                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div className='elevation-1 border-light p-1'>Properties</div>
                                                            <div className='bold text-lime text-sm p-1'>
                                                                {loadingtotalproperties &&
                                                                    <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                }
                                                                {selectedmonth !== '' &&
                                                                    !loadingtotalproperties && totalproperties
                                                                }
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div className='elevation-1 border-light p-1'>Houses</div>
                                                            <div className='bold text-lime text-sm p-1'>
                                                                {loadingtotalhouses &&
                                                                    <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                }
                                                                {selectedmonth !== '' &&
                                                                    !loadingtotalhouses && totalhouses
                                                                }
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div className='elevation-1 border-light p-1'>Tenants</div>
                                                            <div className='bold text-lime text-sm p-1'>
                                                                {loadingtotaltenants &&
                                                                    <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                }
                                                                {selectedmonth !== '' &&
                                                                    !loadingtotaltenants && totaltenants
                                                                } 
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div className='elevation-1 border-light p-1'>Occupied</div>
                                                            <div className='bold text-lime text-sm p-1'>
                                                                {loadingtotaloccupiedhouses &&
                                                                    <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                }
                                                                {selectedmonth !== '' &&
                                                                    !loadingtotaloccupiedhouses && totaloccupiedhouses
                                                                }  
                                                            </div>
                                                        </div>    
                                                    </div>
                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div className='elevation-1 border-light p-1'>Vacant</div>
                                                            <div className='bold text-lime text-sm p-1'>
                                                                {loadingtotalvacanthouses &&
                                                                    <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                }
                                                                {selectedmonth !== '' &&
                                                                !loadingtotalvacanthouses && totalvacanthouses
                                                                }  
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                        <div className='m-0 p-0 mr-2 border-white-b '>
                                                            <div className='elevation-1 border-light p-1'>Doubles</div>
                                                            <div className='bold text-lime text-sm p-1'>
                                                                {loadingtotaldoublehouses &&
                                                                    <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                                }
                                                                {selectedmonth !== '' &&
                                                                    !loadingtotaldoublehouses && totaldoublehouses
                                                                }  
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <div className="col-md-12 m-0 p-0 mb-4">
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

                                        
                                    </div>
                                </div>

                                <div className="col-md-12 m-0 p-0 mb-4 justify-content-center">
                                    <div className="row justify-content-center m-0 p-0">
                                    {selectedmonth !== '' &&  (totalrent + totalrentd) >0 &&
                                        <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                            <div className='m-0 p-0 mr-2 border-white-b '>
                                                {selectedmonth !== '' &&  totalrent >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>Rent</div>
                                                        <div className='bold p-1 text-info text-sm'>
                                                            {loadingtotalrent &&
                                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                            }
                                                            {selectedmonth !== '' &&
                                                                !loadingtotalrent && 'Kshs. '+totalrent
                                                            }  
                                                        </div>
                                                    </div>
                                                }
                                                {selectedmonth !== '' &&  totalrentd >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>Rent D</div>
                                                        <div className='bold p-1 text-info text-sm'>
                                                            {loadingtotalrentd &&
                                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                            }
                                                            {selectedmonth !== '' &&
                                                                !loadingtotalrentd && 'Kshs. '+totalrentd
                                                            }  
                                                        </div>
                                                    </div>
                                                }
                                            </div>
                                        </div>
                                        }
                                        {selectedmonth !== '' &&  (totalwaterbill + totalwaterd) >0 &&
                                            <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                <div className='m-0 p-0 mr-2 border-white-b '>
                                                    {selectedmonth !== '' &&  totalwaterbill >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>Waterbill</div>
                                                        <div className='bold p-1 text-info text-sm'>
                                                            {loadingtotalwaterbill &&
                                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                            }
                                                            {selectedmonth !== '' &&
                                                                !loadingtotalwaterbill && 'Kshs. '+totalwaterbill
                                                            } 
                                                        </div>
                                                    </div>
                                                    }
                                                    {selectedmonth !== '' &&  totalwaterd >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>Water D</div>
                                                    <div className='bold p-1 text-info text-sm'>
                                                        {loadingtotalwaterd &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalwaterd && 'Kshs. '+totalwaterd
                                                        }  
                                                    </div>
                                                    
                                                    </div>
                                                    }
                                                </div>
                                            </div>
                                        }
                                        {selectedmonth !== '' &&  (totalgarbage + totalkplcd) >0 &&
                                            <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                <div className='m-0 p-0 mr-2 border-white-b '>
                                                    {selectedmonth !== '' &&  totalgarbage >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>Garbage</div>
                                                    <div className='bold p-1 text-info text-sm'>
                                                        {loadingtotalgarbage &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalgarbage && 'Kshs. '+totalgarbage
                                                        } 
                                                    </div>
                                                    
                                                    </div>
                                                    }
                                                    {selectedmonth !== '' &&  totalkplcd >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>KPLC D</div>
                                                    <div className='bold p-1 text-info text-sm'>
                                                        {loadingtotalkplcd &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                        !loadingtotalkplcd && 'Kshs. '+totalkplcd
                                                        }  
                                                    </div>
                                                    
                                                    </div>
                                                    }
                                                </div>
                                            </div>
                                        }
                                        {selectedmonth !== '' &&  (totalarrears + totalexcess) >0 &&
                                            <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                <div className='m-0 p-0 mr-2 border-white-b '>
                                                    {selectedmonth !== '' &&  totalarrears >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>Arrears</div>
                                                    <div className='bold p-1 text-info text-sm'>
                                                        {loadingtotalarrears &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalarrears && 'Kshs. '+totalarrears
                                                        }  
                                                    </div>
                                                    
                                                    </div>
                                                    }
                                                    {selectedmonth !== '' &&  totalexcess >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>Excess</div>
                                                    <div className='bold p-1 text-info text-sm'>
                                                        {loadingtotalexcess &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalexcess && 'Kshs. '+totalexcess
                                                        } 
                                                    </div>
                                                    
                                                    </div>
                                                    }
                                                </div>
                                            </div>
                                        }
                                        {selectedmonth !== '' &&  (totalbilled + totalpaid) >0 &&
                                            <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                <div className='m-0 p-0 mr-2 border-white-b '>
                                                    {selectedmonth !== '' &&  totalbilled >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>Billed</div>
                                                    <div className='bold p-1 text-info text-sm'>
                                                        {loadingtotalbilled &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalbilled && 'Kshs. '+totalbilled
                                                        } 
                                                    </div>
                                                    
                                                    </div>
                                                    }
                                                    {selectedmonth !== '' &&  totalpaid >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>Paid</div>
                                                    <div className='bold p-1 text-info text-sm'>
                                                        {loadingtotalpaid &&
                                                            <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                        }
                                                        {selectedmonth !== '' &&
                                                            !loadingtotalpaid && 'Kshs. '+totalpaid
                                                        }  
                                                    </div>
                                                    
                                                    </div>
                                                    }
                                                </div>
                                            </div>
                                        }
                                        {selectedmonth !== '' &&  (totalbalance + totalrefund) >0 &&
                                            <div className='col-4 col-lg-2 col-md-3 m-0 p-0  mb-1 text-center'>
                                                <div className='m-0 p-0 mr-2 border-white-b '>
                                                    {selectedmonth !== '' &&  totalbalance >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>Balance</div>
                                                        <div className='bold p-1 text-info text-sm'>
                                                            {loadingtotalbalance &&
                                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                            }
                                                            {selectedmonth !== '' &&
                                                            !loadingtotalbalance && 'Kshs. '+totalbalance
                                                            }  
                                                        </div>
                                                    
                                                    </div>
                                                    }
                                                    {selectedmonth !== '' &&  totalrefund >0 &&
                                                    <div>
                                                        <div className='elevation-1 border-light'>To Refund</div>
                                                        <div className='bold p-1 text-info text-sm'>
                                                            {loadingtotalrefund &&
                                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                                            }
                                                            {selectedmonth !== '' &&
                                                                !loadingtotalrefund && 'Kshs. '+totalrefund
                                                            }  
                                                        </div>
                                                    
                                                    </div>
                                                    }
                                                </div>
                                            </div>
                                        }
                                        
                                    </div>
                                    
                                </div>


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
                                

                                
                                {/* <Button variant='primary' onClick={()=>{handleShow("D1","2")}}>
                                    Show Modal
                                </Button> */}
                                <Modal show={show} onHide={handleClose} className="mt-4">
                                    <Modal.Header className='justify-content-center bg-info m-0 p-2' closeButton>
                                        <Modal.Title className='mx-auto text-white'> 
                                            <h5>Download {property} Waterbill </h5>
                                        </Modal.Title>
                                    </Modal.Header>
                                    <Modal.Body>
                                        <div className="card-box-links m-0 p-1 justify-content-center text-center">
                                            <div className="row m-0 p-0 justify-content-center text-center">
                                                <div className='col-12 p-0 m-1'>
                                                    <a target='_blank' href={process.env.REACT_APP_BACKEND_API_URL+'/properties/downloads/Reports/Waterbill/'+propertyid+'/'+ (!loadingmonths && prevmonths && prevmonths[selectedmonth].month)} className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {property} Waterbill for {!loadingmonths && prevmonths && prevmonths[selectedmonth].monthname} </small> </i> 
                                                    </a>
                                                </div>
                                                
                                                {!loadingwater && currentyearup != '' &&
                                                    <div className='col-12 p-0 m-1'>
                                                        <a target='_blank' href={process.env.REACT_APP_BACKEND_API_URL+'/properties/downloads/Reports/Waterbill/'+propertyid+'/Year/'+currentyearup} className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {currentyearup} Waterbill</small> </i>
                                                        </a>
                                                    </div>
                                                }

                                                {!loadingwater && currentyear != '' &&
                                                    <div className='col-12 p-0 m-1'>
                                                        <a target='_blank' href={process.env.REACT_APP_BACKEND_API_URL+'/properties/downloads/Reports/Waterbill/'+propertyid+'/Year/'+currentyear} className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {currentyear} Waterbill</small> </i>
                                                        </a>
                                                    </div>
                                                }
                                                {!loadingwater && currentyeardown != '' &&
                                                    <div className='col-12 p-0 m-1'>
                                                        <a target='_blank' href={process.env.REACT_APP_BACKEND_API_URL+'/properties/downloads/Reports/Waterbill/'+propertyid+'/Year/'+currentyeardown} className="p-2 m-0 pl-1 pr-1 btn btn-block btn-outline-primary"> <i className='fa fa-download text-md'> <small> {currentyeardown} Waterbill</small> </i>
                                                        </a>
                                                    </div>
                                                }
                                            </div>
                                        </div>
                                    </Modal.Body>
                                    <Modal.Footer className='justify-content-center bg-light'>
                                        <Button variant='secondary' onClick={handleClose}>
                                            Close
                                        </Button>
                                    </Modal.Footer>
                                </Modal>

                                <Modal show={showdownloadpayments} onHide={handlePaymentClose} className="mt-4">
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
                                </Modal>


                            </div>
                        </div>

                        <div className="col-lg-4">
                            <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                <div className="card border-white-b" >
                                    <div className="card-body text-center m-1 mb-3 p-2">
                                        
                                        <div className='text-md bold text-center weight-bold'>
                                            Water Bill 
                                            {loadingmonths &&
                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                            }
                                            
                                            {selectedmonth !== '' &&
                                                !loadingmonths && prevmonths && " "+prevmonths[selectedmonth].monthname
                                            }
                                            
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
                            </div>

                            <div className="col-md-12 m-0 p-0 mt-2 mb-4">
                                <div className="card border-white-b" >
                                    

                                    <div className="card-body text-center m-1 mb-3 p-2">
                                        <div className='text-md bold text-center weight-bold'>
                                            Payments  
                                            {loadingmonths &&
                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                            }
                                            
                                            {selectedmonth !== '' &&
                                                !loadingmonths && prevmonths && " "+prevmonths[selectedmonth].monthname
                                            }
                                            
                                        </div>
                                        
                                        {loadingpayment &&
                                            <Carousel className='' >
                                                <Spinner  variant="info" size="sm" role="status"></Spinner>
                                            </Carousel>
                                        }

                                        {!loadingpayment &&
                                            <LoadPayments payments={payments} handleShow={handleShow} />
                                        }
                                            
                                    </div>
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
                                            {/* action, activeStartDate, value, view */}
                                                <Calendar 
                                                // selectRange={true}
                                                views={['month']}
                                                onClickMonth={handleMonthChange}
                                                onClickYear={handleYearChange}
                                                onChange={handleDateChange}
                                                value={selectedDate}/>
                                            </div>
                                            {/* {date.length > 0 ? (
                                            <p>
                                                <span>Start:</span>{' '} {date[0].toDateString()}
                                                &nbsp; to &nbsp;
                                                <span>End:</span> {date[1].toDateString()}
                                            </p>
                                                    ) : (
                                            <p>
                                                <span>Default selected date:</span>{' '} {date.toDateString()}
                                            </p>
                                                    )} */}
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
            }
            </div>
        </main>


        <DashFooter />
      </div>
    </>
  );
}

export default Dashboard;