function check_all(){
var isbn = document.forms[1];
var i;
if (isbn[0].checked) 
for (i = 0; i < isbn.length; i++) 
  isbn[i].checked=true;
else
for (i = 0; i < isbn.length; i++) 
  isbn[i].checked=false;
}
