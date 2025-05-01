function toggleWishlist(movie) {
    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    let index = wishlist.findIndex(item => item.title === movie.title);

    if (index === -1) {
        wishlist.push(movie);
        alert(`${movie.title} added to wishlist!`);
    } else {
        wishlist.splice(index, 1);
        alert(`${movie.title} removed from wishlist!`);
    }

    localStorage.setItem("wishlist", JSON.stringify(wishlist));
}

function loadWishlist() {
    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    return wishlist;
}