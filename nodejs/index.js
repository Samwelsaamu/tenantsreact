const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const cors = require('cors');

const PORT = process.env.PORT || 5098;

const app = express();
app.use(cors);
app.use(express.json());

// app.get('/', (req, res) => {
//     res.send('Hello World!')
// })

const server =http.createServer(app);
// const io= socketio(server);


const io=new Server(server, {
    cors: {
     origin: "*",
     methods:["GET","POST"],
     credentials: true
    },
});

// const io = new Server(server, {
//     cors: {
//       origin: "*", // Allow all origins (for development, NOT recommended for production)
//       methods: ["GET", "POST"], // Specify allowed HTTP methods
//       allowedHeaders: ["my-custom-header"], // Specify allowed request headers (if any)
//       credentials: true // If you need to handle cookies or authorization headers
//     }
//   });



// you logic here

app.get('/', (req, res) => {
    res.send('<h1>Hello world</h1>');
  });
  
io.on('connection', (socket) => {
    console.log('Socket: '+socket.id+' Connected');
    
    socket.on('tenant_assigned', (msg) =>{
        // console.log('Assigned:: '+msg);
        io.emit('tenant_assigned',msg);//broadcast to all connected clients
    });

    
    socket.on('tenant_vacated', (msg) =>{
        // console.log('Vacated:: '+msg);
        io.emit('tenant_vacated',msg);//broadcast to all connected clients
    });

    
    socket.on('load_credit_balance', (msg) =>{
        // console.log('Vacated:: '+msg);
        io.emit('load_credit_balance',msg);//broadcast to all connected clients
    });
    
    socket.on('message_sent', (msg) =>{
        // console.log('Vacated:: '+msg);
        io.emit('message_sent',msg);//broadcast to all connected clients
    });


    socket.on('waterbill_added', (msg) =>{
        // // console.log(msg)
        // console.log('Waterbill Added:: '+msg.month);
        // console.log('Waterbill Added:: '+msg.message);
        socket.broadcast.emit('waterbill_changed',msg);//broadcast to all connected clients
    });


    socket.on('waterbill_uploaded', (msg) =>{
        // console.log('Vacated:: '+msg);
        io.emit('waterbill_uploaded',msg);//broadcast to all connected clients
    });


    socket.on('monthly_bills_generated', (msg) =>{
        // console.log('Vacated:: '+msg);
        io.emit('waterbill_uploaded',msg);//broadcast to all connected clients
    });
    

    

    socket.on('disconnect', () =>{
        console.log('Socket: '+socket.id+' Disconnected');
    });
});





server.listen(PORT,() =>{
    console.log(`Server listening on port ${PORT}`)
})