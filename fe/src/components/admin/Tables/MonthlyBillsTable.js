import {Link, Navigate, useNavigate} from 'react-router-dom';

function MonthlyBillsTable({waterbill,no,handleShow,uploadwaterbill,allchecked,handleChange}) {
    
  return (
    <tr className={`unwaterbillvaluesdiv m-0 p-0 ${(uploadwaterbill.upwaterbill.filter( (f) => f === waterbill.id+'?'+waterbill.housename+'?'+waterbill.tid+'?'+waterbill.tenantname+'?'+waterbill.pid+'?'+waterbill.paymentid+'?')).length ?'bg-secondary text-info':(waterbill.saved=='No'?'bg-light text-dark':'bg-warning text-dark')}`}
      key={no} >
        {/* <td className='m-0 p-0'>{no+1}</td> */}
        <td className='m-0 p-1'>{no+1}</td>
        
        <th className='m-0 p-1'>{waterbill.housename}</th>
        <td className='m-0 p-0' title={waterbill.tenantname}>{waterbill.tenantfname}</td>
        <td className='m-0 p-1'>{new Number((waterbill.Arrears-waterbill.Excess)).toFixed(2)}</td>
        <td className={`m-0 p-1 ${(waterbill.Rent>0)?'bg-warning text-dark':'bg-light text-dark'}`}>{new Number(waterbill.Rent).toFixed(2)}</td>
        <td className={`m-0 p-1 ${(waterbill.Rent>0)?'bg-warning text-dark':'bg-light text-dark'}`}>{new Number(waterbill.Garbage).toFixed(2)}</td>
        <td className='m-0 p-1'>{new Number(waterbill.Waterbill).toFixed(2)}</td>
        <td className='m-0 p-1' title='House Deposit + KPLC Deposit + Water Deposit'>
                                {new Number(waterbill.HseDeposit).toFixed(2)}
                                +{new Number(waterbill.KPLC).toFixed(2)}
                                +{new Number(waterbill.Water).toFixed(2)}</td>
        <td className='m-0 p-1'>{new Number(waterbill.Lease).toFixed(2)}</td>
        <td className='m-0 p-1'>{new Number(((waterbill.Rent)
                                +(waterbill.Arrears)
                                +(waterbill.Garbage)
                                +(waterbill.Waterbill)
                                +(waterbill.HseDeposit)
                                +(waterbill.KPLC)
                                +(waterbill.Water)
                                +(waterbill.Lease))
                                -(waterbill.Excess)).toFixed(2)}</td>

        <td className='m-0 p-1'>
        {waterbill.saved==='Yes' && <button className='btn btn-warning m-0 p-0 pl-2 pr-2' onClick={()=>{handleShow(waterbill)}}><small>Edit</small></button>}
        {waterbill.saved==='No' && <button className='btn btn-info m-0 p-0 pl-2 pr-2 text-white' onClick={()=>{handleShow(waterbill)}}><small>Add</small></button>} 
        </td>

    </tr>
  );
}

export default MonthlyBillsTable;