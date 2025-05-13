import {Link, Navigate, useNavigate} from 'react-router-dom';

function PermissionsTable({permission,no,updatePermission,deletePermission,updateAssignPermissionToUser,updateAssignPermissionToRole}) {
    
  return (
    <tr className={`unwaterbillvaluesdiv m-0 p-0`}
      key={no} >
        
        <td className='m-0 p-1'>{no+1}</td>
        
        <td className='m-0 p-1 text-left'>{permission.name} (<span className='text-primary m-0 p-0'><small> Roles({permission.assignedroles})</small></span>) </td>
        <td className=' m-0 p-1'>
          <div className='d-flex justify-content-center my-auto'>
            {permission.assignedroles!=0 &&
            <>
              {permission.roles  && permission.roles.map((role,key) => (
                  <span className='text-success m-0 p-1'>{role.name} </span>
              ))
              }
            </>}
          </div>
        </td>
        <td className=' m-0 p-1'>
          <div className='d-flex justify-content-center my-auto'>
            <button className='bg-warning m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-dark' onClick={()=>{updatePermission(permission)}}><small><i className='fa fa-edit'></i> </small></button>
            <button className='bg-danger m-0 ml-1 pt-1 pl-2 pr-2 pb-1 border-info text-white' onClick={()=>{deletePermission(permission,'Delete')}}><small><i className='fa fa-trash'> </i></small></button>
          </div>
        </td>
    </tr>
  );
}

export default PermissionsTable;