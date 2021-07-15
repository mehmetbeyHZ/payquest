const app = require('express')();
const http = require('http').createServer(app);
var io = require('socket.io')(http);
let totalOnline = 0;
