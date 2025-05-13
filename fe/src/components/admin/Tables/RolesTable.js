import {Link, Navigate, useNavigate} from 'react-router-dom';

function RolesTable({role,no,updateRole,deleteRole,updateAssignRoleToUser,updateAssignRoleToPermission}) {
    
  return (
    <tr className={`unwaterbillvaluesdiv m-0 p-0`}
      key={no} >
        
        <td className='m-0 p-1'>{no+1}</td>
        
        <td className='m-0 p-1 text-left'>{role.name} </td>
        {/* <td className='m-0 p-1 d-block' style={{"whiteSpace":"nowrap"}}>{role.created_at}</td> */}
        <td className=' m-0 p-1'>
          <div className='d-flex justify-content-center my-auto'>
            <button className='bg-success m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{updateAssignRoleToUser(role)}}><small><i className='fa fa-check'></i> Users ({role.roleusers})</small></button>
            <button className='bg-primary m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{updateAssignRoleToPermission(role)}}><small><i className='fa fa-handshake'></i> Permissions ({role.rolepermissions})</small></button>
            <button className='bg-warning m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-dark' onClick={()=>{updateRole(role)}}><small><i className='fa fa-edit'></i> </small></button>
            <button className='bg-danger m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{deleteRole(role,'Delete')}}><small><i className='fa fa-trash'> </i></small></button>
          </div>
        </td>
    </tr>
  );
}

export default RolesTable;