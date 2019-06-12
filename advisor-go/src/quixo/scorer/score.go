import (
	"quixo/game"
)

/*
 * GetGridScore return the score of the board
 * the score of the board is the max score of all straight lines or diagonal
*/
func GetBoardScore(board game.Board) int {
	diagonalScore = getDiagonalScore(board)
	invertedDiagScore = getInvertedDiagonalScore(board)
	rowsScore = getMaxLinesScore(board)
	invertedBoard := game.Board{
		Board: board.FlipRowsAndCols(board.Grid)
		Player: board.Player
	}
	colsScore = getMaxLinesScore(invertedBoard)
	scores = []int{diagonalScore, invertedDiagScore, rowsScore, colsScore}
	return getMaxInArray(scores)
}

func getMaxLinesScore(board game.Board) int {
	size := len(board.Grid)
	scores := []int
	for i := 0; i < size; i++ {
		scores = append(scores, getLineScore(board.Grid[i], board.Player))
	}
	return getMaxInArray(scores)
}

func getLineScore(line []int, player int) int {
	score := 0
	for i := 0; i < len(line); i++ {
		if line[i] == player {
			score += 1
		}
	}
	return score
}

func getDiagonalScore(board game.Board) int {
	diag := []int
	size = len(board.Grid)
	for i := 0; i < size; i++ {
		diag = append(diag, board.Grid[i][i])
	}
	return getLineScore(diag, board.Player)
}

func getInvertedDiagonalScore(board game.Board) int {
	diag := []int
	size = len(board.Grid)
	for i := 0; i < size; i++ {
		diag = append(diag, board.Grid[i][size - 1 - i])
	}
	return getLineScore(diag, board.Player)
}

func getMaxInArray(array []int) int {
	max = 0
	for i := 0; i < len(array); i++ {
		if array[i] > max {
			max = array[i]
		}
	}
	return max
}