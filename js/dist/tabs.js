"use strict";// Show right tab
function ngvOpenTab(a,b,c){let d=document.getElementsByClassName(c),e=document.getElementsByClassName(a.currentTarget.classList[0]);for(let e=0;e<d.length;e++)d[e].style.display="none";for(let f=0;f<d.length;f++)e[f].classList.remove("active");document.getElementById(b).style.display="block",a.currentTarget.classList.add("active")}// Sets active tab and cookies
function mainTabs(){let a=document.getElementById("firsttablink"),b=document.getElementById("sectablink"),c=document.getElementById("thirdtablink"),d=document.getElementById("forthtablink");0<=document.cookie.indexOf("ng-gen")?(a.click(),document.cookie="ng-gen"):0<=document.cookie.indexOf("ng-manager")?(b.click(),document.cookie="ng-manager"):0<=document.cookie.indexOf("ng-val")?(c.click(),document.cookie="ng-val"):0<=document.cookie.indexOf("ng-ac")?(d.click(),document.cookie="ng-ac"):0<=document.cookie.indexOf("get_enterprise")?(d.click(),document.getElementById("custom_message").innerHTML="<h2 style=\"color:#4CAF50;\">You need enterprise edition to create more then four lists.</h2>",document.cookie="get_enterprise"):a.click()}jQuery(document).ready(function(){document.querySelectorAll(".gen-tab-links").forEach(a=>{a.onclick=function(b){ngvOpenTab(b,a.getAttribute("data-index"),a.getAttribute("data-target"))}}),document.querySelectorAll(".short-tab-links").forEach(a=>{a.onclick=function(b){ngvOpenTab(b,a.getAttribute("data-index"),a.getAttribute("data-target"))}}),document.querySelectorAll(".settings-tab-links").forEach(a=>{a.onclick=function(b){ngvOpenTab(b,a.getAttribute("data-index"),a.getAttribute("data-target"))}}),document.querySelectorAll(".tablinks").forEach(a=>{a.onclick=function(b){document.cookie=a.getAttribute("data-index"),ngvOpenTab(b,a.getAttribute("data-index"),a.getAttribute("data-target"))}}),document.querySelectorAll(".back-to-storage").forEach(a=>{a.onclick=function(){document.getElementById("sectablink").click()}}),mainTabs(),document.getElementById("first-gen-tab").click(),document.getElementById("first-short-tab").click(),document.getElementById("first-settings-tab").click()});