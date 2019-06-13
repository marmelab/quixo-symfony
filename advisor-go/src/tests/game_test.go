package tests

import (
	"quixo/game"
	"reflect"
	"testing"
)

func TestGetMovablesCubesFromEmptyBoard(t *testing.T) {
	neutralCube := 0
	initGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	player := 1
	testBoard := game.GetBoard(initGrid, player)

	expectedMovables := []game.Cube{
		game.Cube{game.Coords{0, 0}, neutralCube}, game.Cube{game.Coords{0, 1}, neutralCube}, game.Cube{game.Coords{0, 2}, neutralCube}, game.Cube{game.Coords{0, 3}, neutralCube}, game.Cube{game.Coords{0, 4}, neutralCube},
		game.Cube{game.Coords{1, 0}, neutralCube}, game.Cube{game.Coords{1, 4}, neutralCube}, game.Cube{game.Coords{2, 0}, neutralCube}, game.Cube{game.Coords{2, 4}, neutralCube}, game.Cube{game.Coords{3, 0}, neutralCube}, game.Cube{game.Coords{3, 4}, neutralCube},
		game.Cube{game.Coords{4, 0}, neutralCube}, game.Cube{game.Coords{4, 1}, neutralCube}, game.Cube{game.Coords{4, 2}, neutralCube}, game.Cube{game.Coords{4, 3}, neutralCube}, game.Cube{game.Coords{4, 4}, neutralCube},
	}
	movables := game.GetMovablesCubes(testBoard)
	if !reflect.DeepEqual(expectedMovables, movables) {
		t.Errorf("Movables at start of the game are false")
	}
}

func TestGetMovablesCubesFromFullBoard(t *testing.T) {
	initGrid := [][]int{
		{-1, -1, -1, -1, -1},
		{-1, -1, -1, -1, -1},
		{-1, -1, -1, -1, -1},
		{-1, -1, -1, -1, -1},
		{-1, 1, -1, -1, -1},
	}
	player := 1
	testBoard := game.GetBoard(initGrid, player)

	expectedMovables := []game.Cube{
		game.Cube{game.Coords{4, 1}, player},
	}
	movables := game.GetMovablesCubes(testBoard)
	if !reflect.DeepEqual(expectedMovables, movables) {
		t.Errorf("(4, 1) cube should be movable")
	}
}

func TestGetAvailablesDestinationsFromCorner(t *testing.T) {
	neutralCube := 0
	initGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	selectedCube := game.Coords{0, 0}
	expectedDestinations := []game.Coords{
		game.Coords{0, 4},
		game.Coords{4, 0},
	}
	destinations := game.GetAvailablesDestinations(initGrid, selectedCube)
	if !reflect.DeepEqual(expectedDestinations, destinations) {
		t.Errorf("Destinations coords should be (0, 4) & (4, 0)")
	}
}

func TestGetAvailablesDestinationsFromMiddle(t *testing.T) {
	neutralCube := 0
	initGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	selectedCube := game.Coords{0, 2}
	expectedDestinations := []game.Coords{
		game.Coords{0, 0},
		game.Coords{0, 4},
		game.Coords{4, 2},
	}
	destinations := game.GetAvailablesDestinations(initGrid, selectedCube)
	if !reflect.DeepEqual(expectedDestinations, destinations) {
		t.Errorf("Destinations coords should be (0, 0) & (0, 4) & (4, 2)")
	}
}

func TestMoveCubeFromCornerWithEmptyBoard(t *testing.T) {
	neutralCube := 0
	initGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	player := 1
	testBoard := game.GetBoard(initGrid, player)
	coordsStart := game.Coords{0, 0}
	coordsEnd := game.Coords{0, 4}

	expectedGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, neutralCube, player},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	grid := game.MoveCube(testBoard, coordsStart, coordsEnd)
	if !reflect.DeepEqual(expectedGrid, grid) {
		t.Errorf("(0, 4) Cube should be from player after move")
	}
}

func TestMoveCubeFromMiddleWithShift(t *testing.T) {
	neutralCube := 0
	player := 1
	initGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, player, neutralCube, player, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	testBoard := game.GetBoard(initGrid, player)
	coordsStart := game.Coords{2, 0}
	coordsEnd := game.Coords{2, 4}

	expectedGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{player, neutralCube, player, neutralCube, player},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
	}
	grid := game.MoveCube(testBoard, coordsStart, coordsEnd)
	if !reflect.DeepEqual(expectedGrid, grid) {
		t.Errorf("Move is false when shift values")
	}
}

// The MoveCube function will Flip the grid to compute the move because yStart == yEnd
func TestMoveCubeFromMiddleWithShiftAndFlip(t *testing.T) {
	neutralCube := 0
	player := 1
	initGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, player, neutralCube, neutralCube},
		{neutralCube, neutralCube, player, neutralCube, neutralCube},
	}
	testBoard := game.GetBoard(initGrid, player)
	coordsStart := game.Coords{0, 2}
	coordsEnd := game.Coords{4, 2}

	expectedGrid := [][]int{
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, neutralCube, neutralCube, neutralCube},
		{neutralCube, neutralCube, player, neutralCube, neutralCube},
		{neutralCube, neutralCube, player, neutralCube, neutralCube},
		{neutralCube, neutralCube, player, neutralCube, neutralCube},
	}
	grid := game.MoveCube(testBoard, coordsStart, coordsEnd)
	if !reflect.DeepEqual(expectedGrid, grid) {
		t.Errorf("Move is false when shift values and flipped array")
	}
}
