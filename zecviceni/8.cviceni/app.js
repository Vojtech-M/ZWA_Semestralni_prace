let namesList = document.querySelector("ul.names");

function renderName(name) {
    let li = document.createElement("li");
    li.innerHTML = name;
    namesList.append(li);
}

function getNamesWithXHR() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "http://zwa.toad.cz/passwords.txt", true); // true je default a chceme vždy

    xhr.addEventListener("load", function (e) {
        if (xhr.status >= 200 && xhr.status < 300) {
            let text = xhr.responseText;  // JSON.parse(xhr.responseText) - pokud by to byl JSON
            let names = text.split("\n");

            for (let i = 0; i < names.length; i++) {
                let name = names[i].trim();
                if (names[i] == "") { return }
                renderName(name);
            }
        } else {
            console.log("Error: ", xhr.statusText);
        }
    });

    xhr.addEventListener("error", function () {
        console.log("Error: ", xhr.statusText);
    });

    xhr.send();
}

getNamesWithXHR();  // zavoláme funkci, která naplní seznam jmen


/* --------------------------- */

let nameInput = document.querySelector("input#name");
document.querySelector("form").addEventListener("submit", function (event) {
    event.preventDefault();  // zamezíme odeslání formuláře

    if (nameInput.value.trim() == "") {
        alert("Vyplňte jméno.");
        return;
    }

    let xhr = new XMLHttpRequest();     // 1. vytvoříme instanci XMLHttpRequest
    xhr.open("GET", "http://zwa.toad.cz/passwords.txt"); // 2. otevřeme spojení
    xhr.addEventListener("load", function (e) {
        if (xhr.status >= 200 && xhr.status < 300) {
            let text = xhr.responseText;
            let names = text.split("\n");

            if (names.includes(nameInput.value)) {
                alert("Jméno už existuje");
                return;
            }
            alert("Jméno je v pořádku");
            nameInput.value = "";
        } else {
            console.log("Error: ", xhr.statusText);
        }
    });

    xhr.addEventListener("error", function () {
        console.log("Error: ", xhr.statusText);
    });

    xhr.send(); // 3. odešleme požadavek
});

/* --------------------------- */

/*  Pro zajímavost - naplnění tabulky s použitím Fetch API a then() */
function getNamesWithFetch1() {
    fetch("http://zwa.toad.cz/passwords.txt")
    .then(response => response.text())
    .then(function (text) {
        let names = text.split("\n");

        names.forEach(name => {
            name = name.trim();
            if (name == "") { return }
            renderName(name);
        });
    })
}

/*  Pro zajímavost - naplnění tabulky s použitím Fetch API a try/catch */
async function getNamesWithFetch2() {
    try {
        let response = await fetch("http://zwa.toad.cz/passwords.txt");
        let text = await response.text();
        let names = text.split("\n");

        names.forEach(name => {
            name = name.trim();
            if (name == "") { return }
            renderName(name);
        });
    } catch (error) {
        console.log("Error: ", error);
    }
}