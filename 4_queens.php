<!DOCTYPE html>
<html>
<head>
	<title>Eight Queens Puzzle</title>
	<style type="text/css">
    .container {
      width: 800px;
      margin: 0 auto;
    }
h1 {
  font-size: 38px;
  font-weight: bold;
  text-align: center;
  color: #333;
  text-shadow: 2px 2px 0px rgba(255,255,255,0.5);
  background-color: #f5f5f5;
  padding: 10px;
  border-radius: 10px;
  box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
  max-width: 80%;
  color: #3e8e41;
}

    .button-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    }

    #reset-button {
      background-color: green;
      color: #fff;
      border: none;
      border-radius: 5px;
      padding: 10px;
      font-size: 16px;
      cursor: pointer;
      width: 670px;
    }

    #new-button {
      background-color: blue;
      color: #fff;
      border: none;
      border-radius: 5px;
      padding: 10px;
      font-size: 16px;
      cursor: pointer;
      width: 670px;
      height: 50px;
    }

	table {
		border-collapse: collapse;
	}

	td {
		width: 50px;
		height: 50px;
		text-align: center;
		font-size: 30px;
		font-weight: bold;
		border: 1px solid black;
	}

    tr:nth-child(odd) td:nth-child(even),
    tr:nth-child(even) td:nth-child(odd) {
      background-color: black;
    }

    tr:nth-child(odd) td:nth-child(odd),
    tr:nth-child(even) td:nth-child(even) {
        background-color: white;
    }

	.queen {
		color: darkgreen;
	}

    .popup {
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		background-color: white;
		padding: 20px;
		box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
	}
	.popup h1 {
		margin-top: 0px;
	}
	.popup button {
		background-color: #4CAF50;
		color: white;
		padding: 10px;
		border: none;
		border-radius: 5px;
		cursor: pointer;
		width: 670px;
	}
	.popup button:hover {
		background-color: #3e8e41;
	}

#clock {
  font-size: 22px;
  font-weight: bold;
  text-align: center;
  margin-top: 30px;
  padding-left: 0px;
  margin-left: -60px;
  color: red;
}

#chessboard td {
  width: 163px;
  height: 110px;
}
    
	</style>
	<script type="text/javascript">
		var queens = [];
        var draggingQueen = null;
		var origRow = null;
		var origCol = null;

	    function showTime() {
            var date = new Date();
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var seconds = date.getSeconds();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0'+minutes : minutes;
            seconds = seconds < 10 ? '0'+seconds : seconds;
            var time = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
            document.getElementById('clock').innerHTML = time;
            setTimeout(showTime, 1000);
        }
        window.onload = showTime;

        function isSafePosition(row, col) {
            for (var i = 0; i < queens.length; i++) {
                var q = queens[i];
                if (q[0] == row && q[1] == col) {
                  continue; // Skip the queen being moved
                }
                if (q[0] == row || q[1] == col || Math.abs(q[0] - row) == Math.abs(q[1] - col)) {
                   return false;
                }
            }
          return true;
        }


        function placeQueen(row, col) {
            for (var i = 0; i < queens.length; i++) {
                if (queens[i][0] == row && queens[i][1] == col) {
                    queens.splice(i, 1);
                    updateBoard();
                    return;
                }
            }
            if (isSafePosition(row, col)) {
                queens.push([row, col]);
                updateBoard();
            } else {
                alert("This position is not safe!");
            }
        }

		function startDragging(row, col) {
			for (var i = 0; i < queens.length; i++) {
				if (queens[i][0] == row && queens[i][1] == col) {
					draggingQueen = i;
					origRow = row;
					origCol = col;
					break;
				}
			}
		}

        function drag(row, col) {
			if (draggingQueen !== null) {
				queens[draggingQueen] = [row, col];
				updateBoard();
			}
		}

        function stopDragging() {
			if (draggingQueen !== null) {
				if (!isSafePosition(queens[draggingQueen][0], queens[draggingQueen][1])) {
					queens[draggingQueen] = [origRow, origCol];
					alert("This position is not safe!");
				}
				draggingQueen = null;
				origRow = null;
				origCol = null;
				updateBoard();
			}
		}

		function removeQueen(row, col) {
			for (var i = 0; i < queens.length; i++) {
				if (queens[i][0] == row && queens[i][1] == col) {
					queens.splice(i, 1);
					break;
				}
			}
			updateBoard();
		}
        function showMessage(message) {
	        // create the popup container
	        var popupContainer = document.createElement("div");
	        popupContainer.classList.add("popup");

	        // create the message header
	        var header = document.createElement("h1");
	        header.innerHTML = message;
	        popupContainer.appendChild(header);

	        // create the OK button
	        var okButton = document.createElement("button");
	        okButton.innerHTML = "OK";
	        okButton.addEventListener("click", function() {
		        document.body.removeChild(popupContainer);
	        });
	        popupContainer.appendChild(okButton);

	        // add the popup to the page
	        document.body.appendChild(popupContainer);
        }

		function updateBoard() {
			var table = document.getElementById("chessboard");
			for (var i = 0; i < table.rows.length; i++) {
				for (var j = 0; j < table.rows[i].cells.length; j++) {
					var cell = table.rows[i].cells[j];
					cell.innerHTML = "";
					cell.className = "";
					for (var q = 0; q < queens.length; q++) {
						if (queens[q][0] == i && queens[q][1] == j) {
							cell.innerHTML = "&#9813;";
							cell.className = "queen";
						}
					}
				}
			}
			if (queens.length == 4) {
				var isSafe = true;
				for (var q1 = 0; q1 < queens.length; q1++) {
					for (var q2 = q1 + 1; q2 < queens.length; q2++) {
						if (queens[q1][0] == queens[q2][0] || queens[q1][1] == queens[q2][1] || Math.abs(queens[q1][0] - queens[q2][0]) == Math.abs(queens[q1][1] - queens[q2][1])) {
							isSafe = false;
							break;
						}
					}
					if (!isSafe) {
						break;
					}
				}
               if (isSafe) {
                	showMessage("Congratulations! You have solved the Eight Queens Puzzle!");
	                document.querySelector("button").style.display = "none"; // hide the reset button
                } else {
	                showMessage("Sorry, your solution is not correct. Please try again.");
                }

			} else {
                document.querySelector("button").style.display = "block"; // show the reset button
            }
		}

        function resetBoard() {
            queens = [];
            updateBoard();
        }
        function newBoard() {
            queens = [];
            updateBoard();
        }


	</script>
</head>
<body>
  <div class="container">
    <h1>Eight Queens Puzzle</h1>
	<div id="clock"></div>

    <div class="board-container">
      <table id="chessboard">
        <?php for ($i = 0; $i < 4; $i++) { ?>
        <tr>
          <?php for ($j = 0; $j < 4; $j++) { ?>
          <td onclick="placeQueen(<?php echo $i; ?>, <?php echo $j; ?>)" onmousedown="startDragging(<?php echo $i; ?>, <?php echo $j; ?>)" onmousemove="drag(<?php echo $i; ?>, <?php echo $j; ?>)" onmouseup="stopDragging()"></td>
          <?php } ?>
        </tr>
        <?php } ?>
      </table>
    </div>
    <div class="button-container">
    <button id="reset-button" onclick="resetBoard()">Reset</button>
    </div>
    <div class="button-container">
      <button id="new-button" onclick="newBoard()">New Game</button>
    </div>
  </div>
</body>
</html>
