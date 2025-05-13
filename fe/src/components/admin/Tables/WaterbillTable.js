import {Link, Navigate, useNavigate} from 'react-router-dom';

function WaterbillTable({waterbill,no,preview,handleShow}) {
    
  return (
    <tr key={no} style={{"padding":"0px","margin":"0","backgroundColor":"#FFFFFF"}}>
        <td className='m-0 p-1'>{no+1}</td>
        <th className='m-0 p-1'>{waterbill.housename}</th>
        <td className='m-0 p-0' title={waterbill.tenantname}>{waterbill.tenantfname}</td>
        <td className='m-0 p-1'>{waterbill.previous}</td>
        <td className='m-0 p-1'>{waterbill.current}</td>
        <td className='m-0 p-1'>{new Number(waterbill.cost).toFixed(2)}</td>
        <td className='m-0 p-1'>{waterbill.units}</td>
        <td className='m-0 p-1'>{new Number((waterbill.total)+(waterbill.total_os)).toFixed(2)}</td>

        {preview &&
        <>
        <td className='m-0 p-1'>{waterbill.saved_bill}</td>
        <td className='m-0 p-1'>{waterbill.saved}</td>
        <td className='m-0 p-1'>{waterbill.prevmatches}</td>
        </>
        }
        {!preview &&
        <>
        <td className='m-0 p-1'>
        {waterbill.present==='Yes' && <button className='btn btn-warning m-0 p-0 pl-2 pr-2' onClick={()=>{handleShow(waterbill)}}><small>Edit</small></button>}
        {waterbill.present==='No' && <button className='btn btn-info m-0 p-0 pl-2 pr-2 text-white' onClick={()=>{handleShow(waterbill)}}><small>Add</small></button>} 
        </td>
        </>
        }

    </tr>
  );
}

export default WaterbillTable;