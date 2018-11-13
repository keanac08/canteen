var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);

//~ server.listen('http://ipcpc285:3000');
server.listen(3000);
// WARNING: app.listen(80) will NOT work here!

//~ app.get('/', function (req, res) {
//~ res.sendfile(__dirname + '/index.html');

let nsp = io.of('canteen');

console.log('Canteen V2 Server. Please do not close.')

nsp.on('connection', function (socket) {
	
	socket.on('join_session', function (client_ip) {
		session = client_ip;
		socket.room = client_ip;
		socket.join(session);
	});
	
	socket.on('new_cart_item', function (data) {
		//~ console.log('Client : ' + session + ' - NewCartItem____' + data.name + '____@' +   data.price + '____x' + data.quantity + '____' + data.total);
		//~ console.log(data);
		nsp.to(socket.room).emit("new_cart_item", data);
	});	
	
	socket.on('update_cart_item', function (data) {
		//~ console.log('Client : ' + session + ' - UpdateCartItem____' + data.name + '____@' +   data.price + '____x' + data.quantity + '____' + data.total);
		//~ console.log(data);
		nsp.to(socket.room).emit("update_cart_item", data);
	});	
	
	socket.on('delete_cart_item', function (data) {
		//~ console.log('Client : ' + session + ' - DeleteCartItem____' + data.name);
		//~ console.log(data);
		nsp.to(socket.room).emit("delete_cart_item", data);
	});	
	
	socket.on('update_cart_total', function (data) {
		//~ console.log('Client : ' + session + ' - CartTotal ' + data.total );
		//~ console.log(data);
		nsp.to(socket.room).emit("update_cart_total", data);
	});	
	
	socket.on('update_balance', function (data) {
		//~ console.log('Client : ' + session + ' - CartTotal ' + data.total );
		//~ console.log(data);
		nsp.to(socket.room).emit("update_balance", data);
	});	
	
	socket.on('clear_cart', function () {
		//~ console.log('Client : ' + session + ' - ClearCart');
		//~ console.log(data);
		nsp.to(socket.room).emit("clear_cart");
	});	
	
	socket.on('transaction_completed', function () {
		//~ console.log(data);
		nsp.to(socket.room).emit("transaction_completed");
	});	
	
	socket.on('employee_details', function (data) {
		//~ console.log('Employee : ' + data.employee.number + ' - ' + data.employee.name);
		//~ console.log(data);
		nsp.to(socket.room).emit("employee_details", data);
	});	
	socket.on('check_out', function () {
		//~ console.log(data);
		nsp.to(socket.room).emit("check_out");
	});	
	
	socket.on('refresh', function () {
		//~ console.log('refresh');
		nsp.to(socket.room).emit("refresh");
	});	
	
});

