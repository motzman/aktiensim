<?php
$conn = mysqli_connect("localhost", "root", "", "aktiensim");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


