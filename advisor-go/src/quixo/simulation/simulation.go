package simulation

import (
	"quixo/game"
	"quixo/scorer"
	"sync"
	"time"

	"github.com/davecgh/go-spew/spew"
)

// Move with coordsStart and coordsEnd
type Move struct {
	CoordsStart game.Coords
	CoordsEnd   game.Coords
}

// BoardNode ...
type BoardNode struct {
	Ancestor *BoardNode
	Board    game.Board
	Score    int
	Move     Move
}

// GetAllWinnablesBoardNode ...
func GetAllWinnablesBoardNode(board game.Board) []BoardNode {
	timeStart := time.Now()
	nodes := []BoardNode{{
		Board: board,
		Score: scorer.GetBoardScore(board),
	}}
	leaves := []BoardNode{}
	for time.Now().Sub(timeStart).Seconds() < 30000 {

		var wg sync.WaitGroup
		nodesChannel := make(chan BoardNode, len(nodes)*16*3)

		for _, node := range nodes {
			wg.Add(1)
			go MakeAllMovesForBoard(nodesChannel, node, &wg)
		}
		wg.Wait()

		close(nodesChannel)

		nodes = []BoardNode{}

		for node := range nodesChannel {
			spew.Dump(node.Score)
			if node.Score == 5 {
				leaves = append(leaves, node)
			} else {
				nodes = append(nodes, node)
			}
		}
	}
	return leaves
}

// MakeAllMovesForBoard and write created nodes in channel
func MakeAllMovesForBoard(nodesChan chan<- BoardNode, node BoardNode, wg *sync.WaitGroup) {
	moves := getAllMoves(node.Board)
	for _, move := range moves {
		newGrid := game.MoveCube(node.Board, move.CoordsStart, move.CoordsEnd)
		newBoard := game.GetBoardWithNoCubeSelected(newGrid, node.Board.Player)
		score := scorer.GetBoardScore(newBoard)
		nodesChan <- BoardNode{&node, newBoard, score, move}
	}
	wg.Done()
}

func getAllMoves(board game.Board) []Move {
	moves := []Move{}
	movables := game.GetMovablesCubes(board)
	for _, movable := range movables {
		coordsStart := movable.Coords
		destinations := game.GetAvailablesDestinations(board.Grid, coordsStart)
		for _, destination := range destinations {
			moves = append(moves, Move{coordsStart, destination})
		}
	}
	return moves
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
	scoreComparator func(int, int) bool,
) Move {
	destination, _ := getBestDestinationWithScore(board, board.SelectedCube, forOpponentPlayer, scoreComparator)
	return Move{
		board.SelectedCube.Coords,
		destination,
	}
}

func getMoveWithNoCubeSelected(
	board game.Board,
	forOpponentPlayer bool,
	scoreComparator func(int, int) bool,
) Move {
	movables := game.GetMovablesCubes(board)
	bestMove := Move{}
	bestScore := 0
	for i := 0; i < len(movables); i++ {
		destination, score := getBestDestinationWithScore(board, movables[i], forOpponentPlayer, scoreComparator)

		if scoreComparator(score, bestScore) || i == 0 {
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
	scoreComparator func(int, int) bool,
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
		if scoreComparator(score, bestScore) || i == 0 {
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
