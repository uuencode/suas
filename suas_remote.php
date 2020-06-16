<?php

// S.U.A.S. Screenshot - Upload - Annotate - Share
// https://github.com/uuencode/suas

// SETTINGS 

$uploadkey='SECURITYSTRINGNOSPACES'; // obvious?
$render121='false'; // render image without changing width/height: true or false in quotes
$saveimgas='99999999999999';

/* $saveimgas: new upload must be saved with a NUMERIC name!
   99xxxxxxxxxxxx - rawshot goes to the top of the list
   10xxxxxxxxxxxx - rawshot goes to the bottom of the list */

// ----

$markerjs=false; $htmtitle='Images';

// accepts uploads with name $uploadkey only
if(isset($_FILES[$uploadkey])){
	move_uploaded_file($_FILES[$uploadkey]['tmp_name'],$saveimgas.'.png');
	die();
}

// save rendered image
if(isset($_POST['mjsrc']) && isset($_POST['dtime']) && is_numeric($_POST['dtime'])){
 
 	$mjsrc=str_replace('data:image/png;base64,','',$_POST['mjsrc']);
 	$mjsrc=@base64_decode($mjsrc); 
 	$pfile=$_POST['dtime'].'.png';
 	file_put_contents($pfile,$mjsrc);

}

// delete; numeric filenames only
if(isset($_GET['del']) && is_numeric($_GET['del'])){ @unlink($_GET['del'].'.png'); }

// trigger loading markerJS | list of images
if(isset($_GET['markerjs']) && !isset($_POST['mjsrc']) && is_file($saveimgas.'.png')){ 
	$markerjs=true;
	$randnum=mt_rand(100000,999999);
	$htmtitle='Annotate...'; 
}

?>

<!DOCTYPE html>
<html lang="en"><head>
<meta charset=utf-8 />
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title><?php print $htmtitle;?></title>

<style>

