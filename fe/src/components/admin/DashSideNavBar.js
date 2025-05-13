import { useState } from 'react';
import logo from '../../assets/img/wagitonga.png';
import { Link, Navigate } from 'react-router-dom';
import axios from 'axios';

function DashSideNavBar({setClosed,closed,active}) {
    const [menuopenproperties,setMenuOpenProperties]=useState(false)
    const [menuopenservices,setMenuOpenServices]=useState(false)
    const [menuopenmessages,setMenuOpenMessages]=useState(false)
    const [menuopenmail,setMenuOpenMail]=useState(false)
    const [menuopenothers,setMenuOpenOthers]=useState(false)
    const [menuopendepositrefunds,setMenuOpenDepositRefunds]=useState(false)

    const [redirect,setRedirect]=useState(false);
    const [url,setUrl]=useState('');

    const handleLogout=(e) =>{
        e.preventDefault();
            axios.post('/v2/logout')
            .then(response=>{
                if(response.data.status=== 200){
                    localStorage.removeItem('auth_token');
                    localStorage.removeItem('auth_name');
                    // Swal("Success",response.data.message,"success");
                    setRedirect(true);
                    setUrl('/login');
                }

            })
            .catch((error)=>{
                // Swal("Not Logged Out",""+error.message,"error");
                localStorage.removeItem('auth_token');
                localStorage.removeItem('auth_name');
                setRedirect(true);
                setUrl('/login');
            })
    }

    if(redirect){
        return <Navigate to={url} />
    }
  return (
    <>
        <aside className={`main-sidebar main-sidebar-custom elevation-4 ${closed?'closed':''}`}>
            <Link to="/dashboard" className="brand-link navbar-info  border-none row ml-1 mt-0 mr-1 mb-1 p-0 ">
                <div className="col-12 m-0 p-0 mt-1 image brand-text justify-item-center mx-auto">
                    <img src={logo} alt="Wagitonga Logo" className="brand-image elevation-3 m-0 p-0 " style={{"opacity": "1","width":"100%","borderRadius": "15px 15px 5px 5px"}}/>
                </div>
                
                <div className={`col-12 ${closed?'logo-name-hidden':''} font-weight-bold text-center text-dark p-1 m-0 mt-1`}><span>Wagitonga Agency Limited</span></div>
                
            </Link>
            
            <div className="sidebar  m-1 p-0 border-none">
                
            
                <nav className="mt-2">
                    <ul className={`nav nav-pills nav-sidebar flex-column ${closed?'closed':''}`} data-widget="treeview" role="menu" data-accordion="false">
                    
                        <li className="nav-item mt-1">
                            <Link to="/dashboard" className={`nav-link ${active==='home'?'active':''} m-0 p-1`}>
                                <p>
                                    <i className="nav-icon fa fa-home"></i>
                                    <span>Dashboard</span>
                                </p>
                            </Link>
                            
                        </li>

                        {/* <li className="nav-item mt-1">
                        <div aria-live='polite'
                            aria-atomic='true'
                            className='toast-div bg-dark position-relative'>
                                dfd

                        </div>
                        </li> */}

                        <li className={`nav-item ${menuopenproperties?'menu-closed':'menu-open'} mt-1`}>
                            <Link to="#" className="nav-link  m-0 p-1" onClick={()=>{setMenuOpenProperties(!menuopenproperties)}}>
                                <p>
                                    <i className="nav-icon fa fa-university"></i>
                                    <span>Properties</span>
                                </p>
                                <i className="fas fa-angle-left right"></i>
                            </Link>
                            
                            <ul className={`nav nav-treeview ${menuopenproperties?'menu-closed':'menu-open'} pt-1`}>

                            
                                <li className="nav-item">
                                    <Link to="/properties/manage" className={`nav-link ${active==='manage'?'active':''}`}>
                                    <i className="fa fa-sitemap nav-icon"></i>
                                    <p>Manage Properties</p>
                                    </Link>
                                </li>

                                <li className="nav-item">
                                    <Link to="/properties/manage/all" className={`nav-link`}>
                                    <i className="fa fa-building nav-icon"></i>
                                    <p>Manage Houses</p>
                                    </Link>
                                </li>
                                <li className="nav-item">
                                    <Link to="/properties/mgr/tenants" className={`nav-link ${active==='tenant'?'active':''}`}>
                                    <i className="fa fa-address-card nav-icon"></i>
                                    <p>Manage Tenants</p>
                                    </Link>
                                </li>

                                

                                {/* <li className="nav-item">
                                    <Link to="/properties/update/bills" className="nav-link">
                                    <i className="fa fa-briefcase nav-icon"></i>
                                    <p>Update Monthly Bills</p>
                                    </Link>
                                </li>

                                <li className="nav-item">
                                    <Link to="/properties/View/Reports" className="nav-link">
                                    <i className="fa fa-truck nav-icon"></i>
                                    <p>View Reports</p>
                                    </Link>
                                </li> */}

                            </ul>
                        </li>

                        <li className={`nav-item ${menuopenservices?'menu-closed':'menu-open'} mt-1`}>
                            <Link to="#" className="nav-link m-0 p-1" onClick={()=>{setMenuOpenServices(!menuopenservices)}}>
                                <p>
                                    <i className="nav-icon fa fa-tint"></i>
                                    <span>Services & Bills</span>
                                </p>
                                
                                <i className="fas fa-angle-left right"></i>
                            </Link>
                            <ul className={`nav nav-treeview ${menuopenservices?'menu-closed':'menu-open'} pt-1`}>

                            <li className="nav-item">
                                <Link to="/properties/update/waterbill" className={`nav-link ${active==='waterbill'?'active':''}`}>
                                    <i className="fa fa-water nav-icon"></i>
                                    <p>Water Bill</p>
                                </Link>
                            </li>

                            <li className="nav-item">
                                <Link to="/properties/update/rentandgarbage" className={`nav-link ${active==='rent'?'active':''}`}>
                                    <i className="fa fa-layer-group nav-icon"></i>
                                    <p>Monthly Bills</p>
                                </Link>
                            </li>

                            {/* <li className="nav-item">
                                <Link to="/properties/update/monthlybills" className={`nav-link ${active==='monthlybills'?'active':''}`}>
                                    <i className="fas fa-money-bill nav-icon"></i>
                                    <p>Monthly Bills</p>
                                </Link>
                            </li> */}
                            <li className="nav-item">
                                <Link to="/properties/update/newtenant/monthlybills" className={`nav-link ${active==='newtenantmonthlybills'?'active':''}`}>
                                    <i className="fa fa-users nav-icon"></i>
                                    <p>New Tenants Bills</p>
                                </Link>
                            </li>

                            </ul>
                        </li>

                        <li className={`nav-item ${menuopenmessages?'menu-closed':'menu-open'} mt-1`}>
                            <Link to="#" className="nav-link m-0 p-1" onClick={()=>{setMenuOpenMessages(!menuopenmessages)}}>
                                <p>
                                    <i className="nav-icon fa fa-comments"></i>
                                    <span>Messages</span>
                                </p>
                                <i className="fas fa-angle-left right"></i>
                            </Link>
                            <ul className={`nav nav-treeview ${menuopenmessages?'menu-closed':'menu-open'} pt-1`}>
                            
                            <li className="nav-item">
                                <Link to="/messages/new" className={`nav-link ${active==='newmessage'?'active':''}`}>
                                <i className="fas fa-comment nav-icon"></i>
                                <p>Compose Message</p>
                                </Link>
                            </li>

                            
                            <li className="nav-item">
                                <Link to="/messages/tenant" className={`nav-link ${active==='tenantmessages'?'active':''}`}>
                                <i className="fa fa-paper-plane nav-icon"></i>
                                <p>Message to Tenant</p>
                                </Link>
                            </li>

                            <li className="nav-item">
                                <Link to="/messages/water" className={`nav-link ${active==='waterbillmessages'?'active':''}`}>
                                <i className="far fa-comment-alt nav-icon"></i>
                                <p>Send Waterbill</p>
                                </Link>
                            </li>
                            
                            {/* <li className="nav-item" disabled>
                                <Link to="#" className={`nav-link ${active==='paymentmessages'?'active':''}`}>
                                /messages/payments
                                <i className="far fa-comments nav-icon"></i>
                                <p>Acknowledge Payments</p>
                                Send Notification for Money Paid through MPESA, Cash, Bank, Cheque etc,
                                </Link>
                            </li> */}
                            

                            <li className="nav-item">
                                <Link to="/messages/reminders" className={`nav-link ${active==='rentremainders'?'active':''}`}>
                                <i className="far fa-comment-dots nav-icon"></i>
                                <p>Payment Remainders</p>
                                {/* Notify Tenant of paying Rent, Waterbill and other Due Bills */}
                                </Link>
                            </li>

                            {/* <li className="nav-item">
                                <Link to="#" className={`nav-link ${active==='messagessummary'?'active':''}`}>
                                /messages/summary
                                <i className="fas fa-sms nav-icon"></i>
                                <p>Messages Summary</p>
                                A One Way Stop to review all messages sent per Property or House or Tenant or Phone Number
                                </Link>
                            </li> */}
                            

                            {/* Send Single Water
                            Send all Water 
                            Send Completed Payment
                            Send Summary Paid
                            Choose Tenant      
                            Choose Rent
                            Send All Tenant  */}

                                
                            </ul>
                        </li>

                        <li className={`nav-item ${menuopendepositrefunds?'menu-closed':'menu-open'} mt-1`}>
                            <Link to="#" className="nav-link m-0 p-1" onClick={()=>{setMenuOpenDepositRefunds(!menuopendepositrefunds)}}>
                                <p>
                                    <i className="nav-icon fa fa-cash-register"></i>
                                    <span>Deposits & Refunds</span>
                                </p>
                                <i className="fas fa-angle-left right"></i>
                            </Link>
                            <ul className={`nav nav-treeview ${menuopendepositrefunds?'menu-closed':'menu-open'} pt-1`}>
                            
                                <li className="nav-item">
                                    <Link to="/properties/mgr/refunds" className={`nav-link ${active==='refunds'?'active':''}`}>
                                    <i className="fas fa-cash-register nav-icon"></i>
                                    <p>Manage Refunds</p>
                                    </Link>
                                </li>

                                <li className="nav-item">
                                    <Link to="/properties/mgr/deposits" className={`nav-link ${active==='deposits'?'active':''}`}>
                                    <i className="fas fa-briefcase nav-icon"></i>
                                    <p>Manage Deposits</p>
                                    </Link>
                                </li>

                                <li className="nav-item">
                                    <Link to="/properties/mgr/leases" className={`nav-link ${active==='leases'?'active':''}`}>
                                    <i className="fas fa-money-check nav-icon"></i>
                                    <p>Manage Lease</p>
                                    </Link>
                                </li>

                            </ul>
                        </li>


                        <li className={`nav-item ${menuopenmail?'menu-closed':'menu-open'} mt-1`}>
                            <Link to="#" className="nav-link m-0 p-1" onClick={()=>{setMenuOpenMail(!menuopenmail)}}>
                                <p>
                                    <i className="nav-icon fa fa-envelope"></i>
                                    <span>Mail</span>
                                </p>
                                <i className="fas fa-angle-left right"></i>
                            </Link>
                            <ul className={`nav nav-treeview ${menuopenmail?'menu-closed':'menu-open'} pt-1`}>
                            
                            <li className="nav-item">
                                <Link to="/mail/getmail" className="nav-link">
                                <i className="fa fa-inbox nav-icon"></i>
                                <p>Mails</p>
                                </Link>
                            </li>
                            </ul>
                        </li>
                        <li className={`nav-item ${menuopenothers?'menu-closed':'menu-open'} mt-1`}>
                            <Link to="#" className="nav-link m-0 p-1" onClick={()=>{setMenuOpenOthers(!menuopenothers)}}>
                                <p>
                                    <i className="nav-icon fa fa-cogs"></i>
                                    <span>Others</span>
                                </p>
                                <i className="fas fa-angle-left right"></i>
                            </Link>
                            <ul className={`nav nav-treeview ${menuopenothers?'menu-closed':'menu-open'} pt-1`}>
                            
                            {/* <li className="nav-item">
                                <Link to="/properties/frequentlyasked" className="nav-link">
                                <i className="nav-icon fas fa-th"></i>
                                <p>
                                    FAQs
                                </p>
                                </Link>
                            </li>
                            <li className="nav-item">
                                <Link className="nav-link" to="/profile/change-password">
                                <i className="fas fa-lock nav-icon"></i>
                                <p>Change Password</p>
                                </Link>
                            </li>
                            <li className="nav-item">
                                <Link className="nav-link" to="/users/create">
                                <i className="fas fa-plus nav-icon nav-icon"></i> 
                                <p>New User</p>
                                </Link>
                            </li>
                            <li className="nav-item">
                                <Link className="nav-link" to="/users">
                                <i className="fas fa-users nav-icon nav-icon"></i> 
                                <p>Users</p>  
                            </Link>
                            </li> */}
                            <li className="nav-item">
                                <Link className="nav-link" to="/settings">
                                <i className="fas fa-tree nav-icon"></i> 
                                <p>Settings</p>
                                </Link>
                            </li>

                            </ul>
                        </li>
                    
                    
                    </ul>
                </nav>
            
            </div>
            
            <div className="sidebar-custom bg-primary border-none m-1 p-1">

                <Link className="btn bg-success pos-right p-1 m-1 ml-2" to="/users" title='View Users' >
                    <i className="fa fa-users nav-icon"></i>
                </Link>
                
                <Link className="btn btn-success p-1 m-1 ml-2" to="/users/create" title='Create New User' >
                    <i className="fa fa-user-plus nav-icon"></i>
                </Link>

                <Link className="btn btn-success p-1 m-1 ml-2" to="/properties/frequentlyasked" title='View FAQs' >
                    {/* <i className="fa fa-users nav-icon"> </i> */}
                    FAQS
                </Link>

                <Link className="btn btn-danger p-1 m-1 ml-2" to="#" title='Click to Logout' onClick={handleLogout}>
                    <i className="fa fa-power-off nav-icon"></i>
                </Link>

                
            
            </div>
      </aside>
    </>
    // <Navbar bg="primary" variant="dark" expand="lg">
    //   <Container fluid>
    //     <Navbar.Brand to="#">Navbar scroll</Navbar.Brand>
    //     <Navbar.Toggle aria-controls="navbarScroll" />
    //     <Navbar.Collapse id="navbarScroll">
    //       <Nav 
    //         className="d-flex me-auto my-2 my-lg-0 justify-content-center"
    //         style={{ maxHeight: '100px' }}
    //         navbarScroll
    //       >
    //         <Nav.Link to="#action1" variant="dark">Home</Nav.Link>
    //         <Nav.Link to="#action2" variant="dark">Link</Nav.Link>
    //         <NavDropdown title="Link" id="navbarScrollingDropdown">
    //           <NavDropdown.Item to="#action3">Action</NavDropdown.Item>
    //           <NavDropdown.Item to="#action4">
    //             Another action
    //           </NavDropdown.Item>
    //           <NavDropdown.Divider />
    //           <NavDropdown.Item to="#action5">
    //             Something else here
    //           </NavDropdown.Item>
    //         </NavDropdown>
    //         <Nav.Link to="#" disabled>
    //           Link
    //         </Nav.Link>
    //       </Nav>
    //       <Form className="d-flex">
    //         <Form.Control
    //           type="search"
    //           placeholder="Search"
    //           className="me-2"
    //           aria-label="Search"
    //         />
    //         <Button variant="outline-success">Search</Button>
    //       </Form>
    //     </Navbar.Collapse>
    //   </Container>
    // </Navbar>
  );
}

export default DashSideNavBar;