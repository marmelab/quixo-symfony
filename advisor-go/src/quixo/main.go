package main

import (
	"fmt"
	"net/http"
	"quixo/board"
	"strconv"

	"github.com/davecgh/go-spew/spew"
)

const port int = 8001

func main() {
	grid := [][]int{
		{0, 0, 0, 0, 0},
		{0, 0, 0, 0, 0},
		{0, 0, 0, 0, 0},
		{0, 0, 0, 0, 0},
		{0, 0, 0, 0, 0},
	}
	newBoard := board.GetBoard(grid)
	movables := board.GetMovablesCubes(newBoard)
	spew.Dump(len(movables))
	fmt.Printf("Server listening on %d", port)
	http.HandleFunc("/", handler)
	http.ListenAndServe(":"+strconv.Itoa(port), nil)
}

func handler(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("content-type", "application/json")
	w.Write([]byte(`{"message": "Hello you !"}`))
}
