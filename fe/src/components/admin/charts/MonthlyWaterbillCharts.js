import React from "react";

import ApexReactCharts from "react-apexcharts";

const MonthlyWaterbillCharts =({monthlyinsights}) =>{  

    const series= [
                {
                    name: "Waterbill",
                    data: monthlyinsights[0].waterbillw
                }];
    const options= {
                chart: {
                height: 350,
                type: 'bar',
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
                text:'Monthly Waterbill '+ monthlyinsights[0].monthname+' Insights',
                align: 'center'
                },
                grid: {
                row: {
                    colors: ['#f3f3f3',  'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
                },
                xaxis: {
                categories: monthlyinsights[0].propertsw,
                }
            };



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
export default MonthlyWaterbillCharts;