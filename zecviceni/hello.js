function onClick(event) {
    let p = document.querySelector("h1");
        p.hidden = !p.hidden ? false : true;

        p.classList.toggle("hidden")
}
h1.addEventListener("click",onClick)

alert("ss")