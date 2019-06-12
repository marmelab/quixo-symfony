package main

import (
	"fmt"
	"net/http"
	"strconv"
)

const port int = 8001

func main() {
	fmt.Printf("Server listening on %d", port)
	http.HandleFunc("/", handler)
	http.ListenAndServe(":"+strconv.Itoa(port), nil)
}

func handler(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("content-type", "application/json")
	w.Write([]byte(`{"message": "Hello world !"}`))
}
