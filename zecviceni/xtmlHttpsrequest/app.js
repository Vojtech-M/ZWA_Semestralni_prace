let xhr = new XMLHttpRequest();
console.log(xhr.status); // => 0
xhr.open("GET", "https://zwa.toad.cz/passwords.txt", true); // true enables asynchronous processing

xhr.addEventListener("load", function(e) {
    // Check if the request was successful
    if (xhr.status >= 200 && xhr.status < 300) {
        let text = xhr.responseText; // fixed typo: reponseText -> responseText
        //console.log(text);
        let names = text.split("\n");

        let nameList = document.querySelector(".names");
        names.forEach(function(name) { // fixed syntax: added parameter "name"
            let li = document.createElement("li");
            li.textContent = name.trim(); // trim removes whitespace from both sides
            nameList.append(li);
        });

    } else {
        console.log("Error status:", xhr.status);
    }
});

xhr.addEventListener("error", function(e) {
    console.log("Error occurred", e);
});

xhr.send();

let form = document.querySelector("form")

form.addEventListener("submit", function(e) {
    e.preventDefault

    let inputname = document.querySelector("input")
    let name = inputName.value
    if (names.includes(name)){
        alert("neco neco")

    } else {
        alert("neco neco")

    }

})





/*


//let xhr = new XMLHttpRequest()
console.log(xhr.status) // => 0

xhr.open("GET","https://zwa.toad.cz/passwords.txt",true); // true chceme aby prochazel asynchrone

xhr.addEventListener("load",function(e){
    //console.log(e.target == xhr) //trget je objekt kterej vyvolal tu udalost
    //console.log(xhr.status) // => 200
    if (xhr.status >= 200 && xhr.status <300) {
        let text = xhr.reponseText;
        console.log(text)
        let names = text.split("\n")

        let nameList = document.querySelector(".names")
        names.forEach((function) {
            let li = document.createElement("li")
            li.textContent = name.trim()//trim to oreze z obou stran
            nameList.append(li)

        })
       
        //...
    } else{
        console.log("chybový status:", xhr.status)
    }

})

xhr.addEventListener("error",function(e){
console.log("Nastel error",e)

})
// je dobrou funkci dat listeneri na error

xhr.send()
// vytvorit, otevrit

*/