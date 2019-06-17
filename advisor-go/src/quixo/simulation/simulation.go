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

// GetWorstMoveForPlayer return the move that will benefit the most for the opponent player
func GetWorstMoveForPlayer(board game.Board) Move {
	return getBestMove(board, true)
}

// GetMoveThatMakeMyOpponentLose return the move that will be the worst for the opponent
func GetMoveThatMakeMyOpponentLose(board game.Board) Move {
	isSmallerThan := func(int1 int, int2 int) bool { return int1 < int2 }
	if hasCubeSelected(board) {
		return getMoveWithCubeSelected(board, true, isSmallerThan)
	}
	return getMoveWithNoCubeSelected(board, true, isSmallerThan)
}

func getBestMove(board game.Board, forOpponentPlayer bool) Move {
	isGreaterThan := func(int1 int, int2 int) bool { return int1 > int2 }

	if hasCubeSelected(board) {
		return getMoveWithCubeSelected(board, forOpponentPlayer, isGreaterThan)
	}
	return getMoveWithNoCubeSelected(board, forOpponentPlayer, isGreaterThan)
}

func getMoveWithCubeSelected(
	board game.Board,
	forOpponentPlayer bool,
	comparator func(int, int) bool,
) Move {
	destination, _ := getBestDestinationWithScore(board, board.SelectedCube, forOpponentPlayer, comparator)
	return Move{
		board.SelectedCube.Coords,
		destination,
	}
}

func getMoveWithNoCubeSelected(
	board game.Board,
	forOpponentPlayer bool,
	comparator func(int, int) bool,
) Move {
	movables := game.GetMovablesCubes(board)
	bestMove := Move{}
	bestScore := 0
	for i := 0; i < len(movables); i++ {
		destination, score := getBestDestinationWithScore(board, movables[i], forOpponentPlayer, comparator)

		if comparator(score, bestScore) || i == 0 {
			bestScore = score
			bestMove = Move{
				CoordsStart: movables[i].Coords,
				CoordsEnd:   destination,
			}
		}
	}
	return bestMove
}

func getBestDestinationWithScore(
	board game.Board,
	cube game.Cube,
	forOpponentPlayer bool,
	comparator func(int, int) bool,
) (game.Coords, int) {
	grid := board.Grid
	destinations := game.GetAvailablesDestinations(grid, cube.Coords)
	bestScore := 0
	bestDestination := game.Coords{}
	for i := 0; i < len(destinations); i++ {
		newGrid := game.MoveCube(board, cube.Coords, destinations[i])

		player := board.Player
		if forOpponentPlayer {
			player = getOpponentPlayer(board.Player)
		}

		newBoard := game.GetBoardWithNoCubeSelected(newGrid, player)

		score := scorer.GetBoardScore(newBoard)
		if comparator(score, bestScore) || i == 0 {
			bestDestination = destinations[i]
			bestScore = score
		}
	}
	return bestDestination, bestScore
}

func getOpponentPlayer(player int) int {
	return player * -1
}

func hasCubeSelected(board game.Board) bool {
	cube := board.SelectedCube
	return cube.Coords.X != -1 && cube.Coords.Y != -1
}
