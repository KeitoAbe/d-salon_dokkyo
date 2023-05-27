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
      text: "次の場所のうち、移動販売車が販売を行っているのはどれか？",
      choice: ["中央棟前", "天野貞祐記念館前", "学生センター前", "西棟前"],
      ansewer: "西棟前"
    },
    {
      text: "獨協大学では、何か国語の言語を学ぶことができるか？",
      choice: ["8か国","10か国", "15か国", "20か国"],
      ansewer: "15か国"
    },
    {
      text: "獨協大学校歌の作曲者は誰か？",
      choice: ["萬澤 遼", "野田隆造", "小池辰雄", "谷川正秋"],
      ansewer: "萬澤 遼"
    },
    {
      text: "次の文は獨協大学校歌の一部である。〇に入る言葉は何か？<br>○○○ふりさけみつつ　さし昇る陽を胸に抱きて",
      choice: ["雲隠れ", "天の原", "大江山", "有明の"],
      ansewer: "天の原"
    },
    {
      text: "次のうち、獨協大学に存在している施設として間違っているものはどれか？",
      choice: ["キャリアセンター", "防災センター", "エクステンションセンター", "カウンセリングセンター"],
      ansewer: "防災センター"
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
      alert("素晴らしい！正解です！　‧˚₊*̥ヾ(｀°∀°)/‧˚₊*̥ ")
    } else {
      incorrectAnswer();
      alert("残念！正解は「" + answer + "」です！　o(`･ω･´)o")
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