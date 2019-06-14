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
	http.HandleFunc("/worst-move", worstMove)
	http.ListenAndServe(":"+strconv.Itoa(port), nil)
}

func bestMove(w http.ResponseWriter, r *http.Request) {
	board := getBoardFromRequest(r)
	bestMove := simulation.GetBestMoveForPlayer(board)
	sendResponse(w, bestMove)
}

func worstMove(w http.ResponseWriter, r *http.Request) {
	board := getBoardFromRequest(r)
	worstMove := simulation.GetWorstMoveForPlayer(board)
	sendResponse(w, worstMove)
}

func getBoardFromRequest(r *http.Request) game.Board {
	decoder := json.NewDecoder(r.Body)
	defer r.Body.Close()
	var board game.Board
	err := decoder.Decode(&board)
	if err != nil {
		panic(err)
	}
	return board
}

func handle(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("content-type", "application/json")
	w.Write([]byte(`{"message": "Hello world !"}`))
}

func sendResponse(w http.ResponseWriter, response interface{}) {
	encodedResponse, err := json.Marshal(response)
	if err != nil {
		panic(err)
	}
	w.Header().Set("content-type", "application/json")
	w.Write([]byte(string(encodedResponse)))
}
