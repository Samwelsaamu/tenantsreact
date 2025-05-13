import React, {useState,useRef} from "react";

import ApexReactCharts from "react-apexcharts";

const MonthlyRentGarbageChartsre =({monthlyinsightsrent}) =>{  

    // const [categories,setCategories]=useState(monthlyinsights[0].properts);
    // const [seriesdata,setSeriesData]=useState(monthlyinsights[0].rentbinsw);
    // const [series,setSeries]=useState([
    //     {
    //         name: 'Rent',
    //         data: monthlyinsights[0].rentbins
    //     }
    // ]);

    const chartData ={
        series:[
            {
                name: 'Rent',
                data: monthlyinsights[0].rentbins,
            }
        ],
        xaxis:{
            categories:monthlyinsights[0].properts,
        }
    }
    const seriess= [{
        name: "Rent",
        data: monthlyinsights[0].rentbins
    }];
    // const chartRef=useRef(null);
    // const UpdateSeries= () => {
    //     const filteredData = seriess.map((serie) =>({
    //         name: serie.name,
    //         data: serie.data.filter((value) => value !== 0),
    //     }));
        
    //     chartRef.updateSeries([{
    //         name: "Rent",
    //         data: monthlyinsights[0].rentbins
    //     }]);
    // };

    // const filteredData = chartData.series.map((serie) =>({
    //     name: serie.name,
    //     data: serie.data.filter((value) => value !== 0),
    // }));
    const filteredData = chartData.series.map((serie) =>({
        name: serie.name,
        data: serie.data.filter((value) => value !== 0),
    }));
   
    const filteredCategories = chartData.xaxis.categories.filter((category,index) =>{
        return chartData.series[0].data[index] !== 0;
    });

    
    
    const options= {
            chart: {
                height: 350,
                type: 'bar',
                stacked:true,
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
            text:'Monthly Rent & Garbage '+ monthlyinsights[0].monthname+' Insights',
            align: 'center'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3',  'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: filteredCategories,
            }

        };

        // UpdateSeries();

    return(
      <div className="wrapper">
            <ApexReactCharts 
                options={options} 
                series={seriess}
                type="area" 
                height={250} />
      </div>
    );
}
export default MonthlyRentGarbageChartsre;