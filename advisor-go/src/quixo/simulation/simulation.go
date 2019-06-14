package simulation

import (
	"quixo/game"
	"quixo/scorer"
)

// Move with coordsStart and coordsEnd
type Move struct {
	CoordsStart game.Coords
	CoordsEnd   game.Coords
}

// GetBestMoveForPlayer return the best move for the player
func GetBestMoveForPlayer(board game.Board) Move {
	return getBestMove(board, false)
}

// GetWorstMoveForPlayer return the move that will benefit the most to the opponent player
func GetWorstMoveForPlayer(board game.Board) Move {
	return getBestMove(board, true)
}

func getBestMove(board game.Board, forOpponentPlayer bool) Move {
	movables := game.GetMovablesCubes(board)

	// If a cube is already selected, it's the only option to consider
	if hasCubeSelected(board) {
		movables = []game.Cube{board.SelectedCube}
	}
	bestMove := Move{}
	maxScore := -1
	for i := 0; i < len(movables); i++ {
		destination, score := getBestDestinationWithScore(board, movables[i], forOpponentPlayer)

		if score > maxScore {
			maxScore = score
			bestMove = Move{
				CoordsStart: movables[i].Coords,
				CoordsEnd:   destination,
			}
		}
	}
	return bestMove
}

func getBestDestinationWithScore(board game.Board, cube game.Cube, forOpponentPlayer bool) (game.Coords, int) {
	grid := board.Grid
	destinations := game.GetAvailablesDestinations(grid, cube.Coords)
	maxScore := -1
	bestDestination := game.Coords{}
	for i := 0; i < len(destinations); i++ {
		newGrid := game.MoveCube(board, cube.Coords, destinations[i])

		player := board.Player
		if forOpponentPlayer {
			player = getOpponentPlayer(board.Player)
		}

		newBoard := game.Board{
			Grid:   newGrid,
			Player: player,
		}
		score := scorer.GetBoardScore(newBoard)
		if score > maxScore {
			bestDestination = destinations[i]
			maxScore = score
		}
	}
	return bestDestination, maxScore
}

func getOpponentPlayer(player int) int {
	return player * -1
}

func hasCubeSelected(board game.Board) bool {
	cube := board.SelectedCube
	return cube.Coords.X != -1 && cube.Coords.Y != -1
}
