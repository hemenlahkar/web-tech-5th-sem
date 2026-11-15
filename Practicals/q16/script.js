const display = document.getElementById("main-input");
const btnContainer = document.querySelector(".btns");

let expression = "";
let openParanthesisCount = 0;

btnContainer.addEventListener("click", function (e) {
  if (e.target.className == "op") {
    switch (e.target.textContent) {
      case "+":
      case "-":
      case "/":
      case "%":
        addSymbol(e.target.textContent);
        break;
      case "X":
        addSymbol("*");
        break;
      case "C":
        expression = "";
        break;
      case ".":
        if (/^\d$/.test(expression.charAt(expression.length - 1)))
          expression += ".";
        break;
      case "( )":
        addParanthesis();
        break;
      case "<x":
        if (expression) expression = expression.slice(0, -1);
        break;
      case "=":
        getResult();
        break;
      default:
        break;
    }
  } else if (e.target.className === "digit") {
    expression += e.target.textContent;
  }
  display.value = expression || "0";
});

function addSymbol(symbol) {
  if (shouldRemove(expression.charAt(expression.length - 1)))
    expression = expression.slice(0, -1);
  if (expression || symbol == "-") expression += symbol;
}

function shouldRemove(character) {
  if (["+", "-", "*", "/", ".", "("].includes(character)) return true;
  return false;
}

function addParanthesis() {
  const lastChar = expression.charAt(expression.length - 1);
  if (/^\d$/.test(lastChar)) {
    if (!openParanthesisCount) {
      expression += "*(";
      openParanthesisCount++;
    } else {
      expression += ")";
      openParanthesisCount--;
    }
    return;
  }
  if (lastChar === "(") {
    expression += "(";
    openParanthesisCount++;
    return;
  }
  if (lastChar === ".") {
    expression = expression.slice(0, -1) + "*(";
    openParanthesisCount++;
    return;
  }
  if (["*", "+", "-", "/"].includes(lastChar)) {
    expression += "(";
    openParanthesisCount++;
    return;
  }
  if (openParanthesisCount) {
    expression += ")";
    openParanthesisCount--;
  }
}

function getResult() {
    expression = expression.replaceAll("%", "*0.01");
    display.value = expression = eval(expression).toString();
}