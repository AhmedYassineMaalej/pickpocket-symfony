// public/js/myspace.js

// Settings Password Form Validation
document.addEventListener("DOMContentLoaded", function () {
    const oldPass = document.getElementById('old_password');
    const newPass = document.getElementById('new_password');
    const saveBtn = document.getElementById('saveBtn');

    if (oldPass && newPass && saveBtn) {
        function toggleButtonState() {
            if (oldPass.value.trim() === "" || newPass.value.trim() === "") {
                saveBtn.disabled = true;
            } else {
                saveBtn.disabled = false;
            }
        }

        oldPass.addEventListener('input', toggleButtonState);
        newPass.addEventListener('input', toggleButtonState);
    }
});

function deleteBookmarkFromDb(buttonElement, productId) {
    const iconImg = buttonElement.querySelector('.bookmark-icon');
    
    // Optimistically update UI icon state using your correct path structure
    if (iconImg) {
        iconImg.src = "/images/bookmark-empty.svg"; 
    }

    const targetCard = buttonElement.closest('[id^="bookmark-card-"]');

    if (!productId || productId === 'undefined') {
        console.error("Could not resolve valid product ID.");
        if (iconImg) iconImg.src = "/images/bookmark-full.svg";
        return;
    }

    // Fixed: Matches 'productId' parameter mapping expected by BookmarksController
    fetch('/bookmarks/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `productId=${encodeURIComponent(productId)}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('The bookmark does not exist or server error.');
        }
        return response.text(); // BookmarksController returns plain text string 'OK'
    })
    .then(text => {
        if (text === 'OK') {
            if (targetCard) {
                targetCard.style.transition = 'all 0.35s cubic-bezier(0.4, 0, 0.2, 1)';
                targetCard.style.opacity = '0';
                targetCard.style.transform = 'scale(0.85) translateY(5px)';
                
                setTimeout(() => {
                    targetCard.remove();
                    if (document.querySelectorAll('[id^="bookmark-card-"]').length === 0) {
                        window.location.reload();
                    }
                }, 350);
            } else {
                window.location.reload();
            }
        } else {
            if (iconImg) iconImg.src = "/images/bookmark-full.svg";
            alert('Failed to remove entry record.');
        }
    })
    .catch(err => {
        console.error('Network sync failure:', err);
        if (iconImg) iconImg.src = "/images/bookmark-full.svg";
        alert('Could not update your bookmarks: ' + err.message);
    });
}