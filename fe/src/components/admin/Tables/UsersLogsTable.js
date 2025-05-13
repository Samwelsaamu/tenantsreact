import {Link, Navigate, useNavigate} from 'react-router-dom';

function UsersLogsTable({waterbill,no}) {
    
  return (
    <tr className={`unwaterbillvaluesdiv m-0 p-0`}
      key={no} >
        
        <td className='m-0 p-1'>{no+1}</td>
        
        <td className='m-0 p-1 text-left'>{waterbill.Message} </td>
        <td className='m-0 p-1 d-block' style={{"whiteSpace":"nowrap"}}>{waterbill.created_at}</td>
    </tr>
  );
}

export default UsersLogsTable;