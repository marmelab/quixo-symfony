package board

// Board with Grid and Player
type Board struct {
	Grid   [][]int
	Player int
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

// GetBoard return a Board from grid and player
func GetBoard(grid [][]int, player int) Board {
	newBoard := Board{}
	newBoard.Grid = grid
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
			if isOutsideCube(cube.Coords, len(grid[x])) && isPlayerCube(cube.Value, player) {
				movables = append(movables, cube)
			}
		}
	}
	return movables
}

// GetAvailablesDestinations return an array of destinations
func GetAvailablesDestinations(board Board, coords Coords) []Coords {
	destinations := []Coords{}
	x := coords.X
	y := coords.Y
	indexEnd := len(board)

	if x == 0 or x == indexEnd {
		if y != 0 {
			destinations = append(destinations, Coords{X: x, Y: 0})
		}
		if y != indexEnd {
			destinations = append(destinations, Coords{X: x, Y: indexEnd})
		}
		if x == 0 {
			destinations = append(destinations, Coords{X: indexEnd, Y: y})
		} else {
			destinations = append(destinations, Coords{X: 0, Y: y})
		}
	} else if y == 0 or y == indexEnd {
		if x != 0 {
			destinations = append(destinations, Coords{X: 0 Y: y})
		}
		if x != indexEnd {
			destinations = append(destinations, Coords{X: indexEnd, Y: y})
		}
		if y == 0 {
			destinations = append(destinations, Coords{X: x, Y: indexEnd})
		} else {
			destinations = append(destinations, Coords{X: x, Y: 0})
		}
	}

	return destinations
}

// Move a cube from coordsStart to coordsEnd
func MoveCube(board Board, coordsStart Coords, coordsEnd Coords) [][]int {
	grid := board.Grid
	player := board.Player
	if coordsStart.X == coordsEnd.X {
		return shiftLine(grid, coordsStart.Y, coordsEnd.Y, coordsStart.X, player)
	}
	if coordsStart.Y == coordsEnd.Y {
		flippedGrid := flipRowsAndCols(grid)
		flippedGrid = shiftLine(grid, coordsStart.X, coordsEnd.X, coordsStart.Y, player)
		return flipRowsAndCols(flippedGrid)
	}
	return nil
}


func isOutsideCube(coords Coords, size int) bool {
	return coords.X == 0 || coords.Y == 0 || coords.X == size-1 || coords.Y == size-1
}

func isPlayerCube(cubeValue int, playerValue int) bool {
	return cubeValue == 0 || cubeValue == playerValue
}

// Shift all value from xEnd to xStart in one row
func shiftLine(grid [][]int, xStart int, xEnd int, rowIndex int, player int) [][] int {
	newGrid = grid
	step := 1
	indexBound := xStart - 1
	if xEnd > xStart {
		step = -1
		indexBound = xStart + 1
	}
	value = player
	for index := xEnd; index != indexBound; index += step {
		tmpValue = newGrid[rowIndex][index]
		newGrid[rowIndex][index] = value
		value = tmpValue
	}
	return newGrid
}

func flipRowsAndCols(source [][]int) [][]int {
	flipped = [][]int
	for x := 0; x < len(source); x++ {
		for y := 0; y < len(source[x]); y++ {
			flipped[y][x] = source[x][y]
		}
	}
	return flipped
}
