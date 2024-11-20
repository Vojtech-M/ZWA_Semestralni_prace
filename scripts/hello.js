
//function showAlert() {
 //   alert("Test");
//}
//showAlert();


a = "Ahoj"; // tohle nedělat

var b;

let c = "Praha";

const d = "Brno";






//var form = document.querySelector("from");
//form.addEventListener("Submit",function(e){
//   e.preventDefault();
//});
// fomrulář se neodešle

// heslo min délka 6
// max input délka 128
// jméno minimálně 2
// to co leze z formulářů je vždycky text a tak je to potřeba přetypovat


//AJAX 
// našeptávače

//na ty data použít JSOn
//kod z přednášky
function showHint(event) {
    let str = event.target.value;
    
    if (str.length == 0) {
        document.getElementById("txtHint").innerHTML = "";
    return;
    } else {
        var xmlhttp = new XMLHttpRequest(); //objekt pro posilani dotazu
        xmlhttp.addEventListener("load", function() {
            document.getElementById("txtHint").innerHTML = this.responseText;
        });
        xmlhttp.open("GET", "gethint.php?q=" + str, true);
        xmlhttp.send();
        }
}    
    
window.addEventListener("load", registerEvents);

function registerEvents() {
    let input = document.querySelector("[type=text]");
    input.addEventListener("keyup", showHint)
}