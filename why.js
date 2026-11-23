const whyFolder = document.getElementById("why-folder");

const whyFolderInstruction =
    document.getElementById("why-folder-instruction");

const whyScene = document.getElementById("why-scene");

whyFolder.addEventListener("click", () => {
    // If the folder is open, we prevent it from reading any click inputs again
    if (whyScene.classList.contains("open")) {
        return;
    }

    whyScene.classList.add("open");
    whyFolder.setAttribute("aria-expanded", "true");
    whyFolderInstruction.style.opacity = "0";
});