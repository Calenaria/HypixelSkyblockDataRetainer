window.addEventListener('load', function () {
    badges = document.getElementsByClassName("badge");
    Array.from(badges).forEach((element) => {
        switch (element.textContent) {
            case "UNCOMMON":
                element.style.color = "lightgreen";
            break;
            case "RARE":
                element.style.color = "blue";
            break;
            case "EPIC":
                element.style.color = "purple";
            break;
            case "LEGENDARY":
                element.style.color = "orange";
            break;
            case "MYTHIC":
                element.style.color = "magenta";
            break;
            case "DIVINE":
                element.style.color = "cyan";
            break;
            case "SPECIAL":
                element.style.color = "red";
            break;
            case "VERY_SPECIAL":
                element.style.color = "red";
            break;
            case "UNOBTAINABLE":
                element.style.color = "black";
            break;
        }
    });
})

