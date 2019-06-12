package server

import (
	"net/http"
	"strconv"
)

const port int = 8001

// Start the API server
func Start() {
	http.HandleFunc("/", handle)
	http.HandleFunc("/best-move", bestMove)
	http.ListenAndServe(":"+strconv.Itoa(port), nil)
}

func bestMove(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("content-type", "application/json")
	w.Write([]byte(`{"message": "Hello world !"}`))
}

func handle(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("content-type", "application/json")
	w.Write([]byte(`{"message": "Hello world !"}`))
}
