import { useState } from "react";
import "./App.css";

const PIECES = {
  white: { k: "\u2654", q: "\u2655", r: "\u2656", b: "\u2657", n: "\u2658", p: "\u2659" },
  black: { k: "\u265A", q: "\u265B", r: "\u265C", b: "\u265D", n: "\u265E", p: "\u265F" },
};

const createInitialBoard = () => {
  const board = Array(64).fill(null);
  const layout = ["r", "n", "b", "q", "k", "b", "n", "r"];

  layout.forEach((p, i) => {
    board[i] = { type: p, color: "black" };
    board[56 + i] = { type: p, color: "white" };
  });

  for (let i = 0; i < 8; i++) {
    board[8 + i] = { type: "p", color: "black" };
    board[48 + i] = { type: "p", color: "white" };
  }
  return board;
};

function App() {
  const [board, setBoard] = useState(createInitialBoard());
  const [selected, setSelected] = useState(null);
  const [turn, setTurn] = useState("white");

  // Helper: Convert index to {row, col}
  const getPos = (i) => ({ row: Math.floor(i / 8), col: i % 8 });

  const isValidMove = (start, end) => {
    const piece = board[start];
    const target = board[end];
    if (target && target.color === piece.color) return false; // Can't capture own piece

    const s = getPos(start);
    const e = getPos(end);
    const dy = e.row - s.row;
    const dx = e.col - s.col;
    const absDy = Math.abs(dy);
    const absDx = Math.abs(dx);

    // Path checking for sliding pieces (Rook, Bishop, Queen)
    const isPathClear = () => {
      const stepY = dy === 0 ? 0 : dy / absDy;
      const stepX = dx === 0 ? 0 : dx / absDx;
      let currY = s.row + stepY;
      let currX = s.col + stepX;

      while (currY !== e.row || currX !== e.col) {
        if (board[currY * 8 + currX]) return false;
        currY += stepY;
        currX += stepX;
      }
      return true;
    };

    switch (piece.type) {
      case "p": // Pawn
        const direction = piece.color === "white" ? -1 : 1;
        const startRow = piece.color === "white" ? 6 : 1;

        // Forward move
        if (dx === 0 && !target) {
          if (dy === direction) return true;
          if (dy === 2 * direction && s.row === startRow && !board[start + 8 * direction]) return true;
        }
        // Capture
        if (absDx === 1 && dy === direction && target) return true;
        return false;

      case "r": // Rook
        if (dx !== 0 && dy !== 0) return false;
        return isPathClear();

      case "n": // Knight
        return (absDx === 1 && absDy === 2) || (absDx === 2 && absDy === 1);

      case "b": // Bishop
        if (absDx !== absDy) return false;
        return isPathClear();

      case "q": // Queen
        if (absDx !== absDy && dx !== 0 && dy !== 0) return false;
        return isPathClear();

      case "k": // King
        return absDx <= 1 && absDy <= 1;

      default:
        return false;
    }
  };

  const handleSquareClick = (index) => {
    const piece = board[index];

    // Selecting a piece
    if (piece && piece.color === turn) {
      setSelected(index);
      return;
    }

    // Moving a piece
    if (selected !== null) {
      if (isValidMove(selected, index)) {
        const newBoard = [...board];
        newBoard[index] = board[selected];
        newBoard[selected] = null;

        setBoard(newBoard);
        setSelected(null);
        setTurn(turn === "white" ? "black" : "white");
      } else {
        // If move is invalid, just deselect or select the new piece if it belongs to the player
        setSelected(piece && piece.color === turn ? index : null);
      }
    }
  };

  return (
    <div className="game-container">
      <div className="status-bar">
        <h2 className={turn}>{turn.toUpperCase()}'S TURN</h2>
      </div>
      
      <div className="board">
        {board.map((square, i) => {
          const isDark = (Math.floor(i / 8) + i) % 2 === 1;
          return (
            <div
              key={i}
              className={`square ${isDark ? "dark" : "light"} ${
                selected === i ? "selected" : ""
              }`}
              onClick={() => handleSquareClick(i)}
            >
              <span className="piece">
                {square ? PIECES[square.color][square.type] : ""}
              </span>
            </div>
          );
        })}
      </div>
      
      <button className="reset-btn" onClick={() => {
        setBoard(createInitialBoard());
        setSelected(null);
        setTurn("white");
      }}>
        Reset Game
      </button>
    </div>
  );
}

export default App;