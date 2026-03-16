<?php include("header.php"); ?>

<style>

.section{
width:100%;
height:90vh;
display:flex;
align-items:center;
justify-content:center;
overflow:hidden;
}

.section img{
width:100%;
height:100%;
object-fit:cover;
transition:transform 6s ease;
}

.section:hover img{
transform:scale(1.1);
}

/* DIFFERENT BACKGROUND EFFECT */

.section:nth-child(odd){
background:#f4f6f9;
}

</style>


<!-- IMAGE SECTION 1 -->

<section class="section">

<img src="images/image1.png">

</section>


<!-- IMAGE SECTION 2 -->

<section class="section">

<img src="images/image2.png">

</section>


<!-- IMAGE SECTION 3 -->

<section class="section">

<img src="images/image3.png">

</section>


<?php include("footer.php"); ?>