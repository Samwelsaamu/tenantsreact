import React from "react";

import ApexReactCharts from "react-apexcharts";

const DailyCharts =({dailyinsights}) =>{  
    
    const series= [{
                    name: "Enrolled",
                    data: [dailyinsights[0].enrolled_0007hrs,
                        dailyinsights[0].enrolled_0711hrs,
                        dailyinsights[0].enrolled_1115hrs,
                        dailyinsights[0].enrolled_1521hrs,
                        dailyinsights[0].enrolled_2100hrs]
                },
                {
                    name: "Purchased",
                    data: [dailyinsights[0].purchased_0007hrs,
                        dailyinsights[0].purchased_0711hrs,
                        dailyinsights[0].purchased_1115hrs,
                        dailyinsights[0].purchased_1521hrs,
                        dailyinsights[0].purchased_2100hrs]
                },
                {
                    name: "Benefits",
                    data: [dailyinsights[0].benefits_0007hrs,
                        dailyinsights[0].benefits_0711hrs,
                        dailyinsights[0].benefits_1115hrs,
                        dailyinsights[0].benefits_1521hrs,
                        dailyinsights[0].benefits_2100hrs]
                },
                {
                    name: "Jobs",
                    data: [dailyinsights[0].jobs_0007hrs,
                        dailyinsights[0].jobs_0711hrs,
                        dailyinsights[0].jobs_1115hrs,
                        dailyinsights[0].jobs_1521hrs,
                        dailyinsights[0].jobs_2100hrs]
                },
                {
                    name: "Tickets",
                    data: [dailyinsights[0].tickets_0007hrs,
                        dailyinsights[0].tickets_0711hrs,
                        dailyinsights[0].tickets_1115hrs,
                        dailyinsights[0].tickets_1521hrs,
                        dailyinsights[0].tickets_2100hrs]
                },
                {
                    name: "Attended",
                    data: [dailyinsights[0].attended_0007hrs,
                        dailyinsights[0].attended_0711hrs,
                        dailyinsights[0].attended_1115hrs,
                        dailyinsights[0].attended_1521hrs,
                        dailyinsights[0].attended_2100hrs]
                },
                {
                    name: "Moveouts",
                    data: [dailyinsights[0].moveouts_0007hrs,
                        dailyinsights[0].moveouts_0711hrs,
                        dailyinsights[0].moveouts_1115hrs,
                        dailyinsights[0].moveouts_1521hrs,
                        dailyinsights[0].moveouts_2100hrs]
                }];
                
    const options= {
                chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
                },
                dataLabels: {
                enabled: false
                },
                stroke: {
                curve: 'straight'
                },
                title: {
                text: 'Today ('+dailyinsights[0].today+') Insights',
                align: 'center'
                },
                grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.2
                },
                },
                xaxis: {
                categories: ['0000-0700 Hrs', '0700-1100 Hrs', '1100-1500 Hrs', '1500-2100 Hrs', '2100-0000 Hrs'],
                }
            };


    return(
      <div className="wrapper">
            <ApexReactCharts 
                options={options} 
                series={series} 
                type="bar" 
                height={250} />
      </div>
    );
}
export default DailyCharts;