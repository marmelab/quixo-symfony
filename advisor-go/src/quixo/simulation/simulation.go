package simulation

import (
	"quixo/game"
	"quixo/scorer"
)

// Move with coordsStart and coordsEnd
type Move struct {
	coordsStart game.Coords
	coordsEnd   game.Coords
}

// GetBestMove return the best move
func GetBestMove(board game.Board) Move {
	movables := game.GetMovablesCubes(board)

	bestMove := Move{}
	maxScore := 0
	for i := 0; i < len(movables); i++ {
		destination, score := getBestDestinationWithScore(board, movables[i])

		if score > maxScore {
			maxScore = score
			bestMove = Move{
				coordsStart: movables[i].Coords,
				coordsEnd:   destination,
			}
		}
	}

	return bestMove
}

func getBestDestinationWithScore(board game.Board, cube game.Cube) (game.Coords, int) {
	grid := board.Grid
	destinations := game.GetAvailablesDestinations(grid, cube.Coords)

	maxScore := 0
	bestDestination := game.Coords{}
	for i := 0; i < len(destinations); i++ {
		newGrid := game.MoveCube(board, cube.Coords, destinations[i])

		newBoard := game.Board{
			Grid:   newGrid,
			Player: board.Player,
		}
		score := scorer.GetBoardScore(newBoard)
		if score > maxScore {
			bestDestination = destinations[i]
			maxScore = score
		}
	}
	return bestDestination, maxScore
}
