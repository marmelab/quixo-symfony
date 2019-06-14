package game

import (
	"fmt"
	"strconv"
)

// Board with Grid and Player
type Board struct {
	Grid         [][]int
	Player       int
	SelectedCube Cube
}

// Coords x and y
type Coords struct {
	X int
	Y int
}

// Cube with Coords and value
type Cube struct {
	Coords Coords
	Value  int
}

// GetBoardWithNoCubeSelected return a Board from grid and player
func GetBoardWithNoCubeSelected(grid [][]int, player int) Board {
	newBoard := Board{
		Grid:   grid,
		Player: player,
		SelectedCube: Cube{
			Coords: Coords{X: -1, Y: -1},
		},
	}
	return newBoard
}

// GetBoard return a Board from grid, player and selectedCube
func GetBoard(grid [][]int, player int, selectedCube Cube) Board {
	newBoard := Board{grid, player, selectedCube}
	return newBoard
}

// GetMovablesCubes return an array of movables cube
func GetMovablesCubes(board Board) []Cube {
	grid := board.Grid
	player := board.Player

	movables := []Cube{}
	for x := 0; x < len(grid); x++ {
		for y := 0; y < len(grid[x]); y++ {
			cube := Cube{
				Coords: Coords{X: x, Y: y},
				Value:  grid[x][y],
			}
			if isOutsideCube(cube.Coords, len(grid[x])) && cubeBelongsToPlayer(cube.Value, player) {
				movables = append(movables, cube)
			}
		}
	}
	return movables
}

// GetAvailablesDestinations return an array of destinations
func GetAvailablesDestinations(grid [][]int, coords Coords) []Coords {
	destinations := []Coords{}
	x := coords.X
	y := coords.Y
	size := len(grid)

	isOnStart := func(index int) bool {
		return index == 0
	}
	isOnEnd := func(index int, size int) bool {
		return index == size-1
	}
	isOnEdge := func(index int, size int) bool {
		return isOnStart(index) || isOnEnd(index, size)
	}
	indexStart := 0
	indexEnd := size - 1

	// If on the top or the bottom of the board, else it's on the left or right
	if isOnEdge(x, size) {
		// If not on the left of the board, we can move it to the left
		if !isOnStart(y) {
			destinations = append(destinations, Coords{X: x, Y: indexStart})
		}
		// If not on the right of the board, we can move it to the right
		if !isOnEnd(y, size) {
			destinations = append(destinations, Coords{X: x, Y: indexEnd})
		}
		// If on the top of the board, we can move it to the bottom, else to the top
		if isOnStart(x) {
			destinations = append(destinations, Coords{X: indexEnd, Y: y})
		} else {
			destinations = append(destinations, Coords{X: indexStart, Y: y})
		}
	} else if isOnEdge(y, size) {
		if !isOnStart(x) {
			destinations = append(destinations, Coords{X: indexStart, Y: y})
		}
		if !isOnEnd(x, size) {
			destinations = append(destinations, Coords{X: indexEnd, Y: y})
		}
		if isOnStart(y) {
			destinations = append(destinations, Coords{X: x, Y: indexEnd})
		} else {
			destinations = append(destinations, Coords{X: x, Y: indexStart})
		}
	}

	return destinations
}

// MoveCube from coordsStart to coordsEnd
func MoveCube(board Board, coordsStart Coords, coordsEnd Coords) [][]int {
	newGrid := DuplicateGrid(board.Grid)
	player := board.Player
	if coordsStart.X == coordsEnd.X {
		return shiftLine(newGrid, coordsStart.Y, coordsEnd.Y, coordsStart.X, player)
	}
	if coordsStart.Y == coordsEnd.Y {
		flippedGrid := FlipRowsAndCols(newGrid)
		shiftedFlippedGrid := shiftLine(flippedGrid, coordsStart.X, coordsEnd.X, coordsStart.Y, player)
		return FlipRowsAndCols(shiftedFlippedGrid)
	}
	return nil
}

func isOutsideCube(coords Coords, size int) bool {
	return coords.X == 0 || coords.Y == 0 || coords.X == size-1 || coords.Y == size-1
}

func cubeBelongsToPlayer(cubeValue int, playerValue int) bool {
	return cubeValue == 0 || cubeValue == playerValue
}

// Shift all values from xEnd to xStart in one row
func shiftLine(grid [][]int, xStart int, xEnd int, rowIndex int, player int) [][]int {
	newGrid := DuplicateGrid(grid)
	step := 1
	indexBound := xStart + 1
	if xEnd > xStart {
		step = -1
		indexBound = xStart - 1
	}
	value := player
	for index := xEnd; index != indexBound; index += step {
		tmpValue := newGrid[rowIndex][index]
		newGrid[rowIndex][index] = value
		value = tmpValue
	}
	return newGrid
}

// FlipRowsAndCols return a 2d array with inverted cols and rows
func FlipRowsAndCols(source [][]int) [][]int {
	size := len(source)
	flipped := make([][]int, size)
	for i := 0; i < size; i++ {
		flipped[i] = make([]int, size)
	}
	for x := 0; x < len(source); x++ {
		for y := 0; y < len(source[x]); y++ {
			flipped[y][x] = source[x][y]
		}
	}
	return flipped
}

// DuplicateGrid return the copy of a 2D array
func DuplicateGrid(grid [][]int) [][]int {
	newGrid := make([][]int, len(grid))
	for i := 0; i < len(grid); i++ {
		newGrid[i] = make([]int, len(grid[i]))
		copy(newGrid[i], grid[i])
	}

	return newGrid
}

// DrawBoard for debug purpose
func DrawBoard(grid [][]int) int {
	for i := 0; i < len(grid); i++ {
		for j := 0; j < len(grid[i]); j++ {
			fmt.Printf(strconv.Itoa(grid[i][j]))
		}
		fmt.Printf("\n")
	}
	return 0
}
