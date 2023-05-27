(function() {
  // sceneXXXは、各ゲーム画面の要素です
  const sceneTop = document.getElementById("sceneTop");
  const sceneGame = document.getElementById("sceneGame");
  const sceneAnswer = document.getElementById("sceneAnswer");
  const sceneResult = document.getElementById("sceneResult");
  // 問題文を表示する要素です
  const textQuestion = document.getElementById("textQuestion");
  // 選択肢を表示する要素です
  const listAnswer = document.getElementById("listAnswer");
  // 正解数を表示する要素です
  const numResult = document.getElementById("numResult");
  // トップ画面にて、ゲームを開始するボタン要素です
  const btnStart = document.getElementById("btnStart");
  // リザルト画面にて、ゲームをリセットしトップへ戻るボタン要素です
  const btnReset = document.getElementById("btnReset");

  //問題文を格納する要素です
  const question = [
    {
      text: "The (　　) of the meeting will be exactly one hour.",
      choice: ["add", "length", "limited", "adbanced"],
      ansewer: "length"
    },
    {
      text: "We hope you will (　　) this invitation to speak at our seminar.",
      choice: ["accept", "lend", "vote", "adopt"],
      ansewer: "accept"
    },
    {
      text: "The (　　) can help you find the book that you want to borrow.",
      choice: [
        "librarian",
        "luggage",
        "access",
        "lost"
      ],
      ansewer: "librarian"
    },
    {
      text: "She previously managed and organized a government agency, so she has experience in (　　).",
      choice: ["adjacent", "administration", "warehouse", "additionally"],
      ansewer: "administration"
    },
    {
      text: "Most graduates of a university remain (　　) fans of its sports team throughout their lives.",
      choice: ["license", "update", "loyal", "admit"],
      ansewer: "loyal"
    },
    {
      text: "I (　　) my mistake and accept responsibility for any problems that it caused.",
      choice: ["admit", "administration", "loyal", "librarian"],
      ansewer: "admit"
    },
    {
      text: "Make sure to (　　) your computer with the newest version of the security software.",
      choice: ["accept", "length", "loss", "limited"],
      ansewer: "update"
    },
    {
      text: "You must pass a written test and a road test to get your driver’s (　　).",
      choice: ["add", "advanced", "license", "lend"],
      ansewer: "license"
    },
    {
      text: "The community program receives some government funding and is financed (　　) through private donations.",
      choice: ["vote", "additionally", "luggage", "lost"],
      ansewer: "additionally"
    },
    {
      text: "Extra inventory is kept in the (　　) until it is needed at the store.",
      choice: ["access", "warehouse", "adjacent", "additionally"],
      ansewer: "warehouse"
    },
    {
      text: "A print shop is (　　) to our building, so we can make copies next door.",
      choice: ["adjacent", "warehouse", "license", "admit"],
      ansewer: "adjacent"
    },
    {
      text: "You should bring a map, or else you might get (　　).",
      choice: ["update", "loyal", "admit", "lost"],
      ansewer: "lost"
    },
    {
      text: "Only employees who work in the department may (　　) these files.",
      choice: ["administration", "librarian", "length", "access"],
      ansewer: "access"
    },
    {
      text: "Passengers on this flight may check two pieces of (　　).",
      choice: ["limited", "luggage", "add", "loss"],
      ansewer: "luggage"
    },
    {
      text: "The managers have decided to (　　) a new policy, which will take effect next month.",
      choice: ["adopt", "vote", "lend", "advanced"],
      ansewer: "adopt"
    },
    {
      text: "People across the country will (　　) in the upcoming national election.",
      choice: ["vote", "lost", "access", "luggage"],
      ansewer: "vote"
    },
    {
      text: "The bank agreed to (　　) money to the company for its expansion.",
      choice: ["additionally", "lend", "warehouse", "adjacent"],
      ansewer: "lend"
    },
    {
      text: "The (　　) class is much more demanding than the introductory course. ",
      choice: ["admit", "advanced", "update", "license"],
      ansewer: "advanced"
    },
    {
      text: "Agriculture contributes very little to the country’s economy due to (　　) rainfall in the region.",
      choice: ["loss", "advanced", "limited", "add"],
      ansewer: "limited"
    },
    {
      text: "The university plans to (　　) some new courses next fall.",
      choice: ["add", "lend", "luggage", "vote"],
      ansewer: "add"
    },
    {
      text: "She previously managed and organized a government agency, so she has experience in (　　).",
      choice: ["adopt", "administration", "access", "warehouse"],
      ansewer: "administration"
    },
  ];

  // ゲームで使用する共通の変数です
  // answer...プレイヤーの答えと比較する、正解のテキストです
  // gameCount...プレイヤーが答えた数です
  // success...プレイヤーが答えて、正解した数です
  let state = {
    answer: "",
    gameCount: 0,
    success: 0
  };

  function numEnglish(){
    gameCount
  }

  // ゲームをリセットする関数を書いてみましょう
  function init() {
    state.gameCount = 0;
    state.success = 0;
    changeScene(sceneResult, sceneTop);

    btnStart.addEventListener("click", gameStart, false);
  }

  // 1.トップ画面　2.ゲーム画面　3.リザルト画面
  function changeScene(hiddenScene, visibleScene) {
    hiddenScene.classList.add("is-hidden");
    hiddenScene.classList.remove("is-visible");
    visibleScene.classList.add("is-visible");
  }

  // 問題と選択肢をViewに表示し、正解を共通の変数へ代入
  function showQuestion() {
    var str = "";
    question[state.gameCount].choice.forEach(function(value) {
      str += '<button class="questionChoice btn btn-primary" type="button">' + value + "</button>";
    });
    textQuestion.innerHTML = question[state.gameCount].text;
    listAnswer.innerHTML = str;
    console.log(state.gameCount)
    document.getElementById("numEnglish").innerHTML = state.gameCount + 1;
  }

  function choiceQuestion() {
    let questionChoice = document.querySelectorAll(".questionChoice");
    questionChoice.forEach(function(choice) {
      choice.addEventListener(
        "click",
        function() {
          state.answer = this.textContent;
          checkAnswer(question[state.gameCount].ansewer);
        },
        false
      );
    });
  }

  // 解答が正解か不正解かをチェック
  function checkAnswer(answer) {
    if (answer === state.answer) {
      correctAnswer();
    } else {
      incorrectAnswer();
    }
    state.gameCount++;
    if (state.gameCount < question.length) {
      showQuestion();
      choiceQuestion();

    } else {
      gameEnd();
    }
  }

  // 上でチェックし、正解だった場合
  function correctAnswer() {
    state.success++;
    console.log("正解");
  }

  // 上でチェックし、不正解だった場合
  function incorrectAnswer() {
    console.log("不正解");
  }

  // スタートボタンが押された時
  function gameStart() {
    changeScene(sceneTop, sceneGame);
    showQuestion();
    choiceQuestion();
  }

  // ゲームが終了した時
  function gameEnd() {
    changeScene(sceneGame, sceneResult);
    numResult.innerHTML = state.success;
    btnReset.addEventListener("click", init, false);
  }

  // スタートボタンが押されたら、ゲームスタートの関数を
  // リセットボタンが押されたら、ゲーム終了後にゲームをリセットする関数を実行するイベントです
  init();
})();