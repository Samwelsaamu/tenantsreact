import Carousel from 'react-bootstrap/Carousel';
import { Link } from 'react-router-dom';
import {useState} from 'react';
function LoadPayments({payments,handlePaymentShow}) {

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
                {payments && payments.map((payment,keys) => (
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

        {payments && payments.map((payment,key) => (
            <Carousel.Item key={key}>
                <div key={key} className="row m-0 mb-0 ml-4 mr-4 pl-2 pr-2">
                    <div className="col-12 m-0 mb-2 p-0">
                        <div className="card-box mb-1">
                        {/* MonthlyArrears,MonthlyExcess,MonthlyRefund,MonthlyBilled,MonthlyPaid,MonthlyBalance */}

                            <div className='card-icon'>
                                <span className="card-box-icon bg-light elevation-1 mt-1 bold" style={{"width":"66px"}}><small className='bold'>{payment.plotcode}<br/>
                                <span className="card-box-text"><span className='text-success bold'>{payment.totalbillshse}/<span className='title-sm bold'>{payment.totalhouseshse}</span> </span></span>
                                </small> </span>
                                <span className="card-box-icon bg-light elevation-1 mt-1" style={{"width":"66px"}}> <i className='fa fa-envelope text-sm'></i> <small>Sent:{payment.totalbillsmsghse}</small></span>
                                <span className="card-box-icon bg-light elevation-1 mt-1" style={{"width":"66px"}}><small>Rent:<br/><small className='text-lime bold'>{new Number(payment.rent).toFixed(2)}</small></small></span>
                                <span className="card-box-icon bg-light elevation-1 mt-1" style={{"width":"66px"}}><small>Waterbill:<br/><small className='text-lime bold'>{new Number(payment.waterbill).toFixed(2)}</small></small></span>
                                <span className="card-box-icon bg-light elevation-1 mt-1" style={{"width":"66px"}}><small>Garbage:<br/><small className='text-lime bold'>{new Number(payment.garbage).toFixed(2)}</small></small></span>
                            </div>
                            <div className="card-box-content">
                                <p className='text-center bg-info text-white elevation-1 border-light p-2 mb-2' style={{"textAlign": "center","whiteSpace": "nowrap","fontSize":"8px"}}>{payment.plotname}</p>
                                <span className="card-box-text"><span className='title-sm bold'>Balance: </span> <span className='text-success bold'>{payment.MonthlyBalance} </span></span>
                                <span className="card-box-text"><span className='title-sm bold'>Rent D: </span> <span className='text-success bold'>{payment.hsedeposit} </span></span>
                                <span className="card-box-text"><span className='title-sm bold'>Water D: </span> <span className='text-success bold'>{payment.water} </span></span>
                                <span className="card-box-text"><span className='title-sm bold'>KPLC D: </span> <span className='text-success bold'>{payment.kplc} </span></span>
                                <span className="card-box-text"><span className='title-sm bold'>Lease: </span> <span className='text-success bold'>{payment.lease} </span></span>
                                {/* <span className="card-box-text"><span className='title-sm bold'>Billed Waterbill: </span> Kshs. {new Number(payment.totals).toFixed(2)} </span> */}
                                <span className='card-box-text'>
                                    <div className="card-box-links m-0 p-1">
                                        <div className="row m-0 p-0">
                                            <div className='col-4 p-0 m-0'>
                                                <Link to={'/properties/update/rentandgarbage/'+payment.id+'/'+payment.month} className="p-0 m-0 pl-1 pr-1 btn btn-outline-primary"> <i className='fa fa-upload text-sm'></i> </Link>
                                            </div>
                                            
                                            <div className='col-4 p-0 m-0'>
                                                <Link to={'/messages/reminders/'+payment.id+'/'+payment.month} className="p-0 m-0 pl-1 pr-1 btn btn-outline-success"> <i className='fa fa-envelope text-sm'></i> </Link>
                                            </div>

                                            <div className='col-4 p-0 m-0'>
                                                <Link to="#" className="p-0 m-0 pl-1 pr-1 btn btn-outline-secondary" onClick={()=>{handlePaymentShow(payment)}}> <i className='fa fa-download text-sm'></i> </Link>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                            <div className='card-icon'>
                            {/* MonthlyPaidEquity,MonthlyPaidCoop,MontlyOthersPaid */}
                                <span className="card-box-icon bg-light elevation-1 mt-1 p-1" style={{"width":"66px"}}>
                                    <Link to={'/properties/update/rentandgarbage/'+payment.id+'/'+payment.month}> 
                                        <small className='bold text-dark'>Billed:<br/><small className='bold text-info'>{payment.MonthlyBilled}</small></small> 
                                    </Link>
                                </span>
                                <span className="card-box-icon bg-light elevation-1 mt-1" style={{"width":"66px"}}><small>Equity:<br/><small className='bold'>{payment.MonthlyPaidEquity}</small></small></span>
                                <span className="card-box-icon bg-light elevation-1 mt-1" style={{"width":"66px"}}><small>CO-OP:<br/><small className='bold'>{new Number(payment.MonthlyPaidCoop).toFixed(2)}</small></small></span>
                                <span className="card-box-icon bg-light elevation-1 mt-1" style={{"width":"66px"}}><small>Others:<br/><small className='bold'>{payment.MontlyOthersPaid}</small></small></span>
                                <span className="card-box-icon bg-light elevation-1 mt-1" style={{"width":"66px"}}><small className='bold'>Paid:<br/><small className='bold'>{payment.MonthlyPaid}</small></small></span>
                            </div>
                        </div>
                        <div className="row card-box m-0 p-1 mt-2">
                            <div className='col-4 m-0 p-0  mb-1 text-center'>
                                <div className='m-0 p-0 mr-2 border-white-b ' title='Sent Once'>
                                    <div className='elevation-1 p-0 border-light text-sm'>Sent *1</div>
                                    <div className='bold p-1 text-info text-sm'>
                                        {payment.totalbillsmsghseonce}
                                    </div>
                                </div>
                            </div>

                            <div className='col-4 m-0 p-0  mb-1 text-center'>
                                <div className='m-0 p-0 mr-2 border-white-b ' title='Sent Twice'>
                                    <div className='elevation-1 p-0 border-light text-sm'>Sent *2</div>
                                    <div className='bold p-1 text-info text-sm'>
                                        {payment.totalbillsmsghsetwice}
                                    </div> 
                                </div>
                            </div>

                            <div className='col-4 m-0 p-0  mb-1 text-center'>
                                <div className='m-0 p-0 mr-2 border-white-b ' title='Sent Thrice'>
                                    <div className='elevation-1 p-0 border-light text-sm'>Sent *3</div>
                                    <div className='bold p-1 text-info text-sm'>
                                        {payment.totalbillsmsghsethrice}
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

export default LoadPayments;