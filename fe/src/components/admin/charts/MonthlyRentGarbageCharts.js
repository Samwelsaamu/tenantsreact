import React, {useState,useRef} from "react";

import ApexReactCharts from "react-apexcharts";

const MonthlyRentGarbageCharts =({monthlyinsightsrent}) =>{  

    // const series= [
    //     {
    //         name: "Waterbill",
    //         data: monthlyinsights.waterbillw
    //     }];

    const series= [{
        name: "Rent and Garbage",
        data: monthlyinsightsrent[0].rentbinsw
    }];
    
    
    const options= {
            chart: {
                height: 350,
                type: 'bar',
                stacked:false,
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '8px', // Adjust font size for readability
                    rotate: 0, // Reset any default rotation
                },
            },
            stroke: {
            curve: 'smooth',
            show:true
            },
            title: {
            text:'Monthly Rent & Garbage '+ monthlyinsightsrent[0].monthname+' Insights',
            align: 'center'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3',  'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: monthlyinsightsrent[0].propertsr,
            }

        };

        // UpdateSeries();

    return(
      <div className="wrapper">
            <ApexReactCharts 
                options={options} 
                series={series}
                type="area" 
                height={250} />
      </div>
    );
}
export default MonthlyRentGarbageCharts;