package board

type Board struct {
	Grid   [][]int
	Player int
}

type Coords struct {
	X int
	Y int
}

type Cube struct {
	Coords Coords
	Value  int
}

func GetBoard(grid [][]int) Board {
	newBoard := Board{}
	newBoard.Grid = grid
	return newBoard
}

func isOutsideCube(coords Coords, size int) bool {
	return coords.X == 0 || coords.Y == 0 || coords.X == size-1 || coords.Y == size-1
}

func isPlayerCube(cubeValue int, playerValue int) bool {
	return cubeValue == 0 || cubeValue == playerValue
}

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
