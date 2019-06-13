package server

import (
	"encoding/json"
	"net/http"
	"quixo/game"
	"quixo/simulation"
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
	decoder := json.NewDecoder(r.Body)
	var board game.Board
	err := decoder.Decode(&board)
	if err != nil {
		panic(err)
	}
	bestMove := simulation.GetBestMove(board)
	encodedMove, err := json.Marshal(bestMove)
	w.Header().Set("content-type", "application/json")
	w.Write([]byte(string(encodedMove)))
}

func handle(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("content-type", "application/json")
	w.Write([]byte(`{"message": "Hello world !"}`))
}
