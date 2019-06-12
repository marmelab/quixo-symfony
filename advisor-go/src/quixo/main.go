package main

import (
	"net/http"
	"quixo/game"
	"quixo/simulation"

	"github.com/davecgh/go-spew/spew"
)

const port int = 8001

func main() {
	grid := [][]int{
		{0, 0, 0, 0, 1},
		{0, 0, 0, 1, 0},
		{0, 0, 1, 0, 0},
		{0, 1, 0, 0, 0},
		{0, 0, 0, 0, 0},
	}
	board := game.GetBoard(grid, 1)
	bestMove := simulation.GetBestMove(board)
	spew.Dump(bestMove)
}

func handler(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("content-type", "application/json")
	w.Write([]byte(`{"message": "Hello you !"}`))
}
