<?php
    session_start();
    
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: customer_login.php");
        exit;
    }
?>
<!doctype html>
<html>

<head>
    <title> Book Store </title>
    <link rel="stylesheet" href="myStyle.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
</head>
<style>
    body{
        font: 14px sans-serif; text-align: center;
    }
    .welcome-message{
        font-size: 44px;
        background-color:#353635;
        padding: 40px 0;
        font-family: Bahnschrift;
        color: #f9d423;
    }
</style>

<body>
<header>
        <nav class="welcome-message"> e-Shopping Site for Books <br><br>
        Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b><br> Welcome to our site. <br>
        <a href="customer_logout.php" class="btn btn-danger">Log out</a>
        <a href="customer_profile.php" class="btn btn-danger">Profile</a>
        </nav>
    </header>

    <script src="js_Function.js"></script>

    <section>
        <div class="container">
            <div class="top_left">
                <div class="picture">
                    <h4>Add a Book</h4>
                    <form id="addbookform">
                        <label for="image">Upload Image:</label><br>
                        <input type="file" id="image_file" name="image" accept="image/*"><br>
                        <label for="bookname">Book Name:</label><br>
                        <input type="text" id="bookname" name="bookname"><br>
                        <label for="brief">Brief Info:</label><br>
                        <input type="text" id="brief" name="brief"><br>
                        <label for="price">Price:</label><br>
                        <input type="text" id="price" name="price">
                    </form>

                    <button type="button" onclick="checkError(document.getElementById('price'))">Check Error</button>
                    <button type="button" onclick="clearForm()">Clear</button> <br>
                    <button type="button" onclick="saveBook(document.getElementById('image_file').files[0],
                    document.getElementById('bookname').value, document.getElementById('brief').value,
                    document.getElementById('price').value)">Save Book</button>
                    <button type="button" onclick="getElementById('listbooks').innerHTML = listBooks()">List
                        Books</button>

                </div>
            </div>
            <div class="right">
                <div id="books" class="info">
                    <p id="witcher">
                        <img src="Book Images\witcher_7books.png" width="100" /> <br>
                        <strong><u> Witcher </u></strong><br>
                        This book is about Geralt of Rivia and it is writed by Andrei Sapkowski. <br>
                        <strong>Price: </strong> 200.00 TL <br>
                        <button type="button" id="add_witcher" onclick="addToCartWitcher()">Add to Cart</button>
                    </p> <br>

                    <p id="lotr">
                        <img src="Book Images\lotr.png" width="100" /> <br>
                        <strong><u> Lord of the Rings </u></strong><br>
                        This book is about middle earth and it is written by Tolkien. <br>
                        <strong>Price: </strong> 39.95 TL <br>
                        <button type="button" id="add_lotr" onclick="addToCartLOTR()">Add to Cart</button>
                    </p> <br>

                    <p id="hp">
                        <img src="Book Images\harrypotter_all.png" width="100" /> <br>
                        <strong><u> Harry Potter </u></strong><br>
                        This book is about a student of Hogwarts and his name is 'Harry Potter'.<br>
                        The book is written by J.K. Rowling. <br>
                        <strong>Price: </strong> 250.00 TL <br>
                        <button type="button" id="add_hp" onclick="addToCartHP()">Add to Cart</button>
                    </p> <br>

                    <p id="sherlock">
                        <img src="Book Images\sherlock.png" width="100" /> <br>
                        <strong><u> Sherlock Holmes </u></strong><br>
                        This book is about detective Sherlock and it is writtten by Sir Arthur Conan Doyle.<br>
                        <strong>Price: </strong> 21.45 TL <br>
                        <button type="button" id="add_sherlock" onclick="addToCartSherlock()">Add to Cart</button>
                    </p> <br>

                    <p id="okkes">
                        <img src="Book Images\okkes_serisi.png" width="100" /> <br>
                        <strong><u> Ökkeş Serisi </u></strong><br>
                        This book is about a Turkish child 'Ökkeş' and it is writed by Muzaffer İzgü. <br>
                        <u>Note: <i>This book series is not English!</i></u><br>
                        <strong>Price: </strong> 100.00 TL <br>
                        <button type="button" id="add_okkes" onclick="addToCartOkkes()">Add to Cart</button>
                    </p> <br>
                    <p id="listbooks"></p>
                </div>
            </div>
            <div class="bottom_left">
                <h2>
                    <strong><b> Cart </b></strong>
                    <button type="button" id="show_cart"
                        onclick="document.getElementById('cart').innerHTML = showCart()">Show Cart</button>
                </h2>

                <p id="cart"></p>
                <form id="choose" name="choose">
                    <input type="radio" name="rads" value="continue">Continue Shopping<br>
                    <input type="radio" name="rads" value="purchase!">Purchase!
                </form>
                <p id="address"></p>
                <p id="paymentmethod"></p>
            </div>
        </div>

        <div class="clearfix"></div>
    </section>

    <footer>
        <div class="container-footer">
            <p style="text-align: center;">HOMEWORK #1</p>
        </div>
    </footer>
</body>

<script>
    document.choose.onclick = function () {
        var radVal = document.choose.rads.value;
        address.innerHTML = "You selected: " + radVal;

        if (radVal == "purchase!") {
            address.innerHTML += "<br><br><br>Please write your address:"

            var addr = document.createElement("textarea");
            addr.rows = 4, addr.cols = 30, addr.name = "addressInput", addr.innerText = "Enter your address!";
            document.getElementById('address').appendChild(addr);

            address.innerHTML += "<br><br> Choose your payment method:";

            var paymentChoose = document.createElement("form");
            paymentChoose.id = "pays", paymentChoose.name = "pays";

            var label_wire = document.createElement("label");
            var wire = document.createElement("input");
            wire.type = "radio", wire.name = "payment", wire.value = "Wire Transfer";
            label_wire.appendChild(wire);
            label_wire.appendChild(document.createTextNode("Wire Transfer"));

            var label_credit = document.createElement("label");
            var credit = document.createElement("input");
            credit.type = "radio", credit.name = "payment", credit.value = "Credit Card";
            label_credit.appendChild(credit);
            label_credit.appendChild(document.createTextNode("Credit Card"));

            paymentChoose.appendChild(label_wire);
            paymentChoose.innerHTML += "<br>";
            paymentChoose.appendChild(label_credit);

            document.getElementById('address').appendChild(paymentChoose);

            document.getElementById('pays').onclick = function () {
                var paymentVal = document.getElementById('pays').payment.value;

                paymentmethod.innerHTML = "<br>Your payment method is: <b>" + paymentVal +
                    "</b> and books will be shipped to your address<br><br>" +
                    "<img src='Book Images/thanks.png' width='100'><br><b>Enjoy with new books!</b>";
            }
        }
    }
</script>

</html>