<?php

include 'baglanti.php';

include 'function.php';

if(!admin_girisimi())
{
	header('location:admingiris.php');
}


include 'header.php';

?>

<?php

include 'footer.php';

?>