a:link{color:#ccc;text-decoration:none}
a:visited{color:#ccc;text-decoration:none}
a:hover{color:#fff;text-decoration:none}
a:active{color:#fff;text-decoration:none}

body{padding-top:50px;text-align:center;color:#fff;background-color:#333;font-family:monospace;font-size:14px}
hr {clear:both;height:0;border-bottom:1px solid #a00;margin-top:20px}
#contn{display:inline-block;max-width:90%}
#xshot{max-width:1200px;width:100%;display:block;margin:auto}
#svbut{width:100%;padding:20px 0 20px 0;margin:auto;cursor:pointer;border-radius:5px;font-weight:700;background-color:#111;color:#fff;text-align:center;}

#shots{width:1200px;max-width:90%;margin:auto}
#shots div{display:inline-block;white-space:nowrap;padding:20px;background-color:#222;margin:10px;border-radius:20px 0 20px 0}
#shots a{display:inline-block;margin:0 10px 0 10px;cursor:pointer}
#shots img{float:left;width:80px;height:80px;margin-right:10px;cursor:pointer}

#hscrn{position:fixed;top:0;bottom:0;left:0;right:0;background-color:#222;padding-top:50px;}
#hscrn div{display:inline-block;border-radius:20px 0 20px 0;margin:1%;width:30%;height:30%;background-size:contain;background-repeat:no-repeat;background-position:center;background-color:#333;cursor:pointer}

</style>

<script src="https://unpkg.com/markerjs"></script>

</head>
<body>

<?php if($markerjs){?>

<div id="contn">
<img id="xshot" src="<?php print $saveimgas;?>.png?r=<?php print $randnum;?>" onclick="show_mj(this)" alt="screenshot" />
<br /><div id="svbut" onclick="f_upload()">SAVE</div>
</div>

<form method="post">
<input type="hidden" name="mjsrc" value="0" />
<input type="hidden" name="dtime" value="0" />
</form>

<div id="hscrn">
<div onclick="select_colors(1)" style="background-color:#333;background-image:url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cGF0aCBmaWxsPSIjMzMzIiBwYWludC1vcmRlcj0ic3Ryb2tlIGZpbGwgbWFya2VycyIgZD0iTTIgMmg5NnY5NkgyeiIvPjxwYXRoIGZpbGw9IiNFOTFFNjMiIHBhaW50LW9yZGVyPSJzdHJva2UgZmlsbCBtYXJrZXJzIiBkPSJNOC4xMTQgNDIuNzY4aDUwdjUwaC01MHoiLz48cGF0aCBkPSJNODEuNjc0IDcwLjk1NHEzLjY0IDAgNS4zODQgMS41NjYgMS43NDYgMS41NjYgMS43NDYgNC45OXYxMy4yOTVoLTIuMzc3bC0uNjMtMi43NjhoLS4xNXEtMS4zIDEuNjAyLTIuNzQ4IDIuMzY3LTEuNDEyLjc2NS0zLjkzNy43NjUtMi43MSAwLTQuNDk0LTEuMzg1LTEuNzg0LTEuNDItMS43ODQtNC40MDcgMC0yLjkxNCAyLjM0LTQuNDggMi4zNC0xLjYwMyA3LjIwNS0xLjc1bDMuMzgtLjEwOHYtMS4xNjZxMC0yLjQ0LTEuMDc3LTMuMzg3LTEuMDc3LS45NDctMy4wNDUtLjk0Ny0xLjU2IDAtMi45Ny40NzQtMS40MTIuNDM3LTIuNjM4IDEuMDJsLTEuMDAyLTIuNDA1cTEuMy0uNjkzIDMuMDgyLTEuMTY2IDEuNzgyLS41MSAzLjcxNC0uNTF6bTMuOSAxMC4zMDhsLTIuOTM1LjExcS0zLjcxNS4xNDUtNS4xNjMgMS4xNjUtMS40MSAxLjAyLTEuNDEgMi44NzcgMCAxLjY0IDEuMDAyIDIuNDA0IDEuMDM4Ljc2NSAyLjYzNS43NjUgMi41MjYgMCA0LjE5Ny0xLjM0OCAxLjY3LTEuMzg0IDEuNjctNC4yMjV6bS0xOC4wODcgOS41NDNsLTMuMTkzLTguMDVoLTEwLjUxbC0zLjE1NyA4LjA1aC0zLjM4TDU3LjYxIDY0LjY5aDMuMDA3TDcwLjk0IDkwLjgwNHpNNjMuMjkgNzkuODRsLTIuOTctNy44NjZxLS4xMS0uMjkyLS4zNy0xLjA1Ni0uMjYtLjc2NS0uNTItMS41NjctLjIyNC0uODM2LS4zNzMtMS4yNzMtLjI2IDEuMTMtLjU5NCAyLjIyLS4zMzQgMS4wNTgtLjU1NyAxLjY3N2wtMy4wMDggNy44Njd6bTMuMzE1LTczLjc5M2EyNy41IDI3LjUgMCAwIDAtMjcuMzggMjcuNSAyNy41IDI3LjUgMCAwIDAgMjcuNDk4IDI3LjUgMjcuNSAyNy41IDAgMCAwIDI3LjUtMjcuNSAyNy41IDI3LjUgMCAwIDAtMjcuNS0yNy41IDI3LjUgMjcuNSAwIDAgMC0uMTE4IDB6bS4xMTggNS41YTIyIDIyIDAgMCAxIDIyLjAwMiAyMiAyMiAyMiAwIDAgMS0yMi4wMDIgMjIgMjIgMjIgMCAwIDEtMjItMjIgMjIgMjIgMCAwIDEgMjItMjJ6IiBmaWxsPSIjZmZmIi8+PHBhdGggb3BhY2l0eT0iLjQ2MiIgcGFpbnQtb3JkZXI9InN0cm9rZSBmaWxsIG1hcmtlcnMiIGZpbGw9IiMwMDAiIGQ9Ik04LjExNCAxMS43NjhoNTB2MjVoLTUweiIvPjwvc3ZnPg==)"></div>
<div onclick="select_colors(2)" style="background-color:#333;background-image:url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cGF0aCBmaWxsPSIjMzMzIiBwYWludC1vcmRlcj0ic3Ryb2tlIGZpbGwgbWFya2VycyIgZD0iTTIgMmg5NnY5NkgyeiIvPjxwYXRoIGZpbGw9IiM5QzI3QjAiIHBhaW50LW9yZGVyPSJzdHJva2UgZmlsbCBtYXJrZXJzIiBkPSJNOC4xMTQgNDIuNzY4aDUwdjUwaC01MHoiLz48cGF0aCBkPSJNODEuNjc0IDcwLjk1NHEzLjY0IDAgNS4zODQgMS41NjYgMS43NDYgMS41NjYgMS43NDYgNC45OXYxMy4yOTVoLTIuMzc3bC0uNjMtMi43NjhoLS4xNXEtMS4zIDEuNjAyLTIuNzQ4IDIuMzY3LTEuNDEyLjc2NS0zLjkzNy43NjUtMi43MSAwLTQuNDk0LTEuMzg1LTEuNzg0LTEuNDItMS43ODQtNC40MDcgMC0yLjkxNCAyLjM0LTQuNDggMi4zNC0xLjYwMyA3LjIwNS0xLjc1bDMuMzgtLjEwOHYtMS4xNjZxMC0yLjQ0LTEuMDc3LTMuMzg3LTEuMDc3LS45NDctMy4wNDUtLjk0Ny0xLjU2IDAtMi45Ny40NzQtMS40MTIuNDM3LTIuNjM4IDEuMDJsLTEuMDAyLTIuNDA1cTEuMy0uNjkzIDMuMDgyLTEuMTY2IDEuNzgyLS41MSAzLjcxNC0uNTF6bTMuOSAxMC4zMDhsLTIuOTM1LjExcS0zLjcxNS4xNDUtNS4xNjMgMS4xNjUtMS40MSAxLjAyLTEuNDEgMi44NzcgMCAxLjY0IDEuMDAyIDIuNDA0IDEuMDM4Ljc2NSAyLjYzNS43NjUgMi41MjYgMCA0LjE5Ny0xLjM0OCAxLjY3LTEuMzg0IDEuNjctNC4yMjV6bS0xOC4wODcgOS41NDNsLTMuMTkzLTguMDVoLTEwLjUxbC0zLjE1NyA4LjA1aC0zLjM4TDU3LjYxIDY0LjY5aDMuMDA3TDcwLjk0IDkwLjgwNHpNNjMuMjkgNzkuODRsLTIuOTctNy44NjZxLS4xMS0uMjkyLS4zNy0xLjA1Ni0uMjYtLjc2NS0uNTItMS41NjctLjIyNC0uODM2LS4zNzMtMS4yNzMtLjI2IDEuMTMtLjU5NCAyLjIyLS4zMzQgMS4wNTgtLjU1NyAxLjY3N2wtMy4wMDggNy44Njd6bTMuMzE1LTczLjc5M2EyNy41IDI3LjUgMCAwIDAtMjcuMzggMjcuNSAyNy41IDI3LjUgMCAwIDAgMjcuNDk4IDI3LjUgMjcuNSAyNy41IDAgMCAwIDI3LjUtMjcuNSAyNy41IDI3LjUgMCAwIDAtMjcuNS0yNy41IDI3LjUgMjcuNSAwIDAgMC0uMTE4IDB6bS4xMTggNS41YTIyIDIyIDAgMCAxIDIyLjAwMiAyMiAyMiAyMiAwIDAgMS0yMi4wMDIgMjIgMjIgMjIgMCAwIDEtMjItMjIgMjIgMjIgMCAwIDEgMjItMjJ6IiBmaWxsPSIjZmZmIi8+PHBhdGggb3BhY2l0eT0iLjQ2MiIgcGFpbnQtb3JkZXI9InN0cm9rZSBmaWxsIG1hcmtlcnMiIGZpbGw9IiNGRkMxMDciIGQ9Ik04LjExNCAxMS43NjhoNTB2MjVoLTUweiIvPjwvc3ZnPg0K)"></div>
<div onclick="select_colors(3)" style="background-color:#333;background-image:url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cGF0aCBmaWxsPSIjMzMzIiBwYWludC1vcmRlcj0ic3Ryb2tlIGZpbGwgbWFya2VycyIgZD0iTTIgMmg5NnY5NkgyeiIvPjxwYXRoIGZpbGw9IiMwM0E5RjQiIHBhaW50LW9yZGVyPSJzdHJva2UgZmlsbCBtYXJrZXJzIiBkPSJNOC4xMTQgNDIuNzY4aDUwdjUwaC01MHoiLz48cGF0aCBkPSJNODEuNjc0IDcwLjk1NHEzLjY0IDAgNS4zODQgMS41NjYgMS43NDYgMS41NjYgMS43NDYgNC45OXYxMy4yOTVoLTIuMzc3bC0uNjMtMi43NjhoLS4xNXEtMS4zIDEuNjAyLTIuNzQ4IDIuMzY3LTEuNDEyLjc2NS0zLjkzNy43NjUtMi43MSAwLTQuNDk0LTEuMzg1LTEuNzg0LTEuNDItMS43ODQtNC40MDcgMC0yLjkxNCAyLjM0LTQuNDggMi4zNC0xLjYwMyA3LjIwNS0xLjc1bDMuMzgtLjEwOHYtMS4xNjZxMC0yLjQ0LTEuMDc3LTMuMzg3LTEuMDc3LS45NDctMy4wNDUtLjk0Ny0xLjU2IDAtMi45Ny40NzQtMS40MTIuNDM3LTIuNjM4IDEuMDJsLTEuMDAyLTIuNDA1cTEuMy0uNjkzIDMuMDgyLTEuMTY2IDEuNzgyLS41MSAzLjcxNC0uNTF6bTMuOSAxMC4zMDhsLTIuOTM1LjExcS0zLjcxNS4xNDUtNS4xNjMgMS4xNjUtMS40MSAxLjAyLTEuNDEgMi44NzcgMCAxLjY0IDEuMDAyIDIuNDA0IDEuMDM4Ljc2NSAyLjYzNS43NjUgMi41MjYgMCA0LjE5Ny0xLjM0OCAxLjY3LTEuMzg0IDEuNjctNC4yMjV6bS0xOC4wODcgOS41NDNsLTMuMTkzLTguMDVoLTEwLjUxbC0zLjE1NyA4LjA1aC0zLjM4TDU3LjYxIDY0LjY5aDMuMDA3TDcwLjk0IDkwLjgwNHpNNjMuMjkgNzkuODRsLTIuOTctNy44NjZxLS4xMS0uMjkyLS4zNy0xLjA1Ni0uMjYtLjc2NS0uNTItMS41NjctLjIyNC0uODM2LS4zNzMtMS4yNzMtLjI2IDEuMTMtLjU5NCAyLjIyLS4zMzQgMS4wNTgtLjU1NyAxLjY3N2wtMy4wMDggNy44Njd6bTMuMzE1LTczLjc5M2EyNy41IDI3LjUgMCAwIDAtMjcuMzggMjcuNSAyNy41IDI3LjUgMCAwIDAgMjcuNDk4IDI3LjUgMjcuNSAyNy41IDAgMCAwIDI3LjUtMjcuNSAyNy41IDI3LjUgMCAwIDAtMjcuNS0yNy41IDI3LjUgMjcuNSAwIDAgMC0uMTE4IDB6bS4xMTggNS41YTIyIDIyIDAgMCAxIDIyLjAwMiAyMiAyMiAyMiAwIDAgMS0yMi4wMDIgMjIgMjIgMjIgMCAwIDEtMjItMjIgMjIgMjIgMCAwIDEgMjItMjJ6IiBmaWxsPSIjRkY1NzIyIi8+PHBhdGggb3BhY2l0eT0iLjQ2MiIgcGFpbnQtb3JkZXI9InN0cm9rZSBmaWxsIG1hcmtlcnMiIGZpbGw9IiMwMDAwMDAiIGQ9Ik04LjExNCAxMS43NjhoNTB2MjVoLTUweiIvPjwvc3ZnPg==)"></div>
<div onclick="select_colors(4)" style="background-color:#eee;background-image:url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cGF0aCBmaWxsPSIjZWVlIiBwYWludC1vcmRlcj0ic3Ryb2tlIGZpbGwgbWFya2VycyIgZD0iTTIgMmg5NnY5NkgyeiIvPjxwYXRoIGZpbGw9IiM4QkMzNEEiIHBhaW50LW9yZGVyPSJzdHJva2UgZmlsbCBtYXJrZXJzIiBkPSJNOC4xMTQgNDIuNzY4aDUwdjUwaC01MHoiLz48cGF0aCBkPSJNODEuNjc0IDcwLjk1NHEzLjY0IDAgNS4zODQgMS41NjYgMS43NDYgMS41NjYgMS43NDYgNC45OXYxMy4yOTVoLTIuMzc3bC0uNjMtMi43NjhoLS4xNXEtMS4zIDEuNjAyLTIuNzQ4IDIuMzY3LTEuNDEyLjc2NS0zLjkzNy43NjUtMi43MSAwLTQuNDk0LTEuMzg1LTEuNzg0LTEuNDItMS43ODQtNC40MDcgMC0yLjkxNCAyLjM0LTQuNDggMi4zNC0xLjYwMyA3LjIwNS0xLjc1bDMuMzgtLjEwOHYtMS4xNjZxMC0yLjQ0LTEuMDc3LTMuMzg3LTEuMDc3LS45NDctMy4wNDUtLjk0Ny0xLjU2IDAtMi45Ny40NzQtMS40MTIuNDM3LTIuNjM4IDEuMDJsLTEuMDAyLTIuNDA1cTEuMy0uNjkzIDMuMDgyLTEuMTY2IDEuNzgyLS41MSAzLjcxNC0uNTF6bTMuOSAxMC4zMDhsLTIuOTM1LjExcS0zLjcxNS4xNDUtNS4xNjMgMS4xNjUtMS40MSAxLjAyLTEuNDEgMi44NzcgMCAxLjY0IDEuMDAyIDIuNDA0IDEuMDM4Ljc2NSAyLjYzNS43NjUgMi41MjYgMCA0LjE5Ny0xLjM0OCAxLjY3LTEuMzg0IDEuNjctNC4yMjV6bS0xOC4wODcgOS41NDNsLTMuMTkzLTguMDVoLTEwLjUxbC0zLjE1NyA4LjA1aC0zLjM4TDU3LjYxIDY0LjY5aDMuMDA3TDcwLjk0IDkwLjgwNHpNNjMuMjkgNzkuODRsLTIuOTctNy44NjZxLS4xMS0uMjkyLS4zNy0xLjA1Ni0uMjYtLjc2NS0uNTItMS41NjctLjIyNC0uODM2LS4zNzMtMS4yNzMtLjI2IDEuMTMtLjU5NCAyLjIyLS4zMzQgMS4wNTgtLjU1NyAxLjY3N2wtMy4wMDggNy44Njd6bTMuMzE1LTczLjc5M2EyNy41IDI3LjUgMCAwIDAtMjcuMzggMjcuNSAyNy41IDI3LjUgMCAwIDAgMjcuNDk4IDI3LjUgMjcuNSAyNy41IDAgMCAwIDI3LjUtMjcuNSAyNy41IDI3LjUgMCAwIDAtMjcuNS0yNy41IDI3LjUgMjcuNSAwIDAgMC0uMTE4IDB6bS4xMTggNS41YTIyIDIyIDAgMCAxIDIyLjAwMiAyMiAyMiAyMiAwIDAgMS0yMi4wMDIgMjIgMjIgMjIgMCAwIDEtMjItMjIgMjIgMjIgMCAwIDEgMjItMjJ6IiBmaWxsPSIjMDAwIi8+PHBhdGggb3BhY2l0eT0iLjQ2MiIgcGFpbnQtb3JkZXI9InN0cm9rZSBmaWxsIG1hcmtlcnMiIGZpbGw9IiMwMDAiIGQ9Ik04LjExNCAxMS43NjhoNTB2MjVoLTUweiIvPjwvc3ZnPg==)"></div>
<div onclick="select_colors(5)" style="background-color:#eee;background-image:url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cGF0aCBmaWxsPSIjZWVlIiBwYWludC1vcmRlcj0ic3Ryb2tlIGZpbGwgbWFya2VycyIgZD0iTTIgMmg5NnY5NkgyeiIvPjxwYXRoIGZpbGw9IiNGRkMxMDciIHBhaW50LW9yZGVyPSJzdHJva2UgZmlsbCBtYXJrZXJzIiBkPSJNOC4xMTQgNDIuNzY4aDUwdjUwaC01MHoiLz48cGF0aCBkPSJNODEuNjc0IDcwLjk1NHEzLjY0IDAgNS4zODQgMS41NjYgMS43NDYgMS41NjYgMS43NDYgNC45OXYxMy4yOTVoLTIuMzc3bC0uNjMtMi43NjhoLS4xNXEtMS4zIDEuNjAyLTIuNzQ4IDIuMzY3LTEuNDEyLjc2NS0zLjkzNy43NjUtMi43MSAwLTQuNDk0LTEuMzg1LTEuNzg0LTEuNDItMS43ODQtNC40MDcgMC0yLjkxNCAyLjM0LTQuNDggMi4zNC0xLjYwMyA3LjIwNS0xLjc1bDMuMzgtLjEwOHYtMS4xNjZxMC0yLjQ0LTEuMDc3LTMuMzg3LTEuMDc3LS45NDctMy4wNDUtLjk0Ny0xLjU2IDAtMi45Ny40NzQtMS40MTIuNDM3LTIuNjM4IDEuMDJsLTEuMDAyLTIuNDA1cTEuMy0uNjkzIDMuMDgyLTEuMTY2IDEuNzgyLS41MSAzLjcxNC0uNTF6bTMuOSAxMC4zMDhsLTIuOTM1LjExcS0zLjcxNS4xNDUtNS4xNjMgMS4xNjUtMS40MSAxLjAyLTEuNDEgMi44NzcgMCAxLjY0IDEuMDAyIDIuNDA0IDEuMDM4Ljc2NSAyLjYzNS43NjUgMi41MjYgMCA0LjE5Ny0xLjM0OCAxLjY3LTEuMzg0IDEuNjctNC4yMjV6bS0xOC4wODcgOS41NDNsLTMuMTkzLTguMDVoLTEwLjUxbC0zLjE1NyA4LjA1aC0zLjM4TDU3LjYxIDY0LjY5aDMuMDA3TDcwLjk0IDkwLjgwNHpNNjMuMjkgNzkuODRsLTIuOTctNy44NjZxLS4xMS0uMjkyLS4zNy0xLjA1Ni0uMjYtLjc2NS0uNTItMS41NjctLjIyNC0uODM2LS4zNzMtMS4yNzMtLjI2IDEuMTMtLjU5NCAyLjIyLS4zMzQgMS4wNTgtLjU1NyAxLjY3N2wtMy4wMDggNy44Njd6bTMuMzE1LTczLjc5M2EyNy41IDI3LjUgMCAwIDAtMjcuMzggMjcuNSAyNy41IDI3LjUgMCAwIDAgMjcuNDk4IDI3LjUgMjcuNSAyNy41IDAgMCAwIDI3LjUtMjcuNSAyNy41IDI3LjUgMCAwIDAtMjcuNS0yNy41IDI3LjUgMjcuNSAwIDAgMC0uMTE4IDB6bS4xMTggNS41YTIyIDIyIDAgMCAxIDIyLjAwMiAyMiAyMiAyMiAwIDAgMS0yMi4wMDIgMjIgMjIgMjIgMCAwIDEtMjItMjIgMjIgMjIgMCAwIDEgMjItMjJ6IiBmaWxsPSIjMDAwIi8+PHBhdGggb3BhY2l0eT0iLjQ2MiIgcGFpbnQtb3JkZXI9InN0cm9rZSBmaWxsIG1hcmtlcnMiIGZpbGw9IiMwMDAiIGQ9Ik04LjExNCAxMS43NjhoNTB2MjVoLTUweiIvPjwvc3ZnPg==)"></div>
<div onclick="select_colors(6)" style="background-color:#eee;background-image:url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cGF0aCBmaWxsPSIjZWVlIiBwYWludC1vcmRlcj0ic3Ryb2tlIGZpbGwgbWFya2VycyIgZD0iTTIgMmg5NnY5NkgyeiIvPjxwYXRoIGZpbGw9IiMyMjIyMjIiIHBhaW50LW9yZGVyPSJzdHJva2UgZmlsbCBtYXJrZXJzIiBkPSJNOC4xMTQgNDIuNzY4aDUwdjUwaC01MHoiLz48cGF0aCBkPSJNODEuNjc0IDcwLjk1NHEzLjY0IDAgNS4zODQgMS41NjYgMS43NDYgMS41NjYgMS43NDYgNC45OXYxMy4yOTVoLTIuMzc3bC0uNjMtMi43NjhoLS4xNXEtMS4zIDEuNjAyLTIuNzQ4IDIuMzY3LTEuNDEyLjc2NS0zLjkzNy43NjUtMi43MSAwLTQuNDk0LTEuMzg1LTEuNzg0LTEuNDItMS43ODQtNC40MDcgMC0yLjkxNCAyLjM0LTQuNDggMi4zNC0xLjYwMyA3LjIwNS0xLjc1bDMuMzgtLjEwOHYtMS4xNjZxMC0yLjQ0LTEuMDc3LTMuMzg3LTEuMDc3LS45NDctMy4wNDUtLjk0Ny0xLjU2IDAtMi45Ny40NzQtMS40MTIuNDM3LTIuNjM4IDEuMDJsLTEuMDAyLTIuNDA1cTEuMy0uNjkzIDMuMDgyLTEuMTY2IDEuNzgyLS41MSAzLjcxNC0uNTF6bTMuOSAxMC4zMDhsLTIuOTM1LjExcS0zLjcxNS4xNDUtNS4xNjMgMS4xNjUtMS40MSAxLjAyLTEuNDEgMi44NzcgMCAxLjY0IDEuMDAyIDIuNDA0IDEuMDM4Ljc2NSAyLjYzNS43NjUgMi41MjYgMCA0LjE5Ny0xLjM0OCAxLjY3LTEuMzg0IDEuNjctNC4yMjV6bS0xOC4wODcgOS41NDNsLTMuMTkzLTguMDVoLTEwLjUxbC0zLjE1NyA4LjA1aC0zLjM4TDU3LjYxIDY0LjY5aDMuMDA3TDcwLjk0IDkwLjgwNHpNNjMuMjkgNzkuODRsLTIuOTctNy44NjZxLS4xMS0uMjkyLS4zNy0xLjA1Ni0uMjYtLjc2NS0uNTItMS41NjctLjIyNC0uODM2LS4zNzMtMS4yNzMtLjI2IDEuMTMtLjU5NCAyLjIyLS4zMzQgMS4wNTgtLjU1NyAxLjY3N2wtMy4wMDggNy44Njd6bTMuMzE1LTczLjc5M2EyNy41IDI3LjUgMCAwIDAtMjcuMzggMjcuNSAyNy41IDI3LjUgMCAwIDAgMjcuNDk4IDI3LjUgMjcuNSAyNy41IDAgMCAwIDI3LjUtMjcuNSAyNy41IDI3LjUgMCAwIDAtMjcuNS0yNy41IDI3LjUgMjcuNSAwIDAgMC0uMTE4IDB6bS4xMTggNS41YTIyIDIyIDAgMCAxIDIyLjAwMiAyMiAyMiAyMiAwIDAgMS0yMi4wMDIgMjIgMjIgMjIgMCAwIDEtMjItMjIgMjIgMjIgMCAwIDEgMjItMjJ6IiBmaWxsPSIjREMwMjRDIi8+PHBhdGggb3BhY2l0eT0iLjQ2MiIgcGFpbnQtb3JkZXI9InN0cm9rZSBmaWxsIG1hcmtlcnMiIGZpbGw9IiNmZmYiIGQ9Ik04LjExNCAxMS43NjhoNTB2MjVoLTUweiIvPjwvc3ZnPg==)"></div>
<br />&nbsp;<br /> Pick foreground colors that suit your image. </div>

<script>

settings = {markerColors:{mainColor:'#fff', highlightColor:'#000', coverColor:'#a00'},strokeWidth:'15'}

function de(x){return document.getElementById(x)}

function show_mj(img) {
	let mark = new markerjs.MarkerArea(img,settings);
	mark.show((dataUrl) => {img.src = dataUrl})
}

function f_upload(){
	if(de('xshot').src.length<200){
		alert('Render with ✓ first!');
		return
	}
	document.forms[0].mjsrc.value=de('xshot').src
	document.forms[0].dtime.value=dtime_fname()
	document.forms[0].submit()
}

function dtime_fname(t){
	d = new Date();
	mh =  d.getMonth()+1; mh="0"+mh; mh=mh.substr(-2)
	dt='0'+d.getDate(); dt=dt.substr(-2)
	hr='0'+d.getHours(); hr=hr.substr(-2)
	mn='0'+d.getMinutes(); mn=mn.substr(-2)
	sc='0'+d.getSeconds(); sc=sc.substr(-2)
	return d.getFullYear()+mh+dt+hr+mn+sc
}

function select_colors(x){
	switch(x){
		case 1: settings = {renderAtNaturalSize:<?php print $render121;?>,markerColors:{mainColor:'#ffffff', highlightColor:'#000000', coverColor:'#E91E63'},strokeWidth:'15'} ;break
		case 2: settings = {renderAtNaturalSize:<?php print $render121;?>,markerColors:{mainColor:'#ffffff', highlightColor:'#FFC107', coverColor:'#9C27B0'},strokeWidth:'15'} ;break
		case 3: settings = {renderAtNaturalSize:<?php print $render121;?>,markerColors:{mainColor:'#FF5722', highlightColor:'#000000', coverColor:'#03A9F4'},strokeWidth:'15'} ;break
		case 4: settings = {renderAtNaturalSize:<?php print $render121;?>,markerColors:{mainColor:'#000000', highlightColor:'#000000', coverColor:'#8BC34A'},strokeWidth:'15'} ;break
		case 5: settings = {renderAtNaturalSize:<?php print $render121;?>,markerColors:{mainColor:'#000000', highlightColor:'#000000', coverColor:'#FFC107'},strokeWidth:'15'} ;break
		case 6: settings = {renderAtNaturalSize:<?php print $render121;?>,markerColors:{mainColor:'#DC024C', highlightColor:'#000000', coverColor:'#222222'},strokeWidth:'15'} ;break
		default:break;
	}
	de('hscrn').style.display='none'
	show_mj(de('xshot'))
}

</script>

<?php } else{

print '<div id="shots">';

// list and sort Z-A a list of PNG files
$pngs = glob('*.png'); rsort($pngs);

foreach($pngs as $fname){

	$fsize=round(@filesize($fname)/1024,2);
	$fsize=$fsize.'kB';

	$sname=str_replace('.png','',$fname);
	if($sname==$saveimgas){
		$sname='<span style="color:#E91E63">LATEST RAWSHOT<br /><small>'.$fsize.'</small></span>';}
	else{
		$sname=$sname.'<br /><small>'.$fsize.'</small>';
	}

	print '<div><img onclick="self.location.href=\''.$fname.'\'" src="'.$fname.'" alt="" /><a href="'.$fname.'">'.$sname.'</a>';
	print '<br /><br />';
	print '<a href="'.$fname.'" onclick="if(location.protocol.search(\'https\')>-1){navigator.clipboard.writeText(this.href)}else{prompt(\'URL\',this.href)};this.style.color=\'#666\';return false">Copy</a>';
	print '<a onclick="if(confirm(\'Confirm delete!\')){self.location.href=\'?del=\'+parseInt(\''.$fname.'\')} return false">Delete</a>';
	print '</div>';
}

print '<br style="clear:both" /></div>';

if(is_file($saveimgas.'.png')){
	print '<br />&nbsp;<br /><a href="?markerjs">Annotate the latest rawshot?</a>';
}

if(count($pngs)<1){
	print '<br />&nbsp;<br />No images in the list...';
}

}?>

</body>
</html>
