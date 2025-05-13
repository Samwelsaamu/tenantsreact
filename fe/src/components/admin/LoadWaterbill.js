import Carousel from 'react-bootstrap/Carousel';
import { Link } from 'react-router-dom';
import {useState} from 'react';

function LoadWaterbill({waterbill,handleShow}) {
    
    const [prevIndex, setPrevIndex] = useState(1);
    
        const handleSelect = (selectedIndex, e) => {
            // console.log(
            //   selectedIndex > prevIndex ? 'Next clicked' : 'Previous clicked',
            //   'Selected Index:', selectedIndex, 'Previous Index:', prevIndex, 'Event:',  e
            // );
            if(selectedIndex<1){
                setPrevIndex(1);
            }
            else{
                setPrevIndex(selectedIndex);
            }
        }
    
        const handleIndicatorClick =(key) => {
            setPrevIndex(key+1)
        }

  return (
    <Carousel slide={true} keyboard={true} indicators={false} 
        activeIndex={prevIndex} onSelect={handleSelect} className='m-0'>
        <div class="carousel-indicators p-1">
            <div className='elevation-1 border-info m-0 mr-3 ml-3 p-1 pr-3 pl-3'>
                {waterbill && waterbill.map((payment,keys) => (
                    <>
                    {keys+1==prevIndex ?
                        <button type="button" className='btn btn-primary'
                            style={{"margin":"1px","padding":"0px","paddingLeft":"2px","paddingRight":"2px","zIndex":"999999"}} 
                            onClick={() => handleIndicatorClick(keys)}
                            title={payment.plotname} aria-current="false"><small>{payment.plotcode}</small></button>
                        :
                        <button type="button" className='btn btn-info'
                            style={{"margin":"1px","padding":"0px","paddingLeft":"2px","paddingRight":"2px","zIndex":"999999"}} 
                            onClick={() => handleIndicatorClick(keys)}
                            title={payment.plotname} aria-current="false"><small>{payment.plotcode}</small></button>
                    }
                    </>
                ))
                }
            </div>
        </div>
        {waterbill && waterbill.map((waters,key) => (
            <Carousel.Item key={key}>
                <div key={key} className="row m-0 mb-0 ml-4 mr-4 pl-2 pr-2">
                    <div className="col-12 m-0 mb-2 p-1">
                        <div className="card-box mb-1">
                            
                            <div className='card-icon'>
                                <span className="card-box-icon bold bg-light elevation-1">{key+1}.  </span>
                                <span className="card-box-icon bg-light bold elevation-1 mt-1">{waters.plotcode}</span>
                                <span className="card-box-icon bg-light elevation-1 mt-1"><small>Sent:{waters.totalbillsmsghse}</small></span>
                                <span className="card-box-icon bg-light elevation-1 mt-1" style={{"width":"66px"}}><small>Units:<br/><small className='text-lime bold'>{new Number(waters.totalunits).toFixed(2)}</small></small></span>
                                <span className="card-box-icon bg-light elevation-1 mt-1" style={{"width":"66px"}}><small>Units ({waters.thismonthname}:):<br/><small className='text-success bold'>{new Number(waters.thisbilltotalunits).toFixed(2)}</small></small></span>
                                {/* <span className="card-box-icon bg-light elevation-1 mt-1" style={{"width":"55px"}}><small>Units:{waters.totalunits}</small></span> */}
                            </div>

                            <div className="card-box-content">
                                {/* <p className='text-xs text-center text-lime mb-2'>{waters.plotname}</p> */}
                                <p className='text-center bg-info text-white elevation-1 border-light p-2 mb-2' style={{"textAlign": "center","whiteSpace": "nowrap","fontSize":"8px"}}>{waters.plotname}</p>
                                <span className="card-box-text"><span className='title-sm bold'>Billed: </span> {waters.totalbillshse}/{waters.totalhouseshse}  </span>
                                <div className="card-box-text" title='Billed on Selected Month but to be Paid on Next Month'><span className='title-sm bold'>{waters.nextmonthname}: </span> {new Number(waters.totals).toFixed(2)} </div>
                                <div className="card-box-text" title='Billed on Last Month but to be Paid on Selected Month'><span className='title-sm bold text-lime'>{waters.thismonthname}: </span> <span className='text-success bold'>{new Number(waters.thisbilltotals).toFixed(2)} </span> </div>
                                
                            </div>

                            <div className='card-icon'>
                                <div className="card-box-links m-0 p-0">
                                    <div className="row m-0 p-0 justify-content-center">
                                        <div className='col-12 p-0 m-1' title='Upload/Update Waterbill'>
                                            <Link to={'/properties/update/waterbill/'+waters.id+'/'+waters.month} className="p-0 m-0 pl-1 pr-1 btn btn-outline-primary"> <i className='fa fa-upload text-sm'></i></Link>
                                        </div>
                                        
                                        <div className='col-12 p-0 m-1' title='Send/View Waterbill Messages'>
                                            <Link to={'/messages/water/'+waters.id+'/'+waters.month} className="p-0 m-0 pl-1 pr-1 btn btn-outline-success"> <i className='fa fa-envelope text-sm'></i></Link>
                                        </div>

                                        <div className='col-12 p-0 m-1' title='Download Waterbill'>
                                            <Link to="#" className="p-0 m-0 pl-1 pr-1 btn btn-outline-secondary" onClick={()=>{handleShow(waters)}}> <i className='fa fa-download text-sm'></i></Link>
                                        </div>
                                    </div>
                                </div>
                            </div>


                                 {/* totalbillsmsghseonce
                                 totalbillsmsghsetwice
                                 totalbillsmsghsethrice */}


                        </div>

                        <div className="row card-box m-0 p-1 mt-2">
                            <div className='col-4 m-0 p-0  mb-1 text-center'>
                                <div className='m-0 p-0 mr-2 border-white-b ' title='Sent Once'>
                                    <div className='elevation-1 p-0 border-light text-sm'>Sent *1</div>
                                    <div className='bold p-1 text-info text-sm'>
                                        {waters.totalbillsmsghseonce}
                                    </div>
                                </div>
                            </div>

                            <div className='col-4 m-0 p-0  mb-1 text-center'>
                                <div className='m-0 p-0 mr-2 border-white-b ' title='Sent Twice'>
                                    <div className='elevation-1 p-0 border-light text-sm'>Sent *2</div>
                                    <div className='bold p-1 text-info text-sm'>
                                        {waters.totalbillsmsghsetwice}
                                    </div> 
                                </div>
                            </div>

                            <div className='col-4 m-0 p-0  mb-1 text-center'>
                                <div className='m-0 p-0 mr-2 border-white-b ' title='Sent Thrice'>
                                    <div className='elevation-1 p-0 border-light text-sm'>Sent *3</div>
                                    <div className='bold p-1 text-info text-sm'>
                                        {waters.totalbillsmsghsethrice}
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                        
                    </div>
                </div>
            </Carousel.Item>
        ))
        }
    </Carousel>
  );
}

export default LoadWaterbill;