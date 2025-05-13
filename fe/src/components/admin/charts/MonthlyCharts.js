import React from "react";

import ApexReactCharts from "react-apexcharts";

const MonthlyCharts =({monthlyinsights}) =>{  

    const series= [{
                    name: "Rent",
                    data: monthlyinsights[0].rent
                },
                {
                    name: "Waterbill",
                    data: monthlyinsights[0].waterbill
                },
                {
                    name: "Garbage",
                    data: monthlyinsights[0].garbages
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

                // dataLabels: {
                // enabled: true
                // },
                stroke: {
                curve: 'smooth',
                show:true
                },
                title: {
                text:'Monthly Bills '+ monthlyinsights[0].monthname+' Insights',
                align: 'center'
                },
                grid: {
                row: {
                    colors: ['#f3f3f3',  'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
                },
                xaxis: {
                categories: monthlyinsights[0].propertsr,
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
export default MonthlyCharts;