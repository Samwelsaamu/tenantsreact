
import { useState } from 'react';
import DashboardNotFound from '../admin/DashboardNotFound';
import PageNotFound from '../home/PageNotFound';



function NotFound() {
    var notfoundcmp='';
    if(!localStorage.getItem("auth_token")){
        notfoundcmp=(
            <PageNotFound />
        )
    }
    else{
        notfoundcmp=(
            <DashboardNotFound />
        )
    }


  return (
    <>
        {notfoundcmp}
    </>
  );
}

export default NotFound;