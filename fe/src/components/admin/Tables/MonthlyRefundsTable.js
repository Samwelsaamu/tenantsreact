import {Link, Navigate, useNavigate} from 'react-router-dom';

function MonthlyRefundsTable({waterbill,no,handleShow,uploadwaterbill,allchecked,handleChange,showcheckhouse,showchecktenant}) {
    
  return (
    <tr className={`unwaterbillvaluesdiv m-0 p-0`}
      key={no} >
        
        <td className='m-0 p-1'>{no+1}</td>
        
        <th className='m-0 p-1 bg-white'>{waterbill.housename}
          {waterbill.tenantnamecheck!=='' && showcheckhouse && <><small className='text-success'><br/>{waterbill.tenantnamecheck}</small></>}
        </th>
        <td className='m-0 p-1' title={waterbill.tenantname}>{waterbill.tenantfname}
          {waterbill.housenamecheck!=='' && showchecktenant && <><small className='text-success'><br/>{waterbill.housenamecheck}</small></>}</td>
        <td className='m-0 p-1'>{new Number((waterbill.Deposit)).toFixed(2)}</td>
        <td className='m-0 p-1'>{new Number(waterbill.Arrears).toFixed(2)}</td>
        <td className='m-0 p-1' title={'Damages :'+new Number(waterbill.Damages).toFixed(2)}>{new Number(waterbill.Damages).toFixed(2)}</td>
        <td className='m-0 p-1'title={'Transaction :'+new Number(waterbill.Transaction).toFixed(2)}>{new Number(waterbill.Transaction).toFixed(2)}</td>
        <td className='m-0 p-1'title={'OtherCharges :'+new Number(waterbill.OtherCharges).toFixed(2)}>{new Number(waterbill.OtherCharges).toFixed(2)}</td>
        <td className='m-0 p-1'>{new Number((waterbill.Refund)).toFixed(2)}</td>
        <td className='m-0 p-1'>{waterbill.DateAssigned}</td>
        <td className='m-0 p-1'>{waterbill.monthname}</td>

        {/* <td className='d-flex m-0 p-0'>
         <button className='btn btn-outline-success m-1 p-0' onClick={()=>{handleShow(waterbill)}}> <small className='d-flex m-0 p-1'><i className='fa fa-edit m-0 p-1'></i> Edit</small></button>
         <button className='btn btn-outline-info m-1 p-0' onClick={()=>{handleShow(waterbill)}}> <small className='d-flex m-0 p-1'><i className='fa fa-check m-0 p-1'></i> Pay</small></button>
        </td> */}

    </tr>
  );
}

export default MonthlyRefundsTable;