// Show right tab
function ngvOpenTab(evt, tabName, tabList) {
        
    let tabcontent = document.getElementsByClassName(tabList);
    let tablinks = document.getElementsByClassName(evt.currentTarget.classList[0]);

    for (let i = 0; i < tabcontent.length; i++) {

        tabcontent[i].style.display = "none";

    }
    
    for (let i = 0; i < tabcontent.length; i++) {

        tablinks[i].classList.remove("active");

    }

    document.getElementById(tabName).style.display = "block";
    

    evt.currentTarget.classList.add("active");
}

// Sets active tab and cookies
function mainTabs(){
    
    let first_tab = document.getElementById('firsttablink');
    let sec_tab = document.getElementById('sectablink');
    let third_tab = document.getElementById('thirdtablink');
    let forth_tab = document.getElementById('forthtablink');

    if ( document.cookie.indexOf('ng-gen') >= 0 ) {
        
        first_tab.click();
        document.cookie = "ng-gen";

    } else if ( document.cookie.indexOf('ng-manager') >= 0 ) {
        
        sec_tab.click();
        document.cookie = "ng-manager";

    } else if ( document.cookie.indexOf('ng-val') >= 0 ) {
        
        third_tab.click();
        document.cookie = "ng-val";
    
    } else if ( document.cookie.indexOf('ng-ac') >= 0 ) {
        
        forth_tab.click();
        document.cookie = "ng-ac";

    }
    
    else if ( document.cookie.indexOf('get_enterprise') >= 0 ) {
        
        forth_tab.click();
        document.getElementById('custom_message').innerHTML = '<h2 style="color:#4CAF50;">You need enterprise edition to create more then four lists.</h2>';
        document.cookie = "get_enterprise";
    
    } else {
        
        first_tab.click();

    }
}

jQuery(document).ready(function () {
    
    document.querySelectorAll('.gen-tab-links').forEach(element => {
        element.onclick = function(event) {
            ngvOpenTab(event, element.getAttribute('data-index'), element.getAttribute('data-target'));
        }
    });

    document.querySelectorAll('.short-tab-links').forEach(element => {
        element.onclick = function(event) {

            ngvOpenTab(event, element.getAttribute('data-index'), element.getAttribute('data-target'));

        }
    });

    document.querySelectorAll('.settings-tab-links').forEach(element => {
        element.onclick = function(event) {

            ngvOpenTab(event, element.getAttribute('data-index'), element.getAttribute('data-target'));

        }
    });

    document.querySelectorAll('.tablinks').forEach(element => {
        element.onclick = function(event) {
            document.cookie = element.getAttribute('data-index');
            ngvOpenTab(event, element.getAttribute('data-index'), element.getAttribute('data-target'));
        }
    });

    document.querySelectorAll('.back-to-storage').forEach(element => {
        element.onclick = function() {
            document.getElementById('sectablink').click();
        }
    });

    mainTabs();
    document.getElementById('first-gen-tab').click();
    document.getElementById('first-short-tab').click();
    document.getElementById('first-settings-tab').click();
    
});