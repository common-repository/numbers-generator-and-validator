"use strict";function ngvCopySelected(a){let b=document.getElementById(a);console.log(b),b.select(),document.execCommand("copy")}// Generates numbers
function ngvGeneratorFunction(){var a=Math.floor,b=Math.abs;let c=document.getElementById("quantity"),d=document.getElementById("firstnumber"),e=document.getElementById("lastnumber"),f=document.getElementById("show-numbers"),g=b(d.value),h=b(e.value),i=b(c.value),j=document.getElementById("sort"),k=j.options[j.selectedIndex].value,l=h-g;// Rules
if(c.style.border="unset",d.style.border="unset",e.style.border="unset",f.innerHTML="",g>h)return d.style.border="2px solid #c41212",f.innerHTML="The first number is bigger then the last number!",!1;if(g==h)return d.style.border="2px solid #c41212",e.style.border="2px solid #c41212",f.innerHTML="The first number is the same as the last number!",!1;l<i&&(f.innerHTML="The quantity of numbers you want is higher then the range diffrence!<br>The range diffrence is: "+l,c.value=l,i=l),1>i&&(document.getElementById("show-numbers").innerHTML="Quantity cant be empty, using all available numbers!",c.value=l,i=l);let m=[],n=[],o=0,p="";// Main generator
for(;o<i;)p=a(Math.random()*(h-g+1))+g,n[p]||(m[o]=n[p]=p,o+=1);// Check if sort is specificed
1==k&&m.sort(function(c,a){return c-a}),document.getElementById("show-numbers").innerHTML=m.join(" ")}function generateASerial(a,b,c,d,e){var f=Math.floor;let g="";for(let h=0;h<a;h++){let a=f(Math.random()*b.length),h="a";if(!c)h=d?b[a].toLowerCase():b[a].toLowerCase();else if(!d)h=b[a].toUpperCase();else{let c=f(2*Math.random());h=0===c?b[a].toUpperCase():b[a].toLowerCase()}if(e){let a=f(2*Math.random());0===a&&(h=f(10*Math.random()))}g+=h}return g}function searchForDublicates(a,b){let c=a[b];for(let d=b+1;d<a.length;d++)if(c===a[d])return!0;return!1}// Generates serials
function ngvSerialFunction(){let a=document.querySelectorAll(".serial-charset-input"),b=0,c=["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"],d=document.getElementById("show-serials"),e=!1,f=!1,g=!1,h=0,j=[];d.innerHTML="";// Check if first input is checked else return false
for(let c=0;c<a.length;c++){if("charset"===a[c].getAttribute("data")&&!1===a[c].checked)return d.innerHTML="Select at least one option",!1;// If digits value is smaller then 1 return false
if("length"===a[c].getAttribute("data")){if(1>a[c].value)return!1;b=a[c].value}// If quantity value is smaller then 1 return false
if("qty"===a[c].getAttribute("data")){if(1>a[c].value)return!1;h=a[c].value}"uppercase"===a[c].getAttribute("data")&&(e=a[c].checked),"lowercase"===a[c].getAttribute("data")&&(f=a[c].checked),"digits"===a[c].getAttribute("data")&&(g=a[c].checked)}// Add serials to list
for(let a=0;a<h;a++)j.push(generateASerial(b,c,e,f,g));// Search for dublicates
for(let a=0;a<j.length;a++)if(searchForDublicates(j,a)){let i=!0,k=0;for(;i;){if(1e3<k)return d.innerHTML="Could not generate "+h+" unique serials. Either you change the serial options or decrease the quantity.",!1;k+=1,j[a]=generateASerial(b,c,e,f,g),searchForDublicates(j,a)||(i=!1)}}d.innerHTML=j.join(" ")}// Hides checkboxes if charset is not checked
function checkboxControl(){let a=document.querySelectorAll(".serial-charset-input"),b=!1;for(let c,d=0;d<a.length;d++)c=a[d],"charset"===c.getAttribute("data")?(c.onclick=function(){checkboxControl()},c.checked?(c.style.border="unset",b=!0):c.style.border="2px solid #c41212"):"charset"!==c.getAttribute("data")&&(b?c.disabled=!1:c.disabled=!0);return b}window.onload=function(){document.getElementById("gen-numbers-button").onclick=function(){ngvGeneratorFunction()},document.getElementById("gen-serials-button").onclick=function(){ngvSerialFunction()},document.getElementById("cpy-numbers-button").onclick=function(){ngvCopySelected("show-numbers")},document.getElementById("cpy-serials-button").onclick=function(){ngvCopySelected("show-serials")},checkboxControl()};