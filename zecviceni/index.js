console.log("ahoj")

let global = "global"



function showAlert() {
    alert("vyrajte iPhone 16 Pro" + global);
    console.log("Alert odkliknut")
}

//showAlert();


//query selector 
// najde to prvni prvek ktery odpoida css celectoru
let h1 = document.querySelector("h1")
//document.querySelector("h1.title")

console.log(h1, h1.tagName, h1.textContent)


//query selector all  to vybere vsechny prvky, ktere odpovidaji tomu css





let h2 = document.querySelector("sdfsdf")
if (h2){
    console.log("je to alright")

}
else {

    console.log("není to alright")

}

h1.querySelector


//je doporucovany vystup z query selestoru dat do proměnný

// eventa input, mouse 
// když kliknu na input tak ho zvětšit

function onClick(){
    alert("ahoj")
    console.log("kliknuto" , Event)
}


h1.addEventListener("click",onClick)

