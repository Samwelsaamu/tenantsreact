import Button from 'react-bootstrap/Button';
import Container from 'react-bootstrap/Container';
import Form from 'react-bootstrap/Form';
import logo from '../../assets/img/wagitonga.png'


import {Link, Navigate} from 'react-router-dom';
function HomeNavBar({active}) {
  
  return (
    <>
        <nav className="navbar navbar-expand-md bg-primary fixed-top shadow-sm">
          <div className="container text-center">
                    <Link className="navbar-brand" to="/">
                        <img src={logo} alt="Wagitonga Logo" className="brand-image elevation-1 m-0 " style={{"opacity": "1","width":"120px","borderRadius": "10px 10px 1px 1px","marginRight": "1%"}}/>
                    </Link>
                    
                    <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation">
                        <span className="navbar-toggler-icon"></span>
                    </button>

                  <div className="collapse navbar-collapse" id="navbarSupportedContent">
                      {/* <!-- Left Side Of Navbar --> */}
                      <ul className="navbar-nav mx-auto" >
                          <li className="nav-item">
                              <Link className={`nav-link ${active==='home'?'active':''}`} to="/">Home</Link>
                          </li>
                          <li className="nav-item">
                              <Link className={`nav-link ${active==='properties'?'active':''}`} to="/allproperties">Properties</Link>
                          </li>

                          <li className="nav-item">
                              <Link className={`nav-link ${active==='aboutus'?'active':''}`} to="/aboutus">About</Link>
                          </li>

                          <li className="nav-item">
                              <Link className={`nav-link ${active==='contactus'?'active':''}`} to="/contactus">Contacts</Link>
                          </li>

                          <li className="nav-item">
                              <Link className={`nav-link ${active==='gallery'?'active':''}`} to="/gallery">Gallery</Link>
                          </li>
                          <Form.Control
                            type="search"
                                placeholder="Search"
                                className="me-1"
                                aria-label="Search"
                            />
                      </ul>

                      {/* <ul className="navbar-nav mx-auto" > */}
                         
                            {/* <Form.Control
                            type="search"
                                placeholder="Search"
                                className="me-1"
                                aria-label="Search"
                            /> */}
                            {/* <Button bg="info" variant="info"><i className="fas fa-search"></i></Button> */}
                       
                      {/* </ul> */}

                     

                      {/* <!-- Right Side Of Navbar --> */}
                      <ul className="navbar-nav ml-auto">
                          {/* <!-- Authentication Links --> */}
                          
                                <li className="nav-item">
                                    <Link className={`nav-link ${active==='login'?'active':''}`} to="/login">Login</Link>
                                </li>
                            
                                {/* <li className="nav-item">
                                    <Link className={`nav-link ${active==='register'?'active':''}`} to="/register">Register</Link>
                                </li> */}
                             
                      </ul>
                  </div>
              </div>
          </nav>

          
      {/* </div> */}
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

export default HomeNavBar;