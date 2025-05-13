import {Link, Navigate, useNavigate} from 'react-router-dom';

function VacatedWaterTable({waterbill,no,preview}) {
    
  return (
    <tr key={no} style={{"padding":"0px","margin":"0","backgroundColor":"red"}}>
        <td className='m-0 p-1'>{no+1}</td>
        <th className='m-0 p-1'>{waterbill.housename}</th>
        <td className='m-0 p-1' title={waterbill.tenantname}>{waterbill.tenantfname}</td>
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
        
    </tr>
  );
}

export default VacatedWaterTable;