var pos = 0, test, test_status, question, choice, choices, chA, chB, chC, correct = 0;
var timer;

    function shuffleQuestions(array) {
        var currentIndex = array.length, temporaryValue, randomIndex;

        // Selama masih ada elemen yang belum diacak
        while (0 !== currentIndex) {
            // Ambil elemen tersisa
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex -= 1;

            // Tukar dengan elemen saat ini
            temporaryValue = array[currentIndex];
            array[currentIndex] = array[randomIndex];
            array[randomIndex] = temporaryValue;
        }

    return array;
}

questions = shuffleQuestions(questions);

function _(x) {
    return document.getElementById(x);
}

function startQuiz() {
    _("welcomeSection").style.display = "none";
    _("quiz").style.display = "block";
    _("timerSection").style.display = "block";
    renderQuestion();
    startTimer();
}

function startTimer() {
    timer = setInterval(function () {
        timeLeft--;
        if (timeLeft <= 0) {
            clearInterval(timer);
            timeUp();
        } else {
            _("timer").innerHTML = "Waktu tersisa: " + timeLeft + " detik";
        }
    }, 1000);
}

function renderQuestion() {
    test = _("test");
    if (pos >= questions.length) {
        clearInterval(timer);
        if (correct == questions.length) {
            test.innerHTML = "<h2>Congratulations! You got " + correct + " of " + questions.length + " questions correct. You are now allowed to connect to the WiFi.</h2>";
            test.innerHTML += "<button class='btn btn-warning' onclick='connectToWiFI()'>Connect to WiFi</button>";
        } else {
            test.innerHTML = "<h2>We're sorry, you got " + correct + " of " + questions.length + " questions correct. Please try again.</h2>";
            test.innerHTML += "<button class='btn btn-warning' onclick='refreshPage()'>Start over</button>";
        }
        _("test_status").innerHTML = "<b>Quiz completed</b>";
        pos = 0;
        correct = 0;
        return false;
    }
    _("test_status").innerHTML = "Question " + (pos + 1) + " of " + questions.length;
    question = questions[pos][0];
    chA = questions[pos][1];
    chB = questions[pos][2];
    chC = questions[pos][3];
    test.innerHTML = "<h2 class='text-warning'>" + question + "</h2>";
    test.innerHTML += "<input class='form-check-input' type='radio' name='choices' value='A'> " + chA + "<br>";
    test.innerHTML += "<input class='form-check-input' type='radio' name='choices' value='B'> " + chB + "<br>";
    test.innerHTML += "<input class='form-check-input' type='radio' name='choices' value='C'> " + chC + "<br><br>";
    test.innerHTML += "<button class='btn btn-warning' onclick='checkAnswer()'>Submit Answer</button>";

}

function checkAnswer() {
    choices = document.getElementsByName("choices");
    var selectedOption = false;
    for (var i = 0; i < choices.length; i++) {
        if (choices[i].checked) {
            selectedOption = true;
            choice = choices[i].value;
        }
    }
    if (selectedOption) {
        if (choice == questions[pos][4]) {
            correct++;
        }
        pos++;
        renderQuestion();
    } else {
        alert("Anda harus memilih jawaban sebelum lanjut ke soal berikutnya");
    }
}

function connectToWiFI() {
    window.location.href = "https://agincourtresources.com";
}

function refreshPage() {
    window.location.reload();
}

function timeUp() {
    alert("Maaf, waktu sudah habis.");
    refreshPage();
}
