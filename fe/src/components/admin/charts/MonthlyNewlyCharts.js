import React from "react";

import ApexReactCharts from "react-apexcharts";

const MonthlyNewlyCharts =({monthlyinsights}) =>{  

    const series= [{
                    name: "Water Deposit",
                    data: monthlyinsights[0].water
                },
                {
                    name: "Rent Deposit",
                    data: monthlyinsights[0].hsedeposit
                },
                {
                    name: "KPLC Deposit",
                    data: monthlyinsights[0].kplc
                },
                {
                    name: "Lease",
                    data: monthlyinsights[0].lease
                }];
    const options= {
                chart: {
                height: 350,
                type: 'line',
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
                text:'Monthly Deposits '+ monthlyinsights[0].monthname+' Insights',
                align: 'center'
                },
                grid: {
                row: {
                    colors: ['#f3f3f3',  'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
                },
                xaxis: {
                categories: monthlyinsights[0].propertsd,
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
export default MonthlyNewlyCharts;