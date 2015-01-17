killall mjpg_streamer
mjpg_streamer -i "input_uvc.so -f 5 -q 50 -y" -o "output_http.so -p 8080" -b
