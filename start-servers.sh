#!/bin/bash

echo "Starting Laravel Development Servers..."
echo ""
echo "Port 8000: Subdomain Template (Public Pages)"
echo "Port 9000: Main Site (Admin Login and Dashboard)"
echo ""
echo "Press Ctrl+C to stop both servers"
echo ""

# Start server on port 8000 in background
php artisan serve --port=8000 &
SERVER_8000_PID=$!

# Wait a moment
sleep 2

# Start server on port 9000 in background
php artisan serve --port=9000 &
SERVER_9000_PID=$!

echo ""
echo "Both servers are starting..."
echo "Subdomain Template: http://127.0.0.1:8000"
echo "Main Site (Admin): http://127.0.0.1:9000/admin/login"
echo ""
echo "Server PIDs: $SERVER_8000_PID (port 8000), $SERVER_9000_PID (port 9000)"
echo "To stop: kill $SERVER_8000_PID $SERVER_9000_PID"
echo ""

# Wait for user interrupt
wait

