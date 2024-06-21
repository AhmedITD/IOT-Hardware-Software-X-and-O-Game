function onloadBord() 
{
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/game/paly', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json; charset=UTF-8',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            permit: 5,
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        if (data.state === '200') {
            console.log('the page is available:');

            let currentTurn = "x"
            let gameIsFinished = false
            let dataInfo;//
            let gridItems = document.getElementsByClassName("square")

            let boardArray = [
                "0", "1", "2",
                "3", "4", "5",
                "6", "7", "8"
            ];

            for (const item of gridItems) {
                item.addEventListener('click', function () {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch('/game/paly', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json; charset=UTF-8',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            permit: 4
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.state == 200) {
                            if (gameIsFinished) {
                                return;
                            }

                            let value = item.getAttribute('value'); // Store reference to item
                            let index = value - 1;

                            if (boardArray[index] == 'x' || boardArray[index] == 'o') {
                                return;
                            }

                            // Add the (X - O) to the square content
                            let squareContent = document.querySelector(`.square[value='${value}'] .square-content`);
                            squareContent.innerHTML = currentTurn;
                            squareContent.classList.add('animate__animated', 'animate__bounceIn');

                            // Update the backend
                            fetch('/game/paly', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json; charset=UTF-8',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    currentTurn: currentTurn,
                                    square: index,
                                    permit: 1
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok ' + response.statusText);
                                }
                                return response.json();
                            })
                            .then(data => {
                                console.log(data);
                            })
                            .catch(error => {
                                console.error('There was a problem with the fetch operation:', error);
                            });

                            // Update the game logic
                            boardArray[index] = currentTurn;

                            // Switch the turn
                            currentTurn = currentTurn == 'x' ? 'o' : 'x';

                            // Update instructions
                            document.getElementById('instruction').textContent = `${currentTurn.toUpperCase()} turn`;

                            evaluateBoard();

                        } else {
                            console.log(data);
                        }
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
                });
            }


            function evaluateBoard() {
            if (
                // Rows
                (boardArray[0] == boardArray[1] && boardArray[1] == boardArray[2]) ||
                (boardArray[3] == boardArray[4] && boardArray[4] == boardArray[5]) ||
                (boardArray[6] == boardArray[7] && boardArray[7] == boardArray[8]) ||
                // Columns
                (boardArray[0] == boardArray[3] && boardArray[3] == boardArray[6]) ||
                (boardArray[1] == boardArray[4] && boardArray[4] == boardArray[7]) ||
                (boardArray[2] == boardArray[5] && boardArray[5] == boardArray[8]) ||
                // Diagonals
                (boardArray[2] == boardArray[4] && boardArray[4] == boardArray[6]) ||
                (boardArray[0] == boardArray[4] && boardArray[4] == boardArray[8])
            ) {
                checkRealTimeInfo();
            }

            // Check for draw
            let isDraw = boardArray.every(square => square == 'x' || square == 'o');

            if (isDraw) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch('/game/paly', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=UTF-8',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        isDraw: isDraw,
                        permit: 3
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
                alertify.alert('Draw', '', function () {
                    // Additional logic can be added here
                });
            }
            }


            document.getElementById("reset-btn").addEventListener("click", function(){
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch('/game/paly', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=UTF-8',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        permit: 0,
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => console.log(data))
                .catch(error => console.error('There was a problem with the fetch operation:', error));
                reset()
            })

            function reset()
            {    

                gameIsFinished = false
                currentTurn = "x"
                document.getElementById("instruction").textContent = `${currentTurn} turn`

                let index = 0
                for(item of gridItems)
                {
                    
                    let value = item.getAttribute("value")
                    let squareContent = document.querySelector(`.square[value='${value}'] .square-content`)
                    squareContent.classList.remove('animate__animated', 'animate__bounceIn');
                    squareContent.classList.add('animate__animated', 'animate__bounceOut');
                    
                    
                    squareContent.addEventListener('animationend', (animation) => {
                        // console.log("the animation isssss")
                        // console.log(animation.animationName)
                        // do something            
                        if(animation.animationName == "bounceOut")
                        {
                            squareContent.classList.remove('animate__animated', 'animate__bounceOut');
                            squareContent.innerHTML = ""
                        }
                        
                    });

                    index++
                    
                    
                }

                boardArray = [
                    "0", "1", "2",
                    "3", "4", "5",
                    "6", "7", "8"
                ];
            }

            function checkRealTimeInfo() {
                let winner = currentTurn == 'o' ? 'X' : 'O';
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch('/game/paly', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=UTF-8',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        permit: 4,
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.state === '200') {
                        console.log('available:');

                        fetch('/game/paly', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json; charset=UTF-8',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                boardArray: boardArray,
                                winner: winner,
                                permit: 2
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data);
                            alertify.alert(`${winner} Won!`, '', function () {
                                // Additional logic can be added here
                            });
                            gameIsFinished = true;
                            return;
                        })
                        .catch(error => {
                            console.error('There was a problem with the fetch operation:', error);
                        });

                    } else if (data.state === '600') {
                        console.log('The resource is still busy palying...');
                        setTimeout(checkRealTimeInfo, 500); // Retry after 1 second
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    setTimeout(checkRealTimeInfo, 500); // Retry after 1 second
                });
}

        } else if (data.state === '600') 
            {
            console.log('The resource is still busy boadrding...');
            setTimeout(onloadBord, 500); // Retry after 1 second
        }
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
        setTimeout(onloadBord, 500); // Retry after 1 second
    });
}