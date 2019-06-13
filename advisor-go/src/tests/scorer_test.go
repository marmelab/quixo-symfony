package tests

import (
	"quixo/game"
	"quixo/scorer"
	"testing"
)

func TestGetScoreFromEmptyBoard(t *testing.T) {
	neutralCube := 0
	player := 1
	initGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedScore := 0
	score := scorer.GetBoardScore(testBoard)
	if score != expectedScore {
		t.Errorf("Score of an empty grid should be 0")
	}
}

func TestGetScoreFromBoardWithOneCube(t *testing.T) {
	neutralCube := 0
	player := 1
	initGrid := [][]int{
		{player, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedScore := 1
	score := scorer.GetBoardScore(testBoard)
	if score != expectedScore {
		t.Errorf("Score of a grid with 1 cube should be 1")
	}
}

func TestGetScoreFromBoardWithOpposedCubeOnLine(t *testing.T) {
	neutralCube := 0
	player := 1
	initGrid := [][]int{
		{player, neutralCube, neutralCube, neutralCube, player},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedScore := 2
	score := scorer.GetBoardScore(testBoard)
	if score != expectedScore {
		t.Errorf("Score of a grid with 2 opposed cubes should be 2")
	}
}

func TestGetScoreFromBoardWithMultiplesLines(t *testing.T) {
	neutralCube := 0
	player := 1
	initGrid := [][]int{
		{player, neutralCube, neutralCube, neutralCube, player},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{player, neutralCube, player, neutralCube, player},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedScore := 3
	score := scorer.GetBoardScore(testBoard)
	if score != expectedScore {
		t.Errorf("Score of a grid with multiples lines should be the max of the lines")
	}
}

func TestGetScoreFromBoardWithMultiplesLinesAndDiagonal(t *testing.T) {
	neutralCube := 0
	player := 1
	initGrid := [][]int{
		{player, neutralCube, neutralCube, neutralCube, player},
		{neutralCube, player, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, player, player, player},
		{neutralCube, neutralCube, neutralCube, neutralCube, player},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedScore := 4
	score := scorer.GetBoardScore(testBoard)
	if score != expectedScore {
		t.Errorf("Score of a grid with multiples lines and diagonal should be the score of the diagonal")
	}
}

func TestGetScoreFromWinBoardInStraightLine(t *testing.T) {
	neutralCube := 0
	player := 1
	initGrid := [][]int{
		{player, neutralCube, neutralCube, neutralCube, player},
		{neutralCube, player, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{player, player, player, player, player},
		{neutralCube, neutralCube, neutralCube, neutralCube, player},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedScore := 5
	score := scorer.GetBoardScore(testBoard)
	if score != expectedScore {
		t.Errorf("Score of a grid with a straight Line should be 5")
	}
}

func TestGetScoreFromWinBoardInDiagonal(t *testing.T) {
	neutralCube := 0
	player := 1
	initGrid := [][]int{
		{player, neutralCube, neutralCube, neutralCube, player},
		{neutralCube, player, neutralCube, player, neutralCube},
		{neutralCube, neutralCube, player, neutralCube, neutralCube},
		{neutralCube, neutralCube, player, player, player},
		{player, neutralCube, neutralCube, neutralCube, player},
	}
	testBoard := game.GetBoard(initGrid, player)
	expectedScore := 5
	score := scorer.GetBoardScore(testBoard)
	if score != expectedScore {
		t.Errorf("Score of a grid with a straight Line should be 5")
	}
}
