function clearForm() {
    document.getElementById('image_file').value = "";
    document.getElementById('bookname').value = "";
    document.getElementById('brief').value = "";
    document.getElementById('price').value = "";
}

class Book {
    constructor(bookImage, bookName, bookBrief, bookPrice) {
        this.image = bookImage;
        this.name = bookName;
        this.brief = bookBrief;
        this.price = bookPrice;
    }

    Info() {
        displayImage(this.image, 'listbooks');
        return "<br><strong><u>" + this.name + "</u></strong><br>" + this.brief +
            "<br><strong>Price: </strong>" + this.price + " TL";
    }
    ToPrice() {
        return Number(this.price);
    }
    ToCart() {
        totalCost += this.ToPrice();
    }
}

function displayImage(image, where) {
    var reader = new FileReader();
    reader.onload = function (e) {
        var img = document.createElement("img");
        img.src = e.target.result;
        img.width = 100;
        document.getElementById(where).appendChild(img);
    }
    reader.readAsDataURL(image);
}

var books = new Array();

function saveBook(bookImage, bookName, bookBrief, bookPrice) {
    var newBook = new Book(bookImage, bookName, bookBrief, bookPrice);
    books.push(newBook);
}

function listBooks() {
    var mylist = "";
    for (let i = 0; i < books.length; i++) {
        mylist += books[i].Info() + "<br>";
    }
    costAllNewBooks();
    return mylist;
}

function checkError(control) {
    try {
        if (control.value == "") throw "not written!";
        if (isNaN(control.value)) throw "not a Number!";
        num = Number(control.value);
        if (num < 0) throw "cannot be negative!";
    } catch (error) {
        alert("The price is " + error);
    }
}

var totalCost = 0.0;
var hasWitcher, hasLOTR, hasHP, hasSherlock, hasOkkes = false;
function addToCartWitcher() {
    totalCost += 200.00;
    hasWitcher = true;
}

function addToCartLOTR() {
    totalCost += 34.95;
    hasLOTR = true;
}

function addToCartHP() {
    totalCost += 250.00;
    hasHP = true;
}

function addToCartSherlock() {
    totalCost += 21.45;
    hasSherlock = true;
}

function addToCartOkkes() {
    totalCost += 100.00;
    hasOkkes = true;
}

function costAllNewBooks() {
    for (let i = 0; i < books.length; i++) {
        books[i].ToCart();
        if (i >= 1) {
            totalCost -= books[i - 1].ToPrice();
        }
    }
    return totalCost;
}

function showCart() {
    var infoCart = "";
    if (hasWitcher) {
        infoCart += "<li><strong><u> Witcher </u></strong><br><strong>Price: </strong> 200.00 TL </li><br>";
    }
    if (hasLOTR) {
        infoCart += "<li><strong><u> Lord of the Rings </u></strong><br><strong>Price: </strong> 34.95 TL </li><br>";
    }
    if (hasHP) {
        infoCart += "<li><strong><u> Harry Potter </u></strong><br><strong>Price: </strong> 250.00 TL </li><br>";
    }
    if (hasSherlock) {
        infoCart += "<li><strong><u> Sherlock Holmes </u></strong><br><strong>Price: </strong> 21.45 TL </li><br>";
    }
    if (hasOkkes) {
        infoCart += "<li><strong><u> Ökkeş Serisi </u></strong><br><strong>Price: </strong> 100.00 TL </li><br>";
    }
    for (let i = 0; i < books.length; i++) {
        infoCart += "<li><strong><u>" + books[i].name +
            "</u></strong><br><strong>Price: </strong>" + books[i].price + " TL </li><br>";
    }
    infoCart += "<b>Total cost is:</b> " + Math.round(totalCost * 100) / 100 + " TL";
    return infoCart;
}