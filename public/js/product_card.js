bookmarkButtons = document.querySelectorAll(".product-bookmark-btn");
currentUrl = window.location.href;

bookmarkButtons.forEach((button) => {
    button.addEventListener("click", async (_event) => {
        // check to see if bookmark is already set
        if (button.classList.contains("bookmark-full")) {
            await removeBookmark(button.dataset.productid);
            if (currentUrl == "bookmarks") {
                button.parentElement.remove();
            }
        } else {
            await addBookmark(button.dataset.productid);
        }

        button.classList.toggle("bookmark-full");
    });
});

async function addBookmark(productId) {
    const data = new FormData();
    data.append("productId", productId);

    return await fetch("/bookmarks/add", {
        method: "POST",
        body: data,
    }).then((response) => response.ok);
}

async function removeBookmark(productId) {
    const data = new FormData();

    data.append("productId", productId);
    await fetch("/bookmarks/remove", {
        method: "POST",
        body: data,
    });
}
