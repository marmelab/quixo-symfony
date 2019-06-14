package tests

import (
	"quixo/game"
	"quixo/scorer"
	"quixo/simulation"
	"reflect"
	"testing"
)

/*
 * There are a lot of moves that make the player win in this situation
 * So we check the score of the board after move is the max
 */
func TestGetBestMoveForWinWithMultiplesOptions(t *testing.T) {
	neutralCube := 0
	player := 1
	initGrid := [][]int{
		{player, player, player, player, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedScore := 5
	bestMove := simulation.GetBestMoveForPlayer(testBoard)
	newGrid := game.MoveCube(testBoard, bestMove.CoordsStart, bestMove.CoordsEnd)
	testBoard.Grid = newGrid
	score := scorer.GetBoardScore(testBoard)

	if expectedScore != score {
		t.Errorf("The best move should make me win")
	}
}

func TestGetBestMoveForWinWithOneOption(t *testing.T) {
	neutralCube := 0
	player := 1
	opponentPlayer := -1
	initGrid := [][]int{
		{player, player, player, player, opponentPlayer},
		{neutralCube, neutralCube, neutralCube, neutralCube, opponentPlayer},
		{neutralCube, neutralCube, neutralCube, neutralCube, opponentPlayer},
		{neutralCube, neutralCube, neutralCube, neutralCube, opponentPlayer},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedMove := simulation.Move{
		game.Coords{4, 4},
		game.Coords{0, 4},
	}
	bestMove := simulation.GetBestMoveForPlayer(testBoard)

	if !reflect.DeepEqual(expectedMove, bestMove) {
		t.Errorf("The best move should be the one that make me win")
	}
}

func TestGetBestMoveForWinInDiagonalAndWithShift(t *testing.T) {
	neutralCube := 0
	player := 1
	initGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, neutralCube, player},
		{neutralCube, neutralCube, neutralCube, player, neutralCube},
		{neutralCube, neutralCube, player, neutralCube, neutralCube},
		{neutralCube, neutralCube, player, neutralCube, neutralCube},
		{player, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedMove := simulation.Move{
		game.Coords{3, 0},
		game.Coords{3, 4},
	}
	bestMove := simulation.GetBestMoveForPlayer(testBoard)

	if !reflect.DeepEqual(expectedMove, bestMove) {
		t.Errorf("The best move should be the one that make me win")
	}
}

func TestGetWorstMoveForOpponentWin(t *testing.T) {
	neutralCube := 0
	player := 1
	opponentPlayer := -1
	initGrid := [][]int{
		{player, player, player, player, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, opponentPlayer, opponentPlayer, opponentPlayer, opponentPlayer},
		{opponentPlayer, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedScore := 5
	bestMove := simulation.GetWorstMoveForPlayer(testBoard)

	newGrid := game.MoveCube(testBoard, bestMove.CoordsStart, bestMove.CoordsEnd)
	opponentBoard := game.GetBoard(newGrid, opponentPlayer)

	score := scorer.GetBoardScore(opponentBoard)
	if expectedScore != score {
		t.Errorf("The worst move should make me lose")
	}
	expectedDestination := game.Coords{4, 0}
	if !reflect.DeepEqual(bestMove.CoordsEnd, expectedDestination) {
		t.Errorf("The move should shift the opponentCube to make him win")
	}
}

func TestGetWorstMoveForOpponentWinWithOneMove(t *testing.T) {
	neutralCube := 0
	player := 1
	opponentPlayer := -1
	initGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, opponentPlayer, neutralCube},
		{neutralCube, neutralCube, neutralCube, opponentPlayer, neutralCube},
		{neutralCube, neutralCube, neutralCube, opponentPlayer, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, opponentPlayer},
		{neutralCube, neutralCube, neutralCube, opponentPlayer, neutralCube},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedScore := 5
	bestMove := simulation.GetWorstMoveForPlayer(testBoard)

	newGrid := game.MoveCube(testBoard, bestMove.CoordsStart, bestMove.CoordsEnd)
	opponentBoard := game.GetBoard(newGrid, opponentPlayer)

	score := scorer.GetBoardScore(opponentBoard)
	if expectedScore != score {
		t.Errorf("The worst move should make me lose")
	}
	expectedMove := simulation.Move{
		game.Coords{3, 0},
		game.Coords{3, 4},
	}
	if !reflect.DeepEqual(expectedMove, bestMove) {
		t.Errorf("The worst move should be the one that make me lose")
	}
}
