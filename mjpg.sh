killall mjpg_streamer
mjpg_streamer -i "input_uvc.so -f 4 -r 320x240" -o "output_http.so -p 8080"